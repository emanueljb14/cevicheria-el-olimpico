<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <a href="{{ route('inventario.index') }}" class="text-sky-500 hover:text-sky-700">← Volver</a>
            <h2 class="text-xl font-bold text-sky-800">Nuevo Insumo</h2>
        </div>
    </x-slot>

    <div class="py-6 px-4 max-w-lg mx-auto">
        <div class="bg-white rounded-xl shadow-sm border border-sky-100 overflow-hidden">
            <div class="bg-sky-500 px-6 py-4">
                <h3 class="text-white font-semibold text-lg">Registrar Insumo</h3>
                <p class="text-sky-100 text-sm">Añade un nuevo insumo al inventario</p>
            </div>

            <form action="{{ route('inventario.store') }}" method="POST" class="p-6 space-y-5">
                @csrf

                {{-- Nombre del insumo --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nombre del Insumo <span class="text-red-500">*</span></label>
                    <input type="text" name="nombre_insumo" value="{{ old('nombre_insumo') }}"
                           class="w-full border border-sky-200 rounded-lg px-4 py-2 focus:ring-2 focus:ring-sky-400 focus:outline-none @error('nombre_insumo') border-red-400 @enderror"
                           placeholder="Ej: Limón, Cebolla, Aceite...">
                    @error('nombre_insumo')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Stock y unidad --}}
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Stock Actual <span class="text-red-500">*</span></label>
                        <input type="number" name="stock" value="{{ old('stock', 0) }}" min="0" step="0.01"
                               class="w-full border border-sky-200 rounded-lg px-4 py-2 focus:ring-2 focus:ring-sky-400 focus:outline-none @error('stock') border-red-400 @enderror">
                        @error('stock')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Unidad de Medida <span class="text-red-500">*</span></label>
                        <select name="unidad_medida"
                                class="w-full border border-sky-200 rounded-lg px-4 py-2 focus:ring-2 focus:ring-sky-400 focus:outline-none @error('unidad_medida') border-red-400 @enderror">
                            <option value="">Seleccionar...</option>
                            <option value="kg"       @selected(old('unidad_medida') === 'kg')>kg</option>
                            <option value="L"        @selected(old('unidad_medida') === 'L')>Litros (L)</option>
                            <option value="unidades" @selected(old('unidad_medida') === 'unidades')>Unidades</option>
                            <option value="docenas"  @selected(old('unidad_medida') === 'docenas')>Docenas</option>
                            <option value="g"        @selected(old('unidad_medida') === 'g')>Gramos (g)</option>
                            <option value="ml"       @selected(old('unidad_medida') === 'ml')>ml</option>
                        </select>
                        @error('unidad_medida')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                {{-- Stock mínimo --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Stock Mínimo <span class="text-red-500">*</span></label>
                    <input type="number" name="stock_minimo" value="{{ old('stock_minimo', 5) }}" min="0" step="0.01"
                           class="w-full border border-sky-200 rounded-lg px-4 py-2 focus:ring-2 focus:ring-sky-400 focus:outline-none @error('stock_minimo') border-red-400 @enderror">
                    <p class="text-gray-400 text-xs mt-1">Se mostrará alerta cuando el stock llegue a este nivel.</p>
                    @error('stock_minimo')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Botones --}}
                <div class="flex gap-3 pt-2">
                    <button type="submit"
                            class="flex-1 bg-sky-500 hover:bg-sky-600 text-white py-2 rounded-lg font-medium transition">
                        Guardar Insumo
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
