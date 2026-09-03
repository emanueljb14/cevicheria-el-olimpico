@extends('layouts.app')

@section('content')
<div class="pedidos-container">
    {{-- Encabezado --}}
    <div class="pedidos-header">
        <div>
            <h1 class="pedidos-title">Crear Nuevo Pedido</h1>
            <p class="pedidos-subtitle">Registra la modalidad, cliente y los productos iniciales.</p>
        </div>
        <a href="{{ route('pedidos.index') }}" class="btn-primary" style="background-color: #4b5563;">
            &larr; Volver
        </a>
    </div>

    {{-- Errores de Validación --}}
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
        <div class="table-card" style="padding: 1.5rem; margin-bottom: 1.5rem;">
            <h3 style="font-size: 1.125rem; font-weight: 700; color: #1f2937; margin-bottom: 1rem;">1. Información General</h3>
            
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1.25rem;">
                {{-- Tipo de Pedido --}}
                <div>
                    <label style="display: block; font-size: 0.875rem; font-weight: 600; color: #374151; margin-bottom: 0.375rem;">
                        Tipo de Pedido *
                    </label>
                    <select name="tipo" id="select_tipo" required style="width: 100%; padding: 0.625rem; border: 1px solid #d1d5db; border-radius: 0.375rem;" onchange="toggleMesa()">
                        <option value="local" {{ old('tipo') == 'local' ? 'selected' : '' }}>En Local</option>
                        <option value="recojo" {{ old('tipo') == 'recojo' ? 'selected' : '' }}>Para Llevar / Recojo</option>
                        <option value="delivery" {{ old('tipo') == 'delivery' ? 'selected' : '' }}>Delivery</option>
                    </select>
                </div>

                {{-- Selección de Mesa (Solo si es Local) --}}
                <div id="campo_mesa">
                    <label style="display: block; font-size: 0.875rem; font-weight: 600; color: #374151; margin-bottom: 0.375rem;">
                        Mesa Asignada
                    </label>
                    <select name="mesa_id" style="width: 100%; padding: 0.625rem; border: 1px solid #d1d5db; border-radius: 0.375rem;">
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
                    <label style="display: block; font-size: 0.875rem; font-weight: 600; color: #374151; margin-bottom: 0.375rem;">
                        Cliente
                    </label>
                    <select name="cliente_id" style="width: 100%; padding: 0.625rem; border: 1px solid #d1d5db; border-radius: 0.375rem;">
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
        <div class="table-card" style="padding: 1.5rem; margin-bottom: 1.5rem;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
                <h3 style="font-size: 1.125rem; font-weight: 700; color: #1f2937;">2. Detalle de Productos</h3>
                <button type="button" class="btn-primary" onclick="agregarFilaProducto()">
                    + Añadir Producto
                </button>
            </div>

            <div class="table-responsive">
                <table class="pedidos-table" id="tabla_productos">
                    <thead>
                        <tr>
                            <th style="width: 50%;">Producto *</th>
                            <th style="width: 20%;">Precio</th>
                            <th style="width: 15%;">Cantidad *</th>
                            <th style="width: 10%;">Subtotal</th>
                            <th style="width: 5%; text-align: center;">Acción</th>
                        </tr>
                    </thead>
                    <tbody id="filas_productos">
                        {{-- Se insertará la primera fila con JS al cargar --}}
                    </tbody>
                    <tfoot>
                        <tr style="background-color: #f9fafb;">
                            <td colspan="3" style="text-align: right; font-weight: 700; color: #1f2937;">TOTAL ESTIMADO:</td>
                            <td style="font-weight: 800; color: #2563eb; font-size: 1.125rem;" id="total_pedido">$0.00</td>
                            <td></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>

        {{-- Botón Guardar --}}
        <div style="text-align: right;">
            <button type="submit" class="btn-primary" style="padding: 0.75rem 2rem; font-size: 1rem;">
                Guardar y Registrar Pedido
            </button>
        </div>
    </form>
</div>

<script>
    // Lista de productos inyectada desde PHP
    const productos = @json($productos);
    let itemIndex = 0;

    // Controlar visibilidad del campo Mesa según el tipo de pedido
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

    // Agregar una fila a la tabla
    function agregarFilaProducto() {
        const tbody = document.getElementById('filas_productos');
        const tr = document.createElement('tr');
        tr.id = `fila_${itemIndex}`;

        let opciones = '<option value="">-- Seleccionar --</option>';
        productos.forEach(p => {
            opciones += `<option value="${p.id}" data-precio="${p.precio}">${p.nombre} - $${parseFloat(p.precio).toFixed(2)}</option>`;
        });

        tr.innerHTML = `
            <td>
                <select name="productos[${itemIndex}][id]" required style="width: 100%; padding: 0.5rem; border: 1px solid #d1d5db; border-radius: 0.375rem;" onchange="actualizarFila(${itemIndex})">
                    ${opciones}
                </select>
            </td>
            <td>
                <span id="precio_${itemIndex}">$0.00</span>
            </td>
            <td>
                <input type="number" name="productos[${itemIndex}][cantidad]" value="1" min="1" required style="width: 100%; padding: 0.5rem; border: 1px solid #d1d5db; border-radius: 0.375rem;" oninput="actualizarFila(${itemIndex})">
            </td>
            <td style="font-weight: 700; color: #059669;">
                <span id="subtotal_${itemIndex}">$0.00</span>
            </td>
            <td style="text-align: center;">
                <button type="button" class="btn-link-danger" onclick="eliminarFila(${itemIndex})">&times;</button>
            </td>
        `;

        tbody.appendChild(tr);
        itemIndex++;
    }

    // Actualizar precio, subtotal y total general
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

    // Eliminar fila
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

    // Calcular la suma de todos los subtotales
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

    // Inicialización al cargar la página
    document.addEventListener('DOMContentLoaded', () => {
        toggleMesa();
        agregarFilaProducto(); // Agrega la primera fila por defecto
    });
</script>
@endsection