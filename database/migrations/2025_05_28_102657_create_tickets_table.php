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
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('train_id')->constrained()->onDelete('cascade');
            $table->foreignId('departure_station_id')->constrained('stations');
            $table->foreignId('arrival_station_id')->constrained('stations');

            $table->time('departure_time');
            $table->time('arrival_time');
            $table->decimal('costo', 8, 2);

            $table->foreignId('rolling_stock_id')->constrained()->onDelete('cascade');
            $table->unsignedSmallInteger('numero_posto');

            $table->timestamp('payed_at')->nullable();
            $table->string('payment_token')->nullable();

            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tickets');
    }
};
