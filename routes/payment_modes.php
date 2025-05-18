<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PaymentModeController;
// Payment Mode Routes
Route::get('/payment-modes', [PaymentModeController::class, 'index'])->name('payment-modes.list');
Route::get('/payment-modes/active', [PaymentModeController::class, 'active'])->name('payment-modes.active');
Route::get('/payment-modes/inactive', [PaymentModeController::class, 'inactive'])->name('payment-modes.inactive');
Route::get('/payment-modes/create', [PaymentModeController::class, 'create'])->name('payment-modes.create');
Route::post('/payment-modes', [PaymentModeController::class, 'store'])->name('payment-modes.store');
Route::get('/payment-modes/{id}/edit', [PaymentModeController::class, 'edit'])->name('payment-modes.edit');
Route::put('/payment-modes/{id}', [PaymentModeController::class, 'update'])->name('payment-modes.update');
Route::delete('/payment-modes/{id}', [PaymentModeController::class, 'destroy'])->name('payment-modes.delete');
