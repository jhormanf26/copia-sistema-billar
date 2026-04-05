@extends('adminlte::page')

@section('title', 'Gestión de Mesas')

@section('content_header')
<h1><i class="fas fa-table"></i> Gestión de Mesas y Consumo</h1>
@stop

@section('content')
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<style>
/* Estilos generales */
.modal-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border-bottom: none;
}
.modal-header .btn-close { filter: brightness(0) invert(1); }
.modal-body { padding: 1.5rem; background-color: #f8f9fa; }
.cronometro {
    font-weight: bold;
    color: #444;
    background: #f3f3f3;
    padding: 6px 12px;
    border-radius: 8px;
    margin-bottom: 8px;
    display: inline-block;
}
.card-mesa {
    border-width: 3px;
    transition: all 0.3s ease;
}
.card-mesa:hover {
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    transform: translateY(-2px);
}

/* Estilos para el modal de agregar productos */
.productos-container::-webkit-scrollbar {
    width: 8px;
}

.productos-container::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 10px;
}

.productos-container::-webkit-scrollbar-thumb {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 10px;
}

.producto-row:hover {
    transform: translateX(5px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
}

.cantidad-input:focus {
    border-color: #667eea !important;
    box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25) !important;
}

.producto-row.oculto {
    display: none;
}
</style>

