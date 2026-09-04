<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ExportarVentasCsv extends Command
{
    /**
     * Nombre del comando en la terminal
     */
    protected $signature = 'olimpico:exportar-ventas';

    /**
     * Descripción del comando
     */
    protected $description = 'Exporta el historial de ventas a un archivo CSV para el módulo de analítica en Python';

    /**
     * Ejecución del comando
     */
public function handle()
    {
        $this->info('Iniciando la exportación de ventas...');

        // 1. Consultar las ventas uniendo las tablas de la cevichería
        $ventas = DB::table('detalle_pedidos')
            ->join('pedidos', 'detalle_pedidos.pedido_id', '=', 'pedidos.id')
            ->join('productos', 'detalle_pedidos.producto_id', '=', 'productos.id')
            ->leftJoin('categorias', 'productos.categoria_id', '=', 'categorias.id')
            ->select(
                'pedidos.created_at as fecha',
                'productos.nombre as producto',
                'categorias.nombre as categoria',
                'detalle_pedidos.cantidad',
                'detalle_pedidos.precio_unitario as precio',
                'detalle_pedidos.subtotal as total'
            )
            ->get();

        if ($ventas->isEmpty()) {
            $this->warn('No se encontraron registros de ventas para exportar.');
            return 0;
        }

        // 2. Definir ruta física directa en storage/app/exports
        $destinationPath = storage_path('app/exports');

        if (!file_exists($destinationPath)) {
            mkdir($destinationPath, 0755, true);
        }

        // 3. Crear contenido CSV
        $file = fopen('php://temp', 'r+');
        fputcsv($file, ['fecha', 'producto', 'categoria', 'cantidad', 'precio', 'total']);

        foreach ($ventas as $row) {
            fputcsv($file, [
                $row->fecha,
                $row->producto,
                $row->categoria ?? 'Sin Categoría',
                $row->cantidad,
                $row->precio,
                $row->total
            ]);
        }

        rewind($file);
        $content = stream_get_contents($file);
        fclose($file);

        // 4. Guardar archivo directo
        $fullFilePath = $destinationPath . '/ventas.csv';
        file_put_contents($fullFilePath, $content);

        $this->info("¡Exportación exitosa! Archivo guardado en: {$fullFilePath}");
        return 0;
    }
}    