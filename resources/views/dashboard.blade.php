@extends('layouts.app')

@section('title', 'Dashboard')
@section('page-title', 'Panel General')

@push('styles')
<style>
    .dashboard-page {
        --navy: #062b3d;
        --sea: #00a7a7;
        --sea-dark: #008b8b;
        --gold: #f6c453;
        --paper: #ffffff;
        --muted: #68757d;
        --line: #dfe9eb;
        --bg-subtle: #f4f8f9;
        color: #18242b;
    }

    .dash-welcome {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 24px;
        flex-wrap: wrap;
        gap: 16px;
    }
    .dash-welcome h1 {
        font: 700 clamp(22px, 2.5vw, 30px)/1.2 'Playfair Display', serif;
        color: var(--navy);
        margin: 0;
    }
    .dash-welcome p {
        margin: 4px 0 0;
        color: var(--muted);
        font-size: 14px;
    }
    .badge-date {
        background: var(--paper);
        border: 1px solid var(--line);
        padding: 8px 16px;
        border-radius: 999px;
        font-size: 13px;
        font-weight: 700;
        color: var(--navy);
        box-shadow: 0 2px 8px rgba(6,43,61,.04);
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }

    .kpi-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(230px, 1fr));
        gap: 18px;
        margin-bottom: 28px;
    }
    .kpi-card {
        background: var(--paper);
        border: 1px solid var(--line);
        border-radius: 18px;
        padding: 20px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        box-shadow: 0 10px 25px rgba(6,43,61,.04);
        transition: transform .2s, box-shadow .2s;
    }
    .kpi-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 14px 30px rgba(6,43,61,.08);
    }
    .kpi-info span {
        font-size: 11px;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 0.06em;
        color: var(--muted);
        display: block;
    }
    .kpi-info h3 {
        font-size: 26px;
        font-weight: 800;
        color: var(--navy);
        margin: 6px 0 0;
        letter-spacing: -0.02em;
    }
    .kpi-icon {
        width: 52px;
        height: 52px;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 22px;
        flex-shrink: 0;
    }

    .icon-sea { background: #e7f7f7; color: var(--sea); }
    .icon-gold { background: #fef7e7; color: #d99a00; }
    .icon-navy { background: #e8ecef; color: var(--navy); }
    .icon-danger { background: #fde8e8; color: #e53e3e; }

    .dash-main-grid {
        display: grid;
        grid-template-columns: 2fr 1fr;
        gap: 22px;
        margin-bottom: 28px;
    }
    .dash-secondary-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 22px;
    }

    .dash-card {
        background: var(--paper);
        border: 1px solid var(--line);
        border-radius: 20px;
        padding: 24px;
        box-shadow: 0 10px 28px rgba(6,43,61,.04);
    }
    .dash-card-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
    }
    .dash-card-title {
        font: 700 18px 'Playfair Display', serif;
        color: var(--navy);
        margin: 0;
    }
    .card-link {
        font-size: 12px;
        font-weight: 700;
        color: var(--sea);
        text-decoration: none;
        transition: color .2s;
    }
    .card-link:hover {
        color: var(--sea-dark);
        text-decoration: underline;
    }

    .chart-container {
        position: relative;
        width: 100%;
        height: 280px;
    }

    .quick-actions {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 12px;
    }
    .action-btn {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        padding: 18px 12px;
        background: var(--bg-subtle);
        border: 1px solid var(--line);
        border-radius: 14px;
        text-decoration: none;
        color: var(--navy);
        font-weight: 700;
        font-size: 12px;
        gap: 10px;
        transition: all .2s;
    }
    .action-btn i {
        font-size: 22px;
        color: var(--sea);
        transition: transform .2s;
    }
    .action-btn:hover {
        background: #e7f7f7;
        border-color: var(--sea);
        color: var(--sea-dark);
        transform: translateY(-2px);
    }
    .action-btn:hover i {
        transform: scale(1.15);
    }

    .dash-table {
        width: 100%;
        border-collapse: collapse;
    }
    .dash-table th {
        text-align: left;
        font-size: 11px;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        color: var(--muted);
        padding-bottom: 12px;
        border-bottom: 1px solid var(--line);
    }
    .dash-table td {
        padding: 14px 0;
        border-bottom: 1px solid #f2f6f7;
        font-size: 13px;
        color: var(--navy);
    }
    .dash-table tr:last-child td {
        border-bottom: none;
    }

    .badge-status {
        padding: 4px 10px;
        border-radius: 999px;
        font-size: 10px;
        font-weight: 800;
        letter-spacing: .03em;
        display: inline-block;
    }
    .status-pendiente { background: #fef7e7; color: #b78103; }
    .status-en_proceso { background: #e7f7f7; color: #008b8b; }
    .status-completado { background: #effdf8; color: #087451; }
    .status-cancelado { background: #fdf2f2; color: #a32a2a; }

    @media(max-width: 1024px) {
        .dash-main-grid, .dash-secondary-grid {
            grid-template-columns: 1fr;
        }
    }
</style>
@endpush

@section('content')
<div class="dashboard-page">

    <div class="dash-welcome">
        <div>
            <h1>¡Hola, {{ auth()->user()->name ?? 'Administrador' }}! 👋</h1>
            <p>Resumen general del estado de operaciones de hoy.</p>
        </div>
        <div class="badge-date">
            <i class="fa-regular fa-calendar-days" style="color:var(--sea);"></i>
            <span>{{ now()->locale('es')->isoFormat('D [de] MMMM, YYYY') }}</span>
        </div>
    </div>

    <div class="kpi-grid">
        <div class="kpi-card">
            <div class="kpi-info">
                <span>Ventas de Hoy</span>
                <h3>S/ {{ number_format($totalVentasHoy ?? 0, 2) }}</h3>
            </div>
            <div class="kpi-icon icon-sea"><i class="fa-solid fa-cash-register"></i></div>
        </div>
        <div class="kpi-card">
            <div class="kpi-info">
                <span>Pedidos Activos</span>
                <h3>{{ $pedidosActivos ?? 0 }}</h3>
            </div>
            <div class="kpi-icon icon-gold"><i class="fa-solid fa-utensils"></i></div>
        </div>
        <div class="kpi-card">
            <div class="kpi-info">
                <span>Mesas Ocupadas</span>
                <h3>{{ $mesasOcupadas ?? 0 }}</h3>
            </div>
            <div class="kpi-icon icon-navy"><i class="fa-solid fa-chair"></i></div>
        </div>
        <div class="kpi-card">
            <div class="kpi-info">
                <span>Stock Crítico</span>
                <h3>{{ $insumosCriticos ?? 0 }}</h3>
            </div>
            <div class="kpi-icon icon-danger"><i class="fa-solid fa-triangle-exclamation"></i></div>
        </div>
    </div>

    <div class="dash-main-grid">
        <div class="dash-card">
            <div class="dash-card-header">
                <h2 class="dash-card-title">Ventas de la Semana</h2>
                <span style="font-size: 12px; color: var(--muted); font-weight: 600;">Últimos 7 días</span>
            </div>
            <div class="chart-container">
                <canvas id="salesChart"></canvas>
            </div>
        </div>

        <div class="dash-card">
            <div class="dash-card-header">
                <h2 class="dash-card-title">Acciones Rápidas</h2>
            </div>
            <div class="quick-actions">
                <a href="{{ route('pedidos.create') }}" class="action-btn">
                    <i class="fa-solid fa-cart-plus"></i>
                    <span>Nuevo Pedido</span>
                </a>
                <a href="{{ route('clientes.create') }}" class="action-btn">
                    <i class="fa-solid fa-user-plus"></i>
                    <span>Nuevo Cliente</span>
                </a>
                <a href="{{ route('inventario.create') }}" class="action-btn">
                    <i class="fa-solid fa-boxes-stacked"></i>
                    <span>Nuevo Insumo</span>
                </a>
                <a href="{{ route('reportes.index') }}" class="action-btn">
                    <i class="fa-solid fa-chart-pie"></i>
                    <span>Ver Reportes</span>
                </a>
            </div>
        </div>
    </div>

    <div class="dash-secondary-grid">
        <div class="dash-card">
            <div class="dash-card-header">
                <h2 class="dash-card-title">Últimos Pedidos</h2>
                <a href="{{ route('pedidos.index') }}" class="card-link">Ver todos &rarr;</a>
            </div>
            <div style="overflow-x: auto;">
                <table class="dash-table">
                    <thead>
                        <tr>
                            <th># Pedido</th>
                            <th>Mesa / Tipo</th>
                            <th>Estado</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($ultimosPedidos ?? [] as $pedido)
                            <tr>
                                <td><strong>#{{ $pedido->id }}</strong></td>
                                <td>
                                    @if($pedido->mesa)
                                        Mesa {{ $pedido->mesa->numero }}
                                    @else
                                        <span style="text-transform: capitalize;">{{ $pedido->tipo }}</span>
                                    @endif
                                </td>
                                <td>
                                    @php
                                        $statusClass = match(strtolower($pedido->estado ?? '')) {
                                            'pendiente' => 'status-pendiente',
                                            'en_proceso' => 'status-en_proceso',
                                            'completado' => 'status-completado',
                                            'cancelado' => 'status-cancelado',
                                            default => 'status-pendiente'
                                        };
                                    @endphp
                                    <span class="badge-status {{ $statusClass }}">
                                        {{ strtoupper(str_replace('_', ' ', $pedido->estado ?? 'pendiente')) }}
                                    </span>
                                </td>
                                <td><strong>S/ {{ number_format($pedido->total ?? 0, 2) }}</strong></td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" style="text-align: center; color: var(--muted); padding: 24px 0;">
                                    <i class="fa-regular fa-folder-open" style="font-size:24px; margin-bottom:8px; display:block;"></i>
                                    No hay pedidos registrados.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="dash-card">
            <div class="dash-card-header">
                <h2 class="dash-card-title">Top Productos</h2>
                <span style="font-size: 12px; color: var(--muted); font-weight: 600;">Más solicitados</span>
            </div>
            <div class="chart-container">
                <canvas id="topProductsChart"></canvas>
            </div>
        </div>
    </div>

</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const colorSea = '#00a7a7';

    // 1. Gráfico de Ventas de la Semana
    const salesCtx = document.getElementById('salesChart').getContext('2d');
    
    const gradient = salesCtx.createLinearGradient(0, 0, 0, 250);
    gradient.addColorStop(0, 'rgba(0, 167, 167, 0.35)');
    gradient.addColorStop(1, 'rgba(0, 167, 167, 0.0)');

    const ventasDias = {!! json_encode($graficoVentas['dias'] ?? []) !!};
    const ventasMontos = {!! json_encode($graficoVentas['montos'] ?? []) !!};

    new Chart(salesCtx, {
        type: 'line',
        data: {
            labels: ventasDias,
            datasets: [{
                label: 'Ventas (S/)',
                data: ventasMontos,
                borderColor: colorSea,
                borderWidth: 3,
                backgroundColor: gradient,
                fill: true,
                tension: 0.35,
                pointBackgroundColor: colorSea,
                pointBorderColor: '#ffffff',
                pointBorderWidth: 2,
                pointRadius: 5,
                pointHoverRadius: 7
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return ' S/ ' + context.parsed.y.toFixed(2);
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: { color: 'rgba(0,0,0,0.05)' },
                    ticks: {
                        callback: function(value) { return 'S/ ' + value; },
                        font: { size: 11 }
                    }
                },
                x: {
                    grid: { display: false },
                    ticks: { font: { size: 11 } }
                }
            }
        }
    });

    // 2. Gráfico de Productos Más Vendidos
    const topCtx = document.getElementById('topProductsChart').getContext('2d');
    
    const topProductosNombres = {!! json_encode($graficoTopProductos['nombres'] ?? []) !!};
    const topProductosCantidades = {!! json_encode($graficoTopProductos['cantidades'] ?? []) !!};

    const hasData = topProductosNombres.length > 0;

    new Chart(topCtx, {
        type: 'doughnut',
        data: {
            labels: hasData ? topProductosNombres : ['Sin datos suficientes'],
            datasets: [{
                data: hasData ? topProductosCantidades : [1],
                backgroundColor: hasData ? [
                    '#00a7a7', '#062b3d', '#f6c453', '#38bdf8', '#fb923c'
                ] : ['#dfe9eb'],
                borderWidth: 2,
                borderColor: '#ffffff'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        boxWidth: 12,
                        padding: 12,
                        font: { size: 11 }
                    }
                }
            },
            cutout: '68%'
        }
    });
});
</script>
@endpush