<?php

namespace App\Http\Controllers;

use App\Models\proveedores;
use Illuminate\Http\Request;


class ProveedoresController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $proveedores = proveedores::all();
         $totalStock = \DB::table('productos')->sum('stock');

    // lista de proveedores
    $proveedores = Proveedores::all();
        return view('proveedores.index', compact('proveedores','totalStock'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('proveedores.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'idproveedor' => 'required|unique:proveedores,idproveedor|numeric',
            'nombre' => 'required|string|regex:/^[a-zA-Z\s]+$/|max:100',
            'contacto' => 'required|numeric|digits_between:7,15',
            'direccion' => 'required|string|max:255',
        ]);

        proveedores::create($validatedData);
        return redirect()->route('proveedores.index')->with('success', 'Proveedor creado correctamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(proveedores $proveedores)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($idproveedor)
    {
        $proveedor = proveedores::findOrFail($idproveedor);
        return view('proveedores.edit', compact('proveedor'));

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $idproveedor)
    {
        $proveedor = proveedores::findOrFail($idproveedor);
        
        $validatedData = $request->validate([
            'nombre' => 'required|string|regex:/^[a-zA-Z\s]+$/|max:100',
            'contacto' => 'required|numeric|digits_between:7,15',
            'direccion' => 'required|string|max:255',
        ]);

        $proveedor->update($validatedData);

        return redirect()->route('proveedores.index')->with('success', 'Proveedor actualizado correctamente.');
    }
}
