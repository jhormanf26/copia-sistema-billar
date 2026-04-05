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
        Schema::create('mesas', function (Blueprint $table) {
            $table->id('idmesa');
            $table->enum('estado', ['disponible', 'ocupada', 'reservada'])->default('disponible');
            $table->enum('tipo', ['pool', 'tresbandas', 'libre','consumo']);
            $table->string('numeromesa'); // Número o identificador de la mesa
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mesas');
    }
     public function ventaActiva()
    {
        return $this->hasOne(MesasVentas::class, 'idmesa', 'idmesa')
                    ->whereNull('fechafin')
                    ->latest();
    }

    // Si quieres acceder a ventas históricas
    public function ventas()
    {
        return $this->hasMany(MesasVentas::class, 'idmesa', 'idmesa');
    }
};
