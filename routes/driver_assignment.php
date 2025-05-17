<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DriverAssignmentController;


// Driver Assignment Routes
Route::get('/driver-assignments', [DriverAssignmentController::class, 'index'])->name('driver-assignments.list');
Route::get('/driver-assignments/active', [DriverAssignmentController::class, 'active'])->name('driver-assignments.active');
Route::get('/driver-assignments/inactive', [DriverAssignmentController::class, 'inactive'])->name('driver-assignments.inactive');
Route::get('/driver-assignments/create', [DriverAssignmentController::class, 'create'])->name('driver-assignments.create');
Route::post('/driver-assignments', [DriverAssignmentController::class, 'store'])->name('driver-assignments.store');
Route::get('/driver-assignments/{id}/edit', [DriverAssignmentController::class, 'edit'])->name('driver-assignments.edit');
Route::put('/driver-assignments/{id}', [DriverAssignmentController::class, 'update'])->name('driver-assignments.update');
Route::delete('/driver-assignments/{id}', [DriverAssignmentController::class, 'destroy'])->name('driver-assignments.delete');


// New Routes for Assign/Deassign
Route::post('/trucks/{truck_id}/assign-driver', [DriverAssignmentController::class, 'assignDriver'])->name('trucks.assign-driver');
Route::delete('/trucks/{truck_id}/deassign-driver', [DriverAssignmentController::class, 'deassignDriver'])->name('trucks.deassign-driver');