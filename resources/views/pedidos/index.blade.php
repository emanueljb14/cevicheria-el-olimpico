@extends('layouts.app')

{{-- Importamos el archivo CSS de pedidos en la cabecera usando Vite --}}
@push('styles')
    @vite(['resources/css/pedidos.css'])
@endpu

@section('content')
<div class="pedidos-container">
    {{-- Encabezado --}}
    <div class="pedidos-header">
        <div>
            <h1 class="pedidos-title">Listado de Pedidos</h1>
            <p class="pedidos-subtitle">Gestiona y realiza seguimiento a los pedidos del sistema.</p>
        </div>
        <a href="{{ route('pedidos.create') }}" class="btn-primary">
            <svg style="width:20px;height:20px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            Nuevo Pedido
        </a>
    </div>

    {{-- Alerta de Éxito --}}
@if(session('success'))
    <div class="alert-success">
        <span>{{ session('success') }}</span>
        <button onclick="this.parentElement.remove()" class="btn-close">&times;</button>
    </div>
@endif

{{-- Alerta de Error (Si falla cambiarEstado) --}}
@if(session('error') || $errors->any())
    <div style="background-color: #fee2e2; border: 1px solid #fca5a5; color: #991b1b; padding: 1rem; border-radius: 0.375rem; margin-bottom: 1.5rem;">
        <span>{{ session('error') ?? $errors->first() }}</span>
    </div>
@endif

    {{-- Tabla de Pedidos --}}
    <div class="table-card">
        <div class="table-responsive">
            <table class="pedidos-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Tipo / Ubicación</th>
                        <th>Cliente</th>
                        <th>Atendido por</th>
                        <th>Total</th>
                        <th>Estado</th>
                        <th>Fecha</th>
                        <th style="text-align: center;">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pedidos as $pedido)
                        <tr>
                            <td style="font-weight: bold;">#{{ $pedido->id }}</td>

                            {{-- Tipo de Pedido --}}
                            <td>
                                @if($pedido->tipo === 'local')
                                    <span class="badge badge-local">
                                        Local {{ $pedido->mesa ? ' - Mesa ' . $pedido->mesa->numero : '' }}
                                    </span>
                                @elseif($pedido->tipo === 'recojo')
                                    <span class="badge badge-recojo">Para Llevar</span>
                                @else
                                    <span class="badge badge-delivery">Delivery</span>
                                @endif
                            </td>

                            {{-- Cliente y Usuario --}}
                            <td>{{ $pedido->cliente ? $pedido->cliente->nombre : 'Cliente General' }}</td>
                            <td>{{ $pedido->usuario ? $pedido->usuario->name : 'N/A' }}</td>

                            {{-- Total --}}
                            <td style="font-weight: 600;">${{ number_format($pedido->total, 2) }}</td>

                            {{-- Selector para cambiar estado rápido --}}
                            <td>
                                <form action="{{ route('pedidos.cambiarEstado', $pedido) }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <select name="estado" onchange="this.form.submit()" class="status-select status-{{ $pedido->estado }}">
                                        <option value="pendiente" {{ $pedido->estado === 'pendiente' ? 'selected' : '' }}>Pendiente</option>
                                        <option value="en_proceso" {{ $pedido->estado === 'en_proceso' ? 'selected' : '' }}>En Proceso</option>
                                        <option value="completado" {{ $pedido->estado === 'completado' ? 'selected' : '' }}>Completado</option>
                                        <option value="cancelado" {{ $pedido->estado === 'cancelado' ? 'selected' : '' }}>Cancelado</option>
                                    </select>
                                </form>
                            </td>

                            {{-- Fecha --}}   
                           <td>{{ $pedido->created_at ? $pedido->created_at->format('d/m/Y H:i') : 'N/A' }}</td>
                            

                            {{-- Acciones --}}
                            <td style="text-align: center;">
                                <a href="{{ route('pedidos.show', $pedido) }}" class="btn-link-action">Ver</a>
                                <form action="{{ route('pedidos.destroy', $pedido) }}" method="POST" style="display: inline;" onsubmit="return confirm('¿Estás seguro de eliminar este pedido?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-link-danger">Eliminar</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" style="text-align: center; color: #6b7280; padding: 2rem;">
                                No hay pedidos registrados actualmente.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection