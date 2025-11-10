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
        Schema::table('measurements', function (Blueprint $table) {
            // Añadimos la columna 'energy' (double) después de 'power'
            $table->double('energy')->nullable()->after('power');
        });
    }

    /**
     * Reverse the migrations.
     */
   public function down(): void
    {
        Schema::table('measurements', function (Blueprint $table) {
            // Definimos la lógica para revertir (borrar la columna)
            $table->dropColumn('energy');
        });
    }
};
