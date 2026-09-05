@extends('layouts.app')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@push('styles')
<style>
    .dashboard-page { --navy:#062b3d; --sea:#00a7a7; --gold:#f6c453; --paper:#fff; --muted:#68757d; --line:#dfe9eb; color:#18242b; }
    
    .kpi-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 18px; margin-bottom: 28px; }
    .kpi-card { background: var(--paper); border: 1px solid var(--line); border-radius: 16px; padding: 20px; display: flex; align-items: center; justify-content: space-between; box-shadow: 0 4px 15px rgba(6,43,61,.04); }
    .kpi-info span { font-size: 11px; font-weight: 800; text-transform: uppercase; letter-spacing: 0.05em; color: var(--muted); display: block; }
    .kpi-info h3 { font-size: 24px; font-weight: 800; color: var(--navy); margin: 4px 0 0; }
    .kpi-icon { width: 48px; height: 48px; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 20px; }
    
    .icon-sea { background: #e7f7f7; color: var(--sea); }
    .icon-gold { background: #fef7e7; color: #d99a00; }
    .icon-navy { background: #e8ecef; color: var(--navy); }
    .icon-danger { background: #fde8e8; color: #e53e3e; }

    .dash-grid { display: grid; grid-template-columns: 2fr 1fr; gap: 22px; }
    .dash-card { background: var(--paper); border: 1px solid var(--line); border-radius: 20px; padding: 22px; box-shadow: 0 8px 24px rgba(6,43,61,.05); }
    .dash-card-title { font: 700 18px 'Playfair Display', serif; color: var(--navy); margin-bottom: 16px; display: flex; justify-content: space-between; align-items: center; }

    .quick-actions { display: grid; grid-template-columns: repeat(2, 1fr); gap: 12px; }
    .action-btn { display: flex; flex-direction: column; align-items: center; justify-content: center; padding: 16px; background: #f8fafc; border: 1px dashed #cfdddf; border-radius: 12px; text-decoration: none; color: var(--navy); font-weight: 700; font-size: 12px; gap: 8px; transition: .2s; }
    .action-btn:hover { background: #e7f7f7; border-color: var(--sea); color: var(--sea); }

    .dash-table { width: 100%; border-collapse: collapse; }
    .dash-table th { text-align: left; font-size: 11px; text-transform: uppercase; color: var(--muted); padding-bottom: 10px; border-bottom: 1px solid var(--line); }
    .dash-table td { padding: 12px 0; border-bottom: 1px solid #f2f6f7; font-size: 13px; }

    body.dark-mode .dashboard-page { --paper:#0b3447; --line:#28505f; color:#eaf3f5; }
    body.dark-mode .kpi-info h3, body.dark-mode .dash-card-title { color: #fff; }
    body.dark-mode .action-btn { background: #0d3a4d; border-color: #315565; color: #e7f1f3; }

    @media(max-width: 900px) { .dash-grid { grid-template-columns: 1fr; } }
</style>
@endpush

@section('content')
<div class="dashboard-page">
    
    {{-- Tarjetas KPI Métricas --}}
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

    {{-- Grid Principal --}}
    <div class="dash-grid">
        
        {{-- Últimos Pedidos --}}
        <div class="dash-card">
            <div class="dash-card-title">
                <span>Últimos Pedidos del Día</span>
                <a href="{{ route('pedidos.index') }}" style="font-size: 12px; color: var(--sea); text-decoration: none;">Ver todos &rarr;</a>
            </div>
            <table class="dash-table">
                <thead>
                    <tr>
                        <th># Pedido</th>
                        <th>Mesa</th>
                        <th>Estado</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($ultimosPedidos ?? [] as $pedido)
                        <tr>
                            <td><strong>#{{ $pedido->id }}</strong></td>
                            <td>{{ $pedido->mesa->numero ?? 'Para llevar' }}</td>
                            <td>
                                <span style="padding: 3px 8px; border-radius: 999px; font-size: 10px; font-weight: 800; background: #e7f7f7; color: var(--sea);">
                                    {{ strtoupper($pedido->estado) }}
                                </span>
                            </td>
                            <td>S/ {{ number_format($pedido->total, 2) }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" style="text-align: center; color: var(--muted); padding: 20px 0;">No hay pedidos registrados hoy.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Acceso Rápido --}}
        <div class="dash-card">
            <div class="dash-card-title">Acciones Rápidas</div>
            <div class="quick-actions">
                <a href="{{ route('pedidos.create') }}" class="action-btn">
                    <i class="fa-solid fa-plus-circle" style="font-size: 20px;"></i>
                    Nuevo Pedido
                </a>
                <a href="{{ route('clientes.create') }}" class="action-btn">
                    <i class="fa-solid fa-user-plus" style="font-size: 20px;"></i>
                    Nuevo Cliente
                </a>
                <a href="{{ route('inventario.create') }}" class="action-btn">
                    <i class="fa-solid fa-box" style="font-size: 20px;"></i>
                    Nuevo Insumo
                </a>
                <a href="{{ route('reportes.index') }}" class="action-btn">
                    <i class="fa-solid fa-chart-line" style="font-size: 20px;"></i>
                    Ver Reportes
                </a>
            </div>
        </div>

    </div>
</div>
@endsection