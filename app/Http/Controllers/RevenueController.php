<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Train;

class RevenueController extends Controller
{
    public function index()
    {
        $trains = \App\Models\Train::with(['departureStation', 'arrivalStation', 'tickets', 'convoy.rollingStocks'])->get()->map(function ($train) {
            $totalRevenue = $train->tickets->sum('costo');
            $ticketsSold = $train->tickets->count();
            $totalSeats = $train->convoy->rollingStocks->sum('seats');
            

            return [
                'id' => $train->id,
                'departure' => $train->departureStation->name ?? '-',
                'arrival' => $train->arrivalStation->name ?? '-',
                'tickets_sold' => $ticketsSold,
                'total_seats' => $totalSeats,
                'total_revenue' => $totalRevenue,
                'departure_time' => $train->departure_time,
            ];
        });

        return view('revenue.index', compact('trains'));
    }

}
