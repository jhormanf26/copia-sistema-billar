<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckActiveStatus
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Si el usuario está autenticado, verificar su estado
        if (Auth::check()) {
            $user = Auth::user();
            
            // Si el usuario está inactivo, logout
            if ($user->estado === 'inactivo') {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();
                
                return redirect()->route('login')->with('error', 'Tu cuenta está inactiva. Contacta al administrador.');
            }
        }

        return $next($request);
    }
}
