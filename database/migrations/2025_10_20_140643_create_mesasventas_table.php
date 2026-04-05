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
        Schema::create('mesasventas', function (Blueprint $table) {
            $table->id();
            $table->dateTime('fechainicio')->nullable();
            $table->dateTime('fechafin')->nullable();  
            $table->decimal('total', 10, 2)->default(0);
            $table->unsignedBigInteger('idmesa');
            $table->foreign('idmesa')
                  ->references('idmesa')->on('mesas');   
            $table->timestamps();
            $table->enum('metodo_pago', ['efectivo', 'transferencia', 'tarjeta'])->default('efectivo');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mesasventas');
    }
};
