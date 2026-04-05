@extends('adminlte::page')

@section('title', 'Crear Producto')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1 class="m-0 text-dark">
            <i class="fas fa-box-open text-primary"></i> Crear Nuevo Producto
        </h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb bg-transparent p-0 m-0">
                <li class="breadcrumb-item"><a href="{{ route('productos.index') }}">Productos</a></li>
                <li class="breadcrumb-item active">Crear Nuevo</li>
            </ol>
        </nav>
    </div>
@stop

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-8 offset-lg-2">

            {{-- Alerta Errores --}}
            @if ($errors->any())
                <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
                    <h5 class="alert-heading"><i class="fas fa-exclamation-triangle"></i> Errores de validación</h5>
                    <hr>
                    <ul class="mb-0 pl-3">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="close" data-dismiss="alert">
                        <span>&times;</span>
                    </button>
                </div>
            @endif

            {{-- Tarjeta de instrucciones --}}
            <div class="card card-info shadow-sm mb-3">
                <div class="card-body p-3">
                    <h6 class="mb-2"><i class="fas fa-info-circle"></i> <strong>Instrucciones</strong></h6>
                    <small class="text-muted">
                        Complete todos los campos requeridos (<span class="text-danger">*</span>) para registrar un nuevo producto.
                        Asegúrese de ingresar correctamente el precio y el stock disponible.
                    </small>
                </div>
            </div>

            {{-- Card Principal --}}
            <div class="card card-primary card-outline shadow-lg">
                <div class="card-header bg-gradient-primary">
                    <h3 class="card-title font-weight-bold">
                        <i class="fas fa-tag"></i> Formulario de Registro
                    </h3>
                    <div class="card-tools">
                        <span class="badge badge-light">
                            <i class="far fa-clock"></i> {{ now()->format('d/m/Y') }}
                        </span>
                    </div>
                </div>

                <form action="{{ route('productos.store') }}" method="POST" id="formProducto">
                    @csrf

                    <div class="card-body">

                        {{-- Sección Información del Producto --}}
                        <h5 class="text-primary border-bottom pb-2"><i class="fas fa-box"></i> Información del Producto</h5>

                        {{-- Nombre --}}
                        <div class="form-group mt-3">
                            <label for="nombre" class="font-weight-bold">
                                <i class="fas fa-box-open text-primary"></i> Nombre del Producto
                                <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <span class="input-group-text bg-primary text-white"><i class="fas fa-font"></i></span>
                                <input type="text" id="nombre" name="nombre"
                                    class="form-control @error('nombre') is-invalid @enderror"
                                    value="{{ old('nombre') }}" required maxlength="100">
                            </div>
                            @error('nombre')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Descripción --}}
                        <div class="form-group">
                            <label for="descripcion" class="font-weight-bold">
                                <i class="fas fa-align-left text-info"></i> Descripción
                                <span class="text-danger">*</span>
                            </label>
                            <textarea id="descripcion" name="descripcion"
                                class="form-control @error('descripcion') is-invalid @enderror"
                                rows="3" required>{{ old('descripcion') }}</textarea>
                            @error('descripcion')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Precio --}}
                        <div class="form-group">
                            <label for="precio" class="font-weight-bold">
                                <i class="fas fa-dollar-sign text-success"></i> Precio
                                <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-money-bill-wave"></i></span>
                                <input type="number" step="0.01" id="precio" name="precio"
                                    class="form-control @error('precio') is-invalid @enderror"
                                    value="{{ old('precio') }}" required>
                            </div>
                            @error('precio')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Stock --}}
                        <div class="form-group">
                            <label for="stock" class="font-weight-bold">
                                <i class="fas fa-cubes text-warning"></i> Stock Disponible
                                <span class="text-danger">*</span>
                            </label>
                            <input type="number" id="stock" name="stock"
                                class="form-control @error('stock') is-invalid @enderror"
                                value="{{ old('stock') }}" required min="0">
                            @error('stock')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <hr class="my-4">

                        {{-- Proveedor --}}
                        <h5 class="text-success border-bottom pb-2">
                            <i class="fas fa-truck"></i> Proveedor
                        </h5>

                        <div class="form-group mt-3">
                            <label for="idproveedor" class="font-weight-bold">
                                <i class="fas fa-user-tie text-success"></i> Seleccione un proveedor
                                <span class="text-danger">*</span>
                            </label>
                            <select name="idproveedor" id="idproveedor"
                                class="form-control @error('idproveedor') is-invalid @enderror" required>
                                <option value="">Seleccione...</option>
                                @foreach ($proveedores as $prov)
                                    <option value="{{ $prov->idproveedor }}"
                                        {{ old('idproveedor') == $prov->idproveedor ? 'selected' : '' }}>
                                        {{ $prov->nombre }}
                                    </option>
                                @endforeach
                            </select>
                            @error('idproveedor')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                    </div>

                    {{-- Footer --}}
                    <div class="card-footer bg-light d-flex justify-content-between align-items-center">
                        <small class="text-muted">
                            <i class="fas fa-asterisk text-danger"></i> campos obligatorios
                        </small>

                        <div>
                            <a href="{{ route('productos.index') }}" class="btn btn-secondary shadow-sm">
                                <i class="fas fa-times"></i> Cancelar
                            </a>
                            <button type="submit" id="btnGuardar" class="btn btn-success shadow-sm ml-2">
                                <i class="fas fa-save"></i> Guardar Producto
                            </button>
                        </div>
                    </div>

                </form>
            </div>

        </div>
    </div>
</div>
@stop

@section('css')
<style>
    .card { transition: all 0.3s ease; }
    .card:hover { transform: translateY(-2px); }
    .btn { transition: 0.3s; }
    .btn:hover { transform: translateY(-2px); }
    .form-control:focus {
        border-color: #007bff;
        box-shadow: 0px 0px 5px rgba(0,123,255,0.3);
    }
    .border-bottom { border-bottom: 3px solid !important; }
</style>
@stop

@section('js')
<script>
    $('#formProducto').on('submit', function(e){
        e.preventDefault();

        Swal.fire({
            title: '¿Confirmar Registro?',
            html: `Está a punto de crear el producto:<br><strong>"${$('#nombre').val()}"</strong>`,
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#28a745',
            cancelButtonColor: '#6c757d',
            confirmButtonText: '<i class="fas fa-check"></i> Confirmar',
            cancelButtonText: '<i class="fas fa-times"></i> Cancelar',
        }).then((result)=>{
            if(result.isConfirmed){
                $('#btnGuardar').prop('disabled', true)
                                .html('<i class="fas fa-spinner fa-spin"></i> Guardando...');
                e.target.submit();
            }
        });
    });
</script>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@stop
