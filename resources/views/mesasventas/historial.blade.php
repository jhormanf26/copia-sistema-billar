@extends('adminlte::page')

@section('title', 'Historial de Ventas de Mesas')

@section('content_header')
<h1><i class="fas fa-history"></i> Ventas</h1>
@stop

@section('content')
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>

<style>
    .table-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
    }
    .badge-estado {
        padding: 0.5rem 0.75rem;
        border-radius: 20px;
        font-size: 0.85rem;
        font-weight: 500;
    }
    .badge-completada {
        background-color: #28a745;
        color: white;
    }
    .badge-activa {
        background-color: #ffc107;
        color: black;
    }
    .card {
        border-radius: 10px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        margin-bottom: 20px;
    }
    .btn-sm {
        padding: 0.35rem 0.55rem;
        font-size: 0.8rem;
    }
    .total-highlight {
        background-color: #fff3cd;
        padding: 10px;
        border-radius: 5px;
        font-weight: 600;
    }
</style>

<div class="container-fluid">
    {{-- Botón para volver --}}
    <div class="mb-3">
        <a href="{{ route('welcome') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Volver al Inicio
        </a>
    </div>

    {{-- Mensajes flash --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    {{-- Resumen de estadísticas --}}
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card">
                <div class="card-body text-center">
                    <h6 class="text-muted">Total de Ventas</h6>
                    <h3 class="text-primary">{{ count($ventas) }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body text-center">
                    <h6 class="text-muted">Ingresos Totales</h6>
                    <h3 class="text-success">${{ number_format($ingresoTotal, 2, ',', '.') }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body text-center">
                    <h6 class="text-muted">Ventas Completadas</h6>
                    <h3 class="text-info">{{ $ventasCompletadas }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body text-center">
                    <h6 class="text-muted">Ventas Activas</h6>
                    <h3 class="text-warning">{{ $ventasActivas }}</h3>
                </div>
            </div>
        </div>
    </div>

    {{-- Tabla de registros --}}
    <div class="card">
        <div class="card-header table-header">
            <h5 class="mb-0"><i class="fas fa-table"></i> Registro de Todas las Ventas</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                {{-- Lista de registros en grid (cada venta en su propio container) --}}
                <div class="row g-3">
                    @foreach($ventas as $venta)
                    <div class="col-sm-12 col-md-6 col-lg-4">
                        <div class="card venta-card">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div>
                                        <h5 class="mb-1">Venta #{{ $venta->id }} <small class="text-muted">- Mesa #{{ $venta->mesa->numeromesa ?? 'N/A' }}</small></h5>
                                        <div class="mb-1">
                                            <span class="badge badge-info">{{ $venta->mesa->tipo ?? 'N/A' }}</span>
                                        </div>
                                        <div class="small text-muted">
                                            Inicio: @if($venta->created_at){{ \Carbon\Carbon::parse($venta->created_at)->format('d/m/Y H:i') }}@else - @endif
                                            &nbsp;•&nbsp;
                                            Fin: @if($venta->fechafin){{ \Carbon\Carbon::parse($venta->fechafin)->format('d/m/Y H:i') }}@else - @endif
                                        </div>
                                    </div>
                                    <div class="text-end">
                                        <div class="total-success mb-2">${{ number_format($venta->total_con_tiempo ?? $venta->total, 2, ',', '.') }}</div>
                                        <div>
                                            @if($venta->fechafin)
                                                <span class="badge badge-estado badge-completada"><i class="fas fa-check-circle"></i> Completada</span>
                                            @else
                                                <span class="badge badge-estado badge-activa"><i class="fas fa-hourglass-start"></i> Activa</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <div class="mt-3 d-flex flex-wrap gap-2">
                                    <button class="btn btn-action btn-info" data-bs-toggle="modal" data-bs-target="#modalProductos{{ $venta->id }}">
                                        <i class="fas fa-box"></i> Productos ({{ $venta->productos->count() }})
                                    </button>
                                    <button class="btn btn-action btn-primary" data-bs-toggle="modal" data-bs-target="#modalDetalles{{ $venta->id }}">
                                        <i class="fas fa-eye"></i> Detalles
                                    </button>
                                    {{-- En historial no se muestra el botón Finalizar para evitar cerrar ventas desde aquí --}}
                                </div>
                            </div>
                        </div>

                        {{-- Modal de Productos (mismo contenido que anteriormente) --}}
                        <div class="modal fade" id="modalProductos{{ $venta->id }}" tabindex="-1">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <div class="modal-header modal-header-custom">
                                        <h5 class="modal-title"><i class="fas fa-box me-2"></i>Productos - Venta #{{ $venta->id }}</h5>
                                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body p-4">
                                        @if($venta->productos->count() > 0)
                                            <table class="table table-products table-hover">
                                                <thead>
                                                    <tr>
                                                        <th>Producto</th>
                                                        <th class="text-center">Cantidad</th>
                                                        <th class="text-end">Precio Unitario</th>
                                                        <th class="text-end">Subtotal</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($venta->productos as $producto)
                                                    <tr>
                                                        <td>
                                                            <strong>{{ $producto->nombre }}</strong>
                                                            @if($producto->idproveedor == 5)
                                                                <br><small class="text-muted"><i class="fas fa-clock"></i> (Cargo por Tiempo)</small>
                                                            @endif
                                                        </td>
                                                        <td class="text-center"><span class="badge bg-secondary">{{ $producto->pivot->cantidad }}</span></td>
                                                        <td class="text-end">${{ number_format($producto->pivot->precio_unitario, 2, ',', '.') }}</td>
                                                        <td class="text-end"><strong>${{ number_format($producto->pivot->subtotal, 2, ',', '.') }}</strong></td>
                                                    </tr>
                                                    @endforeach
                                                </tbody>
                                                <tfoot>
                                                    <tr>
                                                        <td colspan="3" class="text-end"><strong>TOTAL:</strong></td>
                                                        <td class="text-end"><strong>${{ number_format($venta->productos->sum(fn($p) => $p->pivot->subtotal), 2, ',', '.') }}</strong></td>
                                                    </tr>
                                                </tfoot>
                                            </table>
                                        @else
                                            <div class="text-center py-5">
                                                <i class="fas fa-box-open fa-3x text-muted mb-3"></i>
                                                <p class="text-muted">No hay productos asociados a esta venta.</p>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Modal de Detalles --}}
                        <div class="modal fade" id="modalDetalles{{ $venta->id }}" tabindex="-1">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header modal-header-custom">
                                        <h5 class="modal-title"><i class="fas fa-info-circle me-2"></i>Detalles de Venta #{{ $venta->id }}</h5>
                                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body p-4">
                                        <div class="row detail-row">
                                            <div class="col-6">
                                                <div class="detail-label">ID Venta:</div>
                                                <div class="detail-value">#{{ $venta->id }}</div>
                                            </div>
                                            <div class="col-6">
                                                <div class="detail-label">Mesa:</div>
                                                <div class="detail-value">Mesa #{{ $venta->mesa->numeromesa ?? 'N/A' }}</div>
                                            </div>
                                        </div>
                                        <div class="row detail-row">
                                            <div class="col-6">
                                                <div class="detail-label">Tipo de Mesa:</div>
                                                <div class="detail-value">{{ ucfirst($venta->mesa->tipo ?? 'N/A') }}</div>
                                            </div>
                                            <div class="col-6">
                                                <div class="detail-label">Estado:</div>
                                                <div class="mt-2">
                                                    @if($venta->fechafin)
                                                        <span class="badge badge-success">Completada</span>
                                                    @else
                                                        <span class="badge badge-warning">Activa</span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        <hr class="my-4">
                                        <div class="row detail-row">
                                            <div class="col-12">
                                                <div class="detail-label"><i class="fas fa-calendar-plus me-1"></i> Fecha de Inicio:</div>
                                                <div class="detail-value">
                                                    @if($venta->created_at)
                                                        {{ \Carbon\Carbon::parse($venta->created_at)->format('d/m/Y H:i:s') }}
                                                    @else
                                                        <span class="text-muted">-</span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row detail-row">
                                            <div class="col-12">
                                                <div class="detail-label"><i class="fas fa-calendar-check me-1"></i> Fecha de Finalización:</div>
                                                <div class="detail-value">
                                                    @if($venta->fechafin)
                                                        {{ \Carbon\Carbon::parse($venta->fechafin)->format('d/m/Y H:i:s') }}
                                                    @else
                                                        <span class="text-warning">Aún activa</span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        <hr class="my-4">
                                        <div class="row detail-row">
                                            <div class="col-6">
                                                <div class="detail-label">Total Productos:</div>
                                                <div class="detail-value text-primary">${{ number_format($venta->total, 2, ',', '.') }}</div>
                                            </div>
                                            
                                        </div>
                                        <div class="row detail-row">
                                            
                                        </div>
                                        <div class="row detail-row">
                                            <div class="col-12">
                                                <div class="detail-label">Método de Pago:</div>
                                                <div class="mt-2">
                                                    @if($venta->metodo_pago)
                                                        <span class="badge badge-primary">{{ ucfirst($venta->metodo_pago) }}</span>
                                                    @else
                                                        <span class="text-muted">No especificado</span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row detail-row">
                                            <div class="col-12">
                                                <div class="detail-label">Productos Asociados:</div>
                                                <div class="detail-value">
                                                    <span class="badge bg-info">{{ $venta->productos->count() }} producto(s)</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                            <i class="fas fa-times me-1"></i> Cerrar
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
                   
                
</script>

@stop
