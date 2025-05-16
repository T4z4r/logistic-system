<?php

use App\Http\Controllers\DriverController;
use Illuminate\Support\Facades\Route;


// Driver Management Routes
Route::get('/drivers', [DriverController::class, 'index'])->name('drivers.list');
Route::get('/drivers/active', [DriverController::class, 'active'])->name('drivers.active');
Route::get('/drivers/inactive', [DriverController::class, 'inactive'])->name('drivers.inactive');
Route::get('/drivers/create', [DriverController::class, 'create'])->name('drivers.create');
Route::post('/drivers', [DriverController::class, 'store'])->name('drivers.store');
Route::get('/drivers/{id}/edit', [DriverController::class, 'edit'])->name('drivers.edit');
Route::put('/drivers/{id}', [DriverController::class, 'update'])->name('drivers.update');
Route::delete('/drivers/{id}', [DriverController::class, 'destroy'])->name('drivers.delete');
