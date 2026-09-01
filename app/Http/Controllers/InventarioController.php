<?php

namespace App\Http\Controllers;

use App\Models\Inventario;
use Illuminate\Http\Request;

class InventarioController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $insumos = Inventario::latest()->get();
        return view('inventario.index', compact('insumos'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('inventario.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nombre_insumo' => 'required|string|max:255',
            'stock'         => 'required|numeric|min:0',
            'unidad_medida' => 'required|string|max:50',
            'stock_minimo'  => 'required|numeric|min:0',
        ]);

        Inventario::create($request->all());

        return redirect()->route('inventario.index')->with('success', 'Insumo registrado en inventario.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Inventario $inventario)
    {
        return view('inventario.show', compact('inventario'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Inventario $inventario)
    {
        return view('inventario.edit', compact('inventario'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Inventario $inventario)
    {
        $request->validate([
            'nombre_insumo' => 'required|string|max:255',
            'stock'         => 'required|numeric|min:0',
            'unidad_medida' => 'required|string|max:50',
            'stock_minimo'  => 'required|numeric|min:0',
        ]);

        $inventario->update($request->all());

        return redirect()->route('inventario.index')->with('success', 'Insumo actualizado correctamente.');
    }

    /**
     * Registrar entradas o salidas de stock de un insumo.
     */
    public function actualizarStock(Request $request, Inventario $inventario)
    {
        $request->validate([
            'cantidad' => 'required|numeric|gt:0',
            'tipo'     => 'required|in:entrada,salida',
        ]);

        if ($request->tipo === 'entrada') {
            $inventario->increment('stock', $request->cantidad);
        } else {
            if ($inventario->stock < $request->cantidad) {
                return redirect()->back()->with('error', 'No hay suficiente stock disponible para esta salida.');
            }
            $inventario->decrement('stock', $request->cantidad);
        }

        return redirect()->route('inventario.index')->with('success', 'Stock ajustado con éxito.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Inventario $inventario)
    {
        $inventario->delete();

        return redirect()->route('inventario.index')->with('success', 'Insumo eliminado del inventario.');
    }
}