@extends('layouts.app')

@section('title', 'Nuevo Insumo')
@section('page-title', 'Inventario')

@push('styles')
<style>
    .inventario-page { --navy:#062b3d; --sea:#00a7a7; --gold:#f6c453; --gold-dark:#e0b043; --paper:#fff; --muted:#68757d; --line:#dfe9eb; color:#18242b; }
    .inventario-header { display:flex; align-items:flex-end; justify-content:space-between; gap:20px; margin-bottom:24px; }
    .inventario-kicker { display:block; margin-bottom:7px; color:var(--sea); font-size:11px; font-weight:800; letter-spacing:.16em; text-transform:uppercase; }
    .inventario-header h1 { margin:0 0 7px; color:var(--navy); font:700 clamp(26px,3vw,36px)/1.08 'Playfair Display',serif; }
    .secondary-button { display:inline-flex; align-items:center; gap:8px; padding:10px 18px; border:1px solid var(--line); border-radius:999px; color:var(--navy); background:#fff; font-size:13px; font-weight:700; text-decoration:none; transition:.2s; }
    .secondary-button:hover { background:#f4f8f9; transform:translateY(-1px); }

    .form-card { max-width:580px; margin:0 auto; padding:28px; border:1px solid var(--line); border-radius:20px; background:var(--paper); box-shadow:0 12px 32px rgba(6,43,61,.07); }
    .form-group { margin-bottom:20px; }
    .form-label { display:block; margin-bottom:8px; color:var(--navy); font-size:11px; font-weight:800; letter-spacing:.08em; text-transform:uppercase; }
    .input-box { position:relative; display:flex; align-items:center; }
    .input-box i { position:absolute; left:14px; color:#8a9ba0; }
    .form-control { width:100%; height:44px; padding:0 14px 0 42px; border:1px solid #cfdddf; border-radius:12px; outline:none; font-size:13px; transition:.2s; background:#fff; box-sizing:border-box; }
    .form-control:focus { border-color:var(--sea); box-shadow:0 0 0 3px rgba(0,167,167,.1); }

    .submit-button { display:inline-flex; align-items:center; justify-content:center; gap:8px; width:100%; height:46px; border:none; border-radius:12px; color:var(--navy); background:var(--gold); font-size:14px; font-weight:800; cursor:pointer; transition:.2s; margin-top:10px; }
    .submit-button:hover { background:var(--gold-dark); transform:translateY(-1px); }

    body.dark-mode .form-card { background:#0b3447; border-color:#28505f; }
    body.dark-mode .form-label { color:#fff; }
    body.dark-mode .form-control { background:#0d3a4d; border-color:#315565; color:#fff; }
    body.dark-mode .secondary-button { background:#0d3a4d; border-color:#315565; color:#fff; }
</style>
@endpush

@section('content')
<div class="inventario-page">
    <header class="inventario-header">
        <div>
            <span class="inventario-kicker">Control de Stock</span>
            <h1>Registrar Nuevo Insumo</h1>
        </div>
        <a class="secondary-button" href="{{ route('inventario.index') }}">
            <i class="fa-solid fa-arrow-left"></i> Volver a la lista
        </a>
    </header>

    <div class="form-card">
        <form action="{{ route('inventario.store') }}" method="POST">
            @csrf

            <div class="form-group">
                <label class="form-label" for="nombre">Nombre del Insumo *</label>
                <div class="input-box">
                    <i class="fa-solid fa-box-archive"></i>
                    <input id="nombre" type="text" name="nombre" class="form-control" placeholder="Ej: Pescado Pota, Limón, Sal" value="{{ old('nombre') }}" required>
                </div>
                @error('nombre') <span style="color:#b53b35; font-size:12px; font-weight:600;">{{ $message }}</span> @enderror
            </div>

            <div class="form-group">
                <label class="form-label" for="unidad_medida">Unidad de Medida *</label>
                <div class="input-box">
                    <i class="fa-solid fa-scale-balanced"></i>
                    <input id="unidad_medida" type="text" name="unidad_medida" class="form-control" placeholder="Ej: Kg, Litros, Gramos, Unidades" value="{{ old('unidad_medida', 'Kg') }}" required>
                </div>
                @error('unidad_medida') <span style="color:#b53b35; font-size:12px; font-weight:600;">{{ $message }}</span> @enderror
            </div>

            <div class="form-group">
                <label class="form-label" for="stock_actual">Stock Actual *</label>
                <div class="input-box">
                    <i class="fa-solid fa-cubes"></i>
                    <input id="stock_actual" type="number" step="0.01" name="stock_actual" class="form-control" placeholder="Ej: 10.50" value="{{ old('stock_actual', 0) }}" min="0" required>
                </div>
                @error('stock_actual') <span style="color:#b53b35; font-size:12px; font-weight:600;">{{ $message }}</span> @enderror
            </div>

            <div class="form-group">
                <label class="form-label" for="stock_minimo">Stock Mínimo (Alerta) *</label>
                <div class="input-box">
                    <i class="fa-solid fa-triangle-exclamation"></i>
                    <input id="stock_minimo" type="number" step="0.01" name="stock_minimo" class="form-control" placeholder="Ej: 2.00" value="{{ old('stock_minimo', 5) }}" min="0" required>
                </div>
                @error('stock_minimo') <span style="color:#b53b35; font-size:12px; font-weight:600;">{{ $message }}</span> @enderror
            </div>

            <div class="form-group">
                <label class="form-label" for="producto_id">Producto Asociado (Opcional)</label>
                <div class="input-box">
                    <i class="fa-solid fa-utensils"></i>
                    <select id="producto_id" name="producto_id" class="form-control">
                        <option value="">-- Sin producto asociado --</option>
                        @foreach($productos as $producto)
                            <option value="{{ $producto->id }}" {{ old('producto_id') == $producto->id ? 'selected' : '' }}>
                                {{ $producto->nombre }}
                            </option>
                        @endforeach
                    </select>
                </div>
                @error('producto_id') <span style="color:#b53b35; font-size:12px; font-weight:600;">{{ $message }}</span> @enderror
            </div>

            <button type="submit" class="submit-button">
                <i class="fa-solid fa-floppy-disk"></i> Guardar Insumo
            </button>
        </form>
    </div>
</div>
@endsection