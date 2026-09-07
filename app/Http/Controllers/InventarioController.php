<?php

namespace App\Http\Controllers;

use App\Models\Inventario;
use App\Models\Producto;
use Illuminate\Http\Request;

class InventarioController extends Controller
{
    public function index()
    {
        $insumos = Inventario::with('producto')->latest()->get();

        return view('inventario.index', compact('insumos'));
    }

    public function create()
    {
        $productos = Producto::all();

        return view('inventario.create', compact('productos'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre'        => 'required|string|max:150',
            'unidad_medida' => 'required|string|max:50',
            'stock_actual'  => 'required|numeric|min:0',
            'stock_minimo'  => 'required|numeric|min:0',
            'producto_id'   => 'nullable|exists:productos,id',
        ]);

        Inventario::create([
            'nombre_insumo' => $request->nombre,
            'unidad_medida' => $request->unidad_medida,
            'stock'         => $request->stock_actual,
            'stock_minimo'  => $request->stock_minimo,
            'producto_id'   => $request->producto_id,
            'estado'        => true,
        ]);

        return redirect()->route('inventario.index')->with('success', 'Insumo registrado correctamente.');
    }

    public function show(Inventario $inventario)
    {
        $inventario->load('producto');

        return view('inventario.show', compact('inventario'));
    }

    public function edit(Inventario $inventario)
    {
        $productos = Producto::all();

        return view('inventario.edit', compact('inventario', 'productos'));
    }

    public function update(Request $request, Inventario $inventario)
    {
        $request->validate([
            'nombre'        => 'required|string|max:150',
            'unidad_medida' => 'required|string|max:50',
            'stock_actual'  => 'required|numeric|min:0',
            'stock_minimo'  => 'required|numeric|min:0',
            'producto_id'   => 'nullable|exists:productos,id',
        ]);

        $inventario->update([
            'nombre_insumo' => $request->nombre,
            'unidad_medida' => $request->unidad_medida,
            'stock'         => $request->stock_actual,
            'stock_minimo'  => $request->stock_minimo,
            'producto_id'   => $request->producto_id,
        ]);

        return redirect()->route('inventario.index')->with('success', 'Insumo actualizado correctamente.');
    }

    public function destroy(Inventario $inventario)
    {
        $inventario->delete();

        return redirect()->route('inventario.index')->with('success', 'Insumo eliminado correctamente.');
    }

    public function actualizarStock(Request $request, Inventario $inventario)
    {
        $request->validate([
            'stock' => 'required|numeric|min:0',
        ]);

        $inventario->update([
            'stock' => $request->stock,
        ]);

        return redirect()->route('inventario.index')->with('success', 'Stock actualizado rápidamente.');
    }
}