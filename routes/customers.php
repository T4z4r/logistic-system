<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CustomerController;

Route::get('customers', [CustomerController::class, 'index'])->name('customers.index');
Route::post('customers/store', [CustomerController::class, 'store'])->name('customers.store');
Route::post('customers/{customer}/update', [CustomerController::class, 'update'])->name('customers.update');
Route::delete('customers/{customer}/delete', [CustomerController::class, 'destroy'])->name('customers.destroy');
