@extends('layouts.app')

@section('title', 'Editar Mesa')
@section('page-title', 'Mesas')

@push('styles')
<style>
    .mesas-page { --navy:#062b3d; --sea:#00a7a7; --gold:#f6c453; --gold-dark:#e0b043; --paper:#fff; --muted:#68757d; --line:#dfe9eb; color:#18242b; }
    .mesas-header { display:flex; align-items:flex-end; justify-content:space-between; gap:20px; margin-bottom:24px; }
    .mesas-kicker { display:block; margin-bottom:7px; color:var(--sea); font-size:11px; font-weight:800; letter-spacing:.16em; text-transform:uppercase; }
    .mesas-header h1 { margin:0 0 7px; color:var(--navy); font:700 clamp(26px,3vw,36px)/1.08 'Playfair Display',serif; }
    .mesas-header p { margin:0; color:var(--muted); }

    .secondary-button { display:inline-flex; align-items:center; gap:8px; padding:10px 18px; border:1px solid var(--line); border-radius:999px; color:var(--navy); background:#fff; font-size:13px; font-weight:700; text-decoration:none; transition:.2s; }
    .secondary-button:hover { background:#f4f8f9; transform:translateY(-1px); }

    .edit-card { max-width:650px; margin:0 auto; border:1px solid var(--line); border-radius:20px; background:var(--paper); box-shadow:0 12px 32px rgba(6,43,61,.07); overflow:hidden; }
    .edit-header { padding:24px 28px; background:linear-gradient(135deg, #062b3d 0%, #09405a 100%); color:#fff; display:flex; align-items:center; gap:16px; }
    .edit-avatar { width:52px; height:52px; border-radius:14px; background:rgba(255,255,255,.12); color:var(--gold); display:grid; place-items:center; font-size:22px; border:1px solid rgba(255,255,255,.2); }
    .edit-header h2 { margin:0; font:700 22px 'Playfair Display',serif; color:#fff; }
    .edit-header span { font-size:12px; color:#a2c0cc; font-weight:600; }

    .edit-body { padding:28px; }

    .form-group { margin-bottom:22px; }
    .form-group label { display:block; margin-bottom:8px; color:var(--navy); font-size:12px; font-weight:800; letter-spacing:.05em; text-transform:uppercase; }
    .form-group label span { color:#b53b35; }
    
    .input-wrapper { position:relative; display:flex; align-items:center; }
    .input-wrapper i { position:absolute; left:14px; color:#8a9ba0; font-size:15px; pointer-events:none; }
    .form-control { width:100%; height:46px; padding:0 14px 0 42px; border:1px solid #cfdddf; border-radius:12px; outline:none; font-size:14px; color:var(--navy); background:#fff; transition:.2s; }
    .form-control:focus { border-color:var(--sea); box-shadow:0 0 0 3px rgba(0,167,167,.12); }

    .select-control { width:100%; height:46px; padding:0 14px 0 42px; border:1px solid #cfdddf; border-radius:12px; outline:none; font-size:14px; color:var(--navy); background:#fff; cursor:pointer; transition:.2s; appearance:none; }
    .select-wrapper { position:relative; }
    .select-wrapper::after { content:'\f0d7'; font-family:'Font Awesome 6 Free'; font-weight:900; position:absolute; right:16px; top:50%; transform:translateY(-50%); color:#8a9ba0; pointer-events:none; }
    .select-control:focus { border-color:var(--sea); box-shadow:0 0 0 3px rgba(0,167,167,.12); }

    .error-msg { margin-top:6px; color:#b53b35; font-size:12px; font-weight:600; }

    .form-actions { display:flex; gap:12px; margin-top:32px; pt-4; border-top:1px solid var(--line); }
    .submit-btn { flex:1; display:inline-flex; align-items:center; justify-content:center; gap:8px; height:46px; border:none; border-radius:12px; color:var(--navy); background:var(--gold); font-size:14px; font-weight:800; cursor:pointer; transition:.2s; box-shadow:0 10px 22px rgba(246,196,83,.24); }
    .submit-btn:hover { background:var(--gold-dark); transform:translateY(-1px); }

    .cancel-btn { flex:1; display:inline-flex; align-items:center; justify-content:center; gap:8px; height:46px; border:1px solid var(--line); border-radius:12px; color:var(--muted); background:#fff; font-size:14px; font-weight:700; text-decoration:none; transition:.2s; }
    .cancel-btn:hover { background:#f4f8f9; color:var(--navy); }

    body.dark-mode .edit-card { background:#0b3447; border-color:#28505f; }
    body.dark-mode .form-group label { color:#eaf3f5; }
    body.dark-mode .form-control, body.dark-mode .select-control { background:#0d3a4d; border-color:#315565; color:#fff; }
    body.dark-mode .secondary-button, body.dark-mode .cancel-btn { background:#0d3a4d; border-color:#315565; color:#fff; }
    body.dark-mode .form-actions { border-color:#28505f; }
</style>
@endpush

@section('content')
<div class="mesas-page">
    <header class="mesas-header">
        <div>
            <span class="mesas-kicker">Gestión de Salón</span>
            <h1>Editar Mesa #{{ $mesa->numero }}</h1>
            <p>Modifica el número, capacidad o estado actual de la mesa.</p>
        </div>
        <a class="secondary-button" href="{{ route('mesas.index') }}">
            <i class="fa-solid fa-arrow-left"></i> Volver a la lista
        </a>
    </header>

    <div class="edit-card">
        <div class="edit-header">
            <div class="edit-avatar">
                <i class="fa-solid fa-pen-to-square"></i>
            </div>
            <div>
                <h2>Modificar Información</h2>
                <span>Mesa ID: {{ $mesa->id }}</span>
            </div>
        </div>

        <form action="{{ route('mesas.update', $mesa->id) }}" method="POST" class="edit-body">
            @csrf
            @method('PUT')

            {{-- Número de Mesa --}}
            <div class="form-group">
                <label for="numero">Número de Mesa <span>*</span></label>
                <div class="input-wrapper">
                    <i class="fa-solid fa-hashtag"></i>
                    <input type="number" id="numero" name="numero" class="form-control" value="{{ old('numero', $mesa->numero) }}" placeholder="Ej: 1" required min="1">
                </div>
                @error('numero')
                    <div class="error-msg">{{ $message }}</div>
                @enderror
            </div>

            {{-- Capacidad --}}
            <div class="form-group">
                <label for="capacidad">Capacidad de Personas <span>*</span></label>
                <div class="input-wrapper">
                    <i class="fa-solid fa-users"></i>
                    <input type="number" id="capacidad" name="capacidad" class="form-control" value="{{ old('capacidad', $mesa->capacidad) }}" placeholder="Ej: 4" required min="1">
                </div>
                @error('capacidad')
                    <div class="error-msg">{{ $message }}</div>
                @enderror
            </div>

            {{-- Estado --}}
            <div class="form-group">
                <label for="estado">Estado Actual <span>*</span></label>
                <div class="select-wrapper input-wrapper">
                    <i class="fa-solid fa-signal"></i>
                    <select id="estado" name="estado" class="select-control" required>
                        <option value="libre" {{ old('estado', $mesa->estado) === 'libre' ? 'selected' : '' }}>🟢 Libre</option>
                        <option value="ocupada" {{ old('estado', $mesa->estado) === 'ocupada' ? 'selected' : '' }}>🔴 Ocupada</option>
                        <option value="reservada" {{ old('estado', $mesa->estado) === 'reservada' ? 'selected' : '' }}>🟡 Reservada</option>
                    </select>
                </div>
                @error('estado')
                    <div class="error-msg">{{ $message }}</div>
                @enderror
            </div>

            {{-- Botones de Acción --}}
            <div class="form-actions">
                <button type="submit" class="submit-btn">
                    <i class="fa-solid fa-floppy-disk"></i> Guardar Cambios
                </button>
                <a href="{{ route('mesas.show', $mesa->id) }}" class="cancel-btn">
                    <i class="fa-solid fa-xmark"></i> Cancelar
                </a>
            </div>
        </form>
    </div>
</div>
@endsection