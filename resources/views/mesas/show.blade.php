@extends('layouts.app')

@section('title', 'Detalles de la Mesa')
@section('page-title', 'Mesas')

@push('styles')
<style>
    .mesas-page { --navy:#062b3d; --sea:#00a7a7; --gold:#f6c453; --gold-dark:#e0b043; --paper:#fff; --muted:#68757d; --line:#dfe9eb; color:#18242b; }
    .mesas-header { display:flex; align-items:flex-end; justify-content:space-between; gap:20px; margin-bottom:24px; }
    .mesas-kicker { display:block; margin-bottom:7px; color:var(--sea); font-size:11px; font-weight:800; letter-spacing:.16em; text-transform:uppercase; }
    .mesas-header h1 { margin:0 0 7px; color:var(--navy); font:700 clamp(26px,3vw,36px)/1.08 'Playfair Display',serif; }
    
    .actions-header { display:flex; gap:10px; }
    .secondary-button { display:inline-flex; align-items:center; gap:8px; padding:10px 18px; border:1px solid var(--line); border-radius:999px; color:var(--navy); background:#fff; font-size:13px; font-weight:700; text-decoration:none; transition:.2s; }
    .secondary-button:hover { background:#f4f8f9; transform:translateY(-1px); }
    .edit-button-hdr { background:var(--gold); border-color:var(--gold); }
    .edit-button-hdr:hover { background:var(--gold-dark); }

    .show-card { max-width:680px; margin:0 auto; padding:28px; border:1px solid var(--line); border-radius:20px; background:var(--paper); box-shadow:0 12px 32px rgba(6,43,61,.07); }
    .details-grid { display:grid; grid-template-columns:repeat(3,1fr); gap:16px; margin-bottom:24px; }
    .detail-item { padding:14px; border-radius:12px; background:#f7fafb; border:1px solid #ebf0f1; }
    .detail-item small { display:block; margin-bottom:4px; color:var(--muted); font-size:10px; font-weight:800; text-transform:uppercase; letter-spacing:.05em; }
    .detail-item strong { color:var(--navy); font-size:16px; }

    .status-badge { display:inline-flex; align-items:center; gap:5px; padding:4px 10px; border-radius:999px; font-size:12px; font-weight:700; }
    .status-libre { color:#087451; background:#effdf8; border:1px solid #a7e5d2; }
    .status-ocupada { color:#a32a2a; background:#fdf2f2; border:1px solid #f8c4c4; }
    .status-reservada { color:#8a6200; background:#fffdf0; border:1px solid #fce2a6; }

    .change-status-box { padding-top:20px; border-top:1px solid var(--line); }
    .change-status-box h3 { margin:0 0 12px; color:var(--navy); font-size:14px; font-weight:800; text-transform:uppercase; letter-spacing:.05em; }
    .status-form { display:flex; gap:10px; }
    .status-select { flex:1; height:44px; padding:0 14px; border:1px solid #cfdddf; border-radius:12px; font-size:13px; outline:none; }
    .status-submit { padding:0 20px; height:44px; border:none; border-radius:12px; background:var(--sea); color:#fff; font-weight:700; cursor:pointer; transition:.2s; }
    .status-submit:hover { background:#008b8b; }

    body.dark-mode .show-card { background:#0b3447; border-color:#28505f; }
    body.dark-mode .detail-item { background:#0d3a4d; border-color:#315565; }
    body.dark-mode .detail-item strong, body.dark-mode .change-status-box h3 { color:#fff; }
    body.dark-mode .secondary-button { background:#0d3a4d; border-color:#315565; color:#fff; }
    body.dark-mode .status-select { background:#0d3a4d; border-color:#315565; color:#fff; }

    @media(max-width:580px) { .details-grid { grid-template-columns:1fr; } .status-form { flex-direction:column; } }
</style>
@endpush

@section('content')
<div class="mesas-page">
    <header class="mesas-header">
        <div>
            <span class="mesas-kicker">Gestión de Salón</span>
            <h1>Detalle de Mesa #{{ $mesa->numero }}</h1>
        </div>
        <div class="actions-header">
            <a class="secondary-button edit-button-hdr" href="{{ route('mesas.edit', $mesa->id) }}">
                <i class="fa-solid fa-pen"></i> Editar
            </a>
            <a class="secondary-button" href="{{ route('mesas.index') }}">
                <i class="fa-solid fa-arrow-left"></i> Volver
            </a>
        </div>
    </header>

    <div class="show-card">
        <div class="details-grid">
            <div class="detail-item">
                <small>Identificador</small>
                <strong>Mesa #{{ $mesa->numero }}</strong>
            </div>
            <div class="detail-item">
                <small>Capacidad</small>
                <strong><i class="fa-solid fa-users"></i> {{ $mesa->capacidad }} Personas</strong>
            </div>
            <div class="detail-item">
                <small>Estado Actual</small>
                <div>
                    @if($mesa->estado === 'libre')
                        <span class="status-badge status-libre"><i class="fa-solid fa-circle"></i> Libre</span>
                    @elseif($mesa->estado === 'ocupada')
                        <span class="status-badge status-ocupada"><i class="fa-solid fa-circle"></i> Ocupada</span>
                    @elseif($mesa->estado === 'reservada')
                        <span class="status-badge status-reservada"><i class="fa-solid fa-circle"></i> Reservada</span>
                    @endif
                </div>
            </div>
        </div>

        <div class="change-status-box">
            <h3>Cambio Rápido de Estado</h3>
            <form action="{{ route('mesas.cambiarEstado', $mesa->id) }}" method="POST" class="status-form">
                @csrf
                @method('PATCH')
                <select name="estado" class="status-select" required>
                    <option value="libre" {{ $mesa->estado === 'libre' ? 'selected' : '' }}>Libre</option>
                    <option value="ocupada" {{ $mesa->estado === 'ocupada' ? 'selected' : '' }}>Ocupada</option>
                    <option value="reservada" {{ $mesa->estado === 'reservada' ? 'selected' : '' }}>Reservada</option>
                </select>
                <button type="submit" class="status-submit">
                    <i class="fa-solid fa-arrows-rotate"></i> Actualizar
                </button>
            </form>
        </div>
    </div>
</div>
@endsection