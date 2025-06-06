<?php

namespace App\Http\Controllers;

use App\Models\Convoy;
use App\Models\RollingStock;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ConvoyController extends Controller
{
    public function create()
    {
        $rollingStocks = RollingStock::orderBy('type')->orderBy('code')->get();
        return view('convoys.create', compact('rollingStocks'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'rolling_stock_ids' => 'required|array|min:1',
            'rolling_stock_ids.*' => 'exists:rolling_stocks,id',
            'positions' => 'required|array',
        ]);

        $convoy = Convoy::create([
            'name' => $validated['name'],
        ]);

        foreach ($validated['rolling_stock_ids'] as $rollingStockId) {
            // Recupera la posizione associata a questo ID
            $position = $request->input("positions.$rollingStockId");

            // Valida singolarmente la posizione
            if (!$position || !is_numeric($position) || $position < 1) {
                // Annulla la creazione e restituisce errore
                return back()->withErrors([
                    'positions' => "Posizione mancante o non valida per il veicolo selezionato (ID: $rollingStockId)."
                ])->withInput();
            }

            $convoy->rollingStocks()->attach($rollingStockId, [
                'position' => $position
            ]);
        }

        return redirect()->route('convoys.create')->with('success', 'Convoglio creato con successo!');
    }

    public function index()
    {
        $convoys = \App\Models\Convoy::with(['rollingStocks'])->get();
        return view('convoys.index', compact('convoys'));
    }

    public function edit($id)
    {
        $convoy = Convoy::with('rollingStocks')->findOrFail($id);
        $rollingStocks = \App\Models\RollingStock::orderBy('type')->orderBy('code')->get();

        // array [rolling_stock_id => posizione]
        $selected = $convoy->rollingStocks->pluck('pivot.position', 'id')->toArray();

        return view('convoys.edit', compact('convoy', 'rollingStocks', 'selected'));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'rolling_stock_ids' => 'required|array|min:1',
            'rolling_stock_ids.*' => 'exists:rolling_stocks,id',
            'positions' => 'required|array',
        ]);

        $convoy = Convoy::findOrFail($id);
        $convoy->update(['name' => $validated['name']]);

        $convoy->rollingStocks()->detach();

        foreach ($validated['rolling_stock_ids'] as $rollingStockId) {
            $position = $request->input("positions.$rollingStockId");

            if (!$position || !is_numeric($position) || $position < 1) {
                return back()->withErrors([
                    'positions' => "Posizione non valida per il veicolo (ID: $rollingStockId)."
                ])->withInput();
            }

            $convoy->rollingStocks()->attach($rollingStockId, ['position' => $position]);
        }

        return redirect()->route('convoys.index')->with('success', 'Convoglio aggiornato!');
    }

    public function destroy(Convoy $convoy)
    {
        $now = Carbon::now();

        $treniAttivi = $convoy->trains()
            ->whereDate('date', '>=', $now->toDateString())
            ->whereTime('arrival_time', '>=', $now->format('H:i'))
            ->exists();

        if ($treniAttivi) {
            return redirect()->route('convoys.index')
                ->with('error', 'Impossibile eliminare: il convoglio è assegnato a treni futuri.');
        }

        $convoy->delete();

        return redirect()->route('convoys.index')
            ->with('success', 'Convoglio eliminato con successo.');
    }

    public function show(Convoy $convoy)
    {
        return view('convoys.show', compact('convoy'));
    }

    public function publicIndex()
    {
        $convoys = Convoy::with('rollingStocks')->get();
        return view('convoys.public_index', compact('convoys'));
    }

}
