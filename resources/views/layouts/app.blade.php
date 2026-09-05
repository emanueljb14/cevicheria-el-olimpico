<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Panel') | Cevichería El Olímpico</title>

    <!-- Fuentes y CDN externos (Van PRIMERO) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Playfair+Display:wght@600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" rel="stylesheet">

    <!-- CSS Centralizado de la App (Va DESPUÉS para sobreescribir estilos por defecto) -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

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