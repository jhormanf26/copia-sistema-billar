@extends('adminlte::page')

@section('title', 'Detalle de Compra #' . $compra->id)

@section('content_header')
    <div class="container-fluid">
        <div class="row align-items-center justify-content-between">
            <div class="col">
                <h1 class="text-dark"><i class="fas fa-receipt mr-2"></i> Detalle de Compra #{{ $compra->id }}</h1>
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
    {{-- INFORMACIÓN GENERAL --}}
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card card-outline card-primary shadow-lg">
                <div class="card-header bg-primary">
                    <h3 class="card-title"><i class="fas fa-info-circle mr-2"></i> Información de la Compra</h3>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-sm-5">
                            <h6 class="mb-0 text-muted">ID de Compra</h6>
                        </div>
                        <div class="col-sm-7">
                            <strong>#{{ $compra->id }}</strong>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-sm-5">
                            <h6 class="mb-0 text-muted">Fecha</h6>
                        </div>
                        <div class="col-sm-7">
                            <strong>
                                @if(is_object($compra->fecha_compra))
                                    {{ $compra->fecha_compra->format('d/m/Y H:i') }}
                                @else
                                    {{ \Carbon\Carbon::parse($compra->fecha_compra)->format('d/m/Y H:i') }}
                                @endif
                            </strong>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-5">
                            <h6 class="mb-0 text-muted">Estado</h6>
                        </div>
                        <div class="col-sm-7">
                            <span class="badge badge-success">Completada</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card card-outline card-info shadow-lg">
                <div class="card-header bg-info">
                    <h3 class="card-title"><i class="fas fa-truck mr-2"></i> Información del Proveedor</h3>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-sm-5">
                            <h6 class="mb-0 text-muted">Proveedor</h6>
                        </div>
                        <div class="col-sm-7">
                            <strong>{{ $compra->proveedor->nombre }}</strong>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-sm-5">
                            <h6 class="mb-0 text-muted">Contacto</h6>
                        </div>
                        <div class="col-sm-7">
                            <strong>{{ $compra->proveedor->contacto ?? 'N/A' }}</strong>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-5">
                            <h6 class="mb-0 text-muted">Dirección</h6>
                        </div>
                        <div class="col-sm-7">
                            <strong>{{ $compra->proveedor->direccion ?? 'N/A' }}</strong>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- DETALLES DE COMPRA --}}
    <div class="card card-outline card-success shadow-lg mb-4">
        <div class="card-header bg-success">
            <h3 class="card-title"><i class="fas fa-box mr-2"></i> Detalles de la Compra</h3>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead class="bg-light">
                        <tr>
                            <th>Producto</th>
                            <th>Cantidad</th>
                            <th>Precio Unitario</th>
                            <th>Precio Venta</th>
                            <th>Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($compra->detalles as $detalle)
                            <tr>
                                <td>{{ $detalle->producto->nombre }}</td>
                                <td><span class="badge badge-info">{{ $detalle->cantidad }}</span></td>
                                <td>${{ number_format($detalle->precio_compra, 2, ',', '.') }}</td>
                                <td>${{ $detalle->precio_venta ? number_format($detalle->precio_venta, 2, ',', '.') : 'N/A' }}</td>
                                <td><strong>${{ number_format($detalle->subtotal, 2, ',', '.') }}</strong></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- RESUMEN FINANCIERO --}}
    <div class="row">
        <div class="col-md-4 ml-auto">
            <div class="card card-outline card-warning shadow-lg">
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-8">
                            <h6 class="text-muted">Cantidad de Ítems</h6>
                        </div>
                        <div class="col-4 text-right">
                            <h5>{{ $compra->detalles->count() }}</h5>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-8">
                            <h6 class="text-muted">Costo Total</h6>
                        </div>
                        <div class="col-4 text-right">
                            <h4 class="text-success">${{ number_format($compra->total, 2, ',', '.') }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ACCIONES --}}
    <div class="mt-4">
        <a href="{{ route('compras.edit', $compra) }}" class="btn btn-warning btn-lg">
            <i class="fas fa-edit mr-2"></i> Editar
        </a>
        <form action="{{ route('compras.destroy', $compra) }}" method="POST" style="display:inline;" onsubmit="return confirm('¿Estás seguro de que deseas eliminar esta compra?')">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-danger btn-lg">
                <i class="fas fa-trash mr-2"></i> Eliminar
            </button>
        </form>
        <a href="{{ route('compras.index') }}" class="btn btn-secondary btn-lg">
            <i class="fas fa-arrow-left mr-2"></i> Volver
        </a>
    </div>
</div>
@endsection
