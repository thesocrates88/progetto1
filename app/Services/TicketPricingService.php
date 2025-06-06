<?php

namespace App\Services;

use App\Models\Station;

class TicketPricingService
{
    /**
     * Calcola distanza e costo tra due stazioni.
     *
     * @return array ['km' => int, 'cost' => float]
     */
    public function getPriceAndDistance(int $departureStationId, int $arrivalStationId): array
    {
        $departure = Station::find($departureStationId);
        $arrival = Station::find($arrivalStationId);

        if (!$departure || !$arrival) {
            return ['km' => 0, 'cost' => 0.00];
        }

        $km = abs($arrival->kilometer - $departure->kilometer);
        $cost = round($km * 1.00, 2); // 1€ al km

        return ['km' => $km, 'cost' => $cost];
    }
}
