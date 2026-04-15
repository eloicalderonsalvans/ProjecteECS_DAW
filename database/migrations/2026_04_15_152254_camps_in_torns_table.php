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
        Schema::table('torns', function (Blueprint $table) {
            $table->text('descripcio')->nullable()->change();
            $table->text('color')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('torns', function (Blueprint $table) {
            $table->time('descripcio')->nullable()->change();
            $table->time('color')->change();
        });
    }
};
