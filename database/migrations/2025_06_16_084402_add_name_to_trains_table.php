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
        Schema::table('trains', function (Blueprint $table) {
            $table->string('name')->nullable()->after('id'); // o after('convoy_id') se preferisci
        });
    }

    public function down(): void
    {
        Schema::table('trains', function (Blueprint $table) {
            $table->dropColumn('name');
        });
    }
};
