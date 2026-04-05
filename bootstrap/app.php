<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use App\Http\Middleware\CheckActiveStatus;
use App\Http\Middleware\CheckRole;
use App\Http\Middleware\CheckEmployeeAccess;
use App\Http\Middleware\CheckAdminRegistration;
use App\Http\Middleware\CheckAdminOnly;
use App\Http\Middleware\CheckUserActive;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // Registrar middlewares personalizados
        $middleware->alias([
            'active' => CheckActiveStatus::class,
            'role' => CheckRole::class,
            'employee' => CheckEmployeeAccess::class,
            'admin-registration' => CheckAdminRegistration::class,
            'admin' => CheckAdminOnly::class,
            'check-user-active' => CheckUserActive::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
