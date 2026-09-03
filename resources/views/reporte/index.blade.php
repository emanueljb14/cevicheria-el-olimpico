<x-app-layout>
    <style>
        :root {
            --primary: #38bdf8;
            --primary-dark: #0284c7;
            --primary-light: #e0f2fe;
            --background: #f0f9ff;
            --surface: #ffffff;
            --text: #0c4a6e;
            --muted: #64748b;
            --border: #bae6fd;
            --danger: #dc2626;
            --warning: #d97706;
            --success: #059669;
            --shadow: 0 12px 30px rgba(14, 165, 233, .10);
        }

        * { box-sizing: border-box; }

        body {
            margin: 0;
            min-height: 100vh;
            font-family: 'Inter', sans-serif;
            color: var(--text);
            background: linear-gradient(135deg, #e0f2fe 0%, #ffffff 55%, #f0f9ff 100%);
        }

        .topbar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 24px;
            padding: 18px clamp(20px, 5vw, 76px);
            color: white;
            background: linear-gradient(110deg, #0284c7, #38bdf8);
            box-shadow: 0 5px 18px rgba(2, 132, 199, .20);
        }

        .brand { display: flex; align-items: center; gap: 12px; }
        .brand-icon {
            display: grid; place-items: center; width: 44px; height: 44px;
            border-radius: 14px; font-size: 24px; background: rgba(255,255,255,.18);
        }
        .brand strong { display: block; font-size: 18px; }
        .brand small { opacity: .82; }
        .nav-link { color: white; text-decoration: none; font-size: 14px; font-weight: 600; }

        .container { width: min(1180px, calc(100% - 32px)); margin: 38px auto 60px; }
        .page-header { display: flex; align-items: flex-start; justify-content: space-between; gap: 20px; margin-bottom: 26px; }
        h1 { margin: 0 0 8px; font-size: clamp(28px, 4vw, 40px); letter-spacing: -.04em; }
        .subtitle { margin: 0; color: var(--muted); }

        .btn {
            display: inline-flex; align-items: center; justify-content: center; gap: 8px;
            border: 0; border-radius: 12px; padding: 12px 17px; cursor: pointer;
            font: inherit; font-size: 14px; font-weight: 700; text-decoration: none;
            transition: .2s ease;
        }
        .btn:hover { transform: translateY(-1px); }
        .btn-primary { color: white; background: var(--primary-dark); box-shadow: 0 8px 18px rgba(2,132,199,.22); }
        .btn-light { color: var(--primary-dark); background: #ecfeff; }
        .btn-danger { color: var(--danger); background: #fef2f2; }

        .alert {
            margin-bottom: 22px; padding: 15px 18px; border: 1px solid #a7f3d0;
            border-radius: 14px; color: #065f46; background: #ecfdf5;
        }

        .stats { display: grid; grid-template-columns: repeat(4, 1fr); gap: 18px; margin-bottom: 20px; }
        .card { border: 1px solid var(--border); border-radius: 18px; background: var(--surface); box-shadow: var(--shadow); }
        .stat { position: relative; overflow: hidden; padding: 22px; }
        .stat::after { content: ''; position: absolute; right: -28px; bottom: -38px; width: 100px; height: 100px; border-radius: 50%; background: var(--primary-light); opacity: .65; }
        .stat-label { display: block; margin-bottom: 10px; color: var(--muted); font-size: 13px; font-weight: 600; }
        .stat-value { position: relative; z-index: 1; font-size: 25px; font-weight: 800; }

        .dashboard { display: grid; grid-template-columns: 1.45fr .8fr; gap: 20px; margin-bottom: 20px; }
        .section { padding: 24px; }
        .section-title { display: flex; justify-content: space-between; align-items: center; gap: 12px; margin-bottom: 20px; }
        .section-title h2 { margin: 0; font-size: 18px; }
        .badge { padding: 5px 9px; border-radius: 999px; font-size: 12px; font-weight: 700; color: var(--primary-dark); background: var(--primary-light); }
        .chart-box { height: 285px; }

        .ranking { margin: 0; padding: 0; list-style: none; }
        .ranking li { display: grid; grid-template-columns: 34px 1fr auto; align-items: center; gap: 12px; padding: 13px 0; border-bottom: 1px solid #edf5f7; }
        .ranking li:last-child { border: 0; }
        .rank { display: grid; place-items: center; width: 30px; height: 30px; border-radius: 9px; color: var(--primary-dark); font-size: 13px; font-weight: 800; background: var(--primary-light); }
        .product-name { overflow: hidden; text-overflow: ellipsis; font-weight: 600; white-space: nowrap; }
        .quantity { color: var(--muted); font-size: 13px; }

        .inventory { margin-bottom: 20px; }
        .critical-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 12px; }
        .critical-item { padding: 14px; border: 1px solid #fed7aa; border-radius: 12px; background: #fff7ed; }
        .critical-item strong { display: block; margin-bottom: 5px; color: #9a3412; }
        .critical-item span { color: var(--warning); font-size: 13px; }

        .table-wrap { overflow-x: auto; }
        table { width: 100%; border-collapse: collapse; font-size: 14px; }
        th, td { padding: 14px 13px; text-align: left; border-bottom: 1px solid #e8f2f4; }
        th { color: var(--muted); font-size: 12px; text-transform: uppercase; letter-spacing: .04em; background: #f8fdfe; }
        td strong { color: var(--text); }
        .type { display: inline-block; padding: 5px 9px; border-radius: 8px; color: var(--primary-dark); font-size: 12px; font-weight: 700; text-transform: capitalize; background: var(--primary-light); }
        .actions { display: flex; gap: 7px; }
        .actions .btn { padding: 8px 10px; font-size: 12px; }
        .empty { padding: 34px 15px !important; color: var(--muted); text-align: center; }
        .empty-list { color: var(--muted); font-size: 14px; }

        @media (max-width: 900px) {
            .stats { grid-template-columns: repeat(2, 1fr); }
            .dashboard { grid-template-columns: 1fr; }
            .critical-grid { grid-template-columns: repeat(2, 1fr); }
        }

        @media (max-width: 600px) {
            .topbar { padding: 15px 18px; }
            .brand small, .nav-link { display: none; }
            .container { margin-top: 26px; }
            .page-header { flex-direction: column; }
            .page-header .btn-primary { width: 100%; }
            .stats, .critical-grid { grid-template-columns: 1fr; }
            .section { padding: 18px; }
        }
    </style>
</head>
    <x-slot name="header">
        <div style="display:flex; align-items:center; justify-content:space-between; gap:16px;">
            <h2 style="margin:0; color:#0c4a6e; font-size:1.25rem; font-weight:700;">
                Reportes y analíticas
            </h2>
            <a href="{{ route('dashboard') }}" style="color:#0284c7; font-size:.875rem; font-weight:600; text-decoration:none;">
                ← Volver al panel
            </a>
        </div>
    </x-slot>

    <main class="container">
        <section class="page-header">
            <div>
                <h1>Reportes y analíticas</h1>
                <p class="subtitle">Revisa las ventas, productos e inventario del negocio.</p>
            </div>
            <a href="{{ route('reportes.create') }}" class="btn btn-primary">＋ Generar reporte</a>
        </section>

        @if (session('success'))
            <div class="alert">✓ {{ session('success') }}</div>
        @endif

        @php
            $totalVentas = $ventasPorDia->sum('total');
            $mejorDia = $ventasPorDia->sortByDesc('total')->first();
        @endphp

        <section class="stats">
            <article class="card stat">
                <span class="stat-label">Ventas (últimos registros)</span>
                <span class="stat-value">S/ {{ number_format($totalVentas, 2) }}</span>
            </article>
            <article class="card stat">
                <span class="stat-label">Reportes guardados</span>
                <span class="stat-value">{{ $reportes->count() }}</span>
            </article>
            <article class="card stat">
                <span class="stat-label">Productos destacados</span>
                <span class="stat-value">{{ $productosMasVendidos->count() }}</span>
            </article>
            <article class="card stat">
                <span class="stat-label">Insumos con stock bajo</span>
                <span class="stat-value">{{ $insumosCriticos->count() }}</span>
            </article>
        </section>

        <section class="dashboard">
            <article class="card section">
                <div class="section-title">
                    <h2>Ventas por día</h2>
                    <span class="badge">Últimos 7 días con ventas</span>
                </div>
                <div class="chart-box">
                    <canvas id="ventasChart"></canvas>
                </div>
            </article>

            <article class="card section">
                <div class="section-title">
                    <h2>Más vendidos</h2>
                    <span class="badge">Top 5</span>
                </div>
                @forelse ($productosMasVendidos as $item)
                    @if ($loop->first)<ol class="ranking">@endif
                    <li>
                        <span class="rank">{{ $loop->iteration }}</span>
                        <span class="product-name">{{ $item->producto->nombre ?? 'Producto eliminado' }}</span>
                        <span class="quantity">{{ $item->total_vendido }} vendidos</span>
                    </li>
                    @if ($loop->last)</ol>@endif
                @empty
                    <p class="empty-list">Todavía no hay productos vendidos para mostrar.</p>
                @endforelse
            </article>
        </section>

        <section class="card section inventory">
            <div class="section-title">
                <h2>Alertas de inventario</h2>
                <span class="badge">{{ $insumosCriticos->count() }} críticos</span>
            </div>
            <div class="critical-grid">
                @forelse ($insumosCriticos as $insumo)
                    <div class="critical-item">
                        <strong>{{ $insumo->nombre ?? $insumo->insumo ?? 'Insumo' }}</strong>
                        <span>Stock: {{ $insumo->stock }} / Mínimo: {{ $insumo->stock_minimo }}</span>
                    </div>
                @empty
                    <p class="empty-list">✓ No hay insumos con stock crítico.</p>
                @endforelse
            </div>
        </section>

        <section class="card section">
            <div class="section-title">
                <h2>Historial de reportes</h2>
                <span class="badge">{{ $reportes->count() }} registros</span>
            </div>
            <div class="table-wrap">
                <table>
                    <thead>
                        <tr>
                            <th>Título</th>
                            <th>Tipo</th>
                            <th>Periodo</th>
                            <th>Monto total</th>
                            <th>Creado por</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($reportes as $reporte)
                            <tr>
                                <td><strong>{{ $reporte->titulo }}</strong></td>
                                <td><span class="type">{{ $reporte->tipo }}</span></td>
                                <td>
                                    {{ $reporte->fecha_inicio?->format('d/m/Y') ?? 'Sin fecha' }}
                                    — {{ $reporte->fecha_fin?->format('d/m/Y') ?? 'Sin fecha' }}
                                </td>
                                <td>S/ {{ number_format($reporte->monto_total, 2) }}</td>
                                <td>{{ $reporte->usuario->name ?? 'Usuario eliminado' }}</td>
                                <td>
                                    <div class="actions">
                                        <a href="{{ route('reportes.show', $reporte) }}" class="btn btn-light">Ver</a>
                                        <a href="{{ route('reportes.edit', $reporte) }}" class="btn btn-light">Editar</a>
                                        <form action="{{ route('reportes.destroy', $reporte) }}" method="POST" onsubmit="return confirm('¿Seguro que deseas eliminar este reporte?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger">Eliminar</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="6" class="empty">No hay reportes guardados. Genera el primero.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const ventas = @json($ventasPorDia->reverse()->values());
        const canvas = document.getElementById('ventasChart');

        if (canvas && typeof Chart !== 'undefined') {
            new Chart(canvas, {
                type: 'line',
                data: {
                    labels: ventas.map(item => {
                        const [year, month, day] = item.fecha.split('-');
                        return `${day}/${month}`;
                    }),
                    datasets: [{
                        label: 'Ventas (S/)',
                        data: ventas.map(item => Number(item.total)),
                        borderColor: '#0284c7',
                        backgroundColor: 'rgba(56, 189, 248, .16)',
                        fill: true,
                        tension: .38,
                        pointRadius: 4,
                        pointBackgroundColor: '#ffffff',
                        pointBorderWidth: 3
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { display: false } },
                    scales: {
                        x: { grid: { display: false } },
                        y: {
                            beginAtZero: true,
                            ticks: { callback: value => 'S/ ' + value },
                            grid: { color: '#e6f2f5' }
                        }
                    }
                }
            });
        }
    </script>
</x-app-layout>