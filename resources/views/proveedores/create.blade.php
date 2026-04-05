@extends('adminlte::page')

@section('title', 'Crear Proveedor')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1 class="m-0 text-dark">
            <i class="fas fa-truck-moving text-primary"></i> Crear Nuevo Proveedor
        </h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb bg-transparent p-0 m-0">
                <li class="breadcrumb-item"><a href="{{ route('proveedores.index') }}">Proveedores</a></li>
                <li class="breadcrumb-item active">Crear Nuevo</li>
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

            {{-- Card de Instrucciones --}}
            <div class="card card-info shadow-sm mb-3">
                <div class="card-body p-3">
                    <h6 class="mb-2"><i class="fas fa-info-circle"></i> <strong>Instrucciones</strong></h6>
                    <small class="text-muted">
                        Complete todos los campos requeridos (<span class="text-danger">*</span>) para registrar un nuevo proveedor en el sistema.
                        Asegúrese de que el ID sea único y no esté duplicado.
                    </small>
                </div>
            </div>

            {{-- Card Principal con Sombra y Bordes Redondeados --}}
            <div class="card card-primary card-outline shadow-lg">
                <div class="card-header bg-gradient-primary">
                    <h3 class="card-title font-weight-bold">
                        <i class="fas fa-user-plus"></i> Formulario de Registro
                    </h3>
                    <div class="card-tools">
                        <span class="badge badge-light">
                            <i class="far fa-clock"></i> {{ now()->format('d/m/Y') }}
                        </span>
                    </div>
                </div>
                
                <form action="{{ route('proveedores.store') }}" method="POST" id="formProveedor">
                    @csrf
                    
                    <div class="card-body">
                        
                        {{-- Sección 1: Identificación --}}
                        <div class="mb-4">
                            <h5 class="text-primary border-bottom pb-2">
                                <i class="fas fa-id-card"></i> Identificación
                            </h5>

                            {{-- CAMPO ID PROVEEDOR --}}
                            <div class="form-group mt-3">
                                <label for="idproveedor" class="font-weight-bold">
                                    <i class="fas fa-hashtag text-primary"></i> ID Proveedor
                                    <span class="text-danger">*</span>
                                </label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text bg-primary text-white">
                                            <i class="fas fa-key"></i>
                                        </span>
                                    </div>
                                    <input type="text" 
                                           name="idproveedor" 
                                           id="idproveedor" 
                                           class="form-control @error('idproveedor') is-invalid @enderror" 
                                           placeholder="Ej: 10001" 
                                           value="{{ old('idproveedor') }}" 
                                           required
                                           autofocus
                                           inputmode="numeric" 
                                           pattern="[0-9]*" 
                                           maxlength="10"
                                           title="Solo se permiten números para el ID">
                                    @error('idproveedor') 
                                        <div class="invalid-feedback">
                                            <i class="fas fa-exclamation-circle"></i> {{ $message }}
                                        </div> 
                                    @enderror
                                </div>
                                <small class="form-text text-muted">
                                    <i class="fas fa-lightbulb"></i> Este ID debe ser único y no puede repetirse
                                </small>
                            </div>

                            {{-- Campo Nombre --}}
                            <div class="form-group">
                                <label for="nombre" class="font-weight-bold">
                                    <i class="fas fa-building text-info"></i> Nombre del Proveedor
                                    <span class="text-danger">*</span>
                                </label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">
                                            <i class="fas fa-user-tie"></i>
                                        </span>
                                    </div>
                                    <input type="text" 
                                           name="nombre" 
                                           id="nombre" 
                                           class="form-control @error('nombre') is-invalid @enderror" 
                                           placeholder="Ej: Distribuidora ABC S.A.S" 
                                           value="{{ old('nombre') }}" 
                                           required
                                           maxlength="100"
                                           oninput="this.value = this.value.replace(/[^a-zA-Z\s]/g, '')">
                                    @error('nombre')  
                                        <div class="invalid-feedback">
                                            <i class="fas fa-exclamation-circle"></i> {{ $message }}
                                        </div> 
                                    @enderror
                                </div>
                                <small class="form-text text-muted">
                                    <i class="fas fa-info-circle"></i> Ingrese el nombre completo o razón social
                                </small>
                            </div>
                        </div>

                        <hr class="my-4">

                        {{-- Sección 2: Información de Contacto --}}
                        <div class="mb-4">
                            <h5 class="text-success border-bottom pb-2">
                                <i class="fas fa-address-book"></i> Información de Contacto
                            </h5>

                            {{-- Campo Contacto --}}
                            <div class="form-group mt-3">
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
                                           value="{{ old('contacto') }}" 
                                           required 
                                           inputmode="numeric" 
                                           pattern="[0-9]*" 
                                           maxlength="15"
                                           title="Solo se permiten números">
                                    @error('contacto') 
                                        <div class="invalid-feedback">
                                            <i class="fas fa-exclamation-circle"></i> {{ $message }}
                                        </div> 
                                    @enderror
                                </div>
                                <small class="form-text text-muted">
                                    <i class="fas fa-info-circle"></i> Solo números, sin espacios ni caracteres especiales (7-15 dígitos)
                                </small>
                            </div>

                            {{-- Campo Dirección --}}
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
                                           placeholder="Ej: Calle 123 #45-67, Bucaramanga, Santander" 
                                           value="{{ old('direccion') }}" 
                                           required
                                           maxlength="200">
                                    @error('direccion') 
                                        <div class="invalid-feedback">
                                            <i class="fas fa-exclamation-circle"></i> {{ $message }}
                                        </div> 
                                    @enderror
                                </div>
                                <small class="form-text text-muted">
                                    <i class="fas fa-info-circle"></i> Dirección completa incluyendo ciudad y departamento
                                </small>
                            </div>
                        </div>

                        {{-- Resumen de Campos Completados --}}
                        <div class="alert alert-light border" id="resumenCampos" style="display: none;">
                            <h6 class="mb-2"><i class="fas fa-check-circle text-success"></i> <strong>Resumen</strong></h6>
                            <div class="small">
                                <div><strong>ID:</strong> <span id="resumenID">-</span></div>
                                <div><strong>Nombre:</strong> <span id="resumenNombre">-</span></div>
                                <div><strong>Contacto:</strong> <span id="resumenContacto">-</span></div>
                                <div><strong>Dirección:</strong> <span id="resumenDireccion">-</span></div>
                            </div>
                        </div>

                    </div>
                    
                    {{-- Footer con Botones Mejorados --}}
                    <div class="card-footer bg-light d-flex justify-content-between align-items-center">
                        <div>
                            <small class="text-muted">
                                <i class="fas fa-asterisk text-danger"></i> Campos obligatorios
                            </small>
                        </div>
                        <div>
                            <a href="{{ route('proveedores.index') }}" class="btn btn-secondary shadow-sm">
                                <i class="fas fa-times"></i> Cancelar
                            </a>
                            <button type="submit" class="btn btn-success shadow-sm ml-2" id="btnGuardar">
                                <i class="fas fa-save"></i> Guardar Proveedor
                            </button>
                        </div>
                    </div>
                </form>
            </div>

            {{-- Card de Ayuda --}}
            <div class="card card-secondary shadow-sm mt-3">
                <div class="card-body p-3">
                    <div class="row">
                        <div class="col-md-6">
                            <h6 class="text-secondary"><i class="fas fa-question-circle"></i> <strong>¿Necesitas ayuda?</strong></h6>
                            <small class="text-muted">
                                Si tienes dudas sobre cómo llenar el formulario, contacta al administrador del sistema.
                            </small>
                        </div>
                        <div class="col-md-6 text-right">
                            <small class="text-muted">
                                <i class="fas fa-shield-alt"></i> Los datos serán almacenados de forma segura
                            </small>
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
    /* Animaciones suaves */
    .card {
        transition: all 0.3s ease;
    }
    
    .card:hover {
        transform: translateY(-2px);
    }
    
    /* Mejora visual de inputs en focus */
    .form-control:focus {
        border-color: #007bff;
        box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
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
        border-color: #007bff;
    }
    
    /* Secciones del formulario */
    .border-bottom {
        border-bottom: 3px solid !important;
    }
    
    /* Estado de validación positiva */
    .form-control.is-valid {
        border-color: #28a745;
        padding-right: calc(1.5em + .75rem);
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 8 8'%3e%3cpath fill='%2328a745' d='M2.3 6.73L.6 4.53c-.4-1.04.46-1.4 1.1-.8l1.1 1.4 3.4-3.8c.6-.63 1.6-.27 1.2.7l-4 4.6c-.43.5-.8.4-1.1.1z'/%3e%3c/svg%3e");
        background-repeat: no-repeat;
        background-position: right calc(.375em + .1875rem) center;
        background-size: calc(.75em + .375rem) calc(.75em + .375rem);
    }
    
    /* Contador de caracteres */
    .char-counter {
        font-size: 0.85rem;
        color: #6c757d;
    }
    
    /* Progreso del formulario */
    #resumenCampos {
        animation: fadeIn 0.5s ease;
    }
    
    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }
