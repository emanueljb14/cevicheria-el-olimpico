<?php

namespace App\Http\Controllers;

use App\Models\CierreCaja;
use App\Models\Venta; // Modelo de ventas
use Illuminate\Http\Request;
use Exception;

class CierreCajaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $arqueos = CierreCaja::with('usuario')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        // Calcular ventas registradas en el día de hoy
        $totalVentasHoy = class_exists('\App\Models\Venta')
            ? Venta::whereDate('created_at', today())->sum('total')
            : 0;

        return view('caja.index', compact('arqueos', 'totalVentasHoy'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('caja.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'efectivo_fisico' => 'required|numeric|min:0',
            'digital_fisico'  => 'required|numeric|min:0',
            'observacion'     => 'nullable|string|max:500',
        ]);

        try {
            // 1. Obtener total registrado en el sistema para el día de hoy
            $totalSistema = class_exists('\App\Models\Venta')
                ? Venta::whereDate('created_at', today())->sum('total')
                : 0;

            $efectivo = $request->input('efectivo_fisico');
            $digital = $request->input('digital_fisico');
            $totalContado = $efectivo + $digital;

            // 2. Calcular diferencia: > 0 (Sobrante), < 0 (Faltante)
            $diferencia = $totalContado - $totalSistema;

            // 3. Crear el registro
            CierreCaja::create([
                'user_id'         => auth()->id(),
                'monto_apertura'  => 0,
                'efectivo_fisico' => $efectivo,
                'digital_fisico'  => $digital,
                'total_sistema'   => $totalSistema,
                'diferencia'      => $diferencia,
                'estado'          => 'cerrado',
                'observacion'     => $request->input('observacion'),
            ]);

            return redirect()->back()->with('success', '¡Cierre de caja guardado con éxito!');

        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Error al procesar el cierre: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(CierreCaja $cierreCaja)
    {
        return view('caja.show', compact('cierreCaja'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(CierreCaja $cierreCaja)
    {
        return view('caja.edit', compact('cierreCaja'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, CierreCaja $cierreCaja)
    {
        $request->validate([
            'observacion' => 'nullable|string|max:500',
        ]);

        $cierreCaja->update($request->only('observacion'));

        return redirect()->route('cierre-caja.index')->with('success', 'Registro actualizado correctamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CierreCaja $cierreCaja)
    {
        $cierreCaja->delete();

        return redirect()->route('cierre-caja.index')->with('success', 'Registro eliminado correctamente.');
    }
}