<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AllocationController;

// Allocation Routes
Route::get('/allocations', [AllocationController::class, 'index'])->name('allocations.list');
Route::get('/allocations/active', [AllocationController::class, 'active'])->name('allocations.active');
Route::get('/allocations/inactive', [AllocationController::class, 'inactive'])->name('allocations.inactive');
Route::get('/allocations/create', [AllocationController::class, 'create'])->name('allocations.create');
Route::post('/allocations', [AllocationController::class, 'store'])->name('allocations.store');
Route::get('/allocations/{id}/edit', [AllocationController::class, 'edit'])->name('allocations.edit');
Route::put('/allocations/{id}', [AllocationController::class, 'update'])->name('allocations.update');
Route::delete('/allocations/{id}', [AllocationController::class, 'destroy'])->name('allocations.delete');
