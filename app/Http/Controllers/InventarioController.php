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

        // Enviamos la variable $insumos explícitamente a la vista
        return view('inventario.index', compact('insumos'));
    }

    // ... los demás métodos se mantienen igual por ahora
}