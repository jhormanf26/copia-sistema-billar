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
        // Convertir todos los 'gerente' a 'empleado'
        DB::table('users')->where('tipo', 'gerente')->update(['tipo' => 'empleado']);

        // Modificar el enum para eliminar 'gerente'
        Schema::table('users', function (Blueprint $table) {
            $table->enum('tipo', ['admin', 'empleado'])->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Restaurar el enum anterior en caso de rollback
        Schema::table('users', function (Blueprint $table) {
            $table->enum('tipo', ['admin', 'empleado', 'gerente'])->change();
        });
    }
};
