@extends('adminlte::page')

@section('title', 'Editar Compra #' . $compra->id)

@section('content_header')
    <div class="container-fluid">
        <div class="row align-items-center justify-content-between">
            <div class="col">
                <h1 class="text-dark"><i class="fas fa-edit mr-2"></i> Editar Compra #{{ $compra->id }}</h1>
            </div>
            <div class="col-auto">
                <a href="{{ route('compras.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left mr-2"></i> Volver
                </a>
            </div>
        </div>
    </div>
@stop

@section('content')
<div class="container-fluid">
    {{-- ALERTA DE ERRORES --}}
    @if($errors->any())
    <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
        <i class="fas fa-exclamation-circle mr-3"></i>
        <strong>Errores:</strong>
        <ul class="mb-0 mt-2">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
        <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    @endif

    <div class="row">
        <div class="col-md-8">
            <form method="POST" action="{{ route('compras.update', $compra) }}">
                @csrf
                @method('PUT')

                {{-- INFORMACIÓN DE LA COMPRA --}}
                <div class="card card-outline card-primary shadow-lg mb-4">
                    <div class="card-header bg-primary">
                        <h3 class="card-title"><i class="fas fa-info-circle mr-2"></i> Información de la Compra</h3>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <label>ID de Compra</label>
                            <input type="text" class="form-control" value="{{ $compra->id }}" disabled>
                        </div>

                        <div class="form-group">
                            <label>Fecha de Compra</label>
                            <input type="text" class="form-control" value="{{ $compra->fecha_compra ? \Carbon\Carbon::parse($compra->fecha_compra)->format('d/m/Y H:i') : '' }}" disabled>
                        </div>

                        <div class="form-group">
                            <label for="idproveedor">Proveedor <span class="text-danger">*</span></label>
                            <select id="idproveedor" name="idproveedor" class="form-control @error('idproveedor') is-invalid @enderror" required>
                                @foreach($proveedores as $prov)
                                    <option value="{{ $prov->idproveedor }}" {{ $compra->idproveedor == $prov->idproveedor ? 'selected' : '' }}>
                                        {{ $prov->nombre }}
                                    </option>
                                @endforeach
                            </select>
                            @error('idproveedor')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                {{-- ACCIONES --}}
                <div class="card-footer">
                    <button type="submit" class="btn btn-primary btn-lg">
                        <i class="fas fa-save mr-2"></i> Guardar Cambios
                    </button>
                    <a href="{{ route('compras.index') }}" class="btn btn-secondary btn-lg ml-2">
                        <i class="fas fa-times mr-2"></i> Cancelar
                    </a>
                </div>
            </form>
        </div>

        {{-- SIDEBAR CON INFORMACIÓN --}}
        <div class="col-md-4">
            {{-- RESUMEN --}}
            <div class="card card-outline card-info shadow-lg mb-4">
                <div class="card-header bg-info">
                    <h3 class="card-title"><i class="fas fa-chart-pie mr-2"></i> Resumen</h3>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <h6 class="text-muted mb-2">Total Gastado</h6>
                        <h3 class="text-success">${{ number_format($compra->total, 2, ',', '.') }}</h3>
                    </div>
                    <div class="mb-3">
                        <h6 class="text-muted mb-2">Cantidad de Productos</h6>
                        <h4>{{ $compra->detalles->count() }} item(s)</h4>
                    </div>
                    <div>
                        <h6 class="text-muted mb-2">Proveedor Actual</h6>
                        <p class="mb-0"><strong>{{ $compra->proveedor->nombre }}</strong></p>
                    </div>
                </div>
            </div>

            {{-- DETALLES DE COMPRA --}}
            <div class="card card-outline card-success shadow-lg">
                <div class="card-header bg-success">
                    <h3 class="card-title"><i class="fas fa-box mr-2"></i> Productos</h3>
                </div>
                <div class="card-body" style="max-height: 400px; overflow-y: auto;">
                    @foreach($compra->detalles as $detalle)
                        <div class="mb-3 pb-3 border-bottom">
                            <h6 class="mb-1"><strong>{{ $detalle->producto->nombre }}</strong></h6>
                            <small class="text-muted">
                                {{ $detalle->cantidad }} x ${{ number_format($detalle->precio_compra, 2, ',', '.') }} =
                                <strong>${{ number_format($detalle->subtotal, 2, ',', '.') }}</strong>
                            </small>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
