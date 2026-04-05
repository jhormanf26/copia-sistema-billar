<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Auth;

class RoleHelper
{
    /**
     * Verificar si el usuario autenticado tiene un rol específico
     */
    public static function hasRole(...$roles): bool
    {
        if (!Auth::check()) {
            return false;
        }

        return in_array(Auth::user()->tipo, $roles);
    }

    /**
     * Verificar si el usuario es administrador
     */
    public static function isAdmin(): bool
    {
        return self::hasRole('admin');
    }

    /**
     * Verificar si el usuario es empleado
     */
    public static function isEmpleado(): bool
    {
        return self::hasRole('empleado');
    }

    /**
     * Verificar si el usuario está activo
     */
    public static function isActive(): bool
    {
        if (!Auth::check()) {
            return false;
        }

        return Auth::user()->estado === 'activo';
    }
}