<div class="container-fluid">
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


    <div class="row">

        {{-- ================= MESAS  ================= --}}
        @foreach($mesas as $mesa)
        <div class="col-md-3 mb-3">
            <div class="card card-mesa {{ $mesa->estado == 'ocupada' ? 'border-danger' : ($mesa->estado == 'reservada' ? 'border-info' : 'border-success') }}">
                <div class="card-header text-center">
                    <h4 class="card-title mb-0">Mesa #{{ $mesa->numeromesa }}</h4>
                </div>
                <div class="card-body text-center">
                    <img src="{{ asset('img/mesas/' . ($mesa->tipo ?? 'default') . '.png') }}" alt="mesa" style="height:110px;">
                    <p class="mt-2"><strong>Estado:</strong> {{ ucfirst($mesa->estado) }}</p>

                    @if($mesa->tipo !== 'consumo')
                        <div id="cronometro-{{ $mesa->idmesa }}" class="cronometro">00:00:00</div>
                    @endif


                    <div class="d-flex justify-content-center gap-2 flex-wrap mt-3">
                       {{-- Iniciar / Parar --}}

                                @php
                                    // Obtener venta activa para esta mesa
                                    $ventaActiva = $mesa->ventaActiva()->whereNull('fechafin')->first();
                                @endphp

                            {{-- Mostrar botón Iniciar solo si la mesa está disponible --}}
                            @if( $mesa->tipo !== 'consumo' && ( $ventaActiva === null || !$ventaActiva->fechainicio))
                                <form action="{{ route('mesasventas.iniciar', $mesa->idmesa) }}" method="POST" onsubmit="startTimer(event, {{ $mesa->idmesa }})">
                                    @csrf
                                    <button class="btn btn-success btn-sm" title="Iniciar">
                                        <i class="fas fa-play"></i>
                                    </button>
                                </form>

                                {{-- Mostrar botón Parar solo si hay tiempo iniciado --}}
                                @elseif($ventaActiva && $ventaActiva->fechainicio && $mesa->tipo !== 'consumo')
                                    <form action="{{ route('mesasventas.finalizar', $mesa->idmesa) }}" method="POST" onsubmit="stopTimer(event, {{ $mesa->idmesa }})">
                                        @csrf
                                        <button class="btn btn-danger btn-sm" title="Parar">
                                            <i class="fas fa-stop"></i>
                                        </button>
                                    </form>
                                @endif


                        {{-- Cambiar estado --}}

                            <form action="{{ route('mesasventas.estado', $mesa->idmesa) }}" method="POST" class="d-flex gap-1">
                                @csrf
                                <select name="estado" class="form-control form-control-sm">
                                    <option value="disponible" {{ $mesa->estado=='disponible'?'selected':'' }}>Disponible</option>
                                    <option value="ocupada" {{ $mesa->estado=='ocupada'?'selected':'' }}>Ocupada</option>
                                    <option value="reservada" {{ $mesa->estado=='reservada'?'selected':'' }}>Reservada</option>
                                </select>
                                @if($mesa->estado != 'ocupada')
                                <button class="btn btn-primary btn-sm" title="Actualizar estado">
                                    <i class="fas fa-sync"></i>
                                </button>
                                @endif
                            </form>

                        {{-- Botón Carrito / Modal --}}
                        <button
                            type="button"
                            class="btn btn-warning btn-sm"
                            onclick="verificarMesa({{ $mesa->idmesa }}, '{{ $mesa->tipo }}', '{{ $mesa->estado }}')"
                            data-bs-target="#productosModal-{{ $mesa->idmesa }}">
                            <i class="fas fa-cart-plus"></i>
                        </button>

                        {{-- Botón para ver productos agregados --}}
                        @if($mesa->ventaActiva )
                            <button type="button" class="btn btn-secondary btn-sm" data-bs-toggle="modal" data-bs-target="#productosAgregadosModal-{{ $mesa->idmesa }}">
                                <i class="fas fa-eye"></i> Ver
                            </button>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        {{-- Modal de productos agregados (Mesa NORMAL) --}}
        @if(!empty($mesa->ventaActiva) )
        <div class="modal fade" id="productosAgregadosModal-{{ $mesa->idmesa }}" tabindex="-1"
             aria-labelledby="productosAgregadosLabel-{{ $mesa->idmesa }}" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-scrollable">
                <div class="modal-content">
                    {{-- Header --}}
                    <div class="modal-header">
                        <h5 class="modal-title d-flex align-items-center text-white" id="productosAgregadosLabel-{{ $mesa->idmesa }}">
                            <i class="fas fa-utensils me-2"></i>
                            Mesa #{{ $mesa->numeromesa }} - Productos Agregados
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                    </div>

                    {{-- Body --}}
                    <div class="modal-body">
                        {{-- Información del cronómetro --}}
                        <div class="alert alert-light border d-flex justify-content-between align-items-center mb-3">
                            <div>
                                <i class="fas fa-clock text-info me-2"></i>
                                <strong>Tiempo transcurrido:</strong>
                            </div>
                            <span class="badge bg-info fs-6" id="modal-cronometro-{{ $mesa->idmesa }}">00:00:00</span>
                        </div>

                        {{-- Lista de productos --}}
                        <div class="card mb-3">
                            <div class="card-header bg-light">
                                <h6 class="mb-0">
                                    <i class="fas fa-shopping-cart me-2"></i>
                                    Productos ({{ $mesa->ventaActiva->productos->count() }})
                                </h6>
                            </div>
                            <ul class="list-group list-group-flush lista-productos">
                                @foreach($mesa->ventaActiva->productos as $producto)
                                    <li class="list-group-item">
                                        <div class="row align-items-center">
                                            <div class="col-md-6">
                                                <strong>{{ $producto->nombre }}</strong>
                                                @if(!empty($producto->descripcion))
                                                    <small class="text-muted d-block">{{ $producto->descripcion }}</small>
                                                @endif
                                            </div>
                                            <div class="col-md-3 text-center">
                                                <span class="badge bg-primary rounded-pill fs-6">
                                                    Cantidad: {{ $producto->pivot->cantidad }}
                                                </span>
                                            </div>
                                            <div class="col-md-3 text-end">
                                                <div class="d-flex justify-content-end align-items-center gap-2">
                                                    @if(!empty($producto->pivot->precio))
                                                        <span class="text-success fw-bold">
                                                            ${{ number_format($producto->pivot->precio * $producto->pivot->cantidad, 0, ',', '.') }}
                                                        </span>
                                                    @endif

                                                    {{-- Input y Botón eliminar inline --}}
                                                    <form action="{{ route('mesasventas.eliminarProducto', [$mesa->ventaActiva->id, $producto->pivot->id]) }}"
                                                          method="POST"
                                                          class="d-flex align-items-center gap-1">
                                                        @csrf
                                                        @method('DELETE')
                                                        <input type="number" 
                                                               name="cantidad_eliminar"
                                                               min="1" 
                                                               max="{{ $producto->pivot->cantidad }}"
                                                               value="1"
                                                               class="form-control form-control-sm"
                                                               style="width: 60px;"
                                                               title="Cantidad a eliminar"
                                                               placeholder="Cant.">
                                                        <button type="submit" 
                                                                class="btn btn-sm btn-outline-danger"
                                                                title="Eliminar cantidad"
                                                                onclick="return confirm('¿Estás seguro de que deseas eliminar esta cantidad?');">
                                                            <i class="fas fa-trash-alt"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        </div>

                        {{-- Resumen de totales --}}
                        <div class="card">
                            <div class="card-body">
                                <div class="row g-3">
                                    {{-- Total productos --}}
                                    <div class="col-md-4" style="display: none;">
                                        <div class="d-flex justify-content-between align-items-center p-2 bg-light rounded">
                                            <span class="text-muted">
                                                <i class="fas fa-box me-2"></i>Total Productos:
                                            </span>
                                            <span class="fw-bold text-primary fs-5">
                                                $<span id="modal-total-productos-{{ $mesa->idmesa }}">
                                                    {{ number_format($mesa->ventaActiva->total ?? 0, 0, ',', '.') }}
                                                </span>
                                            </span>
                                        </div>
                                    </div>

                                    {{-- Costo tiempo --}}
                                    <div class="col-md-4">
                                        <div class="d-flex justify-content-between align-items-center p-2 bg-light rounded">
                                            <span class="text-muted">
                                                <i class="fas fa-hourglass-half me-2"></i>Costo Tiempo:
                                            </span>
                                            <span class="fw-bold text-warning fs-5">
                                                $<span id="modal-costo-tiempo-{{ $mesa->idmesa }}">0</span>
                                            </span>
                                        </div>
                                    </div>

                                    {{-- Total final --}}
                                    <div class="col-md-4">
                                        <div class="d-flex justify-content-between align-items-center p-2 bg-success text-white rounded">
                                            <span>
                                                <i class="fas fa-calculator me-2"></i>Total Final:
                                            </span>
                                            <span class="fw-bold fs-5">
                                                $<span id="modal-total-final-{{ $mesa->idmesa }}">0</span>
                                            </span>
                                        </div>
                                    </div>
                                    {{-- Método de pago --}}
                                        <div class="mb-3">
                                            <label for="metodo-pago-{{ $mesa->idmesa }}" class="form-label fw-bold">Método de Pago:</label>
                                            <select id="metodo-pago-{{ $mesa->idmesa }}" class="form-select form-select-sm">
                                                <option value="efectivo"{{$mesa->metodo_pago == 'efectivo' ? 'selected' : ''}}>Efectivo</option>
                                                <option value="transferencia"{{$mesa->metodo_pago == 'transferencia' ? 'selected' : ''}}>Transferencia</option>
                                                <option value="tarjeta"{{$mesa->metodo_pago == 'tarjeta' ? 'selected' : ''}}>Tarjeta</option>
                                            </select>
                                        </div>
                                </div>
                            </div>
                        </div>

                        {{-- Inputs hidden para cálculos --}}
                        <input type="hidden" id="total-productos-{{ $mesa->idmesa }}"
                               data-total="{{ $mesa->ventaActiva->total ?? 0 }}">
                        <input type="hidden" id="total-con-tiempo-{{ $mesa->idmesa }}">
                    </div>



                    {{-- Footer con acciones --}}

                    <div class="modal-footer bg-light">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            <i class="fas fa-times me-2"></i>Cerrar
                        </button>
                        <button type="button" class="btn btn-success" onclick="finalizarVenta({{ $mesa->idmesa }})">

                            <i class="fas fa-check-circle me-2"></i>Finalizar Venta
                        </button>
                    </div>
                </div>
            </div>
        </div>
        @endif

        {{-- Modal de agregar productos (Mesa NORMAL) --}}
        <div class="modal fade" id="productosModal-{{ $mesa->idmesa }}" tabindex="-1" aria-labelledby="productosModalLabel-{{ $mesa->idmesa }}" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content border-0 shadow-lg">
                    <div class="modal-header">
                        <h5 class="modal-title text-white fw-bold" id="productosModalLabel-{{ $mesa->idmesa }}">
                            <i class="fas fa-utensils me-2"></i>Agregar productos a Mesa #{{ $mesa->numeromesa }}
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                    </div>
                    <div class="modal-body p-4">
                        {{-- Buscador mejorado --}}
                        <div class="mb-4">
                            <div class="input-group input-group-lg">
                                <span class="input-group-text bg-white border-end-0">
                                    <i class="fas fa-search text-muted"></i>
                                </span>
                                <input type="text"
                                       class="form-control border-start-0 buscador-productos-{{ $mesa->idmesa }}"
                                       placeholder="Buscar producto por nombre..."
                                       style="box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
                            </div>
                            <small class="text-muted ms-2">
                                <span class="resultados-count-{{ $mesa->idmesa }}">{{ count($productos) }}</span> productos encontrados
                            </small>
                        </div>

                        <form action="{{ route('mesasventas.agregarProductos', $mesa->idmesa) }}" method="POST">
                            @csrf
                            <div class="productos-container productos-container-{{ $mesa->idmesa }}" style="max-height: 500px; overflow-y: auto;">
                                @php
                                    $chunks = $productos->chunk(10);
                                @endphp

                                @foreach($chunks as $index => $chunk)
                                <div class="productos-grupo mb-4" data-grupo="{{ $index }}">
                                    <div class="d-flex align-items-center mb-3">
                                        <span class="badge bg-primary rounded-pill px-3 py-2">
                                            Productos {{ ($index * 10) + 1 }} - {{ min(($index + 1) * 10, count($productos)) }}
                                        </span>
                                        <hr class="flex-grow-1 ms-3">
                                    </div>

                                    <div class="table-responsive">
                                        <table class="table table-hover align-middle">
                                            <thead style="background-color: #e9ecef; position: sticky; top: 0; z-index: 10;">
                                                <tr>
                                                    <th class="text-primary fw-semibold">Producto</th>
                                                    <th class="text-primary fw-semibold text-center">Precio</th>
                                                    <th class="text-primary fw-semibold text-center">Stock</th>
                                                    <th class="text-primary fw-semibold text-center" style="width: 150px;">Cantidad</th>
                                                </tr>
                                            </thead>
                                            <tbody class="productos-tbody">
                                                @foreach($chunk as $producto)
                                                <tr class="producto-row bg-white"
                                                    data-nombre="{{ strtolower($producto->nombre) }}"
                                                    style="transition: all 0.3s ease;">
                                                    <td class="fw-medium">
                                                        <i class="fas fa-box text-muted me-2"></i>
                                                        {{ $producto->nombre }}
                                                    </td>
                                                    <td class="text-center">
                                                        <span class="badge bg-success bg-opacity-10 text-success px-3 py-2">
                                                            ${{ number_format($producto->precio, 0, ',', '.') }}
                                                        </span>
                                                    </td>
                                                    <td class="text-center">
                                                        <span class="badge {{ $producto->stock > 10 ? 'bg-info' : 'bg-warning' }} bg-opacity-10 {{ $producto->stock > 10 ? 'text-info' : 'text-warning' }} px-3 py-2">
                                                            {{ $producto->stock }} unid.
                                                        </span>
                                                    </td>
                                                    <td class="text-center">
                                                        <input type="number"
                                                               name="cantidades[{{ $producto->idproducto }}]"
                                                               min="0"
                                                               max="{{ $producto->stock }}"
                                                               class="form-control form-control-sm text-center cantidad-input"
                                                               value="0"
                                                               style="border: 2px solid #dee2e6; border-radius: 8px;">
                                                    </td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                @endforeach
                            </div>

                            <div class="d-flex justify-content-between align-items-center mt-4 pt-3 border-top">
                                <div>
                                    <small class="text-muted">
                                        <i class="fas fa-info-circle me-1"></i>
                                        Selecciona las cantidades deseadas
                                    </small>
                                </div>
                                <button type="submit" class="btn btn-lg px-4" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border: none; color: white;" onclick="filtrarProductos(event)">
                                    <i class="fas fa-check me-2"></i>Agregar Seleccionados
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        @endforeach



    </div>
