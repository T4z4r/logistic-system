<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ChartGroupsController;
use App\Http\Controllers\ChartLedgersController;
use App\Http\Controllers\ChartCostCentersController;
use App\Http\Controllers\ChartCostCategoriesController;
use App\Http\Controllers\ChartVoucherTypesController;
use App\Http\Controllers\ChartGodownsController;
use App\Http\Controllers\ChartUnitsController;


Route::middleware('auth')->prefix('chart-of-accounts/groups')->group(function () {
    Route::get('/', [ChartGroupsController::class, 'index'])->name('chart.groups.index');
    Route::post('/store', [ChartGroupsController::class, 'store'])->name('chart.groups.store');
    Route::put('/update/{id}', [ChartGroupsController::class, 'update'])->name('chart.groups.update');
    Route::delete('/delete/{id}', [ChartGroupsController::class, 'destroy'])->name('chart.groups.destroy');
});

Route::middleware('auth')->prefix('chart-of-accounts/ledgers')->group(function () {
    Route::get('/', [ChartLedgersController::class, 'index'])->name('chart.ledgers.index');
    Route::post('/store', [ChartLedgersController::class, 'store'])->name('chart.ledgers.store');
    Route::put('/update/{id}', [ChartLedgersController::class, 'update'])->name('chart.ledgers.update');
    Route::delete('/delete/{id}', [ChartLedgersController::class, 'destroy'])->name('chart.ledgers.destroy');
});


Route::middleware('auth')->prefix('chart-of-accounts/cost-centers')->group(function () {
    Route::get('/', [ChartCostCentersController::class, 'index'])->name('chart.cost-centers.index');
    Route::post('/store', [ChartCostCentersController::class, 'store'])->name('chart.cost-centers.store');
    Route::put('/update/{id}', [ChartCostCentersController::class, 'update'])->name('chart.cost-centers.update');
    Route::delete('/delete/{id}', [ChartCostCentersController::class, 'destroy'])->name('chart.cost-centers.destroy');
});

Route::middleware('auth')->prefix('chart-of-accounts/cost-categories')->group(function () {
    Route::get('/', [ChartCostCategoriesController::class, 'index'])->name('chart.cost-categories.index');
    Route::post('/store', [ChartCostCategoriesController::class, 'store'])->name('chart.cost-categories.store');
    Route::put('/update/{id}', [ChartCostCategoriesController::class, 'update'])->name('chart.cost-categories.update');
    Route::delete('/delete/{id}', [ChartCostCategoriesController::class, 'destroy'])->name('chart.cost-categories.destroy');
});

Route::middleware('auth')->prefix('chart-of-accounts/godowns')->group(function () {
    Route::get('/', [ChartGodownsController::class, 'index'])->name('chart.godowns.index');
    Route::post('/store', [ChartGodownsController::class, 'store'])->name('chart.godowns.store');
    Route::put('/update/{id}', [ChartGodownsController::class, 'update'])->name('chart.godowns.update');
    Route::delete('/delete/{id}', [ChartGodownsController::class, 'destroy'])->name('chart.godowns.destroy');
});

Route::middleware('auth')->prefix('chart-of-accounts/voucher-types')->group(function () {
    Route::get('/', [ChartVoucherTypesController::class, 'index'])->name('chart.voucher-types.index');
    Route::post('/store', [ChartVoucherTypesController::class, 'store'])->name('chart.voucher-types.store');
    Route::put('/update/{id}', [ChartVoucherTypesController::class, 'update'])->name('chart.voucher-types.update');
    Route::delete('/delete/{id}', [ChartVoucherTypesController::class, 'destroy'])->name('chart.voucher-types.destroy');
});

Route::middleware('auth')->prefix('chart-of-accounts/units')->group(function () {
    Route::get('/', [ChartUnitsController::class, 'index'])->name('chart.units.index');
    Route::post('/store', [ChartUnitsController::class, 'store'])->name('chart.units.store');
    Route::put('/update/{id}', [ChartUnitsController::class, 'update'])->name('chart.units.update');
    Route::delete('/delete/{id}', [ChartUnitsController::class, 'destroy'])->name('chart.units.destroy');
});
