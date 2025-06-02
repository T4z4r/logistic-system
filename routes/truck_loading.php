<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoadingController;

    // For Loading Routes
    Route::prefix('trips/')->controller(LoadingController::class)->middleware('auth')->group(function () {
        Route::any('loading-trucks', 'index')->name('flex.loading-trucks');
        Route::any('load-truck-allocation', 'load_truck')->name('flex.load-truck-allocation');
        Route::any('offload-truck-allocation', 'offload_truck')->name('flex.offload-truck-allocation');
    });
