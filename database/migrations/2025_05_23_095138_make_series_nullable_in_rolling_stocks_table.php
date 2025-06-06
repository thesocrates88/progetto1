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
        Schema::table('rolling_stocks', function (Blueprint $table) {
            $table->string('series')->nullable()->change(); //non avevo settato il campo come nullable su DB ma solo in model
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('rolling_stocks', function (Blueprint $table) {
            //
        });
    }
};
