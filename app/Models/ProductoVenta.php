<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

// models/ProductosVentas.php
class ProductoVenta extends Model
{
    protected $table = 'productosventas';
    protected $fillable = [
        'idproducto',
        'idventa',
        'cantidad',
        'total',
        'descripcion',
    ];

    public function producto()
    {
        return $this->belongsTo(Producto::class, 'idproducto', 'idproducto');
    }

    public function venta()
    {
        return $this->belongsTo(MesaVenta::class, 'id', 'id');
    }
}
