@extends('layouts.app')

@section('content')
<div class="detalles-container">
    {{-- Encabezado --}}
    <div class="detalles-header">
        <div>
            <h1 class="detalles-title">Detalles de Pedidos</h1>
            <p class="detalles-subtitle">Monitoreo y gestión de productos asociados a cada pedido.</p>
        </div>
    </div>

    {{-- Alerta de éxito --}}
    @if(session('success'))
        <div class="alert-success">
            <span>{{ session('success') }}</span>
            <button onclick="this.parentElement.remove()" class="btn-close">&times;</button>
        </div>
    @endif

    {{-- Alert de errores de validación --}}
@if($errors->any())
    <div class="alert-danger" style="background-color: #fef2f2; border: 1px solid #fca5a5; color: #991b1b; padding: 1rem; border-radius: 0.375rem; margin-bottom: 1.5rem;">
        <ul style="margin-left: 1.25rem; list-style-type: disc;">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

    {{-- Tabla de Detalles --}}
    <div class="table-card">
        <div class="table-responsive">
            <table class="detalles-table">
                <thead>
                    <tr>
                        <th>ID Detalle</th>
                        <th># Pedido</th>
                        <th>Producto</th>
                        <th>Precio Unitario</th>
                        <th>Cantidad</th>
                        <th>Subtotal</th>
                        <th>Fecha de Registro</th>
                        <th style="text-align: center;">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($detalles as $detalle)
                        <tr>
                            {{-- ID --}}
                            <td style="font-weight: bold;">#{{ $detalle->id }}</td>

                            {{-- Pedido Enlazado --}}
                            <td>
                                <a href="{{ route('pedidos.show', $detalle->pedido_id) }}" class="badge-pedido">
                                    Pedido #{{ $detalle->pedido_id }}
                                </a>
                            </td>

                            {{-- Producto --}}
                            <td style="font-weight: 600;">
                                {{ $detalle->producto ? $detalle->producto->nombre : 'Producto no disponible' }}
                            </td>

                            {{-- Precio Unitario --}}
                            <td>${{ number_format($detalle->precio_unitario, 2) }}</td>

                            {{-- Formulario de Edición de Cantidad --}}
                            <td>
                                <form action="{{ route('detalle_pedidos.update', $detalle) }}" method="POST" class="inline-form">
                                    @csrf
                                    @method('PUT')
                                    <input type="number" 
                                           name="cantidad" 
                                           value="{{ $detalle->cantidad }}" 
                                           min="1" 
                                           class="input-cantidad"
                                           onchange="this.form.submit()">
                                </form>
                            </td>

                            {{-- Subtotal --}}
                            <td style="font-weight: 700; color: #059669;">
                                ${{ number_format($detalle->subtotal, 2) }}
                            </td>

                            {{-- Fecha --}}
                            <td>{{ $detalle->created_at ? $detalle->created_at->format('d/m/Y H:i') : 'N/A' }}</td>

                            {{-- Acciones --}}
                            <td style="text-align: center;">
                                <div class="actions-wrapper">
                                    {{-- Ver detalle específico --}}
                                    <a href="{{ route('pedidos.show', $detalle->pedido_id) }}" class="btn-link-action">
                                    Ver Pedido
                                    </a>

                                    {{-- Eliminar detalle --}}
                                    <form action="{{ route('detalle_pedidos.destroy', $detalle) }}" 
                                          method="POST" 
                                          style="display: inline;" 
                                          onsubmit="return confirm('¿Deseas eliminar este ítem del pedido? Se recalculará el total automáticamente.');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn-link-danger">Eliminar</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" style="text-align: center; color: #6b7280; padding: 2rem;">
                                No hay detalles de pedidos registrados.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection