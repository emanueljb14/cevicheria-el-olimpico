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
        Schema::create('cierre_cajas', function (Blueprint $table) {
            $table->id();
            
            // Si la tabla de usuarios se llama 'users', usa nullable() si el cierre se hace de forma automática o sin sesión directa
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete(); 
            
            $table->decimal('monto_apertura', 10, 2)->default(0.00);          // Fondo base
            $table->decimal('efectivo_fisico', 10, 2)->default(0.00);         // Efectivo contado en caja
            $table->decimal('digital_fisico', 10, 2)->default(0.00);          // Yape, Plin, POS Tarjetas
            $table->decimal('total_sistema', 10, 2)->default(0.00);           // Ventas registradas por el sistema
            $table->decimal('diferencia', 10, 2)->default(0.00);              // Sobrante (+) o Faltante (-)
            $table->string('estado', 20)->default('cerrado');                 // abierto / cerrado
            $table->text('observacion')->nullable();
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cierre_cajas');
    }
};