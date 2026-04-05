<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('compras', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('idproveedor');
            $table->dateTime('fecha_compra');
            $table->decimal('total', 12, 2)->default(0);
            $table->timestamps();

            $table->foreign('idproveedor')->references('idproveedor')->on('proveedores');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('compras');
    }
};
