<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Afegeix el camp 'estat' per gestionar el flux d'aprovació d'absències.
     */
    public function up(): void
    {
        Schema::table('absencia', function (Blueprint $table) {
            $table->string('estat')->default('pendent')->after('aprobat_per');
        });
    }

    /**
     * Reverteix la migració eliminant el camp 'estat'.
     */
    public function down(): void
    {
        Schema::table('absencia', function (Blueprint $table) {
            $table->dropColumn('estat');
        });
    }
};
