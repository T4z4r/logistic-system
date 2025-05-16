<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RouteController;


// Route Management Routes
Route::get('/routes', [RouteController::class, 'index'])->name('routes.list');
Route::get('/routes/active', [RouteController::class, 'active'])->name('routes.active');
Route::get('/routes/inactive', [RouteController::class, 'inactive'])->name('routes.inactive');
Route::get('/routes/create', [RouteController::class, 'create'])->name('routes.create');
Route::post('/routes', [RouteController::class, 'store'])->name('routes.store');
Route::get('/routes/{id}/edit', [RouteController::class, 'edit'])->name('routes.edit');
Route::put('/routes/{id}', [RouteController::class, 'update'])->name('routes.update');
Route::delete('/routes/{id}', [RouteController::class, 'destroy'])->name('routes.delete');
