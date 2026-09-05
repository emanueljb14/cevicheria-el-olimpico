<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <a href="{{ route('pagos.index') }}" class="text-sky-500 hover:text-sky-700">← Volver</a>
            <h2 class="text-xl font-bold text-sky-800">Editar Pago #{{ $pago->id }}</h2>
        </div>
    </x-slot>

    <div class="py-6 px-4 max-w-lg mx-auto">
        <div class="bg-white rounded-xl shadow-sm border border-sky-100 overflow-hidden">
            <div class="bg-yellow-400 px-6 py-4">
                <h3 class="text-white font-semibold text-lg">Editar Método de Pago</h3>
                <p class="text-yellow-100 text-sm">Solo puedes cambiar el método de pago</p>
            </div>

            <form action="{{ route('pagos.update', $pago) }}" method="POST" class="p-6 space-y-5">
                @csrf @method('PUT')

                {{-- Info del pago --}}
                <div class="bg-sky-50 border border-sky-100 rounded-lg p-4 space-y-2">
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-500">Pedido:</span>
                        <span class="font-semibold text-gray-800">#{{ $pago->pedido_id }}</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-500">Monto:</span>
                        <span class="font-bold text-sky-700">S/ {{ number_format($pago->monto, 2) }}</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-500">Fecha:</span>
                        <span class="text-gray-700">{{ $pago->created_at->format('d/m/Y H:i') }}</span>
                    </div>
                </div>

                {{-- Método de pago --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Método de Pago <span class="text-red-500">*</span></label>
                    <div class="grid grid-cols-2 gap-3">
                        @foreach(['efectivo' => '💵 Efectivo', 'tarjeta' => '💳 Tarjeta', 'yape' => '📱 Yape', 'plin' => '📲 Plin'] as $valor => $etiqueta)
                            <label class="flex items-center gap-2 border border-sky-200 rounded-lg px-4 py-3 cursor-pointer hover:bg-sky-50 transition has-[:checked]:border-sky-500 has-[:checked]:bg-sky-50">
                                <input type="radio" name="metodo" value="{{ $valor }}"
                                       {{ old('metodo', $pago->metodo) === $valor ? 'checked' : '' }}
                                       class="text-sky-500">
                                <span class="text-sm font-medium text-gray-700">{{ $etiqueta }}</span>
                            </label>
                        @endforeach
                    </div>
                    @error('metodo')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Botones --}}
                <div class="flex gap-3 pt-2">
                    <button type="submit"
                            class="flex-1 bg-yellow-400 hover:bg-yellow-500 text-white py-2 rounded-lg font-medium transition">
                        Actualizar Método
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
