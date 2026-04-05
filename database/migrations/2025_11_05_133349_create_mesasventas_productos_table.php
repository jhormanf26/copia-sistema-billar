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
    Schema::create('mesasventas_productos', function (Blueprint $table) {
        $table->id();

        // 🔗 Clave foránea a la tabla mesasventas
        $table->unsignedBigInteger('idmesaventa');
        $table->foreign('idmesaventa')
              ->references('id') // La clave primaria en mesasventas
              ->on('mesasventas') // Nombre real de tu tabla
              ->onDelete('cascade');

        // 🔗 Clave foránea a la tabla productos
        $table->unsignedBigInteger('idproducto');
        $table->foreign('idproducto')
              ->references('idproducto') // así se llama en tu tabla productos
              ->on('productos')
              ->onDelete('cascade');

        // 🔢 Cantidad del producto en esa venta
        $table->decimal('cantidad', 5,2);

        // 💲 Precio unitario (opcional, para cálculos)
        $table->decimal('precio_unitario', 10, 2)->nullable();

        // 💰 Subtotal del producto (cantidad × precio_unitario)
        $table->decimal('subtotal', 10, 2)->nullable();

        $table->timestamps();
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mesasventas_productos');
    }
};
