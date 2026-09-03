<?php

namespace App\Http\Controllers;

use App\Models\DetallePedido;
use App\Models\Pedido;
use App\Models\Producto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DetallePedidoController extends Controller
{
    /**
     * Muestra todos los detalles (útil para consultas administrativas rápidas).
     */
   public function index()
{
    $detalles = DetallePedido::with(['pedido', 'producto'])->latest()->paginate(15);
    return view('detalle_pedidos.index', compact('detalles'));
}
    /**
     * Agrega un nuevo producto a un pedido existente.
     */
    public function store(Request $request)
    {
        $request->validate([
            'pedido_id'   => 'required|exists:pedidos,id',
            'producto_id' => 'required|exists:productos,id',
            'cantidad'    => 'required|integer|min:1',
        ]);

        DB::transaction(function () use ($request) {
            $producto = Producto::findOrFail($request->producto_id);
            $subtotal = $producto->precio * $request->cantidad;

            // Verificar si el producto ya existe en el pedido para sumar la cantidad
            $detalle = DetallePedido::where('pedido_id', $request->pedido_id)
                ->where('producto_id', $request->producto_id)
                ->first();

            if ($detalle) {
                $nuevaCantidad = $detalle->cantidad + $request->cantidad;
                $nuevoSubtotal = $producto->precio * $nuevaCantidad;

                $detalle->update([
                    'cantidad' => $nuevaCantidad,
                    'subtotal' => $nuevoSubtotal,
                ]);
            } else {
                DetallePedido::create([
                    'pedido_id'       => $request->pedido_id,
                    'producto_id'     => $producto->id,
                    'cantidad'        => $request->cantidad,
                    'precio_unitario' => $producto->precio,
                    'subtotal'        => $subtotal,
                ]);
            }

            // Recalcular el total general del pedido
            $pedido = Pedido::findOrFail($request->pedido_id);
            $pedido->update([
                'total' => $pedido->detalles()->sum('subtotal')
            ]);
        });

        return redirect()->route('pedidos.show', $request->pedido_id)->with('success', 'Producto agregado con éxito.');
    }

    /**
     * Muestra un detalle específico.
     */
    public function show(DetallePedido $detallePedido)
    {
        $detallePedido->load(['pedido', 'producto']);
        return view('detalle_pedidos.show', compact('detallePedido'));
    }

    /**
     * Actualiza la cantidad de un ítem dentro del pedido.
     */
    public function update(Request $request, DetallePedido $detallePedido)
    {
        $request->validate([
            'cantidad' => 'required|integer|min:1',
        ]);

        DB::transaction(function () use ($request, $detallePedido) {
            $nuevoSubtotal = $detallePedido->precio_unitario * $request->cantidad;

            $detallePedido->update([
                'cantidad' => $request->cantidad,
                'subtotal' => $nuevoSubtotal,
            ]);

            // Recalcular el total del pedido
            $pedido = $detallePedido->pedido;
            $pedido->update([
                'total' => $pedido->detalles()->sum('subtotal')
            ]);
        });

        return redirect()->back()->with('success', 'Cantidad del ítem actualizada.');
    }

    /**
     * Elimina un producto individual del pedido.
     */
    public function destroy(DetallePedido $detallePedido)
    {
        DB::transaction(function () use ($detallePedido) {
            $pedido = $detallePedido->pedido;
            
            $detallePedido->delete();

            // Recalcular el total del pedido tras la eliminación
            $pedido->update([
                'total' => $pedido->detalles()->sum('subtotal')
            ]);
        });

        return redirect()->back()->with('success', 'Ítem eliminado del pedido.');
    }
}