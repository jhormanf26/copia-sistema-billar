@extends('adminlte::page')

@section('title', 'Informes y Reportes')

@section('content_header')
    <div class="container-fluid">
        <div class="row align-items-center justify-content-between">
            <div class="col">
                <h1 class="text-dark"><i class="fas fa-file-pdf mr-2"></i> Informes y Reportes</h1>
                <p class="text-muted">Analiza ventas, productos y ocupación de mesas.</p>
            </div>
            <div class="col-auto">
                <a href="@route('welcome')" class="btn btn-secondary">
                    <i class="fas fa-arrow-left mr-2"></i> Volver a Inicio
                </a>
            </div>
        </div>
    </div>
@stop

@section('content')
<div class="container-fluid">
    {{-- FILTROS --}}
    <div class="row">
        <div class="col-md-12">
            <div class="card card-primary card-outline">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-filter mr-2"></i> Filtros</h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="tipo-informe">Tipo de Informe</label>
                                <select id="tipo-informe" class="form-control">
                                    <option value="resumen">Resumen General de Ventas</option>
                                    <option value="ventas">Ventas por Período</option>
                                    <option value="productos">Productos Más Vendidos</option>
                                    <option value="metodos">Ingresos por Método de Pago</option>
                                    <option value="mesas">Ocupación de Mesas</option>
                                    <option value="comparacion">Comparación de Meses (Ventas)</option>
                                    <option value="resumen-compras">Resumen General de Compras</option>
                                    <option value="compras">Compras por Período</option>
                                    <option value="productos-comprados">Productos Comprados</option>
                                    <option value="compras-proveedor">Compras por Proveedor</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="fecha-inicio">Fecha Inicio</label>
                                <input type="date" id="fecha-inicio" class="form-control" value="{{ date('Y-m-01') }}">
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="fecha-fin">Fecha Fin</label>
                                <input type="date" id="fecha-fin" class="form-control" value="{{ date('Y-m-d') }}">
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="tipo-periodo">Período (Ventas)</label>
                                <select id="tipo-periodo" class="form-control">
                                    <option value="dia">Por Día</option>
                                    <option value="semana">Por Semana</option>
                                    <option value="mes">Por Mes</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="metodo-pago">Método de Pago</label>
                                <select id="metodo-pago" class="form-control">
                                    <option value="">Todos</option>
                                    <option value="efectivo">Efectivo</option>
                                    <option value="transferencia">Transferencia</option>
                                    <option value="tarjeta">Tarjeta</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="proveedor-filtro">Proveedor (Compras)</label>
                                <select id="proveedor-filtro" class="form-control">
                                    <option value="">Todos</option>
                                    @foreach(\App\Models\Proveedores::all() as $proveedor)
                                        <option value="{{ $proveedor->idproveedor }}">{{ $proveedor->nombre }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="limite-productos">Límite (Productos)</label>
                                <input type="number" id="limite-productos" class="form-control" value="10" min="1" max="50">
                            </div>
                        </div>

                        <div class="col-md-3 d-flex align-items-end">
                            <button id="btn-aplicar-filtros" class="btn btn-primary btn-block">
                                <i class="fas fa-search mr-2"></i> Aplicar Filtros
                            </button>
                        </div>
                    </div>

                    <div class="row mt-2">
                        <div class="col-md-12">
                            <button id="btn-limpiar-filtros" class="btn btn-secondary">
                                <i class="fas fa-redo mr-2"></i> Limpiar
                            </button>
                        </div>
                    </div>

                    {{-- Selección de meses para comparación --}}
                    <div class="row mt-3" id="row-seleccion-meses" style="display:none;">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="meses-seleccionados">Selecciona los meses a comparar:</label>
                                <div id="meses-seleccionados" class="row">
                                    <!-- Se llenarán dinámicamente -->
                                </div>
                                <button id="btn-cargar-comparacion" class="btn btn-info btn-sm mt-2">
                                    <i class="fas fa-chart-bar mr-2"></i> Cargar Comparación
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- RESUMEN GENERAL --}}
    <div id="seccion-resumen" class="row" style="display:none;">
        <div class="col-md-3">
            <div class="small-box bg-info">
                <div class="inner">
                    <h3><span id="total-ventas">$0</span></h3>
                    <p>Total de Ventas</p>
                </div>
                <div class="icon">
                    <i class="fas fa-coins"></i>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="small-box bg-success">
                <div class="inner">
                    <h3><span id="cantidad-transacciones">0</span></h3>
                    <p>Transacciones</p>
                </div>
                <div class="icon">
                    <i class="fas fa-exchange-alt"></i>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="small-box bg-warning">
                <div class="inner">
                    <h3>$<span id="promedio-transaccion">0</span></h3>
                    <p>Promedio/Transacción</p>
                </div>
                <div class="icon">
                    <i class="fas fa-calculator"></i>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="small-box bg-danger">
                <div class="inner">
                    <h3><span id="productos-registrados">0</span></h3>
                    <p>Productos Registrados</p>
                </div>
                <div class="icon">
                    <i class="fas fa-boxes"></i>
                </div>
            </div>
        </div>
    </div>

    {{-- VENTAS POR PERÍODO --}}
    <div id="seccion-ventas" class="row" style="display:none;">
        <div class="col-md-12">
            <div class="card card-outline card-info">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-chart-line mr-1"></i> Ventas por Período</h3>
                </div>
                <div class="card-body" style="height: 400px;">
                    <canvas id="chart-ventas"></canvas>
                </div>
            </div>
        </div>

        <div class="col-md-12">
            <div class="card card-outline card-primary">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-table mr-1"></i> Detalle de Ventas</h3>
                </div>
                <div class="card-body">
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th>Fecha</th>
                                <th>Total de Ventas</th>
                                <th>Cantidad de Transacciones</th>
                                <th>Promedio</th>
                            </tr>
                        </thead>
                        <tbody id="tbody-ventas">
                            <tr><td colspan="4" class="text-center text-muted">Cargando...</td></tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    {{-- PRODUCTOS MÁS VENDIDOS --}}
    <div id="seccion-productos" class="row" style="display:none;">
        <div class="col-md-12">
            <div class="card card-outline card-success">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-fire mr-1"></i> Top Productos Vendidos</h3>
                </div>
                <div class="card-body">
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th>Producto</th>
                                <th>Cantidad Vendida</th>
                                <th>Ingresos</th>
                                <th>Precio Promedio</th>
                            </tr>
                        </thead>
                        <tbody id="tbody-productos">
                            <tr><td colspan="4" class="text-center text-muted">Cargando...</td></tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    {{-- INGRESOS POR MÉTODO DE PAGO --}}
    <div id="seccion-metodos" class="row" style="display:none;">
        <div class="col-md-6">
            <div class="card card-outline card-warning">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-chart-pie mr-1"></i> Distribución por Método</h3>
                </div>
                <div class="card-body" style="height: 350px;">
                    <canvas id="chart-metodos"></canvas>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card card-outline card-warning">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-list mr-1"></i> Detalle por Método</h3>
                </div>
                <div class="card-body">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Método de Pago</th>
                                <th>Total</th>
                                <th>Porcentaje</th>
                            </tr>
                        </thead>
                        <tbody id="tbody-metodos">
                            <tr><td colspan="3" class="text-center text-muted">Cargando...</td></tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    {{-- OCUPACIÓN DE MESAS --}}
    <div id="seccion-mesas" class="row" style="display:none;">
        <div class="col-md-4">
            <div class="info-box bg-info">
                <span class="info-box-icon"><i class="fas fa-hockey-puck"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Total de Mesas</span>
                    <span class="info-box-number" id="info-total-mesas">0</span>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="info-box bg-success">
                <span class="info-box-icon"><i class="fas fa-check-circle"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Mesas Disponibles</span>
                    <span class="info-box-number" id="info-disponibles">0</span>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="info-box bg-danger">
                <span class="info-box-icon"><i class="fas fa-times-circle"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Mesas Ocupadas</span>
                    <span class="info-box-number" id="info-ocupadas">0</span>
                </div>
            </div>
        </div>

        <div class="col-md-12">
            <div class="card card-outline card-primary">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-table mr-1"></i> Ocupación por Mesa</h3>
                </div>
                <div class="card-body">
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th>Número de Mesa</th>
                                <th>Tipo</th>
                                <th>Usos</th>
                                <th>Ingresos Totales</th>
                                <th>Promedio/Uso</th>
                            </tr>
                        </thead>
                        <tbody id="tbody-mesas">
                            <tr><td colspan="5" class="text-center text-muted">Cargando...</td></tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    {{-- COMPARACIÓN DE MESES --}}
    <div id="seccion-comparacion" class="row" style="display:none;">
        <div class="col-md-12">
            <div class="card card-outline card-warning">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-chart-line mr-1"></i> Comparación de Meses</h3>
                </div>
                <div class="card-body" style="height: 400px;">
                    <canvas id="chart-comparacion"></canvas>
                </div>
            </div>
        </div>

        <div class="col-md-12">
            <div class="card card-outline card-info">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-table mr-1"></i> Detalle de Comparación</h3>
                </div>
                <div class="card-body">
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th>Mes</th>
                                <th>Total de Ventas</th>
                                <th>Transacciones</th>
                                <th>Promedio/Transacción</th>
                            </tr>
                        </thead>
                        <tbody id="tbody-comparacion">
                            <tr><td colspan="4" class="text-center text-muted">Cargando...</td></tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    {{-- RESUMEN COMPRAS --}}
    <div id="seccion-resumen-compras" class="row" style="display:none;">
        <div class="col-md-3">
            <div class="small-box bg-primary">
                <div class="inner">
                    <h3>$<span id="total-compras">0</span></h3>
                    <p>Total de Compras</p>
                </div>
                <div class="icon">
                    <i class="fas fa-shopping-cart"></i>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="small-box bg-info">
                <div class="inner">
                    <h3><span id="cantidad-compras">0</span></h3>
                    <p>Cantidad de Compras</p>
                </div>
                <div class="icon">
                    <i class="fas fa-receipt"></i>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="small-box bg-success">
                <div class="inner">
                    <h3>$<span id="promedio-compra">0</span></h3>
                    <p>Promedio/Compra</p>
                </div>
                <div class="icon">
                    <i class="fas fa-calculator"></i>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="small-box bg-warning">
                <div class="inner">
                    <h3><span id="total-productos-comprados">0</span></h3>
                    <p>Productos Comprados</p>
                </div>
                <div class="icon">
                    <i class="fas fa-boxes"></i>
                </div>
            </div>
        </div>
    </div>

    {{-- COMPRAS POR PERÍODO --}}
    <div id="seccion-compras" class="row" style="display:none;">
        <div class="col-md-12">
            <div class="card card-outline card-primary">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-chart-line mr-1"></i> Compras por Período</h3>
                </div>
                <div class="card-body" style="height: 400px;">
                    <canvas id="chart-compras"></canvas>
                </div>
            </div>
        </div>

        <div class="col-md-12">
            <div class="card card-outline card-info">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-table mr-1"></i> Detalle de Compras</h3>
                </div>
                <div class="card-body">
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th>Fecha</th>
                                <th>Total de Compras</th>
                                <th>Cantidad de Compras</th>
                                <th>Promedio</th>
                            </tr>
                        </thead>
                        <tbody id="tbody-compras">
                            <tr><td colspan="4" class="text-center text-muted">Cargando...</td></tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    {{-- PRODUCTOS COMPRADOS --}}
    <div id="seccion-productos-comprados" class="row" style="display:none;">
        <div class="col-md-12">
            <div class="card card-outline card-success">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-fire mr-1"></i> Top Productos Comprados</h3>
                </div>
                <div class="card-body">
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th>Producto</th>
                                <th>Cantidad Comprada</th>
                                <th>Total Invertido</th>
                                <th>Precio Promedio</th>
                            </tr>
                        </thead>
                        <tbody id="tbody-productos-comprados">
                            <tr><td colspan="4" class="text-center text-muted">Cargando...</td></tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    {{-- COMPRAS POR PROVEEDOR --}}
    <div id="seccion-compras-proveedor" class="row" style="display:none;">
        <div class="col-md-12">
            <div class="card card-outline card-danger">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-users mr-1"></i> Compras por Proveedor</h3>
                </div>
                <div class="card-body">
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th>Proveedor</th>
                                <th>Cantidad de Compras</th>
                                <th>Total Gastado</th>
                                <th>Promedio/Compra</th>
                            </tr>
                        </thead>
                        <tbody id="tbody-compras-proveedor">
                            <tr><td colspan="4" class="text-center text-muted">Cargando...</td></tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@stop

