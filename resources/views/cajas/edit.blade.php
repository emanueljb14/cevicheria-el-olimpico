@extends('layouts.app')

@section('title', 'Editar Observación de Cierre')

@push('styles')
<style>
    .caja-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 16px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.03);
    }
    .read-only-box {
        background-color: #f8fafc;
        border: 1px dashed #cbd5e1;
        border-radius: 10px;
        padding: 12px 16px;
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

    <!-- Encabezado y Navegación -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <span class="text-uppercase text-muted fw-bold" style="font-size: 0.75rem; letter-spacing: 0.08em;">CONTROL OPERATIVO</span>
            <h2 class="fw-bold m-0 text-dark">Editar Observación del Arqueo #{{ $cierreCaja->id }}</h2>
        </div>
        <a href="{{ route('cierre-caja.index') }}" class="btn btn-outline-secondary btn-sm rounded-3 fw-bold">
            <i class="fa-solid fa-arrow-left me-1"></i> Volver al Historial
        </a>
    </div>

    <!-- Alert de Errores de Validación -->
    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm mb-4" role="alert">
            <i class="fa-solid fa-triangle-exclamation me-2"></i> Por favor corrige los errores antes de guardar.
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="caja-card p-4">

                <!-- Resumen no editable de los montos registrados -->
                <h6 class="fw-bold text-muted text-uppercase mb-3" style="font-size: 0.75rem;">Resumen de Auditoría (Solo Lectura)</h6>
                <div class="row g-3 mb-4">
                    <div class="col-md-3">
                        <div class="read-only-box">
                            <span class="text-muted d-block small fw-semibold">Efectivo Físico</span>
                            <span class="fw-bold text-dark fs-6">S/ {{ number_format($cierreCaja->efectivo_fisico, 2) }}</span>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="read-only-box">
                            <span class="text-muted d-block small fw-semibold">Digital / Yape</span>
                            <span class="fw-bold text-dark fs-6">S/ {{ number_format($cierreCaja->digital_fisico, 2) }}</span>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="read-only-box">
                            <span class="text-muted d-block small fw-semibold">Total Sistema</span>
                            <span class="fw-bold text-primary fs-6">S/ {{ number_format($cierreCaja->total_sistema, 2) }}</span>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="read-only-box">
                            <span class="text-muted d-block small fw-semibold">Diferencia</span>
                            <span class="fw-bold fs-6 {{ $cierreCaja->diferencia < 0 ? 'text-danger' : ($cierreCaja->diferencia > 0 ? 'text-success' : 'text-dark') }}">
                                S/ {{ number_format($cierreCaja->diferencia, 2) }}
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Formulario de Edición mapeado a update() -->
                <form action="{{ route('cierre-caja.update', $cierreCaja->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label class="form-label">Observaciones / Notas del Arqueo</label>
                        <textarea name="observacion" rows="4" class="form-control @error('observacion') is-invalid @enderror" placeholder="Modifique o añada aclaraciones sobre la diferencia o incidencias del turno...">{{ old('observacion', $cierreCaja->observacion) }}</textarea>
                        @error('observacion')
                            <span class="text-danger small">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="d-flex justify-content-end gap-2 mt-4">
                        <a href="{{ route('cierre-caja.index') }}" class="btn btn-light fw-semibold px-4">Cancelar</a>
                        <button type="submit" class="btn btn-warning fw-bold px-4 rounded-3 shadow-sm">
                            <i class="fa-solid fa-floppy-disk me-1"></i> Actualizar Observación
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>

</div>
@endsection