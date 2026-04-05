<?php

namespace App\Http\Controllers;

use App\Models\CompraDetalle;
use App\Models\Compra;
use App\Models\productos;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CompraDetallesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $detalles = CompraDetalle::with('compra.proveedor', 'producto')
            ->orderBy('id', 'desc')
            ->get();
        return view('compra_detalles.index', compact('detalles'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $compras = Compra::with('proveedor')->get();
        $productos = productos::all();
        return view('compra_detalles.create', compact('compras', 'productos'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'idcompra' => 'required|exists:compras,id',
            'idproducto' => 'required|exists:productos,idproducto',
            'cantidad' => 'required|numeric|min:1',
            'precio_compra' => 'required|numeric|min:0',
            'precio_venta' => 'nullable|numeric|min:0',
        ]);

        DB::beginTransaction();

        try {
            $subtotal = $request->cantidad * $request->precio_compra;

            $detalle = CompraDetalle::create([
                'idcompra' => $request->idcompra,
                'idproducto' => $request->idproducto,
                'cantidad' => $request->cantidad,
                'precio_compra' => $request->precio_compra,
                'precio_venta' => $request->precio_venta,
                'subtotal' => $subtotal,
            ]);

            // Actualizar stock
            productos::where('idproducto', $request->idproducto)
                ->increment('stock', $request->cantidad);

            // Recalcular total de la compra
            $compra = Compra::find($request->idcompra);
            $compra->update([
                'total' => $compra->detalles()->sum('subtotal')
            ]);

            DB::commit();
            return redirect()->route('compra_detalles.index')
                ->with('success', 'Detalle de compra agregado correctamente');

        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()
                ->withInput()
                ->with('error', 'Error al agregar detalle: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(CompraDetalle $compra_detalles)
    {
        $compra_detalles->load('compra.proveedor', 'producto');
        return view('compra_detalles.show', compact('compra_detalles'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(CompraDetalle $compra_detalles)
    {
        $compras = Compra::all();
        $productos = productos::all();
        return view('compra_detalles.edit', compact('compra_detalles', 'compras', 'productos'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, CompraDetalle $compra_detalles)
    {
        $request->validate([
            'idcompra' => 'required|exists:compras,id',
            'idproducto' => 'required|exists:productos,idproducto',
            'cantidad' => 'required|numeric|min:1',
            'precio_compra' => 'required|numeric|min:0',
            'precio_venta' => 'nullable|numeric|min:0',
        ]);

        DB::beginTransaction();

        try {
            // Revertir stock anterior
            productos::where('idproducto', $compra_detalles->idproducto)
                ->decrement('stock', $compra_detalles->cantidad);

            $subtotal = $request->cantidad * $request->precio_compra;

            $compra_detalles->update([
                'idcompra' => $request->idcompra,
                'idproducto' => $request->idproducto,
                'cantidad' => $request->cantidad,
                'precio_compra' => $request->precio_compra,
                'precio_venta' => $request->precio_venta,
                'subtotal' => $subtotal,
            ]);

            // Actualizar stock nuevo
            productos::where('idproducto', $request->idproducto)
                ->increment('stock', $request->cantidad);

            // Recalcular total de las compras afectadas
            Compra::find($request->idcompra)->update([
                'total' => Compra::find($request->idcompra)->detalles()->sum('subtotal')
            ]);

            DB::commit();
            return redirect()->route('compra_detalles.index')
                ->with('success', 'Detalle de compra actualizado correctamente');

        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()
                ->withInput()
                ->with('error', 'Error al actualizar detalle: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CompraDetalle $compra_detalles)
    {
        DB::beginTransaction();

        try {
            $compra_id = $compra_detalles->idcompra;

            // Revertir stock
            productos::where('idproducto', $compra_detalles->idproducto)
                ->decrement('stock', $compra_detalles->cantidad);

            // Eliminar detalle
            $compra_detalles->delete();

            // Recalcular total de la compra
            $compra = Compra::find($compra_id);
            if ($compra) {
                $compra->update([
                    'total' => $compra->detalles()->sum('subtotal')
                ]);
            }

            DB::commit();
            return redirect()->route('compra_detalles.index')
                ->with('success', 'Detalle de compra eliminado correctamente');

        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()
                ->with('error', 'Error al eliminar detalle: ' . $e->getMessage());
        }
    }
}
