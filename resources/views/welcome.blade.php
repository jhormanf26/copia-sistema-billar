@extends('adminlte::page')

@section('title', $title ?? 'Dashboard Ejecutivo')

@section('content_header')
 <div class="container-fluid text-center">
 <h1 class="text-dark"><i class="fas fa-chart-line mr-2"></i> Panel de Gestión Billar Nexus</h1>
<p class="text-muted">Vista general del negocio.</p>
</div>
@stop

@section('content')
<div class="container-fluid">
    {{-- 1. Fila de Indicadores Clave (Small Boxes) --}}
    {{-- ... (Se mantiene la Fila 1 exactamente igual) ... --}}
    <div class="row">
        @php
            $dias = ['Domingo','Lunes','Martes','Miércoles','Jueves','Viernes','Sábado'];
            $nombreDia = $dias[date('w')];
        @endphp

        <div class="col-lg-4 col-6">
            {{-- INGRESO DEL DÍA --}}
            <div class="small-box bg-info">
                <div class="inner">
                    <h3>
                        $<span id="ingresoDia">{{ number_format($ingresoDia ?? 0, 2, ',', '.') }}</span>
                        <sup style="font-size: 20px">/{{ $nombreDia }}</sup>
                        <p>Hoy es!</p>
                    </h3>
                    <p>Ingresos del Día</p>
                </div>
                <div class="icon">
                    <i class="fas fa-dollar-sign"></i>
                </div>
                <a href="{{ route('informes.index')}}" class="small-box-footer">
                    Ver reportes financieros <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>

        {{-- MESAS ACTIVAS/TOTAL --}}
        <div class="col-lg-4 col-6">
            <div class="small-box bg-success">
                <div class="inner">
                    <h3><span id="ocupadasCount">0</span><sup style="font-size: 20px">/<span id="mesasTotal">0</span></sup></h3>
                    <p>Mesas Ocupadas</p>
                    <div class="mt-2"><small id="listaMesasOcupadas" class="text-white">Cargando...</small></div>
                </div>
                <div class="icon">
                    <i class="fas fa-hockey-puck billar-icon"></i>
                </div>
                <a href="{{ route('mesasventas.index')}}" class="small-box-footer">
                    Gestión de mesas <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>

        {{-- PRODUCTOS REGISTRADOS --}}
        <div class="col-lg-4 col-">
            <div class="small-box bg-warning">
                <div class="inner">
                    <h3><span id="productosCount">0</span></h3>
                    <p>Productos Registrados</p>
                    <div class="mt-2"><small id="productosInfo" class="text-dark">Cargando...</small></div>
                </div>
                <div class="icon">
                    <i class="fas fa-boxes"></i>
                </div>
                <a href="{{ route('inventario.index')}}" class="small-box-footer">
                    Gestión de Inventario <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>


    </div>
    {{-- FIN de la Fila 1 --}}

    {{-- 2. Fila: GRÁFICO (7) y CALENDARIO (5) --}}
    <div class="row">
        {{-- CARD para Gráfico de Ventas (7/12) --}}
        <div class="col-md-7">
            <div class="card card-outline card-info">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-chart-bar mr-1"></i>
                        Rendimiento de Ventas (Semana Actual)
                    </h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <canvas id="ventasSemanaChart" style="height:300px; min-height:300px"></canvas>
                </div>
            </div>
        </div>

        {{-- CARD para CALENDARIO SIMPLE (5/12) --}}
        <div class="col-md-5">
            <div class="card card-outline card-danger">
                <div class="card-header bg-danger">
                    <h3 class="card-title">
                        <i class="fas fa-calendar-alt mr-1"></i>
                        Calendario y Eventos (Local)
                    </h3>
                </div>
                <div class="card-body p-1">
                    <div id="miniCalendar" class="p-2">
                        <h4 class="text-center mb-2" id="mesActualTitulo"></h4>
                        <table class="table table-bordered table-sm text-center">
                            <thead>
                                <tr>
                                    <th>Lun</th><th>Mar</th><th>Mié</th><th>Jue</th><th>Vie</th><th>Sáb</th><th>Dom</th>
                                </tr>
                            </thead>
                            <tbody id="calendarBody">
                                </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer text-center p-2">
                    <small class="text-muted"></small>
                </div>
            </div>
        </div>
    </div>
    {{-- FIN de la Fila 2 --}}



