<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TruckChangeRequestController;


Route::prefix('truck-change-requests')->group(function () {
    Route::get('/', [TruckChangeRequestController::class, 'index'])->name('truck-change-requests.index');
    Route::get('/create', [TruckChangeRequestController::class, 'create'])->name('truck-change-requests.create');
    Route::post('/store', [TruckChangeRequestController::class, 'store'])->name('truck-change-requests.store');
    Route::get('/edit/{id}', [TruckChangeRequestController::class, 'edit'])->name('truck-change-requests.edit');
    Route::post('/update/{id}', [TruckChangeRequestController::class, 'update'])->name('truck-change-requests.update');
    Route::delete('/destroy/{id}', [TruckChangeRequestController::class, 'destroy'])->name('truck-change-requests.destroy');
});
