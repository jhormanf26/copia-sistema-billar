<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductoProveedor extends Model
{
    protected $table = 'productosproveedors';
    protected $fillable = [
        'idproducto',
        'idproveedor',
    ];
}
