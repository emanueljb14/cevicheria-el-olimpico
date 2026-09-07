@extends('layouts.app')

@section('title', 'Cierre de Caja')

@push('styles')
<style>
    .caja-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 16px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.03);
    }
    .kpi-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 14px;
        padding: 20px;
    }
    .kpi-title {
        font-size: 0.75rem;
        font-weight: 800;
        text-transform: uppercase;
        color: #64748b;
    }
    .kpi-value {
        font-size: 1.6rem;
        font-weight: 800;
        color: #0f172a;
        margin-top: 6px;
    }
    .badge-estado {
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 700;
        text-transform: uppercase;
    }
    .badge-cerrado { background-color: #fee2e2; color: #991b1b; }
    .badge-abierto { background-color: #dcfce7; color: #166534; }
</style>
@endpush

@section('content')
<div class="container-fluid py-3">
    
    <!-- Encabezado con Botón para Abrir Vista Dedicada Create -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <span class="text-uppercase text-muted fw-bold" style="font-size: 0.75rem; letter-spacing: 0.08em;">CONTROL OPERATIVO</span>
            <h2 class="fw-bold m-0 text-dark">Arqueo y Cierre de Caja</h2>
        </div>
        <a href="{{ route('cierre-caja.create') }}" class="btn btn-warning fw-bold px-3 py-2 rounded-3 shadow-sm">
            <i class="fa-solid fa-plus me-1"></i> Nuevo Cierre de Caja
        </a>
    </div>

    <!-- Alert de Notificaciones -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm mb-4" role="alert">
            <i class="fa-solid fa-circle-check me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm mb-4" role="alert">
            <i class="fa-solid fa-triangle-exclamation me-2"></i> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Tarjeta KPI con Total Ventas Hoy -->
    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="kpi-card">
                <div class="kpi-title">Ventas Registradas Sistema (Hoy)</div>
                <div class="kpi-value text-primary">S/ {{ number_format($totalVentasHoy, 2) }}</div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="kpi-card">
                <div class="kpi-title">Último Cierre Registrado</div>
                <div class="kpi-value text-dark">
                    S/ {{ number_format($arqueos->first()->total_sistema ?? 0, 2) }}
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="kpi-card">
                <div class="kpi-title">Estado de Turno</div>
                <div class="kpi-value fs-5 mt-2">
                    <span class="badge-estado badge-abierto"><i class="fa-solid fa-lock-open me-1"></i> Operativo</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Historial de Arqueos ($arqueos) -->
    <div class="caja-card p-4">
        <h5 class="fw-bold text-dark mb-4"><i class="fa-solid fa-clock-rotate-left me-2 text-secondary"></i>Historial de Cierres Registrados</h5>
        
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr class="text-secondary" style="font-size: 0.8rem;">
                        <th>FECHA / HORA</th>
                        <th>CAJERO</th>
                        <th>EFECTIVO</th>
                        <th>DIGITAL</th>
                        <th>TOTAL SISTEMA</th>
                        <th>DIFERENCIA</th>
                        <th>ESTADO</th>
                        <th class="text-end">ACCIONES</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($arqueos as $item)
                        <tr>
                            <td class="fw-semibold">{{ $item->created_at->format('d/m/Y h:i A') }}</td>
                            <td>{{ $item->usuario->name ?? 'Usuario Sistema' }}</td>
                            <td>S/ {{ number_format($item->efectivo_fisico, 2) }}</td>
                            <td>S/ {{ number_format($item->digital_fisico, 2) }}</td>
                            <td class="fw-bold">S/ {{ number_format($item->total_sistema, 2) }}</td>
                            <td>
                                @if($item->diferencia < 0)
                                    <span class="text-danger fw-bold">S/ {{ number_format($item->diferencia, 2) }}</span>
                                @elseif($item->diferencia > 0)
                                    <span class="text-success fw-bold">+S/ {{ number_format($item->diferencia, 2) }}</span>
                                @else
                                    <span class="text-muted fw-bold">S/ 0.00</span>
                                @endif
                            </td>
                            <td>
                                <span class="badge-estado badge-cerrado">{{ strtoupper($item->estado) }}</span>
                            </td>
                            <td class="text-end">
                                <a href="{{ route('cierre-caja.show', $item->id) }}" class="btn btn-sm btn-outline-secondary border-0" title="Ver Detalle">
                                    <i class="fa-solid fa-eye"></i>
                                </a>
                                <a href="{{ route('cierre-caja.edit', $item->id) }}" class="btn btn-sm btn-outline-warning border-0" title="Editar Observación">
                                    <i class="fa-solid fa-pen"></i>
                                </a>
                                <form action="{{ route('cierre-caja.destroy', $item->id) }}" method="POST" class="d-inline" onsubmit="return confirm('¿Eliminar este registro de cierre?');">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger border-0" title="Eliminar">
                                        <i class="fa-solid fa-trash-can"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-4 text-muted">No existen cierres de caja registrados en el sistema.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="d-flex justify-content-end mt-3">
            {{ $arqueos->links() }}
        </div>
    </div>

</div>
@endsection