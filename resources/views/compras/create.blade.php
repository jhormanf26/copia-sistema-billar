@extends('adminlte::page')

@section('title', 'Nueva Compra')

@section('content_header')
    <div class="container-fluid">
        <div class="row align-items-center justify-content-between">
            <div class="col">
                <h1 class="text-dark"><i class="fas fa-plus-circle mr-2"></i> Registrar Nueva Compra</h1>
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
    {{-- ALERTA DE ERRORES --}}
    @if($errors->any())
    <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
        <i class="fas fa-exclamation-circle mr-3"></i>
        <strong>Errores:</strong>
        <ul class="mb-0 mt-2">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
        <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    @endif

    <form method="POST" action="{{ route('compras.store') }}" id="formCompra">
        @csrf

        {{-- SELECCIÓN DE PROVEEDOR --}}
        <div class="row">
            <div class="col-md-6">
                <div class="card card-outline card-primary shadow-lg">
                    <div class="card-header bg-primary">
                        <h3 class="card-title"><i class="fas fa-truck mr-2"></i> Seleccionar Proveedor</h3>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <label for="proveedor">Proveedor <span class="text-danger">*</span></label>
                            <select id="proveedor" name="idproveedor" class="form-control @error('idproveedor') is-invalid @enderror" required>
                                <option value="">-- Selecciona un proveedor --</option>
                                @foreach($proveedores as $prov)
                                    <option value="{{ $prov->idproveedor }}" {{ old('idproveedor') == $prov->idproveedor ? 'selected' : '' }}>
                                        {{ $prov->nombre }}
                                    </option>
                                @endforeach
                            </select>
                            @error('idproveedor')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <hr class="my-4">

        {{-- PRODUCTOS DISPONIBLES --}}
        <div class="card card-outline card-info shadow-lg mb-4">
            <div class="card-header bg-info">
                <h3 class="card-title"><i class="fas fa-box mr-2"></i> Productos del Proveedor</h3>
            </div>
            <div class="card-body">
                <div id="productos">
                    <div class="alert alert-secondary text-center py-4">
                        <i class="fas fa-info-circle mr-2"></i> Selecciona un proveedor para ver sus productos
                    </div>
                </div>
            </div>
        </div>

        {{-- PRODUCTOS AGREGADOS --}}
        <div class="card card-outline card-success shadow-lg">
            <div class="card-header bg-success">
                <h3 class="card-title"><i class="fas fa-shopping-cart mr-2"></i> Productos Agregados a la Compra</h3>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-hover" id="tablaCompras">
                        <thead class="bg-light">
                            <tr>
                                <th>Producto</th>
                                <th>Cantidad</th>
                                <th>Precio Compra</th>
                                <th>Precio Venta</th>
                                <th>Subtotal</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>

                <div class="row mt-4 border-top pt-3">
                    <div class="col-md-6 text-right">
                        <h4>Total de Compra:</h4>
                    </div>
                    <div class="col-md-6">
                        <h3 class="text-success">$<span id="total">0</span></h3>
                    </div>
                </div>

                <input type="hidden" name="total" id="inputTotal">
                <input type="hidden" name="productos" id="productosJSON">
            </div>
            <div class="card-footer">
                <button type="submit" class="btn btn-success btn-lg" id="btnFinalizar" disabled>
                    <i class="fas fa-check-circle mr-2"></i> Finalizar Compra
                </button>
                <a href="{{ route('compras.index') }}" class="btn btn-secondary btn-lg ml-2">
                    <i class="fas fa-times mr-2"></i> Cancelar
                </a>
            </div>
        </div>
    </form>
</div>
@endsection

@section('js')
<script>
let productosSeleccionados = [];

