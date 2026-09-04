@extends('layouts.app')

@section('title', 'Gestión de Clientes')
@section('page-title', 'Clientes')

@push('styles')
<style>
    .clients-page { --navy:#062b3d; --sea:#00a7a7; --gold:#f6c453; --gold-dark:#e0b043; --paper:#fff; --muted:#68757d; --line:#dfe9eb; color:#18242b; }
    .clients-header { display:flex; align-items:flex-end; justify-content:space-between; gap:20px; margin-bottom:24px; }
    .clients-kicker { display:block; margin-bottom:7px; color:var(--sea); font-size:11px; font-weight:800; letter-spacing:.16em; text-transform:uppercase; }
    .clients-header h1 { margin:0 0 7px; color:var(--navy); font:700 clamp(30px,4vw,43px)/1.08 'Playfair Display',serif; }
    .clients-header p { margin:0; color:var(--muted); }
    .primary-button { display:inline-flex; align-items:center; justify-content:center; gap:9px; padding:12px 18px; border-radius:999px; color:var(--navy); background:var(--gold); box-shadow:0 10px 22px rgba(246,196,83,.24); font-size:13px; font-weight:800; text-decoration:none; transition:.2s; }
    .primary-button:hover { color:var(--navy); background:var(--gold-dark); transform:translateY(-2px); }

    .flash { display:flex; gap:10px; margin-bottom:20px; padding:14px 16px; border-radius:13px; font-size:13px; }
    .flash-success { border:1px solid #a7e5d2; color:#087451; background:#effdf8; }
    .flash-info { border:1px solid #b8e4ea; color:#0b5875; background:#effcfd; }
    
    .client-stats { display:grid; grid-template-columns:repeat(3,1fr); gap:16px; margin-bottom:22px; }
    .stat-card { position:relative; overflow:hidden; padding:20px; border:1px solid var(--line); border-radius:18px; background:var(--paper); box-shadow:0 10px 28px rgba(6,43,61,.06); }
    .stat-icon { display:grid; width:41px; height:41px; margin-bottom:12px; place-items:center; border-radius:13px; color:var(--sea); background:#e7f7f7; }
    .stat-card small { display:block; margin-bottom:5px; color:var(--muted); font-size:10px; font-weight:800; letter-spacing:.06em; text-transform:uppercase; }
    .stat-card strong { color:var(--navy); font-size:22px; }

    .history-card { overflow:hidden; border:1px solid var(--line); border-radius:20px; background:var(--paper); box-shadow:0 12px 32px rgba(6,43,61,.07); }
    .history-toolbar { display:flex; align-items:center; justify-content:space-between; gap:15px; padding:19px 21px; border-bottom:1px solid var(--line); }
    .history-toolbar h2 { margin:0; color:var(--navy); font:700 20px 'Playfair Display',serif; }
    .record-count { display:inline-block; margin-left:7px; padding:4px 9px; border-radius:999px; color:#087b7b; background:#e7f7f7; font:700 11px 'DM Sans',sans-serif; vertical-align:middle; }
    .search-box { position:relative; width:min(100%,320px); }
    .search-box i { position:absolute; top:50%; left:14px; color:#8a9ba0; transform:translateY(-50%); }
    .search-box input { width:100%; height:41px; padding:0 14px 0 40px; border:1px solid #cfdddf; border-radius:999px; outline:none; font-size:13px; }
    .search-box input:focus { border-color:var(--sea); box-shadow:0 0 0 3px rgba(0,167,167,.1); }

    .table-scroll { overflow-x:auto; }
    .clients-table { width:100%; min-width:800px; border-collapse:collapse; }
    .clients-table th { padding:13px 16px; color:#718187; background:#f7fafb; font-size:10px; font-weight:800; letter-spacing:.08em; text-align:left; text-transform:uppercase; white-space:nowrap; }
    .clients-table td { padding:15px 16px; border-top:1px solid #ebf0f1; color:#3e4d53; font-size:13px; vertical-align:middle; }
    .clients-table tbody tr:hover { background:#fbfdfd; }
    
    .client-code { display:flex; align-items:center; gap:9px; color:var(--navy); font-weight:800; }
    .client-code i { display:grid; width:34px; height:34px; place-items:center; border-radius:10px; color:var(--sea); background:#e7f7f7; }
    .main-data { display:block; color:var(--navy); font-weight:700; }
    .sub-data { display:block; margin-top:3px; color:#89979c; font-size:11px; }
    
    .actions { display:flex; gap:7px; }
    .action-button { display:grid; width:34px; height:34px; place-items:center; border:1px solid var(--line); border-radius:10px; color:var(--navy); background:white; cursor:pointer; text-decoration:none; transition:.15s; }
    .action-button:hover { color:white; border-color:var(--sea); background:var(--sea); }
    .edit-button:hover { border-color:var(--gold-dark); background:var(--gold-dark); color:var(--navy); }
    .delete-button { color:#b53b35; }
    .delete-button:hover { border-color:#b53b35; background:#b53b35; color:white; }
    
    .empty-state { padding:55px 20px !important; text-align:center; }
    .empty-state i { display:grid; width:62px; height:62px; margin:0 auto 13px; place-items:center; border-radius:50%; color:var(--sea); background:#e7f7f7; font-size:24px; }
    .empty-state strong { display:block; margin-bottom:5px; color:var(--navy); font-size:16px; }
    .empty-state span { color:var(--muted); }

    body.dark-mode .clients-page { --paper:#0b3447; --line:#28505f; color:#eaf3f5; }
    body.dark-mode .clients-header h1, body.dark-mode .stat-card strong, body.dark-mode .history-toolbar h2,
    body.dark-mode .client-code, body.dark-mode .main-data, body.dark-mode .empty-state strong { color:#fff; }
    body.dark-mode .clients-table th { color:#b9cbd0; background:#082838; }
    body.dark-mode .clients-table td { border-color:#264b5a; color:#d9e5e8; }
    body.dark-mode .clients-table tbody tr:hover { background:#0e3a4e; }
    body.dark-mode .action-button, body.dark-mode .search-box input { color:#e7f1f3; border-color:#315565; background:#0d3a4d; }

    @media(max-width:800px) { .client-stats{grid-template-columns:1fr} }
    @media(max-width:650px) { .clients-header,.history-toolbar{align-items:stretch;flex-direction:column}.primary-button,.search-box{width:100%}.stat-card{padding:16px}.stat-card strong{font-size:18px} }
</style>
@endpush

@section('content')
@php
    $totalClientes = $clientes->count();
    $conTelefono = $clientes->whereNotNull('telefono')->count();
    $conEmail = $clientes->whereNotNull('email')->count();
@endphp

<div class="clients-page">
    <header class="clients-header">
        <div>
            <span class="clients-kicker">Directorio de atención</span>
            <h1>Lista de clientes</h1>
            <p>Gestiona la información de contacto y registro de tus clientes.</p>
        </div>
        <a class="primary-button" href="{{ route('clientes.create') }}">
            <i class="fa-solid fa-user-plus"></i> Registrar nuevo cliente
        </a>
    </header>

    @if(session('success'))
        <div class="flash flash-success"><i class="fa-solid fa-circle-check"></i><span>{{ session('success') }}</span></div>
    @endif
    @if(session('info'))
        <div class="flash flash-info"><i class="fa-solid fa-circle-info"></i><span>{{ session('info') }}</span></div>
    @endif

    <section class="client-stats">
        <article class="stat-card">
            <span class="stat-icon"><i class="fa-solid fa-users"></i></span>
            <small>Total registrados</small>
            <strong>{{ $totalClientes }}</strong>
        </article>
        <article class="stat-card">
            <span class="stat-icon"><i class="fa-solid fa-phone"></i></span>
            <small>Con teléfono</small>
            <strong>{{ $conTelefono }}</strong>
        </article>
        <article class="stat-card">
            <span class="stat-icon"><i class="fa-solid fa-envelope"></i></span>
            <small>Con correo electrónico</small>
            <strong>{{ $conEmail }}</strong>
        </article>
    </section>

    <section class="history-card">
        <div class="history-toolbar">
            <h2>Clientes registrados <span class="record-count">{{ $totalClientes }}</span></h2>
            <label class="search-box" for="clientSearch">
                <i class="fa-solid fa-magnifying-glass"></i>
                <input id="clientSearch" type="search" placeholder="Buscar por nombre, teléfono o email...">
            </label>
        </div>

        <div class="table-scroll">
            <table class="clients-table">
                <thead>
                    <tr>
                        <th>Código</th>
                        <th>Cliente</th>
                        <th>Teléfono</th>
                        <th>Correo Electrónico</th>
                        <th>Dirección</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($clientes as $cliente)
                        <tr class="client-row">
                            <td>
                                <span class="client-code">
                                    <i class="fa-solid fa-user"></i>
                                    #CLI-{{ str_pad($cliente->id, 4, '0', STR_PAD_LEFT) }}
                                </span>
                            </td>
                            <td>
                                <span class="main-data">{{ $cliente->nombre }}</span>
                                <span class="sub-data">Registrado: {{ $cliente->created_at?->format('d/m/Y') ?? 'Sin fecha' }}</span>
                            </td>
                            <td>
                                <span class="main-data">{{ $cliente->telefono ?? 'Sin teléfono' }}</span>
                            </td>
                            <td>
                                <span class="main-data">{{ $cliente->email ?? 'Sin correo' }}</span>
                            </td>
                            <td>
                                <span class="main-data">{{ $cliente->direccion ?? 'Sin dirección' }}</span>
                            </td>
                            <td>
                                <div class="actions">
                                    <a class="action-button" href="{{ route('clientes.show', $cliente) }}" title="Ver detalle">
                                        <i class="fa-regular fa-eye"></i>
                                    </a>
                                    <a class="action-button edit-button" href="{{ route('clientes.edit', $cliente) }}" title="Editar cliente">
                                        <i class="fa-solid fa-pen"></i>
                                    </a>
                                    <form method="POST" action="{{ route('clientes.destroy', $cliente) }}" onsubmit="return confirm('¿Deseas eliminar este cliente?')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="action-button delete-button" type="submit" title="Eliminar cliente">
                                            <i class="fa-solid fa-trash-can"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td class="empty-state" colspan="6">
                                <i class="fa-solid fa-users-slash"></i>
                                <strong>No hay clientes registrados</strong>
                                <span>Agrega el primer cliente para comenzar a gestionar tu directorio.</span>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>
</div>
@endsection

@push('scripts')
<script>
    const clientSearch = document.getElementById('clientSearch');
    const clientRows = document.querySelectorAll('.client-row');
    
    clientSearch?.addEventListener('input', event => {
        const term = event.target.value.toLowerCase().trim();
        clientRows.forEach(row => {
            row.style.display = row.textContent.toLowerCase().includes(term) ? '' : 'none';
        });
    });
</script>
@endpush