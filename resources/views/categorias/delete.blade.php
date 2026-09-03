<!DOCTYPE html>
<html lang="es">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Eliminar Categoría | El Olímpico</title>

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

    <div class="w-full max-w-lg">

        <!-- VOLVER -->

        <a href="{{ route('categorias.index') }}"
           class="text-gray-500 hover:text-orange-600 text-sm">

            ← Volver a categorías

        </a>


        <!-- CARD -->

        <div class="bg-white rounded-2xl card p-8 mt-4 text-center">


            <!-- ICONO -->

            <div class="w-20 h-20 mx-auto bg-red-100 rounded-full flex items-center justify-center mb-6">

                <span class="text-4xl">
                    🗑️
                </span>

            </div>


            <h1 class="text-2xl font-bold text-gray-800 mb-3">

                ¿Eliminar categoría?

            </h1>


            <p class="text-gray-500 mb-6">

                Estás a punto de eliminar la categoría:

            </p>


            <!-- NOMBRE -->

            <div class="bg-gray-50 rounded-xl p-4 mb-6">

                <h2 class="text-xl font-bold text-gray-800">

                    {{ $categoria->nombre }}

                </h2>


                @if($categoria->descripcion)

                    <p class="text-gray-500 text-sm mt-1">

                        {{ $categoria->descripcion }}

                    </p>

                @endif

            </div>


            <!-- ADVERTENCIA -->

            <div class="bg-red-50 border border-red-200 rounded-xl p-4 text-left mb-6">

                <p class="text-red-700 text-sm">

                    ⚠️ <strong>Advertencia:</strong>

                    Esta acción eliminará la categoría permanentemente.

                </p>

            </div>


            <!-- FORMULARIO -->

            <form action="{{ route('categorias.destroy', $categoria) }}"
                  method="POST">

                @csrf

                @method('DELETE')


                <div class="flex flex-col md:flex-row gap-3">

                    <a href="{{ route('categorias.index') }}"
                       class="w-full border border-gray-300 hover:bg-gray-100 text-gray-700 px-5 py-3 rounded-xl font-semibold">

                        Cancelar

                    </a>


                    <button
                        type="submit"
                        class="w-full bg-red-600 hover:bg-red-700 text-white px-5 py-3 rounded-xl font-semibold">

                        🗑️ Sí, eliminar

                    </button>

                </div>

            </form>


        </div>

    </div>

</div>

</body>

</html>