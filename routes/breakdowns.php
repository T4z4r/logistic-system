<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BreakdownController;

// Breakdown Routes
Route::prefix('breakdowns')->name('breakdowns.')->group(function () {
    Route::get('/', [BreakdownController::class, 'index'])->name('index');
    Route::get('/create', [BreakdownController::class, 'create'])->name('create');
    Route::post('/', [BreakdownController::class, 'store'])->name('store');
    Route::get('/{id}/edit', [BreakdownController::class, 'edit'])->name('edit');
    Route::put('/{id}', [BreakdownController::class, 'update'])->name('update');
    Route::delete('/{id}', [BreakdownController::class, 'destroy'])->name('destroy');
    Route::get('/{id}/show', [BreakdownController::class, 'show'])->name('show');
});
