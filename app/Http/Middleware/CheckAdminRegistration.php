<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckAdminRegistration
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Si intenta registrar un usuario con rol "admin" sin ser admin
        if ($request->input('tipo') === 'admin' && Auth::check() && Auth::user()->tipo !== 'admin') {
            return back()->withErrors(['tipo' => 'Solo los administradores pueden crear cuentas de administrador.']);
        }

        return $next($request);
    }
}
