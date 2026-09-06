<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <a href="{{ route('mesas.index') }}" class="text-sky-500 hover:text-sky-700">← Volver</a>
            <h2 class="text-xl font-bold text-sky-800">Detalle — Mesa {{ $mesa->numero }}</h2>
        </div>
    </x-slot>

    <div class="py-6 px-4 max-w-3xl mx-auto space-y-6">

        {{-- DETALLE DE LA MESA --}}
        <div class="bg-white rounded-xl shadow-sm border border-sky-100 overflow-hidden">
            <div class="bg-sky-500 px-6 py-4">
                <h3 class="text-white font-semibold text-lg">Mesa {{ $mesa->numero }}</h3>
            </div>
            <div class="p-6 grid grid-cols-2 gap-6">
                <div>
                    <p class="text-xs text-gray-400 uppercase font-medium">Número</p>
                    <p class="text-xl font-bold text-gray-800 mt-1">{{ $mesa->numero }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-400 uppercase font-medium">Capacidad</p>
                    <p class="text-xl font-bold text-gray-800 mt-1">{{ $mesa->capacidad }} personas</p>
                </div>
                <div>
                    <p class="text-xs text-gray-400 uppercase font-medium">Estado actual</p>
                    {{-- ESTADO ACTUAL --}}
<div class="mt-1">
    @if($mesa->estado === 'disponible')
        <span class="px-3 py-1 bg-green-100 text-green-700 rounded-full text-sm font-semibold">🟢 Disponible</span>
    @elseif($mesa->estado === 'ocupada')
        <span class="px-3 py-1 bg-red-100 text-red-700 rounded-full text-sm font-semibold">🔴 Ocupada</span>
    @elseif($mesa->estado === 'reservada')
        <span class="px-3 py-1 bg-yellow-100 text-yellow-700 rounded-full text-sm font-semibold">🟡 Reservada</span>
    @else
        <span class="px-3 py-1 bg-gray-100 text-gray-700 rounded-full text-sm font-semibold">⚪ Mantenimiento</span>
    @endif
</div>
                </div>
                <div>
                    <p class="text-xs text-gray-400 uppercase font-medium">Registrada</p>
                    <p class="text-gray-700 mt-1">{{ $mesa->created_at->format('d/m/Y') }}</p>
                </div>
            </div>

            {{-- Cambiar estado --}}
            <div class="px-6 pb-6">
                <p class="text-xs text-gray-400 uppercase font-medium mb-2">Cambiar estado rápido</p>
                <form action="{{ route('mesas.cambiar-estado', $mesa) }}" method="POST" class="flex gap-3 items-center">
                    @csrf @method('PATCH')
                    <select name="estado" class="border border-sky-200 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-sky-400 focus:outline-none">
    <option value="disponible" @selected($mesa->estado === 'disponible')>🟢 Disponible</option>
    <option value="ocupada"    @selected($mesa->estado === 'ocupada')>🔴 Ocupada</option>
    <option value="reservada"  @selected($mesa->estado === 'reservada')>🟡 Reservada</option>
    <option value="mantenimiento" @selected($mesa->estado === 'mantenimiento')>⚪ Mantenimiento</option>
</select>
                    <button type="submit" class="bg-sky-500 hover:bg-sky-600 text-white px-4 py-2 rounded-lg text-sm font-medium transition">
                        Aplicar
                    </button>
                </form>
            </div>
        </div>

        {{-- PEDIDOS DE ESTA MESA --}}
        <div class="bg-white rounded-xl shadow-sm border border-sky-100 overflow-hidden">
            <div class="bg-sky-50 px-6 py-4 border-b border-sky-100">
                <h3 class="text-sky-700 font-semibold">Pedidos de esta Mesa</h3>
            </div>
            <div class="p-6">
                @if($mesa->pedidos->count() > 0)
                    <ul class="divide-y divide-gray-100">
                        @foreach($mesa->pedidos as $pedido)
                        <li class="py-3 flex justify-between items-center">
                            <span class="text-gray-700 text-sm">Pedido #{{ $pedido->id }}</span>
                            <span class="text-xs px-2 py-1 bg-sky-100 text-sky-700 rounded-full">{{ $pedido->estado }}</span>
                            <span class="text-gray-500 text-sm">S/ {{ number_format($pedido->total, 2) }}</span>
                        </li>
                        @endforeach
                    </ul>
                @else
                    <p class="text-gray-400 text-sm text-center py-4">Sin pedidos registrados para esta mesa.</p>
                @endif
            </div>
        </div>

        {{-- ACCIONES --}}
        <div class="flex gap-3">
            <a href="{{ route('mesas.edit', $mesa) }}"
               class="flex-1 text-center bg-yellow-400 hover:bg-yellow-500 text-white py-2 rounded-lg font-medium transition">
                Editar Mesa
            </a>
            <form action="{{ route('mesas.destroy', $mesa) }}" method="POST" class="flex-1"
                  onsubmit="return confirm('¿Eliminar esta mesa?')">
                @csrf @method('DELETE')
                <button class="w-full bg-red-100 hover:bg-red-200 text-red-600 py-2 rounded-lg font-medium transition">
                    Eliminar Mesa
                </button>
            </form>
        </div>
    </div>
</x-app-layout>
