@extends('adminlte::page')

@section('title', 'Editar Producto')

@section('content')
<div class="container">

    {{-- TÍTULO PRINCIPAL --}}
    <div class="mb-4">
        <h1 class="text-primary">
            <i class="fas fa-edit"></i> Editar Producto
        </h1>
        <p class="text-muted mb-0">Modifica la información del producto seleccionado.</p>
    </div>

    {{-- ERRORES --}}
    @if ($errors->any())
        <div class="alert alert-danger">
            <h5><i class="fas fa-exclamation-circle"></i> Hay errores en el formulario:</h5>
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- CARD PRINCIPAL --}}
    <div class="card shadow-sm">
        <div class="card-body">

            <form action="{{ route('productos.update', $producto->idproducto) }}" method="POST">
                @csrf

                <div class="row">

                    {{-- ID del Producto --}}
                    <div class="col-md-4 mb-3">
                        <label for="idproducto" class="form-label fw-bold">ID Producto</label>
                        <input
                            type="text"
                            name="idproducto"
                            id="idproducto"
                            class="form-control"
                            value="{{ $producto->idproducto }}"
                            readonly
                        >
                        <small class="text-warning d-block mt-1">
                            <i class="fas fa-lock"></i> Este campo no se puede modificar.
                        </small>
                    </div>

                    {{-- Nombre --}}
                    <div class="col-md-8 mb-3">
                        <label for="nombre" class="form-label fw-bold">Nombre</label>
                        <input
                            type="text"
                            name="nombre"
                            id="nombre"
                            class="form-control"
                            value="{{ old('nombre', $producto->nombre) }}"
                            required
                        >
                    </div>

                    {{-- Descripción --}}
                    <div class="col-md-12 mb-3">
                        <label for="descripcion" class="form-label fw-bold">Descripción</label>
                        <textarea
                            name="descripcion"
                            id="descripcion"
                            class="form-control"
                            rows="3"
                            required
                        >{{ old('descripcion', $producto->descripcion) }}</textarea>
                    </div>

                    {{-- Precio --}}
                    <div class="col-md-4 mb-3">
                        <label for="precio" class="form-label fw-bold">Precio Venta</label>
                        <input
                            type="number"
                            step="0.01"
                            name="precio"
                            id="precio"
                            class="form-control"
                            value="{{ old('precio', $producto->precio) }}"
                            required
                        >
                    </div>

                    {{-- Stock --}}
                    <div class="col-md-4 mb-3">
                        <label for="stock" class="form-label fw-bold">Stock</label>
                        <input
                            type="number"
                            name="stock"
                            id="stock"
                            class="form-control"
                            value="{{ old('stock', $producto->stock) }}"
                            readonly
                        >
                        <small class="text-warning d-block mt-1">
                            <i class="fas fa-lock"></i> Este campo no se puede modificar.
                        </small>
                    </div>

                    {{-- Proveedor --}}
                    <div class="col-md-4 mb-3">
                        <label for="idproveedor" class="form-label fw-bold">Proveedor</label>
                        <select name="idproveedor" id="idproveedor" class="form-control" required>
                            <option value="">Seleccione un proveedor</option>
                            @foreach($proveedores as $prov)
                                <option
                                    value="{{ $prov->idproveedor }}"
                                    {{ $producto->idproveedor == $prov->idproveedor ? 'selected' : '' }}
                                >
                                    {{ $prov->nombre }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                {{-- BOTONES --}}
                <div class="mt-4">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Actualizar
                    </button>

                    <a href="{{ route('productos.index') }}" class="btn btn-secondary">
                        <i class="fas fa-times"></i> Cancelar
                    </a>
                </div>

            </form>

        </div>
    </div>
</div>
@stop
