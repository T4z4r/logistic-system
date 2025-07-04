<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;


Route::get('/users', [UserController::class, 'index'])->name('users.index');
Route::get('/users/active', [UserController::class, 'active'])->name('users.active');
Route::get('/users/inactive', [UserController::class, 'inactive'])->name('users.inactive');
Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
Route::post('/users', [UserController::class, 'store'])->name('users.store');
Route::get('/users/{id}/show', [UserController::class, 'show'])->name('users.show');
Route::get('/users/{id}/edit', [UserController::class, 'edit'])->name('users.edit');
Route::put('/users/{id}', [UserController::class, 'update'])->name('users.update');
Route::post('/users/activate/{id}', [UserController::class, 'activate'])->name('users.activate');
Route::post('/users/deactivate/{id}', [UserController::class, 'deactivate'])->name('users.deactivate');
Route::delete('/users/{id}', [UserController::class, 'destroy'])->name('users.destroy');
Route::get('/users/reset', [UserController::class, 'reset'])->name('users.reset');