</div>

@stop

@section('js')
<script>
    // Validación en tiempo real para inputs de cantidad a eliminar
    document.addEventListener('DOMContentLoaded', function() {
        const inputsEliminar = document.querySelectorAll('input[name="cantidad_eliminar"]');
        
        inputsEliminar.forEach(input => {
            input.addEventListener('input', function() {
                const max = parseInt(this.max) || 1;
                let valor = parseInt(this.value) || 0;
                
                if (valor > max) {
                    this.value = max;
                }
                if (valor < 1) {
                    this.value = 1;
                }
            });
        });
    });

    //  Filtrar productos con cantidad > 0 antes de enviar
    function filtrarProductos(event) {
        event.preventDefault();

        const form = event.target.closest('form');
        const inputs = form.querySelectorAll('input[name^="cantidades"]');
        let hayProductos = false;

        // Primero, ocultar/deshabilitar todos los inputs con cantidad 0
        inputs.forEach(input => {
            const cantidad = parseInt(input.value) || 0;
            if (cantidad > 0) {
                hayProductos = true;
                input.disabled = false; // Habilitar para que se envíe
            } else {
                input.disabled = true; // Deshabilitar para que NO se envíe
            }
        });

        if (!hayProductos) {
            alert(' Debes seleccionar al menos un producto con cantidad mayor a 0');
            // Volver a habilitar los inputs para que se puedan editar
            inputs.forEach(input => input.disabled = false);
            return false;
        }

        // Enviar el formulario
        form.submit();
    }

    function finalizarVenta(idmesa) {

    console.log(idmesa)

    const costoTiempo = document.getElementById(`modal-costo-tiempo-${idmesa}`).textContent.replace(/\./g, '');
    const totalFinal = document.getElementById(`modal-total-final-${idmesa}`).textContent.replace(/\./g, '');
    const metodoPago = document.getElementById(`metodo-pago-${idmesa}`).value;




    fetch(`/mesasventas/finalizarVenta/${idmesa}`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({
            metodo_pago: metodoPago
        })
    })
    .then(response => response.json())
    .then(data => {
        if(data.success) {
            location.reload();
        } else {
            // Si data.message existe y no está vacío, lo mostramos;
            // si no, usamos el mensaje por defecto.
            const mensaje = data.message && data.message.trim() !== ''
                ? data.message
                : 'Error al finalizar la venta';

            alert(mensaje);
        }
    })
    .catch(err => console.error('Error en la petición:', err));
}

