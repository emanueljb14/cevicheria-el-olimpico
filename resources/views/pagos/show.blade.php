<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <a href="{{ route('pagos.index') }}" class="text-sky-500 hover:text-sky-700">← Volver</a>
            <h2 class="text-xl font-bold text-sky-800">Pago #{{ $pago->id }}</h2>
        </div>
    </x-slot>

    <div class="py-6 px-4 max-w-3xl mx-auto space-y-6">

        {{-- ENCABEZADO DEL PAGO --}}
        <div class="bg-white rounded-xl shadow-sm border border-sky-100 overflow-hidden">
            <div class="bg-sky-500 px-6 py-4">
                <div class="flex justify-between items-center">
                    <div>
                        <h3 class="text-white font-bold text-lg">Pago #{{ $pago->id }}</h3>
                        <p class="text-sky-100 text-sm">{{ $pago->created_at->format('d/m/Y H:i') }}</p>
                    </div>
                    <div class="text-right">
                        <p class="text-sky-100 text-sm">Total</p>
                        <p class="text-white font-bold text-2xl">S/ {{ number_format($pago->monto, 2) }}</p>
                    </div>
                </div>
            </div>

            <div class="p-6 grid grid-cols-3 gap-6">
                <div>
                    <p class="text-xs text-gray-400 uppercase font-medium">Método de Pago</p>
                    @php
                        $colores = [
                            'efectivo' => 'bg-green-100 text-green-700',
                            'tarjeta'  => 'bg-blue-100 text-blue-700',
                            'yape'     => 'bg-purple-100 text-purple-700',
                            'plin'     => 'bg-pink-100 text-pink-700',
                        ];
                    @endphp
                    <span class="inline-block mt-2 px-3 py-1 rounded-full text-sm font-semibold {{ $colores[$pago->metodo] ?? 'bg-gray-100 text-gray-700' }}">
                        {{ ucfirst($pago->metodo) }}
                    </span>
                </div>
                <div>
                    <p class="text-xs text-gray-400 uppercase font-medium">Pedido</p>
                    <p class="text-gray-800 font-semibold mt-2">#{{ $pago->pedido_id }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-400 uppercase font-medium">Mesa</p>
                    <p class="text-gray-800 font-semibold mt-2">
                        {{ optional(optional($pago->pedido)->mesa)->numero ? 'Mesa ' . $pago->pedido->mesa->numero : '—' }}
                    </p>
                </div>
            </div>
        </div>

        {{-- DETALLE DEL PEDIDO --}}
        @if($pago->pedido && $pago->pedido->detalles->count() > 0)
        <div class="bg-white rounded-xl shadow-sm border border-sky-100 overflow-hidden">
            <div class="bg-sky-50 px-6 py-4 border-b border-sky-100">
                <h3 class="text-sky-700 font-semibold">Productos del Pedido</h3>
            </div>
            <table class="min-w-full divide-y divide-gray-100">
                <thead class="bg-sky-100">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-sky-700 uppercase">Producto</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-sky-700 uppercase">Cantidad</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-sky-700 uppercase">Precio Unit.</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-sky-700 uppercase">Subtotal</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-100">
                    @foreach($pago->pedido->detalles as $detalle)
                    <tr>
                        <td class="px-6 py-3 text-gray-800">{{ optional($detalle->producto)->nombre ?? '—' }}</td>
                        <td class="px-6 py-3 text-gray-600">{{ $detalle->cantidad }}</td>
                        <td class="px-6 py-3 text-gray-600">S/ {{ number_format($detalle->precio_unitario, 2) }}</td>
                        <td class="px-6 py-3 font-semibold text-gray-800">S/ {{ number_format($detalle->cantidad * $detalle->precio_unitario, 2) }}</td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot class="bg-sky-50">
                    <tr>
                        <td colspan="3" class="px-6 py-3 text-right font-bold text-sky-700">Total:</td>
                        <td class="px-6 py-3 font-bold text-sky-700 text-lg">S/ {{ number_format($pago->monto, 2) }}</td>
                    </tr>
                </tfoot>
            </table>
        </div>
        @endif

        {{-- VENTA GENERADA --}}
        @if($pago->venta)
        <div class="bg-white rounded-xl shadow-sm border border-green-100 p-6">
            <h3 class="text-green-700 font-semibold mb-3">✅ Venta Generada</h3>
            <div class="grid grid-cols-2 gap-4 text-sm">
                <div>
                    <p class="text-gray-400">ID Venta</p>
                    <p class="font-semibold text-gray-800">#{{ $pago->venta->id }}</p>
                </div>
                <div>
                    <p class="text-gray-400">Monto Total</p>
                    <p class="font-bold text-green-700">S/ {{ number_format($pago->venta->monto_total, 2) }}</p>
                </div>
            </div>
        </div>
        @endif

        {{-- ACCIONES --}}
        <div class="flex gap-3">
            <a href="{{ route('pagos.edit', $pago) }}"
               class="flex-1 text-center bg-yellow-400 hover:bg-yellow-500 text-white py-2 rounded-lg font-medium transition">
                Editar Método
            </a>
            <form action="{{ route('pagos.destroy', $pago) }}" method="POST" class="flex-1"
                  onsubmit="return confirm('¿Anular este pago? El pedido volverá a pendiente.')">
                @csrf @method('DELETE')
                <button class="w-full bg-red-100 hover:bg-red-200 text-red-600 py-2 rounded-lg font-medium transition">
                    Anular Pago
                </button>
            </form>
        </div>
    </div>
</x-app-layout>
