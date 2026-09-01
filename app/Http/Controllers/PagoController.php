<?php

namespace App\Http\Controllers;

use App\Models\Pago;
use App\Models\Pedido;
use App\Models\Venta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PagoController extends Controller
{
    /**
     * Muestra el historial de pagos registrados.
     */
    public function index()
    {
        $pagos = Pago::with(['pedido.cliente', 'pedido.mesa'])->latest()->get();
        return view('pagos.index', compact('pagos'));
    }

    /**
     * Muestra el formulario para procesar el pago de un pedido.
     */
    public function create()
    {
        $pedidosPendientes = Pedido::whereIn('estado', ['pendiente', 'en_proceso'])->get();
        return view('pagos.create', compact('pedidosPendientes'));
    }

    /**
     * Registra el pago, cambia el estado del pedido, libera la mesa y crea la venta.
     */
    public function store(Request $request)
    {
        $request->validate([
            'pedido_id' => 'required|exists:pedidos,id',
            'metodo'    => 'required|in:efectivo,tarjeta,yape,plin',
        ]);

        DB::transaction(function () use ($request) {
            $pedido = Pedido::findOrFail($request->pedido_id);

            // 1. Crear el pago
            $pago = Pago::create([
                'pedido_id' => $pedido->id,
                'metodo'    => $request->metodo,
                'monto'     => $pedido->total,
            ]);

            // 2. Registrar la venta correspondiente
            Venta::create([
                'pedido_id'   => $pedido->id,
                'pago_id'     => $pago->id,
                'monto_total' => $pedido->total,
                'fecha'       => now(),
            ]);

            // 3. Actualizar estado del pedido
            $pedido->update(['estado' => 'completado']);

            // 4. Liberar mesa si aplica
            if ($pedido->mesa) {
                $pedido->mesa->update(['estado' => 'libre']);
            }
        });

        return redirect()->route('pagos.index')->with('success', 'Pago procesado exitosamente.');
    }

    /**
     * Muestra el detalle de un pago específico.
     */
    public function show(Pago $pago)
    {
        $pago->load(['pedido.cliente', 'pedido.detalles.producto', 'venta']);
        return view('pagos.show', compact('pago'));
    }

    /**
     * Formulario de edición (no aplica cambios de monto si el pago ya fue procesado).
     */
    public function edit(Pago $pago)
    {
        return view('pagos.edit', compact('pago'));
    }

    /**
     * Actualiza el método de pago utilizado.
     */
    public function update(Request $request, Pago $pago)
    {
        $request->validate([
            'metodo' => 'required|in:efectivo,tarjeta,yape,plin',
        ]);

        $pago->update(['metodo' => $request->metodo]);

        return redirect()->route('pagos.index')->with('success', 'Método de pago actualizado.');
    }

    /**
     * Anula el pago y revierte el estado del pedido a pendiente.
     */
    public function destroy(Pago $pago)
    {
        DB::transaction(function () use ($pago) {
            if ($pago->pedido) {
                $pago->pedido->update(['estado' => 'pendiente']);
            }
            $pago->delete();
        });

        return redirect()->route('pagos.index')->with('success', 'Pago anulado correctamente.');
    }
}