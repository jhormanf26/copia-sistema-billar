<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     * 
     * Este middleware verifica que el usuario autenticado tenga el rol requerido.
     * - admin: Acceso a todas las rutas
     * - empleado: Acceso restringido solo a mesasventas.index
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string  ...$roles
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
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
            return redirect()->route('login')->with('error', 'Tu cuenta está inactiva. Contacta al administrador.');
        }

        // Si es admin, permitir acceso a todo
        if ($user->tipo === 'admin') {
            return $next($request);
        }

        // Verificar si el rol del usuario está en la lista permitida
        if (!in_array($user->tipo, $roles)) {
            return response()->view('errors.403', ['message' => 'No tienes permiso para acceder a este recurso.'], 403);
        }

        return $next($request);
    }
}
