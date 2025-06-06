<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('requested_trains', function (Blueprint $table) {
            $table->id();

            $table->foreignId('departure_station_id')->constrained('stations');
            $table->foreignId('arrival_station_id')->constrained('stations');

            $table->date('date');
            $table->time('departure_time');
            $table->integer('seats'); // capacità richiesta

            $table->text('admin_message')->nullable(); // messaggio da amministrazione
            $table->text('ops_message')->nullable(); // messaggio da esercizio

            $table->enum('status', [
                'in attesa del backoffice esercizio',
                'treno creato',
                'richiesta rifiutata'
            ])->default('in attesa del backoffice esercizio');

            $table->foreignId('created_by')->constrained('users'); // chi ha fatto la richiesta
            $table->foreignId('train_id')->nullable()->constrained('trains'); // se viene creato

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('requested_trains');
    }
};
