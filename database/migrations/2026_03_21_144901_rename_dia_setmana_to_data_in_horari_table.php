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
        Schema::table('horari', function (Blueprint $table) {
            // Primer eliminem l'antiga i creem la nova per evitar conflictes de tipus
            $table->dropColumn('dia_setmana');
            $table->date('data')->after('hora_sortida');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('horari', function (Blueprint $table) {
            $table->dropColumn('data');
            $table->integer('dia_setmana');
        });
    }
};
