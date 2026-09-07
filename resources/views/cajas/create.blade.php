@extends('layouts.app')

@section('title', 'Nuevo Cierre de Caja')

@push('styles')
<style>
    .caja-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 16px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.03);
    }
    .kpi-card {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        padding: 15px;
    }
    .kpi-title {
        font-size: 0.75rem;
        font-weight: 800;
        text-transform: uppercase;
        color: #64748b;
    }
    .kpi-value {
        font-size: 1.4rem;
        font-weight: 800;
        color: #0f172a;
        margin-top: 4px;
    }
    .form-label {
        font-size: 0.82rem;
        font-weight: 700;
        color: #334155;
    }
</style>
@endpush

@section('content')
<div class="container-fluid py-3">

    <!-- Encabezado y Regreso -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <span class="text-uppercase text-muted fw-bold" style="font-size: 0.75rem; letter-spacing: 0.08em;">CONTROL OPERATIVO</span>
            <h2 class="fw-bold m-0 text-dark">Registrar Nuevo Arqueo</h2>
        </div>
        <a href="{{ route('cierre-caja.index') }}" class="btn btn-outline-secondary btn-sm rounded-3 fw-bold">
            <i class="fa-solid fa-arrow-left me-1"></i> Volver al Historial
        </a>
    </div>

    <!-- Alert de Errores de Validación -->
    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm mb-4" role="alert">
            <i class="fa-solid fa-triangle-exclamation me-2"></i> Por favor corrige los errores del formulario.
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="caja-card p-4">
                <h5 class="fw-bold text-dark mb-4">
                    <i class="fa-solid fa-cash-register me-2 text-warning"></i>Ingreso de Valores Físicos
                </h5>

                <!-- KPIs en tiempo real -->
                <div class="row g-3 mb-4">
                    <div class="col-md-4">
                        <div class="kpi-card">
                            <div class="kpi-title">Ventas Sistema</div>
                            <div class="kpi-value text-primary">S/ {{ number_format($totalVentasHoy ?? 0, 2) }}</div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="kpi-card">
                            <div class="kpi-title">Total Físico Contado</div>
                            <div class="kpi-value text-success" id="kpiArqueado">S/ 0.00</div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="kpi-card">
                            <div class="kpi-title">Diferencia Calculada</div>
                            <div class="kpi-value" id="kpiDiferencia">S/ 0.00</div>
                        </div>
                    </div>
                </div>

                <!-- Formulario mapeado a store() -->
                <form action="{{ route('cierre-caja.store') }}" method="POST">
                    @csrf

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Efectivo Físico en Caja (S/)</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0"><i class="fa-solid fa-money-bill-wave text-success"></i></span>
                                <input type="number" step="0.01" min="0" name="efectivo_fisico" id="efectivo_fisico" class="form-control border-start-0 @error('efectivo_fisico') is-invalid @enderror" value="{{ old('efectivo_fisico', '0.00') }}" required>
                            </div>
                            @error('efectivo_fisico')
                                <span class="text-danger small">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Cobros Digitales / POS / Yape (S/)</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0"><i class="fa-solid fa-mobile-screen text-primary"></i></span>
                                <input type="number" step="0.01" min="0" name="digital_fisico" id="digital_fisico" class="form-control border-start-0 @error('digital_fisico') is-invalid @enderror" value="{{ old('digital_fisico', '0.00') }}" required>
                            </div>
                            @error('digital_fisico')
                                <span class="text-danger small">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="col-12">
                            <label class="form-label">Observación (Opcional)</label>
                            <textarea name="observacion" rows="3" class="form-control @error('observacion') is-invalid @enderror" placeholder="Detalle variaciones o notas adicionales del arqueo...">{{ old('observacion') }}</textarea>
                            @error('observacion')
                                <span class="text-danger small">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="d-flex justify-content-end gap-2 mt-4">
                        <a href="{{ route('cierre-caja.index') }}" class="btn btn-light fw-semibold px-4">Cancelar</a>
                        <button type="submit" class="btn btn-warning fw-bold px-4 rounded-3 shadow-sm">
                            <i class="fa-solid fa-vault me-1"></i> Guardar Cierre de Caja
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const efectivoInput = document.getElementById('efectivo_fisico');
        const digitalInput = document.getElementById('digital_fisico');
        const kpiArqueado = document.getElementById('kpiArqueado');
        const kpiDiferencia = document.getElementById('kpiDiferencia');
        
        const totalSistema = {{ $totalVentasHoy ?? 0 }};

        function calcularTotales() {
            const efectivo = parseFloat(efectivoInput.value) || 0;
            const digital = parseFloat(digitalInput.value) || 0;
            
            const totalArqueado = efectivo + digital;
            const diferencia = totalArqueado - totalSistema;

            kpiArqueado.textContent = `S/ ${totalArqueado.toFixed(2)}`;
            kpiDiferencia.textContent = `S/ ${diferencia.toFixed(2)}`;

            if (diferencia < 0) {
                kpiDiferencia.className = 'kpi-value text-danger';
            } else if (diferencia > 0) {
                kpiDiferencia.className = 'kpi-value text-success';
            } else {
                kpiDiferencia.className = 'kpi-value text-dark';
            }
        }

        efectivoInput.addEventListener('input', calcularTotales);
        digitalInput.addEventListener('input', calcularTotales);
        
        calcularTotales();
    });
</script>
@endpush