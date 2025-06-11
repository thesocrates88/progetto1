<?php

namespace App\Http\Controllers;

use App\Models\{Station, Train, SubTratta, Ticket, PendingPayment};
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Http;
use App\Services\TicketPricingService;
use Illuminate\Support\Facades\Log;


class TicketController extends Controller
{
    protected TicketPricingService $pricing;

    public function __construct(TicketPricingService $pricing)
    {
        $this->pricing = $pricing;
    }

    /**
     * Mostra il form di ricerca per i biglietti.
     */
    public function create()
    {
        $stations = Station::orderBy('order')->get();
        return view('tickets.buy', compact('stations'));
    }

    /**
     * Esegue la ricerca dei treni disponibili per la tratta e data selezionata.
     */
    public function search(Request $request)
    {
        $request->validate([
            'departure_station_id' => 'required|exists:stations,id',
            'arrival_station_id' => 'required|exists:stations,id|different:departure_station_id',
            'date' => 'required|date|after_or_equal:today',
        ]);

        return redirect()->route('ticket.results', [
            'departure_station_id' => $request->departure_station_id,
            'arrival_station_id' => $request->arrival_station_id,
            'date' => Carbon::parse($request->date)->toDateString(),
        ]);
    }

    /**
     * Visualizza i risultati della ricerca treni (GET)
     */
    public function results(Request $request)
    {
        $departureStationId = $request->departure_station_id;
        $arrivalStationId = $request->arrival_station_id;
        $date = Carbon::parse($request->date)->toDateString();

        // Prendo tutti i treni attivi nella data richiesta
        $trainIds = Train::whereDate('date', $date)->pluck('id');

        // Prendo tutte le subtratte solo per quei treni
        $subtratteByTrain = SubTratta::whereIn('train_id', $trainIds)
            ->orderBy('train_id')
            ->orderBy('order')
            ->get()
            ->groupBy('train_id');

        $validTrainIds = [];

        // Per ogni treno, verifica che prima compaia la stazione di partenza e poi quella di arrivo
        foreach ($subtratteByTrain as $trainId => $subtratte) {
            $foundFrom = false;

            foreach ($subtratte as $sub) {
                if ($sub->from_station_id == $departureStationId) {
                    $foundFrom = true;
                }

                if ($foundFrom && $sub->to_station_id == $arrivalStationId) {
                    $validTrainIds[] = $trainId;
                    break;
                }
            }
        }

        // Recupera i treni validi
        $trains = Train::whereIn('id', $validTrainIds)
            ->with(['departureStation', 'arrivalStation', 'subtratte'])
            ->orderBy('departure_time')
            ->get();

        // Calcolo prezzo
        $priceData = $this->pricing->getPriceAndDistance($departureStationId, $arrivalStationId);
        foreach ($trains as $train) {
            $train->sub_km = $priceData['km'];
            $train->sub_cost = $priceData['cost'];
        }

        $stations = Station::orderBy('order')->get();

        return view('tickets.buy', compact('stations', 'trains'))
            ->with([
                'selectedDeparture' => $departureStationId,
                'selectedArrival' => $arrivalStationId,
                'selectedDate' => $date,
            ]);
    }

    /**
     * Mostra il riepilogo prima del pagamento
     */
    public function checkout(Request $request, Train $train)
    {
        $request->validate([
            'from_station_id' => 'required|exists:stations,id',
            'to_station_id' => 'required|exists:stations,id',
            'date' => 'required|date',
            'posti' => 'required|array',
        ]);

        $postiSelezionati = collect($request->posti)->flatMap(fn($posti, $carrozzaId) =>
            collect($posti)->map(fn($numero) => [
                'rolling_stock_id' => $carrozzaId,
                'numero_posto' => $numero,
            ])
        );

        $price = $this->pricing->getPriceAndDistance($request->from_station_id, $request->to_station_id);

        return view('tickets.checkout', [
            'train' => $train,
            'posti' => $postiSelezionati,
            'from' => $request->from_station_id,
            'to' => $request->to_station_id,
            'date' => $request->date,
            'cost' => $price['cost'],
            'total' => $postiSelezionati->count() * $price['cost'],
        ]);
    }

