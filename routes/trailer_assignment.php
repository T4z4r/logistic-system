<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TrailerAssignmentController;


// Trailer Assignment Routes
Route::get('/trailer-assignments', [TrailerAssignmentController::class, 'index'])->name('trailer-assignments.list');
Route::get('/trailer-assignments/active', [TrailerAssignmentController::class, 'active'])->name('trailer-assignments.active');
Route::get('/trailer-assignments/inactive', [TrailerAssignmentController::class, 'inactive'])->name('trailer-assignments.inactive');
Route::get('/trailer-assignments/create', [TrailerAssignmentController::class, 'create'])->name('trailer-assignments.create');
Route::post('/trailer-assignments', [TrailerAssignmentController::class, 'store'])->name('trailer-assignments.store');
Route::get('/trailer-assignments/{id}/edit', [TrailerAssignmentController::class, 'edit'])->name('trailer-assignments.edit');
Route::put('/trailer-assignments/{id}', [TrailerAssignmentController::class, 'update'])->name('trailer-assignments.update');
Route::delete('/trailer-assignments/{id}', [TrailerAssignmentController::class, 'destroy'])->name('trailer-assignments.delete');
Route::post('/trailers/{trailer_id}/assign-truck', [TrailerAssignmentController::class, 'assignTruck'])->name('trailers.assign-truck');
Route::delete('/trailers/{trailer_id}/deassign-truck', [TrailerAssignmentController::class, 'deassignTruck'])->name('trailers.deassign-truck');