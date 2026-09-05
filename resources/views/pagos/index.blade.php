<<<<<<< HEAD
@extends('layouts.app')

@section('title', 'Historial de pagos')
@section('page-title', 'Pagos')

@push('styles')
<style>
    .payments-page { --navy:#062b3d; --sea:#00a7a7; --gold:#f6c453; --gold-dark:#e0b043; --paper:#fff; --muted:#68757d; --line:#dfe9eb; color:#18242b; }
    .payments-header { display:flex; align-items:flex-end; justify-content:space-between; gap:20px; margin-bottom:24px; }
    .payments-kicker { display:block; margin-bottom:7px; color:var(--sea); font-size:11px; font-weight:800; letter-spacing:.16em; text-transform:uppercase; }
    .payments-header h1 { margin:0 0 7px; color:var(--navy); font:700 clamp(30px,4vw,43px)/1.08 'Playfair Display',serif; }
    .payments-header p { margin:0; color:var(--muted); }
    .primary-button { display:inline-flex; align-items:center; justify-content:center; gap:9px; padding:12px 18px; border-radius:999px; color:var(--navy); background:var(--gold); box-shadow:0 10px 22px rgba(246,196,83,.24); font-size:13px; font-weight:800; text-decoration:none; transition:.2s; }
    .primary-button:hover { color:var(--navy); background:var(--gold-dark); transform:translateY(-2px); }

    .flash { display:flex; gap:10px; margin-bottom:20px; padding:14px 16px; border-radius:13px; font-size:13px; }
    .flash-success { border:1px solid #a7e5d2; color:#087451; background:#effdf8; }
    .flash-info { border:1px solid #b8e4ea; color:#0b5875; background:#effcfd; }
    .payment-stats { display:grid; grid-template-columns:repeat(4,1fr); gap:16px; margin-bottom:22px; }
    .stat-card { position:relative; overflow:hidden; padding:20px; border:1px solid var(--line); border-radius:18px; background:var(--paper); box-shadow:0 10px 28px rgba(6,43,61,.06); }
    .stat-icon { display:grid; width:41px; height:41px; margin-bottom:12px; place-items:center; border-radius:13px; color:var(--sea); background:#e7f7f7; }
    .stat-card small { display:block; margin-bottom:5px; color:var(--muted); font-size:10px; font-weight:800; letter-spacing:.06em; text-transform:uppercase; }
    .stat-card strong { color:var(--navy); font-size:22px; }

    .history-card { overflow:hidden; border:1px solid var(--line); border-radius:20px; background:var(--paper); box-shadow:0 12px 32px rgba(6,43,61,.07); }
    .history-toolbar { display:flex; align-items:center; justify-content:space-between; gap:15px; padding:19px 21px; border-bottom:1px solid var(--line); }
    .history-toolbar h2 { margin:0; color:var(--navy); font:700 20px 'Playfair Display',serif; }
    .record-count { display:inline-block; margin-left:7px; padding:4px 9px; border-radius:999px; color:#087b7b; background:#e7f7f7; font:700 11px 'DM Sans',sans-serif; vertical-align:middle; }
    .search-box { position:relative; width:min(100%,320px); }
    .search-box i { position:absolute; top:50%; left:14px; color:#8a9ba0; transform:translateY(-50%); }
    .search-box input { width:100%; height:41px; padding:0 14px 0 40px; border:1px solid #cfdddf; border-radius:999px; outline:none; font-size:13px; }
    .search-box input:focus { border-color:var(--sea); box-shadow:0 0 0 3px rgba(0,167,167,.1); }

    .table-scroll { overflow-x:auto; }
    .payments-table { width:100%; min-width:900px; border-collapse:collapse; }
    .payments-table th { padding:13px 16px; color:#718187; background:#f7fafb; font-size:10px; font-weight:800; letter-spacing:.08em; text-align:left; text-transform:uppercase; white-space:nowrap; }
    .payments-table td { padding:15px 16px; border-top:1px solid #ebf0f1; color:#3e4d53; font-size:13px; vertical-align:middle; }
    .payments-table tbody tr:hover { background:#fbfdfd; }
    .payment-code { display:flex; align-items:center; gap:9px; color:var(--navy); font-weight:800; }
    .payment-code i { display:grid; width:34px; height:34px; place-items:center; border-radius:10px; color:var(--sea); background:#e7f7f7; }
    .main-data { display:block; color:var(--navy); font-weight:700; }
    .sub-data { display:block; margin-top:3px; color:#89979c; font-size:11px; }
    .order-badge, .table-badge, .method-badge { display:inline-flex; align-items:center; gap:6px; padding:6px 9px; border-radius:999px; font-size:11px; font-weight:700; white-space:nowrap; }
    .order-badge { color:#0b5875; background:#e8f6f8; }
    .table-badge { color:#76580d; background:#fff6d9; }
    .method-badge { color:#5b4077; background:#f3eefa; text-transform:capitalize; }
    .amount { color:#087f7f; font-size:15px; font-weight:800; white-space:nowrap; }
    .actions { display:flex; gap:7px; }
    .action-button { display:grid; width:34px; height:34px; place-items:center; border:1px solid var(--line); border-radius:10px; color:var(--navy); background:white; cursor:pointer; text-decoration:none; transition:.15s; }
    .action-button:hover { color:white; border-color:var(--sea); background:var(--sea); }
    .delete-button { color:#b53b35; }
    .delete-button:hover { border-color:#b53b35; background:#b53b35; }
    .empty-state { padding:55px 20px !important; text-align:center; }
    .empty-state i { display:grid; width:62px; height:62px; margin:0 auto 13px; place-items:center; border-radius:50%; color:var(--sea); background:#e7f7f7; font-size:24px; }
    .empty-state strong { display:block; margin-bottom:5px; color:var(--navy); font-size:16px; }
    .empty-state span { color:var(--muted); }

    body.dark-mode .payments-page { --paper:#0b3447; --line:#28505f; color:#eaf3f5; }
    body.dark-mode .payments-header h1, body.dark-mode .stat-card strong, body.dark-mode .history-toolbar h2,
    body.dark-mode .payment-code, body.dark-mode .main-data, body.dark-mode .empty-state strong { color:#fff; }
    body.dark-mode .payments-table th { color:#b9cbd0; background:#082838; }
    body.dark-mode .payments-table td { border-color:#264b5a; color:#d9e5e8; }
    body.dark-mode .payments-table tbody tr:hover { background:#0e3a4e; }
    body.dark-mode .action-button, body.dark-mode .search-box input { color:#e7f1f3; border-color:#315565; background:#0d3a4d; }

    @media(max-width:950px) { .payment-stats{grid-template-columns:repeat(2,1fr)} }
    @media(max-width:650px) { .payments-header,.history-toolbar{align-items:stretch;flex-direction:column}.primary-button,.search-box{width:100%}.payment-stats{gap:10px}.stat-card{padding:16px}.stat-card strong{font-size:18px} }
    @media(max-width:410px) { .payment-stats{grid-template-columns:1fr} }
</style>
@endpush

@section('content')
@php
    $totalPagado = $pagos->sum('monto');
    $totalEfectivo = $pagos->where('metodo', 'efectivo')->sum('monto');
    $totalDigital = $pagos->whereIn('metodo', ['yape', 'plin'])->sum('monto');
    $totalTarjeta = $pagos->where('metodo', 'tarjeta')->sum('monto');
@endphp

<div class="payments-page">
    <header class="payments-header">
        <div><span class="payments-kicker">Caja y facturación</span><h1>Historial de pagos</h1><p>Consulta todos los cobros procesados por el restaurante.</p></div>
        <a class="primary-button" href="{{ route('pagos.create') }}"><i class="fa-solid fa-plus"></i> Procesar nuevo pago</a>
    </header>

    @if(session('success'))<div class="flash flash-success"><i class="fa-solid fa-circle-check"></i><span>{{ session('success') }}</span></div>@endif
    @if(session('info'))<div class="flash flash-info"><i class="fa-solid fa-circle-info"></i><span>{{ session('info') }}</span></div>@endif

    <section class="payment-stats">
        <article class="stat-card"><span class="stat-icon"><i class="fa-solid fa-sack-dollar"></i></span><small>Total procesado</small><strong>S/ {{ number_format($totalPagado, 2) }}</strong></article>
        <article class="stat-card"><span class="stat-icon"><i class="fa-solid fa-money-bill-wave"></i></span><small>En efectivo</small><strong>S/ {{ number_format($totalEfectivo, 2) }}</strong></article>
        <article class="stat-card"><span class="stat-icon"><i class="fa-solid fa-mobile-screen-button"></i></span><small>Yape y Plin</small><strong>S/ {{ number_format($totalDigital, 2) }}</strong></article>
        <article class="stat-card"><span class="stat-icon"><i class="fa-regular fa-credit-card"></i></span><small>Con tarjeta</small><strong>S/ {{ number_format($totalTarjeta, 2) }}</strong></article>
    </section>

    <section class="history-card">
        <div class="history-toolbar">
            <h2>Operaciones registradas <span class="record-count">{{ $pagos->count() }}</span></h2>
            <label class="search-box" for="paymentSearch"><i class="fa-solid fa-magnifying-glass"></i><input id="paymentSearch" type="search" placeholder="Buscar pago, cliente o mesa..."></label>
        </div>

        <div class="table-scroll">
            <table class="payments-table">
                <thead><tr><th>Pago</th><th>Fecha</th><th>Pedido</th><th>Cliente</th><th>Mesa</th><th>Método</th><th>Monto</th><th>Acciones</th></tr></thead>
                <tbody>
                    @forelse($pagos as $pago)
                        @php $cliente = $pago->pedido?->cliente; $mesa = $pago->pedido?->mesa; @endphp
                        <tr class="payment-row">
                            <td><span class="payment-code"><i class="fa-solid fa-wallet"></i>#PG-{{ str_pad($pago->id, 4, '0', STR_PAD_LEFT) }}</span></td>
                            <td><span class="main-data">{{ $pago->created_at?->format('d/m/Y') ?? 'Sin fecha' }}</span><span class="sub-data">{{ $pago->created_at?->format('h:i A') ?? '' }}</span></td>
                            <td><span class="order-badge"><i class="fa-solid fa-clipboard-list"></i>#P-{{ str_pad($pago->pedido_id, 4, '0', STR_PAD_LEFT) }}</span></td>
                            <td><span class="main-data">{{ $cliente?->nombre ?? 'Cliente general' }}</span><span class="sub-data">{{ $cliente?->documento ?? $cliente?->dni ?? 'Sin documento' }}</span></td>
                            <td><span class="table-badge"><i class="fa-solid fa-chair"></i>{{ $mesa?->numero ? 'Mesa '.$mesa->numero : 'Sin mesa' }}</span></td>
                            <td><span class="method-badge"><i class="fa-solid {{ $pago->metodo === 'efectivo' ? 'fa-money-bill' : ($pago->metodo === 'tarjeta' ? 'fa-credit-card' : 'fa-mobile-screen-button') }}"></i>{{ $pago->metodo }}</span></td>
                            <td><span class="amount">S/ {{ number_format($pago->monto, 2) }}</span></td>
                            <td><div class="actions">
                                <a class="action-button" href="{{ route('pagos.show', $pago) }}" title="Ver detalle"><i class="fa-regular fa-eye"></i></a>
                                <form method="POST" action="{{ route('pagos.destroy', $pago) }}" onsubmit="return confirm('¿Deseas anular este pago? El pedido volverá al estado pendiente.')">@csrf @method('DELETE')<button class="action-button delete-button" type="submit" title="Anular pago"><i class="fa-solid fa-ban"></i></button></form>
                            </div></td>
                        </tr>
                    @empty
                        <tr><td class="empty-state" colspan="8"><i class="fa-solid fa-wallet"></i><strong>No hay pagos registrados</strong><span>Procesa el pago de un pedido para ver la primera operación.</span></td></tr>
=======
<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="text-xl font-bold text-sky-800">Historial de Pagos</h2>
            <a href="{{ route('pagos.create') }}"
               class="bg-sky-500 hover:bg-sky-600 text-white px-4 py-2 rounded-lg text-sm font-medium transition">
                + Procesar Pago
            </a>
        </div>
    </x-slot>

    <div class="py-6 px-4 max-w-7xl mx-auto">

        @if(session('success'))
            <div class="mb-4 bg-green-100 border border-green-300 text-green-700 px-4 py-3 rounded-lg">
                ✅ {{ session('success') }}
            </div>
        @endif

        {{-- RESUMEN --}}
        <div class="grid grid-cols-4 gap-4 mb-6">
            <div class="bg-white border border-sky-100 rounded-xl p-4 text-center shadow-sm col-span-1">
                <p class="text-2xl font-bold text-sky-600">{{ $pagos->count() }}</p>
                <p class="text-sm text-gray-500 mt-1">Total Pagos</p>
            </div>
            <div class="bg-white border border-sky-100 rounded-xl p-4 text-center shadow-sm col-span-1">
                <p class="text-2xl font-bold text-green-600">S/ {{ number_format($pagos->sum('monto'), 2) }}</p>
                <p class="text-sm text-gray-500 mt-1">Total Recaudado</p>
            </div>
            <div class="bg-white border border-sky-100 rounded-xl p-4 text-center shadow-sm col-span-1">
                <p class="text-2xl font-bold text-gray-700">{{ $pagos->where('metodo','efectivo')->count() }}</p>
                <p class="text-sm text-gray-500 mt-1">En Efectivo</p>
            </div>
            <div class="bg-white border border-sky-100 rounded-xl p-4 text-center shadow-sm col-span-1">
                <p class="text-2xl font-bold text-purple-600">{{ $pagos->whereIn('metodo',['yape','plin'])->count() }}</p>
                <p class="text-sm text-gray-500 mt-1">Yape / Plin</p>
            </div>
        </div>

        {{-- TABLA --}}
        <div class="bg-white rounded-xl shadow-sm overflow-hidden border border-sky-100">
            <div class="bg-sky-50 px-6 py-4 border-b border-sky-100">
                <h3 class="text-sky-700 font-semibold">Pagos Registrados</h3>
            </div>
            <table class="min-w-full divide-y divide-sky-100">
                <thead class="bg-sky-100">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-sky-700 uppercase">#</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-sky-700 uppercase">Pedido</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-sky-700 uppercase">Cliente</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-sky-700 uppercase">Mesa</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-sky-700 uppercase">Método</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-sky-700 uppercase">Monto</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-sky-700 uppercase">Fecha</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-sky-700 uppercase">Acciones</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-100">
                    @forelse($pagos as $pago)
                    <tr class="hover:bg-sky-50 transition">
                        <td class="px-6 py-4 text-gray-400 text-sm">{{ $loop->iteration }}</td>
                        <td class="px-6 py-4 font-semibold text-gray-800">#{{ $pago->pedido_id }}</td>
                        <td class="px-6 py-4 text-gray-600">
                            {{ optional(optional($pago->pedido)->cliente)->nombre ?? '—' }}
                        </td>
                        <td class="px-6 py-4 text-gray-600">
                            {{ optional(optional($pago->pedido)->mesa)->numero ? 'Mesa ' . $pago->pedido->mesa->numero : '—' }}
                        </td>
                        <td class="px-6 py-4">
                            @php
                                $colores = [
                                    'efectivo' => 'bg-green-100 text-green-700',
                                    'tarjeta'  => 'bg-blue-100 text-blue-700',
                                    'yape'     => 'bg-purple-100 text-purple-700',
                                    'plin'     => 'bg-pink-100 text-pink-700',
                                ];
                            @endphp
                            <span class="px-2 py-1 rounded-full text-xs font-semibold {{ $colores[$pago->metodo] ?? 'bg-gray-100 text-gray-700' }}">
                                {{ ucfirst($pago->metodo) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 font-bold text-gray-800">S/ {{ number_format($pago->monto, 2) }}</td>
                        <td class="px-6 py-4 text-gray-500 text-sm">{{ $pago->created_at->format('d/m/Y H:i') }}</td>
                        <td class="px-6 py-4">
                            <div class="flex gap-3">
                                <a href="{{ route('pagos.show', $pago) }}"
                                   class="text-sky-600 hover:text-sky-800 text-sm font-medium">Ver</a>
                                <a href="{{ route('pagos.edit', $pago) }}"
                                   class="text-yellow-600 hover:text-yellow-800 text-sm font-medium">Editar</a>
                                <form action="{{ route('pagos.destroy', $pago) }}" method="POST"
                                      onsubmit="return confirm('¿Anular este pago?')">
                                    @csrf @method('DELETE')
                                    <button class="text-red-500 hover:text-red-700 text-sm font-medium">Anular</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="px-6 py-10 text-center text-gray-400">
                            No hay pagos registrados aún.
                        </td>
                    </tr>
>>>>>>> origin/fabian
                    @endforelse
                </tbody>
            </table>
        </div>
<<<<<<< HEAD
    </section>
</div>
@endsection

@push('scripts')
<script>
    const paymentSearch = document.getElementById('paymentSearch');
    const paymentRows = document.querySelectorAll('.payment-row');
    paymentSearch?.addEventListener('input', event => {
        const term = event.target.value.toLowerCase().trim();
        paymentRows.forEach(row => row.style.display = row.textContent.toLowerCase().includes(term) ? '' : 'none');
    });
</script>
@endpush
=======
    </div>
</x-app-layout>
>>>>>>> origin/fabian
