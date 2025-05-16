<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TrailerController;


Route::get('/trailers', [TrailerController::class, 'index'])->name('trailers.list');
Route::get('/trailers/active', [TrailerController::class, 'active'])->name('trailers.active');
Route::get('/trailers/inactive', [TrailerController::class, 'inactive'])->name('trailers.inactive');
Route::get('/trailers/create', [TrailerController::class, 'create'])->name('trailers.create');
Route::post('/trailers', [TrailerController::class, 'store'])->name('trailers.store');
Route::get('/trailers/{id}/edit', [TrailerController::class, 'edit'])->name('trailers.edit');
Route::put('/trailers/{id}', [TrailerController::class, 'update'])->name('trailers.update');
Route::delete('/trailers/{id}', [TrailerController::class, 'destroy'])->name('trailers.delete');
