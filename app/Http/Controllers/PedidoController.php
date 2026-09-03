<?php

namespace App\Http\Controllers;

use App\Models\Pedido;
use App\Models\DetallePedido;
use App\Models\Producto;
use App\Models\Mesa;
use App\Models\Cliente;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PedidoController extends Controller
{
    public function index()
    {
        $pedidos = Pedido::with(['cliente', 'mesa', 'usuario', 'detalles.producto'])
            ->latest()
            ->get();

        return view('pedidos.index', compact('pedidos'));
    }

    public function create()
    {
        $productos = Producto::where('estado', true)->get();
        $mesas = Mesa::where('estado', 'libre')->get();
        $clientes = Cliente::all();

        return view('pedidos.create', compact('productos', 'mesas', 'clientes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'tipo'                 => 'required|in:local,recojo,delivery',
            'cliente_id'           => 'nullable|exists:clientes,id',
            'mesa_id'              => 'nullable|exists:mesas,id',
            'productos'            => 'required|array|min:1',
            'productos.*.id'       => 'required|exists:productos,id',
            'productos.*.cantidad' => 'required|integer|min:1',
        ]);

        DB::transaction(function () use ($request) {
            $total = 0;

            $pedido = Pedido::create([
                'user_id'    => auth()->id(),
                'cliente_id' => $request->cliente_id,
                'mesa_id'    => $request->tipo === 'local' ? $request->mesa_id : null,
                'tipo'       => $request->tipo,
                'estado'     => 'pendiente',
                'total'      => 0,
            ]);

            foreach ($request->productos as $item) {
                $producto = Producto::findOrFail($item['id']);
                $subtotal = $producto->precio * $item['cantidad'];
                $total += $subtotal;

                DetallePedido::create([
                    'pedido_id'       => $pedido->id,
                    'producto_id'     => $producto->id,
                    'cantidad'        => $item['cantidad'],
                    'precio_unitario' => $producto->precio,
                    'subtotal'        => $subtotal,
                ]);
            }

            $pedido->update(['total' => $total]);

            // Si es pedido en local, marcamos la mesa como ocupada
            if ($request->tipo === 'local' && $request->mesa_id) {
                Mesa::where('id', $request->mesa_id)->update(['estado' => 'ocupada']);
            }
        });

        return redirect()->route('pedidos.index')->with('success', 'Pedido creado exitosamente.');
    }

   public function show(Pedido $pedido)
{
    $pedido->load(['cliente', 'mesa', 'usuario', 'detalles.producto']);
    $productos = \App\Models\Producto::where('estado', true)->get();

    return view('pedidos.show', compact('pedido', 'productos'));
}

    public function cambiarEstado(Request $request, Pedido $pedido)
    {
        $request->validate([
            'estado' => 'required|in:pendiente,en_proceso,completado,cancelado',
        ]);

        $pedido->update(['estado' => $request->estado]);

        // Si se cancela el pedido y tenía mesa asignada, liberamos la mesa
        if ($request->estado === 'cancelado' && $pedido->mesa_id) {
            $pedido->mesa->update(['estado' => 'libre']);
        }

        return redirect()->back()->with('success', 'Estado del pedido actualizado.');
    }

    public function destroy(Pedido $pedido)
    {
        if ($pedido->mesa_id) {
            $pedido->mesa->update(['estado' => 'libre']);
        }

        $pedido->delete();

        return redirect()->route('pedidos.index')->with('success', 'Pedido eliminado correctamente.');
    }
}