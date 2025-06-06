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
        Schema::create('convoy_rolling_stock', function (Blueprint $table) {
            $table->id();
            $table->foreignId('convoy_id')->constrained()->onDelete('cascade');
            $table->foreignId('rolling_stock_id')->constrained()->onDelete('cascade');
            $table->unsignedTinyInteger('position'); // ordine del veicolo nel convoglio
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('convoy_rolling_stock');
    }
};
