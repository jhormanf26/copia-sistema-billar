@extends('adminlte::page')

@section('title', 'Editar Proveedor')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1 class="m-0 text-dark">
            <i class="fas fa-edit text-warning"></i> Editar Proveedor
        </h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb bg-transparent p-0 m-0">
                <li class="breadcrumb-item"><a href="{{ route('proveedores.index') }}">Proveedores</a></li>
                <li class="breadcrumb-item active">Editar</li>
            </ol>
        </nav>
    </div>
@stop

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-8 offset-lg-2">
            
            {{-- Alerta de Errores Mejorada --}}
            @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
                <h5 class="alert-heading"><i class="fas fa-exclamation-triangle"></i> Errores de validación</h5>
                <hr>
                <ul class="mb-0 pl-3">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            @endif

            {{-- Card Principal con Sombra y Bordes Redondeados --}}
            <div class="card card-warning card-outline shadow-lg">
                <div class="card-header bg-gradient-warning">
                    <h3 class="card-title font-weight-bold">
                        <i class="fas fa-user-edit"></i> Información del Proveedor
                    </h3>
                    <div class="card-tools">
                        <span class="badge badge-light">ID: {{ $proveedor->idproveedor }}</span>
                    </div>
                </div>
                
                <form action="{{ route('proveedores.update', $proveedor->idproveedor) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="card-body">
                        
                        {{-- Alerta Informativa sobre el ID --}}
                        <div class="alert alert-info alert-dismissible fade show" role="alert">
                            <i class="fas fa-info-circle"></i> 
                            <strong>Nota:</strong> El ID del proveedor es único e inmutable. No puede ser modificado.
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>

                        {{-- CAMPO ID (SOLO LECTURA) - Diseño Mejorado --}}
                        <div class="form-group">
                            <label for="idproveedor" class="font-weight-bold">
                                <i class="fas fa-hashtag text-muted"></i> ID Proveedor
                            </label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text bg-light">
                                        <i class="fas fa-lock text-secondary"></i>
                                    </span>
                                </div>
                                <input type="text" 
                                       class="form-control bg-light font-weight-bold" 
                                       value="{{ $proveedor->idproveedor }}" 
                                       readonly 
                                       style="cursor: not-allowed;">
                            </div>
                            <small class="form-text text-muted">
                                <i class="fas fa-shield-alt"></i> Campo protegido
                            </small>
                        </div>

                        <hr class="my-4">

                        {{-- Campo Nombre con Icono --}}
                        <div class="form-group">
                            <label for="nombre" class="font-weight-bold">
                                <i class="fas fa-building text-primary"></i> Nombre del Proveedor
                                <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">
                                        <i class="fas fa-user-tie"></i>
                                    </span>
                                </div>
                                <<input type="text" 
                                       name="nombre" 
                                       id="nombre" 
                                       class="form-control @error('nombre') is-invalid @enderror" 
                                       placeholder="Ej: Distribuidora ABC S.A." 
                                       value="{{ old('nombre', $proveedor->nombre) }}" 
                                       required
                                       autofocus
                                       oninput="this.value = this.value.replace(/[^a-zA-Z\s]/g, '')">
                                @error('nombre') 
                                    <div class="invalid-feedback">
                                        <i class="fas fa-exclamation-circle"></i> {{ $message }}
                                    </div> 
                                @enderror
                            </div>
                        </div>

                        {{-- Campo Contacto con Validación Visual --}}
                        <div class="form-group">
                            <label for="contacto" class="font-weight-bold">
                                <i class="fas fa-phone text-success"></i> Teléfono de Contacto
                                <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">
                                        <i class="fas fa-mobile-alt"></i>
                                    </span>
                                </div>
                                <input type="text" 
                                       name="contacto" 
                                       id="contacto" 
                                       class="form-control @error('contacto') is-invalid @enderror" 
                                       placeholder="Ej: 3001234567" 
                                       value="{{ old('contacto', $proveedor->contacto) }}" 
                                       required 
                                       inputmode="numeric" 
                                       pattern="[0-9]*" 
                                       title="Solo se permiten números"
                                       maxlength="15">
                                @error('contacto') 
                                    <div class="invalid-feedback">
                                        <i class="fas fa-exclamation-circle"></i> {{ $message }}
                                    </div> 
                                @enderror
                            </div>
                            <small class="form-text text-muted">
                                <i class="fas fa-info-circle"></i> Solo números, sin espacios ni caracteres especiales
                            </small>
                        </div>

                        {{-- Campo Dirección con Icono de Ubicación --}}
                        <div class="form-group">
                            <label for="direccion" class="font-weight-bold">
                                <i class="fas fa-map-marker-alt text-danger"></i> Dirección
                                <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">
                                        <i class="fas fa-map-marked-alt"></i>
                                    </span>
                                </div>
                                <input type="text" 
                                       name="direccion" 
                                       id="direccion" 
                                       class="form-control @error('direccion') is-invalid @enderror" 
                                       placeholder="Ej: Calle 123 #45-67, Bucaramanga" 
                                       value="{{ old('direccion', $proveedor->direccion) }}" 
                                       required>
                                @error('direccion') 
                                    <div class="invalid-feedback">
                                        <i class="fas fa-exclamation-circle"></i> {{ $message }}
                                    </div> 
                                @enderror
                            </div>
                        </div>

                    </div>
                    
                    {{-- Footer con Botones Mejorados --}}
                    <div class="card-footer bg-light d-flex justify-content-between">
                        <a href="{{ route('proveedores.index') }}" class="btn btn-secondary shadow-sm">
                            <i class="fas fa-arrow-left"></i> Volver al Listado
                        </a>
                        <button type="submit" class="btn btn-warning shadow-sm">
                            <i class="fas fa-save"></i> Guardar Cambios
                        </button>
                    </div>
                </form>
            </div>

            {{-- Card de Información Adicional --}}
            <div class="card card-light mt-3 shadow-sm">
                <div class="card-body p-3">
                    <small class="text-muted">
                        <i class="fas fa-clock"></i> Última actualización: 
                        <strong>{{ $proveedor->updated_at ? $proveedor->updated_at->format('d/m/Y H:i') : 'N/A' }}</strong>
                    </small>
                </div>
            </div>

        </div>
    </div>
