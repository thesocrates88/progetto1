<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Station;

class StationSeeder extends Seeder
{
    public function run(): void
    {
        $stations = [
            ['name' => 'Torre Spaventa',     'kilometer' => 0.000,  'order' => 1],
            ['name' => 'Prato Terra',        'kilometer' => 2.700,  'order' => 2],
            ['name' => 'Rocca Pietrosa',     'kilometer' => 7.580,  'order' => 3],
            ['name' => 'Villa Pietrosa',     'kilometer' => 12.680, 'order' => 4],
            ['name' => 'Villa Santa Maria',  'kilometer' => 16.900, 'order' => 5],
            ['name' => 'Pietra Santa Maria', 'kilometer' => 23.950, 'order' => 6],
            ['name' => 'Castro Marino',      'kilometer' => 31.500, 'order' => 7],
            ['name' => 'Porto Spigola',      'kilometer' => 39.500, 'order' => 8],
            ['name' => 'Porto San Felice',   'kilometer' => 46.000, 'order' => 9],
            ['name' => 'Villa San Felice',   'kilometer' => 54.680, 'order' => 10],
        ];

        foreach ($stations as $station) {
            Station::create($station);
        }
    }
}
