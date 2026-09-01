<?php

namespace App\Http\Controllers;

use App\Models\Reporte;
use App\Models\Venta;
use App\Models\DetallePedido;
use App\Models\Inventario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReporteController extends Controller
{
    /**
     * Muestra la vista principal de reportes y analíticas.
     */
    public function index(Request $request)
    {
        // 1. Historial de reportes guardados
        $reportes = Reporte::with('usuario')->latest()->get();

        // 2. Cálculo de Ventas por Día (Últimos 7 días)
        $ventasPorDia = Venta::select(
            DB::raw('DATE(fecha) as fecha'),
            DB::raw('SUM(monto_total) as total')
        )
        ->groupBy('fecha')
        ->orderBy('fecha', 'desc')
        ->take(7)
        ->get();

        // 3. Top 5 Productos más vendidos
        $productosMasVendidos = DetallePedido::select('producto_id', DB::raw('SUM(cantidad) as total_vendido'))
            ->with('producto')
            ->groupBy('producto_id')
            ->orderBy('total_vendido', 'desc')
            ->take(5)
            ->get();

        // 4. Alertar insumos con stock bajo
        $insumosCriticos = Inventario::whereColumn('stock', '<=', 'stock_minimo')->get();

        return view('reportes.index', compact(
            'reportes',
            'ventasPorDia',
            'productosMasVendidos',
            'insumosCriticos'
        ));
    }

    /**
     * Muestra el formulario para generar un nuevo reporte.
     */
    public function create()
    {
        return view('reportes.create');
    }

    /**
     * Guarda el registro de un reporte procesado en la base de datos.
     */
    public function store(Request $request)
    {
        $request->validate([
            'titulo'       => 'required|string|max:255',
            'tipo'         => 'required|in:ventas,productos,inventario,pedidos',
            'fecha_inicio' => 'nullable|date',
            'fecha_fin'    => 'nullable|date|after_or_equal:fecha_inicio',
        ]);

        $montoTotal = 0;

        // Calcular el monto si el reporte es de ventas
        if ($request->tipo === 'ventas' && $request->fecha_inicio && $request->fecha_fin) {
            $montoTotal = Venta::whereBetween('fecha', [$request->fecha_inicio, $request->fecha_fin])
                ->sum('monto_total');
        }

        Reporte::create([
            'titulo'       => $request->titulo,
            'tipo'         => $request->tipo,
            'fecha_inicio' => $request->fecha_inicio,
            'fecha_fin'    => $request->fecha_fin,
            'monto_total'  => $montoTotal,
            'user_id'      => auth()->id(),
        ]);

        return redirect()->route('reportes.index')->with('success', 'Reporte generado correctamente.');
    }

    /**
     * Muestra un reporte específico.
     */
    public function show(Reporte $reporte)
    {
        $reporte->load('usuario');
        return view('reportes.show', compact('reporte'));
    }

    /**
     * Muestra el formulario para editar datos del reporte.
     */
    public function edit(Reporte $reporte)
    {
        return view('reportes.edit', compact('reporte'));
    }

    /**
     * Actualiza la información del reporte.
     */
    public function update(Request $request, Reporte $reporte)
    {
        $request->validate([
            'titulo' => 'required|string|max:255',
        ]);

        $reporte->update(['titulo' => $request->titulo]);

        return redirect()->route('reportes.index')->with('success', 'Reporte actualizado.');
    }

    /**
     * Elimina el reporte.
     */
    public function destroy(Reporte $reporte)
    {
        $reporte->delete();

        return redirect()->route('reportes.index')->with('success', 'Reporte eliminado.');
    }
}