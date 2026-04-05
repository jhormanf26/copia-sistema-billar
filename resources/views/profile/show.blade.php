@extends('adminlte::page')

@section('title', 'Mi Perfil')

@section('content_header')
    <div class="container-fluid">
        <div class="row align-items-center mb-3">
            <div class="col-md-8">
                <h1 class="text-dark mb-2 font-weight-bold">
                    <i class="fas fa-user-circle mr-2 text-primary"></i> Mi Perfil
                </h1>
                <p class="text-muted mb-0 lead">Gestiona tu información personal y seguridad</p>
            </div>
            <div class="col-md-4 text-right">
                <a href="{{ route('welcome') }}" class="btn btn-outline-primary btn-lg rounded-pill shadow-sm">
                    <i class="fas fa-arrow-left mr-2"></i> Volver al Inicio
                </a>
            </div>
        </div>
        <hr class="divider">
    </div>
@stop

@section('content')
<div class="container-fluid">
    {{-- Mostrar mensajes de éxito --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle mr-2"></i>
            <strong>¡Éxito!</strong> {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Cerrar">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    {{-- Mostrar errores --}}
    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>Errores encontrados:</strong>
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
        <!-- Tarjeta de Información del Usuario (Sidebar) -->
        <div class="col-md-4 col-sm-12 mb-4">
            <div class="card card-outline card-primary">
                <div class="card-body box-profile text-center">
                    <!-- Avatar con badge de estado -->
                    <div class="profile-user-img mb-4 position-relative d-inline-block w-100">
                        <img src="{{ auth()->user()->getAvatarUrl() }}" 
                             alt="Avatar" 
                             class="img-circle img-bordered-sm shadow"
                             id="userAvatar"
                             style="width: 180px; height: 180px; border: 5px solid #007bff; object-fit: cover; cursor: pointer; transition: transform 0.3s ease;"
                             data-toggle="tooltip" 
                             title="Haz clic para ver opciones">
                        
                        <!-- Badge de estado flotante -->
                        <span class="position-absolute badge" 
                              id="statusBadge"
                              style="top: 10px; right: 10px; font-size: 0.9rem;">
                            <i class="fas fa-check-circle mr-1"></i> En línea
                        </span>
                    </div>

                    <!-- Nombre y Tipo -->
                    <h3 class="profile-username mb-2 mt-3">
                        {{ auth()->user()->name }} {{ auth()->user()->apellidos }}
                    </h3>

                    <p class="text-muted mb-3">
                        <i class="fas fa-briefcase mr-1"></i>
                        <span class="badge badge-primary" style="font-size: 0.9rem; padding: 0.4rem 0.8rem;">
                            {{ ucfirst(auth()->user()->tipo) }}
                        </span>
                    </p>

                    <!-- Botones de acción del avatar -->
                    <div class="mb-3">
                        <button type="button" 
                                class="btn btn-sm btn-info" 
                                data-toggle="modal" 
                                data-target="#cambiarAvatarModal"
                                title="Cambiar avatar">
                            <i class="fas fa-image mr-1"></i> Cambiar Avatar
                        </button>
                    </div>

                    <!-- Información en lista -->
                    <ul class="list-group list-group-unbordered mb-3">
                        <li class="list-group-item d-flex justify-content-between">
                            <span><i class="fas fa-envelope mr-2 text-info"></i> <strong>Email:</strong></span>
                            <span class="text-muted small">{{ auth()->user()->email }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between">
                            <span><i class="fas fa-id-card mr-2 text-warning"></i> <strong>Documento:</strong></span>
                            <span class="text-muted small">{{ auth()->user()->numerodocumento }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between">
                            <span><i class="fas fa-calendar mr-2 text-success"></i> <strong>Miembro:</strong></span>
                            <span class="text-muted small">{{ auth()->user()->created_at->format('d/m/Y') }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between">
                            <span><i class="fas fa-sync mr-2 text-secondary"></i> <strong>Actualizado:</strong></span>
                            <span class="text-muted small">{{ auth()->user()->updated_at->format('d/m/Y H:i') }}</span>
                        </li>
                    </ul>

                    <!-- Botones de acción -->
                    <div class="btn-group-vertical w-100">
                        <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#editarPerfilModal">
                            <i class="fas fa-edit mr-2"></i> Editar Información
                        </button>
                        <button type="button" class="btn btn-warning btn-sm mt-2" data-toggle="modal" data-target="#cambiarContraseñaModal">
                            <i class="fas fa-lock mr-2"></i> Cambiar Contraseña
                        </button>
                    </div>
                </div>
            </div>

            <!-- Consejo de seguridad -->
            <div class="card card-outline card-warning mt-3">
                <div class="card-body p-3">
                    <h6 class="mb-2"><i class="fas fa-shield-alt mr-2"></i> <strong>Seguridad</strong></h6>
                    <small class="text-muted">
                        Mantén tu información actualizada y cambia tu contraseña regularmente.
                    </small>
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
                                <input type="text" class="form-control" value="{{ auth()->user()->name }}" disabled>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="font-weight-bold"><i class="fas fa-user mr-2 text-primary"></i> Apellidos</label>
                                <input type="text" class="form-control" value="{{ auth()->user()->apellidos }}" disabled>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="font-weight-bold"><i class="fas fa-envelope mr-2 text-info"></i> Email</label>
                                <input type="email" class="form-control" value="{{ auth()->user()->email }}" disabled>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="font-weight-bold"><i class="fas fa-id-card mr-2 text-warning"></i> Tipo de Documento</label>
                                <input type="text" class="form-control" value="{{ auth()->user()->tipodocumento ?? 'No especificado' }}" disabled>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="font-weight-bold"><i class="fas fa-passport mr-2 text-success"></i> Número de Documento</label>
                                <input type="text" class="form-control" value="{{ auth()->user()->numerodocumento }}" disabled>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="font-weight-bold"><i class="fas fa-crown mr-2 text-danger"></i> Tipo de Usuario</label>
                                <input type="text" class="form-control" value="{{ ucfirst(auth()->user()->tipo) }}" disabled>
                            </div>
                        </div>
                    </div>

                    <div class="alert alert-info mt-3">
                        <i class="fas fa-lightbulb mr-2"></i>
                        <small><strong>Tip:</strong> Haz clic en "Editar Información" para modificar tu perfil.</small>
                    </div>
                </div>
            </div>

            <!-- Seguridad -->
            <div class="card card-outline card-warning">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-lock mr-2"></i> Seguridad
                    </h3>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center p-3 bg-light rounded">
                        <div>
                            <h5 class="mb-1"><i class="fas fa-key mr-2 text-warning"></i> Contraseña</h5>
                            <small class="text-muted">
                                Última actualización: 
                                <strong>{{ auth()->user()->updated_at->diffForHumans() }}</strong>
                            </small>
                        </div>
                        <button type="button" class="btn btn-warning btn-sm" data-toggle="modal" data-target="#cambiarContraseñaModal">
                            <i class="fas fa-edit mr-2"></i> Cambiar
                        </button>
                    </div>

                    <div class="alert alert-warning mt-3">
                        <i class="fas fa-shield-alt mr-2"></i>
                        <small><strong>Recomendación:</strong> Cambia tu contraseña regularmente y utiliza contraseñas fuertes.</small>
                    </div>
                </div>
            </div>

            <!-- Zona de Peligro -->
            <div class="card card-outline card-danger mt-4">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-exclamation-triangle mr-2"></i> Zona de Peligro
                    </h3>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center p-3 bg-light rounded border border-danger">
                        <div>
                            <h5 class="mb-1"><i class="fas fa-trash-alt mr-2 text-danger"></i> Eliminar Cuenta</h5>
                            <small class="text-muted">
                                Esta acción es irreversible y eliminará permanentemente tu cuenta y todos tus datos.
                            </small>
                        </div>
                        <button type="button" class="btn btn-danger btn-sm" data-toggle="modal" data-target="#eliminarCuentaModal">
                            <i class="fas fa-trash-alt mr-2"></i> Eliminar
                        </button>
                    </div>

                    <div class="alert alert-danger mt-3">
                        <i class="fas fa-exclamation-circle mr-2"></i>
                        <small><strong>Advertencia:</strong> No podemos recuperar tu cuenta una vez eliminada.</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- ===== MODAL: EDITAR PERFIL ===== -->
<div class="modal fade" id="editarPerfilModal" tabindex="-1" role="dialog" aria-labelledby="editarPerfilModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <form action="{{ route('profile.updateProfile') }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="editarPerfilModalLabel">
                        <i class="fas fa-edit mr-2"></i> Editar Mi Información
                    </h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Cerrar">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="name"><i class="fas fa-user mr-2 text-primary"></i> <strong>Nombre</strong> *</label>
                                <input type="text" 
                                       class="form-control @error('name') is-invalid @enderror" 
                                       id="name" 
                                       name="name" 
                                       value="{{ old('name', auth()->user()->name) }}"
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
                                       value="{{ old('apellidos', auth()->user()->apellidos) }}"
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
                                       value="{{ old('email', auth()->user()->email) }}"
                                       required>
                                @error('email')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="tipodocumento">
                                    <i class="fas fa-id-card mr-2 text-warning"></i> 
                                    <strong>Tipo de Documento</strong>
                                </label>

                                <select class="form-control @error('tipodocumento') is-invalid @enderror" 
                                        id="tipodocumento" 
                                        name="tipodocumento">
                                    <option value="">Selecciona un tipo (opcional)</option>
                                    <option value="CC" @if(old('tipodocumento', auth()->user()->tipodocumento) === 'CC') selected @endif>Cédula de Ciudadanía</option>
                                    <option value="CE" @if(old('tipodocumento', auth()->user()->tipodocumento) === 'CE') selected @endif>Cédula de Extranjería</option>
                                    <option value="PA" @if(old('tipodocumento', auth()->user()->tipodocumento) === 'PA') selected @endif>Pasaporte</option>
                                    <option value="NIT" @if(old('tipodocumento', auth()->user()->tipodocumento) === 'NIT') selected @endif>NIT</option>
                                </select>

                                @error('tipodocumento')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="numerodocumento"><i class="fas fa-passport mr-2 text-success"></i> <strong>Número de Documento</strong> *</label>
                        <input type="text" 
                               class="form-control @error('numerodocumento') is-invalid @enderror" 
                               id="numerodocumento" 
                               name="numerodocumento" 
                               value="{{ old('numerodocumento', auth()->user()->numerodocumento) }}"
                               required>
                        @error('numerodocumento')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="alert alert-info">
                        <i class="fas fa-info-circle mr-2"></i>
                        <small>Los campos marcados con <strong>*</strong> son obligatorios.</small>
                    </div>
                </div>

                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save mr-2"></i> Guardar Cambios
                    </button>
                </div>
            </form>
        </div>
    </div>
    
</div>
@if ($errors->any())
<script>
    document.addEventListener("DOMContentLoaded", function() {
        $('#editarPerfilModal').modal('show');
    });
</script>
@endif

<!-- ===== MODAL: CAMBIAR CONTRASEÑA ===== -->
<div class="modal fade" id="cambiarContraseñaModal" tabindex="-1" role="dialog" aria-labelledby="cambiarContraseñaModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form action="{{ route('profile.updatePassword') }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="modal-header bg-warning text-dark">
                    <h5 class="modal-title" id="cambiarContraseñaModalLabel">
                        <i class="fas fa-lock mr-2"></i> Cambiar Contraseña
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                    <div class="form-group">
                        <label for="current_password"><i class="fas fa-key mr-2 text-danger"></i> <strong>Contraseña Actual</strong> *</label>
                        <div class="input-group">
                            <input type="password" 
                                   class="form-control @error('current_password') is-invalid @enderror" 
                                   id="current_password" 
                                   name="current_password" 
                                   required>
                            <div class="input-group-append">
                                <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('current_password')">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                            @error('current_password')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <hr class="my-3">

                    <div class="form-group">
                        <label for="password"><i class="fas fa-lock mr-2 text-success"></i> <strong>Nueva Contraseña</strong> *</label>
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
                            <i class="fas fa-info-circle mr-1"></i>
                            Usa mayúsculas, minúsculas, números y caracteres especiales.
                        </small>
                    </div>

                    <div class="form-group">
                        <label for="password_confirmation"><i class="fas fa-lock mr-2 text-success"></i> <strong>Confirmar Contraseña</strong> *</label>
                        <div class="input-group">
                            <input type="password" 
                                   class="form-control @error('password_confirmation') is-invalid @enderror" 
                                   id="password_confirmation" 
                                   name="password_confirmation" 
                                   required
                                   placeholder="Repite la nueva contraseña">
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

                    <div class="alert alert-warning mt-3">
                        <i class="fas fa-exclamation-triangle mr-2"></i>
                        <small><strong>Importante:</strong> Tu nueva contraseña debe tener al menos 8 caracteres.</small>
                    </div>
                </div>

                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-warning">
                        <i class="fas fa-save mr-2"></i> Cambiar Contraseña
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- ===== MODAL: ELIMINAR CUENTA ===== -->
<div class="modal fade" id="eliminarCuentaModal" tabindex="-1" role="dialog" aria-labelledby="eliminarCuentaModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content border-danger">
            <form id="deleteAccountForm" action="{{ route('profile.destroy') }}" method="POST">
                @csrf
                @method('DELETE')
                
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title" id="eliminarCuentaModalLabel">
                        <i class="fas fa-exclamation-triangle mr-2"></i> Eliminar Cuenta Permanentemente
                    </h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Cerrar">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                    <div class="alert alert-danger mb-3">
                        <i class="fas fa-exclamation-circle mr-2"></i>
                        <strong>⚠️ Advertencia:</strong> Esta acción no se puede deshacer.
                    </div>

                    <p class="text-dark mb-3">
                        Si eliminas tu cuenta:
                    </p>

                    <ul class="text-muted list-unstyled">
                        <li class="mb-2">
                            <i class="fas fa-times-circle text-danger mr-2"></i>
                            Se eliminarán todos tus datos personales
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-times-circle text-danger mr-2"></i>
                            Perderás acceso a tu historial de transacciones
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-times-circle text-danger mr-2"></i>
                            No podremos recuperar tu cuenta
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-times-circle text-danger mr-2"></i>
                            Se cerrará tu sesión automáticamente
                        </li>
                    </ul>

                    <hr class="my-3">

                    <div class="form-group">
                        <label for="delete_password" class="font-weight-bold">
                            <i class="fas fa-key mr-2 text-danger"></i> Para confirmar, ingresa tu contraseña:
                        </label>
                        <div class="input-group">
                            <input type="password" 
                                   class="form-control @error('password') is-invalid @enderror" 
                                   id="delete_password" 
                                   name="password" 
                                   required
                                   placeholder="Tu contraseña">
                            <div class="input-group-append">
                                <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('delete_password')">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                            @error('password')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                        <small class="text-muted d-block mt-2">
                            <i class="fas fa-info-circle mr-1"></i>
                            Por seguridad, debes confirmar tu contraseña.
                        </small>
                    </div>
                </div>

                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-danger" onclick="return confirm('¿Estás completamente seguro? Esta acción es irreversible.')">
                        <i class="fas fa-trash-alt mr-2"></i> Sí, Eliminar Mi Cuenta
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- ===== MODAL: ELIMINAR CUENTA ===== -->
<div class="modal fade" id="eliminarCuentaModal" tabindex="-1" role="dialog" aria-labelledby="eliminarCuentaModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content border-danger">
            <form action="{{ route('profile.destroy') }}" method="POST" id="eliminarCuentaForm">
                @csrf
                @method('DELETE')
                
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title" id="eliminarCuentaModalLabel">
                        <i class="fas fa-trash-alt mr-2"></i> Eliminar Cuenta
                    </h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Cerrar">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                    <div class="alert alert-danger">
                        <i class="fas fa-exclamation-circle mr-2"></i>
                        <strong>¡Atención!</strong> Esta acción no se puede deshacer. Se eliminará tu cuenta y todos tus datos de forma permanente.
                    </div>

                    <p class="mb-3">
                        Para eliminar tu cuenta, por favor escribe tu contraseña para confirmar:
                    </p>

                    <div class="form-group">
                        <label for="delete_password"><i class="fas fa-lock mr-2 text-danger"></i> <strong>Contraseña</strong> *</label>
                        <div class="input-group">
                            <input type="password" 
                                   class="form-control @error('password') is-invalid @enderror" 
                                   id="delete_password" 
                                   name="password" 
                                   required
                                   placeholder="Ingresa tu contraseña para confirmar">
                            <div class="input-group-append">
                                <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('delete_password')">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                            @error('password')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="custom-control custom-checkbox mt-3">
                        <input type="checkbox" class="custom-control-input" id="confirmDelete" required>
                        <label class="custom-control-label" for="confirmDelete">
                            Entiendo que esta acción eliminará permanentemente mi cuenta y no podrá ser recuperada.
                        </label>
                    </div>
                </div>

                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-danger" id="confirmarEliminarBtn" disabled>
                        <i class="fas fa-trash-alt mr-2"></i> Eliminar Cuenta Permanentemente
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- ===== MODAL: CAMBIAR AVATAR ===== -->
<div class="modal fade" id="cambiarAvatarModal" tabindex="-1" role="dialog" aria-labelledby="cambiarAvatarModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header bg-info text-white">
                <h5 class="modal-title" id="cambiarAvatarModalLabel">
                    <i class="fas fa-image mr-2"></i> Cambiar Avatar
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Cerrar">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <!-- Nav tabs -->
                <ul class="nav nav-tabs mb-3" id="avatarTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <a class="nav-link active" id="colors-tab" data-toggle="tab" href="#colors-content" role="tab" aria-controls="colors-content" aria-selected="true">
                            <i class="fas fa-palette mr-2"></i> Colores
                        </a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link" id="upload-tab" data-toggle="tab" href="#upload-content" role="tab" aria-controls="upload-content" aria-selected="false">
                            <i class="fas fa-upload mr-2"></i> Subir Imagen
                        </a>
                    </li>
                </ul>

                <!-- Tab content -->
                <div class="tab-content" id="avatarTabContent">
                    <!-- Colores Tab -->
                    <div class="tab-pane fade show active" id="colors-content" role="tabpanel" aria-labelledby="colors-tab">
                        <div class="row">
                            <div class="col-md-6">
                                <h6 class="mb-3"><i class="fas fa-palette mr-2"></i> <strong>Selecciona un Color</strong></h6>
                                <div class="list-group mb-3" id="avatarList">
                                    <button type="button" class="list-group-item list-group-item-action avatar-option @if(auth()->user()->avatar_color === 'FF6B6B') active @endif" data-color="FF6B6B">
                                        <img src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name . ' ' . (auth()->user()->apellidos ?? '')) }}&background=FF6B6B&size=80&bold=true&rounded=true" 
                                             class="img-circle" style="width: 50px; height: 50px; margin-right: 10px;">
                                        <span>Rojo</span>
                                    </button>
                                    <button type="button" class="list-group-item list-group-item-action avatar-option @if(auth()->user()->avatar_color === '4ECDC4') active @endif" data-color="4ECDC4">
                                        <img src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name . ' ' . (auth()->user()->apellidos ?? '')) }}&background=4ECDC4&size=80&bold=true&rounded=true" 
                                             class="img-circle" style="width: 50px; height: 50px; margin-right: 10px;">
                                        <span>Turquesa</span>
                                    </button>
                                    <button type="button" class="list-group-item list-group-item-action avatar-option @if(auth()->user()->avatar_color === '45B7D1') active @endif" data-color="45B7D1">
                                        <img src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name . ' ' . (auth()->user()->apellidos ?? '')) }}&background=45B7D1&size=80&bold=true&rounded=true" 
                                             class="img-circle" style="width: 50px; height: 50px; margin-right: 10px;">
                                        <span>Azul</span>
                                    </button>
                                    <button type="button" class="list-group-item list-group-item-action avatar-option @if(auth()->user()->avatar_color === '96CEB4') active @endif" data-color="96CEB4">
                                        <img src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name . ' ' . (auth()->user()->apellidos ?? '')) }}&background=96CEB4&size=80&bold=true&rounded=true" 
                                             class="img-circle" style="width: 50px; height: 50px; margin-right: 10px;">
                                        <span>Verde</span>
                                    </button>
                                    <button type="button" class="list-group-item list-group-item-action avatar-option @if(auth()->user()->avatar_color === 'FFEAA7') active @endif" data-color="FFEAA7">
                                        <img src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name . ' ' . (auth()->user()->apellidos ?? '')) }}&background=FFEAA7&size=80&bold=true&rounded=true" 
                                             class="img-circle" style="width: 50px; height: 50px; margin-right: 10px;">
                                        <span>Amarillo</span>
                                    </button>
                                    <button type="button" class="list-group-item list-group-item-action avatar-option @if(auth()->user()->avatar_color === 'DDA15E') active @endif" data-color="DDA15E">
                                        <img src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name . ' ' . (auth()->user()->apellidos ?? '')) }}&background=DDA15E&size=80&bold=true&rounded=true" 
                                             class="img-circle" style="width: 50px; height: 50px; margin-right: 10px;">
                                        <span>Naranja</span>
                                    </button>
                                    <button type="button" class="list-group-item list-group-item-action avatar-option @if(auth()->user()->avatar_color === 'BC6C25') active @endif" data-color="BC6C25">
                                        <img src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name . ' ' . (auth()->user()->apellidos ?? '')) }}&background=BC6C25&size=80&bold=true&rounded=true" 
                                             class="img-circle" style="width: 50px; height: 50px; margin-right: 10px;">
                                        <span>Marrón</span>
                                    </button>
                                    <button type="button" class="list-group-item list-group-item-action avatar-option @if(auth()->user()->avatar_color === '9D84B7') active @endif" data-color="9D84B7">
                                        <img src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name . ' ' . (auth()->user()->apellidos ?? '')) }}&background=9D84B7&size=80&bold=true&rounded=true" 
                                             class="img-circle" style="width: 50px; height: 50px; margin-right: 10px;">
                                        <span>Púrpura</span>
                                    </button>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <h6 class="mb-3"><i class="fas fa-eye mr-2"></i> <strong>Vista Previa</strong></h6>
                                <div class="text-center p-4 border rounded bg-light">
                                    <img src="{{ auth()->user()->getAvatarUrl() }}" 
                                         alt="Preview" 
                                         class="img-circle shadow"
                                         id="avatarPreview"
                                         style="width: 150px; height: 150px; border: 4px solid #007bff;">
                                    <p class="text-muted mt-3">
                                        <small>El avatar se actualiza automáticamente con tu nombre y apellidos</small>
                                    </p>
                                    <button type="button" class="btn btn-sm btn-success mt-2" id="saveColorBtn">
                                        <i class="fas fa-save mr-2"></i> Guardar Color
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Upload Tab -->
                    <div class="tab-pane fade" id="upload-content" role="tabpanel" aria-labelledby="upload-tab">
                        <div class="row">
                            <div class="col-md-6">
                                <h6 class="mb-3"><i class="fas fa-upload mr-2"></i> <strong>Subir Imagen</strong></h6>
                                <form action="{{ route('profile.uploadAvatarImage') }}" method="POST" enctype="multipart/form-data" id="uploadAvatarForm">
                                    @csrf
                                    <div class="form-group">
                                        <label for="avatar_file"><i class="fas fa-image mr-2"></i> <strong>Selecciona una imagen</strong></label>
                                        <div class="custom-file">
                                            <input type="file" 
                                                   class="custom-file-input @error('avatar_file') is-invalid @enderror" 
                                                   id="avatar_file" 
                                                   name="avatar_file"
                                                   accept="image/*"
                                                   onchange="previewUploadedImage(this)">
                                            <label class="custom-file-label" for="avatar_file">Elige un archivo...</label>
                                            @error('avatar_file')
                                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <small class="text-muted d-block mt-2">
                                            <i class="fas fa-info-circle mr-1"></i>
                                            Formatos: JPG, PNG, GIF, WEBP (Máximo 5MB)
                                        </small>
                                    </div>

                                    <button type="submit" class="btn btn-success btn-block">
                                        <i class="fas fa-upload mr-2"></i> Subir Imagen
                                    </button>
                                </form>

                                @if(auth()->user()->avatar_image)
                                    <div class="mt-3">
                                        <form action="{{ route('profile.deleteAvatarImage') }}" method="POST" style="display: inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-block" onclick="return confirm('¿Eliminar la imagen de avatar?')">
                                                <i class="fas fa-trash mr-2"></i> Eliminar Imagen Actual
                                            </button>
                                        </form>
                                    </div>
                                @endif
                            </div>

                            <div class="col-md-6">
                                <h6 class="mb-3"><i class="fas fa-eye mr-2"></i> <strong>Vista Previa</strong></h6>
                                <div class="text-center p-4 border rounded bg-light">
                                    <img src="{{ auth()->user()->getAvatarUrl() }}" 
                                         alt="Preview" 
                                         class="img-circle shadow"
                                         id="uploadPreview"
                                         style="width: 150px; height: 150px; border: 4px solid #007bff; object-fit: cover;">
                                    <p class="text-muted mt-3">
                                        @if(auth()->user()->avatar_image)
                                            <small><strong>Imagen personalizada actual</strong></small>
                                        @else
                                            <small>Se mostrará tu imagen aquí</small>
                                        @endif
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal-footer bg-light">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

