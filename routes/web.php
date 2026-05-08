<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\CotizacionController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ReporteController;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\VentaController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function (): void {
    Route::get('/', [LoginController::class, 'create'])->name('login');
    Route::get('/login', [LoginController::class, 'create']);
    Route::post('/login', [LoginController::class, 'store'])->name('login.store');
});

Route::middleware('auth')->group(function (): void {
    Route::post('/logout', [LoginController::class, 'destroy'])->name('logout');
    Route::get('/dashboard', DashboardController::class)->name('dashboard');

    Route::get('clientes', [ClienteController::class, 'index'])->name('clientes.index');
    Route::get('cotizaciones', [CotizacionController::class, 'index'])->name('cotizaciones.index');
    Route::get('ventas', [VentaController::class, 'index'])->name('ventas.index');

    Route::middleware('role:Administrador,Usuario Comercial')->group(function (): void {
        Route::resource('clientes', ClienteController::class)->only(['store', 'update', 'destroy']);
        Route::resource('cotizaciones', CotizacionController::class)
            ->only(['store', 'update', 'destroy'])
            ->parameters(['cotizaciones' => 'cotizacion']);
        Route::patch('cotizaciones/{cotizacion}/estado', [CotizacionController::class, 'changeStatus'])->name('cotizaciones.estado');
        Route::post('cotizaciones/{cotizacion}/convertir-venta', [CotizacionController::class, 'convertToVenta'])->name('cotizaciones.convertir-venta');
        Route::resource('ventas', VentaController::class)->only(['store']);
    });

    Route::middleware('role:Administrador')->group(function (): void {
        Route::resource('usuarios', UsuarioController::class)->only(['index', 'store', 'update', 'destroy']);
    });

    Route::middleware('role:Administrador,Usuario de Consulta')->group(function (): void {
        Route::get('reportes', [ReporteController::class, 'index'])->name('reportes.index');
    });
});
