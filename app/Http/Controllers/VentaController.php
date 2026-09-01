<?php

namespace App\Http\Controllers;

use App\Models\Venta;
use Illuminate\Http\Request;

class VentaController extends Controller
{
    /**
     * Muestra el historial general de ventas del restaurante.
     */
    public function index()
    {
        $ventas = Venta::with(['pedido.cliente', 'pedido.mesa', 'pago'])
            ->latest('fecha')
            ->get();

        return view('ventas.index', compact('ventas'));
    }

    /**
     * Las ventas se generan automáticamente al procesar el Pago.
     * Este método redirige al formulario de pago para mantener el flujo del sistema.
     */
    public function create()
    {
        return redirect()->route('pagos.create')
            ->with('info', 'Para registrar una venta, procese el pago del pedido correspondiente.');
    }

    /**
     * Muestra la información detallada de una venta específica (Comprobante / Ticket).
     */
    public function show(Venta $venta)
    {
        $venta->load(['pedido.cliente', 'pedido.mesa', 'pedido.usuario', 'pedido.detalles.producto', 'pago']);
        return view('ventas.show', compact('venta'));
    }

    /**
     * Elimina un registro de venta (solo para administradores en caso de anulación).
     */
    public function destroy(Venta $venta)
    {
        $venta->delete();

        return redirect()->route('ventas.index')->with('success', 'Registro de venta eliminado correctamente.');
    }
}