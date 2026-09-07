@extends('layouts.app')

@section('title', 'Editar Insumo - ' . $inventario->nombre_insumo)
@section('page-title', 'Inventario')

@push('styles')
<style>
    .inv-edit-page { 
        --navy: #062b3d; 
        --sea: #00a7a7; 
        --gold: #f6c453; 
        --gold-dark: #e0b043; 
        --paper: #ffffff; 
        --muted: #68757d; 
        --line: #dfe9eb; 
        --bg-subtle: #f8fafc;
        --danger: #b53b35;
        color: #18242b; 
    }

    /* Header */
    .inv-header { display: flex; align-items: flex-end; justify-content: space-between; gap: 16px; margin-bottom: 24px; flex-wrap: wrap; }
    .inv-kicker { display: block; margin-bottom: 4px; color: var(--sea); font-size: 11px; font-weight: 800; letter-spacing: .16em; text-transform: uppercase; }
    .inv-header h1 { margin: 0; color: var(--navy); font: 700 clamp(24px, 3vw, 32px)/1.1 'Playfair Display', serif; }

    .secondary-button { display: inline-flex; align-items: center; gap: 8px; padding: 10px 20px; border-radius: 999px; color: var(--navy); background: var(--paper); border: 1px solid var(--line); font-size: 13px; font-weight: 700; text-decoration: none; transition: all .2s ease; box-shadow: 0 2px 6px rgba(6,43,61,.04); }
    .secondary-button:hover { background: #f4f8f9; transform: translateY(-1px); }

    /* Layout en 2 Columnas estilo Show */
    .edit-grid { display: grid; grid-template-columns: 320px 1fr; gap: 24px; }

    /* Tarjeta Lateral de Estado */
    .sidebar-card { background: var(--paper); border: 1px solid var(--line); border-radius: 20px; padding: 24px; box-shadow: 0 12px 32px rgba(6,43,61,.05); height: fit-content; }
    .insumo-icon-box { width: 64px; height: 64px; border-radius: 16px; background: #e0f2fe; color: #0369a1; display: flex; align-items: center; justify-content: center; font-size: 28px; margin-bottom: 16px; }
    .sidebar-title { font-size: 18px; font-weight: 800; color: var(--navy); margin: 0 0 4px; line-height: 1.2; }
    .sidebar-subtitle { font-size: 12px; color: var(--muted); font-weight: 600; display: block; margin-bottom: 16px; }

    .stock-highlight { background: var(--bg-subtle); border-radius: 12px; padding: 14px 16px; border: 1px solid var(--line); margin-bottom: 16px; }
    .stock-highlight-label { font-size: 11px; font-weight: 800; color: var(--muted); text-transform: uppercase; letter-spacing: .08em; display: block; margin-bottom: 2px; }
    .stock-highlight-value { font-size: 22px; font-weight: 800; color: var(--navy); }

    /* Badges */
    .badge-stock { display: inline-flex; align-items: center; gap: 6px; padding: 6px 12px; border-radius: 999px; font-size: 11px; font-weight: 700; }
    .stock-ok { background: #effdf8; color: #087451; border: 1px solid #a7e5d2; }
    .stock-low { background: #fdf2f2; color: #a32a2a; border: 1px solid #f8c4c4; }

    /* Card del Formulario */
    .form-card { border: 1px solid var(--line); border-radius: 20px; background: var(--paper); box-shadow: 0 12px 32px rgba(6,43,61,.05); overflow: hidden; }
    .card-header-custom { padding: 20px 24px; border-bottom: 1px solid var(--line); background: var(--bg-subtle); display: flex; align-items: center; justify-content: space-between; }
    .card-header-title { margin: 0; color: var(--navy); font-size: 15px; font-weight: 800; display: flex; align-items: center; gap: 10px; }
    .card-body-custom { padding: 24px; }

    /* Formulario e Inputs */
    .form-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 20px; }
    .form-group { display: flex; flex-direction: column; }
    .form-group.full-width { grid-column: span 2; }

    .form-label { display: block; margin-bottom: 8px; color: var(--navy); font-size: 11px; font-weight: 800; letter-spacing: .08em; text-transform: uppercase; }
    
    .input-box { position: relative; display: flex; align-items: center; }
    .input-box i { position: absolute; left: 14px; color: #8a9ba0; font-size: 14px; pointer-events: none; transition: color .2s; }
    
    .form-control { width: 100%; height: 44px; padding: 0 14px 0 42px; border: 1px solid #cfdddf; border-radius: 12px; outline: none; font-size: 13px; transition: all .2s ease; background: var(--paper); color: var(--navy); box-sizing: border-box; }
    .form-control:focus { border-color: var(--sea); box-shadow: 0 0 0 3px rgba(0,167,167,.12); }
    .form-control:focus + i, .input-box:focus-within i { color: var(--sea); }
    .form-control.is-invalid { border-color: var(--danger); }

    select.form-control { cursor: pointer; appearance: none; -webkit-appearance: none; background-image: url("data:image/svg+xml;charset=UTF-8,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='%2368757d' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3e%3cpolyline points='6 9 12 15 18 9'%3e%3c/polyline%3e%3c/svg%3e"); background-repeat: no-repeat; background-position: right 14px center; background-size: 16px; }

    .error-msg { margin-top: 6px; color: var(--danger); font-size: 12px; font-weight: 600; display: flex; align-items: center; gap: 4px; }

    /* Botón Submit */
    .submit-button { display: inline-flex; align-items: center; justify-content: center; gap: 8px; width: 100%; height: 46px; border: none; border-radius: 12px; color: var(--navy); background: var(--gold); font-size: 14px; font-weight: 800; cursor: pointer; transition: all .2s ease; box-shadow: 0 4px 12px rgba(246,196,83,.3); }
    .submit-button:hover { background: var(--gold-dark); transform: translateY(-1px); box-shadow: 0 6px 16px rgba(224,176,67,.4); }

    /* Responsivo */
    @media (max-width: 900px) {
        .edit-grid { grid-template-columns: 1fr; }
    }
    @media (max-width: 600px) {
        .form-grid { grid-template-columns: 1fr; }
        .form-group.full-width { grid-column: span 1; }
    }

    /* Dark Mode Support */
    body.dark-mode .sidebar-card, body.dark-mode .form-card { background: #0b3447; border-color: #28505f; }
    body.dark-mode .card-header-custom, body.dark-mode .stock-highlight { background: #0d3a4d; border-color: #28505f; }
    body.dark-mode .sidebar-title, body.dark-mode .card-header-title, body.dark-mode .stock-highlight-value, body.dark-mode .form-label { color: #fff; }
    body.dark-mode .form-control { background: #0d3a4d; border-color: #315565; color: #fff; }
    body.dark-mode .secondary-button { background: #0d3a4d; border-color: #315565; color: #fff; }
    body.dark-mode select.form-control { background-image: url("data:image/svg+xml;charset=UTF-8,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='%23ffffff' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3e%3cpolyline points='6 9 12 15 18 9'%3e%3c/polyline%3e%3c/svg%3e"); }
</style>
@endpush

@section('content')
<div class="inv-edit-page">
    {{-- Header --}}
    <header class="inv-header">
        <div>
            <span class="inv-kicker">Edición de Registro</span>
            <h1>Editar Insumo</h1>
        </div>
        <a class="secondary-button" href="{{ route('inventario.index') }}">
            <i class="fa-solid fa-arrow-left"></i> Volver a la lista
        </a>
    </header>

    @php
        $isLow = $inventario->stock <= $inventario->stock_minimo;
    @endphp

    <div class="edit-grid">
        {{-- Lateral: Resumen del Insumo --}}
        <aside class="sidebar-card">
            <div class="insumo-icon-box">
                <i class="fa-solid fa-box-archive"></i>
            </div>
            <h2 class="sidebar-title">{{ $inventario->nombre_insumo }}</h2>
            <span class="sidebar-subtitle">
                Producto: {{ $inventario->producto->nombre ?? 'Sin vincular' }}
            </span>

            <div class="stock-highlight">
                <span class="stock-highlight-label">Stock Actual</span>
                <div class="stock-highlight-value">
                    {{ number_format($inventario->stock, 2) }}
                    <small style="font-size: 13px; font-weight: 600; color: var(--muted);">{{ $inventario->unidad_medida }}</small>
                </div>
            </div>

            <div style="margin-bottom: 8px;">
                <span style="font-size: 11px; font-weight: 800; color: var(--muted); text-transform: uppercase; letter-spacing: .08em; display: block; margin-bottom: 6px;">
                    Estado Actual
                </span>
                @if($isLow)
                    <span class="badge-stock stock-low">
                        <i class="fa-solid fa-triangle-exclamation"></i> Stock Bajo
                    </span>
                @else
                    <span class="badge-stock stock-ok">
                        <i class="fa-solid fa-check"></i> Normal
                    </span>
                @endif
            </div>
        </aside>

        {{-- Formulario Principal --}}
        <main class="form-card">
            <div class="card-header-custom">
                <h3 class="card-header-title">
                    <i class="fa-solid fa-pen-to-square" style="color: var(--sea);"></i> Formulario de Actualización
                </h3>
            </div>

            <div class="card-body-custom">
                <form action="{{ route('inventario.update', $inventario->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="form-grid">
                        {{-- Nombre del Insumo --}}
                        <div class="form-group full-width">
                            <label class="form-label" for="nombre_insumo">Nombre del Insumo *</label>
                            <div class="input-box">
                                <i class="fa-solid fa-box-archive"></i>
                                <input id="nombre_insumo" type="text" name="nombre_insumo" class="form-control @error('nombre_insumo') is-invalid @enderror" value="{{ old('nombre_insumo', $inventario->nombre_insumo) }}" required>
                            </div>
                            @error('nombre_insumo')
                                <span class="error-msg"><i class="fa-solid fa-circle-exclamation"></i> {{ $message }}</span>
                            @enderror
                        </div>

                        {{-- Unidad de Medida --}}
                        <div class="form-group full-width">
                            <label class="form-label" for="unidad_medida">Unidad de Medida *</label>
                            <div class="input-box">
                                <i class="fa-solid fa-scale-balanced"></i>
                                <input id="unidad_medida" type="text" name="unidad_medida" class="form-control @error('unidad_medida') is-invalid @enderror" value="{{ old('unidad_medida', $inventario->unidad_medida) }}" required>
                            </div>
                            @error('unidad_medida')
                                <span class="error-msg"><i class="fa-solid fa-circle-exclamation"></i> {{ $message }}</span>
                            @enderror
                        </div>

                        {{-- Stock Actual --}}
                        <div class="form-group">
                            <label class="form-label" for="stock">Stock Actual *</label>
                            <div class="input-box">
                                <i class="fa-solid fa-cubes"></i>
                                <input id="stock" type="number" step="0.01" name="stock" class="form-control @error('stock') is-invalid @enderror" value="{{ old('stock', $inventario->stock) }}" min="0" required>
                            </div>
                            @error('stock')
                                <span class="error-msg"><i class="fa-solid fa-circle-exclamation"></i> {{ $message }}</span>
                            @enderror
                        </div>

                        {{-- Stock Mínimo --}}
                        <div class="form-group">
                            <label class="form-label" for="stock_minimo">Stock Mínimo (Alerta) *</label>
                            <div class="input-box">
                                <i class="fa-solid fa-triangle-exclamation"></i>
                                <input id="stock_minimo" type="number" step="0.01" name="stock_minimo" class="form-control @error('stock_minimo') is-invalid @enderror" value="{{ old('stock_minimo', $inventario->stock_minimo) }}" min="0" required>
                            </div>
                            @error('stock_minimo')
                                <span class="error-msg"><i class="fa-solid fa-circle-exclamation"></i> {{ $message }}</span>
                            @enderror
                        </div>

                        {{-- Producto Asociado --}}
                        <div class="form-group full-width">
                            <label class="form-label" for="producto_id">Producto Asociado (Opcional)</label>
                            <div class="input-box">
                                <i class="fa-solid fa-utensils"></i>
                                <select id="producto_id" name="producto_id" class="form-control @error('producto_id') is-invalid @enderror">
                                    <option value="">-- Sin producto asociado --</option>
                                    @foreach($productos as $producto)
                                        <option value="{{ $producto->id }}" {{ old('producto_id', $inventario->producto_id) == $producto->id ? 'selected' : '' }}>
                                            {{ $producto->nombre }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            @error('producto_id')
                                <span class="error-msg"><i class="fa-solid fa-circle-exclamation"></i> {{ $message }}</span>
                            @enderror
                        </div>

                        {{-- Botón Guardar --}}
                        <div class="form-group full-width" style="margin-top: 8px;">
                            <button type="submit" class="submit-button">
                                <i class="fa-solid fa-rotate"></i> Guardar Cambios
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </main>
    </div>
</div>
@endsection