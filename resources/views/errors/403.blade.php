@extends('adminlte::page')

@section('title', 'Acceso Denegado')

@section('content_header')
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>
                    <i class="fas fa-lock mr-2"></i> Acceso Denegado
                </h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('welcome') }}">Inicio</a></li>
                    <li class="breadcrumb-item active">Error 403</li>
                </ol>
            </div>
        </div>
    </div>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card card-danger card-outline">
                <div class="card-header bg-danger">
                    <h3 class="card-title">
                        <i class="fas fa-exclamation-triangle mr-2"></i> Error 403 - Acceso No Autorizado
                    </h3>
                </div>
                <div class="card-body text-center py-5">
                    <div class="mb-4">
                        <i class="fas fa-lock fa-5x text-danger opacity-50"></i>
                    </div>
                    <h3 class="mb-3">No tienes permiso para acceder a este recurso</h3>
                    
                    @if(isset($message))
                        <p class="text-muted mb-4 lead">
                            {{ $message }}
                        </p>
                    @else
                        <p class="text-muted mb-4 lead">
                            Lo sentimos, no tienes los permisos suficientes para ver esta página.
                        </p>
                    @endif

                    @if(auth()->check())
                        <div class="alert alert-info mt-4" role="alert">
                            <strong>📋 Tu Información de Cuenta:</strong><br>
                            <small>
                                <i class="fas fa-user mr-2"></i> <strong>{{ auth()->user()->name }} {{ auth()->user()->apellidos ?? '' }}</strong><br>
                                <i class="fas fa-shield-alt mr-2"></i> Rol: <strong>{{ ucfirst(auth()->user()->tipo) }}</strong><br>
                                <i class="fas fa-envelope mr-2"></i> Email: <strong>{{ auth()->user()->email }}</strong>
                            </small>
                        </div>

                        @if(auth()->user()->tipo === 'empleado')
                            <div class="alert alert-success mt-3" role="alert">
                                <i class="fas fa-check-circle mr-2"></i>
                                <strong>Como empleado</strong>, tienes acceso a la sección de <strong>Mesas Ventas</strong>
                            </div>
                        @endif
                    @endif
                </div>
                <div class="card-footer d-flex justify-content-center gap-2">
                    <a href="{{ route('welcome') }}" class="btn btn-primary">
                        <i class="fas fa-home mr-2"></i>
                        Ir a Inicio
                    </a>
                    @if(auth()->check() && auth()->user()->tipo === 'empleado')
                        <a href="{{ route('mesasventas.index') }}" class="btn btn-success">
                            <i class="fas fa-dollar-sign mr-2"></i>
                            Ir a Mesas Ventas
                        </a>
                    @endif
                    <button onclick="history.back()" class="btn btn-secondary">
                        <i class="fas fa-arrow-left mr-2"></i>
                        Volver
                    </button>
                </div>
            </div>

            <div class="card card-warning mt-4">
                <div class="card-header bg-warning">
                    <h3 class="card-title">
                        <i class="fas fa-info-circle mr-2"></i> ¿Necesitas más permisos?
                    </h3>
                </div>
                <div class="card-body">
                    <p class="mb-0">
                        Si crees que deberías tener acceso a este recurso, 
                        contacta con el <strong>administrador del sistema</strong> para que revise tus permisos.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('css')
    <style>
        .card {
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
        }
        .opacity-50 {
            opacity: 0.5;
        }
        .gap-2 {
            gap: 0.5rem;
        }
    </style>
@endsection

