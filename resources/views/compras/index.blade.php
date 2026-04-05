@extends('adminlte::page')

@section('title', 'Gestión de Compras')

@section('content_header')
    <div class="container-fluid">
        <div class="row align-items-center justify-content-between py-3">
            <div class="col-md-8">
                <h1 class="text-dark font-weight-bold mb-2">
                    <i class="fas fa-shopping-cart mr-3 text-primary"></i> 
                    Gestión de Compras
                </h1>
                <p class="text-muted mb-0 ml-5 pl-2">
                    <i class="fas fa-clipboard-list mr-2 text-info"></i>
                    Administra tus compras a proveedores
                </p>
            </div>
            <div class="col-md-4 text-right">
                <span class="badge badge-info badge-lg px-4 py-2">
                    <i class="fas fa-receipt mr-2"></i>
                    {{ count($compras) }} Compras
                </span>
            </div>
        </div>
    </div>
@stop

@section('content')
<div class="container-fluid">

    {{-- ALERTA DE ÉXITO --}}
    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
        <div class="d-flex align-items-center">
            <i class="fas fa-check-circle fa-2x mr-3"></i>
            <div>
                <h5 class="alert-heading mb-1">¡Operación Exitosa!</h5>
                <p class="mb-0">{{ session('success') }}</p>
            </div>
        </div>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    @endif

    {{-- ALERTA DE ERROR --}}
    @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
        <div class="d-flex align-items-center">
            <i class="fas fa-exclamation-circle fa-2x mr-3"></i>
            <div>
                <h5 class="alert-heading mb-1">Error</h5>
                <p class="mb-0">{{ session('error') }}</p>
            </div>
        </div>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    @endif

    {{-- BARRA DE ACCIONES --}}
    <div class="card border-0 shadow-lg mb-4">
        <div class="card-body py-3">
            <div class="row align-items-center">
                <div class="col-md-6 mb-2 mb-md-0">
                    <a href="{{ route('welcome') }}" class="btn btn-outline-secondary btn-lg">
                        <i class="fas fa-arrow-left mr-2"></i> Volver al Inicio
                    </a>
                </div>
                <div class="col-md-6 text-md-right">
                    <a href="{{ route('compras.create') }}" class="btn btn-primary btn-lg shadow-sm"> 
                        <i class="fas fa-plus-circle mr-2"></i> Nueva Compra
                    </a>
                </div>
            </div>
        </div>
    </div>

    {{-- TABLA DE COMPRAS --}}
    <div class="card card-outline card-primary shadow-lg">
        <div class="card-header bg-primary">
            <h3 class="card-title">
                <i class="fas fa-list mr-2"></i> Listado de Compras
            </h3>
        </div>
        <div class="card-body">
            @if($compras->isEmpty())
                <div class="alert alert-info text-center py-4">
                    <i class="fas fa-info-circle fa-2x mb-2"></i>
                    <p class="mb-0">No hay compras registradas. <a href="{{ route('compras.create') }}">Crea una nueva compra</a></p>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead class="bg-light">
                            <tr>
                                <th>ID</th>
                                <th>Proveedor</th>
                                <th>Fecha</th>
                                <th>Productos</th>
                                <th>Total</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($compras as $compra)
                                <tr>
                                    <td><strong>#{{ $compra->id }}</strong></td>
                                    <td>{{ $compra->proveedor->nombre ?? 'N/A' }}</td>
                                    <td>
                                        @if(is_object($compra->fecha_compra))
                                            {{ $compra->fecha_compra->format('d/m/Y H:i') }}
                                        @else
                                            {{ \Carbon\Carbon::parse($compra->fecha_compra)->format('d/m/Y H:i') }}
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge badge-info">
                                            {{ $compra->detalles->count() }} item(s)
                                        </span>
                                    </td>
                                    <td>
                                        <strong>${{ number_format($compra->total, 2, ',', '.') }}</strong>
                                    </td>
                                    <td>
                                        <div class="btn-group btn-group-sm" role="group">
                                            <a href="{{ route('compras.show', $compra) }}" class="btn btn-info" title="Ver">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('compras.edit', $compra) }}" class="btn btn-warning" title="Editar">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('compras.destroy', $compra) }}" method="POST" style="display:inline;" onsubmit="return confirm('¿Estás seguro?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger" title="Eliminar">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
