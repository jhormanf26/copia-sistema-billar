@extends('adminlte::page')

@section('title', 'Gestión de Proveedores')

@section('content_header')
    <div class="container-fluid">
        <div class="row align-items-center justify-content-between py-3">
            <div class="col-md-8">
                <h1 class="text-dark font-weight-bold mb-2">
                    <i class="fas fa-truck-loading mr-3 text-primary"></i> 
                    Gestión de Proveedores
                </h1>
                <p class="text-muted mb-0 ml-5 pl-2">
                    <i class="fas fa-clipboard-list mr-2 text-info"></i>
                    Administra tus proveedores y contactos comerciales
                </p>
            </div>
            <div class="col-md-4 text-right">
                <span class="badge badge-info badge-lg px-4 py-2">
                    <i class="fas fa-users mr-2"></i>
                    {{ count($proveedores) }} Proveedores
                </span>
            </div>
        </div>
    </div>
@stop

@section('content')
<div class="container-fluid">

    {{-- ALERTA DE ÉXITO MEJORADA --}}
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
    {{-- BARRA DE ACCIONES --}}
    <div class="card border-0 shadow-lg mb-4">
        <div class="card-body py-3">
            <div class="row align-items-center">
                <div class="col-md-6 mb-2 mb-md-0">
                    <a href="{{ route('inventario.index') }}" class="btn btn-outline-secondary btn-lg">
                        <i class="fas fa-arrow-left mr-2"></i> Volver a Inventario
                    </a>
                </div>
                <div class="col-md-6 text-md-right">
                    <a href="{{ route('proveedores.create') }}" class="btn btn-primary btn-lg shadow-sm"> 
                        <i class="fas fa-plus-circle mr-2"></i> Nuevo Proveedor
                    </a>
                </div>
            </div>
        </div>
    </div>
    
    {{-- ESTADÍSTICAS RÁPIDAS --}}
    <div class="row mb-4">
        <div class="col-lg-4 col-md-6 mb-3">
            <div class="info-card bg-gradient-primary">
                <div class="info-card-icon">
                    <i class="fas fa-handshake"></i>
                </div>
                <div class="info-card-content">
                    <h3 class="mb-0">{{ count($proveedores) }}</h3>
                    <p class="mb-0">Proveedores Activos</p>
                </div>
            </div>
        </div>

        <div class="col-lg-4 col-md-6 mb-3">
            <div class="info-card bg-gradient-success">
                <div class="info-card-icon">
                    <i class="fas fa-box-open"></i>
                </div>
                <div class="info-card-content">
                    <h3 class="mb-0">{{ $totalStock }}</h3>
                    <p class="mb-0">Stock Total en Inventario</p>
                </div>
            </div>
        </div>
    </div>

    

    {{-- TABLA DE PROVEEDORES MEJORADA --}}
    <div class="card border-0 shadow-lg">
        <div class="card-header bg-gradient-primary text-white">
            <h3 class="card-title mb-0 font-weight-bold">
                <i class="fas fa-list mr-2"></i> Listado de Proveedores
            </h3>
            <div class="card-tools">
                <button type="button" class="btn btn-tool text-white" data-card-widget="collapse">
                    <i class="fas fa-minus"></i>
                </button>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th style="width: 5%" class="text-center">
                                <i class="fas fa-hashtag text-primary"></i>
                            </th>
                            <th style="width: 25%">
                                <i class="fas fa-building text-info mr-2"></i>Nombre
                            </th>
                            <th style="width: 20%">
                                <i class="fas fa-phone text-success mr-2"></i>Contacto
                            </th>
                            <th style="width: 30%">
                                <i class="fas fa-map-marker-alt text-danger mr-2"></i>Dirección
                            </th>
                            <th style="width: 20%" class="text-center">
                                <i class="fas fa-cogs text-warning mr-2"></i>Acciones
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($proveedores as $index => $proveedor)
                        <tr class="proveedor-row">
                            <td class="text-center align-middle">
                                <span class="badge badge-pill badge-primary badge-number">
                                    {{ $proveedor->idproveedor }}
                                </span>
                            </td>
                            <td class="align-middle">
                                <div class="d-flex align-items-center">
                                    <div class="proveedor-avatar">
                                        <i class="fas fa-store"></i>
                                    </div>
                                    <div class="ml-3">
                                        <strong class="text-dark">{{ $proveedor->nombre }}</strong>
                                        <br>
                                        <small class="text-muted">
                                            <i class="fas fa-clock mr-1"></i>Activo
                                        </small>
                                    </div>
                                </div>
                            </td>
                            <td class="align-middle">
                                <div class="contact-info">
                                    <i class="fas fa-phone-alt text-success mr-2"></i>
                                    <span>{{ $proveedor->contacto }}</span>
                                </div>
                            </td>
                            <td class="align-middle">
                                <div class="address-info">
                                    <i class="fas fa-map-marker-alt text-danger mr-2"></i>
                                    <span class="text-muted">{{ $proveedor->direccion }}</span>
                                </div>
                            </td>
                            
                            <td class="text-center align-middle">
                                <div class="btn-group" role="group">
                                    {{-- Botón Ver Detalles --}}
                                    <button 
                                        type="button" 
                                        class="btn btn-sm btn-info shadow-sm"
                                        onclick="verDetallesProveedor('{{ $proveedor->nombre }}', '{{ $proveedor->contacto }}', '{{ $proveedor->direccion }}')"
                                        title="Ver Detalles">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    
                                    {{-- Botón Editar --}}
                                    <a 
                                        href="{{ route('proveedores.edit', $proveedor->idproveedor) }}" 
                                        class="btn btn-sm btn-warning shadow-sm" 
                                        title="Editar">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    
                                    {{-- Botón Eliminar --}}
                                    <button 
                                        type="button" 
                                        class="btn btn-sm btn-danger shadow-sm" 
                                        onclick="alertaEliminarProveedor('{{ $proveedor->nombre }}')" 
                                        title="Eliminar">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center py-5">
                                <div class="empty-state">
                                    <i class="fas fa-inbox fa-4x text-muted mb-3"></i>
                                    <h5 class="text-muted">No hay proveedores registrados</h5>
                                    <p class="text-muted">Comienza agregando tu primer proveedor</p>
                                    <a href="{{ route('proveedores.create') }}" class="btn btn-primary mt-3">
                                        <i class="fas fa-plus-circle mr-2"></i> Crear Proveedor
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if(count($proveedores) > 0)
        <div class="card-footer bg-light">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <p class="mb-0 text-muted">
                        <i class="fas fa-info-circle mr-2"></i>
                        Mostrando {{ count($proveedores) }} proveedor(es)
                    </p>
                </div>
                <div class="col-md-6 text-md-right">
                    <small class="text-muted">
                        <i class="fas fa-shield-alt mr-1"></i>
                        Los proveedores con registros asociados están protegidos
                    </small>
                </div>
            </div>
        </div>
        @endif
    </div>
