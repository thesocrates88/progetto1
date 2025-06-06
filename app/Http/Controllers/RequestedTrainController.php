<?php
namespace App\Http\Controllers;

use App\Models\RequestedTrain;
use App\Models\Station;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RequestedTrainController extends Controller
{
    public function index()
    {
        $requests = RequestedTrain::with(['departureStation', 'arrivalStation'])
            ->orderByDesc('created_at')
            ->get();

        return view('requested_trains.index', compact('requests'));
    }

    public function create()
    {
        $stations = Station::orderBy('order')->get();
        return view('requested_trains.create', compact('stations'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'date' => 'required|date|after_or_equal:today',
            'departure_station_id' => 'required|exists:stations,id',
            'arrival_station_id' => 'required|exists:stations,id|different:departure_station_id',
            'departure_time' => 'required|date_format:H:i',
            'seats' => 'required|integer|min:10',
            'admin_message' => 'nullable|string|max:1000',
        ]);

        RequestedTrain::create([
            'date' => $request->date,
            'departure_time' => $request->departure_time,
            'departure_station_id' => $request->departure_station_id,
            'arrival_station_id' => $request->arrival_station_id,
            'seats' => $request->seats,
            'admin_message' => $request->admin_message,
            'status' => 'in attesa del backoffice esercizio',
            'created_by' => $request->user()->id,

        ]);

        return redirect()->route('requested-trains.index')->with('success', 'Richiesta inviata con successo.');
    }

    public function edit(RequestedTrain $requestedTrain)
    {
        $stations = Station::orderBy('order')->get();
        return view('requested_trains.edit', compact('requestedTrain', 'stations'));
    }

    public function update(Request $request, RequestedTrain $requestedTrain)
    {
        $user = auth()->user();

        if ($user->role === 'backoffice_amministrazione') {
            $request->validate([
                'date' => 'required|date|after_or_equal:today',
                'departure_time' => 'required|date_format:H:i',
                'departure_station_id' => 'required|exists:stations,id',
                'arrival_station_id' => 'required|exists:stations,id|different:departure_station_id',
                'seats' => 'required|integer|min:1',
                'admin_message' => 'nullable|string|max:1000',
                'status' => 'required|in:in attesa del backoffice esercizio,treno creato,richiesta rifiutata',
                'exercise_message' => 'nullable|string|max:1000',
            ]);

            $requestedTrain->update([
                'date' => $request->date,
                'departure_time' => $request->departure_time,
                'departure_station_id' => $request->departure_station_id,
                'arrival_station_id' => $request->arrival_station_id,
                'seats' => $request->seats,
                'admin_message' => $request->admin_message,
                'status' => $request->status,
                'exercise_message' => $request->exercise_message,
                'edited_at' => now(),
            ]);
        } elseif ($user->role === 'backoffice_esercizio') {
            $request->validate([
                'status' => 'required|in:in attesa del backoffice esercizio,treno creato,richiesta rifiutata',
                'exercise_message' => 'nullable|string|max:1000',
            ]);

            $requestedTrain->update([
                'status' => $request->status,
                'exercise_message' => $request->exercise_message,
            ]);
        } else {
            abort(403, 'Non autorizzato.');
        }

        return redirect()->route('requested-trains.index')->with('success', 'Richiesta aggiornata.');
    }


    public function show(RequestedTrain $requestedTrain)
    {
        return view('requested_trains.show', compact('requestedTrain'));
    }

    public function destroy(RequestedTrain $requestedTrain)
    {
        $requestedTrain->delete();
        return redirect()->route('requested-trains.index')->with('success', 'Richiesta eliminata.');
    }
}
