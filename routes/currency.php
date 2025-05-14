<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CurrencyController;

Route::get('currencies', [CurrencyController::class, 'index'])->name('currencies.index');
Route::post('currencies/store', [CurrencyController::class, 'store'])->name('currencies.store');
Route::post('currencies/{currency}/update', [CurrencyController::class, 'update'])->name('currencies.update');
Route::delete('currencies/{currency}/delete', [CurrencyController::class, 'destroy'])->name('currencies.destroy');
