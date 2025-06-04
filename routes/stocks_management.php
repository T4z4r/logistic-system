<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StockGroupsController;
use App\Http\Controllers\StockItemsController;

Route::middleware('auth')->prefix('stock/stock-groups')->group(function () {
    Route::get('/', [StockGroupsController::class, 'index'])->name('stock.groups.index');
    Route::post('/store', [StockGroupsController::class, 'store'])->name('stock.groups.store');
    Route::put('/update/{id}', [StockGroupsController::class, 'update'])->name('stock.groups.update');
    Route::delete('/delete/{id}', [StockGroupsController::class, 'destroy'])->name('stock.groups.destroy');
});


Route::middleware('auth')->prefix('stock/stock-items')->group(function () {
    Route::get('/', [StockItemsController::class, 'index'])->name('stock.items.index');
    Route::post('/store', [StockItemsController::class, 'store'])->name('stock.items.store');
    Route::put('/update/{id}', [StockItemsController::class, 'update'])->name('stock.items.update');
    Route::delete('/delete/{id}', [StockItemsController::class, 'destroy'])->name('stock.items.destroy');
});
