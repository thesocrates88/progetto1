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
        Schema::create('rolling_stocks', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('code'); //codice materiale rotante e.g. B1, B2 o Cavour
            $table->enum('type',['Carrozza','Automotrice','Bagagliaio','Locomotriva']); //tipo di materiale per il dropdown
            $table->unsignedSmallInteger('seats'); // posti a sedere
            $table->string('series'); //numero di serie e.g.1928

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rolling_stocks');
    }
};
