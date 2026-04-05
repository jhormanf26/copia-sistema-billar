<?php

namespace App\Http\Controllers;

use App\Models\mesas;
use Illuminate\Http\Request;

class MesasController extends Controller
{
    /**
     * Display a listing of the resource.
     */
public function index()
{
    $mesas = mesas::all();

    return view('mesas.index', compact('mesas'));
}



    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('mesas.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        mesas::create($request->all());
        return redirect()->route('mesas.index')->with('success', 'Mesa creada correctamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(mesas $mesas)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($idmesa)
    {
        $mesa = mesas::findOrFail($idmesa);
        return view('mesas.edit', compact('mesa'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $idmesa)
    {
        $mesa = mesas::findOrFail($idmesa);
        $mesa->update($request->all());
        return redirect()->route('mesas.index')->with('success', 'Mesa actualizada correctamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($idmesa)
    {
        $mesa = mesas::findOrFail($idmesa);
        $mesa->delete();
        return redirect()->route('mesas.index')->with('success', 'Mesa eliminada correctamente.');
    }

    public function updateEstado(Request $request, $idmesa)
{
    $request->validate([
        'estado' => 'required|in:libre,ocupada,reservada',
    ]);

    $mesa = mesas::findOrFail($idmesa);
    $mesa->estado = $request->estado;
    $mesa->save();

    return redirect()->back()->with('success', 'Estado de la mesa actualizado correctamente.');
}
public function startTimer($idmesa)
{
    $mesa = mesas::findOrFail($idmesa);
    $mesa->inicio_tiempo = now();
    $mesa->estado = 'ocupada';
    $mesa->save();

    return back()->with('success', 'Tiempo iniciado correctamente.');
}

public function stopTimer($idmesa)
{
    $mesa = mesas::findOrFail($idmesa);
    $mesa->fin_tiempo = now();
    $mesa->estado = 'libre';
    $mesa->save();

    return back()->with('success', 'Tiempo finalizado correctamente.');
}

}

