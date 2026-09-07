@extends('layouts.app')

@section('title', 'Detalle de Mesa')
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

    .show-card { max-width:650px; margin:0 auto; border:1px solid var(--line); border-radius:20px; background:var(--paper); box-shadow:0 12px 32px rgba(6,43,61,.07); overflow:hidden; }
    .show-header { padding:24px 28px; background:linear-gradient(135deg, #062b3d 0%, #09405a 100%); color:#fff; display:flex; align-items:center; justify-content:space-between; }
    .show-title-group { display:flex; align-items:center; gap:16px; }
    .show-avatar { width:52px; height:52px; border-radius:14px; background:rgba(255,255,255,.12); color:var(--gold); display:grid; place-items:center; font-size:22px; border:1px solid rgba(255,255,255,.2); }
    .show-title-group h2 { margin:0; font:700 22px 'Playfair Display',serif; color:#fff; }
    .show-title-group span { font-size:12px; color:#a2c0cc; font-weight:600; }

    .show-body { padding:28px; }
    .info-grid { display:grid; grid-template-columns:repeat(2, 1fr); gap:20px; margin-bottom:28px; }
    .info-item { padding:16px; border:1px solid var(--line); border-radius:14px; background:#f8fafb; }
    .info-item label { display:block; margin-bottom:6px; color:var(--muted); font-size:10px; font-weight:800; letter-spacing:.08em; text-transform:uppercase; }
    .info-item .value { font-size:15px; font-weight:700; color:var(--navy); display:flex; align-items:center; gap:8px; }

    .status-badge { display:inline-flex; align-items:center; gap:6px; padding:5px 12px; border-radius:999px; font-size:12px; font-weight:700; }
    .status-libre { color:#087451; background:#effdf8; border:1px solid #a7e5d2; }
    .status-ocupada { color:#a32a2a; background:#fdf2f2; border:1px solid #f8c4c4; }
    .status-reservada { color:#8a6200; background:#fffdf0; border:1px solid #fce2a6; }

    .quick-status-form { padding:20px; border:1px solid var(--line); border-radius:14px; background:#f4f8f9; margin-bottom:28px; }
    .quick-status-form h3 { margin:0 0 12px; font-size:13px; font-weight:800; text-transform:uppercase; letter-spacing:.06em; color:var(--navy); }
    .status-options { display:grid; grid-template-columns:repeat(3, 1fr); gap:10px; }
    .status-btn { display:flex; align-items:center; justify-content:center; gap:6px; padding:10px; border-radius:10px; font-size:12px; font-weight:700; border:1px solid var(--line); background:#fff; color:var(--navy); cursor:pointer; transition:.2s; }
    .status-btn:hover { border-color:var(--sea); background:#e7f7f7; color:var(--sea); }
    .status-btn.active { border-color:var(--sea); background:var(--sea); color:#fff; }

    .show-actions { display:flex; gap:12px; }
    .edit-btn { flex:1; display:inline-flex; align-items:center; justify-content:center; gap:8px; height:44px; border-radius:12px; color:var(--navy); background:var(--gold); font-size:13px; font-weight:800; text-decoration:none; transition:.2s; box-shadow:0 10px 22px rgba(246,196,83,.24); }
    .edit-btn:hover { background:var(--gold-dark); transform:translateY(-1px); }
    
    .delete-form { flex:1; }
    .delete-btn { width:100%; display:inline-flex; align-items:center; justify-content:center; gap:8px; height:44px; border:none; border-radius:12px; color:#fff; background:#b53b35; font-size:13px; font-weight:800; cursor:pointer; transition:.2s; }
    .delete-btn:hover { background:#9c2f2a; transform:translateY(-1px); }

    body.dark-mode .show-card { background:#0b3447; border-color:#28505f; }
    body.dark-mode .info-item, body.dark-mode .quick-status-form { background:#0d3a4d; border-color:#315565; }
    body.dark-mode .info-item label { color:#a2c0cc; }
    body.dark-mode .info-item .value, body.dark-mode .quick-status-form h3 { color:#fff; }
    body.dark-mode .secondary-button { background:#0d3a4d; border-color:#315565; color:#fff; }
    body.dark-mode .status-btn { background:#082838; border-color:#28505f; color:#eaf3f5; }

    @media(max-width:550px) { .info-grid, .status-options { grid-template-columns:1fr; } .show-actions { flex-direction:column; } }
</style>
@endpush

@section('content')
<div class="mesas-page">
    <header class="mesas-header">
        <div>
            <span class="mesas-kicker">Gestión de Salón</span>
            <h1>Mesa #{{ $mesa->numero }}</h1>
            <p>Consulta la información detallada y cambia el estado de la mesa.</p>
        </div>
        <a class="secondary-button" href="{{ route('mesas.index') }}">
            <i class="fa-solid fa-arrow-left"></i> Volver a la lista
        </a>
    </header>

    <div class="show-card">
        <div class="show-header">
            <div class="show-title-group">
                <div class="show-avatar">
                    <i class="fa-solid fa-chair"></i>
                </div>
                <div>
                    <h2>Mesa #{{ $mesa->numero }}</h2>
                    <span>ID de Registro: {{ $mesa->id }}</span>
                </div>
            </div>
            @if($mesa->estado === 'libre')
                <span class="status-badge status-libre"><i class="fa-solid fa-circle"></i> Libre</span>
            @elseif($mesa->estado === 'ocupada')
                <span class="status-badge status-ocupada"><i class="fa-solid fa-circle"></i> Ocupada</span>
            @elseif($mesa->estado === 'reservada')
                <span class="status-badge status-reservada"><i class="fa-solid fa-circle"></i> Reservada</span>
            @endif
        </div>

        <div class="show-body">
            <div class="info-grid">
                <div class="info-item">
                    <label>Número de Mesa</label>
                    <div class="value">
                        <i class="fa-solid fa-hashtag" style="color:var(--sea);"></i>
                        <span>Mesa {{ $mesa->numero }}</span>
                    </div>
                </div>

                <div class="info-item">
                    <label>Capacidad</label>
                    <div class="value">
                        <i class="fa-solid fa-users" style="color:var(--sea);"></i>
                        <span>{{ $mesa->capacidad }} Personas</span>
                    </div>
                </div>

                <div class="info-item">
                    <label>Estado Actual</label>
                    <div class="value">
                        <i class="fa-solid fa-signal" style="color:var(--sea);"></i>
                        <span style="text-transform:capitalize;">{{ $mesa->estado }}</span>
                    </div>
                </div>

                <div class="info-item">
                    <label>Última Actualización</label>
                    <div class="value">
                        <i class="fa-regular fa-clock" style="color:var(--sea);"></i>
                        <span>{{ $mesa->updated_at ? $mesa->updated_at->format('d/m/Y H:i') : 'N/A' }}</span>
                    </div>
                </div>
            </div>

            {{-- Cambio rápido de estado --}}
            <div class="quick-status-form">
                <h3>Cambiar Estado Rápidamente</h3>
                <form action="{{ route('mesas.cambiarEstado', $mesa) }}" method="POST">
                    @csrf
                    @method('PATCH')
                    <div class="status-options">
                        <button type="submit" name="estado" value="libre" class="status-btn {{ $mesa->estado === 'libre' ? 'active' : '' }}">
                            <i class="fa-solid fa-circle-check"></i> Libre
                        </button>
                        <button type="submit" name="estado" value="ocupada" class="status-btn {{ $mesa->estado === 'ocupada' ? 'active' : '' }}">
                            <i class="fa-solid fa-users"></i> Ocupada
                        </button>
                        <button type="submit" name="estado" value="reservada" class="status-btn {{ $mesa->estado === 'reservada' ? 'active' : '' }}">
                            <i class="fa-solid fa-bookmark"></i> Reservada
                        </button>
                    </div>
                </form>
            </div>

            <div class="show-actions">
                <a href="{{ route('mesas.edit', $mesa->id) }}" class="edit-btn">
                    <i class="fa-solid fa-pen"></i> Editar Mesa
                </a>
                
                <form method="POST" action="{{ route('mesas.destroy', $mesa->id) }}" class="delete-form" onsubmit="return confirm('¿Estás seguro de eliminar la Mesa {{ $mesa->numero }}?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="delete-btn">
                        <i class="fa-solid fa-trash-can"></i> Eliminar Mesa
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection