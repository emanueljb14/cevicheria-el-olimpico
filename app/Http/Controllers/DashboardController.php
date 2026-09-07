<?php

namespace App\Http\Controllers;

use App\Models\Inventario;
use App\Models\Mesa;
use App\Models\Pedido;
use App\Models\Venta;
use App\Models\DetallePedido;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function __invoke(Request $request)
    {
        // 1. Total Ventas de Hoy
        // Utiliza el campo 'monto_total' de la tabla 'ventas' según tu migración
        $totalVentasHoy = Venta::whereDate('fecha', now()->today())->sum('monto_total');

        // 2. Pedidos Activos
        // Estados basados en tu migración: 'pendiente' y 'en_proceso'
        $pedidosActivos = Pedido::whereIn('estado', ['pendiente', 'en_proceso'])->count();

        // 3. Mesas Ocupadas
        // Estado 'ocupada' según tu migración de mesas
        $mesasOcupadas = Mesa::where('estado', 'ocupada')->count();

        // 4. Stock Crítico de Insumos
        // Insumos donde 'stock' <= 'stock_minimo'
        $insumosCriticos = Inventario::whereColumn('stock', '<=', 'stock_minimo')->count();

        // 5. Últimos 5 pedidos
        $ultimosPedidos = Pedido::with('mesa')->latest()->take(5)->get();

        // 6. Ventas de la Semana (Últimos 7 días)
        $dias = [];
        $montos = [];

        for ($i = 6; $i >= 0; $i--) {
            $fecha = now()->subDays($i);
            $dias[] = ucfirst($fecha->locale('es')->minDayName);
            
            $totalDia = Venta::whereDate('fecha', $fecha->format('Y-m-d'))->sum('monto_total');
            $montos[] = (float) $totalDia;
        }

        $graficoVentas = [
            'dias'   => $dias,
            'montos' => $montos,
        ];

        // 7. Top 5 Productos más vendidos
        $topProductos = DetallePedido::select('producto_id', DB::raw('SUM(cantidad) as total_cantidad'))
            ->with('producto')
            ->groupBy('producto_id')
            ->orderByDesc('total_cantidad')
            ->take(5)
            ->get();

        $graficoTopProductos = [
            'nombres'    => $topProductos->pluck('producto.nombre')->filter()->values()->toArray(),
            'cantidades' => $topProductos->pluck('total_cantidad')->map(fn($v) => (float)$v)->toArray(),
        ];

        return view('dashboard', compact(
            'totalVentasHoy',
            'pedidosActivos',
            'mesasOcupadas',
            'insumosCriticos',
            'ultimosPedidos',
            'graficoVentas',
            'graficoTopProductos'
        ));
    }
}