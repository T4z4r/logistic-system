<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FuelServiceController;

Route::any('/trips/fuel-lpo', [FuelServiceController::class, 'save_lpo'])->name('flex.fuel_lpo');
Route::any('/trips/offbudget-fuel-lpo', [FuelServiceController::class, 'save_offbudget_lpo'])->name('flex.offbudget_fuel_lpo');
Route::any('/trips/admin-fuel-lpo', [FuelServiceController::class, 'save_administration_lpo'])->name('flex.admin_fuel_lpo');
Route::any('/trips/retiremnt-fuel-lpo', [FuelServiceController::class, 'save_retirement_lpo'])->name('flex.retirement_fuel_lpo');
Route::any('trips/generate-fuel-expense', [FuelServiceController::class, 'generate_lpo'])->name('flex.generate_fuel_lpo');
Route::any('trips/save-fuel-expense', [FuelServiceController::class, 'save_bulk_lpo'])->name('flex.save_fuel_lpo');
Route::any('trips/cost_details/{id}', [FuelServiceController::class, 'getCostDetails'])->name('flex.getCostDetails');