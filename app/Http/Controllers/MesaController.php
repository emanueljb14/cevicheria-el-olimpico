<?php

namespace App\Http\Controllers;

use App\Models\Mesa;
use Illuminate\Http\Request;

class MesaController extends Controller
{
    /**
     * Muestra la lista de mesas y métricas.
     */
    public function index()
    {
        $mesas = Mesa::latest()->get();
        
        $totalMesas = $mesas->count();
        $disponiblesCount = $mesas->where('estado', 'libre')->count();
        $ocupadasCount = $mesas->whereIn('estado', ['ocupada', 'reservada'])->count();

        return view('mesas.index', compact('mesas', 'totalMesas', 'disponiblesCount', 'ocupadasCount'));
    }

    /**
     * Formulario para crear una nueva mesa.
     */
    public function create()
    {
        return view('mesas.create');
    }

    /**
     * Guarda una nueva mesa en la base de datos.
     */
    public function store(Request $request)
    {
        $request->validate([
            'numero'    => 'required|string|unique:mesas,numero|max:50',
            'capacidad' => 'required|integer|min:1',
            'estado'    => 'required|in:libre,ocupada,reservada',
        ]);

        Mesa::create($request->only(['numero', 'capacidad', 'estado']));

        return redirect()->route('mesas.index')->with('success', 'Mesa agregada correctamente.');
    }

    /**
     * Muestra el detalle de una mesa.
     */
    public function show($id)
    {
        $mesa = Mesa::findOrFail($id);
        return view('mesas.show', compact('mesa'));
    }

    /**
     * Formulario para editar una mesa existente.
     */
    public function edit($id)
    {
        $mesa = Mesa::findOrFail($id);
        return view('mesas.edit', compact('mesa'));
    }

    /**
     * Actualiza la mesa en la base de datos.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'numero'    => 'required|string|max:50|unique:mesas,numero,'.$id,
            'capacidad' => 'required|integer|min:1',
            'estado'    => 'required|in:libre,ocupada,reservada',
        ]);

        $mesa = Mesa::findOrFail($id);
        $mesa->update($request->only(['numero', 'capacidad', 'estado']));

        return redirect()->route('mesas.index')
            ->with('success', 'Mesa actualizada con éxito.');
    }

    /**
     * Elimina una mesa de la base de datos.
     */
    public function destroy($id)
    {
        $mesa = Mesa::findOrFail($id);
        $mesa->delete();

        return redirect()->route('mesas.index')
            ->with('success', 'Mesa eliminada con éxito.');
    }

    /**
     * Método para cambiar estado rápido de la mesa.
     */
    public function cambiarEstado(Request $request, Mesa $mesa)
    {
        $validated = $request->validate([
            'estado' => 'required|in:libre,ocupada,reservada',
        ]);

        $mesa->update([
            'estado' => $validated['estado']
        ]);

        return redirect()->back()->with('success', 'El estado de la mesa se actualizó correctamente.');
    }
}