<?php

namespace App\Http\Controllers;

use App\Models\Train;
use App\Models\Station;
use Illuminate\Http\Request;

class TimetableController extends Controller
{
    public function index()
    {
        $stations = \App\Models\Station::orderBy('order')->get();

        $trains = \App\Models\Train::with(['convoy', 'subTratte'])
            ->orderBy('date')
            ->orderBy('departure_time')
            ->get();

        $northSouth = $trains->filter(fn($t) =>
            optional($t->subTratte->first())->direction === 'sud'
        );

        $southNorth = $trains->filter(fn($t) =>
            optional($t->subTratte->first())->direction === 'nord'
        );
        $trains = $northSouth->merge($southNorth)->sortBy('name');

        return view('timetable.index', compact('stations', 'northSouth', 'southNorth', 'trains'));

    }
}
