@extends('layouts.app')

@section('title', 'Detalle de Insumo')
@section('page-title', 'Inventario')

@push('styles')
<style>
    .inv-page { --navy:#062b3d; --sea:#00a7a7; --gold:#f6c453; --gold-dark:#e0b043; --paper:#fff; --muted:#68757d; --line:#dfe9eb; color:#18242b; }
    .inv-header { display:flex; align-items:flex-end; justify-content:space-between; gap:20px; margin-bottom:24px; }
    .inv-kicker { display:block; margin-bottom:7px; color:var(--sea); font-size:11px; font-weight:800; letter-spacing:.16em; text-transform:uppercase; }
    .inv-header h1 { margin:0 0 7px; color:var(--navy); font:700 clamp(26px,3vw,36px)/1.08 'Playfair Display',serif; }
    .inv-header p { margin:0; color:var(--muted); }

    .secondary-button { display:inline-flex; align-items:center; gap:8px; padding:10px 18px; border:1px solid var(--line); border-radius:999px; color:var(--navy); background:#fff; font-size:13px; font-weight:700; text-decoration:none; transition:.2s; }
    .secondary-button:hover { background:#f4f8f9; transform:translateY(-1px); }

    .show-card { max-width:680px; margin:0 auto; border:1px solid var(--line); border-radius:20px; background:var(--paper); box-shadow:0 12px 32px rgba(6,43,61,.07); overflow:hidden; }
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

    .badge-stock { display:inline-flex; align-items:center; gap:6px; padding:5px 12px; border-radius:999px; font-size:12px; font-weight:700; }
    .stock-ok { background:#effdf8; color:#087451; border:1px solid #a7e5d2; }
    .stock-low { background:#fdf2f2; color:#a32a2a; border:1px solid #f8c4c4; }

    .quick-movement-form { padding:20px; border:1px solid var(--line); border-radius:14px; background:#f4f8f9; margin-bottom:28px; }
    .quick-movement-form h3 { margin:0 0 6px; font-size:13px; font-weight:800; text-transform:uppercase; letter-spacing:.06em; color:var(--navy); }
    .quick-movement-form p { margin:0 0 16px; font-size:12px; color:var(--muted); }
    
    .movement-grid { display:grid; grid-template-columns:1fr 1fr auto; gap:12px; align-items:flex-end; }
    .form-group-sm label { display:block; margin-bottom:6px; color:var(--navy); font-size:11px; font-weight:800; text-transform:uppercase; }
    .input-control { width:100%; height:42px; padding:0 12px; border:1px solid #cfdddf; border-radius:10px; font-size:13px; outline:none; color:var(--navy); background:#fff; transition:.2s; }
    .input-control:focus { border-color:var(--sea); box-shadow:0 0 0 3px rgba(0,167,167,.12); }
    
    .submit-movement-btn { height:42px; padding:0 20px; border:none; border-radius:10px; background:var(--sea); color:#fff; font-size:13px; font-weight:800; cursor:pointer; transition:.2s; display:inline-flex; align-items:center; gap:8px; }
    .submit-movement-btn:hover { background:#008b8b; transform:translateY(-1px); }

    .show-actions { display:flex; gap:12px; }
    .edit-btn { flex:1; display:inline-flex; align-items:center; justify-content:center; gap:8px; height:44px; border-radius:12px; color:var(--navy); background:var(--gold); font-size:13px; font-weight:800; text-decoration:none; transition:.2s; box-shadow:0 10px 22px rgba(246,196,83,.24); }
    .edit-btn:hover { background:var(--gold-dark); transform:translateY(-1px); }
    
    .delete-form { flex:1; }
    .delete-btn { width:100%; display:inline-flex; align-items:center; justify-content:center; gap:8px; height:44px; border:none; border-radius:12px; color:#fff; background:#b53b35; font-size:13px; font-weight:800; cursor:pointer; transition:.2s; }
    .delete-btn:hover { background:#9c2f2a; transform:translateY(-1px); }

    .alert-success { margin-bottom:20px; padding:14px 18px; border-radius:12px; background:#effdf8; color:#087451; border:1px solid #a7e5d2; font-size:13px; font-weight:600; }
    .alert-danger { margin-bottom:20px; padding:14px 18px; border-radius:12px; background:#fdf2f2; color:#a32a2a; border:1px solid #f8c4c4; font-size:13px; font-weight:600; }

    body.dark-mode .show-card { background:#0b3447; border-color:#28505f; }
    body.dark-mode .info-item, body.dark-mode .quick-movement-form { background:#0d3a4d; border-color:#315565; }
    body.dark-mode .info-item label { color:#a2c0cc; }
    body.dark-mode .info-item .value, body.dark-mode .quick-movement-form h3 { color:#fff; }
    body.dark-mode .secondary-button { background:#0d3a4d; border-color:#315565; color:#fff; }
    body.dark-mode .input-control { background:#082838; border-color:#28505f; color:#fff; }

    @media(max-width:600px) { .info-grid, .movement-grid { grid-template-columns:1fr; } .show-actions { flex-direction:column; } }
</style>
@endpush

@section('content')
<div class="inv-page">
    <header class="inv-header">
        <div>
            <span class="inv-kicker">Almacén y Cocina</span>
            <h1>{{ $inventario->nombre_insumo }}</h1>
            <p>Consulta el estado actual del insumo y registra entradas o salidas.</p>
        </div>
        <a class="secondary-button" href="{{ route('inventario.index') }}">
            <i class="fa-solid fa-arrow-left"></i> Volver al inventario
        </a>
    </header>

    @if(session('success'))
        <div class="alert-success">
            <i class="fa-solid fa-circle-check"></i> {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert-danger">
            <i class="fa-solid fa-circle-exclamation"></i> {{ session('error') }}
        </div>
    @endif

    <div class="show-card">
        <div class="show-header">
            <div class="show-title-group">
                <div class="show-avatar">
                    <i class="fa-solid fa-boxes-stacked"></i>
                </div>
                <div>
                    <h2>{{ $inventario->nombre_insumo }}</h2>
                    <span>ID Registro: #{{ $inventario->id }}</span>
                </div>
            </div>

            @if($inventario->stock <= $inventario->stock_minimo)
                <span class="badge-stock stock-low">
                    <i class="fa-solid fa-triangle-exclamation"></i> Stock Bajo
                </span>
            @else
                <span class="badge-stock stock-ok">
                    <i class="fa-solid fa-check"></i> Stock OK
                </span>
            @endif
        </div>

        <div class="show-body">
            <div class="info-grid">
                <div class="info-item">
                    <label>Stock Actual</label>
                    <div class="value">
                        <i class="fa-solid fa-cubes" style="color:var(--sea);"></i>
                        <span style="font-size:18px;">{{ number_format($inventario->stock, 2) }}</span>
                        <small style="color:var(--muted); font-weight:600;">{{ $inventario->unidad_medida }}</small>
                    </div>
                </div>

                <div class="info-item">
                    <label>Stock Mínimo</label>
                    <div class="value">
                        <i class="fa-solid fa-triangle-exclamation" style="color:var(--sea);"></i>
                        <span>{{ number_format($inventario->stock_minimo, 2) }}</span>
                        <small style="color:var(--muted); font-weight:600;">{{ $inventario->unidad_medida }}</small>
                    </div>
                </div>

                <div class="info-item">
                    <label>Producto Asociado</label>
                    <div class="value">
                        <i class="fa-solid fa-utensils" style="color:var(--sea);"></i>
                        <span>{{ $inventario->producto->nombre ?? 'Ninguno' }}</span>
                    </div>
                </div>

                <div class="info-item">
                    <label>Última Actualización</label>
                    <div class="value">
                        <i class="fa-regular fa-clock" style="color:var(--sea);"></i>
                        <span>{{ $inventario->updated_at ? $inventario->updated_at->format('d/m/Y H:i') : 'N/A' }}</span>
                    </div>
                </div>
            </div>

            {{-- Formulario para Ajustar Stock --}}
            <div class="quick-movement-form">
                <h3>Registrar Movimiento de Stock</h3>
                <p>Modifica existencias agregando o restando unidades.</p>
                
                <form action="{{ route('inventario.actualizar-stock', $inventario->id) }}" method="POST">
                    @csrf
                    @method('PATCH')
                    
                    <div class="movement-grid">
                        <div class="form-group-sm">
                            <label for="cantidad">Cantidad</label>
                            <input type="number" id="cantidad" name="cantidad" step="0.01" min="0.01" class="input-control" placeholder="0.00" required>
                        </div>

                        <div class="form-group-sm">
                            <label for="tipo">Tipo de Movimiento</label>
                            <select id="tipo" name="tipo" class="input-control" required>
                                <option value="entrada">📥 Entrada (Añadir)</option>
                                <option value="salida">📤 Salida (Restar)</option>
                            </select>
                        </div>

                        <button type="submit" class="submit-movement-btn">
                            <i class="fa-solid fa-floppy-disk"></i> Registrar
                        </button>
                    </div>
                </form>
            </div>

            {{-- Acciones Principales --}}
            <div class="show-actions">
                <a href="{{ route('inventario.edit', $inventario->id) }}" class="edit-btn">
                    <i class="fa-solid fa-pen"></i> Editar Insumo
                </a>
                
                <form method="POST" action="{{ route('inventario.destroy', $inventario->id) }}" class="delete-form" onsubmit="return confirm('¿Estás seguro de eliminar el insumo {{ $inventario->nombre_insumo }}?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="delete-btn">
                        <i class="fa-solid fa-trash-can"></i> Eliminar Insumo
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection