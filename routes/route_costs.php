<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RouteCostController;


// Route Cost Routes
Route::get('/routes/{route_id}/costs', [RouteCostController::class, 'index'])->name('route-costs.list');
Route::get('/routes/{route_id}/costs/create', [RouteCostController::class, 'create'])->name('route-costs.create');
Route::post('/routes/{route_id}/costs', [RouteCostController::class, 'store'])->name('route-costs.store');
Route::get('/routes/{route_id}/costs/{id}/edit', [RouteCostController::class, 'edit'])->name('route-costs.edit');
Route::put('/routes/{route_id}/costs/{id}', [RouteCostController::class, 'update'])->name('route-costs.update');
Route::delete('/routes/{route_id}/costs/{id}', [RouteCostController::class, 'destroy'])->name('route-costs.delete');