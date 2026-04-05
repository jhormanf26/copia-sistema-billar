@extends('adminlte::page')

@section('title', 'Ver Usuario')

@section('content_header')
    <div class="container-fluid">
        <div class="row align-items-center mb-3">
            <div class="col-md-8">
                <h1 class="text-dark mb-2 font-weight-bold">
                    <i class="fas fa-user-circle mr-2 text-primary"></i> Detalles del Usuario
                </h1>
                <p class="text-muted mb-0">Información de: <strong>{{ $user->name }} {{ $user->apellidos }}</strong></p>
            </div>
            <div class="col-md-4 text-right">
                <a href="{{ route('users.edit', $user) }}" class="btn btn-primary btn-lg rounded-pill shadow-sm">
                    <i class="fas fa-edit mr-2"></i> Editar
                </a>
                <a href="{{ route('users.index') }}" class="btn btn-outline-primary btn-lg rounded-pill shadow-sm">
                    <i class="fas fa-arrow-left mr-2"></i> Volver
                </a>
            </div>
        </div>
        <hr class="divider">
    </div>
@stop

@section('content')
<div class="container-fluid">
    <div class="row">
        <!-- Tarjeta de Usuario (Sidebar) -->
        <div class="col-md-4 col-sm-12 mb-4">
            <div class="card card-outline card-primary">
                <div class="card-body box-profile text-center">
                    <!-- Avatar -->
                    <div class="profile-user-img mb-3">
                        <img src="https://ui-avatars.com/api/?name={{ urlencode($user->name . ' ' . ($user->apellidos ?? '')) }}&background=random&size=200&bold=true" 
                             alt="Avatar" 
                             class="img-circle img-bordered-sm"
                             style="width: 150px; height: 150px; border: 4px solid #007bff; object-fit: cover;">
                    </div>

                    <!-- Nombre y Tipo -->
                    <h3 class="profile-username mb-2">
                        {{ $user->name }} {{ $user->apellidos }}
                    </h3>

                    <p class="text-muted mb-3">
                        <i class="fas fa-briefcase mr-1"></i>
                        <span class="badge badge-primary">{{ ucfirst($user->tipo) }}</span>
                    </p>

                    <!-- Información en lista -->
                    <ul class="list-group list-group-unbordered mb-3">
                        <li class="list-group-item d-flex justify-content-between">
                            <span><i class="fas fa-envelope mr-2 text-info"></i> <strong>Email:</strong></span>
                            <span class="text-muted small">{{ $user->email }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between">
                            <span><i class="fas fa-id-card mr-2 text-warning"></i> <strong>Documento:</strong></span>
                            <span class="text-muted small">{{ $user->numerodocumento }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between">
                            <span><i class="fas fa-check-circle mr-2 text-success"></i> <strong>Estado:</strong></span>
                            <span>
                                @if($user->estado === 'activo')
                                    <span class="badge badge-success">Activo</span>
                                @else
                                    <span class="badge badge-danger">Inactivo</span>
                                @endif
                            </span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between">
                            <span><i class="fas fa-calendar mr-2 text-secondary"></i> <strong>Miembro desde:</strong></span>
                            <span class="text-muted small">{{ $user->created_at->format('d/m/Y') }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between">
                            <span><i class="fas fa-sync mr-2 text-secondary"></i> <strong>Última actualización:</strong></span>
                            <span class="text-muted small">{{ $user->updated_at->diffForHumans() }}</span>
                        </li>
                    </ul>

                    <!-- Botones de acción -->
                    <div class="btn-group-vertical w-100">
                        <a href="{{ route('users.edit', $user) }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-edit mr-2"></i> Editar Usuario
                        </a>
                        <form action="{{ route('users.destroy', $user) }}" method="POST" style="display: inline;" onsubmit="return confirm('¿Estás seguro de que deseas eliminar este usuario?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm w-100 mt-2">
                                <i class="fas fa-trash mr-2"></i> Eliminar Usuario
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Contenido Principal -->
        <div class="col-md-8 col-sm-12">
            <!-- Información Personal -->
            <div class="card card-outline card-info mb-4">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-info-circle mr-2"></i> Información Personal
                    </h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="font-weight-bold"><i class="fas fa-user mr-2 text-primary"></i> Nombre</label>
                                <input type="text" class="form-control" value="{{ $user->name }}" disabled>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="font-weight-bold"><i class="fas fa-user mr-2 text-primary"></i> Apellidos</label>
                                <input type="text" class="form-control" value="{{ $user->apellidos }}" disabled>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="font-weight-bold"><i class="fas fa-envelope mr-2 text-info"></i> Email</label>
                                <input type="email" class="form-control" value="{{ $user->email }}" disabled>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="font-weight-bold"><i class="fas fa-id-card mr-2 text-warning"></i> Tipo de Documento</label>
                                <input type="text" class="form-control" value="{{ $user->tipodocumento ?? 'No especificado' }}" disabled>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="font-weight-bold"><i class="fas fa-passport mr-2 text-success"></i> Número de Documento</label>
                                <input type="text" class="form-control" value="{{ $user->numerodocumento }}" disabled>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="font-weight-bold"><i class="fas fa-crown mr-2 text-danger"></i> Tipo de Usuario</label>
                                <input type="text" class="form-control" value="{{ ucfirst($user->tipo) }}" disabled>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Estado del Usuario -->
            <div class="card card-outline card-success mb-4">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-heartbeat mr-2"></i> Estado del Usuario
                    </h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="font-weight-bold"><i class="fas fa-check-circle mr-2 text-success"></i> Estado Actual</label>
                                <div>
                                    @if($user->estado === 'activo')
                                        <span class="badge badge-success" style="font-size: 1rem; padding: 0.5rem 1rem;">
                                            <i class="fas fa-check-circle mr-2"></i> Activo
                                        </span>
                                    @else
                                        <span class="badge badge-danger" style="font-size: 1rem; padding: 0.5rem 1rem;">
                                            <i class="fas fa-times-circle mr-2"></i> Inactivo
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="font-weight-bold"><i class="fas fa-calendar mr-2 text-info"></i> Detalles de Fecha</label>
                                <div>
                                    <small class="text-muted">
                                        <strong>Creado:</strong> {{ $user->created_at->format('d/m/Y H:i') }}<br>
                                        <strong>Actualizado:</strong> {{ $user->updated_at->format('d/m/Y H:i') }}
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Acciones Rápidas -->
            <div class="card card-outline card-warning">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-cogs mr-2"></i> Acciones Rápidas
                    </h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <a href="{{ route('users.edit', $user) }}" class="btn btn-block btn-primary">
                                <i class="fas fa-edit mr-2"></i> Editar Información
                            </a>
                        </div>
                        <div class="col-md-6">
                            <a href="{{ route('users.index') }}" class="btn btn-block btn-secondary">
                                <i class="fas fa-list mr-2"></i> Ver Lista de Usuarios
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@stop

@section('css')
<style>
    .divider {
        border: 0;
        height: 2px;
        background: linear-gradient(to right, transparent, #007bff, transparent);
        margin: 1.5rem 0;
    }

    .profile-user-img {
        display: flex;
        justify-content: center;
    }

    .box-profile {
        background-color: #fff;
    }

    .list-group-item {
        border: none;
        border-bottom: 1px solid #f0f0f0;
        padding: 0.75rem 0;
        background-color: transparent;
    }

    .list-group-item:last-child {
        border-bottom: none;
    }

    .card-outline.card-primary {
        border-top: 3px solid #007bff;
    }

    .card-outline.card-info {
        border-top: 3px solid #17a2b8;
    }

    .card-outline.card-success {
        border-top: 3px solid #28a745;
    }

    .card-outline.card-warning {
        border-top: 3px solid #ffc107;
    }

    .btn-group-vertical {
        display: flex;
        flex-direction: column;
    }

    .btn-group-vertical .btn {
        border-radius: 0.25rem;
    }

    .form-control:disabled,
    .form-control[readonly] {
        background-color: #f8f9fa;
        border-color: #dee2e6;
    }
</style>
@stop
