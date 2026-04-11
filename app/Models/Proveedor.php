<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Proveedor extends Model
{
    protected $table = 'proveedores';   // nombre de la tabla

    protected $primaryKey = 'idproveedor';  //  aquí indicas tu PK real
    protected $keyType = 'int';    // tipo de dato de tu PK

    protected $fillable = [
        'idproveedor',
        'nombre',
        'contacto',
        'direccion',
    ];
     public function productos()
    {
        return $this->hasMany(Producto::class, 'idproveedor');
    }
}
