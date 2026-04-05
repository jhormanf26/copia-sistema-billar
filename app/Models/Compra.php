<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Compra extends Model
{
    use HasFactory;

    protected $table = 'compras';
    protected $primaryKey = 'id';
    public $timestamps = true;

    protected $fillable = [
        'idproveedor',
        'fecha_compra',
        'total'
    ];

    protected $dates = [
        'fecha_compra',
        'created_at',
        'updated_at'
    ];

    public function proveedor()
    {
        return $this->belongsTo(Proveedores::class, 'idproveedor', 'idproveedor');
    }

    public function detalles()
    {
        return $this->hasMany(CompraDetalle::class, 'idcompra', 'id');
    }
}
