<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="text-xl font-bold text-sky-800">Inventario</h2>
            <a href="{{ route('inventario.create') }}"
               class="bg-sky-500 hover:bg-sky-600 text-white px-4 py-2 rounded-lg text-sm font-medium transition">
                + Nuevo Insumo
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

        {{-- ALERTA STOCK BAJO --}}
        @php $bajoStock = $insumos->filter(fn($i) => $i->stock <= $i->stock_minimo); @endphp
        @if($bajoStock->count() > 0)
            <div class="mb-4 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg">
                ⚠️ <strong>{{ $bajoStock->count() }} insumo(s)</strong> con stock crítico:
                {{ $bajoStock->pluck('nombre_insumo')->join(', ') }}
            </div>
        @endif

        <div class="bg-white rounded-xl shadow-sm overflow-hidden border border-sky-100">
            <div class="bg-sky-50 px-6 py-4 border-b border-sky-100">
                <h3 class="text-sky-700 font-semibold">Lista de Insumos</h3>
            </div>
            <table class="min-w-full divide-y divide-sky-100">
                <thead class="bg-sky-100">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-sky-700 uppercase">#</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-sky-700 uppercase">Insumo</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-sky-700 uppercase">Stock Actual</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-sky-700 uppercase">Unidad</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-sky-700 uppercase">Stock Mínimo</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-sky-700 uppercase">Estado</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-sky-700 uppercase">Acciones</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-100">
                    @forelse($insumos as $insumo)
                    <tr class="hover:bg-sky-50 transition {{ $insumo->bajo_stock ? 'bg-red-50' : '' }}">
                        <td class="px-6 py-4 text-gray-400 text-sm">{{ $loop->iteration }}</td>
                        <td class="px-6 py-4 font-semibold text-gray-800">{{ $insumo->nombre_insumo }}</td>
                        <td class="px-6 py-4">
                            <span class="font-bold {{ $insumo->bajo_stock ? 'text-red-600' : 'text-gray-800' }}">
                                {{ $insumo->stock }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-gray-600">{{ $insumo->unidad_medida }}</td>
                        <td class="px-6 py-4 text-gray-500">{{ $insumo->stock_minimo }}</td>
                        <td class="px-6 py-4">
                            @if($insumo->bajo_stock)
                                <span class="px-2 py-1 bg-red-100 text-red-700 rounded-full text-xs font-semibold">⚠️ Stock Bajo</span>
                            @else
                                <span class="px-2 py-1 bg-green-100 text-green-700 rounded-full text-xs font-semibold">✅ OK</span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex gap-3">
                                <a href="{{ route('inventario.show', $insumo) }}"
                                   class="text-sky-600 hover:text-sky-800 text-sm font-medium">Ver</a>
                                <a href="{{ route('inventario.edit', $insumo) }}"
                                   class="text-yellow-600 hover:text-yellow-800 text-sm font-medium">Editar</a>
                                <form action="{{ route('inventario.destroy', $insumo) }}" method="POST"
                                      onsubmit="return confirm('¿Eliminar {{ $insumo->nombre_insumo }}?')">
                                    @csrf @method('DELETE')
                                    <button class="text-red-500 hover:text-red-700 text-sm font-medium">Eliminar</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-10 text-center text-gray-400">
                            No hay insumos en el inventario.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>
