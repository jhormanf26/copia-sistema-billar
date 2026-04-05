<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckEmployeeAccess
{
    /**
     * Handle an incoming request.
     * 
     * Este middleware restringe a los empleados a solo acceder a mesasventas.index
     * Los admins tienen acceso total.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Si no está autenticado, redirigir a login
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();

        // Verificar si el usuario está inactivo
        if ($user->estado === 'inactivo') {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            return redirect()->route('login')->with('error', 'Tu cuenta está inactiva.');
        }

        // Los admins pueden acceder a todo
        if ($user->tipo === 'admin') {
            return $next($request);
        }

        // Los empleados solo pueden acceder a mesasventas.index
        if ($user->tipo === 'empleado') {
            $routeName = $request->route()->getName();
            
            // Permitir acceso a mesasventas.index y rutas relacionadas
            $allowedRoutes = [
                'mesasventas.index',
                'mesasventas.historial',
                'mesasventas.create',
                'mesasventas.store',
                'mesasventas.show',
                'mesasventas.iniciar',
                'mesasventas.finalizar',
                'mesasventas.estado',
                'mesasventas.reiniciar',
                'mesasventas.agregarProductos',
                'mesasventas.agregarProductosConsumo',
                'mesasventas.verTotalVenta',
                'mesasventas.eliminarProducto',
                'mesasventas.finalizarVenta',
                'mesasventas.cerrarVenta',
                'mesasventas.parar',
            ];

            if (!in_array($routeName, $allowedRoutes)) {
                return response()->view('errors.403', ['message' => 'Como empleado, solo puedes acceder a Mesas Ventas.'], 403);
            }
        }

        return $next($request);
    }
}
