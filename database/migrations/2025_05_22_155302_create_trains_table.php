<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('trains', function (Blueprint $table) {
            $table->id();
            $table->foreignId('convoy_id')->constrained()->onDelete('cascade');

            $table->foreignId('departure_station_id')->constrained('stations')->onDelete('cascade');
            $table->foreignId('arrival_station_id')->constrained('stations')->onDelete('cascade');

            $table->date('date'); // giorno del viaggio
            $table->time('departure_time');
            $table->time('arrival_time')->nullable(); // calcolabile

            $table->timestamps();
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trains');
    }
};
