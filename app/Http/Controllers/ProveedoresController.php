<?php

namespace App\Http\Controllers;

use App\Models\Proveedor;
use Illuminate\Http\Request;


class ProveedoresController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $proveedores = Proveedor::all();
         $totalStock = \DB::table('productos')->sum('stock');

    // lista de proveedores
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

        Proveedor::create($validatedData);
        return redirect()->route('proveedores.index')->with('success', 'Proveedor creado correctamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Proveedor $proveedores)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($idproveedor)
    {
        $proveedor = Proveedor::findOrFail($idproveedor);
        return view('proveedores.edit', compact('proveedor'));

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $idproveedor)
    {
        $proveedor = Proveedor::findOrFail($idproveedor);
        
        $validatedData = $request->validate([
            'nombre' => 'required|string|regex:/^[a-zA-Z\s]+$/|max:100',
            'contacto' => 'required|numeric|digits_between:7,15',
            'direccion' => 'required|string|max:255',
        ]);

        $proveedor->update($validatedData);

        return redirect()->route('proveedores.index')->with('success', 'Proveedor actualizado correctamente.');
    }
}
