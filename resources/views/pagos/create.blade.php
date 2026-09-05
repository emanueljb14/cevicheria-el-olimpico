<<<<<<< HEAD
@extends('layouts.app')

@section('title', 'Procesar pago')
@section('page-title', 'Procesar pago')

@push('styles')
<style>
    .payment-page { --navy:#062b3d; --sea:#00a7a7; --gold:#f6c453; --gold-dark:#e0b043; --paper:#fff; --muted:#68757d; --line:#dfe9eb; color:#18242b; }
    .payment-heading { display:flex; align-items:flex-end; justify-content:space-between; gap:20px; margin-bottom:25px; }
    .payment-kicker { display:block; margin-bottom:7px; color:var(--sea); font-size:11px; font-weight:800; letter-spacing:.16em; text-transform:uppercase; }
    .payment-heading h1 { margin:0 0 7px; color:var(--navy); font:700 clamp(30px,4vw,43px)/1.08 'Playfair Display',serif; }
    .payment-heading p { margin:0; color:var(--muted); }
    .back-button { display:inline-flex; align-items:center; gap:8px; color:var(--navy); font-size:13px; font-weight:700; text-decoration:none; }
    .back-button:hover { color:var(--sea); }

    .payment-layout { display:grid; grid-template-columns:minmax(0,1.35fr) minmax(310px,.65fr); gap:22px; align-items:start; }
    .payment-card { overflow:hidden; border:1px solid var(--line); border-radius:20px; background:var(--paper); box-shadow:0 12px 32px rgba(6,43,61,.07); }
    .card-heading { padding:20px 22px; border-bottom:1px solid var(--line); }
    .card-heading h2 { margin:0 0 4px; color:var(--navy); font:700 21px 'Playfair Display',serif; }
    .card-heading p { margin:0; color:var(--muted); font-size:12px; }
    .card-body { padding:22px; }

    .error-summary { display:flex; gap:10px; margin-bottom:20px; padding:14px 16px; border:1px solid #efb9b5; border-radius:13px; color:#a3312b; background:#fff4f3; font-size:13px; }
    .error-summary ul { margin:4px 0 0; padding-left:17px; }
    .field-label { display:block; margin-bottom:9px; color:var(--navy); font-size:13px; font-weight:800; }
    .order-select { width:100%; height:51px; padding:0 42px 0 15px; border:1px solid #cedcdf; border-radius:13px; outline:none; color:#27383f; background:#fff; font:500 14px 'DM Sans',sans-serif; cursor:pointer; }
    .order-select:focus { border-color:var(--sea); box-shadow:0 0 0 4px rgba(0,167,167,.1); }
    .field-error { display:block; margin-top:7px; color:#b43a34; font-size:12px; }

    .methods-title { margin:26px 0 11px; color:var(--navy); font-size:13px; font-weight:800; }
    .method-grid { display:grid; grid-template-columns:repeat(4,1fr); gap:11px; }
    .method-option input { position:absolute; opacity:0; pointer-events:none; }
    .method-card { display:flex; min-height:105px; flex-direction:column; align-items:center; justify-content:center; gap:9px; padding:13px 8px; border:1px solid var(--line); border-radius:15px; color:#506168; background:#fff; cursor:pointer; transition:.18s; }
    .method-card i { color:var(--sea); font-size:24px; }
    .method-card span { font-size:12px; font-weight:700; }
    .method-option input:checked + .method-card { border-color:var(--sea); color:var(--navy); background:#eaf9f8; box-shadow:0 0 0 3px rgba(0,167,167,.09); }
    .method-option input:focus-visible + .method-card { outline:3px solid rgba(0,167,167,.24); }

    .security-note { display:flex; gap:10px; margin-top:21px; padding:13px 14px; border-radius:13px; color:#4d636b; background:#f2f7f7; font-size:12px; line-height:1.55; }
    .security-note i { margin-top:2px; color:var(--sea); }

    .summary-card { position:sticky; top:94px; }
    .summary-hero { padding:24px; color:white; background:linear-gradient(140deg,#062b3d,#0b5875); }
    .summary-hero small { color:#b8cdd3; font-size:11px; font-weight:700; letter-spacing:.08em; text-transform:uppercase; }
    .summary-amount { display:block; margin-top:7px; color:var(--gold); font:700 38px 'Playfair Display',serif; }
    .summary-lines { padding:20px 22px; }
    .summary-row { display:flex; align-items:flex-start; justify-content:space-between; gap:18px; padding:11px 0; border-bottom:1px solid #e9eff0; font-size:13px; }
    .summary-row:last-child { border:0; }
    .summary-row span { color:var(--muted); }
    .summary-row strong { color:var(--navy); text-align:right; }
    .pay-button { display:flex; width:calc(100% - 44px); height:52px; margin:0 22px 22px; align-items:center; justify-content:center; gap:10px; border:0; border-radius:999px; color:var(--navy); background:var(--gold); box-shadow:0 10px 22px rgba(246,196,83,.25); font:800 14px 'DM Sans',sans-serif; cursor:pointer; transition:.2s; }
    .pay-button:hover:not(:disabled) { background:var(--gold-dark); transform:translateY(-2px); }
    .pay-button:disabled { opacity:.55; cursor:not-allowed; }
    .empty-orders { padding:45px 22px; text-align:center; }
    .empty-orders i { display:grid; width:60px; height:60px; margin:0 auto 13px; place-items:center; border-radius:50%; color:var(--sea); background:#e7f7f7; font-size:23px; }
    .empty-orders h3 { margin-bottom:6px; color:var(--navy); font:700 20px 'Playfair Display',serif; }
    .empty-orders p { margin-bottom:18px; color:var(--muted); font-size:13px; }

    body.dark-mode .payment-page { --paper:#0b3447; --line:#28505f; color:#eaf3f5; }
    body.dark-mode .payment-heading h1, body.dark-mode .card-heading h2, body.dark-mode .field-label,
    body.dark-mode .methods-title, body.dark-mode .summary-row strong, body.dark-mode .empty-orders h3 { color:#fff; }
    body.dark-mode .order-select, body.dark-mode .method-card { color:#eaf3f5; border-color:#315867; background:#0c3a4e; }
    body.dark-mode .method-option input:checked + .method-card { background:#10525c; }
    body.dark-mode .security-note { color:#cfdee2; background:#092b3c; }
    body.dark-mode .summary-row { border-color:#28505f; }

    @media(max-width:900px) { .payment-layout{grid-template-columns:1fr}.summary-card{position:static}.method-grid{grid-template-columns:repeat(2,1fr)} }
    @media(max-width:600px) { .payment-heading{align-items:flex-start;flex-direction:column}.card-body{padding:18px}.method-card{min-height:90px} }
</style>
@endpush

@section('content')
<div class="payment-page">
    <header class="payment-heading">
        <div>
            <span class="payment-kicker">Caja y facturación</span>
            <h1>Procesar un pago</h1>
            <p>Selecciona el pedido pendiente y registra el método utilizado.</p>
        </div>
        <a class="back-button" href="{{ route('pagos.index') }}"><i class="fa-solid fa-arrow-left"></i> Volver al historial</a>
    </header>

    @if($errors->any())
        <div class="error-summary">
            <i class="fa-solid fa-circle-exclamation"></i>
            <div><strong>No se pudo procesar el pago.</strong><ul>@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>
        </div>
    @endif

    @if($pedidosPendientes->isEmpty())
        <section class="payment-card empty-orders">
            <i class="fa-solid fa-clipboard-check"></i>
            <h3>No hay pedidos pendientes</h3>
            <p>Cuando exista un pedido pendiente o en proceso, podrás cobrarlo desde aquí.</p>
            <a class="back-button" href="{{ route('pedidos.index') }}">Ver todos los pedidos <i class="fa-solid fa-arrow-right"></i></a>
        </section>
    @else
        <form method="POST" action="{{ route('pagos.store') }}" id="paymentForm">
            @csrf
            <div class="payment-layout">
                <section class="payment-card">
                    <div class="card-heading"><h2>Datos del cobro</h2><p>La venta se generará automáticamente al confirmar.</p></div>
                    <div class="card-body">
                        <label class="field-label" for="pedido_id">Pedido pendiente</label>
                        <select class="order-select" name="pedido_id" id="pedido_id" required>
                            <option value="">Selecciona un pedido</option>
                            @foreach($pedidosPendientes as $pedido)
                                <option
                                    value="{{ $pedido->id }}"
                                    data-total="{{ $pedido->total }}"
                                    data-client="{{ $pedido->cliente?->nombre ?? 'Cliente general' }}"
                                    data-table="{{ $pedido->mesa?->numero ? 'Mesa '.$pedido->mesa->numero : 'Sin mesa' }}"
                                    data-status="{{ ucfirst(str_replace('_', ' ', $pedido->estado)) }}"
                                    {{ old('pedido_id') == $pedido->id ? 'selected' : '' }}
                                >
                                    Pedido #{{ str_pad($pedido->id, 4, '0', STR_PAD_LEFT) }} — {{ $pedido->cliente?->nombre ?? 'Cliente general' }} — S/ {{ number_format($pedido->total, 2) }}
                                </option>
                            @endforeach
                        </select>
                        @error('pedido_id')<span class="field-error">{{ $message }}</span>@enderror

                        <p class="methods-title">Método de pago</p>
                        <div class="method-grid">
                            @foreach([
                                'efectivo' => ['fa-money-bill-wave', 'Efectivo'],
                                'tarjeta'  => ['fa-credit-card', 'Tarjeta'],
                                'yape'     => ['fa-mobile-screen-button', 'Yape'],
                                'plin'     => ['fa-qrcode', 'Plin']
                            ] as $value => [$icon, $label])
                                <label class="method-option">
                                    <input type="radio" name="metodo" value="{{ $value }}" {{ old('metodo', 'efectivo') === $value ? 'checked' : '' }} required>
                                    <span class="method-card"><i class="fa-solid {{ $icon }}"></i><span>{{ $label }}</span></span>
                                </label>
                            @endforeach
                        </div>
                        @error('metodo')<span class="field-error">{{ $message }}</span>@enderror

                        <div class="security-note"><i class="fa-solid fa-shield-halved"></i><span>El monto se obtiene directamente del pedido y no puede modificarse desde este formulario. Al confirmar, el pedido quedará completado y su mesa será liberada.</span></div>
                    </div>
                </section>

                <aside class="payment-card summary-card">
                    <div class="summary-hero"><small>Total a cobrar</small><strong class="summary-amount" id="summaryAmount">S/ 0.00</strong></div>
                    <div class="summary-lines">
                        <div class="summary-row"><span>Pedido</span><strong id="summaryOrder">Sin seleccionar</strong></div>
                        <div class="summary-row"><span>Cliente</span><strong id="summaryClient">—</strong></div>
                        <div class="summary-row"><span>Mesa</span><strong id="summaryTable">—</strong></div>
                        <div class="summary-row"><span>Estado</span><strong id="summaryStatus">—</strong></div>
                    </div>
                    <button class="pay-button" id="payButton" type="submit" disabled><i class="fa-solid fa-circle-check"></i> Confirmar y registrar pago</button>
                </aside>
            </div>
        </form>
    @endif
</div>
@endsection

@push('scripts')
<script>
    const orderSelect = document.getElementById('pedido_id');
    const payButton = document.getElementById('payButton');

    function updatePaymentSummary() {
        if (!orderSelect) return;
        const option = orderSelect.options[orderSelect.selectedIndex];
        const selected = Boolean(option?.value);

        document.getElementById('summaryAmount').textContent = selected
            ? `S/ ${Number(option.dataset.total).toFixed(2)}` : 'S/ 0.00';
        document.getElementById('summaryOrder').textContent = selected
            ? `#${String(option.value).padStart(4, '0')}` : 'Sin seleccionar';
        document.getElementById('summaryClient').textContent = selected ? option.dataset.client : '—';
        document.getElementById('summaryTable').textContent = selected ? option.dataset.table : '—';
        document.getElementById('summaryStatus').textContent = selected ? option.dataset.status : '—';
        payButton.disabled = !selected;
    }

    orderSelect?.addEventListener('change', updatePaymentSummary);
    updatePaymentSummary();

    document.getElementById('paymentForm')?.addEventListener('submit', event => {
        if (!confirm('¿Confirmas el pago de este pedido?')) event.preventDefault();
    });
</script>
@endpush
=======
<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <a href="{{ route('pagos.index') }}" class="text-sky-500 hover:text-sky-700">← Volver</a>
            <h2 class="text-xl font-bold text-sky-800">Procesar Pago</h2>
        </div>
    </x-slot>

    <div class="py-6 px-4 max-w-lg mx-auto">
        <div class="bg-white rounded-xl shadow-sm border border-sky-100 overflow-hidden">
            <div class="bg-sky-500 px-6 py-4">
                <h3 class="text-white font-semibold text-lg">Nuevo Pago</h3>
                <p class="text-sky-100 text-sm">Selecciona el pedido y método de pago</p>
            </div>

            <form action="{{ route('pagos.store') }}" method="POST" class="p-6 space-y-5">
                @csrf

                {{-- Pedido --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Pedido <span class="text-red-500">*</span></label>
                    <select name="pedido_id"
                            class="w-full border border-sky-200 rounded-lg px-4 py-2 focus:ring-2 focus:ring-sky-400 focus:outline-none @error('pedido_id') border-red-400 @enderror">
                        <option value="">-- Seleccionar pedido pendiente --</option>
                        @foreach($pedidosPendientes as $pedido)
                            <option value="{{ $pedido->id }}" @selected(old('pedido_id') == $pedido->id)>
                                Pedido #{{ $pedido->id }}
                                @if($pedido->cliente) — {{ $pedido->cliente->nombre }} @endif
                                @if($pedido->mesa) — Mesa {{ $pedido->mesa->numero }} @endif
                                — S/ {{ number_format($pedido->total, 2) }}
                            </option>
                        @endforeach
                    </select>
                    @error('pedido_id')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                    @if($pedidosPendientes->isEmpty())
                        <p class="text-yellow-600 text-xs mt-1">⚠️ No hay pedidos pendientes de pago.</p>
                    @endif
                </div>

                {{-- Método de pago --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Método de Pago <span class="text-red-500">*</span></label>
                    <div class="grid grid-cols-2 gap-3">
                        @foreach(['efectivo' => '💵 Efectivo', 'tarjeta' => '💳 Tarjeta', 'yape' => '📱 Yape', 'plin' => '📲 Plin'] as $valor => $etiqueta)
                            <label class="flex items-center gap-2 border border-sky-200 rounded-lg px-4 py-3 cursor-pointer hover:bg-sky-50 transition has-[:checked]:border-sky-500 has-[:checked]:bg-sky-50">
                                <input type="radio" name="metodo" value="{{ $valor }}"
                                       {{ old('metodo', 'efectivo') === $valor ? 'checked' : '' }}
                                       class="text-sky-500">
                                <span class="text-sm font-medium text-gray-700">{{ $etiqueta }}</span>
                            </label>
                        @endforeach
                    </div>
                    @error('metodo')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Info --}}
                <div class="bg-sky-50 border border-sky-100 rounded-lg p-4 text-sm text-sky-700">
                    ℹ️ Al procesar el pago, el pedido pasará a <strong>completado</strong> y la mesa quedará <strong>libre</strong> automáticamente.
                </div>

                {{-- Botones --}}
                <div class="flex gap-3 pt-2">
                    <button type="submit"
                            class="flex-1 bg-sky-500 hover:bg-sky-600 text-white py-2 rounded-lg font-medium transition">
                        Confirmar Pago
                    </button>
                    <a href="{{ route('pagos.index') }}"
                       class="flex-1 text-center bg-gray-100 hover:bg-gray-200 text-gray-700 py-2 rounded-lg font-medium transition">
                        Cancelar
                    </a>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
>>>>>>> origin/fabian
