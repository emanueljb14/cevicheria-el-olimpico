<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <a href="{{ route('inventario.index') }}" class="text-sky-500 hover:text-sky-700">← Volver</a>
            <h2 class="text-xl font-bold text-sky-800">Editar — {{ $inventario->nombre_insumo }}</h2>
        </div>
    </x-slot>

    <div class="py-6 px-4 max-w-lg mx-auto">
        <div class="bg-white rounded-xl shadow-sm border border-sky-100 overflow-hidden">
            <div class="bg-yellow-400 px-6 py-4">
                <h3 class="text-white font-semibold text-lg">Editar Insumo</h3>
                <p class="text-yellow-100 text-sm">Modifica los datos del insumo</p>
            </div>

            <form action="{{ route('inventario.update', $inventario) }}" method="POST" class="p-6 space-y-5">
                @csrf @method('PUT')

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nombre del Insumo <span class="text-red-500">*</span></label>
                    <input type="text" name="nombre_insumo" value="{{ old('nombre_insumo', $inventario->nombre_insumo) }}"
                           class="w-full border border-sky-200 rounded-lg px-4 py-2 focus:ring-2 focus:ring-sky-400 focus:outline-none @error('nombre_insumo') border-red-400 @enderror">
                    @error('nombre_insumo')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Stock Actual <span class="text-red-500">*</span></label>
                        <input type="number" name="stock" value="{{ old('stock', $inventario->stock) }}" min="0" step="0.01"
                               class="w-full border border-sky-200 rounded-lg px-4 py-2 focus:ring-2 focus:ring-sky-400 focus:outline-none">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Unidad de Medida <span class="text-red-500">*</span></label>
                        <select name="unidad_medida"
                                class="w-full border border-sky-200 rounded-lg px-4 py-2 focus:ring-2 focus:ring-sky-400 focus:outline-none">
                            <option value="kg"       @selected(old('unidad_medida', $inventario->unidad_medida) === 'kg')>kg</option>
                            <option value="L"        @selected(old('unidad_medida', $inventario->unidad_medida) === 'L')>Litros (L)</option>
                            <option value="unidades" @selected(old('unidad_medida', $inventario->unidad_medida) === 'unidades')>Unidades</option>
                            <option value="docenas"  @selected(old('unidad_medida', $inventario->unidad_medida) === 'docenas')>Docenas</option>
                            <option value="g"        @selected(old('unidad_medida', $inventario->unidad_medida) === 'g')>Gramos (g)</option>
                            <option value="ml"       @selected(old('unidad_medida', $inventario->unidad_medida) === 'ml')>ml</option>
                        </select>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Stock Mínimo <span class="text-red-500">*</span></label>
                    <input type="number" name="stock_minimo" value="{{ old('stock_minimo', $inventario->stock_minimo) }}" min="0" step="0.01"
                           class="w-full border border-sky-200 rounded-lg px-4 py-2 focus:ring-2 focus:ring-sky-400 focus:outline-none">
                </div>

                <div class="flex gap-3 pt-2">
                    <button type="submit"
                            class="flex-1 bg-yellow-400 hover:bg-yellow-500 text-white py-2 rounded-lg font-medium transition">
                        Actualizar Insumo
                    </button>
                    <a href="{{ route('inventario.index') }}"
                       class="flex-1 text-center bg-gray-100 hover:bg-gray-200 text-gray-700 py-2 rounded-lg font-medium transition">
                        Cancelar
                    </a>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
