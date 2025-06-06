<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Train;


class PublicHomeController extends Controller
{
    public function index()
    {
        $trains = Train::with(['convoy', 'departureStation', 'arrivalStation'])
                    ->orderBy('departure_time')
                    ->get();

        return view('publichome', compact('trains'));
    }
}
