@extends('layouts.app')

@section('title', 'Inventario de Insumos')
@section('page-title', 'Inventario')

@push('styles')
<style>
    .inv-page { --navy:#062b3d; --sea:#00a7a7; --gold:#f6c453; --gold-dark:#e0b043; --paper:#fff; --muted:#68757d; --line:#dfe9eb; color:#18242b; }
    .inv-header { display:flex; align-items:flex-end; justify-content:space-between; gap:20px; margin-bottom:24px; }
    .inv-kicker { display:block; margin-bottom:7px; color:var(--sea); font-size:11px; font-weight:800; letter-spacing:.16em; text-transform:uppercase; }
    .inv-header h1 { margin:0 0 7px; color:var(--navy); font:700 clamp(26px,3vw,36px)/1.08 'Playfair Display',serif; }
    
    .primary-button { display:inline-flex; align-items:center; gap:8px; padding:10px 20px; border-radius:999px; color:var(--navy); background:var(--gold); font-size:13px; font-weight:800; text-decoration:none; transition:.2s; border:none; }
    .primary-button:hover { background:var(--gold-dark); transform:translateY(-1px); }

    .inv-card { border:1px solid var(--line); border-radius:20px; background:var(--paper); box-shadow:0 12px 32px rgba(6,43,61,.07); overflow:hidden; }
    .inv-table { width:100%; border-collapse:collapse; text-align:left; font-size:14px; }
    .inv-table th { padding:16px 20px; background:#f7fafb; color:var(--navy); font-size:11px; font-weight:800; text-transform:uppercase; letter-spacing:.08em; border-bottom:1px solid var(--line); }
    .inv-table td { padding:16px 20px; border-bottom:1px solid #edf2f4; color:#2c3e50; vertical-align:middle; }
    .inv-table tr:last-child td { border-bottom:none; }

    .badge-stock { display:inline-flex; align-items:center; gap:6px; padding:4px 10px; border-radius:999px; font-size:12px; font-weight:700; }
    .stock-ok { background:#effdf8; color:#087451; border:1px solid #a7e5d2; }
    .stock-low { background:#fdf2f2; color:#a32a2a; border:1px solid #f8c4c4; }

    .stock-form { display:flex; align-items:center; gap:6px; }
    .stock-input { width:80px; height:34px; padding:0 8px; border:1px solid #cfdddf; border-radius:8px; font-size:13px; outline:none; }
    .stock-btn { height:34px; padding:0 12px; border:none; border-radius:8px; background:var(--sea); color:#fff; font-size:12px; font-weight:700; cursor:pointer; transition:.2s; }
    .stock-btn:hover { background:#008b8b; }

    .actions-cell { display:flex; align-items:center; justify-content:flex-end; gap:8px; }
    .action-btn { display:inline-flex; align-items:center; justify-content:center; width:34px; height:34px; border-radius:8px; border:1px solid var(--line); background:#fff; color:var(--navy); font-size:13px; text-decoration:none; transition:.2s; cursor:pointer; }
    .action-btn:hover { background:#f4f8f9; transform:translateY(-1px); }
    .btn-edit { color:#00a7a7; border-color:#bce5e5; }
    .btn-edit:hover { background:#eefbfb; }
    .btn-delete { color:#b53b35; border-color:#f4c2c0; }
    .btn-delete:hover { background:#fdf2f2; }

    .alert-success { margin-bottom:20px; padding:14px 18px; border-radius:12px; background:#effdf8; color:#087451; border:1px solid #a7e5d2; font-size:13px; font-weight:600; }

    body.dark-mode .inv-card { background:#0b3447; border-color:#28505f; }
    body.dark-mode .inv-table th { background:#0d3a4d; color:#fff; border-color:#28505f; }
    body.dark-mode .inv-table td { border-color:#1e4758; color:#e1e8ed; }
    body.dark-mode .stock-input { background:#0d3a4d; border-color:#315565; color:#fff; }
    body.dark-mode .action-btn { background:#0d3a4d; border-color:#315565; color:#fff; }
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

    <div class="inv-card">
        <table class="inv-table">
            <thead>
                <tr>
                    <th>Insumo</th>
                    <th>Producto Asociado</th>
                    <th>Stock Actual</th>
                    <th>Stock Mínimo</th>
                    <th>Estado</th>
                    <th>Ajuste Rápido</th>
                    <th style="text-align:right;">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($insumos as $insumo)
                    <tr>
                        <td><strong>{{ $insumo->nombre_insumo }}</strong></td>
                        <td>{{ $insumo->producto->nombre ?? 'N/A' }}</td>
                        <td>
                            <strong>{{ number_format($insumo->stock, 2) }}</strong> 
                            <small>{{ $insumo->unidad_medida }}</small>
                        </td>
                        <td>{{ number_format($insumo->stock_minimo, 2) }} {{ $insumo->unidad_medida }}</td>
                        <td>
                            @if($insumo->stock <= $insumo->stock_minimo)
                                <span class="badge-stock stock-low">
                                    <i class="fa-solid fa-triangle-exclamation"></i> Stock Bajo
                                </span>
                            @else
                                <span class="badge-stock stock-ok">
                                    <i class="fa-solid fa-check"></i> Normal
                                </span>
                            @endif
                        </td>
                        <td>
                            <form action="{{ route('inventario.actualizar-stock', $insumo->id) }}" method="POST" class="stock-form">
                                @csrf
                                @method('PATCH')
                                <input type="number" step="0.01" name="stock" class="stock-input" value="{{ $insumo->stock }}" required min="0">
                                <button type="submit" class="stock-btn" title="Guardar stock rápido">
                                    <i class="fa-solid fa-floppy-disk"></i>
                                </button>
                            </form>
                        </td>
                        <td style="text-align:right;">
                            <div class="actions-cell">
                                <a href="{{ route('inventario.show', $insumo->id) }}" class="action-btn" title="Ver detalles">
                                    <i class="fa-solid fa-eye"></i>
                                </a>
                                <a href="{{ route('inventario.edit', $insumo->id) }}" class="action-btn btn-edit" title="Editar insumo">
                                    <i class="fa-solid fa-pen-to-square"></i>
                                </a>
                                <form action="{{ route('inventario.destroy', $insumo->id) }}" method="POST" onsubmit="return confirm('¿Estás seguro de eliminar este insumo?');" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="action-btn btn-delete" title="Eliminar insumo">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" style="text-align:center; padding:30px; color:var(--muted);">
                            No hay insumos registrados en el inventario.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection