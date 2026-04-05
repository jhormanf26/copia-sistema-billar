@extends('adminlte::page')

@section('title', $title ?? 'Dashboard Ejecutivo')

@section('content_header')
    <div class="container-fluid">
        <div class="row align-items-center mb-4">
            <div class="col-md-8">
                <div class="header-content">
                    <div class="header-badge mb-2">
                        <span class="badge badge-primary-soft">
                            <i class="fas fa-circle pulse-dot mr-1"></i>Sistema en Línea
                        </span>
                    </div>
                    <h1 class="display-4 text-dark mb-2 font-weight-bold">
                        <i class="fas fa-boxes mr-3 text-primary pulse-icon"></i> 
                        Inventario Billar Nexus
                    </h1>
                    <p class="text-muted mb-0 h5 font-weight-light">
                        <i class="fas fa-chart-line mr-2"></i>Vista general del inventario y recursos
                    </p>
                </div>
            </div>
            <div class="col-md-4 text-right">
                <a href="{{ route('welcome') }}" class="btn btn-gradient-primary btn-lg rounded-pill shadow-lg btn-hover px-4">
                    <i class="fas fa-arrow-left mr-2"></i>Volver al Inicio
                </a>
            </div>
        </div>
    </div>
@stop

@section('content')
<div class="container-fluid">
    {{-- Indicadores Principales --}}
    <div class="row mb-5">
        {{-- Productos Disponibles --}}
        <div class="col-lg-4 col-md-6 col-sm-12 mb-4">
            <div class="card-modern card-info">
                <div class="card-modern-overlay"></div>
                <div class="card-glow card-glow-info"></div>
                <div class="card-modern-content">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <span class="badge badge-light-custom badge-pill">
                            <i class="fas fa-layer-group mr-1"></i>Inventario
                        </span>
                    </div>
                    
                    <div class="d-flex align-items-center mb-4">
                        <div class="icon-wrapper-modern bg-info-light mr-3">
                            <i class="fas fa-box-open"></i>
                            <div class="icon-ripple"></div>
                        </div>
                        <h2 class="display-3 font-weight-bold mb-0 text-white counter-number" data-target="{{ $productos->count() }}">
                            0
                        </h2>
                    </div>
                    
                    <p class="card-label mb-4">Productos Disponibles</p>
                    
                    <div class="stats-bar mb-4">
                        <div class="stats-bar-fill" style="width: 85%" data-percentage="85"></div>
                    </div>
                    
                    <div class="d-flex justify-content-between align-items-center">
                        <small class="text-white-50">
                            <i class="fas fa-chart-line mr-1"></i>85% capacidad
                        </small>
                        <a href="{{ route('productos.index') }}" class="btn btn-light-custom btn-sm rounded-pill shadow-sm">
                            Ver todos <i class="fas fa-arrow-right ml-1"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        {{-- Mesas Registradas --}}
        <div class="col-lg-4 col-md-6 col-sm-12 mb-4">
            <div class="card-modern card-success">
                <div class="card-modern-overlay"></div>
                <div class="card-glow card-glow-success"></div>
                <div class="card-modern-content">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <span class="badge badge-light-custom badge-pill">
                            <i class="fas fa-check-circle mr-1"></i>Activos
                        </span>
                    </div>
                    
                    <div class="d-flex align-items-center mb-4">
                        <div class="icon-wrapper-modern bg-success-light mr-3">
                            <i class="fas fa-hockey-puck"></i>
                            <div class="icon-ripple"></div>
                        </div>
                        <h2 class="display-3 font-weight-bold mb-0 text-white counter-number" data-target="{{ $mesas->count() }}">
                            0
                        </h2>
                    </div>
                    
                    <p class="card-label mb-4">Mesas Registradas</p>
                    
                    <div class="stats-bar mb-4">
                        <div class="stats-bar-fill" style="width: 70%" data-percentage="70"></div>
                    </div>
                    
                    <div class="d-flex justify-content-between align-items-center">
                        <small class="text-white-50">
                            <i class="fas fa-check-double mr-1"></i>70% en uso
                        </small>
                        <a href="{{ route('mesas.index') }}" class="btn btn-light-custom btn-sm rounded-pill shadow-sm">
                            Ver mesas <i class="fas fa-arrow-right ml-1"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        {{-- Proveedores Activos --}}
        <div class="col-lg-4 col-md-6 col-sm-12 mb-4">
            <div class="card-modern card-warning">
                <div class="card-modern-overlay"></div>
                <div class="card-glow card-glow-warning"></div>
                <div class="card-modern-content">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <span class="badge badge-light-custom badge-pill">
                            <i class="fas fa-handshake mr-1"></i>Asociados
                        </span>
                    </div>
                    
                    <div class="d-flex align-items-center mb-4">
                        <div class="icon-wrapper-modern bg-warning-light mr-3">
                            <i class="fas fa-truck"></i>
                            <div class="icon-ripple"></div>
                        </div>
                        <h2 class="display-3 font-weight-bold mb-0 text-white counter-number" data-target="{{ $proveedoresActivos ?? 0 }}">
                            0
                        </h2>
                    </div>
                    
                    <p class="card-label mb-4">Proveedores Activos</p>
                    
                    <div class="stats-bar mb-4">
                        <div class="stats-bar-fill" style="width: 60%" data-percentage="60"></div>
                    </div>
                    
                    <div class="d-flex justify-content-between align-items-center">
                        <small class="text-white-50">
                            <i class="fas fa-star mr-1"></i>Verificados
                        </small>
                        <a href="{{ route('proveedores.index') }}" class="btn btn-light-custom btn-sm rounded-pill shadow-sm">
                            Ver todos <i class="fas fa-arrow-right ml-1"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Sección de Tiempo y Top 5 --}}
    <div class="row">
        {{-- Clases de Tiempo --}}
        <div class="col-lg-6 col-md-12 mb-4">
            <div class="card card-ultra-modern shadow-xl">
                <div class="card-header-gradient">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h3 class="card-title-modern mb-1">
                                <i class="fas fa-clock mr-2"></i> 
                                Clases de Tiempo
                            </h3>
                            <p class="text-white-50 mb-0 small">Gestión de tiempos disponibles</p>
                        </div>
                        <div class="header-icon-circle">
                            <i class="fas fa-hourglass-half"></i>
                        </div>
                    </div>
                </div>
                <div class="card-body p-0">
                    @php
                        $colorsNew = [
                            ['primary' => '#17a2b8', 'secondary' => '#138496', 'icon' => 'fa-clock'],
                            ['primary' => '#28a745', 'secondary' => '#218838', 'icon' => 'fa-hourglass-start'],
                            ['primary' => '#007bff', 'secondary' => '#0056b3', 'icon' => 'fa-hourglass-half'],
                            ['primary' => '#ffc107', 'secondary' => '#e0a800', 'icon' => 'fa-hourglass-end'],
                            ['primary' => '#dc3545', 'secondary' => '#c82333', 'icon' => 'fa-stopwatch'],
                            ['primary' => '#6c757d', 'secondary' => '#5a6268', 'icon' => 'fa-business-time']
                        ];
                    @endphp
                    
                    <div class="tiempo-container">
                        @forelse($productosTiempo as $index => $producto)
                            @php $color = $colorsNew[$index % count($colorsNew)]; @endphp
                            <div class="tiempo-item-modern" style="--color-primary: {{ $color['primary'] }}; --color-secondary: {{ $color['secondary'] }};">
                                <div class="tiempo-decoration"></div>
                                <div class="tiempo-rank-badge">
                                    <span>{{ $index + 1 }}</span>
                                </div>
                                <div class="tiempo-info-section">
                                    <h5 class="tiempo-title mb-1">{{ $producto->nombre }}</h5>
                                    <small class="tiempo-subtitle">Tiempo disponible</small>
                                </div>
                                <div class="tiempo-icon-modern">
                                    <i class="fas {{ $color['icon'] }}"></i>
                                </div>
                            </div>
                        @empty
                            <div class="empty-state">
                                <div class="empty-state-icon">
                                    <i class="fas fa-inbox"></i>
                                </div>
                                <p class="empty-state-text">No hay productos de tiempo registrados</p>
                                <a href="#" class="btn btn-outline-primary btn-sm rounded-pill">
                                    <i class="fas fa-plus mr-1"></i>Agregar producto
                                </a>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

        {{-- Top 5 Productos --}}
        <div class="col-lg-6 col-md-12 mb-4">
            <div class="card card-ultra-modern shadow-xl">
                <div class="card-header-gradient-alt">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h3 class="card-title-modern mb-1">
                                <i class="fas fa-fire mr-2"></i> 
                                Top 5 Más Vendidos
                            </h3>
                            <p class="text-white-50 mb-0 small">Productos estrella del mes</p>
                        </div>
                        <button type="button" class="btn-refresh" id="btn-refresh-top5" title="Actualizar">
                            <i class="fas fa-sync-alt"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div id="container-top5" class="top5-container">
                        @forelse($top5Productos as $index => $producto)
                            <div class="top5-item-modern" data-index="{{ $index }}">
                                <div class="top5-rank-modern rank-{{ $index + 1 }}">
                                    <span class="rank-number">{{ $index + 1 }}</span>
                                    @if($index === 0)
                                        <i class="fas fa-crown crown-icon"></i>
                                    @endif
                                </div>
                                <div class="top5-content">
                                    <h6 class="top5-name mb-1">{{ $producto->nombre }}</h6>
                                    <div class="top5-meta">
                                        <span class="meta-item">
                                            <i class="fas fa-shopping-cart mr-1"></i>
                                            <strong>{{ number_format($producto->total_vendido, 0, ',', '.') }} </strong><strong></strong>  unidades vendidas
                                        </span>
                                    </div>
                                </div>
                                <div class="top5-badge-modern">
                                    <div class="badge-circle">{{ number_format($producto->total_vendido, 0, ',', '.') }}</div>
                                </div>
                            </div>
                        @empty
                            <div class="empty-state">
                                <div class="empty-state-icon">
                                    <i class="fas fa-chart-line"></i>
                                </div>
                                <p class="empty-state-text">Sin datos de ventas aún</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@stop