@section('css')
<style>
    .info-box { border-radius: 0.5rem; }
    .small-box { border-radius: 0.5rem; }
    .card { border-radius: 0.5rem; }
    
    canvas {
        max-height: 100%;
        width: 100%;
    }
</style>
@stop

@section('js')
<script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>
<script>
    let chartVentas = null;
    let chartMetodos = null;
    let chartComparacion = null;
    let chartCompras = null;

    // Elementos DOM
    const tipoInforme = document.getElementById('tipo-informe');
    const fechaInicio = document.getElementById('fecha-inicio');
    const fechaFin = document.getElementById('fecha-fin');
    const tipoPeriodo = document.getElementById('tipo-periodo');
    const metodoPago = document.getElementById('metodo-pago');
    const proveedorFiltro = document.getElementById('proveedor-filtro');
    const limiteProductos = document.getElementById('limite-productos');
    const btnAplicar = document.getElementById('btn-aplicar-filtros');
    const btnLimpiar = document.getElementById('btn-limpiar-filtros');
    const rowSeleccionMeses = document.getElementById('row-seleccion-meses');
    const btnCargarComparacion = document.getElementById('btn-cargar-comparacion');
    const mesesSeleccionados = document.getElementById('meses-seleccionados');

    // Secciones
    const seccionResumen = document.getElementById('seccion-resumen');
    const seccionVentas = document.getElementById('seccion-ventas');
    const seccionProductos = document.getElementById('seccion-productos');
    const seccionMetodos = document.getElementById('seccion-metodos');
    const seccionMesas = document.getElementById('seccion-mesas');
    const seccionComparacion = document.getElementById('seccion-comparacion');
    const seccionResumenCompras = document.getElementById('seccion-resumen-compras');
    const seccionCompras = document.getElementById('seccion-compras');
    const seccionProductosComprados = document.getElementById('seccion-productos-comprados');
    const seccionComprasProveedor = document.getElementById('seccion-compras-proveedor');

    // Listeners
    btnAplicar.addEventListener('click', aplicarFiltros);
    btnLimpiar.addEventListener('click', limpiarFiltros);
    tipoInforme.addEventListener('change', mostrarOpcionesFiltros);
    btnCargarComparacion.addEventListener('click', cargarComparacionMeses);

    function ocultarTodasSecciones() {
        seccionResumen.style.display = 'none';
        seccionVentas.style.display = 'none';
        seccionProductos.style.display = 'none';
        seccionMetodos.style.display = 'none';
        seccionMesas.style.display = 'none';
        seccionComparacion.style.display = 'none';
        seccionResumenCompras.style.display = 'none';
        seccionCompras.style.display = 'none';
        seccionProductosComprados.style.display = 'none';
        seccionComprasProveedor.style.display = 'none';
    }

    function mostrarOpcionesFiltros() {
        const tipo = tipoInforme.value;
        if (tipo === 'comparacion') {
            rowSeleccionMeses.style.display = 'grid';
            llenarSelectorMeses();
        } else {
            rowSeleccionMeses.style.display = 'none';
        }
    }

    function llenarSelectorMeses() {
        mesesSeleccionados.innerHTML = '';
        // Generar últimos 12 meses
        for (let i = 11; i >= 0; i--) {
            const fecha = new Date();
            fecha.setMonth(fecha.getMonth() - i);
            const mesAnio = fecha.toISOString().substring(0, 7); // YYYY-MM
            const label = new Date(fecha.getFullYear(), fecha.getMonth()).toLocaleString('es-CO', { month: 'long', year: 'numeric' });

            const div = document.createElement('div');
            div.className = 'col-md-2 mb-2';
            div.innerHTML = `
                <div class="form-check">
                    <input class="form-check-input checkbox-mes" type="checkbox" id="mes-${mesAnio}" value="${mesAnio}" ${i >= 11 - 3 ? 'checked' : ''}>
                    <label class="form-check-label" for="mes-${mesAnio}">
                        ${label.charAt(0).toUpperCase() + label.slice(1)}
                    </label>
                </div>
            `;
            mesesSeleccionados.appendChild(div);
        }
    }

    function cargarComparacionMeses() {
        const mesesChecked = Array.from(document.querySelectorAll('.checkbox-mes:checked')).map(el => el.value);
        if (mesesChecked.length === 0) {
            alert('Por favor selecciona al menos un mes');
            return;
        }

        const params = new URLSearchParams({
            meses: JSON.stringify(mesesChecked)
        });

        fetch(`/api/informes/comparacion-meses?${params}`)
            .then(r => r.json())
            .then(data => {
                const tbody = document.getElementById('tbody-comparacion');
                tbody.innerHTML = data.datos.map(d => `
                    <tr>
                        <td><strong>${d.label}</strong></td>
                        <td>$${Number(d.total_ventas).toLocaleString('es-CO', {maximumFractionDigits: 2})}</td>
                        <td>${d.cantidad_transacciones}</td>
                        <td>$${Number(d.promedio).toLocaleString('es-CO', {maximumFractionDigits: 2})}</td>
                    </tr>
                `).join('');

                const ctx = document.getElementById('chart-comparacion').getContext('2d');
                if (chartComparacion) chartComparacion.destroy();

                chartComparacion = new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: data.datos.map(d => d.label),
                        datasets: [
                            {
                                label: 'Total Ventas ($)',
                                data: data.datos.map(d => d.total_ventas),
                                borderColor: '#17a2b8',
                                backgroundColor: 'rgba(23, 162, 184, 0.1)',
                                borderWidth: 3,
                                tension: 0.4,
                                fill: true,
                                yAxisID: 'y',
                            },
                            {
                                label: 'Transacciones',
                                data: data.datos.map(d => d.cantidad_transacciones),
                                borderColor: '#28a745',
                                backgroundColor: 'rgba(40, 167, 69, 0.1)',
                                borderWidth: 3,
                                tension: 0.4,
                                fill: true,
                                yAxisID: 'y1',
                            }
                        ]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        interaction: {
                            mode: 'index',
                            intersect: false,
                        },
                        plugins: {
                            legend: { display: true, position: 'top' },
                        },
                        scales: {
                            y: {
                                type: 'linear',
                                display: true,
                                position: 'left',
                                title: { display: true, text: 'Ventas ($)' },
                                ticks: {
                                    callback: v => '$' + Number(v).toLocaleString('es-CO'),
                                }
                            },
                            y1: {
                                type: 'linear',
                                display: true,
                                position: 'right',
                                title: { display: true, text: 'Transacciones' },
                                grid: {
                                    drawOnChartArea: false,
                                },
                            }
                        }
                    }
                });

                ocultarTodasSecciones();
                seccionComparacion.style.display = 'grid';
            });
    }

    function aplicarFiltros() {
        ocultarTodasSecciones();
        const tipo = tipoInforme.value;

        const params = new URLSearchParams({
            fecha_inicio: fechaInicio.value,
            fecha_fin: fechaFin.value,
            tipo_periodo: tipoPeriodo.value,
            tipo: tipoPeriodo.value,
            metodo_pago: metodoPago.value,
            idproveedor: proveedorFiltro.value,
            limite: limiteProductos.value,
        });

        switch(tipo) {
            case 'resumen':
                cargarResumen(params);
                break;
            case 'ventas':
                cargarVentas(params);
                break;
            case 'productos':
                cargarProductos(params);
                break;
            case 'metodos':
                cargarMetodos(params);
                break;
            case 'mesas':
                cargarMesas(params);
                break;
            case 'comparacion':
                rowSeleccionMeses.style.display = 'grid';
                llenarSelectorMeses();
                break;
            case 'resumen-compras':
                cargarResumenCompras(params);
                break;
            case 'compras':
                cargarCompras(params);
                break;
            case 'productos-comprados':
                cargarProductosComprados(params);
                break;
            case 'compras-proveedor':
                cargarComprasProveedor(params);
                break;
        }
    }

    function cargarResumen(params) {
        fetch(`/api/informes/resumen?${params}`)
            .then(r => r.json())
            .then(data => {
                document.getElementById('total-ventas').textContent = '$' + Number(data.total_ventas).toLocaleString('es-CO', {maximumFractionDigits: 2});
                document.getElementById('cantidad-transacciones').textContent = data.cantidad_transacciones;
                document.getElementById('promedio-transaccion').textContent = Number(data.promedio_transaccion).toLocaleString('es-CO', {maximumFractionDigits: 2});
                document.getElementById('productos-registrados').textContent = data.productos_registrados;
                seccionResumen.style.display = 'grid';
            });
    }

    function cargarVentas(params) {
        fetch(`/api/informes/ventas-periodo?${params}`)
            .then(r => r.json())
            .then(data => {
                // Actualizar tabla
                const tbody = document.getElementById('tbody-ventas');
                tbody.innerHTML = data.labels.map((label, i) => `
                    <tr>
                        <td>${label}</td>
                        <td>$${Number(data.valores[i]).toLocaleString('es-CO', {maximumFractionDigits: 2})}</td>
                        <td>${data.cantidades[i]}</td>
                        <td>$${(data.valores[i] / data.cantidades[i] || 0).toLocaleString('es-CO', {maximumFractionDigits: 2})}</td>
                    </tr>
                `).join('');

                // Actualizar gráfica
                const ctx = document.getElementById('chart-ventas').getContext('2d');
                if (chartVentas) chartVentas.destroy();
                chartVentas = new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: data.labels,
                        datasets: [{
                            label: 'Ventas ($)',
                            data: data.valores,
                            borderColor: '#17a2b8',
                            backgroundColor: 'rgba(23, 162, 184, 0.3)',
                            borderWidth: 2,
                            fill: true,
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: { display: true, position: 'top' },
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    callback: v => '$' + Number(v).toLocaleString('es-CO'),
                                }
                            }
                        }
                    }
                });

                seccionVentas.style.display = 'grid';
            });
    }

    function cargarProductos(params) {
        fetch(`/api/informes/productos-vendidos?${params}`)
            .then(r => r.json())
            .then(data => {
                const tbody = document.getElementById('tbody-productos');
                tbody.innerHTML = data.productos.map(p => `
                    <tr>
                        <td>${p.nombre}</td>
                        <td>${p.cantidad_vendida}</td>
                        <td>$${Number(p.total_vendido).toLocaleString('es-CO', {maximumFractionDigits: 2})}</td>
                        <td>$${(p.total_vendido / p.cantidad_vendida || 0).toLocaleString('es-CO', {maximumFractionDigits: 2})}</td>
                    </tr>
                `).join('');
                seccionProductos.style.display = 'grid';
            });
    }

    function cargarMetodos(params) {
        fetch(`/api/informes/ingresos-metodo?${params}`)
            .then(r => r.json())
            .then(data => {
                const total = data.total;
                const tbody = document.getElementById('tbody-metodos');
                tbody.innerHTML = Object.entries(data.metodos).map(([metodo, valor]) => `
                    <tr>
                        <td><strong>${metodo.charAt(0).toUpperCase() + metodo.slice(1)}</strong></td>
                        <td>$${Number(valor).toLocaleString('es-CO', {maximumFractionDigits: 2})}</td>
                        <td>${total > 0 ? ((valor / total) * 100).toFixed(2) : 0}%</td>
                    </tr>
                `).join('');

                // Gráfica pie
                const ctx = document.getElementById('chart-metodos').getContext('2d');
                if (chartMetodos) chartMetodos.destroy();
                chartMetodos = new Chart(ctx, {
                    type: 'doughnut',
                    data: {
                        labels: ['Efectivo', 'Transferencia', 'Tarjeta'],
                        datasets: [{
                            data: [data.metodos.efectivo, data.metodos.transferencia, data.metodos.tarjeta],
                            backgroundColor: ['#ffc107', '#17a2b8', '#28a745'],
                            borderColor: '#fff',
                            borderWidth: 2,
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: { position: 'bottom' },
                        }
                    }
                });

                seccionMetodos.style.display = 'grid';
            });
    }

    function cargarMesas(params) {
        fetch(`/api/informes/ocupacion-mesas?${params}`)
            .then(r => r.json())
            .then(data => {
                document.getElementById('info-total-mesas').textContent = data.total_mesas;
                document.getElementById('info-disponibles').textContent = data.disponibles;
                document.getElementById('info-ocupadas').textContent = data.ocupadas;

                const tbody = document.getElementById('tbody-mesas');
                tbody.innerHTML = data.ventas_por_mesa.map(m => `
                    <tr>
                        <td><strong>Mesa ${m.numeromesa}</strong></td>
                        <td>${m.tipo}</td>
                        <td>${m.usos}</td>
                        <td>$${Number(m.ingresos).toLocaleString('es-CO', {maximumFractionDigits: 2})}</td>
                        <td>$${(m.ingresos / m.usos || 0).toLocaleString('es-CO', {maximumFractionDigits: 2})}</td>
                    </tr>
                `).join('');

                seccionMesas.style.display = 'grid';
            });
    }

    function limpiarFiltros() {
        tipoInforme.value = 'resumen';
        fechaInicio.value = new Date().toISOString().split('T')[0].replace(/-\d{2}$/, '-01');
        fechaFin.value = new Date().toISOString().split('T')[0];
        tipoPeriodo.value = 'dia';
        metodoPago.value = '';
        proveedorFiltro.value = '';
        limiteProductos.value = '10';
        ocultarTodasSecciones();
    }

    // Funciones para Compras
    function cargarResumenCompras(params) {
        fetch(`/api/informes/resumen-compras?${params}`)
            .then(r => r.json())
            .then(data => {
                document.getElementById('total-compras').textContent = Number(data.total_compras).toLocaleString('es-CO', {maximumFractionDigits: 2});
                document.getElementById('cantidad-compras').textContent = data.cantidad_compras;
                document.getElementById('promedio-compra').textContent = Number(data.promedio_compra).toLocaleString('es-CO', {maximumFractionDigits: 2});
                document.getElementById('total-productos-comprados').textContent = data.total_productos_comprados;
                seccionResumenCompras.style.display = 'grid';
            });
    }

    function cargarCompras(params) {
        fetch(`/api/informes/compras-periodo?${params}`)
            .then(r => r.json())
            .then(data => {
                // Actualizar tabla
                const tbody = document.getElementById('tbody-compras');
                tbody.innerHTML = data.labels.map((label, i) => `
                    <tr>
                        <td>${label}</td>
                        <td>$${Number(data.valores[i]).toLocaleString('es-CO', {maximumFractionDigits: 2})}</td>
                        <td>${data.cantidades[i]}</td>
                        <td>$${(data.valores[i] / data.cantidades[i] || 0).toLocaleString('es-CO', {maximumFractionDigits: 2})}</td>
                    </tr>
                `).join('');

                // Actualizar gráfica
                const ctx = document.getElementById('chart-compras').getContext('2d');
                if (chartCompras) chartCompras.destroy();
                chartCompras = new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: data.labels,
                        datasets: [{
                            label: 'Compras ($)',
                            data: data.valores,
                            borderColor: '#007bff',
                            backgroundColor: 'rgba(0, 123, 255, 0.3)',
                            borderWidth: 2,
                            fill: true,
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: { display: true, position: 'top' },
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    callback: v => '$' + Number(v).toLocaleString('es-CO'),
                                }
                            }
                        }
                    }
                });

                seccionCompras.style.display = 'grid';
            });
    }

    function cargarProductosComprados(params) {
        fetch(`/api/informes/productos-comprados?${params}`)
            .then(r => r.json())
            .then(data => {
                const tbody = document.getElementById('tbody-productos-comprados');
                tbody.innerHTML = data.productos.map(p => `
                    <tr>
                        <td>${p.nombre}</td>
                        <td>${p.cantidad_comprada}</td>
                        <td>$${Number(p.total_comprado).toLocaleString('es-CO', {maximumFractionDigits: 2})}</td>
                        <td>$${Number(p.precio_promedio).toLocaleString('es-CO', {maximumFractionDigits: 2})}</td>
                    </tr>
                `).join('');
                seccionProductosComprados.style.display = 'grid';
            });
    }

    function cargarComprasProveedor(params) {
        fetch(`/api/informes/compras-proveedor?${params}`)
            .then(r => r.json())
            .then(data => {
                const tbody = document.getElementById('tbody-compras-proveedor');
                tbody.innerHTML = data.proveedores.map(p => `
                    <tr>
                        <td><strong>${p.nombre}</strong></td>
                        <td>${p.cantidad_compras}</td>
                        <td>$${Number(p.total_gastado).toLocaleString('es-CO', {maximumFractionDigits: 2})}</td>
                        <td>$${Number(p.promedio_compra).toLocaleString('es-CO', {maximumFractionDigits: 2})}</td>
                    </tr>
                `).join('');
                seccionComprasProveedor.style.display = 'grid';
            });
    }

    // Cargar resumen al iniciar
    window.addEventListener('load', () => {
        tipoInforme.value = 'resumen';
        aplicarFiltros();
    });
</script>
@stop
