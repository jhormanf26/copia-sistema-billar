<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class Productos extends Model
{

    protected $table = 'productos';
    protected $primaryKey = 'idproducto';
    public $timestamps = true;

    protected $fillable = [
        'nombre',
        'descripcion',
        'precio',
        'stock',
        'idproveedor',
        'cantidad_vendida'
    ];

    public function proveedor()
    {
        return $this->belongsTo(Proveedores::class, 'idproveedor', 'idproveedor');
    }

    public function compraDetalles()
    {
        return $this->hasMany(CompraDetalle::class, 'idproducto', 'idproducto');
    }

    public function ventasPivot()
    {
        return $this->belongsToMany(MesasVentas::class, 'mesasventas_productos', 'idproducto', 'idmesaventa')
                    ->withPivot(['id','cantidad','precio_unitario','subtotal'])
                    ->withTimestamps();
    }


}
