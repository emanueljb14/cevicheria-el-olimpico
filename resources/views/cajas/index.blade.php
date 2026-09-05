@extends('layouts.app')

@section('title', 'Cierre de Caja')
@section('page-title', 'Gestión y Cierre de Caja')

@section('content')
<div class="container-fluid p-0">

    <!-- CABECERA DE LA SECCIÓN -->
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h4 class="fw-bold text-dark mb-1">Cierre y Arqueo de Caja</h4>
            <p class="text-muted small mb-0">Gestione las aperturas, cuadres diarios y cierres de turno.</p>
        </div>
        <button type="button" class="btn btn-warning rounded-pill fw-bold text-dark px-4 shadow-sm" data-bs-toggle="modal" data-bs-target="#arqueoCajaModal">
            <i class="fa-solid fa-vault me-1"></i> Abrir Arqueo de Caja
        </button>
    </div>

    <!-- TARJETAS DE RESUMEN DEL DÍA -->
    <div class="row g-3 mb-4">
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card border-0 shadow-sm rounded-4 p-3">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <span class="text-muted extra-small fw-bold text-uppercase">Fondo Inicial</span>
                        <h4 class="fw-bold mb-0 mt-1 text-dark">S/ {{ number_format($cajaActual->monto_apertura ?? 100, 2) }}</h4>
                    </div>
                    <div class="p-3 bg-secondary-subtle text-secondary rounded-4 fs-4">
                        <i class="fa-solid fa-wallet"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card border-0 shadow-sm rounded-4 p-3">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <span class="text-muted extra-small fw-bold text-uppercase">Ventas del Sistema</span>
                        <h4 class="fw-bold mb-0 mt-1 text-primary">S/ {{ number_format($ventasHoy ?? 0, 2) }}</h4>
                    </div>
                    <div class="p-3 bg-primary-subtle text-primary rounded-4 fs-4">
                        <i class="fa-solid fa-cash-register"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card border-0 shadow-sm rounded-4 p-3">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <span class="text-muted extra-small fw-bold text-uppercase">Gastos de Caja</span>
                        <h4 class="fw-bold mb-0 mt-1 text-danger">S/ {{ number_format($gastosHoy ?? 0, 2) }}</h4>
                    </div>
                    <div class="p-3 bg-danger-subtle text-danger rounded-4 fs-4">
                        <i class="fa-solid fa-hand-holding-dollar"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card border-0 shadow-sm rounded-4 p-3">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <span class="text-muted extra-small fw-bold text-uppercase">Estado de Caja</span>
                        <h4 class="fw-bold mb-0 mt-1 text-success">
                            @if(isset($cajaAbierta) && $cajaAbierta)
                                <span class="badge bg-success-subtle text-success fs-6 fw-bold">Abierta</span>
                            @else
                                <span class="badge bg-secondary-subtle text-secondary fs-6 fw-bold">Cerrada</span>
                            @endif
                        </h4>
                    </div>
                    <div class="p-3 bg-success-subtle text-success rounded-4 fs-4">
                        <i class="fa-solid fa-lock-open"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- TABLA DE HISTORIAL DE CIERRES -->
    <div class="card border-0 shadow-sm rounded-4 p-4">
        <div class="d-flex align-items-center justify-content-between mb-3">
            <h5 class="fw-bold text-dark mb-0">Historial de Cierres de Caja</h5>
            <span class="badge bg-light text-dark border">Últimos registros</span>
        </div>

        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th># ID</th>
                        <th>Fecha y Hora</th>
                        <th>Usuario</th>
                        <th>Fondo Inicial</th>
                        <th>Ventas Sistema</th>
                        <th>Total Arqueado</th>
                        <th>Diferencia</th>
                        <th>Estado</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($historialCajas ?? [] as $caja)
                        <tr>
                            <td class="fw-bold">#{{ $caja->id }}</td>
                            <td>{{ $caja->created_at->format('d/m/Y h:i A') }}</td>
                            <td>{{ $caja->user->name ?? 'Cajero' }}</td>
                            <td>S/ {{ number_format($caja->monto_apertura, 2) }}</td>
                            <td class="fw-bold">S/ {{ number_format($caja->monto_sistema, 2) }}</td>
                            <td class="fw-bold text-primary">S/ {{ number_format($caja->total_arqueado, 2) }}</td>
                            <td>
                                @php $dif = $caja->total_arqueado - $caja->monto_sistema; @endphp
                                <span class="fw-bold {{ $dif < 0 ? 'text-danger' : ($dif > 0 ? 'text-info' : 'text-success') }}">
                                    S/ {{ number_format($dif, 2) }}
                                </span>
                            </td>
                            <td>
                                @if($dif == 0)
                                    <span class="badge bg-success">Cuadrado</span>
                                @elseif($dif > 0)
                                    <span class="badge bg-info text-dark">Sobrante</span>
                                @else
                                    <span class="badge bg-danger">Faltante</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted py-4">No hay cierres de caja registrados anteriormente.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection