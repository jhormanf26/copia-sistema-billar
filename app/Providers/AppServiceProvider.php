<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Auth;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Gates para controlar acceso basado en roles
        Gate::define('view-dashboard', function ($user) {
            return $user->tipo === 'admin';
        });

        Gate::define('view-mesas-ventas', function ($user) {
            return in_array($user->tipo, ['admin', 'empleado']);
        });

        Gate::define('is-admin', function ($user) {
            return $user->tipo === 'admin';
        });
    }
}
