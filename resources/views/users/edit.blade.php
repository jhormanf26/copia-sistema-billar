@extends('adminlte::page')

@section('title', 'Editar Usuario')

@section('content_header')
    <div class="container-fluid">
        <div class="row align-items-center mb-3">
            <div class="col-md-8">
                <h1 class="text-dark mb-2 font-weight-bold">
                    <i class="fas fa-user-edit mr-2 text-primary"></i> Editar Usuario
                </h1>
                <p class="text-muted mb-0">Actualiza la información del usuario: <strong>{{ $user->name }} {{ $user->apellidos }}</strong></p>
            </div>
            <div class="col-md-4 text-right">
                <a href="{{ route('users.index') }}" class="btn btn-outline-primary btn-lg rounded-pill shadow-sm">
                    <i class="fas fa-arrow-left mr-2"></i> Volver a Usuarios
                </a>
            </div>
        </div>
        <hr class="divider">
    </div>
@stop

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <div class="card card-outline card-primary">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-form mr-2"></i> Formulario de Edición
                    </h3>
                </div>
                <form action="{{ route('users.update', $user) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="card-body">
                        {{-- Mostrar errores --}}
                        @if($errors->any())
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <strong><i class="fas fa-exclamation-circle mr-2"></i>Errores encontrados:</strong>
                                <ul class="mb-0 mt-2">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                                <button type="button" class="close" data-dismiss="alert" aria-label="Cerrar">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        @endif

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="name"><i class="fas fa-user mr-2 text-primary"></i> <strong>Nombre</strong> *</label>
                                    <input type="text" 
                                           class="form-control @error('name') is-invalid @enderror" 
                                           id="name" 
                                           name="name" 
                                           value="{{ old('name', $user->name) }}"
                                           required>
                                    @error('name')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="apellidos"><i class="fas fa-user mr-2 text-primary"></i> <strong>Apellidos</strong> *</label>
                                    <input type="text" 
                                           class="form-control @error('apellidos') is-invalid @enderror" 
                                           id="apellidos" 
                                           name="apellidos" 
                                           value="{{ old('apellidos', $user->apellidos) }}"
                                           required>
                                    @error('apellidos')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="email"><i class="fas fa-envelope mr-2 text-info"></i> <strong>Email</strong> *</label>
                                    <input type="email" 
                                           class="form-control @error('email') is-invalid @enderror" 
                                           id="email" 
                                           name="email" 
                                           value="{{ old('email', $user->email) }}"
                                           required>
                                    @error('email')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="numerodocumento"><i class="fas fa-passport mr-2 text-success"></i> <strong>Número de Documento</strong> *</label>
                                    <input type="text" 
                                           class="form-control @error('numerodocumento') is-invalid @enderror" 
                                           id="numerodocumento" 
                                           name="numerodocumento" 
                                           value="{{ old('numerodocumento', $user->numerodocumento) }}"
                                           required>
                                    @error('numerodocumento')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="tipodocumento"><i class="fas fa-id-card mr-2 text-warning"></i> <strong>Tipo de Documento</strong> *</label>
                                    <select class="form-control @error('tipodocumento') is-invalid @enderror" 
                                            id="tipodocumento" 
                                            name="tipodocumento"
                                            required>
                                        <option value="">Selecciona un tipo</option>
                                        <option value="cc" @if(old('tipodocumento', $user->tipodocumento) === 'cc') selected @endif>Cédula de Ciudadanía</option>
                                        <option value="ce" @if(old('tipodocumento', $user->tipodocumento) === 'ce') selected @endif>Cédula de Extranjería</option>
                                        <option value="pa" @if(old('tipodocumento', $user->tipodocumento) === 'pa') selected @endif>Pasaporte</option>
                                        <option value="nit" @if(old('tipodocumento', $user->tipodocumento) === 'nit') selected @endif>NIT</option>
                                        <option value="ti" @if(old('tipodocumento', $user->tipodocumento) === 'ti') selected @endif>Tarjeta de Identidad</option>
                                    </select>
                                    @error('tipodocumento')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="tipo"><i class="fas fa-briefcase mr-2 text-danger"></i> <strong>Tipo de Usuario</strong> *</label>
                                    <select class="form-control @error('tipo') is-invalid @enderror" 
                                            id="tipo" 
                                            name="tipo"
                                            required>
                                        <option value="">Selecciona un tipo</option>
                                        <option value="admin" @if(old('tipo', $user->tipo) === 'admin') selected @endif>Administrador</option>
                                        <option value="empleado" @if(old('tipo', $user->tipo) === 'empleado') selected @endif>Empleado</option>
                                    </select>
                                    @error('tipo')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="estado"><i class="fas fa-toggle-on mr-2 text-success"></i> <strong>Estado del Usuario</strong> *</label>
                                    <select class="form-control @error('estado') is-invalid @enderror" 
                                            id="estado" 
                                            name="estado"
                                            required>
                                        <option value="">Selecciona un estado</option>
                                        <option value="activo" @if(old('estado', $user->estado) === 'activo') selected @endif>
                                            <i class="fas fa-check-circle"></i> Activo
                                        </option>
                                        <option value="inactivo" @if(old('estado', $user->estado) === 'inactivo') selected @endif>
                                            <i class="fas fa-times-circle"></i> Inactivo
                                        </option>
                                    </select>
                                    @error('estado')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="alert alert-info">
                            <i class="fas fa-info-circle mr-2"></i>
                            <small>Los campos marcados con <strong>*</strong> son obligatorios. La contraseña no se puede cambiar desde aquí.</small>
                        </div>
                    </div>
                    <div class="card-footer">
                        <a href="{{ route('users.index') }}" class="btn btn-secondary">
                            <i class="fas fa-times mr-2"></i> Cancelar
                        </a>
                        <button type="submit" class="btn btn-primary float-right">
                            <i class="fas fa-save mr-2"></i> Guardar Cambios
                        </button>
                    </div>
                </form>
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

    .card-outline.card-primary {
        border-top: 3px solid #007bff;
    }

    .invalid-feedback {
        color: #dc3545;
        font-size: 0.875rem;
    }
</style>
@stop
