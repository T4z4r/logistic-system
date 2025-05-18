<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PaymentMethodController;

// Payment Method Routes
Route::get('/payment-methods', [PaymentMethodController::class, 'index'])->name('payment-methods.list');
Route::get('/payment-methods/active', [PaymentMethodController::class, 'active'])->name('payment-methods.active');
Route::get('/payment-methods/inactive', [PaymentMethodController::class, 'inactive'])->name('payment-methods.inactive');
Route::get('/payment-methods/create', [PaymentMethodController::class, 'create'])->name('payment-methods.create');
Route::post('/payment-methods', [PaymentMethodController::class, 'store'])->name('payment-methods.store');
Route::get('/payment-methods/{id}/edit', [PaymentMethodController::class, 'edit'])->name('payment-methods.edit');
Route::put('/payment-methods/{id}', [PaymentMethodController::class, 'update'])->name('payment-methods.update');
Route::delete('/payment-methods/{id}', [PaymentMethodController::class, 'destroy'])->name('payment-methods.delete');