@section('css')
<style>
    /* ========== Variables Globales - AdminLTE Original ========== */
    :root {
        --gradient-info: linear-gradient(135deg, #17a2b8 0%, #138496 100%);
        --gradient-success: linear-gradient(135deg, #28a745 0%, #218838 100%);
        --gradient-warning: linear-gradient(135deg, #ffc107 0%, #e0a800 100%);
        --gradient-primary: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
        --shadow-soft: 0 10px 40px rgba(0, 0, 0, 0.08);
        --shadow-medium: 0 15px 50px rgba(0, 0, 0, 0.12);
        --shadow-strong: 0 20px 60px rgba(0, 0, 0, 0.15);
        --transition-smooth: all 0.4s cubic-bezier(0.25, 0.46, 0.45, 0.94);
    }

    /* ========== Header Mejorado ========== */
    .header-content {
        position: relative;
        z-index: 1;
    }

    .header-badge .badge-primary-soft {
        background: linear-gradient(135deg, rgba(23, 162, 184, 0.1) 0%, rgba(19, 132, 150, 0.1) 100%);
        color: #17a2b8;
        padding: 0.5rem 1rem;
        border-radius: 20px;
        font-weight: 500;
        border: 1px solid rgba(23, 162, 184, 0.2);
    }

    .pulse-dot {
        width: 8px;
        height: 8px;
        background: #17a2b8;
        border-radius: 50%;
        display: inline-block;
        animation: pulse-dot 2s infinite;
    }

    @keyframes pulse-dot {
        0%, 100% { opacity: 1; transform: scale(1); }
        50% { opacity: 0.5; transform: scale(1.2); }
    }

    .pulse-icon {
        animation: pulse-smooth 3s ease-in-out infinite;
        display: inline-block;
    }

    @keyframes pulse-smooth {
        0%, 100% { transform: scale(1); }
        50% { transform: scale(1.05); }
    }

    /* ========== Botón Mejorado ========== */
    .btn-gradient-primary {
        background: var(--gradient-primary);
        border: none;
        color: white;
        font-weight: 600;
        letter-spacing: 0.3px;
        transition: var(--transition-smooth);
        position: relative;
        overflow: hidden;
    }

    .btn-gradient-primary::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent);
        transition: left 0.5s;
    }

    .btn-gradient-primary:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 30px rgba(79, 172, 254, 0.4);
        color: white;
    }

    .btn-gradient-primary:hover::before {
        left: 100%;
    }

    /* ========== Cards Modernos Premium ========== */
    .card-modern {
        position: relative;
        border-radius: 24px;
        overflow: hidden;
        border: none;
        box-shadow: var(--shadow-soft);
        transition: var(--transition-smooth);
        min-height: 320px;
        backdrop-filter: blur(10px);
    }

    .card-modern:hover {
        transform: translateY(-12px) scale(1.02);
        box-shadow: var(--shadow-strong);
    }

    .card-info { background: var(--gradient-info); }
    .card-success { background: var(--gradient-success); }
    .card-warning { background: var(--gradient-warning); }

    .card-modern-overlay {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: 
            radial-gradient(circle at 20% 50%, rgba(255, 255, 255, 0.1) 0%, transparent 50%),
            radial-gradient(circle at 80% 80%, rgba(255, 255, 255, 0.08) 0%, transparent 50%);
        pointer-events: none;
    }

    .card-glow {
        position: absolute;
        top: -50%;
        left: -50%;
        width: 200%;
        height: 200%;
        opacity: 0;
        transition: opacity 0.5s;
        pointer-events: none;
    }

    .card-modern:hover .card-glow {
        opacity: 0.3;
        animation: glow-rotate 3s linear infinite;
    }

    @keyframes glow-rotate {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }

    .card-glow-info { background: radial-gradient(circle, #17a2b8, transparent); }
    .card-glow-success { background: radial-gradient(circle, #28a745, transparent); }
    .card-glow-warning { background: radial-gradient(circle, #ffc107, transparent); }

    .card-modern-content {
        position: relative;
        z-index: 2;
        padding: 2.5rem;
        color: white;
    }

    /* ========== Iconos Wrapper ========== */
    .icon-wrapper-modern {
        width: 70px;
        height: 70px;
        border-radius: 20px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: rgba(255, 255, 255, 0.15);
        backdrop-filter: blur(20px);
        font-size: 2rem;
        color: white;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
        position: relative;
        transition: var(--transition-smooth);
    }

    .card-modern:hover .icon-wrapper-modern {
        transform: rotate(10deg) scale(1.1);
    }

    .icon-ripple {
        position: absolute;
        width: 100%;
        height: 100%;
        border-radius: 20px;
        border: 2px solid rgba(255, 255, 255, 0.5);
        animation: ripple 2s infinite;
    }

    @keyframes ripple {
        0% {
            transform: scale(1);
            opacity: 1;
        }
        100% {
            transform: scale(1.5);
            opacity: 0;
        }
    }

    /* ========== Badges ========== */
    .badge-light-custom {
        background: rgba(255, 255, 255, 0.2);
        backdrop-filter: blur(10px);
        color: white;
        border: 1px solid rgba(255, 255, 255, 0.3);
        padding: 0.5rem 1rem;
        font-weight: 500;
        font-size: 0.75rem;
    }

    /* ========== Contador ========== */
    .counter-number {
        font-size: 4rem;
        text-shadow: 0 4px 20px rgba(0, 0, 0, 0.2);
        font-weight: 800;
        letter-spacing: -2px;
    }

    .card-label {
        font-size: 1.1rem;
        font-weight: 600;
        opacity: 0.95;
        text-transform: uppercase;
        letter-spacing: 2px;
    }

    /* ========== Barra de Estadísticas ========== */
    .stats-bar {
        height: 8px;
        background: rgba(255, 255, 255, 0.2);
        border-radius: 10px;
        overflow: hidden;
        position: relative;
    }

    .stats-bar-fill {
        height: 100%;
        background: linear-gradient(90deg, rgba(255, 255, 255, 0.8), rgba(255, 255, 255, 1));
        border-radius: 10px;
        transition: width 1.5s cubic-bezier(0.65, 0, 0.35, 1);
        position: relative;
        box-shadow: 0 0 15px rgba(255, 255, 255, 0.5);
    }

    .stats-bar-fill::after {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.4), transparent);
        animation: shimmer 2s infinite;
    }

    @keyframes shimmer {
        0% { transform: translateX(-100%); }
        100% { transform: translateX(100%); }
    }

    /* ========== Botón Light Custom ========== */
    .btn-light-custom {
        background: rgba(255, 255, 255, 0.95);
        color: #333;
        border: none;
        font-weight: 600;
        padding: 0.5rem 1.25rem;
        transition: var(--transition-smooth);
    }

    .btn-light-custom:hover {
        background: white;
        transform: translateX(5px);
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        color: #333;
    }

    /* ========== Cards Ultra Modern ========== */
    .card-ultra-modern {
        border-radius: 24px;
        border: none;
        overflow: hidden;
        background: white;
        transition: var(--transition-smooth);
    }

    .card-ultra-modern:hover {
        transform: translateY(-5px);
        box-shadow: var(--shadow-medium);
    }

    .card-header-gradient {
        background: linear-gradient(135deg, #17a2b8 0%, #138496 100%);
        padding: 1.75rem;
        border: none;
        position: relative;
        overflow: hidden;
    }

    .card-header-gradient::before {
        content: '';
        position: absolute;
        top: -50%;
        left: -50%;
        width: 200%;
        height: 200%;
        background: radial-gradient(circle, rgba(255, 255, 255, 0.1), transparent);
        animation: header-glow 4s linear infinite;
    }

    @keyframes header-glow {
        0% { transform: translate(0, 0); }
        50% { transform: translate(10%, 10%); }
        100% { transform: translate(0, 0); }
    }

    .card-header-gradient-alt {
        background: linear-gradient(135deg, #28a745 0%, #218838 100%);
        padding: 1.75rem;
        border: none;
        position: relative;
        overflow: hidden;
    }

    .card-header-gradient-alt::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -50%;
        width: 200%;
        height: 200%;
        background: radial-gradient(circle, rgba(255, 255, 255, 0.1), transparent);
        animation: header-glow 4s linear infinite reverse;
    }

    .card-title-modern {
        font-size: 1.4rem;
        font-weight: 700;
        color: white;
        margin: 0;
        position: relative;
        z-index: 1;
    }

    .header-icon-circle {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.2);
        backdrop-filter: blur(10px);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 1.3rem;
        border: 2px solid rgba(255, 255, 255, 0.3);
        position: relative;
        z-index: 1;
    }

    /* ========== Tiempo Items Modern ========== */
    .tiempo-container {
        max-height: 500px;
        overflow-y: auto;
    }

    .tiempo-item-modern {
        display: flex;
        align-items: center;
        padding: 1.5rem;
        border-bottom: 1px solid #f0f0f0;
        transition: var(--transition-smooth);
        position: relative;
        background: white;
    }

    .tiempo-item-modern:hover {
        background: linear-gradient(90deg, var(--color-primary), var(--color-secondary));
        padding-left: 2rem;
        color: white;
    }

    .tiempo-item-modern:hover .tiempo-title,
    .tiempo-item-modern:hover .tiempo-subtitle {
        color: white;
    }

    .tiempo-decoration {
        position: absolute;
        left: 0;
        top: 0;
        bottom: 0;
        width: 4px;
        background: linear-gradient(180deg, var(--color-primary), var(--color-secondary));
        transform: scaleY(0);
        transition: transform 0.3s;
    }

    .tiempo-item-modern:hover .tiempo-decoration {
        transform: scaleY(1);
    }

    .tiempo-rank-badge {
        width: 50px;
        height: 50px;
        border-radius: 15px;
        background: linear-gradient(135deg, var(--color-primary), var(--color-secondary));
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: 800;
        font-size: 1.3rem;
        margin-right: 1.5rem;
        box-shadow: 0 5px 20px rgba(0, 0, 0, 0.15);
        transition: var(--transition-smooth);
    }

    .tiempo-item-modern:hover .tiempo-rank-badge {
        transform: rotate(360deg) scale(1.1);
        background: white;
        color: var(--color-primary);
    }

    .tiempo-info-section {
        flex: 1;
    }

    .tiempo-title {
        font-weight: 600;
        color: #333;
        margin: 0;
        font-size: 1.05rem;
    }

    .tiempo-subtitle {
        color: #888;
        font-size: 0.85rem;
    }

    .tiempo-icon-modern {
        font-size: 1.8rem;
        color: #e0e0e0;
        margin-left: 1rem;
        transition: var(--transition-smooth);
    }

    .tiempo-item-modern:hover .tiempo-icon-modern {
        color: white;
        transform: scale(1.2) rotate(15deg);
    }

    /* ========== Top 5 Modern ========== */
    .top5-container {
        max-height: 500px;
        overflow-y: auto;
    }

    .top5-item-modern {
        display: flex;
        align-items: center;
        padding: 1.25rem 1.5rem;
        border-bottom: 1px solid #f0f0f0;
        transition: var(--transition-smooth);
        position: relative;
        background: white;
    }

    .top5-item-modern:hover {
        background: linear-gradient(90deg, #f8f9fa, white);
        padding-left: 2rem;
        box-shadow: inset 4px 0 0 #28a745;
    }

    .top5-rank-modern {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 800;
        font-size: 1.2rem;
        margin-right: 1.25rem;
        position: relative;
        transition: var(--transition-smooth);
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    }

    .rank-1 {
        background: linear-gradient(135deg, #FFD700, #FFA500);
        color: white;
    }

    .rank-2 {
        background: linear-gradient(135deg, #C0C0C0, #A9A9A9);
        color: white;
    }

    .rank-3 {
        background: linear-gradient(135deg, #CD7F32, #B8860B);
        color: white;
    }

    .rank-4, .rank-5 {
        background: linear-gradient(135deg, #28a745, #20c997);
        color: white;
    }

    .top5-item-modern:hover .top5-rank-modern {
        transform: scale(1.15) rotate(360deg);
    }

    .crown-icon {
        position: absolute;
        top: -8px;
        right: -8px;
        font-size: 0.9rem;
        color: #FFD700;
        animation: crown-float 2s ease-in-out infinite;
    }

    @keyframes crown-float {
        0%, 100% { transform: translateY(0); }
        50% { transform: translateY(-5px); }
    }

    .top5-content {
        flex: 1;
        min-width: 0;
    }

    .top5-name {
        font-weight: 600;
        color: #333;
        margin: 0;
        font-size: 1rem;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .top5-meta {
        display: flex;
        gap: 1rem;
        margin-top: 0.25rem;
    }

    .meta-item {
        font-size: 0.85rem;
        color: #888;
        display: flex;
        align-items: center;
    }

    .top5-badge-modern {
        margin-left: 1rem;
    }

    .badge-circle {
        width: 55px;
        height: 55px;
        border-radius: 50%;
        background: linear-gradient(135deg, #28a745, #218838);
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 1.1rem;
        box-shadow: 0 5px 20px rgba(40, 167, 69, 0.3);
        transition: var(--transition-smooth);
    }

    .top5-item-modern:hover .badge-circle {
        transform: scale(1.1);
        box-shadow: 0 8px 30px rgba(40, 167, 69, 0.5);
    }

    /* ========== Botón Refresh ========== */
    .btn-refresh {
        width: 45px;
        height: 45px;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.2);
        backdrop-filter: blur(10px);
        border: 2px solid rgba(255, 255, 255, 0.3);
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: var(--transition-smooth);
        position: relative;
        z-index: 1;
    }

    .btn-refresh:hover {
        background: rgba(255, 255, 255, 0.3);
        transform: rotate(180deg);
    }

    .btn-refresh.spinning {
        animation: spin-refresh 1s linear;
    }

    @keyframes spin-refresh {
        from { transform: rotate(0deg); }
        to { transform: rotate(360deg); }
    }

    /* ========== Estado Vacío ========== */
    .empty-state {
        padding: 4rem 2rem;
        text-align: center;
    }

    .empty-state-icon {
        width: 100px;
        height: 100px;
        margin: 0 auto 1.5rem;
        border-radius: 50%;
        background: linear-gradient(135deg, #f0f0f0, #e0e0e0);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2.5rem;
        color: #999;
    }

    .empty-state-text {
        color: #888;
        font-size: 1.1rem;
        margin-bottom: 1.5rem;
    }

    /* ========== Scrollbar Personalizado ========== */
    .tiempo-container::-webkit-scrollbar,
    .top5-container::-webkit-scrollbar {
        width: 6px;
    }

    .tiempo-container::-webkit-scrollbar-track,
    .top5-container::-webkit-scrollbar-track {
        background: #f5f5f5;
    }

    .tiempo-container::-webkit-scrollbar-thumb,
    .top5-container::-webkit-scrollbar-thumb {
        background: linear-gradient(180deg, #17a2b8, #138496);
        border-radius: 10px;
    }

    .tiempo-container::-webkit-scrollbar-thumb:hover,
    .top5-container::-webkit-scrollbar-thumb:hover {
        background: linear-gradient(180deg, #138496, #17a2b8);
    }

    /* ========== Animaciones de Entrada ========== */
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @keyframes fadeInScale {
        from {
            opacity: 0;
            transform: scale(0.9);
        }
        to {
            opacity: 1;
            transform: scale(1);
        }
    }

    .card-modern {
        animation: fadeInScale 0.6s ease-out backwards;
    }

    .card-modern:nth-child(1) { animation-delay: 0.1s; }
    .card-modern:nth-child(2) { animation-delay: 0.2s; }
    .card-modern:nth-child(3) { animation-delay: 0.3s; }

    .card-ultra-modern {
        animation: fadeInUp 0.6s ease-out backwards;
        animation-delay: 0.4s;
    }

    /* ========== Responsive Design ========== */
    @media (max-width: 992px) {
        .counter-number {
            font-size: 3rem;
        }
        
        .card-modern {
            min-height: 280px;
        }
        
        .card-modern-content {
            padding: 2rem;
        }
    }

    @media (max-width: 768px) {
        .header-content h1 {
            font-size: 2rem;
        }
        
        .counter-number {
            font-size: 2.5rem;
        }
        
        .icon-wrapper-modern {
            width: 60px;
            height: 60px;
            font-size: 1.5rem;
        }
        
        .card-modern-content {
            padding: 1.5rem;
        }
        
        .tiempo-item-modern,
        .top5-item-modern {
            padding: 1rem;
        }
        
        .tiempo-rank-badge,
        .top5-rank-modern {
            width: 40px;
            height: 40px;
            font-size: 1rem;
        }
    }

    @media (max-width: 576px) {
        .btn-gradient-primary {
            padding: 0.75rem 1.5rem;
            font-size: 0.9rem;
        }
        
        .card-title-modern {
            font-size: 1.1rem;
        }
        
        .header-icon-circle {
            width: 40px;
            height: 40px;
            font-size: 1rem;
        }
    }

    /* ========== Efectos Adicionales ========== */
    @keyframes gradient-shift {
        0% { background-position: 0% 50%; }
        50% { background-position: 100% 50%; }
        100% { background-position: 0% 50%; }
    }

    .card-modern::after {
        content: '';
        position: absolute;
        top: -2px;
        left: -2px;
        right: -2px;
        bottom: -2px;
        background: linear-gradient(45deg, 
            rgba(255, 255, 255, 0.1), 
            transparent, 
            rgba(255, 255, 255, 0.1));
        border-radius: 24px;
        opacity: 0;
        transition: opacity 0.3s;
        pointer-events: none;
        z-index: 0;
    }

    .card-modern:hover::after {
        opacity: 1;
    }

    /* ========== Modo Oscuro (Opcional) ========== */
    @media (prefers-color-scheme: dark) {
        .card-ultra-modern {
            background: #2d2d2d;
        }
        
        .tiempo-item-modern,
        .top5-item-modern {
            background: #2d2d2d;
            border-bottom-color: #404040;
        }
        
        .tiempo-title,
        .top5-name {
            color: #ffffff;
        }
        
        .tiempo-subtitle,
        .meta-item {
            color: #aaaaaa;
        }
        
        .empty-state-icon {
            background: linear-gradient(135deg, #404040, #505050);
        }
        
        .empty-state-text {
            color: #aaaaaa;
        }
    }

    /* ========== Impresión ========== */
    @media print {
        .card-modern,
        .card-ultra-modern {
            box-shadow: none !important;
            page-break-inside: avoid;
        }
        
        .btn-gradient-primary,
        .btn-light-custom,
        .btn-refresh {
            display: none;
        }
    }
</style>
@stop

@section('js')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // ========== Animación de Contadores ========== //
        const animateCounter = (element) => {
            const target = parseInt(element.getAttribute('data-target'));
            const duration = 2000;
            const increment = target / (duration / 16);
            let current = 0;
            
            const timer = setInterval(() => {
                current += increment;
                if (current >= target) {
                    element.textContent = target;
                    clearInterval(timer);
                } else {
                    element.textContent = Math.floor(current);
                }
            }, 16);
        };

        // Animar todos los contadores
        document.querySelectorAll('.counter-number[data-target]').forEach(counter => {
            animateCounter(counter);
        });

        // ========== Animación de Barras de Progreso ========== //
        const animateProgressBars = () => {
            document.querySelectorAll('.stats-bar-fill').forEach(bar => {
                const percentage = bar.getAttribute('data-percentage');
                bar.style.width = '0%';
                setTimeout(() => {
                    bar.style.width = percentage + '%';
                }, 100);
            });
        };

        animateProgressBars();

        // ========== Actualización Top 5 ========== //
        const actualizarTop5 = (callback) => {
            fetch('/api/inventario/top5-productos')
                .then(response => response.json())
                .then(data => {
                    const container = document.getElementById('container-top5');
                    
                    if (!data || data.length === 0) {
                        container.innerHTML = `
                            <div class="empty-state">
                                <div class="empty-state-icon">
                                    <i class="fas fa-chart-line"></i>
                                </div>
                                <p class="empty-state-text">Sin datos de ventas aún</p>
                            </div>
                        `;
                        if (callback) callback();
                        return;
                    }

                    let html = '';
                    data.forEach((producto, index) => {
                        const rankClass = `rank-${index + 1}`;
                        const crownIcon = index === 0 ? '<i class="fas fa-crown crown-icon"></i>' : '';
                        const totalVendido = parseInt(producto.total_vendido);
                        const totalVendidoFormateado = totalVendido.toLocaleString('es-CO');
                        
                        html += `
                            <div class="top5-item-modern" data-index="${index}" style="opacity: 0; transform: translateX(-20px);">
                                <div class="top5-rank-modern ${rankClass}">
                                    <span class="rank-number">${index + 1}</span>
                                    ${crownIcon}
                                </div>
                                <div class="top5-content">
                                    <h6 class="top5-name mb-1">${producto.nombre}</h6>
                                    <div class="top5-meta">
                                        <span class="meta-item">
                                            <i class="fas fa-shopping-cart mr-1"></i>
                                            <strong>${totalVendidoFormateado}</strong> unidades vendidas
                                        </span>
                                    </div>
                                </div>
                                <div class="top5-badge-modern">
                                    <div class="badge-circle">${totalVendidoFormateado}</div>
                                </div>
                            </div>
                        `;
                    });

                    container.innerHTML = html;
                    
                    // Animar entrada de elementos
                    const items = container.querySelectorAll('.top5-item-modern');
                    items.forEach((item, index) => {
                        setTimeout(() => {
                            item.style.transition = 'all 0.4s cubic-bezier(0.25, 0.46, 0.45, 0.94)';
                            item.style.opacity = '1';
                            item.style.transform = 'translateX(0)';
                        }, index * 80);
                    });

                    if (callback) callback();
                })
                .catch(error => {
                    console.error('Error actualizando top 5:', error);
                    if (callback) callback();
                });
        };

        // Actualizar cada 30 segundos
        setInterval(actualizarTop5, 30000);

        // ========== Botón de Actualización Manual ========== //
        const btnRefresh = document.getElementById('btn-refresh-top5');
        if (btnRefresh) {
            btnRefresh.addEventListener('click', function() {
                this.classList.add('spinning');
                actualizarTop5(() => {
                    setTimeout(() => {
                        this.classList.remove('spinning');
                    }, 1000);
                });
            });
        }

        // ========== Efectos de Hover Avanzados ========== //
        document.querySelectorAll('.card-modern').forEach(card => {
            card.addEventListener('mousemove', (e) => {
                const rect = card.getBoundingClientRect();
                const x = e.clientX - rect.left;
                const y = e.clientY - rect.top;
                
                const centerX = rect.width / 2;
                const centerY = rect.height / 2;
                
                const rotateX = (y - centerY) / 20;
                const rotateY = (centerX - x) / 20;
                
                card.style.transform = `perspective(1000px) rotateX(${rotateX}deg) rotateY(${rotateY}deg) translateY(-12px) scale(1.02)`;
            });
            
            card.addEventListener('mouseleave', () => {
                card.style.transform = '';
            });
        });

        // ========== Lazy Loading para Imágenes (si las hubiera) ========== //
        if ('IntersectionObserver' in window) {
            const imageObserver = new IntersectionObserver((entries, observer) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        const img = entry.target;
                        img.src = img.dataset.src;
                        img.classList.add('loaded');
                        observer.unobserve(img);
                    }
                });
            });

            document.querySelectorAll('img[data-src]').forEach(img => {
                imageObserver.observe(img);
            });
        }

        // ========== Tooltips Mejorados ========== //
        const initTooltips = () => {
            const elements = document.querySelectorAll('[data-toggle="tooltip"]');
            elements.forEach(element => {
                new bootstrap.Tooltip(element, {
                    animation: true,
                    delay: { show: 300, hide: 100 }
                });
            });
        };

        if (typeof bootstrap !== 'undefined') {
            initTooltips();
        }

        // ========== Notificación de Bienvenida ========== //
        const showWelcomeNotification = () => {
            // Puedes integrar toastr o crear una notificación personalizada
            console.log('Dashboard cargado exitosamente');
        };

        setTimeout(showWelcomeNotification, 500);

        // ========== Performance Monitoring ========== //
        if (window.performance) {
            const loadTime = window.performance.timing.domContentLoadedEventEnd - 
                           window.performance.timing.navigationStart;
            console.log(`Dashboard cargado en ${loadTime}ms`);
        }
    });
</script>
@stop