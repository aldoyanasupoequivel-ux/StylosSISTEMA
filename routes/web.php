<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CuentaController;
use App\Http\Controllers\ConsultaController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\PedidoController;
use App\Http\Controllers\MaterialController;
use App\Http\Controllers\MovimientoInventarioController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ReporteController;
use App\Http\Controllers\PagoController;
use App\Http\Controllers\AlertaController;

// Consulta Pública (Cliente)
Route::get('/', [ConsultaController::class, 'index'])->name('consulta.index');
Route::post('/consulta', [ConsultaController::class, 'buscar'])->name('consulta.buscar');

// Autenticación
Route::get('/login', [AuthController::class, 'mostrarLogin'])->name('login');
Route::post('/login', [AuthController::class, 'iniciarSesion'])->name('login.post');
Route::post('/logout', [AuthController::class, 'cerrarSesion'])->name('logout');

// Rutas protegidas (Administrativas)
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Gestión de Cuenta
    Route::get('/cuenta', [CuentaController::class, 'mostrar'])->name('cuenta.index');
    Route::put('/cuenta/usuario', [CuentaController::class, 'actualizarUsuario'])->name('cuenta.usuario');
    Route::put('/cuenta/password', [CuentaController::class, 'cambiarPassword'])->name('cuenta.password');

    // Clientes
    Route::resource('clientes', ClienteController::class);

    // Pedidos
    Route::resource('pedidos', PedidoController::class);
    Route::post('/pedidos/{pedido}/pagos', [PagoController::class, 'store'])->name('pagos.store');

    // Inventario (Materiales)
    Route::resource('materiales', MaterialController::class);
    Route::post('/materiales/{material}/movimientos', [MovimientoInventarioController::class, 'store'])->name('movimientos.store');

    // Reportes
    Route::get('/reportes', [ReporteController::class, 'index'])->name('reportes.index');

    // Alertas
    Route::get('/alertas', [AlertaController::class, 'index'])->name('alertas.index');
});