</script>


<script>
const PRECIO_POR_HORA = 10000; // 💰 Precio por hora
let timers = {};

// 🕐 Iniciar cronómetro
function startTimer(event, id) {
    event.preventDefault();

    if (localStorage.getItem('startTime-' + id)) {
        event.target.submit();
        return;
    }

    const startTime = Date.now();
    localStorage.setItem('startTime-' + id, startTime);

    updateTimer(id);
    timers[id] = setInterval(() => {
        updateTimer(id);
        calculateAndDisplayCardTotals(id); // Actualiza el total en el card principal
    }, 1000);

    setTimeout(() => {
        event.target.submit();
    }, 500);
}

// 🛑 Detener cronómetro
function stopTimer(event, id) {
    event.preventDefault();

    clearInterval(timers[id]);
    localStorage.removeItem('startTime-' + id);

    const el = document.getElementById('cronometro-' + id);
    if (el) el.innerText = "00:00:00";

    // Ocultar o resetear valores del modal al detener
    document.getElementById('modal-costo-tiempo-' + id).textContent = "0";
    document.getElementById('modal-total-final-' + id).textContent = "0";
    document.getElementById('modal-cronometro-' + id).innerText = "00:00:00";


    event.target.submit();
}

// 🔁 Actualizar cronómetro y totales
function updateTimer(id) {
    const startTime = localStorage.getItem('startTime-' + id);
    if (!startTime) return;

    const diff = Math.floor((Date.now() - parseInt(startTime)) / 1000);
    const h = String(Math.floor(diff / 3600)).padStart(2, '0');
    const m = String(Math.floor((diff % 3600) / 60)).padStart(2, '0');
    const s = String(diff % 60).padStart(2, '0');

    const tiempoStr = `${h}:${m}:${s}`;
    const el = document.getElementById('cronometro-' + id);
    if (el) el.innerText = tiempoStr;

    // Sincronizar y calcular totales en el modal si está abierto
    syncModalTimer(id);
    calculateAndDisplayModalTotals(id);
    calculateAndDisplayCardTotals(id); // Mantiene la actualización en el card (oculto en tu HTML original)
}

