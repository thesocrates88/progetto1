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
        Schema::table('requested_trains', function (Blueprint $table) {
            $table->renameColumn('ops_message', 'exercise_message');
        });
    }

    public function down(): void
    {
        Schema::table('requested_trains', function (Blueprint $table) {
            $table->renameColumn('exercise_message', 'ops_message');
        });
    }
};