@stop

@section('css')
<style>
    .profile-user-img {
        display: flex;
        justify-content: center;
    }

    .profile-user-img img {
        transition: transform 0.3s ease, filter 0.3s ease;
    }

    .profile-user-img img:hover {
        transform: scale(1.05);
        filter: brightness(1.1);
    }

    #userAvatar {
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
    }

    #statusBadge {
        background-color: #28a745;
        color: white;
        border-radius: 50px;
        padding: 0.4rem 0.7rem !important;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
    }

    .avatar-option {
        display: flex;
        align-items: center;
        padding: 0.75rem 1rem;
        cursor: pointer;
        border: 2px solid transparent;
        transition: all 0.2s ease;
    }

    .avatar-option:hover {
        background-color: #f8f9fa;
        border-color: #007bff;
        transform: translateX(5px);
    }

    .avatar-option.active {
        background-color: #e7f3ff;
        border-color: #007bff;
        font-weight: bold;
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

    .divider {
        border: 0;
        height: 2px;
        background: linear-gradient(to right, transparent, #007bff, transparent);
        margin: 1.5rem 0;
    }

    .card-outline.card-primary {
        border-top: 3px solid #007bff;
    }

    .card-outline.card-info {
        border-top: 3px solid #17a2b8;
    }

    .card-outline.card-warning {
        border-top: 3px solid #ffc107;
    }

    .card-outline.card-danger {
        border-top: 3px solid #dc3545;
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

    .modal-header.bg-primary,
    .modal-header.bg-warning {
        border-bottom: 2px solid rgba(0,0,0,0.1);
    }

    .invalid-feedback {
        color: #dc3545;
        font-size: 0.875rem;
    }

    .input-group-append .btn {
        padding: 0.375rem 0.75rem;
        border-color: #ced4da;
    }

    .input-group-append .btn:hover {
        background-color: #f8f9fa;
    }
</style>
@stop

@section('js')
<script>
    // Función para alternar visibilidad de contraseña
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
            // Validar que coincidan al escribir
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

        // Validación para eliminar cuenta
        const confirmDeleteCheckbox = document.getElementById('confirmDelete');
        const confirmarEliminarBtn = document.getElementById('confirmarEliminarBtn');

        if (confirmDeleteCheckbox && confirmarEliminarBtn) {
            confirmDeleteCheckbox.addEventListener('change', function() {
                confirmarEliminarBtn.disabled = !this.checked;
            });

            // Prevenir envío accidental
            document.getElementById('eliminarCuentaForm').addEventListener('submit', function(e) {
                if (!confirmDeleteCheckbox.checked) {
                    e.preventDefault();
                    alert('Debes confirmar que entiendes las consecuencias de esta acción.');
                }
            });
        }

        // Funcionalidad del avatar
        const avatarOptions = document.querySelectorAll('.avatar-option');
        const avatarPreview = document.getElementById('avatarPreview');
        const saveColorBtn = document.getElementById('saveColorBtn');
        const userName = "{{ urlencode(auth()->user()->name . ' ' . (auth()->user()->apellidos ?? '')) }}";
        let selectedColor = "{{ auth()->user()->avatar_color }}";

        avatarOptions.forEach(option => {
            option.addEventListener('click', function(e) {
                e.preventDefault();
                
                // Remover clase active de todos
                avatarOptions.forEach(o => o.classList.remove('active'));
                
                // Agregar clase active al seleccionado
                this.classList.add('active');
                
                // Guardar el color seleccionado
                selectedColor = this.getAttribute('data-color');
                
                // Actualizar preview
                const previewUrl = `https://ui-avatars.com/api/?name=${userName}&background=${selectedColor}&size=200&bold=true&rounded=true`;
                avatarPreview.src = previewUrl;
            });
        });

        // Guardar color cuando hace clic en el botón
        if (saveColorBtn) {
            saveColorBtn.addEventListener('click', function() {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = '{{ route("profile.updateAvatarColor") }}';
                form.innerHTML = '@csrf<input type="hidden" name="avatar_color" value="' + selectedColor + '">';
                document.body.appendChild(form);
                form.submit();
            });
        }

        // Vista previa de imagen subida
        function previewUploadedImage(input) {
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('uploadPreview').src = e.target.result;
                    document.querySelector('.custom-file-label').textContent = input.files[0].name;
                }
                reader.readAsDataURL(input.files[0]);
            }
        }

        // Inicializar tooltips
        if (typeof $ !== 'undefined') {
            $('[data-toggle="tooltip"]').tooltip();
        }
    });
</script>
@stop