// 💲 Sumar productos + tiempo real (Card Principal - función original renombrada para claridad)
function calculateAndDisplayCardTotals(id) {
    const cronometro = document.getElementById('cronometro-' + id);
    const totalProductosEl = document.getElementById('total-productos-' + id);
    const totalConTiempoEl = document.getElementById('total-con-tiempo-' + id);

    if (!cronometro || !totalProductosEl || !totalConTiempoEl) return;

    const totalProductos = parseFloat(totalProductosEl.dataset.total || 0);

    const tiempo = cronometro.textContent.split(':');
    if (tiempo.length !== 3) return;

    const horas = parseInt(tiempo[0]);
    const minutos = parseInt(tiempo[1]);
    const segundos = parseInt(tiempo[2]);

    const horasTotales = horas + minutos / 60 + segundos / 3600;
    const costoTiempo = horasTotales * PRECIO_POR_HORA;
    const totalFinal = totalProductos + costoTiempo;

    totalConTiempoEl.textContent = new Intl.NumberFormat('es-CO').format(Math.round(totalFinal));
}

// 📋 Calcular y mostrar totales en el Modal (Productos + Tiempo)
function calculateAndDisplayModalTotals(id) {
    const modalCronometroEl = document.getElementById('modal-cronometro-' + id);
    const modalTotalProductosEl = document.getElementById('modal-total-productos-' + id);
    const modalCostoTiempoEl = document.getElementById('modal-costo-tiempo-' + id);
    const modalTotalFinalEl = document.getElementById('modal-total-final-' + id);

    // Asegurarse de que los elementos existan
    if (!modalCronometroEl || !modalTotalProductosEl || !modalCostoTiempoEl || !modalTotalFinalEl) {
        return;
    }

    const totalProductosStr = modalTotalProductosEl.textContent.replace(/[^0-9,-]+/g, "").replace(",", ".");
    const totalProductos = parseFloat(totalProductosStr) || 0;

    const tiempo = modalCronometroEl.textContent.split(':');
    if (tiempo.length !== 3) return;

    const horas = parseInt(tiempo[0]);
    const minutos = parseInt(tiempo[1]);
    const segundos = parseInt(tiempo[2]);

    const horasTotales = horas + minutos / 60 + segundos / 3600;
    const costoTiempo = horasTotales * PRECIO_POR_HORA;
    const totalFinal = totalProductos + costoTiempo;

    // Formatear y mostrar los valores
    const formatter = new Intl.NumberFormat('es-CO');

    modalCostoTiempoEl.textContent = formatter.format(Math.round(costoTiempo));
    modalTotalFinalEl.textContent = formatter.format(Math.round(totalFinal));
}