    /**
     * Salva i biglietti acquistati.
     */


    public function purchase(Request $request, Train $train)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'surname' => 'required|string|max:255',
            'from_station_id' => 'required|exists:stations,id',
            'to_station_id' => 'required|exists:stations,id|different:from_station_id',
            'date' => 'required|date|after_or_equal:today',
            'posti' => 'required|array',
        ]);

        $from = $request->from_station_id;
        $to = $request->to_station_id;
        $user = $request->user();
        $posti = $request->posti;

        $distance = abs(Station::find($to)->kilometer - Station::find($from)->kilometer);
        $cost = $distance;
        $totalAmount = $cost * collect($posti)->flatten()->count();

        $transactionId = uniqid('tx_');

        // Salvataggio dati temporanei in sessione - deprecato perchè uso il modello PendingPayment
        session()->put("payment_pending.$transactionId", [
            'train_id' => $train->id,
            'user_id' => $user->id,
            'from' => $from,
            'to' => $to,
            'posti' => $posti,
            'cost' => $cost,
            'name' => $request->name,
            'surname' => $request->surname,
        ]);

        //scrivi in db il pending payment prima della chiamata
        $pending = PendingPayment::create([
            'transaction_id' => $transactionId,
            'user_id' => $user->id,
            'train_id' => $train->id,
            'from_station_id' => $from,
            'to_station_id' => $to,
            'posti' => $posti,
            'cost' => $cost,
            'name' => $request->name,
            'surname' => $request->surname,
        ]);

        Log::info('PendingPayment salvato', ['id' => $pending->id]);

        // Configurazione PaySteam
        $merchantId = config('services.paysteam.merchant_id');
        $apiUrl = config('services.paysteam.api_url');
        $apiKey = config('services.paysteam.api_key');

        if (! $merchantId || ! $apiUrl || ! $apiKey) {
            abort(500, 'Configurazione PaySteam mancante.');
        }

        // Logging iniziale
        \Log::info('Invio richiesta a PaySteam', [
            'apiUrl' => $apiUrl,
            'merchant_id' => $merchantId,
            'id_transazione' => $transactionId,
            'importo' => $totalAmount,
            'callback_url' => route('payment.callback'),
        ]);

        // Richiesta a PaySteam
        $response = Http::withHeaders([
                'X-API-KEY' => $apiKey,
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
            ])
            ->post($apiUrl, [
                'merchant_id' => $merchantId,
                'id_transazione' => $transactionId,
                'descrizione' => "Biglietti treno #{$train->id} da " .Station::find($from)?->name . " a " . Station::find($to)?->name,
                'importo' => $totalAmount,
                'url_callback' => route('payment.callback'),
                'url_in' => route('ticket.index'),
            ]);

        if ($response->successful() && isset($response->json()['redirect_url'])) {
            \Log::info('Redirect PaySteam ricevuto', [
                'redirect_url' => $response->json()['redirect_url']
            ]);

            return view('tickets.redirecting-to-paysteam', [
                'redirectUrl' => $response->json()['redirect_url'],
            ]);
        }

        \Log::error('Errore comunicazione con PaySteam', [
            'status' => $response->status(),
            'body' => $response->body(),
        ]);

        return back()->withErrors(['error' => 'Errore nella comunicazione con PaySteam.']);
    }



