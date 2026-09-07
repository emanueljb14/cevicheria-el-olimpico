@extends('layouts.app')

@section('title', 'Comprobante de pago')
@section('page-title', 'Detalle del pago')

@push('styles')
<style>
    .receipt-page {
        --navy:#062b3d; --navy-dark:#031b27; --sea:#00a7a7;
        --gold:#f6c453; --paper:#fff; --muted:#68757d; --line:#dfe9eb;
        max-width:920px; margin:auto; color:#18242b;
    }
    .receipt-actions { display:flex; align-items:center; justify-content:space-between; gap:14px; margin-bottom:20px; }
    .back-link { display:inline-flex; align-items:center; gap:8px; color:var(--navy); font-size:13px; font-weight:800; text-decoration:none; }
    .back-link:hover { color:var(--sea); }
    .action-group { display:flex; gap:9px; }
    .action-button { display:inline-flex; min-height:42px; align-items:center; justify-content:center; gap:8px; padding:0 16px; border:1px solid var(--line); border-radius:999px; color:var(--navy); background:#fff; font:800 12px 'DM Sans',sans-serif; text-decoration:none; cursor:pointer; transition:.18s; }
    .action-button:hover { color:#fff; border-color:var(--sea); background:var(--sea); }
    .danger-button { color:#ac332e; border-color:#edc5c2; }
    .danger-button:hover { border-color:#b63a35; background:#b63a35; }

    .receipt { overflow:hidden; border:1px solid var(--line); border-radius:23px; background:var(--paper); box-shadow:0 16px 45px rgba(6,43,61,.10); }
    .receipt-header { position:relative; display:flex; align-items:center; justify-content:space-between; gap:25px; overflow:hidden; padding:30px 34px; color:#fff; background:linear-gradient(135deg,var(--navy-dark),#0b5875); }
    .receipt-header::after { content:'PAGADO'; position:absolute; right:-15px; bottom:-35px; color:rgba(255,255,255,.055); font:800 90px 'Playfair Display',serif; transform:rotate(-5deg); }
    .receipt-brand { position:relative; z-index:1; display:flex; align-items:center; gap:12px; }
    .brand-icon { display:grid; width:49px; height:49px; flex:0 0 49px; place-items:center; border-radius:50%; color:var(--navy); background:var(--gold); font-size:20px; }
    .receipt-brand strong { display:block; font:700 22px/1 'Playfair Display',serif; }
    .receipt-brand small { display:block; margin-top:5px; color:#bfd1d6; font-size:9px; letter-spacing:2.2px; }
    .receipt-number { position:relative; z-index:1; text-align:right; }
    .receipt-number small { display:block; margin-bottom:4px; color:#bfd1d6; font-size:10px; letter-spacing:.1em; text-transform:uppercase; }
    .receipt-number strong { color:var(--gold); font:700 24px 'Playfair Display',serif; }

    .receipt-body { padding:30px 34px; }
    .status-line { display:flex; align-items:center; justify-content:space-between; gap:15px; margin-bottom:25px; padding-bottom:22px; border-bottom:1px solid var(--line); }
    .paid-badge { display:inline-flex; align-items:center; gap:7px; padding:7px 11px; border-radius:999px; color:#087451; background:#eafaf4; font-size:11px; font-weight:800; }
    .status-line time { color:var(--muted); font-size:12px; }

    .info-grid { display:grid; grid-template-columns:repeat(3,1fr); gap:13px; margin-bottom:27px; }
    .info-box { padding:15px; border:1px solid #e4ecee; border-radius:14px; background:#fafcfc; }
    .info-box small,.info-box strong { display:block; }
    .info-box small { margin-bottom:6px; color:#77888e; font-size:9px; font-weight:800; letter-spacing:.08em; text-transform:uppercase; }
    .info-box strong { overflow:hidden; color:var(--navy); font-size:13px; text-overflow:ellipsis; white-space:nowrap; }
    .info-box span { display:block; margin-top:4px; color:var(--muted); font-size:11px; }

    .section-title { display:flex; align-items:center; justify-content:space-between; gap:15px; margin-bottom:12px; }
    .section-title h2 { margin:0; color:var(--navy); font:700 20px 'Playfair Display',serif; }
    .section-title span { color:var(--muted); font-size:11px; }
    .products-table-wrap { overflow-x:auto; margin-bottom:25px; border:1px solid var(--line); border-radius:15px; }
    .products-table { width:100%; min-width:590px; border-collapse:collapse; }
    .products-table th { padding:11px 14px; color:#718187; background:#f5f9f9; font-size:9px; font-weight:800; letter-spacing:.08em; text-align:left; text-transform:uppercase; }
    .products-table th:nth-child(n+2),.products-table td:nth-child(n+2) { text-align:right; }
    .products-table td { padding:13px 14px; border-top:1px solid #e9eff0; color:#43535a; font-size:12px; }
    .products-table td:first-child { color:var(--navy); font-weight:700; }
    .empty-products { padding:25px!important; color:var(--muted)!important; text-align:center!important; }

    .totals { width:min(100%,360px); margin-left:auto; }
    .total-row { display:flex; justify-content:space-between; gap:20px; padding:9px 0; color:var(--muted); font-size:13px; }
    .total-row strong { color:var(--navy); }
    .grand-total { margin-top:8px; padding:17px 18px; border-radius:14px; color:#fff; background:var(--navy); }
    .grand-total span { color:#c5d6da; font-weight:700; }
    .grand-total strong { color:var(--gold); font:700 25px 'Playfair Display',serif; }

    .receipt-footer { display:flex; align-items:center; justify-content:space-between; gap:15px; margin-top:28px; padding-top:21px; border-top:1px dashed #cbd9dc; color:#7b8c91; font-size:10px; }
    .receipt-footer strong { color:var(--navy); }

    body.dark-mode .receipt-page { --paper:#0b3447; --line:#28505f; color:#eaf3f5; }
    body.dark-mode .back-link { color:var(--gold); }
    body.dark-mode .action-button { color:#eaf3f5; border-color:#315867; background:#0c3a4e; }
    body.dark-mode .status-line { border-color:#28505f; }
    body.dark-mode .info-box { border-color:#28505f; background:#092b3c; }
    body.dark-mode .info-box strong,body.dark-mode .section-title h2,body.dark-mode .products-table td:first-child,body.dark-mode .total-row strong,body.dark-mode .receipt-footer strong { color:#fff; }
    body.dark-mode .products-table th { color:#b7c8cd; background:#082838; }
    body.dark-mode .products-table td { color:#d5e2e5; border-color:#28505f; }

    @media(max-width:680px) {
        .receipt-actions,.status-line { align-items:flex-start; flex-direction:column; }
        .action-group { width:100%; }
        .action-button { flex:1; }
        .receipt-header { align-items:flex-start; flex-direction:column; padding:25px; }
        .receipt-number { text-align:left; }
        .receipt-body { padding:24px 20px; }
        .info-grid { grid-template-columns:1fr; }
        .receipt-footer { align-items:flex-start; flex-direction:column; }
    }

    @media print {
        body { background:#fff!important; }
        .sidebar,.topbar-layout,.receipt-actions { display:none!important; }
        .page-shell { margin:0!important; }
        .page-content { padding:0!important; }
        .receipt-page { max-width:none; }
        .receipt { border:0; box-shadow:none; }
    }
</style>
@endpush

@section('content')
@php
    $pedido = $pago->pedido;
    $cliente = $pedido?->cliente;
    $detalles = $pedido?->detalles ?? collect();
    $cantidadTotal = $detalles->sum('cantidad');
@endphp

<div class="receipt-page">
    <div class="receipt-actions">
        <a class="back-link" href="{{ route('pagos.index') }}"><i class="fa-solid fa-arrow-left"></i> Volver al historial</a>
        <div class="action-group">
            <button class="action-button" type="button" onclick="window.print()"><i class="fa-solid fa-print"></i> Imprimir</button>
            <form method="POST" action="{{ route('pagos.destroy',$pago) }}" onsubmit="return confirm('¿Deseas anular este pago? El pedido volverá a pendiente.')">
                @csrf
                @method('DELETE')
                <button class="action-button danger-button" type="submit"><i class="fa-solid fa-ban"></i> Anular pago</button>
            </form>
        </div>
    </div>

    <article class="receipt">
        <header class="receipt-header">
            <div class="receipt-brand">
                <span class="brand-icon"><i class="fa-solid fa-fish-fins"></i></span>
                <span><strong>EL OLÍMPICO</strong><small>CEVICHERÍA PERUANA</small></span>
            </div>
            <div class="receipt-number"><small>Comprobante de pago</small><strong>#PG-{{ str_pad($pago->id,4,'0',STR_PAD_LEFT) }}</strong></div>
        </header>

        <div class="receipt-body">
            <div class="status-line">
                <span class="paid-badge"><i class="fa-solid fa-circle-check"></i> PAGO PROCESADO</span>
                <time datetime="{{ $pago->created_at?->toIso8601String() }}">{{ $pago->created_at?->format('d/m/Y · h:i A') ?? 'Fecha no disponible' }}</time>
            </div>

            <section class="info-grid">
                <div class="info-box"><small>Cliente</small><strong>{{ $cliente?->nombre ?? 'Cliente general' }}</strong><span>{{ $cliente?->documento ?? $cliente?->dni ?? 'Sin documento' }}</span></div>
                <div class="info-box"><small>Pedido</small><strong>#P-{{ str_pad($pago->pedido_id,4,'0',STR_PAD_LEFT) }}</strong><span>{{ ucfirst(str_replace('_',' ',$pedido?->estado ?? 'completado')) }}</span></div>
                <div class="info-box"><small>Método de pago</small><strong>{{ ucfirst($pago->metodo) }}</strong><span>Operación confirmada</span></div>
                <div class="info-box"><small>Mesa</small><strong>{{ $pedido?->mesa?->numero ? 'Mesa '.$pedido->mesa->numero : 'Sin mesa' }}</strong><span>{{ $pedido?->tipo ?? 'Consumo registrado' }}</span></div>
                <div class="info-box"><small>Venta generada</small><strong>{{ $pago->venta ? '#V-'.str_pad($pago->venta->id,4,'0',STR_PAD_LEFT) : 'No registrada' }}</strong><span>Relacionada con este pago</span></div>
                <div class="info-box"><small>Productos</small><strong>{{ $cantidadTotal }} unidades</strong><span>{{ $detalles->count() }} productos diferentes</span></div>
            </section>

            <div class="section-title"><h2>Detalle del consumo</h2><span>{{ $detalles->count() }} registros</span></div>
            <div class="products-table-wrap">
                <table class="products-table">
                    <thead><tr><th>Producto</th><th>Precio unitario</th><th>Cantidad</th><th>Subtotal</th></tr></thead>
                    <tbody>
                        @forelse($detalles as $detalle)
                            @php
                                $precio = $detalle->precio_unitario ?? $detalle->precio ?? $detalle->producto?->precio ?? 0;
                                $subtotal = $detalle->subtotal ?? ($precio * $detalle->cantidad);
                            @endphp
                            <tr><td>{{ $detalle->producto?->nombre ?? 'Producto eliminado' }}</td><td>S/ {{ number_format($precio,2) }}</td><td>{{ $detalle->cantidad }}</td><td>S/ {{ number_format($subtotal,2) }}</td></tr>
                        @empty
                            <tr><td class="empty-products" colspan="4">No se encontraron productos asociados al pedido.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="totals">
                <div class="total-row"><span>Subtotal</span><strong>S/ {{ number_format($pago->monto,2) }}</strong></div>
                <div class="total-row"><span>Descuento</span><strong>S/ 0.00</strong></div>
                <div class="total-row grand-total"><span>Total pagado</span><strong>S/ {{ number_format($pago->monto,2) }}</strong></div>
            </div>

            <footer class="receipt-footer"><span><strong>Gracias por elegir El Olímpico.</strong><br>Frescura y sabor peruano en cada mesa.</span><span>Este comprobante fue generado por el sistema administrativo.</span></footer>
        </div>
    </article>
</div>
@endsection
