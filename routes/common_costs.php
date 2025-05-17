<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CommonCostController;

// Common Cost Management Routes
Route::get('/common-costs', [CommonCostController::class, 'index'])->name('common-costs.list');
Route::get('/common-costs/editable', [CommonCostController::class, 'editable'])->name('common-costs.editable');
Route::get('/common-costs/non-editable', [CommonCostController::class, 'nonEditable'])->name('common-costs.non-editable');
Route::get('/common-costs/create', [CommonCostController::class, 'create'])->name('common-costs.create');
Route::post('/common-costs', [CommonCostController::class, 'store'])->name('common-costs.store');
Route::get('/common-costs/{id}/edit', [CommonCostController::class, 'edit'])->name('common-costs.edit');
Route::put('/common-costs/{id}', [CommonCostController::class, 'update'])->name('common-costs.update');
Route::delete('/common-costs/{id}', [CommonCostController::class, 'destroy'])->name('common-costs.delete');