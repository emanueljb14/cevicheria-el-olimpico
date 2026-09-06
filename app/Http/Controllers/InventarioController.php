<?php

namespace App\Http\Controllers;

use App\Models\Inventario;
use Illuminate\Http\Request;

class InventarioController extends Controller
{
    public function index()
    {
        // Traemos todos los insumos junto con su relación de producto si existe
        $insumos = Inventario::with('producto')->latest()->get();

        return view('inventario.index', compact('insumos'));
    }

    public function create()
    {
        return view('inventario.create');
    }

    public function store(Request $request)
{
    $request->validate([
        'nombre'        => 'required|string|max:150',
        'unidad_medida' => 'required|string|max:50',
        'stock_actual'  => 'required|numeric|min:0',
        'stock_minimo'  => 'required|numeric|min:0',
    ]);

    Inventario::create([
        'nombre_insumo' => $request->nombre,
        'unidad_medida' => $request->unidad_medida,
        'stock'         => $request->stock_actual,
        'stock_minimo'  => $request->stock_minimo,
        'estado'        => true,
    ]);

    return redirect()->route('inventario.index')->with('success', 'Insumo registrado correctamente.');
}
}