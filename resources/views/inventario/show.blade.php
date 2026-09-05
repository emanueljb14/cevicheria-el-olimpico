<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <a href="{{ route('inventario.index') }}" class="text-sky-500 hover:text-sky-700">← Volver</a>
            <h2 class="text-xl font-bold text-sky-800">{{ $inventario->nombre_insumo }}</h2>
        </div>
    </x-slot>

    <div class="py-6 px-4 max-w-2xl mx-auto space-y-6">

        @if(session('success'))
            <div class="bg-green-100 border border-green-300 text-green-700 px-4 py-3 rounded-lg">
                ✅ {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="bg-red-100 border border-red-300 text-red-700 px-4 py-3 rounded-lg">
                ❌ {{ session('error') }}
            </div>
        @endif

        {{-- DETALLE --}}
        <div class="bg-white rounded-xl shadow-sm border border-sky-100 overflow-hidden">
            <div class="{{ $inventario->bajo_stock ? 'bg-red-500' : 'bg-sky-500' }} px-6 py-4">
                <h3 class="text-white font-semibold text-lg">{{ $inventario->nombre_insumo }}</h3>
                <p class="text-white/80 text-sm">{{ $inventario->bajo_stock ? '⚠️ Stock crítico' : '✅ Stock OK' }}</p>
            </div>
            <div class="p-6 grid grid-cols-2 gap-6">
                <div>
                    <p class="text-xs text-gray-400 uppercase font-medium">Stock Actual</p>
                    <p class="text-3xl font-bold {{ $inventario->bajo_stock ? 'text-red-600' : 'text-sky-600' }} mt-1">
                        {{ $inventario->stock }} <span class="text-base font-normal text-gray-500">{{ $inventario->unidad_medida }}</span>
                    </p>
                </div>
                <div>
                    <p class="text-xs text-gray-400 uppercase font-medium">Stock Mínimo</p>
                    <p class="text-3xl font-bold text-gray-700 mt-1">
                        {{ $inventario->stock_minimo }} <span class="text-base font-normal text-gray-500">{{ $inventario->unidad_medida }}</span>
                    </p>
                </div>
                <div>
                    <p class="text-xs text-gray-400 uppercase font-medium">Unidad de Medida</p>
                    <p class="text-gray-800 font-semibold mt-1">{{ $inventario->unidad_medida }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-400 uppercase font-medium">Último Update</p>
                    <p class="text-gray-600 mt-1">{{ $inventario->updated_at->format('d/m/Y H:i') }}</p>
                </div>
            </div>
        </div>

        {{-- AJUSTAR STOCK --}}
        <div class="bg-white rounded-xl shadow-sm border border-sky-100 overflow-hidden">
            <div class="bg-sky-50 px-6 py-4 border-b border-sky-100">
                <h3 class="text-sky-700 font-semibold">Ajustar Stock</h3>
                <p class="text-gray-400 text-sm">Registra entradas o salidas del insumo</p>
            </div>
            <form action="{{ route('inventario.actualizar-stock', $inventario) }}" method="POST" class="p-6 space-y-4">
                @csrf @method('PATCH')

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Cantidad <span class="text-red-500">*</span></label>
                        <input type="number" name="cantidad" min="0.01" step="0.01" required
                               class="w-full border border-sky-200 rounded-lg px-4 py-2 focus:ring-2 focus:ring-sky-400 focus:outline-none"
                               placeholder="0.00">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Tipo <span class="text-red-500">*</span></label>
                        <select name="tipo"
                                class="w-full border border-sky-200 rounded-lg px-4 py-2 focus:ring-2 focus:ring-sky-400 focus:outline-none">
                            <option value="entrada">📥 Entrada (añadir)</option>
                            <option value="salida">📤 Salida (restar)</option>
                        </select>
                    </div>
                </div>

                <button type="submit"
                        class="w-full bg-sky-500 hover:bg-sky-600 text-white py-2 rounded-lg font-medium transition">
                    Registrar Movimiento
                </button>
            </form>
        </div>

        {{-- ACCIONES --}}
        <div class="flex gap-3">
            <a href="{{ route('inventario.edit', $inventario) }}"
               class="flex-1 text-center bg-yellow-400 hover:bg-yellow-500 text-white py-2 rounded-lg font-medium transition">
                Editar Insumo
            </a>
            <form action="{{ route('inventario.destroy', $inventario) }}" method="POST" class="flex-1"
                  onsubmit="return confirm('¿Eliminar este insumo?')">
                @csrf @method('DELETE')
                <button class="w-full bg-red-100 hover:bg-red-200 text-red-600 py-2 rounded-lg font-medium transition">
                    Eliminar Insumo
                </button>
            </form>
        </div>
    </div>
</x-app-layout>
