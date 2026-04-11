<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Mesa;
use App\Models\MesaConsumo;
use App\Models\Producto;
use App\Models\MesaVenta;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class MesasventasController extends Controller
{
    public function index()

{
    // Cargar mesas con ventaActiva y los productos asociados
    $mesas = Mesa::with(['ventaActiva.productos'])->get();
    $productos = Producto::where('idproveedor', '!=', 5)->get();

    // ...
    return view('mesasventas.index', compact('mesas','productos'));
}


    // Esto asegura que el total se actualice al agregar productos.

    private function _actualizarTotalVenta(MesaVenta $venta)
{
    // Vuelve a cargar la relación productos para tener los datos más recientes
    $venta->load('productos');

    // Suma todos los subtotales (cantidad * precio_unitario) de la tabla pivote.
    $productos_total = $venta->productos->sum(fn($p) => $p->pivot->subtotal);

    // Asignar el total solo de consumo (el tiempo se agrega al finalizar)
    $venta->total = round($productos_total, 2);
    $venta->save();
}


    /**
     * Obtener tarifa por hora según el tipo de mesa consultando un producto específico
     * Mapeo: tresbandas -> producto id 1, pool -> id 2, libre -> id 3
     */
    private function tarifaHoraPorMesaId($idmesa)
    {
        $mesa = Mesa::find($idmesa);
        if (!$mesa) {
            return 10000; // valor por defecto si no existe la mesa
        }

        $map = [
            'tresbandas' => 3,
            'pool' => 1,
            'libre' => 2,
        ];

        $tipo = $mesa->tipo ?? null;
        $productoId = $map[$tipo] ?? null;

        if ($productoId) {
            $producto = Producto::find($productoId);
            if ($producto && isset($producto->precio)) {
                return $producto->precio;
            }
        }

        return 10000; // fallback
    }


   public function agregarProductos(Request $request, $idmesa)
{
    $mesa = Mesa::findOrFail($idmesa);

    $venta = MesaVenta::where('idmesa', $idmesa)->whereNull('fechafin')->first();
    if (!$venta) {
        $venta = MesaVenta::create(['idmesa'=>$idmesa,'total'=>0]);
        if ($mesa->estado !== 'ocupada') {
             $mesa->estado = 'ocupada';
             $mesa->save();
        }
    }

    $cantidadesInput = $request->input('cantidades', []);
    $hayCambios = false;

    foreach ($cantidadesInput as $productoId => $cantidad) {
        if ($cantidad > 0) {
            $producto = Producto::findOrFail($productoId);

            if ($producto->stock < $cantidad) {
                return redirect()->back()->with('error', "Stock insuficiente para {$producto->nombre}");
            }

            $venta->productos()->attach($producto->idproducto, [
                'cantidad' => $cantidad,
                'precio_unitario' => $producto->precio,
                'subtotal' => $producto->precio * $cantidad,
            ]);

            $producto->stock -= $cantidad;
            $producto->cantidad_vendida += $cantidad;

            $producto->save();

            $hayCambios = true;
        }
    }

    if ($hayCambios) {
        $this->_actualizarTotalVenta($venta);
    }

    return redirect()->back()
        ->with('success', 'Productos agregados correctamente.')
        ->with('abrirModalMesa', $idmesa);
}




    public function iniciar($idmesa)
    {
        $mesa = Mesa::findOrFail($idmesa);

        // Solo inicia si está disponible para evitar problemas
        if ($mesa->estado == 'disponible' || $mesa->estado == 'reservada') {
            $mesa->estado = 'ocupada';
            $mesa->save();
        }
            $venta = MesaVenta::where('idmesa',$idmesa)->whereNull('fechafin')->first();
            // error_log(print_r($venta->toJson(), true));
            if (!$venta) {
                MesaVenta::create(['idmesa'=>$idmesa,'fechainicio'=>now(),'total'=>0]);
            } else {
                // Reinicia la fecha de inicio si ya existe una venta
                $venta->update(['fechainicio' => now()]);
            }

        return redirect()->back();
    }

    public function finalizar(Request $request, $idmesa)

{
    // Buscar la venta activa de esa mesa
    $venta = MesaVenta::where('idmesa', $idmesa)
        ->whereNull('fechafin')
        ->latest()
        ->first();


    if (!$venta) {
        return response()->json(['success' => false, 'message' => 'No hay venta activa para esta mesa']);
    }
    error_log(print_r($venta->mesa->toJson(), true));
    // Calcular tiempo transcurrido
    $inicio = Carbon::parse($venta->fechainicio);
    $fin = now();
    $minutos = $inicio->diffInMinutes($fin);
    $horas = $minutos / 60;

    // Tarifa por minuto (💰 ajusta según tu negocio) calculada según tipo de mesa
    $tarifaHora = $this->tarifaHoraPorMesaId($venta->idmesa);
    $map = [
            'tresbandas' => 3,
            'pool' => 1,
            'libre' => 2,
        ];

    // Obtener el id del producto según el tipo de mesa
    $tipo = $venta->mesa->tipo ?? null;
    $idProductoTiempo = $map[$tipo] ?? null;
    // asegurar que existe el producto
    $productoTiempo = $idProductoTiempo ? Producto::find($idProductoTiempo) : null;
    if (!$productoTiempo) {
        // manejar error: no hay producto para este tipo de mesa
        return redirect()->back()->with('error', 'No se encontró el producto de tiempo para esta mesa.');
    }
    $tarifaMinuto = $tarifaHora / 60;
    $costoTiempo = round($minutos * $tarifaMinuto, 2);
    //guardo el tiempo como un producto más
    $venta->productos()->attach($idProductoTiempo, [
                'cantidad' => $horas,
                'precio_unitario' => $tarifaHora,
                'subtotal' => $costoTiempo,
            ]);
    $this->_actualizarTotalVenta($venta);
    // Calcular total de productos (desde la tabla pivote)
    $totalProductos = $venta->productos->sum(fn($p) => $p->pivot->subtotal);

    // Calcular total final (productos + tiempo)
    $totalFinal = $totalProductos + $costoTiempo;

    // Guardar todo en la base de datos
    $venta->update([
        'fechainicio' => null,
        'total' => $totalProductos,
        'metodo_pago' => $request->input('metodo_pago', 'efectivo'),
    ]);


    /* Liberar la mesa
    $mesa = Mesa::findOrFail($idmesa);
    $mesa->estado = 'disponible';
    $mesa->save();*/

    return redirect()->back();

}


   public function reiniciar($idmesa)
{
    $mesa = Mesa::findOrFail($idmesa);
    $mesa->estado = 'disponible';
    $mesa->save();

    // Obtiene la venta activa (si existe)
    $venta = MesaVenta::where('idmesa', $idmesa)->whereNull('fechafin')->first();

    if ($venta) {
        // Finaliza la venta sin borrarla
        $venta->fechafin = now();

        // Calcula tiempo y cargo
        $inicio = Carbon::parse($venta->fechainicio);
        $fin = Carbon::parse($venta->fechafin);
        $minutes = $inicio->diffInMinutes($fin);
        $tarifaHora = $this->tarifaHoraPorMesaId($venta->idmesa);
        $tarifaMinuto = $tarifaHora / 60;
        $cargoTiempo = round($minutes * $tarifaMinuto, 2);

        // Suma productos
        $productos_total = $venta->productos->sum(fn($p) => $p->pivot->subtotal);
        $venta->total = round($productos_total + $cargoTiempo, 2);

        $venta->save();
    }

    return redirect()->back();
}


    public function actualizarEstado(Request $request, $idmesa)
{
    $mesa = Mesa::findOrFail($idmesa);
    $mesa->estado = $request->input('estado', 'disponible');
    $mesa->save();

    return redirect()->back()->with('success', 'Estado de la mesa actualizado correctamente.');
}
    public function actualizarEstadoConsumo(Request $request, $idmesa)
{
    $mesa = MesasConsumos::findOrFail($idmesa);
    $mesa->estado = $request->input('estado', 'disponible');
    $mesa->save();

    return redirect()->back()->with('success', 'Estado de la mesa de consumo actualizado correctamente.');
}
public function eliminarProducto(Request $request, $ventaId, $idMesaVenta_producto)
{
    $venta = MesaVenta::findOrFail($ventaId);

    // Buscar el producto en la venta
    $productoPivot = $venta->productos()->wherePivot('id', $idMesaVenta_producto)->first();

    if (!$productoPivot) {
        return redirect()->back()->with('error', 'Producto no encontrado en esta venta.');
    }

    // Obtener cantidad a eliminar del request
    $cantidadAEliminar = intval($request->input('cantidad_eliminar', 1));
    $cantidadActual = $productoPivot->pivot->cantidad;

    // Validar que no sea cero o negativo
    if ($cantidadAEliminar <= 0) {
        return redirect()->back()->with('error', 'La cantidad debe ser mayor a 0.');
    }

    // Validar que no se elimine más de lo que existe
    if ($cantidadAEliminar > $cantidadActual) {
        return redirect()->back()->with('error', "No puedes eliminar {$cantidadAEliminar} unidades. Solo hay {$cantidadActual} agregadas.");
    }

    $producto = Producto::findOrFail($productoPivot->pivot->idproducto);

    if ($cantidadAEliminar < $cantidadActual) {
        // Resta la cantidad especificada y actualiza subtotal
        $nuevaCantidad = $cantidadActual - $cantidadAEliminar;
        DB::table('mesasventas_productos')
            ->where('id', $idMesaVenta_producto)
            ->update([
                'cantidad' => $nuevaCantidad,
                'subtotal' => $nuevaCantidad * $productoPivot->pivot->precio_unitario,
                'updated_at' => now(),
            ]);
    } else {
        // Elimina completamente el producto (si la cantidad a eliminar es igual a la actual)
        DB::table('mesasventas_productos')
            ->where('id', $idMesaVenta_producto)
            ->delete();
    }

    // Devuelve la cantidad eliminada al stock del producto
    $producto->stock += $cantidadAEliminar;
    
    // Resta la cantidad vendida a diferencia de los tiempos de mesas
    if ($producto->idproveedor != 5) {
        $producto->cantidad_vendida -= $cantidadAEliminar;
    }
    $producto->save();

    // Recalcular el total
    $this->_actualizarTotalVenta($venta);

    return redirect()->back()->with('success', "Se eliminaron {$cantidadAEliminar} unidad(es) correctamente.");
}

public function finalizarConsumo($idmesa)
{
    $mesa = MesasConsumos::findOrFail($idmesa);
    $mesa->estado = 'disponible';
    $mesa->save();

    $venta = MesaVenta::where('idmesa', $idmesa)
        ->whereNull('fechafin')
        ->latest()
        ->first();

    if ($venta) {
        $venta->fechafin = now();

        // Calcular tiempo transcurrido
        $inicio = Carbon::parse($venta->fechainicio);
        $fin = Carbon::parse($venta->fechafin);
        $minutes = $inicio->diffInMinutes($fin);

        $tarifaHora = $this->tarifaHoraPorMesaId($venta->idmesa); // 💰 Tarifa por hora según tipo de mesa
        $tarifaMinuto = $tarifaHora / 60;
        $costoTiempo = round($minutes * $tarifaMinuto, 2);

        // Calcular total de productos
        $productos_total = $venta->productos->sum(fn($p) => $p->pivot->subtotal);

        // Calcular total final
        $totalFinal = $productos_total + $costoTiempo;

        // Guardar todos los valores
        $venta->update([
            'total' => $productos_total,
        ]);
    }

    return redirect()->back()->with('success', 'Mesa de consumo finalizada y marcada como disponible.');
}

public function finalizarVenta(Request $request, $idmesa)
{
    // Buscar la venta activa por mesa
    $venta = MesaVenta::where('idmesa', $idmesa)
        ->whereNull('fechafin')
        ->latest()
        ->first();

    if (!$venta) {
        return response()->json(['success' => false, 'message' => 'No hay venta activa para esta mesa']);
    }
    if ($venta->fechainicio != null) {
        return response()->json(['success' => false, 'message' => 'Para finalizar la venta primero deten el tiempo']);
    }


    // Actualizar venta
    $venta->update([
        'fechafin' => now(),
        'metodo_pago' => $request->input('metodo_pago', 'efectivo'),
    ]);

    // Liberar mesa
    $mesa = \App\Models\Mesa::findOrFail($idmesa);
    $mesa->estado = 'disponible';
    $mesa->save();

    return response()->json([
        'success' => true,
    ]);
}

    /**
     * Mostrar el historial de todas las ventas de mesas
     */
    public function historial()
    {
        // Obtener todas las ventas ordenadas por la más reciente
        $ventas = MesaVenta::with(['mesa', 'productos'])->orderByDesc('id')->get();

        // Calcular estadísticas
        $ingresoTotal = $ventas->sum('total');
        $ventasCompletadas = $ventas->whereNotNull('fechafin')->count();
        $ventasActivas = $ventas->whereNull('fechafin')->count();

        return view('mesasventas.historial', compact('ventas', 'ingresoTotal', 'ventasCompletadas', 'ventasActivas'));
    }

}
