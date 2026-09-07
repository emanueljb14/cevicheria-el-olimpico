@extends('layouts.app')

@section('content')
<div class="pedidos-container">
    {{-- Encabezado --}}
    <div class="pedidos-header">
        <div>
            <h1 class="pedidos-title">Crear Nuevo Pedido</h1>
            <p class="pedidos-subtitle">Registra la modalidad, cliente y los productos iniciales.</p>
        </div>
        {{-- CAMBIO 1: Usamos la clase btn-volver --}}
        <a href="{{ route('pedidos.index') }}" class="btn-volver">
            &larr; Volver
        </a>
    </div>

    {{-- Errores de Evaluación --}}
    @if ($errors->any())
        <div style="background-color: #fee2e2; border-left: 4px solid #ef4444; color: #991b1b; padding: 1rem; border-radius: 0.375rem; margin-bottom: 1.5rem;">
            <strong>¡Atención!</strong> Por favor corrige los siguientes errores:
            <ul style="margin-top: 0.5rem; margin-left: 1.25rem; list-style-type: disc;">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('pedidos.store') }}" method="POST">
        @csrf

        {{-- Datos Principales del Pedido --}}
        <div class="card-panel">
            <h3 style="font-size: 1.125rem; font-weight: 700; margin-bottom: 1rem;">1. Información General</h3>
            
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1.25rem;">
                {{-- Tipo de Pedido --}}
                <div>
                    <label class="info-label" style="margin-bottom: 0.375rem;">Tipo de Pedido *</label>
                    <select name="tipo" id="select_tipo" class="select-estado" style="width: 100%;" onchange="toggleMesa()">
                        <option value="local" {{ old('tipo') == 'local' ? 'selected' : '' }}>En Local</option>
                        <option value="recojo" {{ old('tipo') == 'recojo' ? 'selected' : '' }}>Para Llevar / Recojo</option>
                        <option value="delivery" {{ old('tipo') == 'delivery' ? 'selected' : '' }}>Delivery</option>
                    </select>
                </div>

                {{-- Selección de Mesa --}}
                <div id="campo_mesa">
                    <label class="info-label" style="margin-bottom: 0.375rem;">Mesa Asignada</label>
                    <select name="mesa_id" class="select-estado" style="width: 100%;">
                        <option value="">-- Seleccionar Mesa Libre --</option>
                        @foreach($mesas as $mesa)
                            <option value="{{ $mesa->id }}" {{ old('mesa_id') == $mesa->id ? 'selected' : '' }}>
                                Mesa #{{ $mesa->numero }} {{ $mesa->capacidad ? "({$mesa->capacidad} pers.)" : '' }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Selección de Cliente --}}
                <div>
                    <label class="info-label" style="margin-bottom: 0.375rem;">Cliente</label>
                    <select name="cliente_id" class="select-estado" style="width: 100%;">
                        <option value="">-- Cliente General / Anónimo --</option>
                        @foreach($clientes as $cliente)
                            <option value="{{ $cliente->id }}" {{ old('cliente_id') == $cliente->id ? 'selected' : '' }}>
                                {{ $cliente->nombre }} {{ $cliente->telefono ? "({$cliente->telefono})" : '' }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        {{-- Selección Dinámica de Productos --}}
        <div class="card-panel">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
                <h3 style="font-size: 1.125rem; font-weight: 700;">2. Detalle de Productos</h3>
                <button type="button" class="btn-azul" onclick="agregarFilaProducto()">
                    + Añadir Producto
                </button>
            </div>

            <div class="table-container">
                <table class="pedidos-table" id="tabla_productos">
                    <thead>
                        <tr>
                            <th style="width: 45%;">Producto *</th>
                            <th style="width: 20%;">Precio</th>
                            <th style="width: 15%;">Cantidad *</th>
                            <th style="width: 15%;">Subtotal</th>
                            <th style="width: 5%; text-align: center;">Acción</th>
                        </tr>
                    </thead>
                    <tbody id="filas_productos">
                        {{-- Se inserta con JS --}}
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="3" style="text-align: right; font-weight: 700;">TOTAL ESTIMADO:</td>
                            <td style="font-weight: 800; color: var(--olimpico-primary); font-size: 1.125rem;" id="total_pedido">$0.00</td>
                            <td></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>

        {{-- CAMBIO 2: Usamos la clase btn-azul para el botón registrar --}}
        <div style="text-align: right; margin-top: 1.5rem;">
            <button type="submit" class="btn-azul" style="padding: 0.75rem 2rem; font-size: 1rem;">
                Guardar y Registrar Pedido
            </button>
        </div>
    </form>
</div>

<script>
    const productos = @json($productos);
    let itemIndex = 0;

    function toggleMesa() {
        const tipo = document.getElementById('select_tipo').value;
        const campoMesa = document.getElementById('campo_mesa');
        if (tipo === 'local') {
            campoMesa.style.display = 'block';
        } else {
            campoMesa.style.display = 'none';
            campoMesa.querySelector('select').value = '';
        }
    }

    function agregarFilaProducto() {
        const tbody = document.getElementById('filas_productos');
        const tr = document.createElement('tr');
        tr.id = `fila_${itemIndex}`;

        let opciones = '<option value="">-- Seleccionar --</option>';
        productos.forEach(p => {
            opciones += `<option value="${p.id}" data-precio="${p.precio}">${p.nombre} - $${parseFloat(p.precio).toFixed(2)}</option>`;
        });

        // CAMBIO 3: Asignación de la clase btn-quitar al botón generado por JavaScript
        tr.innerHTML = `
            <td>
                <select name="productos[${itemIndex}][id]" required class="select-estado" style="width: 100%;" onchange="actualizarFila(${itemIndex})">
                    ${opciones}
                </select>
            </td>
            <td>
                <span id="precio_${itemIndex}">$0.00</span>
            </td>
            <td>
                <input type="number" name="productos[${itemIndex}][cantidad]" value="1" min="1" required class="input-cantidad" oninput="actualizarFila(${itemIndex})">
            </td>
            <td style="font-weight: 700; color: var(--olimpico-primary);">
                <span id="subtotal_${itemIndex}">$0.00</span>
            </td>
            <td style="text-align: center;">
                <button type="button" class="btn-quitar" title="Eliminar fila" onclick="eliminarFila(${itemIndex})">&times;</button>
            </td>
        `;

        tbody.appendChild(tr);
        itemIndex++;
    }

    function actualizarFila(index) {
        const fila = document.getElementById(`fila_${index}`);
        if (!fila) return;

        const select = fila.querySelector('select');
        const cantidadInput = fila.querySelector('input[type="number"]');
        const precioSpan = document.getElementById(`precio_${index}`);
        const subtotalSpan = document.getElementById(`subtotal_${index}`);

        const selectedOption = select.options[select.selectedIndex];
        const precioAttr = selectedOption ? selectedOption.getAttribute('data-precio') : null;
        
        const precio = precioAttr ? parseFloat(precioAttr) : 0;
        const cantidad = parseInt(cantidadInput.value) || 0;
        const subtotal = precio * cantidad;

        precioSpan.textContent = `$${precio.toFixed(2)}`;
        subtotalSpan.textContent = `$${subtotal.toFixed(2)}`;

        calcularTotal();
    }

    function eliminarFila(index) {
        const tbody = document.getElementById('filas_productos');
        if (tbody.children.length > 1) {
            const fila = document.getElementById(`fila_${index}`);
            if (fila) fila.remove();
            calcularTotal();
        } else {
            alert('El pedido debe tener al menos un producto.');
        }
    }

    function calcularTotal() {
        let total = 0;
        document.querySelectorAll('#filas_productos tr').forEach(tr => {
            const select = tr.querySelector('select');
            const cantidadInput = tr.querySelector('input[type="number"]');
            
            if (select && select.value) {
                const selectedOption = select.options[select.selectedIndex];
                const precioAttr = selectedOption ? selectedOption.getAttribute('data-precio') : null;
                const precio = precioAttr ? parseFloat(precioAttr) : 0;
                const cantidad = parseInt(cantidadInput.value) || 0;
                
                total += precio * cantidad;
            }
        });
        document.getElementById('total_pedido').textContent = `$${total.toFixed(2)}`;
    }

    document.addEventListener('DOMContentLoaded', () => {
        toggleMesa();
        agregarFilaProducto();
    });
</script>
@endsection