</div>
@stop

@section('css')
<style> /* Estilos existentes */
.small-box {
 border-radius: 0.5rem;
box-shadow: 0 4px 10px rgba(0,0,0,0.05); transition: transform 0.3s ease, box-shadow 0.3s ease; }
.small-box:hover {
     transform: translateY(-3px);
      box-shadow: 0 6px 15px rgba(0,0,0,0.1);
}
.small-box .inner h3 { font-weight: 700; }

 .small-box.bg-info { background-color: #17a2b8 !important; }
.small-box.bg-success { background-color: #28a745 !important; }
.small-box.bg-warning { background-color: #ffc107 !important; color: #333 !important; }
 .small-box.bg-primary { background-color: #007bff !important; }

.small-box .icon {
font-size: 80px;
color: rgba(0,0,0,0.15) !important;
 }

/* Asegurar que las cards tengan la misma altura (Importante para la Fila 2) */
.row > [class*='col-'] .card {
 height: 100%; }


/* Fijar altura de la gráfica para evitar que se alargue */
#ventasSemanaChart {
 max-height: 300px !important;
height: 300px !important;
width: 100% !important;
}

.card-body {
position: relative;
}

    /* Estilos del Calendario */
    #miniCalendar table {
        table-layout: fixed;
        width: 100%;
        margin-bottom: 0;
    }
    #miniCalendar th {
        font-size: 0.75rem;
        padding: 0.3rem !important;
    }
    #miniCalendar td {
        padding: 0.1rem !important;
        cursor: pointer;
        height: 40px; /* Altura para contener el punto de evento */
        vertical-align: top;
        position: relative;
        font-size: 0.9rem;
    }
    #miniCalendar td:hover {
        background-color: #f8f9fa;
        opacity: 0.9;
    }
    .event-dot {
        height: 6px;
        width: 6px;
        background-color: #dc3545; /* Color rojo (danger) */
        border-radius: 50%;
        display: block;
        margin: 2px auto 0;
    }
    .current-day {
        background-color: #dc3545; /* Rojo */
        color: white;
        border-radius: 5px;
        font-weight: bold;
    }
    .current-day .event-dot {
        background-color: white;
    }

</style>
@stop

