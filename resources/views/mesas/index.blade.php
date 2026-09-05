<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="text-xl font-bold text-sky-800">Gestión de Mesas</h2>
            <a href="{{ route('mesas.create') }}"
               class="bg-sky-500 hover:bg-sky-600 text-white px-4 py-2 rounded-lg text-sm font-medium transition">
                + Nueva Mesa
            </a>
        </div>
    </x-slot>

    <div class="py-6 px-4 max-w-7xl mx-auto">

        @if(session('success'))
            <div class="mb-4 bg-green-100 border border-green-300 text-green-700 px-4 py-3 rounded-lg">
                ✅ {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="mb-4 bg-red-100 border border-red-300 text-red-700 px-4 py-3 rounded-lg">
                ❌ {{ session('error') }}
            </div>
        @endif

        {{-- RESUMEN DE ESTADOS --}}
        <div class="grid grid-cols-3 gap-4 mb-6">
            <div class="bg-white border border-sky-100 rounded-xl p-4 text-center shadow-sm">
                <p class="text-3xl font-bold text-green-600">{{ $mesas->where('estado','libre')->count() }}</p>
                <p class="text-sm text-gray-500 mt-1">Libres</p>
            </div>
            <div class="bg-white border border-sky-100 rounded-xl p-4 text-center shadow-sm">
                <p class="text-3xl font-bold text-red-500">{{ $mesas->where('estado','ocupada')->count() }}</p>
                <p class="text-sm text-gray-500 mt-1">Ocupadas</p>
            </div>
            <div class="bg-white border border-sky-100 rounded-xl p-4 text-center shadow-sm">
                <p class="text-3xl font-bold text-yellow-500">{{ $mesas->where('estado','reservada')->count() }}</p>
                <p class="text-sm text-gray-500 mt-1">Reservadas</p>
            </div>
        </div>

        {{-- TABLA --}}
        <div class="bg-white rounded-xl shadow-sm overflow-hidden border border-sky-100">
            <div class="bg-sky-50 px-6 py-4 border-b border-sky-100">
                <h3 class="text-sky-700 font-semibold">Lista de Mesas</h3>
            </div>
            <table class="min-w-full divide-y divide-sky-100">
                <thead class="bg-sky-100">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-sky-700 uppercase">#</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-sky-700 uppercase">Número</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-sky-700 uppercase">Capacidad</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-sky-700 uppercase">Estado</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-sky-700 uppercase">Cambiar Estado</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-sky-700 uppercase">Acciones</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-100">
                    @forelse($mesas as $mesa)
                    <tr class="hover:bg-sky-50 transition">
                        <td class="px-6 py-4 text-gray-400 text-sm">{{ $loop->iteration }}</td>
                        <td class="px-6 py-4 font-bold text-gray-800">Mesa {{ $mesa->numero }}</td>
                        <td class="px-6 py-4 text-gray-600">{{ $mesa->capacidad }} personas</td>
                        <td class="px-6 py-4">
                            @if($mesa->estado === 'libre')
                                <span class="px-3 py-1 bg-green-100 text-green-700 rounded-full text-xs font-semibold">🟢 Libre</span>
                            @elseif($mesa->estado === 'ocupada')
                                <span class="px-3 py-1 bg-red-100 text-red-700 rounded-full text-xs font-semibold">🔴 Ocupada</span>
                            @else
                                <span class="px-3 py-1 bg-yellow-100 text-yellow-700 rounded-full text-xs font-semibold">🟡 Reservada</span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <form action="{{ route('mesas.cambiar-estado', $mesa) }}" method="POST" class="flex gap-2 items-center">
                                @csrf @method('PATCH')
                                <select name="estado" class="text-xs border border-sky-200 rounded-lg px-2 py-1 text-gray-700">
                                    <option value="libre"     @selected($mesa->estado === 'libre')>Libre</option>
                                    <option value="ocupada"   @selected($mesa->estado === 'ocupada')>Ocupada</option>
                                    <option value="reservada" @selected($mesa->estado === 'reservada')>Reservada</option>
                                </select>
                                <button type="submit" class="bg-sky-100 hover:bg-sky-200 text-sky-700 text-xs px-2 py-1 rounded-lg transition">
                                    Aplicar
                                </button>
                            </form>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex gap-3">
                                <a href="{{ route('mesas.show', $mesa) }}"
                                   class="text-sky-600 hover:text-sky-800 text-sm font-medium">Ver</a>
                                <a href="{{ route('mesas.edit', $mesa) }}"
                                   class="text-yellow-600 hover:text-yellow-800 text-sm font-medium">Editar</a>
                                <form action="{{ route('mesas.destroy', $mesa) }}" method="POST"
                                      onsubmit="return confirm('¿Eliminar la Mesa {{ $mesa->numero }}?')">
                                    @csrf @method('DELETE')
                                    <button class="text-red-500 hover:text-red-700 text-sm font-medium">Eliminar</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-10 text-center text-gray-400">
                            No hay mesas registradas aún.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>
