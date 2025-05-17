<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApprovalController;

// Approval Management Routes
Route::get('/approvals', [ApprovalController::class, 'index'])->name('approvals.list');
Route::get('/approvals/create', [ApprovalController::class, 'create'])->name('approvals.create');
Route::post('/approvals', [ApprovalController::class, 'store'])->name('approvals.store');
Route::get('/approvals/{id}', [ApprovalController::class, 'show'])->name('approvals.show');
Route::get('/approvals/{id}/edit', [ApprovalController::class, 'edit'])->name('approvals.edit');
Route::put('/approvals/{id}', [ApprovalController::class, 'update'])->name('approvals.update');
Route::delete('/approvals/{id}', [ApprovalController::class, 'destroy'])->name('approvals.delete');
// Approval Level Routes (within Approval Show)
Route::post('/approvals/{id}/levels', [ApprovalController::class, 'storeLevel'])->name('approvals.levels.store');
Route::put('/approvals/{id}/levels/{level_id}', [ApprovalController::class, 'updateLevel'])->name('approvals.levels.update');
Route::delete('/approvals/{id}/levels/{level_id}', [ApprovalController::class, 'destroyLevel'])->name('approvals.levels.delete');