<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

// models/ProductosVentas.php
class ProductosVentas extends Model
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
        return $this->belongsTo(Productos::class, 'idproducto', 'idproducto');
    }

    public function venta()
    {
        return $this->belongsTo(Ventas::class, 'id', 'id');
    }
}