// 🔁 Restaurar cronómetros al recargar
window.addEventListener('load', () => {
    Object.keys(localStorage).forEach(key => {
        if (key.startsWith('startTime-')) {
            const id = key.split('-')[1];
            updateTimer(id);
            timers[id] = setInterval(() => {
                updateTimer(id);
                calculateAndDisplayCardTotals(id);
            }, 1000);
        }
    });
});

// 🔍 Buscador de productos
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.buscador-productos').forEach(function(input) {
        input.addEventListener('keyup', function() {
            const filter = this.value.toLowerCase();
            const table = this.closest('.modal-body').querySelector('tbody');
            if (!table) return;
            table.querySelectorAll('tr').forEach(function(row) {
                const text = row.querySelector('td').textContent.toLowerCase();
                row.style.display = text.includes(filter) ? '' : 'none';
            });
        });
    });
});

// 🌀 Mantener modales abiertos entre páginas
$(document).ready(function() {
    // Al abrir el modal de productos agregados, forzar el cálculo inicial
    $(document).on('shown.bs.modal', function (e) {
        localStorage.setItem('lastModalOpen', '#' + e.target.id);
        const modalId = e.target.id;
        const match = modalId.match(/productosAgregadosModal-(\d+)/);
        if (match) {
            const mesaId = match[1];
            // Asegurarse de que el cronómetro se haya sincronizado primero
            syncModalTimer(mesaId);
            calculateAndDisplayModalTotals(mesaId);
        }
    });

    $(document).on('hidden.bs.modal', function () {
        localStorage.removeItem('lastModalOpen');
    });

    let lastModal = localStorage.getItem('lastModalOpen');
    if (lastModal) {
        $(lastModal).modal('show');
    }

    // Lógica para mantener el modal abierto al paginar (AJAX)
    $(document).on('click', '.pagination a', function(e) {
        e.preventDefault();
        let url = $(this).attr('href');
        let target = $(this).closest('.modal-content').find('table').parent().parent().parent().parent().attr('id'); // ID del modal

        // Cargar el contenido de la paginación dentro del modal
        $.get(url, function(data) {
            // Reemplazar solo el contenido del modal-body
            let newModalBody = $(data).find('#' + target + ' .modal-body').html();
            $('#' + target + ' .modal-body').html(newModalBody);

            // Mantener el modal abierto
            let lastModal = localStorage.getItem('lastModalOpen');
            if (lastModal) $(lastModal).modal('show');
        });
    });
});

