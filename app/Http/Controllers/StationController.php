<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Station;

class StationController extends Controller
{
    public function create()
    {
        return view('stations.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'kilometer' => 'required|numeric|min:0|max:999.999',
            'order' => 'required|integer|min:1|max:10',
        ]);

        Station::create($validated);

        return redirect()->route('stations.create')->with('success', 'Stazione aggiunta con successo!');
    }

    public function index()
    {
        $stations = \App\Models\Station::orderBy('order')->get();
        return view('stations.index', compact('stations'));
    }
}
