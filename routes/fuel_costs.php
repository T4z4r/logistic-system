<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FuelCostController;

// Fuel Cost Management Routes
Route::get('/fuel-costs', [FuelCostController::class, 'index'])->name('fuel-costs.list');
Route::get('/fuel-costs/editable', [FuelCostController::class, 'editable'])->name('fuel-costs.editable');
Route::get('/fuel-costs/non-editable', [FuelCostController::class, 'nonEditable'])->name('fuel-costs.non-editable');
Route::get('/fuel-costs/create', [FuelCostController::class, 'create'])->name('fuel-costs.create');
Route::post('/fuel-costs', [FuelCostController::class, 'store'])->name('fuel-costs.store');
Route::get('/fuel-costs/{id}/edit', [FuelCostController::class, 'edit'])->name('fuel-costs.edit');
Route::put('/fuel-costs/{id}', [FuelCostController::class, 'update'])->name('fuel-costs.update');
Route::delete('/fuel-costs/{id}', [FuelCostController::class, 'destroy'])->name('fuel-costs.delete');
