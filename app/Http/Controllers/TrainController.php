<?php

namespace App\Http\Controllers;

use App\Models\Train;
use App\Models\Convoy;
use App\Models\Station;
use App\Models\SubTratta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Services\TrainPlannerService;

class TrainController extends Controller
{
    protected TrainPlannerService $planner;

    public function __construct(TrainPlannerService $planner)
    {
        $this->planner = $planner;
    }

    public function create()
    {
        $convoys = Convoy::all();
        $stations = Station::orderBy('order')->get();
        return view('trains.create', compact('convoys', 'stations'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'convoy_id' => 'required|exists:convoys,id',
            'name' => 'required|string|max:255',
            'departure_station_id' => 'required|exists:stations,id|different:arrival_station_id',
            'arrival_station_id' => 'required|exists:stations,id',
            'date' => 'required|date',
            'departure_time' => 'required|date_format:H:i',
        ]);

        $departureStation = Station::find($validated['departure_station_id']);
        $arrivalStation = Station::find($validated['arrival_station_id']);

        $departureTime = Carbon::createFromFormat('H:i', $validated['departure_time']);
        $distance = abs($arrivalStation->kilometer - $departureStation->kilometer);
        $subTratte = abs($departureStation->order - $arrivalStation->order);
        $durationInMinutes = (($distance / 50) * 60) + ($subTratte * 3);
        $arrivalTime = $departureTime->copy()->addMinutes($durationInMinutes);
        $arrivalTimeStr = $arrivalTime->format('H:i');

        // Verifica conflitti sull'utilizzo dello stesso convoglio
        if ($this->planner->convoyHasConflict(
            $validated['convoy_id'],
            $validated['date'],
            $validated['departure_time'],
            $arrivalTimeStr
        )) {
            return redirect()->back()->withInput()->withErrors([
                'convoy_id' => 'Questo convoglio è già impegnato in quell’orario.'
            ]);
        }

        // Genera subtratte e verifica conflitti sulla linea
        $subtratte = $this->planner->generateSubtratte(
            $validated['departure_station_id'],
            $validated['arrival_station_id'],
            $validated['date'],
            $validated['departure_time']
        );

        if ($conflict = $this->planner->hasConflicts($subtratte)) {
            $from = Station::find($conflict['from_station_id'])->name ?? 'stazione';
            $to = Station::find($conflict['to_station_id'])->name ?? 'stazione';
            return redirect()->back()->withInput()->withErrors([
                'departure_station_id' => "La subtratta {$from} → {$to} è già occupata in quell'orario."
            ]);
        }

        $train = Train::create([
            'convoy_id' => $validated['convoy_id'],
            'name' => $validated['name'], 
            'departure_station_id' => $validated['departure_station_id'],
            'arrival_station_id' => $validated['arrival_station_id'],
            'date' => $validated['date'],
            'departure_time' => $validated['departure_time'],
            'arrival_time' => $arrivalTimeStr,
        ]);

        foreach ($subtratte as $sub) {
            SubTratta::create(array_merge($sub, ['train_id' => $train->id]));
        }

        return redirect()->route('trains.create')->with('success', 'Treno schedulato con successo!');
    }


    public function update(Request $request, Train $train)
    {
        $validated = $request->validate([
            'convoy_id' => 'required|exists:convoys,id',
            'name' => 'required|string|max:255',
            'departure_station_id' => 'required|exists:stations,id|different:arrival_station_id',
            'arrival_station_id' => 'required|exists:stations,id',
            'date' => 'required|date',
            'departure_time' => 'required|date_format:H:i',
        ]);

        $departureStation = Station::find($validated['departure_station_id']);
        $arrivalStation = Station::find($validated['arrival_station_id']);

        $departureTime = Carbon::createFromFormat('H:i', $validated['departure_time']);
        $subTratte = abs($departureStation->order - $arrivalStation->order);
        $distance = abs($departureStation->kilometer - $arrivalStation->kilometer);
        $durationMinutes = (($distance / 50) * 60) + ($subTratte * 3);
        $arrivalTime = $departureTime->copy()->addMinutes($durationMinutes);
        $arrivalTimeStr = $arrivalTime->format('H:i');

        // Verifica conflitto sullo stesso convoglio
        if ($this->planner->convoyHasConflict(
            $validated['convoy_id'],
            $validated['date'],
            $validated['departure_time'],
            $arrivalTimeStr,
            $train->id
        )) {
            return redirect()->back()->withInput()->withErrors([
                'convoy_id' => 'Questo convoglio è già impegnato in quell’orario.'
            ]);
        }

        // Genera subtratte e verifica conflitti sulla linea
        $subtratte = $this->planner->generateSubtratte(
            $validated['departure_station_id'],
            $validated['arrival_station_id'],
            $validated['date'],
            $validated['departure_time']
        );

        if ($conflict = $this->planner->hasConflicts($subtratte, $train->id)) {
            $from = Station::find($conflict['from_station_id'])->name ?? 'stazione';
            $to = Station::find($conflict['to_station_id'])->name ?? 'stazione';
            return redirect()->back()->withInput()->withErrors([
                'departure_station_id' => "La subtratta {$from} → {$to} è già occupata in quell'orario."
            ]);
        }

        $train->update([
            'convoy_id' => $validated['convoy_id'],
            'name' => $validated['name'], 
            'departure_station_id' => $validated['departure_station_id'],
            'arrival_station_id' => $validated['arrival_station_id'],
            'date' => $validated['date'],
            'departure_time' => $validated['departure_time'],
            'arrival_time' => $arrivalTimeStr,
        ]);

        $train->subTratte()->delete();

        foreach ($subtratte as $sub) {
            SubTratta::create(array_merge($sub, ['train_id' => $train->id]));
        }

        return redirect()->route('trains.index')->with('success', 'Treno aggiornato con successo.');
    }


    public function index()
    {
        $trains = Train::with(['convoy', 'departureStation', 'arrivalStation'])
            ->orderBy('date')
            ->orderBy('departure_time')
            ->get();

        return view('trains.index', compact('trains'));
    }

    public function edit(Train $train)
    {
        $stations = Station::orderBy('order')->get();
        $convoys = Convoy::all();
        return view('trains.edit', compact('train', 'stations', 'convoys'));
    }

    public function destroy(Train $train)
    {
        $user = auth()->user();

        // Se l'utente è amministrativo, può cancellare solo se non ci sono biglietti
        if ($user->role === 'backoffice_amministrazione' && $train->tickets()->exists()) {
            abort(403, 'Non puoi cancellare un treno con prenotazioni.');
        }
        $train->subTratte()->delete();
        $train->delete();

        return redirect()->route('trains.index')->with('success', 'Treno eliminato con successo.');
    }

    public function show(Train $train)
    {
        $train->load(['subtratte.fromStation', 'subtratte.toStation']); // <--- CARICA QUI

        return view('trains.show', compact('train'));
    }
}
