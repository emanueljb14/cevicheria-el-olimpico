@extends('layouts.app')

@section('content')
<div class="pedidos-container">
    {{-- Encabezado e Información General --}}
    <div class="pedidos-header">
        <div>
            <h1 class="pedidos-title">Pedido #{{ $pedido->id }}</h1>
            <p class="pedidos-subtitle">Fecha: {{ $pedido->created_at->format('d/m/Y H:i') }}</p>
       
 </div>
        <a href="{{ route('pedidos.index') }}" class="btn-primary" style="background-color: #4b5563;">
            &larr; Volver a Pedidos
        </a>
    </div>

    {{-- Alert de éxito --}}
    @if(session('success'))
        <div class="alert-success">
            <span>{{ session('success') }}</span>
            <button onclick="this.parentElement.remove()" class="btn-close">&times;</button>
        </div>
    @endif

{{-- Alert de error por sesión --}}
@if(session('error'))
    <div class="alert-danger" style="background-color: #fef2f2; border: 1px solid #fca5a5; color: #991b1b; padding: 1rem; border-radius: 0.375rem; margin-bottom: 1.5rem;">
        <span>{{ session('error') }}</span>
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

    {{-- Tarjeta de Información del Pedido --}}
    <div class="table-card" style="padding: 1.5rem; margin-bottom: 1.5rem;">
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem;">
            <div>
                <strong style="color: #6b7280; font-size: 0.75rem; text-transform: uppercase;">Tipo de Pedido:</strong>
                <p style="margin-top: 0.25rem;">
                    <span class="badge badge-{{ $pedido->tipo }}">
                        {{ ucfirst($pedido->tipo) }} {{ $pedido->mesa ? ' - Mesa ' . $pedido->mesa->numero : '' }}
                    </span>
                </p>
            </div>
            <div>
                <strong style="color: #6b7280; font-size: 0.75rem; text-transform: uppercase;">Cliente:</strong>
                <p style="margin-top: 0.25rem; font-weight: 600; color: #1f2937;">
                    {{ $pedido->cliente ? $pedido->cliente->nombre : 'Cliente General' }}
                </p>
            </div>
            <div>
                <strong style="color: #6b7280; font-size: 0.75rem; text-transform: uppercase;">Atendido por:</strong>
                <p style="margin-top: 0.25rem; font-weight: 600; color: #1f2937;">
                    {{ $pedido->usuario ? $pedido->usuario->name : 'N/A' }}
                </p>
            </div>
            <div>
                <strong style="color: #6b7280; font-size: 0.75rem; text-transform: uppercase;">Estado:</strong>
                <p style="margin-top: 0.25rem;">
                    <span class="status-select status-{{ $pedido->estado }}">
                        {{ strtoupper($pedido->estado) }}
                    </span>
                </p>
            </div>
        </div>
    </div>

    {{-- Formulario para Agregar Nuevo Producto al Detalle --}}
    <div class="table-card" style="padding: 1.25rem; margin-bottom: 1.5rem; background-color: #f9fafb;">
        <h3 style="font-size: 1rem; font-weight: 700; margin-bottom: 1rem; color: #1f2937;">+ Agregar Producto a este Pedido</h3>
        
        <form action="{{ route('detalle_pedidos.store') }}" method="POST" style="display: flex; gap: 1rem; flex-wrap: wrap; align-items: flex-end;">
            @csrf
            <input type="hidden" name="pedido_id" value="{{ $pedido->id }}">

            <div style="flex: 2; min-width: 200px;">
                <label style="display: block; font-size: 0.75rem; font-weight: 600; color: #4b5563; margin-bottom: 0.25rem;">Producto</label>
                <select name="producto_id" required style="width: 100%; padding: 0.5rem; border: 1px solid #d1d5db; border-radius: 0.375rem;">
                    <option value="">-- Seleccionar Producto --</option>
                    @foreach($productos as $producto)
                        <option value="{{ $producto->id }}">{{ $producto->nombre }} - ${{ number_format($producto->precio, 2) }}</option>
                    @endforeach
                </select>
            </div>

            <div style="flex: 1; min-width: 100px;">
                <label style="display: block; font-size: 0.75rem; font-weight: 600; color: #4b5563; margin-bottom: 0.25rem;">Cantidad</label>
                <input type="number" name="cantidad" value="1" min="1" required style="width: 100%; padding: 0.5rem; border: 1px solid #d1d5db; border-radius: 0.375rem;">
            </div>

            <button type="submit" class="btn-primary" style="height: 38px;">
                Agregar Ítem
            </button>
        </form>
    </div>

    {{-- Tabla de los Detalles jalados del Pedido ($pedido->detalles) --}}
    <div class="table-card">
        <div class="table-responsive">
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
                            <td style="font-weight: 600; color: #1f2937;">
                                {{ $detalle->producto ? $detalle->producto->nombre : 'Producto No Disponible' }}
                            </td>
                            <td>${{ number_format($detalle->precio_unitario, 2) }}</td>
                            
                            {{-- Modificar Cantidad en el detalle --}}
                            <td>
                                <form action="{{ route('detalle_pedidos.update', $detalle) }}" method="POST">
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
                                ${{ number_format($detalle->subtotal, 2) }}
                            </td>

                            {{-- Eliminar Detalle --}}
                            <td style="text-align: center;">
                                <form action="{{ route('detalle_pedidos.destroy', $detalle) }}" method="POST" onsubmit="return confirm('¿Quitar producto del pedido?');">
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
                    <tr style="background-color: #f9fafb; font-size: 1rem;">
                        <td colspan="3" style="text-align: right; font-weight: 700; color: #1f2937;">TOTAL DEL PEDIDO:</td>
                        <td style="font-weight: 800; color: #2563eb; font-size: 1.125rem;">
                            ${{ number_format($pedido->total, 2) }}
                        </td>
                        <td></td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>
@endsection    