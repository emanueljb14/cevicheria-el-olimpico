<?php

namespace App\Http\Controllers;

use App\Models\Mesa;
use Illuminate\Http\Request;

class MesaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $mesas = Mesa::orderBy('numero', 'asc')->get();
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
        $request->validate([
            'numero'    => 'required|string|unique:mesas,numero|max:50',
            'capacidad' => 'required|integer|min:1',
            'estado'    => 'required|in:libre,ocupada,reservada',
        ]);

        Mesa::create($request->all());

        return redirect()->route('mesas.index')->with('success', 'Mesa agregada correctamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Mesa $mesa)
    {
        return view('mesas.show', compact('mesa'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Mesa $mesa)
    {
        return view('mesas.edit', compact('mesa'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Mesa $mesa)
    {
        $request->validate([
            'numero'    => 'required|string|max:50|unique:mesas,numero,' . $mesa->id,
            'capacidad' => 'required|integer|min:1',
            'estado'    => 'required|in:libre,ocupada,reservada',
        ]);

        $mesa->update($request->all());

        return redirect()->route('mesas.index')->with('success', 'Mesa actualizada correctamente.');
    }

    /**
     * Cambiar rápidamente el estado de la mesa (útil para el mapa visual de salón).
     */
    public function cambiarEstado(Request $request, Mesa $mesa)
    {
        $request->validate([
            'estado' => 'required|in:libre,ocupada,reservada',
        ]);

        $mesa->update(['estado' => $request->estado]);

        return redirect()->back()->with('success', 'Estado de la mesa actualizado.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Mesa $mesa)
    {
        $mesa->delete();

        return redirect()->route('mesas.index')->with('success', 'Mesa eliminada correctamente.');
    }
}