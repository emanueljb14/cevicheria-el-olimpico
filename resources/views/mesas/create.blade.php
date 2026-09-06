@extends('layouts.app')

@section('title', 'Nueva Mesa')
@section('page-title', 'Mesas')

@push('styles')
<style>
    .mesas-page { --navy:#062b3d; --sea:#00a7a7; --gold:#f6c453; --gold-dark:#e0b043; --paper:#fff; --muted:#68757d; --line:#dfe9eb; color:#18242b; }
    .mesas-header { display:flex; align-items:flex-end; justify-content:space-between; gap:20px; margin-bottom:24px; }
    .mesas-kicker { display:block; margin-bottom:7px; color:var(--sea); font-size:11px; font-weight:800; letter-spacing:.16em; text-transform:uppercase; }
    .mesas-header h1 { margin:0 0 7px; color:var(--navy); font:700 clamp(26px,3vw,36px)/1.08 'Playfair Display',serif; }
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
</style>
@endpush

@section('content')
<div class="mesas-page">
    <header class="mesas-header">
        <div>
            <span class="mesas-kicker">Gestión de Salón</span>
            <h1>Registrar Nueva Mesa</h1>
        </div>
        <a class="secondary-button" href="{{ route('mesas.index') }}">
            <i class="fa-solid fa-arrow-left"></i> Volver a la lista
        </a>
    </header>

    <div class="form-card">
        <form action="{{ route('mesas.store') }}" method="POST">
            @csrf

            <div class="form-group">
                <label class="form-label" for="numero">Número / Identificador *</label>
                <div class="input-box">
                    <i class="fa-solid fa-chair"></i>
                    <input id="numero" type="text" name="numero" class="form-control" placeholder="Ej: Mesa 01, Terraza A" value="{{ old('numero') }}" required>
                </div>
                @error('numero') <span style="color:#b53b35; font-size:12px; font-weight:600;">{{ $message }}</span> @enderror
            </div>

            <div class="form-group">
                <label class="form-label" for="capacidad">Capacidad (Personas) *</label>
                <div class="input-box">
                    <i class="fa-solid fa-users"></i>
                    <input id="capacidad" type="number" name="capacidad" class="form-control" placeholder="Ej: 4" value="{{ old('capacidad', 4) }}" min="1" required>
                </div>
                @error('capacidad') <span style="color:#b53b35; font-size:12px; font-weight:600;">{{ $message }}</span> @enderror
            </div>

            <div class="form-group">
                <label class="form-label" for="ubicacion">Ubicación</label>
                <div class="input-box">
                    <i class="fa-solid fa-location-dot"></i>
                    <input id="ubicacion" type="text" name="ubicacion" class="form-control" placeholder="Ej: Salón principal, Terraza" value="{{ old('ubicacion') }}">
                </div>
                @error('ubicacion') <span style="color:#b53b35; font-size:12px; font-weight:600;">{{ $message }}</span> @enderror
            </div>

            <div class="form-group">
                <label class="form-label" for="estado">Estado Inicial *</label>
                <select id="estado" name="estado" class="form-control" style="padding-left:14px;" required>
                    <option value="disponible" {{ old('estado') == 'disponible' ? 'selected' : '' }}>Disponible</option>
                    <option value="ocupada" {{ old('estado') == 'ocupada' ? 'selected' : '' }}>Ocupada</option>
                    <option value="reservada" {{ old('estado') == 'reservada' ? 'selected' : '' }}>Reservada</option>
                    <option value="mantenimiento" {{ old('estado') == 'mantenimiento' ? 'selected' : '' }}>Mantenimiento</option>
                </select>
                @error('estado') <span style="color:#b53b35; font-size:12px; font-weight:600;">{{ $message }}</span> @enderror
            </div>

            <button type="submit" class="submit-button">
                <i class="fa-solid fa-floppy-disk"></i> Guardar mesa
            </button>
        </form>
    </div>
</div>
@endsection