</div>
@stop

@section('css')
<style>
    /* Animaciones suaves */
    .card {
        transition: all 0.3s ease;
    }
    
    .card:hover {
        transform: translateY(-2px);
    }
    
    /* Mejora visual de inputs en focus */
    .form-control:focus {
        border-color: #ffc107;
        box-shadow: 0 0 0 0.2rem rgba(255, 193, 7, 0.25);
    }
    
    /* Estilo para campo readonly */
    input[readonly] {
        background-color: #f8f9fa !important;
        border: 2px dashed #dee2e6 !important;
    }
    
    /* Mejora de botones */
    .btn {
        transition: all 0.3s ease;
        padding: 0.5rem 1.5rem;
        font-weight: 500;
    }
    
    .btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0,0,0,0.2);
    }
    
    /* Iconos en labels */
    label i {
        margin-right: 5px;
    }
    
    /* Mejora de alertas */
    .alert {
        border-left: 4px solid;
        animation: slideInDown 0.5s ease;
    }
    
    @keyframes slideInDown {
        from {
            opacity: 0;
            transform: translateY(-20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    /* Input groups mejorados */
    .input-group-text {
        background-color: #fff;
        border-right: none;
    }
    
    .input-group .form-control {
        border-left: none;
    }
    
    .input-group:focus-within .input-group-text {
        border-color: #ffc107;
    }
</style>
@stop

@section('js')
<script>
    console.log('✅ Vista de edición de proveedor cargada correctamente');

    $(document).ready(function() {
        
        // Validación en tiempo real para el campo de contacto
        $('#contacto').on('input', function() {
            this.value = this.value.replace(/[^0-9]/g, '');
            
            // Feedback visual
            if (this.value.length >= 7 && this.value.length <= 15) {
                $(this).removeClass('is-invalid').addClass('is-valid');
            } else if (this.value.length > 0) {
                $(this).removeClass('is-valid').addClass('is-invalid');
            } else {
                $(this).removeClass('is-valid is-invalid');
            }
        });

        // Confirmación antes de enviar el formulario
        $('form').on('submit', function(e) {
            const nombre = $('#nombre').val();
            const confirmacion = confirm(`¿Está seguro de actualizar los datos del proveedor "${nombre}"?`);
            
            if (!confirmacion) {
                e.preventDefault();
            } else {
                // Deshabilitar botón para evitar doble envío
                $(this).find('button[type="submit"]').prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Guardando...');
            }
        });

        // Tooltip para campos
        $('[data-toggle="tooltip"]').tooltip();

        // Auto-dismiss para alertas después de 5 segundos
        setTimeout(function() {
            $('.alert').fadeOut('slow');
        }, 5000);

    });
</script>
@stop