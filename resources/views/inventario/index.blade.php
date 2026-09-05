@extends('layouts.app')

@section('title', 'Gestión de Inventario')
@section('page-title', 'Inventario')

@push('styles')
<style>
    .inventario-page { --navy:#062b3d; --sea:#00a7a7; --gold:#f6c453; --gold-dark:#e0b043; --paper:#fff; --muted:#68757d; --line:#dfe9eb; color:#18242b; }
    .inventario-header { display:flex; align-items:flex-end; justify-content:space-between; gap:20px; margin-bottom:24px; }
    .inventario-kicker { display:block; margin-bottom:7px; color:var(--sea); font-size:11px; font-weight:800; letter-spacing:.16em; text-transform:uppercase; }
    .inventario-header h1 { margin:0 0 7px; color:var(--navy); font:700 clamp(30px,4vw,43px)/1.08 'Playfair Display',serif; }
    .inventario-header p { margin:0; color:var(--muted); }
    .primary-button { display:inline-flex; align-items:center; justify-content:center; gap:9px; padding:12px 18px; border-radius:999px; color:var(--navy); background:var(--gold); box-shadow:0 10px 22px rgba(246,196,83,.24); font-size:13px; font-weight:800; text-decoration:none; transition:.2s; }
    .primary-button:hover { color:var(--navy); background:var(--gold-dark); transform:translateY(-2px); }

    .flash { display:flex; gap:10px; margin-bottom:20px; padding:14px 16px; border-radius:13px; font-size:13px; }
    .flash-success { border:1px solid #a7e5d2; color:#087451; background:#effdf8; }
    .flash-info { border:1px solid #b8e4ea; color:#0b5875; background:#effcfd; }
    .flash-danger { border:1px solid #f8c4c4; color:#a32a2a; background:#fdf2f2; }
    
    .inventario-stats { display:grid; grid-template-columns:repeat(3,1fr); gap:16px; margin-bottom:22px; }
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
    .inventario-table { width:100%; min-width:800px; border-collapse:collapse; }
    .inventario-table th { padding:13px 16px; color:#718187; background:#f7fafb; font-size:10px; font-weight:800; letter-spacing:.08em; text-align:left; text-transform:uppercase; white-space:nowrap; }
    .inventario-table td { padding:15px 16px; border-top:1px solid #ebf0f1; color:#3e4d53; font-size:13px; vertical-align:middle; }
    .inventario-table tbody tr:hover { background:#fbfdfd; }
    
    .insumo-info { display:flex; align-items:center; gap:12px; }
    .insumo-icon-box { width:40px; height:40px; border-radius:10px; display:grid; place-items:center; background:#e7f7f7; color:var(--sea); border:1px solid var(--line); font-size:16px; }
    .main-data { display:block; color:var(--navy); font-weight:700; }
    .sub-data { display:block; margin-top:2px; color:#89979c; font-size:11px; }

    .stock-badge { display:inline-flex; align-items:center; gap:6px; padding:5px 10px; border-radius:999px; font-size:11px; font-weight:700; color:#0b5875; background:#e8f6f8; white-space:nowrap; }
    .status-badge { display:inline-flex; align-items:center; gap:5px; padding:4px 9px; border-radius:999px; font-size:11px; font-weight:700; }
    .status-active { color:#087451; background:#effdf8; border:1px solid #a7e5d2; }
    .status-warning { color:#a32a2a; background:#fdf2f2; border:1px solid #f8c4c4; }

    .actions { display:flex; gap:7px; }
    .action-button { display:grid; width:34px; height:34px; place-items:center; border:1px solid var(--line); border-radius:10px; color:var(--navy); background:white; cursor:pointer; text-decoration:none; transition:.15s; }
    .action-button:hover { color:white; border-color:var(--sea); background:var(--sea); }
    .edit-button:hover { border-color:var(--gold-dark); background:var(--gold-dark); color:var(--navy); }
    .delete-button { color:#b53b35; }
    .delete-button:hover { border-color:#b53b35; background:#b53b35; color:white; }
    
    .empty-state { padding:55px 20px !important; text-align:center; }
    .empty-state i { display:grid; width:62px; height:62px; margin:0 auto 13px; place-items:center; border-radius:50%; color:var(--sea); background:#e7f7f7; font-size:24px; }
    .empty-state strong { display:block; margin-bottom:5px; color:var(--navy); font-size:16px; }
    .empty-state span { color:var(--muted); }

    body.dark-mode .inventario-page { --paper:#0b3447; --line:#28505f; color:#eaf3f5; }
    body.dark-mode .inventario-header h1, body.dark-mode .stat-card strong, body.dark-mode .history-toolbar h2,
    body.dark-mode .main-data, body.dark-mode .empty-state strong { color:#fff; }
    body.dark-mode .inventario-table th { color:#b9cbd0; background:#082838; }
    body.dark-mode .inventario-table td { border-color:#264b5a; color:#d9e5e8; }
    body.dark-mode .inventario-table tbody tr:hover { background:#0e3a4e; }
    body.dark-mode .action-button, body.dark-mode .search-box input { color:#e7f1f3; border-color:#315565; background:#0d3a4d; }

    @media(max-width:800px) { .inventario-stats{grid-template-columns:1fr} }
    @media(max-width:650px) { .inventario-header,.history-toolbar{align-items:stretch;flex-direction:column}.primary-button,.search-box{width:100%}.stat-card{padding:16px}.stat-card strong{font-size:18px} }
</style>
@endpush

@section('content')
@php
    $totalInsumos = $insumos->count();
    $bajoStock = $insumos->filter(fn($i) => ($i->stock ?? $i->cantidad) <= $i->stock_minimo);
    $criticosCount = $bajoStock->count();
    $normalesCount = $totalInsumos - $criticosCount;
@endphp

<div class="inventario-page">
    <header class="inventario-header">
        <div>
            <span class="inventario-kicker">Control de Stock</span>
            <h1>Inventario e Insumos</h1>
            <p>Controla las existencias, unidades de medida y alertas de stock crítico de insumos.</p>
        </div>
        <a class="primary-button" href="{{ route('inventario.create') }}">
            <i class="fa-solid fa-plus"></i> Nuevo insumo
        </a>
    </header>

    @if(session('success'))
        <div class="flash flash-success"><i class="fa-solid fa-circle-check"></i><span>{{ session('success') }}</span></div>
    @endif
    @if(session('info'))
        <div class="flash flash-info"><i class="fa-solid fa-circle-info"></i><span>{{ session('info') }}</span></div>
    @endif

    @if($criticosCount > 0)
        <div class="flash flash-danger">
            <i class="fa-solid fa-triangle-exclamation"></i>
            <span><strong>Alerta de Stock Crítico:</strong> Hay {{ $criticosCount }} insumo(s) por debajo del stock mínimo recomendado: {{ $bajoStock->pluck('nombre_insumo')->join(', ') }}.</span>
        </div>
    @endif

    <section class="inventario-stats">
        <article class="stat-card">
            <span class="stat-icon"><i class="fa-solid fa-boxes-stacked"></i></span>
            <small>Total Insumos</small>
            <strong>{{ $totalInsumos }}</strong>
        </article>
        <article class="stat-card">
            <span class="stat-icon"><i class="fa-solid fa-circle-check"></i></span>
            <small>Stock Normal</small>
            <strong>{{ $normalesCount }}</strong>
        </article>
        <article class="stat-card">
            <span class="stat-icon" style="color: #b53b35; background: #fdf2f2;"><i class="fa-solid fa-triangle-exclamation"></i></span>
            <small>Stock Crítico</small>
            <strong style="color: #b53b35;">{{ $criticosCount }}</strong>
        </article>
    </section>

    <section class="history-card">
        <div class="history-toolbar">
            <h2>Insumos registrados <span class="record-count">{{ $totalInsumos }}</span></h2>
            <label class="search-box" for="insumoSearch">
                <i class="fa-solid fa-magnifying-glass"></i>
                <input id="insumoSearch" type="search" placeholder="Buscar insumo...">
            </label>
        </div>

        <div class="table-scroll">
            <table class="inventario-table">
                <thead>
                    <tr>
                        <th>Insumo</th>
                        <th>Stock Actual</th>
                        <th>Stock Mínimo</th>
                        <th>Estado Stock</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($insumos as $insumo)
                        @php
                            $currentStock = $insumo->stock ?? $insumo->cantidad;
                            $esCritico = $currentStock <= $insumo->stock_minimo;
                        @endphp
                        <tr class="insumo-row">
                            <td>
                                <div class="insumo-info">
                                    <div class="insumo-icon-box">
                                        <i class="fa-solid fa-box-archive"></i>
                                    </div>
                                    <div>
                                        <span class="main-data">{{ $insumo->nombre_insumo ?? $insumo->nombre }}</span>
                                        <span class="sub-data">
                                            Producto vinculado: {{ $insumo->producto->nombre ?? 'Sin vincular' }}
                                        </span>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="stock-badge">
                                    <i class="fa-solid fa-cubes"></i>
                                    {{ number_format($currentStock, 2) }} {{ $insumo->unidad_medida }}
                                </span>
                            </td>
                            <td>
                                <span class="main-data" style="font-weight: 500;">
                                    {{ number_format($insumo->stock_minimo, 2) }} {{ $insumo->unidad_medida }}
                                </span>
                            </td>
                            <td>
                                @if($esCritico)
                                    <span class="status-badge status-warning"><i class="fa-solid fa-circle"></i> Stock Bajo</span>
                                @else
                                    <span class="status-badge status-active"><i class="fa-solid fa-circle"></i> Óptimo</span>
                                @endif
                            </td>
                            <td>
                                <div class="actions">
                                    <a class="action-button" href="{{ route('inventario.show', $insumo) }}" title="Ver detalles">
                                        <i class="fa-regular fa-eye"></i>
                                    </a>
                                    <a class="action-button edit-button" href="{{ route('inventario.edit', $insumo) }}" title="Editar insumo">
                                        <i class="fa-solid fa-pen"></i>
                                    </a>
                                    <form method="POST" action="{{ route('inventario.destroy', $insumo) }}" onsubmit="return confirm('¿Eliminar este insumo del inventario?')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="action-button delete-button" type="submit" title="Eliminar insumo">
                                            <i class="fa-solid fa-trash-can"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td class="empty-state" colspan="5">
                                <i class="fa-solid fa-boxes-stacked"></i>
                                <strong>No existen insumos en inventario</strong>
                                <span>Crea el primer insumo para llevar el control de tus stock.</span>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>
</div>
@endsection

@push('scripts')
<script>
    const insumoSearch = document.getElementById('insumoSearch');
    const insumoRows = document.querySelectorAll('.insumo-row');
    
    insumoSearch?.addEventListener('input', event => {
        const term = event.target.value.toLowerCase().trim();
        insumoRows.forEach(row => {
            row.style.display = row.textContent.toLowerCase().includes(term) ? '' : 'none';
        });
    });
</script>
@endpush