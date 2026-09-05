<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="text-xl font-bold text-sky-800">Historial de Pagos</h2>
            <a href="{{ route('pagos.create') }}"
               class="bg-sky-500 hover:bg-sky-600 text-white px-4 py-2 rounded-lg text-sm font-medium transition">
                + Procesar Pago
            </a>
        </div>
    </x-slot>

    <div class="py-6 px-4 max-w-7xl mx-auto">

        @if(session('success'))
            <div class="mb-4 bg-green-100 border border-green-300 text-green-700 px-4 py-3 rounded-lg">
                ✅ {{ session('success') }}
            </div>
        @endif

        {{-- RESUMEN --}}
        <div class="grid grid-cols-4 gap-4 mb-6">
            <div class="bg-white border border-sky-100 rounded-xl p-4 text-center shadow-sm col-span-1">
                <p class="text-2xl font-bold text-sky-600">{{ $pagos->count() }}</p>
                <p class="text-sm text-gray-500 mt-1">Total Pagos</p>
            </div>
            <div class="bg-white border border-sky-100 rounded-xl p-4 text-center shadow-sm col-span-1">
                <p class="text-2xl font-bold text-green-600">S/ {{ number_format($pagos->sum('monto'), 2) }}</p>
                <p class="text-sm text-gray-500 mt-1">Total Recaudado</p>
            </div>
            <div class="bg-white border border-sky-100 rounded-xl p-4 text-center shadow-sm col-span-1">
                <p class="text-2xl font-bold text-gray-700">{{ $pagos->where('metodo','efectivo')->count() }}</p>
                <p class="text-sm text-gray-500 mt-1">En Efectivo</p>
            </div>
            <div class="bg-white border border-sky-100 rounded-xl p-4 text-center shadow-sm col-span-1">
                <p class="text-2xl font-bold text-purple-600">{{ $pagos->whereIn('metodo',['yape','plin'])->count() }}</p>
                <p class="text-sm text-gray-500 mt-1">Yape / Plin</p>
            </div>
        </div>

        {{-- TABLA --}}
        <div class="bg-white rounded-xl shadow-sm overflow-hidden border border-sky-100">
            <div class="bg-sky-50 px-6 py-4 border-b border-sky-100">
                <h3 class="text-sky-700 font-semibold">Pagos Registrados</h3>
            </div>
            <table class="min-w-full divide-y divide-sky-100">
                <thead class="bg-sky-100">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-sky-700 uppercase">#</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-sky-700 uppercase">Pedido</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-sky-700 uppercase">Cliente</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-sky-700 uppercase">Mesa</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-sky-700 uppercase">Método</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-sky-700 uppercase">Monto</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-sky-700 uppercase">Fecha</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-sky-700 uppercase">Acciones</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-100">
                    @forelse($pagos as $pago)
                    <tr class="hover:bg-sky-50 transition">
                        <td class="px-6 py-4 text-gray-400 text-sm">{{ $loop->iteration }}</td>
                        <td class="px-6 py-4 font-semibold text-gray-800">#{{ $pago->pedido_id }}</td>
                        <td class="px-6 py-4 text-gray-600">
                            {{ optional(optional($pago->pedido)->cliente)->nombre ?? '—' }}
                        </td>
                        <td class="px-6 py-4 text-gray-600">
                            {{ optional(optional($pago->pedido)->mesa)->numero ? 'Mesa ' . $pago->pedido->mesa->numero : '—' }}
                        </td>
                        <td class="px-6 py-4">
                            @php
                                $colores = [
                                    'efectivo' => 'bg-green-100 text-green-700',
                                    'tarjeta'  => 'bg-blue-100 text-blue-700',
                                    'yape'     => 'bg-purple-100 text-purple-700',
                                    'plin'     => 'bg-pink-100 text-pink-700',
                                ];
                            @endphp
                            <span class="px-2 py-1 rounded-full text-xs font-semibold {{ $colores[$pago->metodo] ?? 'bg-gray-100 text-gray-700' }}">
                                {{ ucfirst($pago->metodo) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 font-bold text-gray-800">S/ {{ number_format($pago->monto, 2) }}</td>
                        <td class="px-6 py-4 text-gray-500 text-sm">{{ $pago->created_at->format('d/m/Y H:i') }}</td>
                        <td class="px-6 py-4">
                            <div class="flex gap-3">
                                <a href="{{ route('pagos.show', $pago) }}"
                                   class="text-sky-600 hover:text-sky-800 text-sm font-medium">Ver</a>
                                <a href="{{ route('pagos.edit', $pago) }}"
                                   class="text-yellow-600 hover:text-yellow-800 text-sm font-medium">Editar</a>
                                <form action="{{ route('pagos.destroy', $pago) }}" method="POST"
                                      onsubmit="return confirm('¿Anular este pago?')">
                                    @csrf @method('DELETE')
                                    <button class="text-red-500 hover:text-red-700 text-sm font-medium">Anular</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="px-6 py-10 text-center text-gray-400">
                            No hay pagos registrados aún.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>
