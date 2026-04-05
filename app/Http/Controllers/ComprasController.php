<?php

namespace App\Http\Controllers;

use App\Models\Compra;
use App\Models\CompraDetalle;
use App\Models\Proveedores;
use App\Models\productos;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ComprasController extends Controller
{
    public function index()
    {
        $compras = Compra::with('proveedor', 'detalles.producto')
            ->orderBy('id', 'desc')
            ->get();
        return view('compras.index', compact('compras'));
    }

    public function create()
    {
        $proveedores = Proveedores::where('nombre', '!=', 'Tiempos Mesas')->get();
        return view('compras.create', compact('proveedores'));
    }

    public function getProductosProveedor($id)
    {
        return productos::where('idproveedor', $id)
            ->select('idproducto', 'nombre', 'precio', 'stock')
            ->get()
            ->toJson();
    }

    public function store(Request $request)
    {
        // Decodificar productos si viene como JSON string
        $productos = $request->productos;
        if (is_string($productos)) {
            $productos = json_decode($productos, true);
        }

        // Validar manualmente ya que puede venir como string
        if (empty($productos) || !is_array($productos)) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Debes agregar al menos un producto');
        }

        $request->validate([
            'idproveedor' => 'required|exists:proveedores,idproveedor',
        ]);

        DB::beginTransaction();

        try {
            $total = 0;

            // Calcular total
            foreach ($productos as $item) {
                $subtotal = $item['cantidad'] * $item['precio_compra'];
                $total += $subtotal;
            }

            // Crear compra
            $compra = Compra::create([
                'idproveedor' => $request->idproveedor,
                'fecha_compra' => now(),
                'total' => $total
            ]);

            // Crear detalles y actualizar stock
            foreach ($productos as $item) {
                $subtotal = $item['cantidad'] * $item['precio_compra'];

                CompraDetalle::create([
                    'idcompra' => $compra->id,
                    'idproducto' => $item['idproducto'],
                    'cantidad' => $item['cantidad'],
                    'precio_compra' => $item['precio_compra'],
                    'precio_venta' => $item['precio_venta'] ?? null,
                    'subtotal' => $subtotal,
                ]);

                // Actualizar stock del producto
                productos::where('idproducto', $item['idproducto'])
                    ->increment('stock', $item['cantidad']);
            }

            DB::commit();
            return redirect()->route('compras.index')
                ->with('success', 'Compra registrada correctamente');

        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()
                ->withInput()
                ->with('error', 'Error al registrar la compra: ' . $e->getMessage());
        }
    }

    public function show(Compra $compra)
    {
        $compra->load('proveedor', 'detalles.producto');
        return view('compras.show', compact('compra'));
    }

    public function edit(Compra $compra)
    {
        $proveedores = Proveedores::where('nombre', '!=', 'Tiempos Mesas')->get();
        $compra->load('detalles');
        return view('compras.edit', compact('compra', 'proveedores'));
    }

    public function update(Request $request, Compra $compra)
    {
        $request->validate([
            'idproveedor' => 'required|exists:proveedores,idproveedor',
        ]);

        try {
            $compra->update([
                'idproveedor' => $request->idproveedor,
            ]);

            return redirect()->route('compras.index')
                ->with('success', 'Compra actualizada correctamente');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error al actualizar la compra: ' . $e->getMessage());
        }
    }

    public function destroy(Compra $compra)
    {
        try {
            DB::beginTransaction();

            // Revertir stock de productos
            foreach ($compra->detalles as $detalle) {
                productos::where('idproducto', $detalle->idproducto)
                    ->decrement('stock', $detalle->cantidad);
            }

            // Eliminar detalles (en cascada)
            $compra->detalles()->delete();

            // Eliminar compra
            $compra->delete();

            DB::commit();
            return redirect()->route('compras.index')
                ->with('success', 'Compra eliminada correctamente');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()
                ->with('error', 'Error al eliminar la compra: ' . $e->getMessage());
        }
    }
}
