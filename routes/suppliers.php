<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SupplierController;

// Customer Management Routes
Route::get('/suppliers', [SupplierController::class, 'index'])->name('suppliers.index');
Route::get('/suppliers/active', [SupplierController::class, 'active'])->name('suppliers.active');
Route::get('/suppliers/inactive', [SupplierController::class, 'inactive'])->name('suppliers.inactive');
Route::get('/suppliers/create', [SupplierController::class, 'create'])->name('suppliers.create');
Route::post('/suppliers', [SupplierController::class, 'store'])->name('suppliers.store');
Route::get('/suppliers/{id}/edit', [SupplierController::class, 'edit'])->name('suppliers.edit');
Route::put('/suppliers/{id}', [SupplierController::class, 'update'])->name('suppliers.update');
Route::any('/suppliers/delet/{id}', [SupplierController::class, 'destroy'])->name( 'suppliers.destroy');
