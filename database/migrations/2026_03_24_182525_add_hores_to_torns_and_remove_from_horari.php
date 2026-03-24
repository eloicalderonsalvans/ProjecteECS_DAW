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
        // 1. Afegir hora_entrada i hora_sortida a la taula torns
        Schema::table('torns', function (Blueprint $table) {
            $table->time('hora_entrada')->nullable()->after('color');
            $table->time('hora_sortida')->nullable()->after('hora_entrada');
        });

        // 2. Eliminar hora_entrada i hora_sortida de la taula horari
        Schema::table('horari', function (Blueprint $table) {
            $table->dropColumn(['hora_entrada', 'hora_sortida']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revertir: tornar les columnes a horari i eliminar-les de torns
        Schema::table('horari', function (Blueprint $table) {
            $table->time('hora_entrada')->nullable()->after('torn_id');
            $table->time('hora_sortida')->nullable()->after('hora_entrada');
        });

        Schema::table('torns', function (Blueprint $table) {
            $table->dropColumn(['hora_entrada', 'hora_sortida']);
        });
    }
};
