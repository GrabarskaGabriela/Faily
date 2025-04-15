<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('events', function (Blueprint $table) {
            // Usuwamy niepoprawne kolumny
            $table->dropColumn(['latitude', 'longitude']);

            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();
        });

        DB::table('events')->update([
            'latitude' => 52.069,
            'longitude' => 19.480
        ]);

        Schema::table('events', function (Blueprint $table) {
            $table->decimal('latitude', 10, 7)->nullable(false)->change();
            $table->decimal('longitude', 10, 7)->nullable(false)->change();
        });
    }

    public function down(): void
    {
        Schema::table('events', function (Blueprint $table) {
            $table->dropColumn(['latitude', 'longitude']);
            // Przywracamy poprzedni stan
            $table->decimal('latitude', 10, 7);
            $table->decimal('longitude', 10, 7);
        });
    }
};
