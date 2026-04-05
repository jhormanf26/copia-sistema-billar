<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('compra_detalles', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('idcompra');
            $table->unsignedBigInteger('idproducto');
            $table->integer('cantidad');
            $table->decimal('precio_compra', 10, 2);
            $table->decimal('precio_venta', 10, 2)->nullable();
            $table->decimal('subtotal', 12, 2);
            $table->timestamps();

            $table->foreign('idcompra')->references('id')->on('compras')->onDelete('cascade');
            $table->foreign('idproducto')->references('idproducto')->on('productos')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('compra_detalles');
    }
};
