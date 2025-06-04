<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TallyIntegrationController;

Route::middleware('auth')->prefix('tally-integration')->group(function () {
    Route::get('/', [TallyIntegrationController::class, 'index'])->name('tally.integration');
    Route::post('/import-ledgers', [TallyIntegrationController::class, 'importLedgers'])->name('tally.import-ledgers');
    Route::post('/export-ledgers', [TallyIntegrationController::class, 'exportLedgers'])->name('tally.export-ledgers');
    Route::post('/import-vouchers', [TallyIntegrationController::class, 'importVouchers'])->name('tally.import-vouchers');
    Route::post('/export-vouchers', [TallyIntegrationController::class, 'exportVouchers'])->name('tally.export-vouchers');
    Route::post('/import-stock-items', [TallyIntegrationController::class, 'importStockItems'])->name('tally.import-stock-items');
    Route::post('/export-stock-items', [TallyIntegrationController::class, 'exportStockItems'])->name('tally.export-stock-items');
});

