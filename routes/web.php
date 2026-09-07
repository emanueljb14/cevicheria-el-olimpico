<?php

use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DetallePedidoController;
use App\Http\Controllers\InventarioController;
use App\Http\Controllers\MesaController;
use App\Http\Controllers\PagoController;
use App\Http\Controllers\PedidoController;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReporteController;
use App\Http\Controllers\CierreCajaController;
use App\Http\Controllers\VentaController;
use Illuminate\Support\Facades\Route;

// Ruta Pública / Bienvenida
Route::get('/', function () {
    return view('welcome');
})->name('welcome');

// Ruta Principal del Dashboard apuntando al DashboardController
Route::get('/dashboard', DashboardController::class)
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

// Rutas Protegidas por Autenticación
Route::middleware('auth')->group(function () {
    
    // Perfil de Usuario
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Módulos Principales del Restaurante "El Olímpico"
    Route::resource('categorias', CategoriaController::class);
    Route::resource('productos', ProductoController::class);
    Route::resource('clientes', ClienteController::class);
    
    // Mesas y Cambio Rápido de Estado
    Route::resource('mesas', MesaController::class);
    Route::patch('mesas/{mesa}/cambiar-estado', [MesaController::class, 'cambiarEstado'])->name('mesas.cambiarEstado');

    // Pedidos y Detalles de Pedido
    Route::resource('pedidos', PedidoController::class);
    Route::patch('pedidos/{pedido}/cambiar-estado', [PedidoController::class, 'cambiarEstado'])->name('pedidos.cambiarEstado');
    Route::resource('detalle_pedidos', DetallePedidoController::class)->except(['create', 'edit']);

    // Inventario y Ajuste de Stock
    Route::resource('inventario', InventarioController::class);
    Route::patch('inventario/{inventario}/actualizar-stock', [InventarioController::class, 'actualizarStock'])->name('inventario.actualizar-stock');
    
    // Cierre de Caja
    Route::resource('cierre-caja', CierreCajaController::class);

    // Transacciones y Analítica
    Route::resource('pagos', PagoController::class);
    Route::resource('ventas', VentaController::class)->only(['index', 'create', 'show', 'destroy']);
    Route::resource('reportes', ReporteController::class);
    
    // Módulo de Analítica IA (Streamlit Dashboard)
    Route::get('/analytics', function () {
        return view('analytics');
    })->name('analytics.index');
});

require __DIR__.'/auth.php';