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

        .logout-button { padding: 6px; border: 0; color: rgba(255,255,255,.8); background: transparent; }
        .logout-button:hover { color: #fff; }

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
        .topbar-actions { display: flex; align-items: center; gap: 9px; }
        .circle-button { display: grid; width: 39px; height: 39px; place-items: center; border: 1px solid #ead39a; border-radius: 50%; color: #062b3d; background: #fff7df; }
        .mobile-toggle { display: none; }
        .page-content { padding: clamp(18px, 3vw, 38px); }

        .sidebar-overlay { position: fixed; inset: 0; z-index: 1030; display: none; background: rgba(8,47,73,.48); backdrop-filter: blur(2px); }

        body.dark-mode { --olimpico-bg:#031b27; --olimpico-card:#0b3447; --olimpico-text:#edf7f8; --olimpico-muted:#aebfc5; --olimpico-border:#26414d; color:var(--olimpico-text); background:#031b27; }
        body.dark-mode .topbar-layout { border-color:#26414d; background:rgba(6,43,61,.94); }
        body.dark-mode .topbar-title strong { color:#fff; }
        body.dark-mode .card, body.dark-mode .card-box, body.dark-mode table { color:var(--olimpico-text); background-color:var(--olimpico-card); }
        body.dark-mode .table { --bs-table-bg:transparent; --bs-table-color:var(--olimpico-text); --bs-table-border-color:#1e4962; }

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
                <a class="sidebar-link {{ request()->routeIs('reportes.*') ? 'active' : '' }}" href="{{ route('reportes.index') }}">
                    <i class="fa-solid fa-chart-column"></i><span>Reportes</span>
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
            <header class="topbar-layout">
                <div class="d-flex align-items-center gap-3">
                    <button class="circle-button mobile-toggle" id="sidebarToggle" type="button" aria-label="Abrir menú">
                        <i class="fa-solid fa-bars"></i>
                    </button>
                    <div class="topbar-title">
                        <strong>@yield('page-title', 'Panel administrativo')</strong>
                        <small>Gestión de la Cevichería El Olímpico</small>
                    </div>
                </div>
                <div class="topbar-actions">
                    <span class="d-none d-md-inline text-secondary small">{{ now()->format('d/m/Y') }}</span>
                    <div class="circle-button"><i class="fa-regular fa-bell"></i></div>
                </div>
            </header>
        @endauth

        <main class="page-content">
            @yield('content')
        </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        (() => {
            const body = document.body;
            const themeToggle = document.getElementById('darkModeToggle');
            const sidebar = document.getElementById('sidebar');
            const sidebarToggle = document.getElementById('sidebarToggle');
            const overlay = document.getElementById('sidebarOverlay');

            const darkEnabled = localStorage.getItem('olimpico-theme') === 'dark';
            body.classList.toggle('dark-mode', darkEnabled);
            if (themeToggle) themeToggle.checked = darkEnabled;

            themeToggle?.addEventListener('change', () => {
                body.classList.toggle('dark-mode', themeToggle.checked);
                localStorage.setItem('olimpico-theme', themeToggle.checked ? 'dark' : 'light');
            });

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
        })();
    </script>

    @stack('scripts')
</body>
</html>
