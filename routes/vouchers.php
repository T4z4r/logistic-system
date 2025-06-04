<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PaymentVoucherController;
use App\Http\Controllers\ReceiptVoucherController;
use App\Http\Controllers\ContraVoucherController;
use App\Http\Controllers\SalesVoucherController;
use App\Http\Controllers\PurchaseVoucherController;
use App\Http\Controllers\JournalVoucherController;


Route::middleware('auth')->prefix('vouchers/payment')->group(function () {
    Route::get('/', [PaymentVoucherController::class, 'index'])->name('vouchers.payment.index');
    Route::post('/store', [PaymentVoucherController::class, 'store'])->name('vouchers.payment.store');
    Route::put('/update/{id}', [PaymentVoucherController::class, 'update'])->name('vouchers.payment.update');
    Route::delete('/delete/{id}', [PaymentVoucherController::class, 'destroy'])->name('vouchers.payment.destroy');
});

Route::middleware('auth')->prefix('vouchers/receipt')->group(function () {
    Route::get('/', [ReceiptVoucherController::class, 'index'])->name('vouchers.receipt.index');
    Route::post('/store', [ReceiptVoucherController::class, 'store'])->name('vouchers.receipt.store');
    Route::put('/update/{id}', [ReceiptVoucherController::class, 'update'])->name('vouchers.receipt.update');
    Route::delete('/delete/{id}', [ReceiptVoucherController::class, 'destroy'])->name('vouchers.receipt.destroy');
});


Route::middleware('auth')->prefix('vouchers/contra')->group(function () {
    Route::get('/', [ContraVoucherController::class, 'index'])->name('vouchers.contra.index');
    Route::post('/store', [ContraVoucherController::class, 'store'])->name('vouchers.contra.store');
    Route::put('/update/{id}', [ContraVoucherController::class, 'update'])->name('vouchers.contra.update');
    Route::delete('/delete/{id}', [ContraVoucherController::class, 'destroy'])->name('vouchers.contra.destroy');
});

Route::middleware('auth')->prefix('vouchers/sales')->group(function () {
    Route::get('/', [SalesVoucherController::class, 'index'])->name('vouchers.sales.index');
    Route::post('/store', [SalesVoucherController::class, 'store'])->name('vouchers.sales.store');
    Route::put('/update/{id}', [SalesVoucherController::class, 'update'])->name('vouchers.sales.update');
    Route::delete('/delete/{id}', [SalesVoucherController::class, 'destroy'])->name('vouchers.sales.destroy');
});

Route::middleware('auth')->prefix('vouchers/purchase')->group(function () {
    Route::get('/', [PurchaseVoucherController::class, 'index'])->name('vouchers.purchase.index');
    Route::post('/store', [PurchaseVoucherController::class, 'store'])->name('vouchers.purchase.store');
    Route::put('/update/{id}', [PurchaseVoucherController::class, 'update'])->name('vouchers.purchase.update');
    Route::delete('/delete/{id}', [PurchaseVoucherController::class, 'destroy'])->name('vouchers.purchase.destroy');
});


Route::middleware('auth')->prefix('vouchers/journal')->group(function () {
    Route::get('/', [JournalVoucherController::class, 'index'])->name('vouchers.journal.index');
    Route::post('/store', [JournalVoucherController::class, 'store'])->name('vouchers.journal.store');
    Route::put('/update/{id}', [JournalVoucherController::class, 'update'])->name('vouchers.journal.update');
    Route::delete('/delete/{id}', [JournalVoucherController::class, 'destroy'])->name('vouchers.journal.destroy');
});
