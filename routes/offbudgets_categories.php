<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OffBudgetCategoryController;

Route::get('/off-budget-categories', [OffBudgetCategoryController::class, 'index'])->name('off_budget_categories.index');
Route::post('/off-budget-categories', [OffBudgetCategoryController::class, 'store'])->name('off_budget_categories.store');
Route::put('/off-budget-categories/{id}', [OffBudgetCategoryController::class, 'update'])->name('off_budget_categories.update');
Route::delete('/off-budget-categories/{id}', [OffBudgetCategoryController::class, 'destroy'])->name('off_budget_categories.destroy');