</div>

{{-- MODAL DE DETALLES --}}
<div id="modal-detalles" class="modal-overlay" style="display: none;">
    <div class="modal-container">
        <div class="modal-content-custom">
            <div class="modal-header-custom bg-gradient-info text-white">
                <h5 class="modal-title-custom">
                    <i class="fas fa-info-circle mr-2"></i>
                    Detalles del Proveedor
                </h5>
                <button type="button" class="modal-close-btn" onclick="cerrarModal()">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body-custom">
                <div class="detalle-item">
                    <div class="detalle-icon bg-primary">
                        <i class="fas fa-building"></i>
                    </div>
                    <div class="detalle-content">
                        <label>Nombre del Proveedor</label>
                        <p id="detalle-nombre"></p>
                    </div>
                </div>
                <div class="detalle-item">
                    <div class="detalle-icon bg-success">
                        <i class="fas fa-phone"></i>
                    </div>
                    <div class="detalle-content">
                        <label>Contacto</label>
                        <p id="detalle-contacto"></p>
                    </div>
                </div>
                <div class="detalle-item">
                    <div class="detalle-icon bg-danger">
                        <i class="fas fa-map-marker-alt"></i>
                    </div>
                    <div class="detalle-content">
                        <label>Dirección</label>
                        <p id="detalle-direccion"></p>
                    </div>
                </div>
            </div>
            <div class="modal-footer-custom">
                <button type="button" class="btn btn-secondary" onclick="cerrarModal()">
                    <i class="fas fa-times mr-2"></i>Cerrar
                </button>
            </div>
        </div>
    </div>
</div>
@stop 

