<?php

namespace App\Services;

use App\Models\SubTratta;
use App\Models\Station;
use Carbon\Carbon;

class TrainPlannerService
{
    public function generateSubtratte(int $departureStationId, int $arrivalStationId, string $date, string $departureTime): array
    {
        $stations = Station::orderBy('order')->get();
        $departureStation = $stations->firstWhere('id', $departureStationId);
        $arrivalStation = $stations->firstWhere('id', $arrivalStationId);

        $direction = $departureStation->order > $arrivalStation->order ? 'nord' : 'sud';

        $subStations = $stations->filter(function ($station) use ($departureStation, $arrivalStation) {
            return $station->order >= min($departureStation->order, $arrivalStation->order)
                && $station->order <= max($departureStation->order, $arrivalStation->order);
        });

        $subStations = $direction === 'nord'
            ? $subStations->sortByDesc('order')->values()
            : $subStations->sortBy('order')->values();

        $currentTime = Carbon::createFromFormat('Y-m-d H:i', $date . ' ' . $departureTime);
        $plannedSubtratte = [];

        for ($i = 0; $i < $subStations->count() - 1; $i++) {
            $from = $subStations[$i];
            $to = $subStations[$i + 1];

            $distance = abs($to->kilometer - $from->kilometer);
            $travelMinutes = ($distance / 50) * 60;
            $arrival = $currentTime->copy()->addMinutes($travelMinutes);

            $plannedSubtratte[] = [
                'from_station_id' => $from->id,
                'to_station_id' => $to->id,
                'departure_time' => $currentTime->copy(),
                'arrival_time' => $arrival->copy(),
                'direction' => $direction,
                'order' => $i + 1,
            ];

            $currentTime = $arrival->copy()->addMinutes(3);
        }

        return $plannedSubtratte;
    }

    public function hasConflicts(array $subtratte, ?int $excludeTrainId = null): ?array
    {
        foreach ($subtratte as $sub) {
            $from = $sub['from_station_id'];
            $to = $sub['to_station_id'];
            $start = $sub['departure_time'];
            $end = $sub['arrival_time'];

            $query = SubTratta::where(function ($q) use ($from, $to) {
                    $q->where(function ($q2) use ($from, $to) {
                        $q2->where('from_station_id', $from)
                        ->where('to_station_id', $to);
                    })->orWhere(function ($q2) use ($from, $to) {
                        $q2->where('from_station_id', $to)
                        ->where('to_station_id', $from);
                    });
                })
                ->where(function ($q) use ($start, $end) {
                    $q->where('departure_time', '<', $end)
                    ->where('arrival_time', '>', $start);
                });

            if ($excludeTrainId) {
                $query->where('train_id', '!=', $excludeTrainId);
            }

            if ($query->exists()) {
                return $sub; // Ritorna la prima sub-tratta in conflitto
            }
        }

        return null;
    }

    
    public function convoyHasConflict(int $convoyId, string $date, string $departureTime, string $arrivalTime, ?int $excludeTrainId = null): bool
    {
        $query = \App\Models\Train::where('convoy_id', $convoyId)
            ->whereDate('date', $date)
            ->where(function ($query) use ($departureTime, $arrivalTime) {
                $query->where('departure_time', '<', $arrivalTime)
                    ->where('arrival_time', '>', $departureTime);
            });

        if ($excludeTrainId) {
            $query->where('id', '!=', $excludeTrainId);
        }

        return $query->exists();
    }
}
