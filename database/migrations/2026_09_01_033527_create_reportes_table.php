<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('reportes', function (Blueprint $table) {
            $table->id();
            $table->string('titulo');
            $table->enum('tipo', ['ventas', 'productos', 'inventario', 'pedidos']);
            $table->date('fecha_inicio')->nullable();
            $table->date('fecha_fin')->nullable();
            $table->decimal('monto_total', 10, 2)->default(0.00);
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null'); // Usuario que generó el reporte
            $table->string('archivo_pdf')->nullable(); // Ruta del reporte exportado en PDF
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reportes');
    }
};