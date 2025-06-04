<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ReportsController;

Route::middleware('auth')->prefix('accounting-reports')->group(function () {
    Route::get('/trial-balance', [ReportsController::class, 'trialBalance'])->name('reports.trial-balance');
    Route::get('/profit-loss', [ReportsController::class, 'profitLoss'])->name('reports.profit-loss');
    Route::get('/balance-sheet', [ReportsController::class, 'balanceSheet'])->name('reports.balance-sheet');
    Route::get('/ledger', [ReportsController::class, 'ledger'])->name('reports.ledger');
    Route::get('/cash-book', [ReportsController::class, 'cashBook'])->name('reports.cash-book');
});
