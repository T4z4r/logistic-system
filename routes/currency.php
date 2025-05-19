<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CurrencyController;

// Currency Routes
Route::get('/currencies/index', [CurrencyController::class, 'index'])->name('currencies.index');
Route::get('/currencies', [CurrencyController::class, 'index'])->name('currencies.list');
Route::get('/currencies/active', [CurrencyController::class, 'active'])->name('currencies.active');
Route::get('/currencies/inactive', [CurrencyController::class, 'inactive'])->name('currencies.inactive');
Route::get('/currencies/create', [CurrencyController::class, 'create'])->name('currencies.create');
Route::post('/currencies', [CurrencyController::class, 'store'])->name('currencies.store');
Route::get('/currencies/{id}/edit', [CurrencyController::class, 'edit'])->name('currencies.edit');
Route::put('/currencies/{id}', [CurrencyController::class, 'update'])->name('currencies.update');
Route::delete('/currencies/{id}', [CurrencyController::class, 'destroy'])->name('currencies.delete');
