<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\RollingStock;

class RollingStockSeeder extends Seeder
{
    public function run(): void
    {
        $items = [
            // Carrozze serie 1928 - 36 posti
            ['code' => 'B1', 'type' => 'carrozza', 'series' => '1928', 'seats' => 36],
            ['code' => 'B2', 'type' => 'carrozza', 'series' => '1928', 'seats' => 36],
            ['code' => 'B3', 'type' => 'carrozza', 'series' => '1928', 'seats' => 36],

            // Carrozze serie 1930 - 48 posti
            ['code' => 'C6', 'type' => 'carrozza', 'series' => '1930', 'seats' => 48],
            ['code' => 'C9', 'type' => 'carrozza', 'series' => '1930', 'seats' => 48],

            // Carrozza serie 1952 - 52 posti
            ['code' => 'C12', 'type' => 'carrozza', 'series' => '1952', 'seats' => 52],

            // Bagagliai serie 1910 - 12 posti
            ['code' => 'CD1', 'type' => 'bagagliaio', 'series' => '1910', 'seats' => 12],
            ['code' => 'CD2', 'type' => 'bagagliaio', 'series' => '1910', 'seats' => 12],

            // Automotrici - 56 posti
            ['code' => 'AN56.2', 'type' => 'automotrice', 'series' => 'AN56.2', 'seats' => 56],
            ['code' => 'AN56.4', 'type' => 'automotrice', 'series' => 'AN56.4', 'seats' => 56],

            // Locomotive - 0 posti
            ['code' => 'SFT.3', 'type' => 'locomotiva', 'series' => 'Cavour', 'seats' => 0],
            ['code' => 'SFT.4', 'type' => 'locomotiva', 'series' => 'Vittorio Emanuele', 'seats' => 0],
            ['code' => 'SFT.6', 'type' => 'locomotiva', 'series' => 'Garibaldi', 'seats' => 0],
        ];

        foreach ($items as $item) {
            RollingStock::create($item);
        }
    }
}