@section('js')
<script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    let ventasChartInstance = null;
    const LOCAL_STORAGE_KEY = 'billar_nexus_eventos';

    // === LÓGICA DEL CALENDARIO Y EVENTOS (LOCAL STORAGE) ===

    // Función para obtener eventos de localStorage y limpiarlos si están caducados
    function getAndCleanEvents() {
        const today = new Date();
        today.setHours(0, 0, 0, 0); // Establecer a medianoche para comparación

        const stored = localStorage.getItem(LOCAL_STORAGE_KEY);
        let events = stored ? JSON.parse(stored) : {};
        let cleanedEvents = {};

        // Limpiar eventos caducados
        for (const dateKey in events) {
            const eventDate = new Date(dateKey);
            eventDate.setHours(0, 0, 0, 0);

            // Si la fecha del evento es HOY o en el futuro, se mantiene
            if (eventDate >= today) {
                cleanedEvents[dateKey] = events[dateKey];
            }
        }

        // Sobrescribir el localStorage con los eventos limpios
        localStorage.setItem(LOCAL_STORAGE_KEY, JSON.stringify(cleanedEvents));
        return cleanedEvents;
    }

    function saveEvent(dateKey, eventText) {
        let events = getAndCleanEvents();
        if (eventText) {
            events[dateKey] = eventText;
        } else {
            delete events[dateKey]; // Si el texto está vacío, lo borra
        }
        localStorage.setItem(LOCAL_STORAGE_KEY, JSON.stringify(events));
        renderCalendar(); // Volver a renderizar para ver el cambio
    }

    function renderCalendar() {
        const date = new Date();
        const year = date.getFullYear();
        const month = date.getMonth();
        const todayDay = date.getDate();
        const todayKey = `${year}-${String(month + 1).padStart(2, '0')}-${String(todayDay).padStart(2, '0')}`;

        const monthNames = ["Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"];
        document.getElementById('mesActualTitulo').textContent = `${monthNames[month]} ${year}`;

        const body = document.getElementById('calendarBody');
        body.innerHTML = '';
        const events = getAndCleanEvents();

        // 1. Obtener el primer día del mes y el último día del mes
        const firstDayOfMonth = new Date(year, month, 1).getDay(); // 0=Dom, 1=Lun, ..., 6=Sáb
        const daysInMonth = new Date(year, month + 1, 0).getDate();

        let day = 1;
        let cellCount = 0;

        // El calendario comienza la semana en Lunes (AdminLTE style), ajustamos el offset
        let startOffset = firstDayOfMonth === 0 ? 6 : firstDayOfMonth - 1; // Si es Domingo (0), el offset es 6

        for (let i = 0; i < 6; i++) { // Máximo 6 semanas
            let row = body.insertRow();
            let rowFilled = false;

            for (let j = 0; j < 7; j++) { // 7 días de la semana (Lun a Dom)
                let cell = row.insertCell();
                cellCount++;

                if (cellCount <= startOffset || day > daysInMonth) {
                    // Celdas vacías antes del primer día o después del último
                    cell.textContent = '';
                } else {
                    // Días del mes
                    const currentDay = day;
                    cell.textContent = currentDay;

                    const dateKey = `${year}-${String(month + 1).padStart(2, '0')}-${String(currentDay).padStart(2, '0')}`;

                    // Marcar día actual
                    if (currentDay === todayDay) {
                        cell.classList.add('current-day');
                    }

                    // Marcar evento
                    if (events[dateKey]) {
                        cell.innerHTML += `<span class="event-dot"></span>`;
                        cell.setAttribute('title', events[dateKey]); // Tooltip para el evento
                    }

                    // Manejar clic para agregar/editar evento
                    cell.onclick = () => handleDayClick(dateKey, events[dateKey]);

                    day++;
                    rowFilled = true;
                }
            }
            // Si la fila no tiene días del mes y tampoco días de relleno, se detiene el loop
            if (day > daysInMonth && startOffset <= cellCount) break;
        }
    }

    function handleDayClick(dateKey, currentEvent) {
        const date = new Date(dateKey + 'T00:00:00'); // Asegura zona horaria correcta
        const dayOfMonth = date.getDate();
        const eventText = prompt(
            `Evento para el día ${dayOfMonth}:\n\nIngrese el evento o déjelo vacío para borrar:`,
            currentEvent || ''
        );

        if (eventText !== null) {
            saveEvent(dateKey, eventText.trim());
        }
    }

    // Llamadas para el calendario
    getAndCleanEvents(); // Limpiar al cargar
    renderCalendar();    // Renderizar

    // === FUNCIONES DE GRÁFICOS Y DATOS (EXISTENTES) ===

    // === FUNCIÓN PARA ACTUALIZAR LA GRÁFICA DE VENTAS ===
    function actualizarGraficaVentas() {
        fetch('/ventas-semana')
            .then(response => response.json())
            .then(data => {
                const canvas = document.getElementById('ventasSemanaChart');
                if (!canvas) return;

                const ctx = canvas.getContext('2d');

                if (ventasChartInstance) {
                    ventasChartInstance.destroy();
                }

                ventasChartInstance = new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: data.labels || [],
                        datasets: [{
                            label: 'Ventas de la Semana ($)',
                            data: data.valores || [],
                            borderColor: '#17a2b8',
                            backgroundColor: 'rgba(23, 162, 184, 0.2)',
                            borderWidth: 3,
                            tension: 0.4,
                            fill: true,
                            pointRadius: 5,
                            pointBackgroundColor: '#17a2b8',
                            pointBorderColor: '#fff',
                            pointBorderWidth: 2,
                            pointHoverRadius: 7
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: { display: true, position: 'top', labels: { font: { size: 12 } } },
                            tooltip: {
                                callbacks: {
                                    label: function(context) {
                                        const v = context.parsed.y ?? 0;
                                        return ' $' + Number(v).toLocaleString('es-CO', {
                                            minimumFractionDigits: 2,
                                            maximumFractionDigits: 2
                                        });
                                    }
                                }
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    callback: function(value) {
                                        return '$' + Number(value).toLocaleString('es-CO');
                                    }
                                }
                            },
                            x: { grid: { display: false } }
                        }
                    }
                });
            })
            .catch(error => console.error('Error en actualizarGraficaVentas:', error));
    }

    // === FUNCIÓN PARA ACTUALIZAR INGRESO DIARIO ===
    function actualizarIngresoDia() {
        fetch('/ingreso-dia')
            .then(response => response.json())
            .then(data => {
                const ingreso = Number(data.ingresoDia || 0);
                const elemento = document.getElementById('ingresoDia');
                if (elemento) {
                    elemento.textContent = new Intl.NumberFormat('es-CO', {
                        minimumFractionDigits: 2,
                        maximumFractionDigits: 2
                    }).format(ingreso);
                }
            })
            .catch(error => console.error('Error en actualizarIngresoDia:', error));
    }

    // === FUNCIÓN PARA ACTUALIZAR MESAS OCUPADAS (TIEMPO REAL) ===
    function actualizarMesasOcupadas() {
        fetch('/mesas-ocupadas')
            .then(response => response.json())
            .then(data => {
                const ocupadas = Number(data.ocupadas || 0);
                const total = Number(data.total || 0);

                const ocupadasEl = document.getElementById('ocupadasCount');
                const totalEl = document.getElementById('mesasTotal');
                const listaEl = document.getElementById('listaMesasOcupadas');

                if (ocupadasEl) ocupadasEl.textContent = ocupadas;
                if (totalEl) totalEl.textContent = total;

                if (listaEl) {
                    const mesas = (data.mesas || []).map(m => m.numeromesa).sort((a,b) => a - b);
                    listaEl.textContent = mesas.length ? 'Mesas: ' + mesas.join(', ') : 'No hay mesas ocupadas';
                }
            })
            .catch(error => console.error('Error en actualizarMesasOcupadas:', error));
    }

    // === FUNCIÓN PARA ACTUALIZAR CANTIDAD DE PRODUCTOS REGISTRADOS ===
    function actualizarProductosRegistrados() {
        fetch('/productos-cantidad')
            .then(response => response.json())
            .then(data => {
                const cantidad = Number(data.cantidad || 0);
                const el = document.getElementById('productosCount');
                const infoEl = document.getElementById('productosInfo');

                if (el) el.textContent = cantidad;
                if (infoEl) infoEl.textContent = 'Total registrados.';
            })
            .catch(error => console.error('Error en actualizarProductosRegistrados:', error));
    }

    // Llamadas inmediatas al cargar la página
    actualizarGraficaVentas();
    actualizarIngresoDia();
    actualizarMesasOcupadas();
    actualizarProductosRegistrados();

    // Actualizar cada 10 segundos
    setInterval(actualizarGraficaVentas, 10000);
    setInterval(actualizarIngresoDia, 10000);
    setInterval(actualizarMesasOcupadas, 10000);
    setInterval(actualizarProductosRegistrados, 10000);
});
</script>
@stop
