<?php

namespace App\Http\Controllers;

use App\Models\Productos;
use App\Models\Mesas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Proveedores;

class InventarioController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $productos = Productos::all();
        $mesas = Mesas::all();
        $productosTiempo = DB::table('productos')
            ->select('nombre', 'stock')
            ->where('nombre', 'like', '%tiempo%')
            ->get();

        $proveedoresActivos = Proveedores::count();
        
        // Top 5 productos más vendidos (sin "tiempo")
        $top5Productos = DB::table('mesasventas_productos')
            ->join('productos', 'mesasventas_productos.idproducto', '=', 'productos.idproducto')
            ->where('productos.nombre', 'not like', '%tiempo%')
            ->selectRaw('productos.nombre, SUM(mesasventas_productos.cantidad) as total_vendido')
            ->groupBy('productos.idproducto', 'productos.nombre')
            ->orderByDesc('total_vendido')
            ->limit(5)
            ->get();

        return view('inventario.index', compact('productos', 'mesas', 'productosTiempo', 'proveedoresActivos', 'top5Productos'));
    }

    /**
     * API endpoint para obtener top 5 productos (actualización en tiempo real)
     */
    public function getTop5Productos()
    {
        $top5Productos = DB::table('mesasventas_productos')
            ->join('productos', 'mesasventas_productos.idproducto', '=', 'productos.idproducto')
            ->where('productos.nombre', 'not like', '%tiempo%')
            ->selectRaw('productos.nombre, SUM(mesasventas_productos.cantidad) as total_vendido')
            ->groupBy('productos.idproducto', 'productos.nombre')
            ->orderByDesc('total_vendido')
            ->limit(5)
            ->get();

        return response()->json($top5Productos);
    }
}
