@extends('layouts.app')

@section('title', 'Detalle de Cierre de Caja')

@push('styles')
<style>
    .caja-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 16px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.03);
    }
    .detail-item {
        padding: 12px 16px;
        border-bottom: 1px solid #f1f5f9;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    .detail-item:last-child {
        border-bottom: none;
    }
    .detail-label {
        font-size: 0.85rem;
        font-weight: 600;
        color: #64748b;
    }
    .detail-value {
        font-size: 0.95rem;
        font-weight: 700;
        color: #0f172a;
    }
    .badge-estado {
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 700;
        text-transform: uppercase;
    }
    .badge-cerrado { background-color: #fee2e2; color: #991b1b; }
</style>
@endpush

@section('content')
<div class="container-fluid py-3">

    <!-- Encabezado y Acciones -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <span class="text-uppercase text-muted fw-bold" style="font-size: 0.75rem; letter-spacing: 0.08em;">REVISIÓN DE AUDITORÍA</span>
            <h2 class="fw-bold m-0 text-dark">Detalle de Arqueo #{{ $cierreCaja->id }}</h2>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('cierre-caja.index') }}" class="btn btn-outline-secondary btn-sm rounded-3 fw-bold">
                <i class="fa-solid fa-arrow-left me-1"></i> Volver al Historial
            </a>
            <a href="{{ route('cierre-caja.edit', $cierreCaja->id) }}" class="btn btn-warning btn-sm rounded-3 fw-bold">
                <i class="fa-solid fa-pen me-1"></i> Editar Observación
            </a>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="caja-card p-4">
                
                <!-- Cabecera del Cierre -->
                <div class="d-flex justify-content-between align-items-center pb-3 mb-3 border-bottom">
                    <div>
                        <div class="text-muted small">Registrado por</div>
                        <div class="fw-bold text-dark fs-5">{{ $cierreCaja->usuario->name ?? 'Usuario Sistema' }}</div>
                    </div>
                    <div class="text-end">
                        <span class="badge-estado badge-cerrado mb-1 d-inline-block">{{ strtoupper($cierreCaja->estado) }}</span>
                        <div class="text-muted small">{{ $cierreCaja->created_at->format('d/m/Y - h:i A') }}</div>
                    </div>
                </div>

                <!-- Desglose de Valores -->
                <div class="bg-light rounded-3 p-2 mb-4">
                    <div class="detail-item">
                        <span class="detail-label"><i class="fa-solid fa-money-bill-wave me-2 text-success"></i>Efectivo Físico en Caja</span>
                        <span class="detail-value">S/ {{ number_format($cierreCaja->efectivo_fisico, 2) }}</span>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label"><i class="fa-solid fa-mobile-screen me-2 text-primary"></i>Cobros Digitales / POS / Yape</span>
                        <span class="detail-value">S/ {{ number_format($cierreCaja->digital_fisico, 2) }}</span>
                    </div>
                    <div class="detail-item bg-white rounded-2 shadow-sm my-1">
                        <span class="detail-label text-dark"><i class="fa-solid fa-calculator me-2 text-warning"></i>Total Físico Contado</span>
                        <span class="detail-value text-dark fs-6">S/ {{ number_format($cierreCaja->efectivo_fisico + $cierreCaja->digital_fisico, 2) }}</span>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label"><i class="fa-solid fa-receipt me-2 text-secondary"></i>Ventas Registradas por Sistema</span>
                        <span class="detail-value">S/ {{ number_format($cierreCaja->total_sistema, 2) }}</span>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label"><i class="fa-solid fa-scale-unbalanced me-2 text-info"></i>Diferencia de Cuadre</span>
                        <span class="detail-value fs-6">
                            @if($cierreCaja->diferencia < 0)
                                <span class="text-danger">S/ {{ number_format($cierreCaja->diferencia, 2) }} (Faltante)</span>
                            @elseif($cierreCaja->diferencia > 0)
                                <span class="text-success">+S/ {{ number_format($cierreCaja->diferencia, 2) }} (Sobrante)</span>
                            @else
                                <span class="text-muted">S/ 0.00 (Cuadre Perfecto)</span>
                            @endif
                        </span>
                    </div>
                </div>

                <!-- Observaciones -->
                <div class="mb-3">
                    <label class="form-label fw-bold text-secondary" style="font-size: 0.8rem;">OBSERVACIONES / NOTAS</label>
                    <div class="p-3 bg-light rounded-3 text-dark border">
                        {{ $cierreCaja->observacion ?? 'Sin observaciones registradas para este arqueo.' }}
                    </div>
                </div>

            </div>
        </div>
    </div>

</div>
@endsection