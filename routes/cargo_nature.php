<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CargoNatureController;

// Cargo Nature Routes
Route::get('/cargo-natures', [CargoNatureController::class, 'index'])->name('cargo-natures.list');
Route::get('/cargo-natures/active', [CargoNatureController::class, 'active'])->name('cargo-natures.active');
Route::get('/cargo-natures/inactive', [CargoNatureController::class, 'inactive'])->name('cargo-natures.inactive');
Route::get('/cargo-natures/create', [CargoNatureController::class, 'create'])->name('cargo-natures.create');
Route::post('/cargo-natures', [CargoNatureController::class, 'store'])->name('cargo-natures.store');
Route::get('/cargo-natures/{id}/edit', [CargoNatureController::class, 'edit'])->name('cargo-natures.edit');
Route::put('/cargo-natures/{id}', [CargoNatureController::class, 'update'])->name('cargo-natures.update');
Route::delete('/cargo-natures/{id}', [CargoNatureController::class, 'destroy'])->name('cargo-natures.delete');
