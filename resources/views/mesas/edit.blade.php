<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <a href="{{ route('mesas.index') }}" class="text-sky-500 hover:text-sky-700">← Volver</a>
            <h2 class="text-xl font-bold text-sky-800">Editar Mesa {{ $mesa->numero }}</h2>
        </div>
    </x-slot>

    <div class="py-6 px-4 max-w-lg mx-auto">
        <div class="bg-white rounded-xl shadow-sm border border-sky-100 overflow-hidden">
            <div class="bg-yellow-400 px-6 py-4">
                <h3 class="text-white font-semibold text-lg">Editar Mesa</h3>
                <p class="text-yellow-100 text-sm">Modifica los datos de la mesa</p>
            </div>

            <form action="{{ route('mesas.update', $mesa) }}" method="POST" class="p-6 space-y-5">
                @csrf @method('PUT')

                {{-- Número --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Número de Mesa <span class="text-red-500">*</span></label>
                    <input type="text" name="numero" value="{{ old('numero', $mesa->numero) }}"
                           class="w-full border border-sky-200 rounded-lg px-4 py-2 focus:ring-2 focus:ring-sky-400 focus:outline-none @error('numero') border-red-400 @enderror">
                    @error('numero')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Capacidad --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Capacidad (personas) <span class="text-red-500">*</span></label>
                    <input type="number" name="capacidad" value="{{ old('capacidad', $mesa->capacidad) }}" min="1"
                           class="w-full border border-sky-200 rounded-lg px-4 py-2 focus:ring-2 focus:ring-sky-400 focus:outline-none @error('capacidad') border-red-400 @enderror">
                    @error('capacidad')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Estado --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Estado <span class="text-red-500">*</span></label>
                    <select name="estado"
                            class="w-full border border-sky-200 rounded-lg px-4 py-2 focus:ring-2 focus:ring-sky-400 focus:outline-none">
                        <option value="libre"     @selected(old('estado', $mesa->estado) === 'libre')>🟢 Libre</option>
                        <option value="ocupada"   @selected(old('estado', $mesa->estado) === 'ocupada')>🔴 Ocupada</option>
                        <option value="reservada" @selected(old('estado', $mesa->estado) === 'reservada')>🟡 Reservada</option>
                    </select>
                </div>

                {{-- Botones --}}
                <div class="flex gap-3 pt-2">
                    <button type="submit"
                            class="flex-1 bg-yellow-400 hover:bg-yellow-500 text-white py-2 rounded-lg font-medium transition">
                        Actualizar Mesa
                    </button>
                    <a href="{{ route('mesas.index') }}"
                       class="flex-1 text-center bg-gray-100 hover:bg-gray-200 text-gray-700 py-2 rounded-lg font-medium transition">
                        Cancelar
                    </a>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
