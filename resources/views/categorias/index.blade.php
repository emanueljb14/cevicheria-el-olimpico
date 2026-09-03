<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Categorías | El Olímpico</title>

    <script src="https://cdn.tailwindcss.com"></script>

    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background: #f5f7fa;
        }

        .card {
            box-shadow: 0 10px 30px rgba(0,0,0,.08);
        }
    </style>
</head>

<body>

<div class="min-h-screen p-4 md:p-8">

    <!-- ENCABEZADO -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-8">

        <div>
            <a href="{{ route('dashboard') }}"
               class="text-sm text-gray-500 hover:text-orange-600">
                ← Volver al Dashboard
            </a>

            <h1 class="text-3xl font-bold text-gray-800 mt-2">
                Categorías
            </h1>

            <p class="text-gray-500">
                Administra las categorías de los platos de El Olímpico.
            </p>
        </div>

        <a href="{{ route('categorias.create') }}"
           class="bg-orange-500 hover:bg-orange-600 text-white px-5 py-3 rounded-xl font-semibold shadow-lg transition">
            + Nueva Categoría
        </a>

    </div>


    <!-- MENSAJE DE ÉXITO -->
    @if(session('success'))
        <div class="mb-6 bg-green-100 border border-green-300 text-green-700 px-5 py-4 rounded-xl">
            ✓ {{ session('success') }}
        </div>
    @endif


    <!-- ERRORES -->
    @if($errors->any())
        <div class="mb-6 bg-red-100 border border-red-300 text-red-700 px-5 py-4 rounded-xl">
            <ul class="list-disc ml-5">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif


    <!-- TABLA -->
    <div class="bg-white rounded-2xl card overflow-hidden">

        <div class="px-6 py-5 border-b flex justify-between items-center">
            <div>
                <h2 class="text-xl font-bold text-gray-800">
                    Lista de categorías
                </h2>

                <p class="text-sm text-gray-500">
                    Total: {{ $categorias->count() }} categorías
                </p>
            </div>
        </div>


        @if($categorias->count() > 0)

            <div class="overflow-x-auto">

                <table class="w-full">

                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-gray-600">
                                #
                            </th>

                            <th class="px-6 py-4 text-left text-sm font-semibold text-gray-600">
                                Nombre
                            </th>

                            <th class="px-6 py-4 text-left text-sm font-semibold text-gray-600">
                                Descripción
                            </th>

                            <th class="px-6 py-4 text-center text-sm font-semibold text-gray-600">
                                Estado
                            </th>

                            <th class="px-6 py-4 text-center text-sm font-semibold text-gray-600">
                                Fecha
                            </th>

                            <th class="px-6 py-4 text-center text-sm font-semibold text-gray-600">
                                Acciones
                            </th>
                        </tr>
                    </thead>


                    <tbody class="divide-y divide-gray-100">

                        @foreach($categorias as $categoria)

                            <tr class="hover:bg-orange-50 transition">

                                <td class="px-6 py-5 text-gray-500">
                                    {{ $categoria->id }}
                                </td>


                                <td class="px-6 py-5">

                                    <div class="font-bold text-gray-800">
                                        {{ $categoria->nombre }}
                                    </div>

                                </td>


                                <td class="px-6 py-5 text-gray-600 max-w-xs">

                                    @if($categoria->descripcion)
                                        {{ $categoria->descripcion }}
                                    @else
                                        <span class="text-gray-400 italic">
                                            Sin descripción
                                        </span>
                                    @endif

                                </td>


                                <td class="px-6 py-5 text-center">

                                    @if($categoria->estado)

                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-green-100 text-green-700">
                                            ● Activo
                                        </span>

                                    @else

                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-red-100 text-red-700">
                                            ● Inactivo
                                        </span>

                                    @endif

                                </td>


                                <td class="px-6 py-5 text-center text-sm text-gray-500">

                                    {{ $categoria->created_at->format('d/m/Y') }}

                                </td>


                                <td class="px-6 py-5">

                                    <div class="flex justify-center gap-2">

                                        <!-- EDITAR -->
                                        <a href="{{ route('categorias.edit', $categoria) }}"
                                           class="bg-blue-100 hover:bg-blue-200 text-blue-700 px-3 py-2 rounded-lg font-semibold text-sm transition">
                                            ✏️ Editar
                                        </a>


                                        <!-- ELIMINAR -->
                                        <a href="{{ route('categorias.show', $categoria) }}"
                                           class="bg-red-100 hover:bg-red-200 text-red-700 px-3 py-2 rounded-lg font-semibold text-sm transition">
                                            🗑️ Eliminar
                                        </a>

                                    </div>

                                </td>

                            </tr>

                        @endforeach

                    </tbody>

                </table>

            </div>

        @else

            <!-- SIN CATEGORÍAS -->

            <div class="text-center py-16">

                <div class="text-6xl mb-4">
                    📂
                </div>

                <h3 class="text-xl font-bold text-gray-700">
                    No hay categorías registradas
                </h3>

                <p class="text-gray-500 mt-2 mb-6">
                    Empieza creando la primera categoría.
                </p>

                <a href="{{ route('categorias.create') }}"
                   class="inline-block bg-orange-500 hover:bg-orange-600 text-white px-5 py-3 rounded-xl font-semibold">
                    + Crear categoría
                </a>

            </div>

        @endif

    </div>

</div>

</body>
</html>