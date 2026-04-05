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
        Schema::create('productosproveedors', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('idproducto');       // FK a productos
            $table->unsignedBigInteger('idproveedor');     // FK a proveedores
            $table->foreign('idproducto')->references('idproducto')->on('productos');
            $table->foreign('idproveedor')->references('idproveedor')->on('proveedores');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('productosproveedors');
    }
};
