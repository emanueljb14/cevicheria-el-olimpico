<?php

use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\DetallePedidoController;
use App\Http\Controllers\InventarioController;
use App\Http\Controllers\MesaController;
use App\Http\Controllers\PagoController;
use App\Http\Controllers\PedidoController;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReporteController;
use App\Http\Controllers\VentaController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('welcome');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    // Perfil de usuario
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Módulos del Restaurante "El Olímpico"
    Route::resource('categorias', CategoriaController::class);
    Route::resource('productos', ProductoController::class);
    Route::resource('clientes', ClienteController::class);
    
    // Mesas y acción rápida de cambio de estado
    Route::resource('mesas', MesaController::class);
    Route::patch('mesas/{mesa}/cambiar-estado', [MesaController::class, 'cambiarEstado'])->name('mesas.cambiar-estado');

    // Pedidos y detalles
    // Cambia estas líneas bajo el grupo // Pedidos y detalles:
    Route::resource('pedidos', PedidoController::class);
    Route::patch('pedidos/{pedido}/cambiar-estado', [PedidoController::class, 'cambiarEstado'])->name('pedidos.cambiarEstado'); // Cambiado a camelCase
    Route::resource('detalle_pedidos', DetallePedidoController::class)->except(['create', 'edit']); // Cambiado guion por guion bajo

    // Inventario y ajuste de stock
    Route::resource('inventario', InventarioController::class);
    Route::patch('inventario/{inventario}/actualizar-stock', [InventarioController::class, 'actualizarStock'])->name('inventario.actualizar-stock');

    // Transacciones y Analítica
    Route::resource('pagos', PagoController::class)->except(['edit']);
    Route::resource('ventas', VentaController::class)->only(['index', 'create', 'show', 'destroy']);
    Route::resource('reportes', ReporteController::class);
});

require __DIR__.'/auth.php';