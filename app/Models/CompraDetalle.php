<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompraDetalle extends Model
{
    use HasFactory;

    protected $table = 'compra_detalles';
    protected $primaryKey = 'id';
    public $timestamps = true;

    protected $fillable = [
        'idcompra',
        'idproducto',
        'cantidad',
        'precio_compra',
        'precio_venta',
        'subtotal'
    ];

    public function compra()
    {
        return $this->belongsTo(Compra::class, 'idcompra', 'id');
    }

    public function producto()
    {
        return $this->belongsTo(productos::class, 'idproducto', 'idproducto');
    }
}
