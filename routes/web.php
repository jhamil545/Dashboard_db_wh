<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;

// Ruta para la vista principal (welcome)
Route::get('/', function () {
    return view('welcome');
});

// Ruta para el dashboard (vista principal con gráficos)
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

Route::get('/api/sales-by-year', [DashboardController::class, 'getSalesByYear']);
Route::get('/api/sales-by-brand', [DashboardController::class, 'getSalesByBrand']);
Route::get('/api/sales-by-region', [DashboardController::class, 'getSalesByRegion']);
Route::get('/api/top-customers', [DashboardController::class, 'getTopCustomers']);
Route::get('/api/clientes-genero', [DashboardController::class, 'getClientesPorGenero']);
Route::get('/top-concesionarios', [DashboardController::class, 'top10Concesionarios']);
