@extends('adminlte::page')

@section('title', 'Gestión de Productos')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1 class="m-0 text-dark"><i class="fas fa-boxes"></i> Gestión de Productos</h1>
        {{-- BOTÓN MOVIDO AQUÍ --}}
        <a href="{{ route('inventario.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left"></i> Volver a Inventario
        </a>
    </div>
@stop

@section('content')
<div class="container-fluid">

    {{-- ALERTAS --}}
    @if(session('success'))
        <x-adminlte-alert theme="success" title="Éxito" dismissable>
            {{ session('success') }}
        </x-adminlte-alert>
    @endif

    @if(session('error'))
        <x-adminlte-alert theme="danger" title="Error" dismissable>
            {{ session('error') }}
        </x-adminlte-alert>
    @endif

    {{-- ALERTA DE STOCK CON ID PARA OCULTAR AUTOMÁTICAMENTE --}}
    @if(session('alerta_stock'))
        <x-adminlte-alert theme="warning" title="Alerta de Stock" dismissable id="alertaStockAutomatica">
            <i class="fas fa-box-open"></i> {{ session('alerta_stock') }}
        </x-adminlte-alert>
    @endif

    ---

    {{-- FILTROS Y BUSCADOR (Mantenemos el resto igual) --}}
    <div class="row">
        <div class="col-12">
            <x-adminlte-card title="Filtros y Acciones" theme="info" icon="fas fa-filter" collapsible>
                <div class="d-flex justify-content-between align-items-center flex-wrap">
                    <div class="d-flex mb-3 mb-md-0 me-md-3 flex-grow-1">
                        <input type="text" id="buscarProducto" class="form-control me-2" placeholder="Buscar por ID, Nombre o Precio..." style="max-width: 400px;">
                        <button type="button" class="btn btn-outline-primary" id="btnLimpiar" title="Limpiar Búsqueda">
                            <i class="fas fa-sync-alt"></i>
                        </button>
                    </div>
                    <a href="{{ route('productos.create') }}" class="btn btn-success">
                        <i class="fas fa-plus-circle"></i> Agregar Producto
                    </a>
                </div>
            </x-adminlte-card>
        </div>
    </div>

    ---

    {{-- TABLA DE PRODUCTOS (Mantenemos el resto igual) --}}
    <div class="card shadow-sm">
        <div class="card-header bg-gradient-primary">
            <h3 class="card-title"><i class="fas fa-list-alt"></i> Listado de Productos</h3>
        </div>
        <div class="card-body table-responsive p-0">
            @if(isset($productos) && $productos->count() > 0)
                <table class="table table-striped table-valign-middle" id="tablaProductos">
                    <thead class="bg-light">
                        <tr class="text-center">
                            <th style="width: 5%">ID</th>
                            <th style="width: 30%">Nombre</th>
                            <th style="width: 15%">Precio Venta</th>
                            <th style="width: 20%">Stock Disponible</th>
                            <th style="width: 15%">Cantidad Vendida</th>
                            <th style="width: 15%">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($productos as $producto)
                            <tr>
                                <td class="text-center">{{ $producto->idproducto }}</td>
                                <td class="text-center">{{ $producto->nombre }}</td>
                                <td class="text-center">${{ number_format($producto->precio, 2) }}</td>
                                <td class="text-center">
                                    @if($producto->stock < 10)
                                        <span class="badge bg-warning text-dark p-2">
                                            <i class="fas fa-exclamation-triangle"></i>
                                            Stock Bajo ({{ $producto->stock }} un.)
                                        </span>
                                    @else
                                        <span class="text-secondary">
                                            {{ $producto->stock }} unidades
                                        </span>
                                    @endif
                                </td>
                                <td class="text-center">{{ $producto->cantidad_vendida ?? 0 }}</td>
                                <td class="text-center">
                                    <a href="{{ route('productos.edit', $producto->idproducto) }}" class="btn btn-xs btn-default text-primary mx-1 shadow" title="Editar">
                                        <i class="fa fa-lg fa-fw fa-pen"></i>
                                    </a>

                                    <button class="btn btn-xs btn-default text-danger mx-1 shadow" onclick="alert('❌ Este producto NO se puede eliminar');" title="Eliminar">
                                        <i class="fa fa-lg fa-fw fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <div class="alert alert-secondary m-3 text-center">
                    <i class="fas fa-info-circle"></i> No hay productos registrados.
                </div>
            @endif
        </div>
    </div>
</div>
@stop

@section('js')
<script>
    console.log('Vista de productos lista 🟢');

    // 🆕 INICIO DEL CÓDIGO PARA OCULTAR LA ALERTA 🆕
    const alerta = document.getElementById('alertaStockAutomatica');
    if (alerta) {
        // Ocultar la alerta después de 5 segundos (5000 milisegundos)
        setTimeout(() => {
            // Usamos la clase de Bootstrap 'fade' y 'show' para una transición suave (opcional)
            alerta.classList.add('fade');
            // La función remove() elimina el elemento del DOM
            setTimeout(() => alerta.remove(), 150); // Pequeña espera para la transición
        }, 5000); // 5 segundos
    }
    // ----------------------------------------------------

    // Filtro en tiempo real
    document.getElementById('buscarProducto').addEventListener('keyup', function() {
        let filtro = this.value.toLowerCase().trim();
        let filas = document.querySelectorAll('#tablaProductos tbody tr');

        filas.forEach(fila => {
            let id = fila.children[0].textContent.toLowerCase();
            let nombre = fila.children[1].textContent.toLowerCase();
            let precio = fila.children[2].textContent.toLowerCase().replace('$', '').replace(',', '');

            let coincide = id.includes(filtro) || nombre.includes(filtro) || precio.includes(filtro);

            fila.style.display = coincide ? '' : 'none';
        });
    });

    // Botón para limpiar el buscador
    document.getElementById('btnLimpiar').addEventListener('click', function() {
        document.getElementById('buscarProducto').value = '';
        document.getElementById('buscarProducto').dispatchEvent(new Event('keyup'));
    });
</script>
@stop
