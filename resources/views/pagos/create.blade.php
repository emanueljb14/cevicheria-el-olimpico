<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <a href="{{ route('pagos.index') }}" class="text-sky-500 hover:text-sky-700">← Volver</a>
            <h2 class="text-xl font-bold text-sky-800">Procesar Pago</h2>
        </div>
    </x-slot>

    <div class="py-6 px-4 max-w-lg mx-auto">
        <div class="bg-white rounded-xl shadow-sm border border-sky-100 overflow-hidden">
            <div class="bg-sky-500 px-6 py-4">
                <h3 class="text-white font-semibold text-lg">Nuevo Pago</h3>
                <p class="text-sky-100 text-sm">Selecciona el pedido y método de pago</p>
            </div>

            <form action="{{ route('pagos.store') }}" method="POST" class="p-6 space-y-5">
                @csrf

                {{-- Pedido --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Pedido <span class="text-red-500">*</span></label>
                    <select name="pedido_id"
                            class="w-full border border-sky-200 rounded-lg px-4 py-2 focus:ring-2 focus:ring-sky-400 focus:outline-none @error('pedido_id') border-red-400 @enderror">
                        <option value="">-- Seleccionar pedido pendiente --</option>
                        @foreach($pedidosPendientes as $pedido)
                            <option value="{{ $pedido->id }}" @selected(old('pedido_id') == $pedido->id)>
                                Pedido #{{ $pedido->id }}
                                @if($pedido->cliente) — {{ $pedido->cliente->nombre }} @endif
                                @if($pedido->mesa) — Mesa {{ $pedido->mesa->numero }} @endif
                                — S/ {{ number_format($pedido->total, 2) }}
                            </option>
                        @endforeach
                    </select>
                    @error('pedido_id')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                    @if($pedidosPendientes->isEmpty())
                        <p class="text-yellow-600 text-xs mt-1">⚠️ No hay pedidos pendientes de pago.</p>
                    @endif
                </div>

                {{-- Método de pago --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Método de Pago <span class="text-red-500">*</span></label>
                    <div class="grid grid-cols-2 gap-3">
                        @foreach(['efectivo' => '💵 Efectivo', 'tarjeta' => '💳 Tarjeta', 'yape' => '📱 Yape', 'plin' => '📲 Plin'] as $valor => $etiqueta)
                            <label class="flex items-center gap-2 border border-sky-200 rounded-lg px-4 py-3 cursor-pointer hover:bg-sky-50 transition has-[:checked]:border-sky-500 has-[:checked]:bg-sky-50">
                                <input type="radio" name="metodo" value="{{ $valor }}"
                                       {{ old('metodo', 'efectivo') === $valor ? 'checked' : '' }}
                                       class="text-sky-500">
                                <span class="text-sm font-medium text-gray-700">{{ $etiqueta }}</span>
                            </label>
                        @endforeach
                    </div>
                    @error('metodo')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Info --}}
                <div class="bg-sky-50 border border-sky-100 rounded-lg p-4 text-sm text-sky-700">
                    ℹ️ Al procesar el pago, el pedido pasará a <strong>completado</strong> y la mesa quedará <strong>libre</strong> automáticamente.
                </div>

                {{-- Botones --}}
                <div class="flex gap-3 pt-2">
                    <button type="submit"
                            class="flex-1 bg-sky-500 hover:bg-sky-600 text-white py-2 rounded-lg font-medium transition">
                        Confirmar Pago
                    </button>
                    <a href="{{ route('pagos.index') }}"
                       class="flex-1 text-center bg-gray-100 hover:bg-gray-200 text-gray-700 py-2 rounded-lg font-medium transition">
                        Cancelar
                    </a>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
