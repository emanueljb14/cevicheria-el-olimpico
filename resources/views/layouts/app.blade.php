<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Panel') | Cevichería El Olímpico</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Playfair+Display:wght@600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        :root {
            --olimpico-primary: #00a7a7;
            --olimpico-secondary: #0b5875;
            --olimpico-accent: #f6c453;
            --olimpico-soft: #e7f7f7;
            --olimpico-bg: #f4f7f8;
            --olimpico-card: #ffffff;
            --olimpico-text: #18242b;
            --olimpico-muted: #68757d;
            --olimpico-border: #d8e4e8;
            --sidebar-width: 270px;
        }

        * { box-sizing: border-box; }

        body {
            min-height: 100vh;
            margin: 0;
            color: var(--olimpico-text);
            background: linear-gradient(135deg, #edf5f6 0%, #f8fbfb 48%, #ffffff 100%);
            font-family: 'DM Sans', sans-serif;
            transition: background .25s, color .25s;
        }

        /* Sidebar Styles */
        .sidebar {
            position: fixed;
            inset: 0 auto 0 0;
            z-index: 1040;
            display: flex;
            width: var(--sidebar-width);
            height: 100vh;
            flex-direction: column;
            overflow-y: auto;
            color: #fff;
            background: linear-gradient(180deg, #031b27 0%, #062b3d 58%, #0b5875 100%);
            box-shadow: 12px 0 35px rgba(3, 27, 39, .22);
            transition: transform .25s ease;
        }

        .brand {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 25px 22px;
            border-bottom: 1px solid rgba(255,255,255,.18);
        }

        .brand-logo {
            display: grid;
            width: 48px;
            height: 48px;
            flex: 0 0 48px;
            place-items: center;
            border: 0;
            border-radius: 50%;
            color: #062b3d;
            background: var(--olimpico-accent);
            font-size: 24px;
            box-shadow: inset 0 1px 0 rgba(255,255,255,.22);
        }

        .brand strong { display: block; line-height: 1.15; font-family:'Playfair Display',serif; font-size: 19px; }
        .brand small { color: rgba(255,255,255,.72); font-size: 11px; letter-spacing: .06em; text-transform: uppercase; }

        .menu-label {
            margin: 20px 22px 8px;
            color: rgba(255,255,255,.58);
            font-size: 10px;
            font-weight: 800;
            letter-spacing: .14em;
            text-transform: uppercase;
        }

        .sidebar-menu { flex: 1; padding-bottom: 16px; }

        .sidebar-link {
            display: flex;
            align-items: center;
            gap: 12px;
            margin: 4px 12px;
            padding: 11px 14px;
            border: 1px solid transparent;
            border-radius: 12px;
            color: rgba(255,255,255,.88);
            text-decoration: none;
            font-size: 13px;
            font-weight: 600;
            transition: .18s ease;
        }

        .sidebar-link i { width: 21px; text-align: center; font-size: 15px; }
        .sidebar-link:hover { color: var(--olimpico-accent); background: rgba(255,255,255,.10); transform: translateX(3px); }
        .sidebar-link.active { color: #062b3d; background: var(--olimpico-accent); box-shadow: 0 8px 22px rgba(0,0,0,.18); }

        .sidebar-footer { padding: 14px 12px 18px; border-top: 1px solid rgba(255,255,255,.16); }
        .theme-row, .user-card { border: 1px solid rgba(255,255,255,.14); border-radius: 13px; background: rgba(255,255,255,.11); }
        .theme-row { display: flex; align-items: center; justify-content: space-between; margin-bottom: 10px; padding: 10px 12px; font-size: 12px; }
        .user-card { display: flex; align-items: center; gap: 10px; padding: 11px; }
        .avatar { display: grid; width: 39px; height: 39px; flex: 0 0 39px; place-items: center; border-radius: 50%; color: #062b3d; background: var(--olimpico-accent); font-weight: 800; }
        .user-info { min-width: 0; flex: 1; }
        .user-info strong, .user-info small { display: block; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
        .user-info strong { font-size: 12px; }
        .user-info small { color: rgba(255,255,255,.7); font-size: 10px; }

        .logout-button { padding: 6px; border: 0; color: rgba(255,255,255,.8); background: transparent; cursor: pointer; }
        .logout-button:hover { color: #fff; }

        /* Topbar & Shell Layout */
        .page-shell { min-height: 100vh; margin-left: var(--sidebar-width); }
        .topbar-layout {
            position: sticky;
            top: 0;
            z-index: 1020;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 18px;
            min-height: 72px;
            padding: 13px clamp(18px, 3vw, 38px);
            border-bottom: 1px solid var(--olimpico-border);
            background: rgba(255,255,255,.88);
            backdrop-filter: blur(15px);
        }

        .topbar-title strong { display: block; color:#062b3d; font-family:'Playfair Display',serif; font-size: 20px; }
        .topbar-title small { color: var(--olimpico-muted); font-size: 12px; }
        .topbar-actions { display: flex; align-items: center; gap: 10px; }
        .circle-button { display: grid; width: 39px; height: 39px; place-items: center; border: 1px solid #ead39a; border-radius: 50%; color: #062b3d; background: #fff7df; cursor: pointer; transition: transform 0.15s ease; }
        .circle-button:hover { transform: scale(1.05); }
        .mobile-toggle { display: none; }
        .page-content { padding: clamp(18px, 3vw, 38px); }

        .extra-small { font-size: 11px; line-height: 1.3; }
        .dropdown-menu { animation: fadeIn 0.2s ease; }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-8px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .sidebar-overlay { position: fixed; inset: 0; z-index: 1030; display: none; background: rgba(8,47,73,.48); backdrop-filter: blur(2px); }

        /* Dark Mode Theme */
        body.dark-mode { --olimpico-bg:#031b27; --olimpico-card:#0b3447; --olimpico-text:#edf7f8; --olimpico-muted:#aebfc5; --olimpico-border:#26414d; color:var(--olimpico-text); background:#031b27; }
        body.dark-mode .topbar-layout { border-color:#26414d; background:rgba(6,43,61,.94); }
        body.dark-mode .topbar-title strong { color:#fff; }
        body.dark-mode .card, body.dark-mode .card-box, body.dark-mode table, body.dark-mode .modal-content { color:var(--olimpico-text); background-color:var(--olimpico-card); }
        body.dark-mode .table { --bs-table-bg:transparent; --bs-table-color:var(--olimpico-text); --bs-table-border-color:#1e4962; }
        body.dark-mode .live-clock-badge { background-color: #0d3a4d !important; border-color: #26414d !important; color: #edf7f8 !important; }
        body.dark-mode .live-clock-badge .text-muted, body.dark-mode .live-clock-badge .text-secondary { color: #aebfc5 !important; }
        body.dark-mode .live-clock-badge .border-end { border-color: #26414d !important; }

        @media (max-width: 991px) {
            .sidebar { transform: translateX(-105%); }
            .sidebar.open { transform: translateX(0); }
            .sidebar-overlay.show { display: block; }
            .page-shell { margin-left: 0; }
            .mobile-toggle { display: grid; }
        }

        @media (max-width: 575px) {
            .topbar-layout { min-height: 64px; padding: 11px 15px; }
            .topbar-title small { display: none; }
            .page-content { padding: 15px; }
        }
    </style>

    @stack('styles')
</head>
<body>
    @auth
        <!-- BARRA LATERAL (SIDEBAR) -->
        <aside class="sidebar" id="sidebar">
            <div class="brand">
                <div class="brand-logo">🐟</div>
                <div>
                    <strong>El Olímpico</strong>
                    <small>Cevichería</small>
                </div>
            </div>

            <nav class="sidebar-menu">
                <p class="menu-label">Principal</p>

                <a class="sidebar-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                    <i class="fa-solid fa-house"></i><span>Dashboard</span>
                </a>

                <p class="menu-label">Administración</p>

                <a class="sidebar-link {{ request()->routeIs('categorias.*') ? 'active' : '' }}" href="{{ route('categorias.index') }}">
                    <i class="fa-solid fa-tags"></i><span>Categorías</span>
                </a>
                <a class="sidebar-link {{ request()->routeIs('productos.*') ? 'active' : '' }}" href="{{ route('productos.index') }}">
                    <i class="fa-solid fa-utensils"></i><span>Productos</span>
                </a>
                <a class="sidebar-link {{ request()->routeIs('clientes.*') ? 'active' : '' }}" href="{{ route('clientes.index') }}">
                    <i class="fa-solid fa-users"></i><span>Clientes</span>
                </a>
                <a class="sidebar-link {{ request()->routeIs('mesas.*') ? 'active' : '' }}" href="{{ route('mesas.index') }}">
                    <i class="fa-solid fa-chair"></i><span>Mesas</span>
                </a>

                <p class="menu-label">Operaciones</p>

                <a class="sidebar-link {{ request()->routeIs('pedidos.*') ? 'active' : '' }}" href="{{ route('pedidos.index') }}">
                    <i class="fa-solid fa-clipboard-list"></i><span>Pedidos</span>
                </a>
                <a class="sidebar-link {{ request()->routeIs('inventario.*') ? 'active' : '' }}" href="{{ route('inventario.index') }}">
                    <i class="fa-solid fa-boxes-stacked"></i><span>Inventario</span>
                </a>
                <a class="sidebar-link {{ request()->routeIs('pagos.*') ? 'active' : '' }}" href="{{ route('pagos.index') }}">
                    <i class="fa-solid fa-credit-card"></i><span>Pagos</span>
                </a>
                <a class="sidebar-link {{ request()->routeIs('ventas.*') ? 'active' : '' }}" href="{{ route('ventas.index') }}">
                    <i class="fa-solid fa-cash-register"></i><span>Ventas</span>
                </a>
                <a class="sidebar-link {{ request()->routeIs('caja.*') ? 'active' : '' }}" href="#" data-bs-toggle="modal" data-bs-target="#arqueoCajaModal">
                    <i class="fa-solid fa-vault text-warning"></i><span>Cierre de Caja</span>
                </a>
                <a class="sidebar-link {{ request()->routeIs('reportes.*') ? 'active' : '' }}" href="{{ route('reportes.index') }}">
                    <i class="fa-solid fa-chart-column"></i><span>Reportes</span>
                </a>

                <p class="menu-label">Análisis Predictivo</p>

                <a class="sidebar-link {{ request()->routeIs('analytics.*') ? 'active' : '' }}" href="{{ url('/analytics') }}">
                    <i class="fa-solid fa-brain"></i><span>Predicciones ML</span>
                </a>
            </nav>

            <div class="sidebar-footer">
                <div class="theme-row">
                    <span><i class="fa-solid fa-moon me-2"></i>Modo oscuro</span>
                    <div class="form-check form-switch m-0">
                        <input class="form-check-input" type="checkbox" id="darkModeToggle" aria-label="Activar modo oscuro">
                    </div>
                </div>

                <div class="user-card">
                    <div class="avatar">{{ strtoupper(substr(auth()->user()->name ?? 'U', 0, 1)) }}</div>
                    <div class="user-info">
                        <strong>{{ auth()->user()->name }}</strong>
                        <small>{{ auth()->user()->email }}</small>
                    </div>
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button class="logout-button" type="submit" title="Cerrar sesión">
                            <i class="fa-solid fa-right-from-bracket"></i>
                        </button>
                    </form>
                </div>
            </div>
        </aside>

        <div class="sidebar-overlay" id="sidebarOverlay"></div>
    @endauth

    <div class="page-shell {{ auth()->check() ? '' : 'm-0' }}">
        @auth
            <!-- BARRA SUPERIOR (TOPBAR) -->
            <header class="topbar-layout">
                <div class="d-flex align-items-center gap-2 gap-md-3">
                    <button class="circle-button mobile-toggle" id="sidebarToggle" type="button" aria-label="Abrir menú">
                        <i class="fa-solid fa-bars"></i>
                    </button>

                    <div class="topbar-title">
                        <strong>@yield('page-title', 'Panel administrativo')</strong>
                        <small>Gestión de la Cevichería El Olímpico</small>
                    </div>
                </div>

                <div class="topbar-actions">
                    <!-- Botón a Vista Cocina -->
                    <a href="{{ route('pedidos.index') }}" class="btn btn-outline-dark btn-sm rounded-pill fw-semibold px-3 d-none d-sm-inline-flex align-items-center gap-2 shadow-sm">
                        <i class="fa-solid fa-fire-burner text-danger"></i>
                        <span>Vista Cocina</span>
                    </a>

                    <!-- Botón Arqueo de Caja rápido -->
                    <button type="button" class="btn btn-warning btn-sm rounded-pill fw-bold text-dark px-3 d-none d-md-inline-flex align-items-center gap-2 shadow-sm" data-bs-toggle="modal" data-bs-target="#arqueoCajaModal">
                        <i class="fa-solid fa-cash-register"></i>
                        <span>Cierre Caja</span>
                    </button>

                    <!-- Widget de Hora y Fecha -->
                    <div class="d-none d-md-flex align-items-center gap-3 px-3 py-2 bg-white border rounded-3 shadow-sm live-clock-badge">
                        <div class="d-flex align-items-center gap-2 text-primary fw-bold border-end pe-3">
                            <i class="fa-regular fa-clock fs-6"></i>
                            <span id="liveClock" class="font-monospace fs-6">--:--:--</span>
                        </div>
                        <div class="d-flex align-items-center gap-2 text-muted small">
                            <i class="fa-regular fa-calendar-days text-secondary"></i>
                            <span class="text-capitalize">{{ now()->locale('es')->translatedFormat('l, d/m/Y') }}</span>
                        </div>
                    </div>

                    <!-- Modal Guía de Atajos Teclado -->
                    <button type="button" class="circle-button d-none d-sm-grid" data-bs-toggle="modal" data-bs-target="#shortcutsModal" title="Atajos de Teclado">
                        <i class="fa-solid fa-keyboard text-dark"></i>
                    </button>

                    <!-- Dropdown Notificaciones -->
                    <div class="dropdown">
                        <button class="circle-button position-relative" type="button" id="dropdownNotifications" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fa-regular fa-bell"></i>
                            
                            @php
                                $alertasStock = 0;
                                $pedidosPendientes = 0;

                                try {
                                    if (class_exists('\App\Models\Insumo')) {
                                        $alertasStock = \App\Models\Insumo::whereRaw('stock <= stock_minimo')->count();
                                    } elseif (class_exists('\App\Models\Inventario')) {
                                        $alertasStock = \App\Models\Inventario::whereRaw('stock <= stock_minimo')->count();
                                    } elseif (class_exists('\App\Models\Producto') && \Illuminate\Support\Facades\Schema::hasColumn('productos', 'stock_minimo')) {
                                        $alertasStock = \App\Models\Producto::whereRaw('stock <= stock_minimo')->count();
                                    }

                                    if (class_exists('\App\Models\Pedido')) {
                                        $pedidosPendientes = \App\Models\Pedido::where('estado', 'pendiente')->count();
                                    }
                                } catch (\Throwable $e) {
                                    $alertasStock = 0;
                                    $pedidosPendientes = 0;
                                }

                                $totalAlertas = $alertasStock + $pedidosPendientes;
                            @endphp
                            
                            @if($totalAlertas > 0)
                                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger border border-light" style="font-size: 10px;">
                                    {{ $totalAlertas }}
                                </span>
                            @endif
                        </button>

                        <ul class="dropdown-menu dropdown-menu-end shadow-lg border-0 mt-2 py-0" aria-labelledby="dropdownNotifications" style="width: 320px; border-radius: 14px; overflow: hidden;">
                            <li class="bg-primary text-white p-3 d-flex justify-content-between align-items-center">
                                <span class="fw-bold small mb-0"><i class="fa-solid fa-bell me-2"></i>Notificaciones</span>
                                <span class="badge bg-warning text-dark rounded-pill">{{ $totalAlertas }} Nuevas</span>
                            </li>

                            <div style="max-height: 280px; overflow-y: auto;">
                                @if($alertasStock > 0)
                                    <li>
                                        <a class="dropdown-item p-3 border-bottom d-flex align-items-start gap-3" href="{{ route('inventario.index') }}">
                                            <div class="bg-danger-subtle text-danger p-2 rounded-circle">
                                                <i class="fa-solid fa-triangle-exclamation"></i>
                                            </div>
                                            <div>
                                                <strong class="d-block small text-dark">Stock Crítico</strong>
                                                <p class="mb-0 text-muted extra-small">Hay {{ $alertasStock }} insumo(s) bajo el límite mínimo.</p>
                                            </div>
                                        </a>
                                    </li>
                                @endif

                                @if($pedidosPendientes > 0)
                                    <li>
                                        <a class="dropdown-item p-3 border-bottom d-flex align-items-start gap-3" href="{{ route('pedidos.index') }}">
                                            <div class="bg-warning-subtle text-warning-emphasis p-2 rounded-circle">
                                                <i class="fa-solid fa-utensils"></i>
                                            </div>
                                            <div>
                                                <strong class="d-block small text-dark">Pedidos Pendientes</strong>
                                                <p class="mb-0 text-muted extra-small">Tienes {{ $pedidosPendientes }} pedido(s) en espera de atención.</p>
                                            </div>
                                        </a>
                                    </li>
                                @endif

                                @if($totalAlertas == 0)
                                    <li class="text-center py-4 text-muted small">
                                        <i class="fa-regular fa-circle-check d-block fs-4 text-success mb-2"></i>
                                        Todo al día. Sin alertas pendientes.
                                    </li>
                                @endif
                            </div>

                            <li class="bg-light text-center py-2 border-top">
                                <a href="{{ route('dashboard') }}" class="text-decoration-none small text-primary fw-bold">Ir al Panel</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </header>
        @endauth

        <!-- CONTENIDO PRINCIPAL -->
        <main class="page-content">
            @yield('content')
        </main>
    </div>

    <!-- MODAL 1: GUÍA DE ATAJOS -->
    <div class="modal fade" id="shortcutsModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg" style="border-radius: 16px;">
                <div class="modal-header bg-dark text-white border-0">
                    <h5 class="modal-title fs-6 fw-bold"><i class="fa-solid fa-keyboard me-2 text-warning"></i>Atajos Teclado Rápidos</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <ul class="list-group list-group-flush small">
                        <li class="list-group-item d-flex justify-content-between align-items-center py-2">
                            <span>Ir a Vista Cocina</span><kbd class="bg-light text-dark border">F8</kbd>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center py-2">
                            <span>Cierre de Caja</span><kbd class="bg-light text-dark border">F9</kbd>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <!-- MODAL 2: ARQUEO Y CIERRE COMPLETO DE CAJA -->
    <div class="modal fade" id="arqueoCajaModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg" style="border-radius: 18px;">
                <div class="modal-header bg-dark text-white border-0 py-3">
                    <h5 class="modal-title fs-6 fw-bold d-flex align-items-center gap-2">
                        <i class="fa-solid fa-vault text-warning fs-5"></i>
                        <span>Arqueo y Cierre de Caja del Día</span>
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('pagos.index') }}" method="POST">
                    @csrf
                    <div class="modal-body p-4">
                        <!-- Tarjeta Informativa Resumen del Sistema -->
                        <div class="row g-3 mb-4">
                            <div class="col-6 col-md-3">
                                <div class="p-3 border rounded-3 bg-light text-center">
                                    <span class="d-block extra-small text-muted text-uppercase font-monospace fw-bold">Mapeo del Sistema</span>
                                    <strong class="fs-6 text-dark" id="v_esperado">S/ 0.00</strong>
                                </div>
                            </div>
                            <div class="col-6 col-md-3">
                                <div class="p-3 border rounded-3 bg-light text-center">
                                    <span class="d-block extra-small text-muted text-uppercase font-monospace fw-bold">Total Arqueado</span>
                                    <strong class="fs-6 text-primary" id="v_contado">S/ 0.00</strong>
                                </div>
                            </div>
                            <div class="col-6 col-md-3">
                                <div class="p-3 border rounded-3 bg-light text-center">
                                    <span class="d-block extra-small text-muted text-uppercase font-monospace fw-bold">Diferencia</span>
                                    <strong class="fs-6 text-muted" id="v_diferencia">S/ 0.00</strong>
                                </div>
                            </div>
                            <div class="col-6 col-md-3">
                                <div class="p-3 border rounded-3 bg-light text-center">
                                    <span class="d-block extra-small text-muted text-uppercase font-monospace fw-bold">Estado del Cuadre</span>
                                    <span class="badge bg-secondary extra-small mt-1" id="badge_estado">Sin Procesar</span>
                                </div>
                            </div>
                        </div>

                        <!-- Bloques principales de entrada -->
                        <div class="row g-4">
                            <!-- Conteo Físico -->
                            <div class="col-md-6 border-end">
                                <h6 class="fw-bold small text-uppercase text-primary mb-3">
                                    <i class="fa-solid fa-money-bill-wave me-1"></i> Conteo en Efectivo
                                </h6>
                                <div class="mb-3">
                                    <label class="form-label extra-small fw-bold">Fondo Inicial de Caja (S/)</label>
                                    <input type="number" step="0.10" class="form-control form-control-sm rounded-3 calc-trigger" name="monto_apertura" id="monto_apertura" value="100.00" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label extra-small fw-bold">Efectivo Físico Recaudado (S/)</label>
                                    <input type="number" step="0.10" class="form-control form-control-sm rounded-3 fw-bold text-success calc-trigger" name="efectivo_fisico" id="efectivo_fisico" placeholder="0.00" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label extra-small fw-bold">Salidas / Gastos Directos de Caja (S/)</label>
                                    <input type="number" step="0.10" class="form-control form-control-sm rounded-3 text-danger calc-trigger" name="gastos_caja" id="gastos_caja" value="0.00">
                                </div>
                            </div>

                            <!-- Métodos Digitales -->
                            <div class="col-md-6">
                                <h6 class="fw-bold small text-uppercase text-primary mb-3">
                                    <i class="fa-solid fa-mobile-screen-button me-1"></i> Cobros Digitales y POS
                                </h6>
                                <div class="mb-3">
                                    <label class="form-label extra-small fw-bold">Yape / Plin (S/)</label>
                                    <input type="number" step="0.10" class="form-control form-control-sm rounded-3 calc-trigger" name="yape_plin_fisico" id="yape_plin_fisico" placeholder="0.00">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label extra-small fw-bold">Vouchers Tarjeta / POS (S/)</label>
                                    <input type="number" step="0.10" class="form-control form-control-sm rounded-3 calc-trigger" name="pos_fisico" id="pos_fisico" placeholder="0.00">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label extra-small fw-bold">Esperado en Sistema POS (Opcional) (S/)</label>
                                    <input type="number" step="0.10" class="form-control form-control-sm rounded-3 bg-light calc-trigger" name="monto_sistema" id="monto_sistema" placeholder="0.00">
                                </div>
                            </div>
                        </div>

                        <!-- Observaciones -->
                        <div class="mt-3">
                            <label class="form-label extra-small fw-bold text-uppercase">Notas u Observaciones sobre el Cuadre</label>
                            <textarea class="form-control rounded-3" name="observaciones" rows="2" placeholder="Describa diferencias, vales de compras o incidencias del turno..."></textarea>
                        </div>
                    </div>

                    <div class="modal-footer bg-light border-0 py-3">
                        <button type="button" class="btn btn-sm btn-outline-secondary rounded-pill px-3" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-sm btn-warning rounded-pill fw-bold text-dark px-4 shadow-sm">
                            <i class="fa-solid fa-lock me-1"></i> Cerrar Caja y Generar Ticket
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        (() => {
            const body = document.body;
            const themeToggle = document.getElementById('darkModeToggle');
            const sidebar = document.getElementById('sidebar');
            const sidebarToggle = document.getElementById('sidebarToggle');
            const overlay = document.getElementById('sidebarOverlay');

            // Mueve el modal automáticamente al body para resolver bloqueos por layout o transform
            const modalCaja = document.getElementById('arqueoCajaModal');
            if (modalCaja && modalCaja.parentElement !== document.body) {
                document.body.appendChild(modalCaja);
            }

            // Persistencia del Modo Oscuro
            const darkEnabled = localStorage.getItem('olimpico-theme') === 'dark';
            body.classList.toggle('dark-mode', darkEnabled);
            if (themeToggle) themeToggle.checked = darkEnabled;

            themeToggle?.addEventListener('change', () => {
                body.classList.toggle('dark-mode', themeToggle.checked);
                localStorage.setItem('olimpico-theme', themeToggle.checked ? 'dark' : 'light');
            });

            // Apertura/Cierre del Menú Móvil
            const closeSidebar = () => {
                sidebar?.classList.remove('open');
                overlay?.classList.remove('show');
            };

            sidebarToggle?.addEventListener('click', () => {
                sidebar?.classList.toggle('open');
                overlay?.classList.toggle('show');
            });

            overlay?.addEventListener('click', closeSidebar);
            window.addEventListener('resize', () => {
                if (window.innerWidth > 991) closeSidebar();
            });

            // Atajos Globales de Teclado
            document.addEventListener('keydown', (e) => {
                if (e.key === 'F8') {
                    e.preventDefault();
                    window.location.href = "{{ route('pedidos.index') }}";
                }
                if (e.key === 'F9') {
                    e.preventDefault();
                    const modal = new bootstrap.Modal(document.getElementById('arqueoCajaModal'));
                    modal.show();
                }
            });

            // Reloj en tiempo real
            function updateClock() {
                const clockElement = document.getElementById('liveClock');
                if (clockElement) {
                    const now = new Date();
                    clockElement.textContent = now.toLocaleTimeString('es-PE', { hour: '2-digit', minute: '2-digit', second: '2-digit' });
                }
            }
            setInterval(updateClock, 1000);
            updateClock();

            // Lógica interactiva para Cálculo de Cierre de Caja
            const triggers = document.querySelectorAll('.calc-trigger');
            triggers.forEach(input => input.addEventListener('input', calcularArqueo));

            function calcularArqueo() {
                const apertura = parseFloat(document.getElementById('monto_apertura')?.value || 0);
                const efectivo = parseFloat(document.getElementById('efectivo_fisico')?.value || 0);
                const gastos = parseFloat(document.getElementById('gastos_caja')?.value || 0);
                const yape = parseFloat(document.getElementById('yape_plin_fisico')?.value || 0);
                const pos = parseFloat(document.getElementById('pos_fisico')?.value || 0);
                const esperado = parseFloat(document.getElementById('monto_sistema')?.value || 0);

                const totalContado = (apertura + efectivo + yape + pos) - gastos;
                const diferencia = totalContado - esperado;

                document.getElementById('v_esperado').textContent = `S/ ${esperado.toFixed(2)}`;
                document.getElementById('v_contado').textContent = `S/ ${totalContado.toFixed(2)}`;
                
                const elemDiferencia = document.getElementById('v_diferencia');
                const badge = document.getElementById('badge_estado');

                elemDiferencia.textContent = `S/ ${diferencia.toFixed(2)}`;

                if (esperado === 0) {
                    badge.className = 'badge bg-secondary extra-small mt-1';
                    badge.textContent = 'Pendiente Sistema';
                    elemDiferencia.className = 'fs-6 text-muted';
                } else if (Math.abs(diferencia) < 0.1) {
                    badge.className = 'badge bg-success extra-small mt-1';
                    badge.textContent = 'Cuadre Perfecto';
                    elemDiferencia.className = 'fs-6 text-success fw-bold';
                } else if (diferencia > 0) {
                    badge.className = 'badge bg-info text-dark extra-small mt-1';
                    badge.textContent = 'Sobrante';
                    elemDiferencia.className = 'fs-6 text-info fw-bold';
                } else {
                    badge.className = 'badge bg-danger extra-small mt-1';
                    badge.textContent = 'Faltante';
                    elemDiferencia.className = 'fs-6 text-danger fw-bold';
                }
            }
        })();
    </script>

    @stack('scripts')
</body>
</html>