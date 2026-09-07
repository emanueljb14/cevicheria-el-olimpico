@extends('layouts.app')

@section('title', 'Cierre de Caja')
@section('page-title', 'Arqueo y Cierre de Caja')

@push('styles')
<style>
    .caja-page {
        --navy: #062b3d;
        --sea: #00a7a7;
        --line: #dfe9eb;
        --paper: #ffffff;
        --muted: #68757d;
        --bg-subtle: #f8fafc;
    }

    .caja-header {
        margin-bottom: 24px;
    }
    .caja-subtitle {
        font-size: 11px;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 0.08em;
        color: var(--sea);
        margin: 0 0 4px;
    }
    .caja-title {
        font: 700 28px 'Playfair Display', serif;
        color: var(--navy);
        margin: 0;
    }

    /* Tarjetas KPI de resumen superior */
    .kpi-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 16px;
        margin-bottom: 24px;
    }
    .kpi-card {
        background: var(--paper);
        border: 1px solid var(--line);
        border-radius: 16px;
        padding: 16px 20px;
        box-shadow: 0 4px 12px rgba(6,43,61,.03);
    }
    .kpi-title {
        font-size: 10px;
        font-weight: 800;
        text-transform: uppercase;
        color: var(--muted);
        letter-spacing: 0.05em;
    }
    .kpi-value {
        font-size: 22px;
        font-weight: 800;
        color: var(--navy);
        margin-top: 6px;
    }

    /* Contenedor principal del formulario */
    .caja-card {
        background: var(--paper);
        border: 1px solid var(--line);
        border-radius: 20px;
        padding: 28px;
        box-shadow: 0 8px 24px rgba(6,43,61,.04);
    }

    .form-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 28px;
        margin-bottom: 24px;
    }

    .section-title {
        font-size: 12px;
        font-weight: 800;
        text-transform: uppercase;
        color: var(--sea);
        letter-spacing: 0.05em;
        margin-bottom: 16px;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .form-group {
        margin-bottom: 16px;
    }
    .form-group label {
        display: block;
        font-size: 12px;
        font-weight: 700;
        color: var(--navy);
        margin-bottom: 6px;
    }
    .form-control {
        width: 100%;
        padding: 10px 14px;
        border: 1px solid var(--line);
        border-radius: 10px;
        font-size: 14px;
        background: var(--bg-subtle);
        color: var(--navy);
        transition: border-color .2s;
    }
    .form-control:focus {
        outline: none;
        border-color: var(--sea);
        background: #fff;
    }

    .btn-submit {
        background: #f6c453;
        color: #000;
        font-weight: 800;
        font-size: 14px;
        padding: 14px 28px;
        border: none;
        border-radius: 12px;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: transform .2s, background-color .2s;
    }
    .btn-submit:hover {
        background: #e5b342;
        transform: translateY(-2px);
    }

    @media (max-width: 768px) {
        .form-grid { grid-template-columns: 1fr; }
    }
</style>
@endpush

@section('content')
<div class="caja-page">

    <!-- Encabezado de la Sección -->
    <div class="caja-header">
        <p class="caja-subtitle">Operaciones Diarias</p>
        <h1 class="caja-title">Arqueo y Cierre de Caja del Día</h1>
    </div>

    <!-- Resumen de Métricas -->
    <div class="kpi-grid">
        <div class="kpi-card">
            <div class="kpi-title">Arqueo del Sistema</div>
            <div class="kpi-value">S/ {{ number_format($montoSistema ?? 0, 2) }}</div>
        </div>
        <div class="kpi-card">
            <div class="kpi-title">Total Arqueado</div>
            <div class="kpi-value" style="color: var(--sea);">S/ <span id="totalArqueadoLabel">0.00</span></div>
        </div>
        <div class="kpi-card">
            <div class="kpi-title">Diferencia</div>
            <div class="kpi-value" id="diferenciaLabel">S/ 0.00</div>
        </div>
        <div class="kpi-card">
            <div class="kpi-title">Estado del Cierre</div>
            <div class="kpi-value" style="font-size: 14px; font-weight: 700; margin-top: 10px;">
                <span style="background: #e2e8f0; padding: 4px 10px; border-radius: 999px; color: #475569;">Sin Procesar</span>
            </div>
        </div>
    </div>

    <!-- Formulario Principal -->
    <div class="caja-card">
        <form action="{{ route('cierre-caja.store') }}" method="POST">
            @csrf
            <div class="form-grid">
                
                <!-- Columna 1: Conteo Efectivo -->
                <div>
                    <div class="section-title">
                        <i class="fa-solid fa-money-bill-wave"></i> Conteo en Efectivo
                    </div>

                    <div class="form-group">
                        <label>Fondo inicial de Caja (S/)</label>
                        <input type="number" step="0.01" name="fondo_inicial" id="fondo_inicial" class="form-control" value="100.00">
                    </div>

                    <div class="form-group">
                        <label>Efectivo Físico Recaudado (S/)</label>
                        <input type="number" step="0.01" name="efectivo_fisico" id="efectivo_fisico" class="form-control" placeholder="0.00" value="0.00">
                    </div>

                    <div class="form-group">
                        <label>Salidas / Gastos Directos de Caja (S/)</label>
                        <input type="number" step="0.01" name="gastos_caja" id="gastos_caja" class="form-control" placeholder="0.00" value="0.00">
                    </div>
                </div>

                <!-- Columna 2: Pagos Digitales -->
                <div>
                    <div class="section-title">
                        <i class="fa-solid fa-mobile-screen"></i> Cobros Digitales y POS
                    </div>

                    <div class="form-group">
                        <label>Yape / Plin (S/)</label>
                        <input type="number" step="0.01" name="monto_yape_plin" class="form-control" placeholder="0.00" value="0.00">
                    </div>

                    <div class="form-group">
                        <label>Vouchers Tarjeta / POS (S/)</label>
                        <input type="number" step="0.01" name="monto_tarjeta" class="form-control" placeholder="0.00" value="0.00">
                    </div>

                    <div class="form-group">
                        <label>Esperado en Sistema POS (Opcional) (S/)</label>
                        <input type="number" step="0.01" name="esperado_pos" class="form-control" placeholder="0.00" value="0.00">
                    </div>
                </div>

            </div>

            <!-- Observaciones -->
            <div class="form-group" style="margin-bottom: 24px;">
                <label>Notas u Observaciones sobre el Cuadre</label>
                <textarea name="observaciones" rows="3" class="form-control" placeholder="Describa diferencias, vales de compras o incidencias del turno..."></textarea>
            </div>

            <!-- Botón de acción -->
            <div style="text-align: right;">
                <button type="submit" class="btn-submit">
                    <i class="fa-solid fa-lock"></i> Cerrar Caja y Generar Ticket
                </button>
            </div>
        </form>
    </div>

</div>
@endsection