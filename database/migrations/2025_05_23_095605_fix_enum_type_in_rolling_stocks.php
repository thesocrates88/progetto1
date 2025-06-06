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
            $table->enum('type', ['carrozza','automotrice','bagagliaio','locomotiva'])->change(); //avevo scritto locomotriva
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
