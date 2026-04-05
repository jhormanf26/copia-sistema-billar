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
        // Cambiar enum 'tipodocumento' para incluir más opciones
        Schema::table('users', function (Blueprint $table) {
            // Convertir valores existentes a minúsculas
            DB::table('users')->update([
                'tipodocumento' => DB::raw("LOWER(`tipodocumento`)")
            ]);
            
            // Cambiar el tipo de columna enum
            $table->enum('tipodocumento', ['cc', 'ti', 'ce', 'pa', 'nit'])->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->enum('tipodocumento', ['cc', 'ti', 'ce'])->change();
        });
    }
};
