@extends('layouts.app')

@section('content')
<div class="pedidos-container">

    {{-- Encabezado e Información General --}}
    <div class="pedidos-header">
        <div>
            <h1 class="pedidos-title">Pedido #{{ $pedido->id }}</h1>
            <p class="pedidos-subtitle">
                Fecha: {{ $pedido->created_at ? $pedido->created_at->format('d/m/Y H:i') : now()->format('d/m/Y H:i') }}
            </p>
        </div>
        <a href="{{ route('pedidos.index') }}" class="btn-secondary">
            &larr; Volver a Pedidos
        </a>
    </div>

    {{-- Alert de éxito --}}
    @if(session('success'))
        <div class="alert-success" style="background-color: #def7ec; border: 1px solid #84e1bc; color: #03543f; padding: 1rem; border-radius: 0.375rem; margin-bottom: 1.5rem; display: flex; justify-content: space-between; align-items: center;">
            <span>{{ session('success') }}</span>
            <button onclick="this.parentElement.remove()" class="btn-close" style="background:none; border:none; font-size:1.25rem; cursor:pointer;">&times;</button>
        </div>
    @endif

    {{-- Alert de error por sesión --}}
    @if(session('error'))
        <div class="alert-danger" style="background-color: #fde8e8; border: 1px solid #f8b4b4; color: #9b1c1c; padding: 1rem; border-radius: 0.375rem; margin-bottom: 1.5rem;">
            <span>{{ session('error') }}</span>
        </div>
    @endif

    {{-- Alert de errores de validación --}}
    @if($errors->any())
        <div class="alert-danger" style="background-color: #fde8e8; border: 1px solid #f8b4b4; color: #9b1c1c; padding: 1rem; border-radius: 0.375rem; margin-bottom: 1.5rem;">
            <ul style="margin: 0; padding-left: 1.25rem;">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Tarjeta de Información del Pedido --}}
    <div class="table-card">
        <div class="grid-info">
            <div>
                <span class="info-label">Tipo de Pedido:</span>
                <p class="info-value">
                    {{ ucfirst($pedido->tipo ?? $pedido->tipo_pedido ?? 'En Local') }}
                    @if($pedido->mesa)
                        - Mesa {{ $pedido->mesa->numero }}
                    @endif
                </p>
            </div>
            <div>
                <span class="info-label">Cliente:</span>
                <p class="info-value">
                    {{ $pedido->cliente ? $pedido->cliente->nombre : 'Cliente General' }}
                </p>
            </div>
            <div>
                <span class="info-label">Atendido por:</span>
                <p class="info-value">
                    {{ $pedido->usuario ? $pedido->usuario->name : 'N/A' }}
                </p>
            </div>
            <div>
                <span class="info-label">Estado:</span>
                <p style="margin-top: 0.35rem;">
                    <span class="badge-status">
                        {{ strtoupper($pedido->estado ?? 'PENDIENTE') }}
                    </span>
                </p>
            </div>
        </div>
    </div>

    {{-- Formulario para Agregar Nuevo Producto al Detalle --}}
    <div class="table-card" style="background-color: #f9fafb;">
        <h3 style="font-size: 1rem; font-weight: 700; margin-top: 0; margin-bottom: 1rem; color: #1f2937;">+ Agregar Producto a este Pedido</h3>
        
        <form action="{{ route('detalle_pedidos.store') }}" method="POST" class="form-agregar-item">
            @csrf
            <input type="hidden" name="pedido_id" value="{{ $pedido->id }}">

            <div class="form-group">
                <label>Producto</label>
                <select name="producto_id" required class="form-control">
                    <option value="">-- Seleccionar Producto --</option>
                    @foreach($productos as $producto)
                        <option value="{{ $producto->id }}">{{ $producto->nombre }} - S/ {{ number_format($producto->precio, 2) }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label>Cantidad</label>
                <input type="number" name="cantidad" value="1" min="1" required class="form-control">
            </div>

            <div>
                <button type="submit" class="btn-primary">
                    Agregar Ítem
                </button>
            </div>
        </form>
    </div>

    {{-- Tabla de Detalles del Pedido --}}
    <div class="table-card" style="padding: 0; overflow: hidden;">
        <div style="overflow-x: auto;">
            <table class="pedidos-table">
                <thead>
                    <tr>
                        <th>Producto</th>
                        <th>Precio Unitario</th>
                        <th>Cantidad</th>
                        <th>Subtotal</th>
                        <th style="text-align: center;">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pedido->detalles as $detalle)
                        <tr>
                            <td style="font-weight: 600; color: #111827;">
                                {{ $detalle->producto ? $detalle->producto->nombre : 'Producto No Disponible' }}
                            </td>
                            <td>S/ {{ number_format($detalle->precio_unitario, 2) }}</td>
                            
                            {{-- Modificar Cantidad --}}
                            <td>
                                <form action="{{ route('detalle_pedidos.update', $detalle) }}" method="POST" style="margin: 0;">
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

                            <td style="font-weight: 700; color: #059669;">
                                S/ {{ number_format($detalle->subtotal, 2) }}
                            </td>

                            {{-- Eliminar Detalle --}}
                            <td style="text-align: center;">
                                <form action="{{ route('detalle_pedidos.destroy', $detalle) }}" method="POST" onsubmit="return confirm('¿Quitar producto del pedido?');" style="margin: 0;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-link-danger">Quitar</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" style="text-align: center; color: #6b7280; padding: 2rem;">
                                Este pedido no tiene productos registrados.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
                <tfoot>
                    <tr style="background-color: #f9fafb;">
                        <td colspan="3" style="text-align: right; font-weight: 700; color: #1f2937; padding: 1rem;">TOTAL DEL PEDIDO:</td>
                        <td style="font-weight: 800; color: #2563eb; font-size: 1.125rem; padding: 1rem;">
                            S/ {{ number_format($pedido->total, 2) }}
                        </td>
                        <td></td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>

</div>
@endsection