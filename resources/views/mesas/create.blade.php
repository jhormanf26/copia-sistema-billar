@extends('adminlte::page')

@section('title', 'Nueva Mesa')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1 class="m-0 text-dark">
            <i class="fas fa-plus-circle text-primary"></i> Agregar Nueva Mesa
        </h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb bg-transparent p-0 m-0">
                <li class="breadcrumb-item"><a href="{{ route('mesas.index') }}">Mesas</a></li>
                <li class="breadcrumb-item active">Nueva Mesa</li>
            </ol>
        </nav>
    </div>
@stop

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-6 offset-lg-3">

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
                        Complete todos los campos requeridos (<span class="text-danger">*</span>) para registrar una nueva mesa.
                        Verifique que el número de mesa no esté repetido.
                    </small>
                </div>
            </div>

            {{-- Card Principal --}}
            <div class="card card-primary card-outline shadow-lg">
                <div class="card-header bg-gradient-primary">
                    <h3 class="card-title font-weight-bold">
                        <i class="fas fa-table"></i> Formulario de Registro de Mesa
                    </h3>
                    <div class="card-tools">
                        <span class="badge badge-light">
                            <i class="far fa-clock"></i> {{ now()->format('d/m/Y') }}
                        </span>
                    </div>
                </div>

                <form action="{{ route('mesas.store') }}" method="POST" id="formMesa">
                    @csrf
                    <div class="card-body">

                        {{-- Número de Mesa --}}
                        <div class="form-group mt-3">
                            <label for="numeromesa" class="font-weight-bold">
                                <i class="fas fa-hashtag text-primary"></i> Número de Mesa
                                <span class="text-danger">*</span>
                            </label>
                            <input type="text" id="numeromesa" name="numeromesa"
                                class="form-control @error('numeromesa') is-invalid @enderror"
                                value="{{ old('numeromesa') }}" required maxlength="10">
                            @error('numeromesa')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Tipo --}}
                        <div class="form-group">
                            <label for="tipo" class="font-weight-bold">
                                <i class="fas fa-cogs text-info"></i> Tipo
                                <span class="text-danger">*</span>
                            </label>
                            <select id="tipo" name="tipo" class="form-control @error('tipo') is-invalid @enderror" required>
                                <option value="">Seleccione...</option>
                                <option value="pool" {{ old('tipo') == 'pool' ? 'selected' : '' }}>Pool</option>
                                <option value="tresbandas" {{ old('tipo') == 'tresbandas' ? 'selected' : '' }}>Tres Bandas</option>
                                <option value="libre" {{ old('tipo') == 'libre' ? 'selected' : '' }}>Libre</option>
                                <option value="consumo" {{ old('tipo') == 'consumo' ? 'selected' : '' }}>Consumo</option>
                            </select>
                            @error('tipo')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Estado --}}
                        <div class="form-group">
                            <label for="estado" class="font-weight-bold">
                                <i class="fas fa-toggle-on text-success"></i> Estado
                                <span class="text-danger">*</span>
                            </label>
                            <select id="estado" name="estado" class="form-control @error('estado') is-invalid @enderror" required>
                                <option value="">Seleccione...</option>
                                <option value="disponible" {{ old('estado') == 'disponible' ? 'selected' : '' }}>Disponible</option>
                                <option value="ocupada" {{ old('estado') == 'ocupada' ? 'selected' : '' }}>Ocupada</option>
                                <option value="reservada" {{ old('estado') == 'reservada' ? 'selected' : '' }}>Reservada</option>
                            </select>
                            @error('estado')
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
                            <a href="{{ route('mesas.index') }}" class="btn btn-secondary shadow-sm">
                                <i class="fas fa-times"></i> Cancelar
                            </a>
                            <button type="submit" id="btnGuardar" class="btn btn-success shadow-sm ml-2">
                                <i class="fas fa-save"></i> Guardar Mesa
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
</style>
@stop

@section('js')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    $('#formMesa').on('submit', function(e){
        e.preventDefault();

        Swal.fire({
            title: '¿Confirmar Registro?',
            html: `Está a punto de crear la mesa:<br><strong>"${$('#numeromesa').val()}"</strong>`,
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
@stop
