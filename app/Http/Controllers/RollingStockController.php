<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RollingStock;

class RollingStockController extends Controller
{
    public function create()
    {
        return view('rollingstocks.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'code' => 'required|string|max:255',
            'type' => 'required|in:carrozza,automotrice,bagagliaio,locomotiva',
            'seats' => 'required|integer|min:0|max:100',
            'series' => 'nullable|string|max:255',
        ]);

        RollingStock::create($validated);

        return redirect()->route('rolling-stock.create')->with('success', 'Materiale rotabile inserito!');
    }

    public function index()
    {
        $items = \App\Models\RollingStock::orderBy('type')->orderBy('code')->get();
        return view('rollingstocks.index', compact('items'));
    }

}
