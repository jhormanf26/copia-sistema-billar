<?php

namespace App\Http\Controllers;

use App\Models\Productos;
use App\Models\Proveedores;
use Illuminate\Http\Request;

class ProductosController extends Controller
{
    /**
     * Muestra la lista de productos con buscador y alertas.
     */
    public function index(Request $request)
    {
        $buscar = $request->input('buscar', null);

        $query = Productos::with('proveedor');

        if ($buscar) {
            $query->where(function ($q) use ($buscar) {
                $q->where('nombre', 'LIKE', "%{$buscar}%")
                  ->orWhere('descripcion', 'LIKE', "%{$buscar}%")
                  ->orWhere('idproducto', $buscar);
            });
        }

        $productos = $query->orderBy('nombre', 'asc')->get();

        $productosBajoStock = $productos->where('stock', '<', 10)->count();

        if ($productosBajoStock > 0) {
            session()->flash('alerta_stock', '¡Atención! Algunos productos tienen menos de 10 unidades disponibles.');
        }

        return view('productos.index', compact('productos', 'buscar'));
    }

    /**
     * Muestra el formulario de creación.
     */
    public function create()
    {
        $proveedores = Proveedores::orderBy('nombre', 'asc')->get();
        return view('productos.create', compact('proveedores'));
    }

    /**
     * Guarda un nuevo producto.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:100',
            'descripcion' => 'required|string',
            'precio' => 'required|numeric|min:1000',
            'stock' => 'required|integer|min:0',
            'idproveedor' => 'required|exists:proveedores,idproveedor',
        ]);

        Productos::create($validated);

        return redirect()->route('productos.index')->with('success', 'Producto agregado correctamente.');
    }

    /**
     * Muestra el formulario de edición.
     */
    public function edit($id)
    {
        $producto = Productos::findOrFail($id);
        $proveedores = Proveedores::orderBy('nombre', 'asc')->get();
        return view('productos.edit', compact('producto', 'proveedores'));
    }

    /**
     * Actualiza los datos del producto.
     */
    public function update(Request $request, $id)
    {
        $producto = Productos::findOrFail($id);

        $validated = $request->validate([
            'nombre' => 'required|string|max:100',
            'descripcion' => 'required|string',
            'precio' => 'required|numeric|min:1000',
            'stock' => 'required|integer|min:0',
            'idproveedor' => 'required|exists:proveedores,idproveedor',
        ]);

        $producto->update($validated);

        return redirect()->route('productos.index')->with('success', 'Producto actualizado correctamente.');
    }

    /**
     * No se permite eliminar productos para evitar daños a ventas.
     */
    public function destroy($id)
    {
        return redirect()->route('productos.index')
            ->with('error', '❌ No está permitido eliminar productos.');
    }

    public function topProductos()
    {
        $topProductos = Productos::orderByDesc('cantidad_vendida')->take(5)->get();
        return view('welcome', compact('topProductos'));
    }

    public function mostrarEnInicio()
    {
        $productos = Productos::take(5)->get();
        return view('welcome', compact('productos'));
    }
}
