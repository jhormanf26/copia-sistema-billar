<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProductosController;
use App\Http\Controllers\ProveedoresController;
use App\Http\Controllers\InventarioController;
use App\Http\Controllers\MesasController;
use App\Http\Controllers\MesasventasController;
use App\Http\Controllers\WelcomeController;
use App\Http\Controllers\InformesController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\ComprasController;
use App\Http\Controllers\CompraDetallesController;
use App\Http\Controllers\UsersController;


use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

require __DIR__ . '/auth.php';

Route::get('/', function () {
    if (auth()->check()) {
        return redirect()->route('welcome');
    }
    return redirect()->route('login');
})->name('home');

Route::get('/login', [AuthenticatedSessionController::class, 'create'])->name('login');

// ========================================
// RUTAS PARA USUARIOS AUTENTICADOS Y ACTIVOS
// ========================================
Route::middleware(['auth', 'active', 'check-user-active'])->group(function () {

    // ========================================
    // PERFIL Y AVATAR (Acceso para todos)
    // ========================================
    Route::get('/perfil', [ProfileController::class, 'show'])->name('profile.show');
    Route::put('/perfil/actualizar', [ProfileController::class, 'updateProfile'])->name('profile.updateProfile');
    Route::put('/perfil/contraseña', [ProfileController::class, 'updatePassword'])->name('profile.updatePassword');
    Route::delete('/perfil', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::post('/perfil/avatar-color', [ProfileController::class, 'updateAvatarColor'])->name('profile.updateAvatarColor');
    Route::post('/perfil/avatar-image', [ProfileController::class, 'uploadAvatarImage'])->name('profile.uploadAvatarImage');
    Route::delete('/perfil/avatar-image', [ProfileController::class, 'deleteAvatarImage'])->name('profile.deleteAvatarImage');

    // ========================================
    // RUTAS SOLO PARA EMPLEADOS Y ADMINS
    // ========================================
    Route::middleware(['role:empleado,admin', 'employee'])->group(function () {
        // MESAS VENTAS (Empleados y Admins)
        Route::get('/mesasventas', [MesasventasController::class, 'index'])->name('mesasventas.index');
        Route::get('/mesasventas/historial', [MesasventasController::class, 'historial'])->name('mesasventas.historial');
        Route::get('/mesasventas/create', [MesasventasController::class, 'create'])->name('mesasventas.create');
        Route::post('/mesasventas/store', [MesasventasController::class, 'store'])->name('mesasventas.store');
        Route::get('/mesasventas/{idmesa}', [MesasventasController::class, 'show'])->name('mesasventas.show');
        Route::post('/mesasventas/{id}/iniciar', [MesasventasController::class, 'iniciar'])->name('mesasventas.iniciar');
        Route::post('/mesasventas/{idmesa}/finalizar', [MesasventasController::class, 'finalizar'])->name('mesasventas.finalizar');
        Route::post('/mesasventas/{idmesa}/estado', [MesasventasController::class, 'actualizarEstado'])->name('mesasventas.estado');
        Route::post('/mesasventas/{idmesa}/reiniciar', [MesasventasController::class, 'reiniciar'])->name('mesasventas.reiniciar');
        Route::post('/mesasventas/{idmesa}/agregarproductos', [MesasventasController::class, 'agregarProductos'])->name('mesasventas.agregarProductos');
        Route::post('/mesasventas/{idmesa}/agregarproductosconsumo', [MesasventasController::class, 'agregarProductosConsumo'])->name('mesasventas.agregarProductosConsumo');
        Route::get('/mesasventas/{idmesa}/total', [MesasventasController::class, 'verTotalVenta'])->name('mesasventas.verTotalVenta');
        Route::delete('ventas/{ventaId}/productos/{productoId}', [MesasventasController::class, 'eliminarProducto'])->name('mesasventas.eliminarProducto');
        Route::post('/mesasventas/finalizarVenta/{venta}', [MesasventasController::class, 'finalizarVenta'])->name('mesasventas.finalizarVenta');
        Route::post('/mesasventas/{idmesa}/cerrar', [MesasventasController::class, 'cerrarVenta'])->name('mesasventas.cerrarVenta');
        Route::post('/mesasventas/{id}/parar', [MesasventasController::class, 'parar'])->name('mesasventas.parar');
    });

    // ========================================
    // RUTAS SOLO PARA ADMINISTRADORES
    // ========================================
    Route::middleware('role:admin')->group(function () {

        // WELCOME - Dashboard
        Route::get('/welcome', [WelcomeController::class, 'index'])->name('welcome');

        Route::get('/ingreso-dia', function () {
            $hoy = Carbon::now('America/Bogota')->toDateString();
            $ingresoDia = DB::table('mesasventas')
                ->whereDate(DB::raw('CONVERT_TZ(created_at, "+00:00", "-05:00")'), $hoy)
                ->sum('total') ?? 0;
            return response()->json(['ingresoDia' => $ingresoDia]);
        });

        Route::get('/ventas-semana', function () {
            $dias = ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'];
            $inicioSemana = Carbon::now('America/Bogota')->startOfWeek();
            $labels = [];
            $valores = [];
            for ($i = 0; $i < 7; $i++) {
                $fecha = $inicioSemana->copy()->addDays($i);
                $labels[] = $dias[$fecha->dayOfWeek];
                $total = DB::table('mesasventas')
                    ->whereDate(DB::raw('CONVERT_TZ(created_at, "+00:00", "-05:00")'), $fecha->toDateString())
                    ->sum('total') ?? 0;
                $valores[] = $total;
            }
            return response()->json(['labels' => $labels, 'valores' => $valores]);
        });

        Route::get('/mesas-ocupadas', function () {
            $total = \App\Models\Mesas::count();
            $ocupadas = \App\Models\Mesas::where('estado', 'ocupada')
                ->get(['idmesa', 'numeromesa', 'tipo']);
            return response()->json([
                'ocupadas' => $ocupadas->count(),
                'total' => $total,
                'mesas' => $ocupadas,
            ]);
        });

        Route::get('/productos-cantidad', function () {
            $cantidad = \App\Models\productos::count();
            return response()->json(['cantidad' => $cantidad]);
        });

        // ---------------------- PRODUCTOS ----------------------
        Route::get('/productos/index', [ProductosController::class, 'index'])->name('productos.index');
        Route::get('/productos/create', [ProductosController::class, 'create'])->name('productos.create');
        Route::post('/productos/store', [ProductosController::class, 'store'])->name('productos.store');
        Route::get('/productos/{idproducto}/edit', [ProductosController::class, 'edit'])->name('productos.edit');
        Route::post('/productos/{idproducto}/update', [ProductosController::class, 'update'])->name('productos.update');
        Route::post('/productos/{idproducto}/destroy', [ProductosController::class, 'destroy'])->name('productos.destroy');

        // ---------------------- INVENTARIO ----------------------
        Route::get('/inventario/index', [InventarioController::class, 'index'])->name('inventario.index');
        Route::get('/inventario/create', [InventarioController::class, 'create'])->name('inventario.create');
        Route::post('/inventario/store', [InventarioController::class, 'store'])->name('inventario.store');
        Route::get('/inventario/{id}/edit', [InventarioController::class, 'edit'])->name('inventario.edit');
        Route::post('/inventario/{id}/update', [InventarioController::class, 'update'])->name('inventario.update');
        Route::post('/inventario/{id}/destroy', [InventarioController::class, 'destroy'])->name('inventario.destroy');
        Route::get('/api/inventario/top5-productos', [InventarioController::class, 'getTop5Productos'])->name('api.inventario.top5');

        // ---------------------- PROVEEDORES ----------------------
        Route::get('/proveedores/index', [ProveedoresController::class, 'index'])->name('proveedores.index');
        Route::get('/proveedores/create', [ProveedoresController::class, 'create'])->name('proveedores.create');
        Route::post('/proveedores/store', [ProveedoresController::class, 'store'])->name('proveedores.store');
        Route::get('/proveedores/{idproveedor}/edit', [ProveedoresController::class, 'edit'])->name('proveedores.edit');
        Route::post('/proveedores/{idproveedor}/update', [ProveedoresController::class, 'update'])->name('proveedores.update');
        Route::post('/proveedores/{idproveedor}/destroy', [ProveedoresController::class, 'destroy'])->name('proveedores.destroy');

        // ---------------------- MESAS NORMALES ----------------------
        Route::get('/mesas/index', [MesasController::class, 'index'])->name('mesas.index');
        Route::get('/mesas/create', [MesasController::class, 'create'])->name('mesas.create');
        Route::post('/mesas/store', [MesasController::class, 'store'])->name('mesas.store');
        Route::get('/mesas/{idmesa}/edit', [MesasController::class, 'edit'])->name('mesas.edit');
        Route::post('/mesas/{idmesa}/update', [MesasController::class, 'update'])->name('mesas.update');
        Route::post('/mesas/{idmesa}/destroy', [MesasController::class, 'destroy'])->name('mesas.destroy');

        // ---------------------- INFORMES ----------------------
        Route::get('/informes', [InformesController::class, 'index'])->name('informes.index');
        Route::get('/api/informes/ventas-periodo', [InformesController::class, 'ventasPorPeriodo'])->name('api.informes.ventas-periodo');
        Route::get('/api/informes/productos-vendidos', [InformesController::class, 'productosMasVendidos'])->name('api.informes.productos-vendidos');
        Route::get('/api/informes/ingresos-metodo', [InformesController::class, 'ingresosPorMetodoPago'])->name('api.informes.ingresos-metodo');
        Route::get('/api/informes/ocupacion-mesas', [InformesController::class, 'ocupacionMesas'])->name('api.informes.ocupacion-mesas');
        Route::get('/api/informes/resumen', [InformesController::class, 'resumenGeneral'])->name('api.informes.resumen');
        Route::get('/api/informes/comparacion-meses', [InformesController::class, 'comparacionMeses'])->name('api.informes.comparacion-meses');
        Route::get('/api/informes/compras-periodo', [InformesController::class, 'comprasPorPeriodo'])->name('api.informes.compras-periodo');
        Route::get('/api/informes/productos-comprados', [InformesController::class, 'productosComprados'])->name('api.informes.productos-comprados');
        Route::get('/api/informes/compras-proveedor', [InformesController::class, 'comprasPorProveedor'])->name('api.informes.compras-proveedor');
        Route::get('/api/informes/resumen-compras', [InformesController::class, 'resumenCompras'])->name('api.informes.resumen-compras');

        // ---------------------- COMPRAS ----------------------
        Route::get('/compras', [ComprasController::class, 'index'])->name('compras.index');
        Route::get('/compras/create', [ComprasController::class, 'create'])->name('compras.create');
        Route::post('/compras/store', [ComprasController::class, 'store'])->name('compras.store');
        Route::get('/compras/{compra}', [ComprasController::class, 'show'])->name('compras.show');
        Route::get('/compras/{compra}/edit', [ComprasController::class, 'edit'])->name('compras.edit');
        Route::put('/compras/{compra}', [ComprasController::class, 'update'])->name('compras.update');
        Route::delete('/compras/{compra}', [ComprasController::class, 'destroy'])->name('compras.destroy');
        Route::get('/compras/productos/{id}', [ComprasController::class, 'getProductosProveedor'])->name('compras.productos');

        // ---------------------- COMPRA DETALLES ----------------------
        Route::get('/compra-detalles', [CompraDetallesController::class, 'index'])->name('compra_detalles.index');
        Route::get('/compra-detalles/create', [CompraDetallesController::class, 'create'])->name('compra_detalles.create');
        Route::post('/compra-detalles/store', [CompraDetallesController::class, 'store'])->name('compra_detalles.store');
        Route::get('/compra-detalles/{compra_detalles}', [CompraDetallesController::class, 'show'])->name('compra_detalles.show');
        Route::get('/compra-detalles/{compra_detalles}/edit', [CompraDetallesController::class, 'edit'])->name('compra_detalles.edit');
        Route::put('/compra-detalles/{compra_detalles}', [CompraDetallesController::class, 'update'])->name('compra_detalles.update');
        Route::delete('/compra-detalles/{compra_detalles}', [CompraDetallesController::class, 'destroy'])->name('compra_detalles.destroy');

        // ---------------------- USUARIOS ----------------------
        Route::get('/usuarios', [UsersController::class, 'index'])->name('users.index');
        Route::get('/usuarios/crear', [UsersController::class, 'create'])->name('users.create');
        Route::post('/usuarios/store', [UsersController::class, 'store'])->name('users.store');
        Route::get('/usuarios/{user}', [UsersController::class, 'show'])->name('users.show');
        Route::get('/usuarios/{user}/editar', [UsersController::class, 'edit'])->name('users.edit');
        Route::put('/usuarios/{user}', [UsersController::class, 'update'])->name('users.update');
        Route::delete('/usuarios/{user}', [UsersController::class, 'destroy'])->name('users.destroy');
        Route::post('/usuarios/{user}/toggle-status', [UsersController::class, 'toggleStatus'])->name('users.toggleStatus');
        // Nuevas rutas para cambiar tipo y estado
        Route::post('/users/{user}/update-tipo', [UsersController::class, 'updateTipo'])->name('users.update-tipo');
        Route::post('/users/{user}/update-estado', [UsersController::class, 'updateEstado'])->name('users.update-estado');
        // Vista de gestión de usuarios
        Route::get('/admin/usuarios-management', [UsersController::class, 'adminManagement'])->name('admin.users-management')->middleware('admin');
        Route::get('/api/usuarios', [UsersController::class, 'getUsers'])->name('api.users');
        Route::post('/usuarios/sincronizar-sedevar', [UsersController::class, 'syncFromSedevar'])->name('users.syncFromSedevar');

    }); // Fin de rutas solo para admins

}); // Fin de rutas autenticadas y activas

Route::fallback(function () {
    return redirect()->route('login');
});









