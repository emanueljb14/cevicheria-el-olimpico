@extends('layouts.app')

@section('title', 'Registrar Categoría')
@section('page-title', 'Categorías')

@push('styles')
<style>
    .categories-page { --navy:#062b3d; --sea:#00a7a7; --gold:#f6c453; --gold-dark:#e0b043; --paper:#fff; --muted:#68757d; --line:#dfe9eb; color:#18242b; }
    .categories-header { display:flex; align-items:flex-end; justify-content:space-between; gap:20px; margin-bottom:24px; }
    .categories-kicker { display:block; margin-bottom:7px; color:var(--sea); font-size:11px; font-weight:800; letter-spacing:.16em; text-transform:uppercase; }
    .categories-header h1 { margin:0 0 7px; color:var(--navy); font:700 clamp(30px,4vw,43px)/1.08 'Playfair Display',serif; }
    .categories-header p { margin:0; color:var(--muted); }
    
    .secondary-button { display:inline-flex; align-items:center; justify-content:center; gap:9px; padding:12px 18px; border-radius:999px; color:var(--navy); background:#e7f7f7; font-size:13px; font-weight:800; text-decoration:none; transition:.2s; }
    .secondary-button:hover { background:#d0f0f0; color:var(--navy); }

    .form-card { border:1px solid var(--line); border-radius:20px; background:var(--paper); box-shadow:0 12px 32px rgba(6,43,61,.07); padding:28px; max-width:700px; margin:0 auto; }
    .form-title { margin:0 0 20px; color:var(--navy); font:700 22px 'Playfair Display',serif; border-bottom:1px solid var(--line); padding-bottom:12px; }

    .form-grid { display:grid; grid-template-columns:1fr; gap:20px; }
    .form-group { display:flex; flex-direction:column; gap:6px; }
    
    .form-group label { color:var(--navy); font-size:12px; font-weight:800; text-transform:uppercase; letter-spacing:.05em; }
    .input-wrapper { position:relative; display:flex; align-items:center; }
    .input-wrapper i { position:absolute; left:14px; color:#8a9ba0; font-size:14px; }
    
    .form-control { width:100%; height:44px; padding:0 14px 0 40px; border:1px solid #cfdddf; border-radius:12px; outline:none; font-size:13px; color:#3e4d53; background:var(--paper); transition:.15s; }
    textarea.form-control { height:auto; padding-top:12px; padding-bottom:12px; }
    .form-control:focus { border-color:var(--sea); box-shadow:0 0 0 3px rgba(0,167,167,.1); }
    .form-control.is-invalid { border-color:#b53b35; }

    .error-text { color:#b53b35; font-size:11px; font-weight:700; margin-top:2px; }

    .form-actions { display:flex; justify-content:flex-end; gap:12px; margin-top:28px; padding-top:20px; border-top:1px solid var(--line); }
    .submit-button { display:inline-flex; align-items:center; justify-content:center; gap:9px; padding:12px 24px; border:none; border-radius:999px; color:var(--navy); background:var(--gold); box-shadow:0 10px 22px rgba(246,196,83,.24); font-size:13px; font-weight:800; cursor:pointer; transition:.2s; }
    .submit-button:hover { background:var(--gold-dark); transform:translateY(-2px); }
    .cancel-button { display:inline-flex; align-items:center; justify-content:center; padding:12px 20px; border-radius:999px; color:var(--muted); background:transparent; font-size:13px; font-weight:700; text-decoration:none; transition:.2s; }
    .cancel-button:hover { color:var(--navy); background:#f2f6f7; }

    body.dark-mode .categories-page { --paper:#0b3447; --line:#28505f; color:#eaf3f5; }
    body.dark-mode .categories-header h1, body.dark-mode .form-title, body.dark-mode .form-group label { color:#fff; }
    body.dark-mode .form-control { color:#e7f1f3; border-color:#315565; background:#0d3a4d; }
    body.dark-mode .cancel-button:hover { background:#12455c; color:#fff; }

    @media(max-width:650px) { 
        .categories-header { align-items:stretch; flex-direction:column; }
        .form-actions { flex-direction:column-reverse; }
        .submit-button, .cancel-button { width:100%; }
    }
</style>
@endpush

@section('content')
<div class="categories-page">
    <header class="categories-header">
        <div>
            <span class="categories-kicker">Estructura del Menú</span>
            <h1>Nueva Categoría</h1>
            <p>Crea un nuevo grupo para clasificar los platos o bebidas.</p>
        </div>
        <a class="secondary-button" href="{{ route('categorias.index') }}">
            <i class="fa-solid fa-arrow-left"></i> Volver a la lista
        </a>
    </header>

    <div class="form-card">
        <h2 class="form-title">Información de la categoría</h2>

        <form action="{{ route('categorias.store') }}" method="POST">
            @csrf

            <div class="form-grid">
                <div class="form-group">
                    <label for="nombre">Nombre de la categoría *</label>
                    <div class="input-wrapper">
                        <i class="fa-solid fa-layer-group"></i>
                        <input type="text" id="nombre" name="nombre" class="form-control @error('nombre') is-invalid @enderror" value="{{ old('nombre') }}" placeholder="Ej. Ceviches, Bebidas, Entradas..." required>
                    </div>
                    @error('nombre')
                        <span class="error-text">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="descripcion">Descripción</label>
                    <div class="input-wrapper">
                        <i class="fa-solid fa-align-left" style="top:16px;"></i>
                        <textarea id="descripcion" name="descripcion" rows="4" class="form-control @error('descripcion') is-invalid @enderror" placeholder="Agrega una breve reseña de los platillos que incluye esta categoría...">{{ old('descripcion') }}</textarea>
                    </div>
                    @error('descripcion')
                        <span class="error-text">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <div class="form-actions">
                <a href="{{ route('categorias.index') }}" class="cancel-button">Cancelar</a>
                <button type="submit" class="submit-button">
                    <i class="fa-solid fa-floppy-disk"></i> Guardar categoría
                </button>
            </div>
        </form>
    </div>
</div>
@endsection