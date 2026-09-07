@extends('layouts.app')

@section('title', 'Inventario de Insumos')
@section('page-title', 'Inventario')

@push('styles')
<style>
    .inv-page { 
        --navy: #062b3d; 
        --sea: #00a7a7; 
        --gold: #f6c453; 
        --gold-dark: #e0b043; 
        --paper: #ffffff; 
        --muted: #68757d; 
        --line: #dfe9eb; 
        --bg-subtle: #f8fafc;
        color: #18242b; 
    }

    /* Header y Botón Principal */
    .inv-header { display: flex; align-items: flex-end; justify-content: space-between; gap: 16px; margin-bottom: 24px; flex-wrap: wrap; }
    .inv-kicker { display: block; margin-bottom: 4px; color: var(--sea); font-size: 11px; font-weight: 800; letter-spacing: .16em; text-transform: uppercase; }
    .inv-header h1 { margin: 0; color: var(--navy); font: 700 clamp(24px, 3vw, 32px)/1.1 'Playfair Display', serif; }
    
    .primary-button { display: inline-flex; align-items: center; gap: 8px; padding: 10px 22px; border-radius: 999px; color: var(--navy); background: var(--gold); font-size: 13px; font-weight: 800; text-decoration: none; transition: all .2s ease; border: none; box-shadow: 0 4px 12px rgba(246,196,83,.3); }
    .primary-button:hover { background: var(--gold-dark); transform: translateY(-2px); box-shadow: 0 6px 16px rgba(224,176,67,.4); }

    /* Tarjetas KPI */
    .inv-stats { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 16px; margin-bottom: 24px; }
    .stat-card { background: var(--paper); border: 1px solid var(--line); border-radius: 16px; padding: 16px 20px; display: flex; align-items: center; gap: 16px; box-shadow: 0 4px 12px rgba(6,43,61,.03); }
    .stat-icon { width: 44px; height: 44px; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 18px; flex-shrink: 0; }
    .stat-icon.total { background: #e0f2fe; color: #0369a1; }
    .stat-icon.low { background: #fee2e2; color: #dc2626; }
    .stat-icon.ok { background: #dcfce7; color: #15803d; }
    .stat-data { display: flex; flex-direction: column; }
    .stat-value { font-size: 20px; font-weight: 800; color: var(--navy); line-height: 1.2; }
    .stat-label { font-size: 12px; color: var(--muted); font-weight: 600; }

    /* Toolbar y Filtros */
    .inv-toolbar { display: flex; justify-content: space-between; align-items: center; gap: 12px; margin-bottom: 16px; flex-wrap: wrap; }
    .search-box { position: relative; flex: 1; min-width: 240px; }
    .search-box i { position: absolute; left: 14px; top: 50%; transform: translateY(-50%); color: var(--muted); font-size: 14px; }
    .search-input { width: 100%; height: 40px; padding: 0 14px 0 38px; border: 1px solid var(--line); border-radius: 10px; font-size: 13px; outline: none; transition: .2s; background: var(--paper); }
    .search-input:focus { border-color: var(--sea); box-shadow: 0 0 0 3px rgba(0,167,167,.12); }
    .filter-select { height: 40px; padding: 0 14px; border: 1px solid var(--line); border-radius: 10px; font-size: 13px; outline: none; background: var(--paper); color: var(--navy); cursor: pointer; }

    /* Tabla */
    .inv-card { border: 1px solid var(--line); border-radius: 16px; background: var(--paper); box-shadow: 0 12px 32px rgba(6,43,61,.05); overflow: hidden; }
    .table-responsive { width: 100%; overflow-x: auto; }
    .inv-table { width: 100%; border-collapse: collapse; text-align: left; font-size: 14px; white-space: nowrap; }
    .inv-table th { padding: 14px 18px; background: var(--bg-subtle); color: var(--navy); font-size: 11px; font-weight: 800; text-transform: uppercase; letter-spacing: .08em; border-bottom: 1px solid var(--line); }
    .inv-table td { padding: 14px 18px; border-bottom: 1px solid #edf2f4; color: #2c3e50; vertical-align: middle; }
    .inv-table tr:hover td { background: #fafcfe; }

    /* Badges y Formulario Rápido */
    .badge-stock { display: inline-flex; align-items: center; gap: 6px; padding: 4px 10px; border-radius: 999px; font-size: 11px; font-weight: 700; }
    .stock-ok { background: #effdf8; color: #087451; border: 1px solid #a7e5d2; }
    .stock-low { background: #fdf2f2; color: #a32a2a; border: 1px solid #f8c4c4; }

    .stock-form { display: flex; align-items: center; gap: 6px; }
    .stock-input { width: 85px; height: 34px; padding: 0 8px; border: 1px solid #cfdddf; border-radius: 8px; font-size: 13px; font-weight: 600; outline: none; }
    .stock-btn { height: 34px; width: 34px; border: none; border-radius: 8px; background: var(--sea); color: #fff; display: inline-flex; align-items: center; justify-content: center; font-size: 12px; cursor: pointer; transition: .2s; }
    .stock-btn:hover { background: #008b8b; }

    /* Acciones */
    .actions-cell { display: flex; align-items: center; justify-content: flex-end; gap: 6px; }
    .action-btn { display: inline-flex; align-items: center; justify-content: center; width: 34px; height: 34px; border-radius: 8px; border: 1px solid var(--line); background: #fff; color: var(--navy); font-size: 13px; text-decoration: none; transition: .2s; }
    .action-btn:hover { background: #f4f8f9; }
    .btn-edit { color: #00a7a7; border-color: #bce5e5; }
    .btn-delete { color: #b53b35; border-color: #f4c2c0; }

    .alert-success { margin-bottom: 20px; padding: 14px 18px; border-radius: 12px; background: #effdf8; color: #087451; border: 1px solid #a7e5d2; font-size: 13px; font-weight: 600; }

    /* Dark Mode */
    body.dark-mode .inv-card, body.dark-mode .stat-card { background: #0b3447; border-color: #28505f; }
    body.dark-mode .inv-table th { background: #0d3a4d; color: #fff; border-color: #28505f; }
    body.dark-mode .inv-table td { border-color: #1e4758; color: #e1e8ed; }
    body.dark-mode .stock-input, body.dark-mode .search-input, body.dark-mode .filter-select { background: #0d3a4d; border-color: #315565; color: #fff; }
    body.dark-mode .action-btn { background: #0d3a4d; border-color: #315565; color: #fff; }
    body.dark-mode .stat-value, body.dark-mode .inv-header h1 { color: #fff; }
</style>
@endpush

@section('content')
<div class="inv-page">
    <header class="inv-header">
        <div>
            <span class="inv-kicker">Almacén y Cocina</span>
            <h1>Inventario de Insumos</h1>
        </div>
        <a class="primary-button" href="{{ route('inventario.create') }}">
            <i class="fa-solid fa-plus"></i> Nuevo Insumo
        </a>
    </header>

    @if(session('success'))
        <div class="alert-success">
            <i class="fa-solid fa-circle-check"></i> {{ session('success') }}
        </div>
    @endif

    <div class="inv-stats">
        <div class="stat-card">
            <div class="stat-icon total"><i class="fa-solid fa-boxes-stacked"></i></div>
            <div class="stat-data">
                <span class="stat-value">{{ $insumos->count() }}</span>
                <span class="stat-label">Total Insumos</span>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon low"><i class="fa-solid fa-triangle-exclamation"></i></div>
            <div class="stat-data">
                <span class="stat-value">{{ $insumos->filter(fn($i) => $i->stock <= $i->stock_minimo)->count() }}</span>
                <span class="stat-label">Stock Bajo</span>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon ok"><i class="fa-solid fa-circle-check"></i></div>
            <div class="stat-data">
                <span class="stat-value">{{ $insumos->filter(fn($i) => $i->stock > $i->stock_minimo)->count() }}</span>
                <span class="stat-label">Stock Normal</span>
            </div>
        </div>
    </div>

    <div class="inv-toolbar">
        <div class="search-box">
            <i class="fa-solid fa-magnifying-glass"></i>
            <input type="text" id="searchInput" class="search-input" placeholder="Buscar insumo..." onkeyup="filterTable()">
        </div>
        <select id="statusFilter" class="filter-select" onchange="filterTable()">
            <option value="all">Todos los estados</option>
            <option value="low">Stock Bajo</option>
            <option value="ok">Normal</option>
        </select>
    </div>

    <div class="inv-card">
        <div class="table-responsive">
            <table class="inv-table" id="inventoryTable">
                <thead>
                    <tr>
                        <th>Insumo</th>
                        <th>Producto Asociado</th>
                        <th>Stock Actual</th>
                        <th>Stock Mínimo</th>
                        <th>Precio Unitario</th>
                        <th>Estado</th>
                        <th>Ajuste Rápido</th>
                        <th style="text-align:right;">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($insumos as $insumo)
                        @php $isLow = $insumo->stock <= $insumo->stock_minimo; @endphp
                        <tr data-status="{{ $isLow ? 'low' : 'ok' }}">
                            <td><strong>{{ $insumo->nombre_insumo }}</strong></td>
                            <td><span class="text-muted">{{ $insumo->producto->nombre ?? 'N/A' }}</span></td>
                            <td><strong>{{ number_format($insumo->stock, 2) }}</strong> <small>{{ $insumo->unidad_medida }}</small></td>
                            <td>{{ number_format($insumo->stock_minimo, 2) }} {{ $insumo->unidad_medida }}</td>
                            <td>S/ {{ number_format($insumo->precio_unitario ?? 0, 2) }}</td>
                            <td>
                                <span class="badge-stock {{ $isLow ? 'stock-low' : 'stock-ok' }}">
                                    <i class="fa-solid {{ $isLow ? 'fa-triangle-exclamation' : 'fa-check' }}"></i>
                                    {{ $isLow ? 'Stock Bajo' : 'Normal' }}
                                </span>
                            </td>
                            <td>
                                <form action="{{ route('inventario.actualizar-stock', $insumo->id) }}" method="POST" class="stock-form">
                                    @csrf
                                    @method('PATCH')
                                    <input type="number" step="0.01" name="stock" class="stock-input" value="{{ $insumo->stock }}" required min="0">
                                    <button type="submit" class="stock-btn"><i class="fa-solid fa-floppy-disk"></i></button>
                                </form>
                            </td>
                            <td style="text-align:right;">
                                <div class="actions-cell">
                                    <a href="{{ route('inventario.show', $insumo->id) }}" class="action-btn" title="Ver"><i class="fa-solid fa-eye"></i></a>
                                    <a href="{{ route('inventario.edit', $insumo->id) }}" class="action-btn btn-edit" title="Editar"><i class="fa-solid fa-pen-to-square"></i></a>
                                    <form action="{{ route('inventario.destroy', $insumo->id) }}" method="POST" onsubmit="return confirm('¿Eliminar insumo?');" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="action-btn btn-delete" title="Eliminar"><i class="fa-solid fa-trash"></i></button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr id="emptyRow">
                            <td colspan="8" style="text-align:center; padding:40px; color:var(--muted);">No hay insumos registrados.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function filterTable() {
        const searchInput = document.getElementById('searchInput').value.toLowerCase();
        const statusFilter = document.getElementById('statusFilter').value;
        const rows = document.querySelectorAll('#inventoryTable tbody tr:not(#emptyRow)');

        rows.forEach(row => {
            const text = row.innerText.toLowerCase();
            const status = row.getAttribute('data-status');
            const matchesSearch = text.includes(searchInput);
            const matchesStatus = (statusFilter === 'all') || (status === statusFilter);

            row.style.display = (matchesSearch && matchesStatus) ? '' : 'none';
        });
    }
</script>
@endpush