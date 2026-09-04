<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class VentasPruebaSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Categorías
        $catCeviches = DB::table('categorias')->insertGetId(['nombre' => 'Ceviches', 'created_at' => now(), 'updated_at' => now()]);
        $catMariscos = DB::table('categorias')->insertGetId(['nombre' => 'Mariscos y Chicharrones', 'created_at' => now(), 'updated_at' => now()]);
        $catBebidas = DB::table('categorias')->insertGetId(['nombre' => 'Bebidas', 'created_at' => now(), 'updated_at' => now()]);

        // 2. Productos
        $p1 = DB::table('productos')->insertGetId(['categoria_id' => $catCeviches, 'nombre' => 'Ceviche Clásico', 'precio' => 28.00, 'created_at' => now(), 'updated_at' => now()]);
        $p2 = DB::table('productos')->insertGetId(['categoria_id' => $catCeviches, 'nombre' => 'Ceviche Mixto', 'precio' => 35.00, 'created_at' => now(), 'updated_at' => now()]);
        $p3 = DB::table('productos')->insertGetId(['categoria_id' => $catMariscos, 'nombre' => 'Jalea Mixta', 'precio' => 42.00, 'created_at' => now(), 'updated_at' => now()]);
        $p4 = DB::table('productos')->insertGetId(['categoria_id' => $catMariscos, 'nombre' => 'Arroz con Mariscos', 'precio' => 38.00, 'created_at' => now(), 'updated_at' => now()]);
        $p5 = DB::table('productos')->insertGetId(['categoria_id' => $catBebidas, 'nombre' => 'Chicha Morada 1L', 'precio' => 12.00, 'created_at' => now(), 'updated_at' => now()]);

        $productos = [
            ['id' => $p1, 'precio' => 28.00],
            ['id' => $p2, 'precio' => 35.00],
            ['id' => $p3, 'precio' => 42.00],
            ['id' => $p4, 'precio' => 38.00],
            ['id' => $p5, 'precio' => 12.00],
        ];

        // 3. Generar 50 pedidos aleatorios para los últimos 30 días
        for ($i = 0; $i < 50; $i++) {
            $fecha = Carbon::now()->subDays(rand(0, 30))->subHours(rand(1, 8));
            
            $pedidoId = DB::table('pedidos')->insertGetId([
                'created_at' => $fecha,
                'updated_at' => $fecha,
            ]);

            $prod = $productos[array_rand($productos)];
            $cantidad = rand(1, 4);
            $subtotal = $prod['precio'] * $cantidad;

            DB::table('detalle_pedidos')->insert([
                'pedido_id' => $pedidoId,
                'producto_id' => $prod['id'],
                'cantidad' => $cantidad,
                'precio_unitario' => $prod['precio'],
                'subtotal' => $subtotal,
                'created_at' => $fecha,
                'updated_at' => $fecha,
            ]);
        }
    }
}