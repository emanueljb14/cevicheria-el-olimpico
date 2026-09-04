@extends('layouts.app')

@section('title', 'Registrar Cliente')
@section('page-title', 'Clientes')

@push('styles')
<style>
    .clients-page { --navy:#062b3d; --sea:#00a7a7; --gold:#f6c453; --gold-dark:#e0b043; --paper:#fff; --muted:#68757d; --line:#dfe9eb; color:#18242b; }
    .clients-header { display:flex; align-items:flex-end; justify-content:space-between; gap:20px; margin-bottom:24px; }
    .clients-kicker { display:block; margin-bottom:7px; color:var(--sea); font-size:11px; font-weight:800; letter-spacing:.16em; text-transform:uppercase; }
    .clients-header h1 { margin:0 0 7px; color:var(--navy); font:700 clamp(30px,4vw,43px)/1.08 'Playfair Display',serif; }
    .clients-header p { margin:0; color:var(--muted); }
    
    .secondary-button { display:inline-flex; align-items:center; justify-content:center; gap:9px; padding:12px 18px; border-radius:999px; color:var(--navy); background:#e7f7f7; font-size:13px; font-weight:800; text-decoration:none; transition:.2s; }
    .secondary-button:hover { background:#d0f0f0; color:var(--navy); }

    .form-card { border:1px solid var(--line); border-radius:20px; background:var(--paper); box-shadow:0 12px 32px rgba(6,43,61,.07); padding:28px; max-width:800px; margin:0 auto; }
    .form-title { margin:0 0 20px; color:var(--navy); font:700 22px 'Playfair Display',serif; border-bottom:1px solid var(--line); padding-bottom:12px; }

    .form-grid { display:grid; grid-template-columns:repeat(2, 1fr); gap:20px; }
    .form-group { display:flex; flex-direction:column; gap:6px; }
    .form-group.full-width { grid-column:span 2; }
    
    .form-group label { color:var(--navy); font-size:12px; font-weight:800; text-transform:uppercase; letter-spacing:.05em; }
    .input-wrapper { position:relative; display:flex; align-items:center; }
    .input-wrapper i { position:absolute; left:14px; color:#8a9ba0; font-size:14px; }
    .form-control { width:100%; height:44px; padding:0 14px 0 40px; border:1px solid #cfdddf; border-radius:12px; outline:none; font-size:13px; color:#3e4d53; background:var(--paper); transition:.15s; }
    .form-control:focus { border-color:var(--sea); box-shadow:0 0 0 3px rgba(0,167,167,.1); }
    .form-control.is-invalid { border-color:#b53b35; }
    
    .error-text { color:#b53b35; font-size:11px; font-weight:700; margin-top:2px; }

    .form-actions { display:flex; justify-content:flex-end; gap:12px; margin-top:28px; padding-top:20px; border-top:1px solid var(--line); }
    .submit-button { display:inline-flex; align-items:center; justify-content:center; gap:9px; padding:12px 24px; border:none; border-radius:999px; color:var(--navy); background:var(--gold); box-shadow:0 10px 22px rgba(246,196,83,.24); font-size:13px; font-weight:800; cursor:pointer; transition:.2s; }
    .submit-button:hover { background:var(--gold-dark); transform:translateY(-2px); }
    .cancel-button { display:inline-flex; align-items:center; justify-content:center; padding:12px 20px; border-radius:999px; color:var(--muted); background:transparent; font-size:13px; font-weight:700; text-decoration:none; transition:.2s; }
    .cancel-button:hover { color:var(--navy); background:#f2f6f7; }

    body.dark-mode .clients-page { --paper:#0b3447; --line:#28505f; color:#eaf3f5; }
    body.dark-mode .clients-header h1, body.dark-mode .form-title, body.dark-mode .form-group label { color:#fff; }
    body.dark-mode .form-control { color:#e7f1f3; border-color:#315565; background:#0d3a4d; }
    body.dark-mode .cancel-button:hover { background:#12455c; color:#fff; }

    @media(max-width:650px) { 
        .clients-header { align-items:stretch; flex-direction:column; }
        .form-grid { grid-template-columns:1fr; }
        .form-group.full-width { grid-column:span 1; }
        .form-actions { flex-direction:column-reverse; }
        .submit-button, .cancel-button { width:100%; }
    }
</style>
@endpush

@section('content')
<div class="clients-page">
    <header class="clients-header">
        <div>
            <span class="clients-kicker">Directorio de atención</span>
            <h1>Nuevo cliente</h1>
            <p>Ingresa los datos para registrar un nuevo cliente en la plataforma.</p>
        </div>
        <a class="secondary-button" href="{{ route('clientes.index') }}">
            <i class="fa-solid fa-arrow-left"></i> Volver a la lista
        </a>
    </header>

    <div class="form-card">
        <h2 class="form-title">Información del cliente</h2>

        <form action="{{ route('clientes.store') }}" method="POST">
            @csrf

            <div class="form-grid">
                {{-- Nombre completo --}}
                <div class="form-group full-width">
                    <label for="nombre">Nombre completo *</label>
                    <div class="input-wrapper">
                        <i class="fa-solid fa-user"></i>
                        <input type="text" id="nombre" name="nombre" class="form-control @error('nombre') is-invalid @enderror" value="{{ old('nombre') }}" placeholder="Ej. Juan Pérez" required>
                    </div>
                    @error('nombre')
                        <span class="error-text">{{ $message }}</span>
                    @enderror
                </div>

                {{-- Teléfono --}}
                <div class="form-group">
                    <label for="telefono">Teléfono / Celular</label>
                    <div class="input-wrapper">
                        <i class="fa-solid fa-phone"></i>
                        <input type="text" id="telefono" name="telefono" class="form-control @error('telefono') is-invalid @enderror" value="{{ old('telefono') }}" placeholder="Ej. 987654321">
                    </div>
                    @error('telefono')
                        <span class="error-text">{{ $message }}</span>
                    @enderror
                </div>

                {{-- Correo Electrónico --}}
                <div class="form-group">
                    <label for="email">Correo electrónico</label>
                    <div class="input-wrapper">
                        <i class="fa-solid fa-envelope"></i>
                        <input type="email" id="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" placeholder="cliente@correo.com">
                    </div>
                    @error('email')
                        <span class="error-text">{{ $message }}</span>
                    @enderror
                </div>

                {{-- Dirección --}}
                <div class="form-group full-width">
                    <label for="direccion">Dirección fiscal / domicilio</label>
                    <div class="input-wrapper">
                        <i class="fa-solid fa-location-dot"></i>
                        <input type="text" id="direccion" name="direccion" class="form-control @error('direccion') is-invalid @enderror" value="{{ old('direccion') }}" placeholder="Ej. Av. Principal 123">
                    </div>
                    @error('direccion')
                        <span class="error-text">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <div class="form-actions">
                <a href="{{ route('clientes.index') }}" class="cancel-button">Cancelar</a>
                <button type="submit" class="submit-button">
                    <i class="fa-solid fa-floppy-disk"></i> Guardar cliente
                </button>
            </div>
        </form>
    </div>
</div>
@endsection