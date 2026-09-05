@extends('layouts.app')

@section('title', 'Detalle de venta')
@section('page-title', 'Comprobante de venta')

@push('styles')
<style>
    .sale-detail {
        --navy:#062b3d; --navy-dark:#031b27; --sea:#00a7a7;
        --gold:#f6c453; --paper:#fff; --muted:#68757d; --line:#dfe9eb;
        max-width:940px; margin:auto; color:#18242b;
    }
    .page-actions { display:flex; align-items:center; justify-content:space-between; gap:14px; margin-bottom:20px; }
    .back-link { display:inline-flex; align-items:center; gap:8px; color:var(--navy); font-size:13px; font-weight:800; text-decoration:none; }
    .back-link:hover { color:var(--sea); }
    .actions { display:flex; gap:8px; }
    .action-button { display:inline-flex; min-height:42px; align-items:center; justify-content:center; gap:8px; padding:0 16px; border:1px solid var(--line); border-radius:999px; color:var(--navy); background:#fff; font:800 12px 'DM Sans',sans-serif; cursor:pointer; text-decoration:none; transition:.18s; }
    .action-button:hover { color:#fff; border-color:var(--sea); background:var(--sea); }
    .delete-button { color:#ad3732; border-color:#edc5c2; }
    .delete-button:hover { border-color:#b63a35; background:#b63a35; }

    .sale-document { overflow:hidden; border:1px solid var(--line); border-radius:23px; background:var(--paper); box-shadow:0 16px 45px rgba(6,43,61,.10); }
    .document-header { position:relative; display:flex; align-items:center; justify-content:space-between; gap:25px; overflow:hidden; padding:31px 35px; color:#fff; background:linear-gradient(135deg,var(--navy-dark),#0b5875); }
    .document-header::after { content:'VENTA'; position:absolute; right:-15px; bottom:-36px; color:rgba(255,255,255,.055); font:800 100px 'Playfair Display',serif; transform:rotate(-5deg); }
    .brand { position:relative; z-index:1; display:flex; align-items:center; gap:12px; }
    .brand-icon { display:grid; width:50px; height:50px; flex:0 0 50px; place-items:center; border-radius:50%; color:var(--navy); background:var(--gold); font-size:21px; }
    .brand strong { display:block; font:700 23px/1 'Playfair Display',serif; }
    .brand small { display:block; margin-top:5px; color:#bfd1d6; font-size:9px; letter-spacing:2.3px; }
    .sale-number { position:relative; z-index:1; text-align:right; }
    .sale-number small { display:block; margin-bottom:4px; color:#bfd1d6; font-size:10px; letter-spacing:.1em; text-transform:uppercase; }
    .sale-number strong { color:var(--gold); font:700 25px 'Playfair Display',serif; }

    .document-body { padding:31px 35px; }
    .document-status { display:flex; align-items:center; justify-content:space-between; gap:15px; margin-bottom:25px; padding-bottom:22px; border-bottom:1px solid var(--line); }
    .completed-badge { display:inline-flex; align-items:center; gap:7px; padding:7px 11px; border-radius:999px; color:#087451; background:#eafaf4; font-size:11px; font-weight:800; }
    .document-status time { color:var(--muted); font-size:12px; }

    .information-grid { display:grid; grid-template-columns:repeat(3,1fr); gap:13px; margin-bottom:28px; }
    .information-box { padding:15px; border:1px solid #e4ecee; border-radius:14px; background:#fafcfc; }
    .information-box small,.information-box strong,.information-box span { display:block; }
    .information-box small { margin-bottom:6px; color:#77888e; font-size:9px; font-weight:800; letter-spacing:.08em; text-transform:uppercase; }
    .information-box strong { overflow:hidden; color:var(--navy); font-size:13px; text-overflow:ellipsis; white-space:nowrap; }
    .information-box span { margin-top:4px; color:var(--muted); font-size:11px; }

    .section-heading { display:flex; align-items:center; justify-content:space-between; gap:15px; margin-bottom:12px; }
    .section-heading h2 { margin:0; color:var(--navy); font:700 20px 'Playfair Display',serif; }
    .section-heading span { color:var(--muted); font-size:11px; }
    .table-wrapper { overflow-x:auto; margin-bottom:25px; border:1px solid var(--line); border-radius:15px; }
    .products-table { width:100%; min-width:610px; border-collapse:collapse; }
    .products-table th { padding:11px 14px; color:#718187; background:#f5f9f9; font-size:9px; font-weight:800; letter-spacing:.08em; text-align:left; text-transform:uppercase; }
    .products-table th:nth-child(n+2),.products-table td:nth-child(n+2) { text-align:right; }
    .products-table td { padding:13px 14px; border-top:1px solid #e9eff0; color:#43535a; font-size:12px; }
    .products-table td:first-child { color:var(--navy); font-weight:700; }
    .empty-products { padding:25px!important; color:var(--muted)!important; text-align:center!important; }

    .payment-summary { display:grid; grid-template-columns:1fr minmax(280px,360px); gap:30px; align-items:end; }
    .payment-note { padding:17px; border-radius:14px; color:#4f6269; background:#f3f8f8; font-size:12px; line-height:1.65; }
    .payment-note i { margin-right:7px; color:var(--sea); }
    .totals { width:100%; }
    .total-row { display:flex; justify-content:space-between; gap:20px; padding:9px 0; color:var(--muted); font-size:13px; }
    .total-row strong { color:var(--navy); }
    .grand-total { margin-top:8px; padding:17px 18px; border-radius:14px; color:#fff; background:var(--navy); }
    .grand-total span { color:#c5d6da; font-weight:700; }
    .grand-total strong { color:var(--gold); font:700 25px 'Playfair Display',serif; }
    .document-footer { display:flex; align-items:center; justify-content:space-between; gap:15px; margin-top:28px; padding-top:21px; border-top:1px dashed #cbd9dc; color:#7b8c91; font-size:10px; }
    .document-footer strong { color:var(--navy); }

    body.dark-mode .sale-detail { --paper:#0b3447; --line:#28505f; color:#eaf3f5; }
    body.dark-mode .back-link { color:var(--gold); }
    body.dark-mode .action-button { color:#eaf3f5; border-color:#315867; background:#0c3a4e; }
    body.dark-mode .information-box,body.dark-mode .payment-note { color:#d3e0e3; border-color:#28505f; background:#092b3c; }
    body.dark-mode .information-box strong,body.dark-mode .section-heading h2,body.dark-mode .products-table td:first-child,body.dark-mode .total-row strong,body.dark-mode .document-footer strong { color:#fff; }
    body.dark-mode .products-table th { color:#b7c8cd; background:#082838; }
    body.dark-mode .products-table td { color:#d5e2e5; border-color:#28505f; }

    @media(max-width:700px) {
        .page-actions,.document-status { align-items:flex-start; flex-direction:column; }
        .actions { width:100%; }
        .action-button { flex:1; }
        .document-header { align-items:flex-start; flex-direction:column; padding:25px; }
        .sale-number { text-align:left; }
        .document-body { padding:24px 20px; }
        .information-grid,.payment-summary { grid-template-columns:1fr; }
        .document-footer { align-items:flex-start; flex-direction:column; }
    }

    @media print {
        body { background:#fff!important; }
        .sidebar,.topbar-layout,.page-actions { display:none!important; }
        .page-shell { margin:0!important; }
        .page-content { padding:0!important; }
        .sale-detail { max-width:none; }
        .sale-document { border:0; box-shadow:none; }
    }
</style>
@endpush

@section('content')
@php
    $pedido = $venta->pedido;
    $cliente = $pedido?->cliente;
    $mesa = $pedido?->mesa;
    $usuario = $pedido?->usuario;
    $pago = $venta->pago;
    $detalles = $pedido?->detalles ?? collect();
    $cantidadTotal = $detalles->sum('cantidad');
@endphp

<div class="sale-detail">
    <div class="page-actions">
        <a class="back-link" href="{{ route('ventas.index') }}"><i class="fa-solid fa-arrow-left"></i> Volver a ventas</a>
        <div class="actions">
            <button class="action-button" type="button" onclick="window.print()"><i class="fa-solid fa-print"></i> Imprimir</button>
            <form method="POST" action="{{ route('ventas.destroy',$venta) }}" onsubmit="return confirm('¿Seguro que deseas eliminar este registro de venta?')">
                @csrf
                @method('DELETE')
                <button class="action-button delete-button" type="submit"><i class="fa-regular fa-trash-can"></i> Eliminar</button>
            </form>
        </div>
    </div>

    <article class="sale-document">
        <header class="document-header">
            <div class="brand"><span class="brand-icon"><i class="fa-solid fa-fish-fins"></i></span><span><strong>EL OLÍMPICO</strong><small>CEVICHERÍA PERUANA</small></span></div>
            <div class="sale-number"><small>Comprobante de venta</small><strong>#V-{{ str_pad($venta->id,4,'0',STR_PAD_LEFT) }}</strong></div>
        </header>

        <div class="document-body">
            <div class="document-status"><span class="completed-badge"><i class="fa-solid fa-circle-check"></i> VENTA COMPLETADA</span><time datetime="{{ $venta->fecha?->toIso8601String() }}">{{ $venta->fecha?->format('d/m/Y · h:i A') ?? 'Fecha no disponible' }}</time></div>

            <section class="information-grid">
                <div class="information-box"><small>Cliente</small><strong>{{ $cliente?->nombre ?? 'Cliente general' }}</strong><span>{{ $cliente?->documento ?? $cliente?->dni ?? 'Sin documento' }}</span></div>
                <div class="information-box"><small>Pedido</small><strong>#P-{{ str_pad($venta->pedido_id,4,'0',STR_PAD_LEFT) }}</strong><span>{{ ucfirst(str_replace('_',' ',$pedido?->estado ?? 'completado')) }}</span></div>
                <div class="information-box"><small>Mesa</small><strong>{{ $mesa?->numero ? 'Mesa '.$mesa->numero : 'Sin mesa' }}</strong><span>{{ $pedido?->tipo ?? 'Consumo registrado' }}</span></div>
                <div class="information-box"><small>Pago relacionado</small><strong>{{ $pago ? '#PG-'.str_pad($pago->id,4,'0',STR_PAD_LEFT) : 'No encontrado' }}</strong><span>{{ ucfirst($pago?->metodo ?? 'No especificado') }}</span></div>
                <div class="information-box"><small>Atendido por</small><strong>{{ $usuario?->name ?? 'No registrado' }}</strong><span>Usuario responsable</span></div>
                <div class="information-box"><small>Productos vendidos</small><strong>{{ $cantidadTotal }} unidades</strong><span>{{ $detalles->count() }} productos diferentes</span></div>
            </section>

            <div class="section-heading"><h2>Detalle de productos</h2><span>{{ $detalles->count() }} registros</span></div>
            <div class="table-wrapper">
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

            <div class="payment-summary">
                <div class="payment-note"><i class="fa-solid fa-circle-info"></i>Esta venta fue generada automáticamente al procesar el pago del pedido. Método utilizado: <strong>{{ ucfirst($pago?->metodo ?? 'No especificado') }}</strong>.</div>
                <div class="totals"><div class="total-row"><span>Subtotal</span><strong>S/ {{ number_format($venta->monto_total,2) }}</strong></div><div class="total-row"><span>Descuento</span><strong>S/ 0.00</strong></div><div class="total-row grand-total"><span>Total vendido</span><strong>S/ {{ number_format($venta->monto_total,2) }}</strong></div></div>
            </div>

            <footer class="document-footer"><span><strong>Gracias por elegir El Olímpico.</strong><br>Frescura y sabor peruano en cada mesa.</span><span>Registro generado por el sistema administrativo.</span></footer>
        </div>
    </article>
</div>
@endsection
