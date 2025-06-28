<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TaxController;
use App\Http\Controllers\ProcessLedgerController;
use App\Http\Controllers\ProcessLedgerMapperController;


Route::prefix('process-ledgers')->group(function () {
    Route::get('/', [ProcessLedgerController::class, 'index'])->name('process_ledgers.index');
    Route::post('/store', [ProcessLedgerController::class, 'store'])->name('process_ledgers.store');
    Route::post('/update/{processLedger}', [ProcessLedgerController::class, 'update'])->name('process_ledgers.update');
    Route::delete('/delete/{processLedger}', [ProcessLedgerController::class, 'destroy'])->name('process_ledgers.destroy');
});

Route::prefix('process-ledger-mappers')->group(function () {
    Route::get('/', [ProcessLedgerMapperController::class, 'index'])->name('process_ledger_mappers.index');
    Route::post('/store', [ProcessLedgerMapperController::class, 'store'])->name('process_ledger_mappers.store');
    Route::post('/update/{mapper}', [ProcessLedgerMapperController::class, 'update'])->name('process_ledger_mappers.update');
    Route::delete('/delete/{mapper}', [ProcessLedgerMapperController::class, 'destroy'])->name('process_ledger_mappers.destroy');
});

Route::prefix('settings')->name('finance.settings.')->group(function () {
    // Route::get('/ledgers', [LedgerController::class, 'index'])->name('ledgers');
    // Route::get('/sub-accounts', [SubAccountController::class, 'index'])->name('sub_accounts');
    // Route::get('/vat', [VatController::class, 'index'])->name('vat');
    Route::get('/process-ledgers', [ProcessLedgerMapperController::class, 'index'])->name('process_ledgers');
});

Route::get('taxes', [TaxController::class, 'index'])->name('taxes.index');
Route::post('taxes/store', [TaxController::class, 'store'])->name('taxes.store');
Route::put('taxes/{id}/update', [TaxController::class, 'update'])->name('taxes.update');
Route::delete('taxes/{id}', [TaxController::class, 'destroy'])->name('taxes.destroy');