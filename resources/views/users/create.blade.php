@extends('adminlte::page')

@section('title', 'Crear Nuevo Usuario')

@section('content_header')
    <div class="container-fluid">
        <div class="row align-items-center mb-3">
            <div class="col-md-8">
                <h1 class="text-dark mb-2 font-weight-bold">
                    <i class="fas fa-user-plus mr-2 text-primary"></i> Crear Nuevo Usuario
                </h1>
                <p class="text-muted mb-0">Completa el formulario para registrar un nuevo usuario</p>
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
                        <i class="fas fa-form mr-2"></i> Formulario de Nuevo Usuario
                    </h3>
                </div>
                <form action="{{ route('users.store') }}" method="POST">
                    @csrf
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
                                           value="{{ old('name') }}"
                                           required
                                           placeholder="Nombre del usuario">
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
                                           value="{{ old('apellidos') }}"
                                           required
                                           placeholder="Apellidos del usuario">
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
                                           value="{{ old('email') }}"
                                           required
                                           placeholder="correo@ejemplo.com">
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
                                           value="{{ old('numerodocumento') }}"
                                           required
                                           placeholder="1234567890">
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
                                        <option value="cc" @if(old('tipodocumento') === 'cc') selected @endif>Cédula de Ciudadanía</option>
                                        <option value="ce" @if(old('tipodocumento') === 'ce') selected @endif>Cédula de Extranjería</option>
                                        <option value="pa" @if(old('tipodocumento') === 'pa') selected @endif>Pasaporte</option>
                                        <option value="nit" @if(old('tipodocumento') === 'nit') selected @endif>NIT</option>
                                        <option value="ti" @if(old('tipodocumento') === 'ti') selected @endif>Tarjeta de Identidad</option>
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
                                        <option value="admin" @if(old('tipo') === 'admin') selected @endif>Administrador</option>
                                        <option value="empleado" @if(old('tipo') === 'empleado') selected @endif>Empleado</option>
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
                                    <label for="password"><i class="fas fa-lock mr-2 text-success"></i> <strong>Contraseña</strong> *</label>
                                    <div class="input-group">
                                        <input type="password" 
                                               class="form-control @error('password') is-invalid @enderror" 
                                               id="password" 
                                               name="password" 
                                               required
                                               placeholder="Mínimo 8 caracteres">
                                        <div class="input-group-append">
                                            <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('password')">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                        </div>
                                        @error('password')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <small class="text-muted d-block mt-1">
                                        Usa mayúsculas, minúsculas, números y caracteres especiales.
                                    </small>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="password_confirmation"><i class="fas fa-lock mr-2 text-success"></i> <strong>Confirmar Contraseña</strong> *</label>
                                    <div class="input-group">
                                        <input type="password" 
                                               class="form-control @error('password_confirmation') is-invalid @enderror" 
                                               id="password_confirmation" 
                                               name="password_confirmation" 
                                               required
                                               placeholder="Repite la contraseña">
                                        <div class="input-group-append">
                                            <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('password_confirmation')">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                        </div>
                                        @error('password_confirmation')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="alert alert-info">
                            <i class="fas fa-info-circle mr-2"></i>
                            <small>Los campos marcados con <strong>*</strong> son obligatorios.</small>
                        </div>
                    </div>
                    <div class="card-footer">
                        <a href="{{ route('users.index') }}" class="btn btn-secondary">
                            <i class="fas fa-times mr-2"></i> Cancelar
                        </a>
                        <button type="submit" class="btn btn-primary float-right">
                            <i class="fas fa-save mr-2"></i> Crear Usuario
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

    .input-group-append .btn {
        padding: 0.375rem 0.75rem;
        border-color: #ced4da;
    }

    .input-group-append .btn:hover {
        background-color: #f8f9fa;
    }

    .invalid-feedback {
        color: #dc3545;
        font-size: 0.875rem;
    }
</style>
@stop

@section('js')
<script>
    function togglePassword(fieldId) {
        const field = document.getElementById(fieldId);
        const type = field.getAttribute('type') === 'password' ? 'text' : 'password';
        field.setAttribute('type', type);
    }

    // Validación en tiempo real de contraseñas
    document.addEventListener('DOMContentLoaded', function() {
        const passwordInput = document.getElementById('password');
        const confirmPasswordInput = document.getElementById('password_confirmation');

        if (passwordInput && confirmPasswordInput) {
            [passwordInput, confirmPasswordInput].forEach(input => {
                input.addEventListener('input', function() {
                    if (passwordInput.value !== confirmPasswordInput.value) {
                        confirmPasswordInput.classList.add('is-invalid');
                    } else {
                        confirmPasswordInput.classList.remove('is-invalid');
                    }
                });
            });
        }
    });
</script>
@stop
