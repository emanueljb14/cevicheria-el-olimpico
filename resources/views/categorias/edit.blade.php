<!DOCTYPE html>
<html lang="es">
<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Editar Categoría | El Olímpico</title>

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

<div class="min-h-screen flex items-center justify-center p-5">

    <div class="w-full max-w-2xl">

        <!-- VOLVER -->

        <a href="{{ route('categorias.index') }}"
           class="text-gray-500 hover:text-orange-600 text-sm">

            ← Volver a categorías

        </a>


        <!-- CARD -->

        <div class="bg-white rounded-2xl card p-8 mt-4">

            <div class="mb-8">

                <div class="text-4xl mb-3">
                    ✏️
                </div>

                <h1 class="text-3xl font-bold text-gray-800">
                    Editar Categoría
                </h1>

                <p class="text-gray-500 mt-1">
                    Actualiza la información de la categoría.
                </p>

            </div>


            <!-- ERRORES -->

            @if($errors->any())

                <div class="bg-red-100 border border-red-300 text-red-700 rounded-xl p-4 mb-6">

                    <ul class="list-disc ml-5">

                        @foreach($errors->all() as $error)

                            <li>{{ $error }}</li>

                        @endforeach

                    </ul>

                </div>

            @endif


            <!-- FORMULARIO -->

            <form action="{{ route('categorias.update', $categoria) }}" method="POST">

                @csrf

                @method('PUT')


                <!-- NOMBRE -->

                <div class="mb-6">

                    <label class="block text-gray-700 font-semibold mb-2">
                        Nombre de la categoría *
                    </label>

                    <input
                        type="text"
                        name="nombre"
                        value="{{ old('nombre', $categoria->nombre) }}"
                        required
                        class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-orange-400">

                </div>


                <!-- DESCRIPCIÓN -->

                <div class="mb-6">

                    <label class="block text-gray-700 font-semibold mb-2">
                        Descripción
                    </label>

                    <textarea
                        name="descripcion"
                        rows="5"
                        class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-orange-400">{{ old('descripcion', $categoria->descripcion) }}</textarea>

                </div>


                <!-- ESTADO -->

                <div class="mb-8">

                    <label class="flex items-center gap-3 cursor-pointer">

                        <input
                            type="checkbox"
                            name="estado"
                            value="1"
                            {{ old('estado', $categoria->estado) ? 'checked' : '' }}
                            class="w-5 h-5 text-orange-500">

                        <span class="font-semibold text-gray-700">
                            Categoría activa
                        </span>

                    </label>

                </div>


                <!-- BOTONES -->

                <div class="flex flex-col md:flex-row gap-3">

                    <a href="{{ route('categorias.index') }}"
                       class="w-full text-center border border-gray-300 hover:bg-gray-100 text-gray-700 px-5 py-3 rounded-xl font-semibold">

                        Cancelar

                    </a>


                    <button
                        type="submit"
                        class="w-full bg-blue-600 hover:bg-blue-700 text-white px-5 py-3 rounded-xl font-semibold">

                        ✓ Actualizar Categoría

                    </button>

                </div>

            </form>

        </div>

    </div>

</div>

</body>

</html>