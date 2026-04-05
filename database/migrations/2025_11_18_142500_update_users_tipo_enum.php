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
        // Cambiar enum 'tipo' de ['admin', 'usuario'] a ['admin', 'empleado', 'gerente']
        Schema::table('users', function (Blueprint $table) {
            // Primero, cambiar todos los valores 'usuario' a 'empleado'
            DB::table('users')->where('tipo', 'usuario')->update(['tipo' => 'empleado']);
            
            // Luego, cambiar el tipo de columna
            $table->enum('tipo', ['admin', 'empleado', 'gerente'])->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Revertir cambios de 'empleado' a 'usuario'
            DB::table('users')->where('tipo', 'empleado')->update(['tipo' => 'usuario']);
            
            $table->enum('tipo', ['admin', 'usuario'])->change();
        });
    }
};
