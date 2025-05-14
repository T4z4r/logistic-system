<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PositionController;

Route::get('positions', [PositionController::class, 'index'])->name('positions.index');
Route::post('positions/store', [PositionController::class, 'store'])->name('positions.store');
Route::post('positions/{position}/update', [PositionController::class, 'update'])->name('positions.update');
Route::delete('positions/{position}/delete', [PositionController::class, 'destroy'])->name('positions.destroy');