// 🔁 Sincronizar cronómetro del modal con la mesa principal
function syncModalTimer(id) {
    const mainEl = document.getElementById('cronometro-' + id);
    const modalEl = document.getElementById('modal-cronometro-' + id);
    if (mainEl && modalEl) {
        modalEl.innerText = mainEl.innerText;
    }
}
// 🧩 Verificar si la mesa puede abrir el modal de productos
function verificarMesa(id, tipo, estado) {
    // Si es mesa de consumo, permitir siempre
    if (tipo === 'consumo' || estado === 'ocupada') {
        const modal = new bootstrap.Modal(document.getElementById('productosModal-' + id));
        modal.show();
        return;
    }

    // Para las demás mesas (pool, libre, tresbandas)
    const startTime = localStorage.getItem('startTime-' + id);

    if (!startTime) {
        // Si no hay cronómetro activo
        alert('⚠️ La mesa está disponible. Inicia el tiempo antes de agregar productos.');
        return;
    }

    // Si el tiempo está activo, mostrar el modal normalmente
    const modal = new bootstrap.Modal(document.getElementById('productosModal-' + id));
    modal.show();
}

document.addEventListener('DOMContentLoaded', function () {
    @foreach($mesas as $mesa)
    // Tomamos el input y el contenedor de productos
    const buscador{{ $mesa->idmesa }} = document.querySelector('.buscador-productos-{{ $mesa->idmesa }}');
    const productosTbody{{ $mesa->idmesa }} = document.querySelectorAll('.productos-tbody tr.producto-row');
    const resultadosCount{{ $mesa->idmesa }} = document.querySelector('.resultados-count-{{ $mesa->idmesa }}');

    if(buscador{{ $mesa->idmesa }}) {
        buscador{{ $mesa->idmesa }}.addEventListener('input', function () {
            const texto = this.value.toLowerCase();
            let visibles = 0;

            productosTbody{{ $mesa->idmesa }}.forEach(tr => {
                const nombre = tr.dataset.nombre;
                if(nombre.includes(texto)) {
                    tr.style.display = '';
                    visibles++;
                } else {
                    tr.style.display = 'none';
                }
            });

            resultadosCount{{ $mesa->idmesa }}.textContent = visibles;
        });
    }
    @endforeach
});



</script>
@stop
