<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CustomerController;

// Customer Management Routes
Route::get('/customers', [CustomerController::class, 'index'])->name('customers.index');
Route::get('/customers/active', [CustomerController::class, 'active'])->name('customers.active');
Route::get('/customers/inactive', [CustomerController::class, 'inactive'])->name('customers.inactive');
Route::get('/customers/create', [CustomerController::class, 'create'])->name('customers.create');
Route::post('/customers', [CustomerController::class, 'store'])->name('customers.store');
Route::get('/customers/{id}/edit', [CustomerController::class, 'edit'])->name('customers.edit');
Route::put('/customers/{id}', [CustomerController::class, 'update'])->name('customers.update');
Route::delete('/customers/{id}', [CustomerController::class, 'destroy'])->name('customers.delete');