function actualizarTabla() {
    let tbody = document.querySelector("#tablaCompras tbody");
    tbody.innerHTML = "";
    let total = 0;

    if (productosSeleccionados.length === 0) {
        tbody.innerHTML = `
            <tr>
                <td colspan="6" class="text-center text-muted py-4">
                    <i class="fas fa-box-open mr-2"></i> Aún no hay productos agregados
                </td>
            </tr>
        `;
        document.getElementById("btnFinalizar").disabled = true;
    } else {
        productosSeleccionados.forEach((p, idx) => {
            let subtotal = p.cantidad * p.precio_compra;
            total += subtotal;

            tbody.innerHTML += `
                <tr>
                    <td>${p.nombre}</td>
                    <td><span class="badge badge-info">${p.cantidad}</span></td>
                    <td>$${Number(p.precio_compra).toLocaleString('es-CO', {maximumFractionDigits: 2})}</td>
                    <td>$${Number(p.precio_venta).toLocaleString('es-CO', {maximumFractionDigits: 2})}</td>
                    <td><strong>$${Number(subtotal).toLocaleString('es-CO', {maximumFractionDigits: 2})}</strong></td>
                    <td>
                        <button type="button" class="btn btn-sm btn-danger" onclick="eliminarProducto(${idx})">
                            <i class="fas fa-trash"></i>
                        </button>
                    </td>
                </tr>
            `;
        });
        document.getElementById("btnFinalizar").disabled = false;
    }

    document.getElementById("total").textContent = Number(total).toLocaleString('es-CO', {maximumFractionDigits: 2});
    document.getElementById("inputTotal").value = total;
    document.getElementById("productosJSON").value = JSON.stringify(productosSeleccionados);
}

function eliminarProducto(idx) {
    productosSeleccionados.splice(idx, 1);
    actualizarTabla();
}

document.getElementById('proveedor').addEventListener('change', function () {
    let id = this.value;
    
    if (!id) {
        document.getElementById("productos").innerHTML = `
            <div class="alert alert-secondary text-center py-4">
                <i class="fas fa-info-circle mr-2"></i> Selecciona un proveedor para ver sus productos
            </div>
        `;
        return;
    }

    fetch(`/compras/productos/${id}`)
        .then(r => r.json())
        .then(data => {
            if (!Array.isArray(data)) data = JSON.parse(data);
            
            let html = `
                <div class="table-responsive">
                    <table class="table table-bordered table-sm">
                        <thead class="bg-light">
                            <tr>
                                <th>Producto</th>
                                <th style="width: 120px;">Cantidad</th>
                                <th style="width: 120px;">Precio Compra</th>
                                <th style="width: 120px;">Precio Venta</th>
                                <th style="width: 100px;"></th>
                            </tr>
                        </thead>
                        <tbody>
            `;

            if (data.length === 0) {
                html += `
                    <tr>
                        <td colspan="5" class="text-center text-muted py-3">
                            No hay productos de este proveedor
                        </td>
                    </tr>
                `;
            } else {
                data.forEach(p => {
                    html += `
                        <tr>
                            <td>${p.nombre}</td>
                            <td><input type="number" id="cant_${p.idproducto}" class="form-control form-control-sm" value="1" min="1"></td>
                            <td><input type="number" id="pc_${p.idproducto}" class="form-control form-control-sm" step="0.01" value="${p.precio}" min="0"></td>
                            <td><input type="number" id="pv_${p.idproducto}" class="form-control form-control-sm" step="0.01" value="${p.precio}" min="0"></td>
                            <td><button type="button" class="btn btn-sm btn-primary w-100" onclick="agregarProducto(${p.idproducto}, '${p.nombre}')">
                                <i class="fas fa-plus mr-1"></i> Agregar
                            </button></td>
                        </tr>
                    `;
                });
            }

            html += "</tbody></table></div>";
            document.getElementById("productos").innerHTML = html;
        })
        .catch(error => {
            console.error('Error:', error);
            document.getElementById("productos").innerHTML = `
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-circle mr-2"></i> Error al cargar los productos
                </div>
            `;
        });
});

function agregarProducto(id, nombre) {
    let cantidad = document.getElementById(`cant_${id}`).value;
    let precioCompra = document.getElementById(`pc_${id}`).value;
    let precioVenta = document.getElementById(`pv_${id}`).value;

    if (!cantidad || !precioCompra) {
        alert("Complete todos los campos");
        return;
    }

    // Verificar si el producto ya existe
    let existe = productosSeleccionados.find(p => p.idproducto == id);
    if (existe) {
        alert("Este producto ya fue agregado");
        return;
    }

    productosSeleccionados.push({
        idproducto: id,
        nombre: nombre,
        cantidad: parseInt(cantidad),
        precio_compra: parseFloat(precioCompra),
        precio_venta: parseFloat(precioVenta) || 0
    });

    actualizarTabla();
    alert('Producto agregado correctamente');
}

// Inicializar tabla vacía
actualizarTabla();
</script>
@endsection
