@extends('layouts.app')

@section('title', 'Historial de ventas')
@section('page-title', 'Ventas')

@push('styles')
<style>
    .sales-page {
        --navy:#062b3d; --sea:#00a7a7; --sea-dark:#078080;
        --gold:#f6c453; --gold-dark:#e0b043; --paper:#ffffff;
        --mist:#f4f7f8; --ink:#18242b; --muted:#68757d; --line:#dfe9eb;
        color:var(--ink);
    }

    .sales-header { display:flex; align-items:flex-end; justify-content:space-between; gap:22px; margin-bottom:25px; }
    .sales-kicker { display:block; margin-bottom:7px; color:var(--sea); font-size:11px; font-weight:800; letter-spacing:.16em; text-transform:uppercase; }
    .sales-header h1 { margin:0 0 7px; color:var(--navy); font:700 clamp(30px,4vw,43px)/1.08 'Playfair Display',serif; }
    .sales-header p { margin:0; color:var(--muted); }
    .sales-button { display:inline-flex; align-items:center; justify-content:center; gap:9px; padding:12px 18px; border:0; border-radius:999px; color:var(--navy); background:var(--gold); box-shadow:0 10px 22px rgba(246,196,83,.24); font-size:13px; font-weight:800; text-decoration:none; transition:.2s; }
    .sales-button:hover { color:var(--navy); background:var(--gold-dark); transform:translateY(-2px); }

    .flash { display:flex; align-items:flex-start; gap:10px; margin-bottom:20px; padding:14px 16px; border-radius:13px; font-size:13px; }
    .flash-success { border:1px solid #a7e5d2; color:#087451; background:#effdf8; }
    .flash-info { border:1px solid #b8e4ea; color:#0b5875; background:#effcfd; }

    .sales-stats { display:grid; grid-template-columns:repeat(4,1fr); gap:17px; margin-bottom:22px; }
    .sales-stat { position:relative; overflow:hidden; padding:20px; border:1px solid var(--line); border-radius:18px; background:var(--paper); box-shadow:0 10px 28px rgba(6,43,61,.06); }
    .sales-stat::after { content:''; position:absolute; right:-27px; bottom:-33px; width:95px; height:95px; border-radius:50%; background:#e7f7f7; }
    .stat-icon { display:grid; width:41px; height:41px; margin-bottom:13px; place-items:center; border-radius:13px; color:var(--sea); background:#e7f7f7; }
    .sales-stat small { display:block; margin-bottom:5px; color:var(--muted); font-size:11px; font-weight:700; text-transform:uppercase; letter-spacing:.05em; }
    .sales-stat strong { position:relative; z-index:1; color:var(--navy); font-size:23px; }

    .sales-card { overflow:hidden; border:1px solid var(--line); border-radius:20px; background:var(--paper); box-shadow:0 12px 32px rgba(6,43,61,.07); }
    .sales-toolbar { display:flex; align-items:center; justify-content:space-between; gap:16px; padding:19px 21px; border-bottom:1px solid var(--line); }
    .sales-toolbar h2 { margin:0; color:var(--navy); font:700 20px 'Playfair Display',serif; }
    .sales-count { display:inline-block; margin-left:7px; padding:4px 9px; border-radius:999px; color:var(--sea-dark); background:#e7f7f7; font:700 11px 'DM Sans',sans-serif; vertical-align:middle; }
    .search-box { position:relative; width:min(100%,320px); }
    .search-box i { position:absolute; top:50%; left:14px; color:#8a9ba0; transform:translateY(-50%); }
    .search-box input { width:100%; height:41px; padding:0 14px 0 40px; border:1px solid #cfdddf; border-radius:999px; outline:none; font-size:13px; }
    .search-box input:focus { border-color:var(--sea); box-shadow:0 0 0 3px rgba(0,167,167,.1); }

    .table-scroll { overflow-x:auto; }
    .sales-table { width:100%; min-width:910px; border-collapse:collapse; }
    .sales-table th { padding:13px 16px; color:#718187; background:#f7fafb; font-size:10px; font-weight:800; letter-spacing:.08em; text-align:left; text-transform:uppercase; white-space:nowrap; }
    .sales-table td { padding:15px 16px; border-top:1px solid #ebf0f1; color:#3e4d53; font-size:13px; vertical-align:middle; }
    .sales-table tbody tr { transition:background .15s; }
    .sales-table tbody tr:hover { background:#fbfdfd; }
    .sale-code { display:flex; align-items:center; gap:10px; color:var(--navy); font-weight:800; }
    .sale-code i { display:grid; width:34px; height:34px; place-items:center; border-radius:10px; color:var(--sea); background:#e7f7f7; }
    .date-main, .customer-name { display:block; color:var(--navy); font-weight:700; }
    .date-hour, .customer-document { display:block; margin-top:3px; color:#89979c; font-size:11px; }
    .order-badge, .table-badge { display:inline-flex; align-items:center; gap:6px; padding:6px 9px; border-radius:999px; font-size:11px; font-weight:700; white-space:nowrap; }
    .order-badge { color:#0b5875; background:#e8f6f8; }
    .table-badge { color:#76580d; background:#fff6d9; }
    .amount { color:var(--sea-dark); font-size:15px; font-weight:800; white-space:nowrap; }
    .payment-method { text-transform:capitalize; }
    .row-actions { display:flex; gap:7px; }
    .action-button { display:grid; width:34px; height:34px; place-items:center; border:1px solid var(--line); border-radius:10px; color:var(--navy); background:white; cursor:pointer; text-decoration:none; transition:.15s; }
    .action-button:hover { color:white; border-color:var(--sea); background:var(--sea); }
    .action-delete { color:#b63a35; }
    .action-delete:hover { border-color:#b63a35; background:#b63a35; }
    .empty-sales { padding:55px 20px !important; text-align:center; }
    .empty-icon { display:grid; width:62px; height:62px; margin:0 auto 14px; place-items:center; border-radius:50%; color:var(--sea); background:#e7f7f7; font-size:24px; }
    .empty-sales strong { display:block; margin-bottom:5px; color:var(--navy); font-size:16px; }
    .empty-sales span { color:var(--muted); }

    body.dark-mode .sales-page { --paper:#0b3447; --mist:#0a2a3a; --ink:#edf7f8; --muted:#aebfc5; --line:#264b5a; }
    body.dark-mode .sales-header h1, body.dark-mode .sales-stat strong, body.dark-mode .sales-toolbar h2,
    body.dark-mode .sale-code, body.dark-mode .date-main, body.dark-mode .customer-name, body.dark-mode .empty-sales strong { color:#fff; }
    body.dark-mode .sales-table th { color:#b9cbd0; background:#082838; }
    body.dark-mode .sales-table td { border-color:#264b5a; color:#d9e5e8; }
    body.dark-mode .sales-table tbody tr:hover { background:#0e3a4e; }
    body.dark-mode .action-button, body.dark-mode .search-box input { color:#e7f1f3; border-color:#315565; background:#0d3a4d; }

    @media(max-width:960px) { .sales-stats{grid-template-columns:repeat(2,1fr)} }
    @media(max-width:650px) { .sales-header,.sales-toolbar{align-items:stretch;flex-direction:column}.sales-button{width:100%}.search-box{width:100%}.sales-stats{grid-template-columns:1fr 1fr;gap:11px}.sales-stat{padding:16px}.sales-stat strong{font-size:19px} }
    @media(max-width:410px) { .sales-stats{grid-template-columns:1fr} }
</style>
@endpush

@section('content')
@php
    $totalVendido = $ventas->sum('monto_total');
    $ventasHoy = $ventas->filter(fn ($venta) => $venta->fecha && $venta->fecha->isToday());
    $totalHoy = $ventasHoy->sum('monto_total');
    $promedio = $ventas->count() > 0 ? $totalVendido / $ventas->count() : 0;
@endphp

<div class="sales-page">
    <header class="sales-header">
        <div>
            <span class="sales-kicker">Control comercial</span>
            <h1>Historial de ventas</h1>
            <p>Consulta los pagos procesados y los ingresos registrados por el restaurante.</p>
        </div>
        <a class="sales-button" href="{{ route('pagos.create') }}">
            <i class="fa-solid fa-credit-card"></i> Procesar nuevo pago
        </a>
    </header>

    @if(session('success'))
        <div class="flash flash-success"><i class="fa-solid fa-circle-check"></i><span>{{ session('success') }}</span></div>
    @endif
    @if(session('info'))
        <div class="flash flash-info"><i class="fa-solid fa-circle-info"></i><span>{{ session('info') }}</span></div>
    @endif

    <section class="sales-stats">
        <article class="sales-stat"><span class="stat-icon"><i class="fa-solid fa-sack-dollar"></i></span><small>Ingresos totales</small><strong>S/ {{ number_format($totalVendido, 2) }}</strong></article>
        <article class="sales-stat"><span class="stat-icon"><i class="fa-solid fa-receipt"></i></span><small>Ventas registradas</small><strong>{{ $ventas->count() }}</strong></article>
        <article class="sales-stat"><span class="stat-icon"><i class="fa-solid fa-calendar-day"></i></span><small>Ingresos de hoy</small><strong>S/ {{ number_format($totalHoy, 2) }}</strong></article>
        <article class="sales-stat"><span class="stat-icon"><i class="fa-solid fa-chart-line"></i></span><small>Venta promedio</small><strong>S/ {{ number_format($promedio, 2) }}</strong></article>
    </section>

    <section class="sales-card">
        <div class="sales-toolbar">
            <h2>Registro de operaciones <span class="sales-count">{{ $ventas->count() }}</span></h2>
            <label class="search-box" for="salesSearch">
                <i class="fa-solid fa-magnifying-glass"></i>
                <input id="salesSearch" type="search" placeholder="Buscar venta, cliente o mesa...">
            </label>
        </div>

        <div class="table-scroll">
            <table class="sales-table">
                <thead>
                    <tr>
                        <th>Venta</th>
                        <th>Fecha y hora</th>
                        <th>Pedido</th>
                        <th>Cliente</th>
                        <th>Mesa</th>
                        <th>Método de pago</th>
                        <th>Total</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody id="salesTableBody">
                    @forelse($ventas as $venta)
                        @php
                            $cliente = $venta->pedido?->cliente;
                            $mesa = $venta->pedido?->mesa;
                            $metodoPago = $venta->pago?->metodo_pago
                                ?? $venta->pago?->metodo
                                ?? $venta->pago?->tipo
                                ?? 'No especificado';
                        @endphp
                        <tr class="sale-row">
                            <td><span class="sale-code"><i class="fa-solid fa-file-invoice-dollar"></i>#V-{{ str_pad($venta->id, 4, '0', STR_PAD_LEFT) }}</span></td>
                            <td><span class="date-main">{{ $venta->fecha?->format('d/m/Y') ?? 'Sin fecha' }}</span><span class="date-hour">{{ $venta->fecha?->format('h:i A') ?? '' }}</span></td>
                            <td><span class="order-badge"><i class="fa-solid fa-clipboard-list"></i>#P-{{ str_pad($venta->pedido_id, 4, '0', STR_PAD_LEFT) }}</span></td>
                            <td><span class="customer-name">{{ $cliente?->nombre ?? 'Cliente general' }}</span><span class="customer-document">{{ $cliente?->documento ?? $cliente?->dni ?? 'Sin documento' }}</span></td>
                            <td><span class="table-badge"><i class="fa-solid fa-chair"></i>{{ $mesa?->numero ? 'Mesa '.$mesa->numero : 'Sin mesa' }}</span></td>
                            <td><span class="payment-method">{{ str_replace('_', ' ', $metodoPago) }}</span></td>
                            <td><span class="amount">S/ {{ number_format($venta->monto_total, 2) }}</span></td>
                            <td>
                                <div class="row-actions">
                                    <a class="action-button" href="{{ route('ventas.show', $venta) }}" title="Ver comprobante"><i class="fa-regular fa-eye"></i></a>
                                    <form method="POST" action="{{ route('ventas.destroy', $venta) }}" onsubmit="return confirm('¿Seguro que deseas eliminar esta venta? Esta acción no se puede deshacer.')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="action-button action-delete" type="submit" title="Eliminar venta"><i class="fa-regular fa-trash-can"></i></button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td class="empty-sales" colspan="8"><span class="empty-icon"><i class="fa-solid fa-receipt"></i></span><strong>Todavía no hay ventas registradas</strong><span>Procesa el pago de un pedido para generar la primera venta.</span></td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>
</div>
@endsection

@push('scripts')
<script>
    const salesSearch = document.getElementById('salesSearch');
    const saleRows = document.querySelectorAll('.sale-row');

    salesSearch?.addEventListener('input', event => {
        const term = event.target.value.toLowerCase().trim();
        saleRows.forEach(row => {
            row.style.display = row.textContent.toLowerCase().includes(term) ? '' : 'none';
        });
    });
</script>
@endpush