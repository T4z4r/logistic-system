<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MobilizationRouteController;

Route::prefix('mobilization-routes')->group(function () {
    Route::get('/', [MobilizationRouteController::class, 'index'])->name('mobilization_routes.index');
    Route::get('/create', [MobilizationRouteController::class, 'create'])->name('mobilization_routes.create');
    Route::post('/store', [MobilizationRouteController::class, 'store'])->name('mobilization_routes.store');
    Route::get('/{mobilization_route}/edit', [MobilizationRouteController::class, 'edit'])->name('mobilization_routes.edit');
    Route::put('/{mobilization_route}', [MobilizationRouteController::class, 'update'])->name('mobilization_routes.update');
    Route::delete('/{mobilization_route}', [MobilizationRouteController::class, 'destroy'])->name('mobilization_routes.destroy');
});

