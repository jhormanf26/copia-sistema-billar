<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\Proveedores;
use App\Models\mesas;

class WelcomeController extends Controller
{
    public function index()
    {

    DB::statement("SET lc_time_names = 'es_ES'");

    $hoy = Carbon::now('America/Bogota')->toDateString();

    // 🔹 Ingreso del día
    $ingresoDia = DB::table('mesasventas')
        ->whereDate(DB::raw('CONVERT_TZ(created_at, "+00:00", "-05:00")'), $hoy)
        ->sum('total') ?? 0;

    // 🔹 Top 5 productos vendidos hoy (excluyendo productos con "tiempo")
    $productos = DB::table('productos')
        ->select('nombre', DB::raw('SUM(cantidad_vendida) as cantidad_vendida'))
        ->whereDate('created_at', $hoy)
        ->where('nombre', 'NOT LIKE', '%tiempo%')
        ->groupBy('nombre')
        ->orderByDesc('cantidad_vendida')
        ->limit(5)
        ->get();



    return view('welcome', compact('ingresoDia', 'productos', ));
}

}