</style>
@stop

@section('js')
<script>
    console.log('✅ Vista de creación de proveedor cargada correctamente');

    $(document).ready(function() {
        
        // Validación en tiempo real para ID Proveedor
        $('#idproveedor').on('input', function() {
            this.value = this.value.replace(/[^0-9]/g, '');
            validarCampo($(this), this.value.length > 0 && this.value.length <= 10);
            actualizarResumen();
        });

        // Validación en tiempo real para Nombre
        $('#nombre').on('input', function() {
            validarCampo($(this), this.value.trim().length >= 3);
            actualizarResumen();
        });

        // Validación en tiempo real para Contacto
        $('#contacto').on('input', function() {
            this.value = this.value.replace(/[^0-9]/g, '');
            const valido = this.value.length >= 7 && this.value.length <= 15;
            validarCampo($(this), valido);
            actualizarResumen();
        });

        // Validación para Dirección
        $('#direccion').on('input', function() {
            validarCampo($(this), this.value.trim().length >= 10);
            actualizarResumen();
        });

        // Función para validar visualmente un campo
        function validarCampo($campo, esValido) {
            if (esValido) {
                $campo.removeClass('is-invalid').addClass('is-valid');
            } else if ($campo.val().length > 0) {
                $campo.removeClass('is-valid').addClass('is-invalid');
            } else {
                $campo.removeClass('is-valid is-invalid');
            }
        }

        // Actualizar resumen de campos
        function actualizarResumen() {
            const id = $('#idproveedor').val();
            const nombre = $('#nombre').val();
            const contacto = $('#contacto').val();
            const direccion = $('#direccion').val();

            if (id || nombre || contacto || direccion) {
                $('#resumenCampos').show();
                $('#resumenID').text(id || '-');
                $('#resumenNombre').text(nombre || '-');
                $('#resumenContacto').text(contacto || '-');
                $('#resumenDireccion').text(direccion || '-');
            } else {
                $('#resumenCampos').hide();
            }
        }

        // Confirmación antes de enviar el formulario
        $('#formProveedor').on('submit', function(e) {
            e.preventDefault();
            
            const nombre = $('#nombre').val();
            const id = $('#idproveedor').val();
            
            Swal.fire({
                title: '¿Confirmar registro?',
                html: `Está a punto de crear el proveedor:<br><strong>"${nombre}"</strong><br>con ID: <strong>${id}</strong>`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#28a745',
                cancelButtonColor: '#6c757d',
                confirmButtonText: '<i class="fas fa-check"></i> Sí, crear proveedor',
                cancelButtonText: '<i class="fas fa-times"></i> Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Deshabilitar botón y mostrar loading
                    $('#btnGuardar').prop('disabled', true)
                        .html('<i class="fas fa-spinner fa-spin"></i> Guardando...');
                    
                    // Enviar formulario
                    this.submit();
                }
            });
        });

        // Tooltip para ayuda
        $('[data-toggle="tooltip"]').tooltip();

        // Auto-dismiss para alertas después de 8 segundos
        setTimeout(function() {
            $('.alert').not('.alert-light').fadeOut('slow');
        }, 8000);

        // Prevenir envío con Enter (excepto en el botón submit)
        $('#formProveedor input').on('keypress', function(e) {
            if (e.which === 13 && e.target.type !== 'submit') {
                e.preventDefault();
                // Mover al siguiente campo
                const inputs = $('#formProveedor input:visible');
                const nextInput = inputs.eq(inputs.index(this) + 1);
                if (nextInput.length) {
                    nextInput.focus();
                }
            }
        });

    });
</script>

{{-- SweetAlert2 para confirmaciones modernas --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@stop