@section('css')
<style>
    /* Background mejorado */
    body {
        background-color: transparent;
    }

    /* Header mejorado */
    .content-header {
        background: transparent;
        border-radius: 15px;
        margin-bottom: 1.5rem;
        padding: 1rem 0;
    }

    /* Badge mejorado */
    .badge-lg {
        font-size: 1.1rem;
        padding: 10px 20px;
        border-radius: 25px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    }

    /* Info Cards */
    .info-card {
        border-radius: 15px;
        padding: 25px;
        color: white;
        display: flex;
        align-items: center;
        box-shadow: 0 5px 20px rgba(0, 0, 0, 0.15);
        transition: all 0.3s ease;
    }

    .info-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.25);
    }

    .info-card-icon {
        width: 70px;
        height: 70px;
        background: rgba(255, 255, 255, 0.2);
        border-radius: 15px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2rem;
        margin-right: 20px;
    }

    .info-card-content h3 {
        font-size: 2.5rem;
        font-weight: 800;
        margin-bottom: 5px;
    }

    .info-card-content p {
        font-size: 0.95rem;
        opacity: 0.95;
    }

    /* Gradientes */
    .bg-gradient-primary {
        background: linear-gradient(135deg, #007bff 0%, #0056b3 100%) !important;
    }

    .bg-gradient-success {
        background: linear-gradient(135deg, #28a745 0%, #1e7e34 100%) !important;
    }

    .bg-gradient-warning {
        background: linear-gradient(135deg, #ffc107 0%, #d39e00 100%) !important;
    }

    .bg-gradient-info {
        background: linear-gradient(135deg, #17a2b8 0%, #117a8b 100%) !important;
    }

    /* Alertas mejoradas */
    .alert {
        border-radius: 15px;
        border: none;
    }

    .alert-success {
        background: linear-gradient(135deg, #d4edda 0%, #c3e6cb 100%);
        border-left: 5px solid #28a745;
    }

    /* Cards */
    .card {
        border-radius: 20px;
        transition: all 0.3s ease;
    }

    .card:hover {
        box-shadow: 0 10px 35px rgba(0, 0, 0, 0.12) !important;
    }

    .card-header {
        border-radius: 20px 20px 0 0 !important;
        padding: 1.2rem 1.5rem;
        border-bottom: none;
    }

    /* Tabla mejorada */
    .table {
        margin-bottom: 0;
    }

    .table thead th {
        border-bottom: 2px solid #dee2e6;
        color: #495057;
        font-weight: 700;
        padding: 1rem;
        font-size: 0.95rem;
    }

    .table tbody td {
        padding: 1rem;
        vertical-align: middle;
        border-top: 1px solid #f0f0f0;
    }

    .proveedor-row {
        transition: all 0.3s ease;
    }

    .proveedor-row:hover {
        background-color: #f8f9fa;
        transform: scale(1.01);
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
    }

    /* Avatar del proveedor */
    .proveedor-avatar {
        width: 50px;
        height: 50px;
        border-radius: 12px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 1.5rem;
        box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
    }

    /* Badge de número */
    .badge-number {
        font-size: 1rem;
        padding: 8px 15px;
        font-weight: 700;
    }

    /* Info de contacto y dirección */
    .contact-info, .address-info {
        display: flex;
        align-items: center;
        font-size: 0.95rem;
    }

    .contact-info i, .address-info i {
        font-size: 1rem;
    }

    /* Botones mejorados */
    .btn {
        border-radius: 10px;
        font-weight: 600;
        transition: all 0.3s ease;
    }

    .btn:hover {
        transform: translateY(-2px);
    }

    .btn-group .btn {
        border-radius: 8px;
        margin: 0 2px;
    }

    .btn-sm {
        padding: 8px 15px;
    }

    /* Estado vacío */
    .empty-state {
        padding: 40px 20px;
    }

    .empty-state i {
        opacity: 0.3;
    }

    /* Modal personalizado */
    .modal-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.7);
        z-index: 9999;
        display: flex;
        align-items: center;
        justify-content: center;
        animation: fadeIn 0.3s ease;
    }

    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }

    .modal-container {
        width: 90%;
        max-width: 600px;
        animation: slideIn 0.3s ease;
    }

    @keyframes slideIn {
        from { transform: translateY(-50px); opacity: 0; }
        to { transform: translateY(0); opacity: 1; }
    }

    .modal-content-custom {
        background: #2d3436;
        border-radius: 20px;
        overflow: hidden;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.5);
        color: #fff;
    }

    .modal-header-custom {
        padding: 1.5rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .modal-title-custom {
        margin: 0;
        font-size: 1.5rem;
        font-weight: bold;
    }

    .modal-close-btn {
        background: none;
        border: none;
        color: white;
        font-size: 2rem;
        cursor: pointer;
        width: 40px;
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        transition: all 0.3s ease;
    }

    .modal-close-btn:hover {
        background: rgba(255, 255, 255, 0.2);
        transform: rotate(90deg);
    }

    .modal-body-custom {
        padding: 2rem;
    }

    .detalle-item {
        display: flex;
        align-items: flex-start;
        margin-bottom: 25px;
        padding: 15px;
        background: #343a40;
        border-radius: 12px;
        transition: all 0.3s ease;
    }

    .detalle-item:hover {
        background: #3d4449;
        transform: translateX(5px);
    }

    .detalle-icon {
        width: 50px;
        height: 50px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 1.3rem;
        margin-right: 15px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    }

    .detalle-content {
        flex: 1;
    }

    .detalle-content label {
        font-weight: 700;
        color: #adb5bd;
        font-size: 0.85rem;
        text-transform: uppercase;
        margin-bottom: 5px;
        display: block;
    }

    .detalle-content p {
        font-size: 1.1rem;
        color: #f8f9fa;
        margin: 0;
        font-weight: 600;
    }

    .modal-footer-custom {
        padding: 1.5rem;
        background: #343a40;
        text-align: right;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .info-card {
            margin-bottom: 15px;
        }

        .info-card-icon {
            width: 60px;
            height: 60px;
            font-size: 1.5rem;
        }

        .info-card-content h3 {
            font-size: 2rem;
        }

        .btn-group {
            display: flex;
            flex-direction: column;
        }

        .btn-group .btn {
            margin: 2px 0;
            width: 100%;
        }

        .proveedor-avatar {
            width: 40px;
            height: 40px;
            font-size: 1.2rem;
        }
    }
</style>
@stop

@section('js')
<script>
    console.log('Vista de proveedores mejorada lista 🚀');

    /**
     * Función que muestra una alerta personalizada al intentar eliminar un proveedor
     */
    function alertaEliminarProveedor(nombre) {
        // Crear modal personalizado
        const overlay = document.createElement('div');
        overlay.className = 'modal-overlay';
        overlay.style.display = 'flex';
        overlay.innerHTML = `
            <div class="modal-container">
                <div class="modal-content-custom">
                    <div class="modal-header-custom bg-gradient-danger text-white" style="background: linear-gradient(135deg, #dc3545 0%, #c82333 100%) !important;">
                        <h5 class="modal-title-custom">
                            <i class="fas fa-exclamation-triangle mr-2"></i>
                            No se puede eliminar
                        </h5>
                        <button type="button" class="modal-close-btn" onclick="this.closest('.modal-overlay').remove()">
                            <span>&times;</span>
                        </button>
                    </div>
                    <div class="modal-body-custom text-center py-4">
                        <i class="fas fa-ban fa-4x text-danger mb-3"></i>
                        <h4 class="mb-3 text-white">El proveedor "${nombre}" no puede ser eliminado</h4>
                        <p class="mb-4" style="color: #adb5bd;">
                            <i class="fas fa-info-circle mr-2"></i>
                            Este proveedor tiene registros asociados (productos o compras) en el sistema.
                        </p>
                        <div class="alert alert-warning border-0" style="background: rgba(255, 193, 7, 0.2); color: #ffc107;">
                            <i class="fas fa-lightbulb mr-2"></i>
                            <strong>Sugerencia:</strong> Debe eliminar primero todos los registros asociados antes de poder eliminar este proveedor.
                        </div>
                    </div>
                    <div class="modal-footer-custom">
                        <button type="button" class="btn btn-secondary" onclick="this.closest('.modal-overlay').remove()">
                            <i class="fas fa-times mr-2"></i>Entendido
                        </button>
                    </div>
                </div>
            </div>
        `;
        
        document.body.appendChild(overlay);
        
        // Cerrar al hacer clic fuera
        overlay.addEventListener('click', function(e) {
            if (e.target === overlay) {
                overlay.remove();
            }
        });
    }

    /**
     * Función para mostrar detalles del proveedor
     */
    function verDetallesProveedor(nombre, contacto, direccion) {
        document.getElementById('detalle-nombre').textContent = nombre;
        document.getElementById('detalle-contacto').textContent = contacto;
        document.getElementById('detalle-direccion').textContent = direccion;
        document.getElementById('modal-detalles').style.display = 'flex';
    }

    /**
     * Función para cerrar el modal de detalles
     */
    function cerrarModal() {
        document.getElementById('modal-detalles').style.display = 'none';
    }

    // Cerrar modal al hacer clic fuera
    document.addEventListener('DOMContentLoaded', function() {
        const modal = document.getElementById('modal-detalles');
        if (modal) {
            modal.addEventListener('click', function(e) {
                if (e.target === modal) {
                    cerrarModal();
                }
            });
        }
    });
</script>
@stop