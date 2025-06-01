<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TruckController;

// Truck Management Routes
Route::get('/trucks', [TruckController::class, 'index'])->name('trucks.list');
Route::get('/trucks/active', [TruckController::class, 'active'])->name('trucks.active');
Route::get('/trucks/inactive', [TruckController::class, 'inactive'])->name('trucks.inactive');
Route::get('/trucks/create', [TruckController::class, 'create'])->name('trucks.create');
Route::post('/trucks', [TruckController::class, 'store'])->name('trucks.store');
Route::get('/trucks/{id}/edit', [TruckController::class, 'edit'])->name('trucks.edit');
Route::get('/trucks/{id}/show', [TruckController::class, 'show'])->name('trucks.show');
Route::put('/trucks/{id}', [TruckController::class, 'update'])->name('trucks.update');
Route::delete('/trucks/{id}', [TruckController::class, 'destroy'])->name('trucks.delete');

// Soft Delete Routes
Route::get('/trucks/trashed', [TruckController::class, 'trashed'])->name('trucks.trashed');
Route::put('/trucks/{id}/restore', [TruckController::class, 'restore'])->name('trucks.restore');
Route::delete('/trucks/{id}/force-delete', [TruckController::class, 'forceDelete'])->name('trucks.forceDelete');