public function paymentCallback(Request $request)
{
    // Log iniziale della richiesta raw
    Log::info('RICHIESTA CALLBACK ARRIVATA', ['raw' => $request->getContent()]);

    // Decodifica il payload JSON
    $request->merge(json_decode($request->getContent(), true));

    // Log dei dati ricevuti dopo il merge
    Log::info('MERGE OK', ['merged' => $request->all()]);

    // Validazione dei dati
    $request->validate([
        'id_transazione' => 'required|string',
        'esito' => 'required|in:OK,KO',
    ]);

    $tx = $request->id_transazione;
    $esito = $request->esito;

    // Recupera il pending payment dal database
    $pending = PendingPayment::where('transaction_id', $tx)->first();

    Log::info('Verifica pending payment', [
        'tx' => $tx,
        'found' => (bool) $pending,
        'esito' => $esito
    ]);

    if (! $pending || $esito !== 'OK') {
        Log::warning('Pagamento fallito o dati non trovati', [
            'tx' => $tx,
            'esito' => $esito
        ]);

        return response()->json([
            'message' => 'Pagamento fallito o dati non trovati',
            'redirect' => route('ticket.index'),
        ], 400);
    }

    $train = Train::find($pending->train_id);
    $createdTickets = [];

    foreach ($pending->posti as $rollingStockId => $postiCarrozza) {
        foreach ($postiCarrozza as $numeroPosto) {
            $ticket = Ticket::create([
                'user_id' => $pending->user_id,
                'train_id' => $train->id,
                'departure_station_id' => $pending->from_station_id,
                'arrival_station_id' => $pending->to_station_id,
                'departure_time' => $train->departure_time,
                'arrival_time' => $train->arrival_time,
                'costo' => $pending->cost,
                'rolling_stock_id' => $rollingStockId,
                'numero_posto' => $numeroPosto,
                'payed_at' => now(),
                'payment_token' => $tx,
            ]);
            $createdTickets[] = $ticket;
        }
    }

    // Elimina la pending payment dopo aver creato i biglietti
    $pending->delete();

    Log::info('Biglietti creati con successo', [
        'count' => count($createdTickets),
        'user_id' => $pending->user_id
    ]);

    return response()->json([
        'message' => 'Pagamento riuscito, biglietti creati',
        'redirect' => route('ticket.index'),
    ]);
}





    /**
     * Mostra i biglietti dell'utente loggato.
     */
    public function index(Request $request)
    {
        $tickets = Ticket::with(['train', 'departureStation', 'arrivalStation', 'rollingStock'])
            ->where('user_id', $request->user()->id)
            ->orderByDesc('created_at')
            ->get();

        return view('tickets.index', compact('tickets'));
    }

    /**
     * Mostra il form per modificare un biglietto.
     */
    public function edit(Ticket $ticket, Request $request)
    {
        abort_if(auth()->id() !== $ticket->user_id, 403);

        $today = now()->toDateString();
        $selectedDate = $request->input('date');
        $selectedTrainId = $request->input('train_id');

        // Trova tutti i treni futuri (da oggi in poi)
        $trainIds = Train::whereDate('date', '>=', $today)->pluck('id');

        // Raggruppa subtratte per treno
        $subtratteByTrain = SubTratta::whereIn('train_id', $trainIds)
            ->orderBy('train_id')
            ->orderBy('order')
            ->get()
            ->groupBy('train_id');

        $availableTrainIds = [];

        // Trova i treni che percorrono la subtratta in ordine
        foreach ($subtratteByTrain as $trainId => $subtratte) {
            $foundFrom = false;

            foreach ($subtratte as $sub) {
                if ($sub->from_station_id == $ticket->departure_station_id) {
                    $foundFrom = true;
                }

                if ($foundFrom && $sub->to_station_id == $ticket->arrival_station_id) {
                    $availableTrainIds[] = $trainId;
                    break;
                }
            }
        }

        // Carica i treni ordinati per data e orario
        $treniDisponibili = Train::whereIn('id', $availableTrainIds)
            ->with('convoy.rollingStocks')
            ->orderBy('date')
            ->orderBy('departure_time')
            ->get();

        if ($treniDisponibili->isEmpty()) {
            return back()->withErrors(['date' => 'Nessun treno disponibile da oggi in poi per questa tratta.']);
        }

        // Se non selezionato nulla, prendi primo treno futuro
        $train = $selectedTrainId
            ? $treniDisponibili->firstWhere('id', $selectedTrainId)
            : $treniDisponibili->first();

        $selectedDate = $train->date; // aggiorna la data in base al treno

        $availableDates = $treniDisponibili->pluck('date')->unique()->sort();

        $occupiedSeats = Ticket::where('train_id', $train->id)
            ->where('id', '!=', $ticket->id)
            ->get()
            ->groupBy('rolling_stock_id');

        return view('tickets.edit', compact(
            'ticket',
            'availableDates',
            'selectedDate',
            'treniDisponibili',
            'train',
            'occupiedSeats'
        ));
    }




    /**
     * Salva la modifica al biglietto.
     */
    public function update(Request $request, Ticket $ticket)
    {
        abort_if(auth()->id() !== $ticket->user_id, 403);

        $request->validate([
            'date' => 'required|date|after_or_equal:today',
            'rolling_stock_id' => 'required|exists:rolling_stocks,id',
            'numero_posto' => 'required|integer|min:1',
        ]);

        // Trova i treni che coprono la subtratta del biglietto
        $availableTrainIds = SubTratta::where('from_station_id', $ticket->departure_station_id)
            ->pluck('train_id')
            ->intersect(
                SubTratta::where('to_station_id', $ticket->arrival_station_id)->pluck('train_id')
            );

        // Seleziona il treno per la nuova data
        $newTrain = Train::whereIn('id', $availableTrainIds)
            ->where('date', $request->date)
            ->first();

        if (! $newTrain) {
            return back()->withErrors(['date' => 'Nessun treno disponibile per la nuova data.']);
        }

        // Controlla se il posto è già stato prenotato da un altro biglietto
        $alreadyTaken = Ticket::where('train_id', $newTrain->id)
            ->where('rolling_stock_id', $request->rolling_stock_id)
            ->where('numero_posto', $request->numero_posto)
            ->where('id', '!=', $ticket->id)
            ->exists();

        if ($alreadyTaken) {
            return back()->withErrors(['numero_posto' => 'Questo posto è già stato prenotato.']);
        }

        // Aggiorna il biglietto
        $ticket->update([
            'train_id' => $newTrain->id,
            'departure_time' => $newTrain->departure_time,
            'arrival_time' => $newTrain->arrival_time,
            'rolling_stock_id' => $request->rolling_stock_id,
            'numero_posto' => $request->numero_posto,
        ]);

        return redirect()->route('ticket.index')->with('success', "Biglietto #{$ticket->id} aggiornato con successo.");
    }

    public function show(Request $request, Train $train)
    {
        $request->validate([
            'from_station_id' => 'required|exists:stations,id',
            'to_station_id' => 'required|exists:stations,id',
            'date' => 'required|date',
        ]);

        $fromId = $request->from_station_id;
        $toId = $request->to_station_id;
        $date = $request->date;

        $rollingStocks = $train->convoy->rollingStocks;

        $occupiedSeats = Ticket::where('train_id', $train->id)
            ->where(function ($q) use ($fromId, $toId) {
                $q->where('departure_station_id', '<', $toId)
                ->where('arrival_station_id', '>', $fromId);
            })
            ->get()
            ->groupBy('rolling_stock_id');

        $priceData = $this->pricing->getPriceAndDistance($fromId, $toId);

        return view('tickets.select-seat', [
            'train' => $train,
            'rollingStocks' => $rollingStocks,
            'occupiedSeats' => $occupiedSeats,
            'fromId' => $fromId,
            'toId' => $toId,
            'date' => $date,
            'km' => $priceData['km'],
            'cost' => $priceData['cost'],
        ]);
    }


}
