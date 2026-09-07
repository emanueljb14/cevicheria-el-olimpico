<?php

namespace App\Http\Controllers;

use App\Models\CierreCaja;
use App\Models\Venta;
use App\Models\DetallePedido;
use Illuminate\Http\Request;
use Exception;

class CierreCajaController extends Controller
{
    /**
     * Muestra la lista de cierres de caja y el resumen del día.
     */
    public function index()
    {
        $arqueos = CierreCaja::with('usuario')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        // Obtener el total del día desde los detalles de los pedidos/ventas del día
        $totalVentasHoy = $this->obtenerTotalVentasHoy();

        return view('cajas.index', compact('arqueos', 'totalVentasHoy'));
    }

    /**
     * Muestra el formulario de creación.
     */
    public function create()
    {
        return view('cajas.create');
    }

    /**
     * Guarda el registro del arqueo/cierre de caja.
     */
    public function store(Request $request)
    {
        $request->validate([
            'efectivo_fisico' => 'required|numeric|min:0',
            'digital_fisico'  => 'required|numeric|min:0',
            'observacion'     => 'nullable|string|max:500',
        ]);

        try {
            // Total calculado por el sistema para hoy
            $totalSistema = $this->obtenerTotalVentasHoy();

            $efectivo = $request->input('efectivo_fisico');
            $digital = $request->input('digital_fisico');
            $totalContado = $efectivo + $digital;

            // Diferencia: > 0 (Sobrante), < 0 (Faltante)
            $diferencia = $totalContado - $totalSistema;

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
     * Muestra el detalle de un cierre.
     */
    public function show(CierreCaja $cierreCaja)
    {
        return view('cajas.show', compact('cierreCaja'));
    }

    /**
     * Muestra el formulario de edición.
     */
    public function edit(CierreCaja $cierreCaja)
    {
        return view('cajas.edit', compact('cierreCaja'));
    }

    /**
     * Actualiza las observaciones del cierre.
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
     * Elimina un registro de cierre.
     */
    public function destroy(CierreCaja $cierreCaja)
    {
        $cierreCaja->delete();

        return redirect()->route('cierre-caja.index')->with('success', 'Registro eliminado correctamente.');
    }

    /**
     * Función privada para calcular las ventas de hoy evitando el error de columna.
     */
    private function obtenerTotalVentasHoy()
    {
        // 1. Intenta calcular desde los detalles de los pedidos registrados hoy
        if (class_exists('\App\Models\DetallePedido')) {
            return DetallePedido::whereHas('pedido', function($query) {
                $query->whereDate('created_at', today());
            })->sum('subtotal');
        }

        // 2. Si existe la tabla Venta, consulta directamente
        if (class_exists('\App\Models\Venta')) {
            return Venta::whereDate('created_at', today())->sum('subtotal');
        }

        return 0;
    }
}