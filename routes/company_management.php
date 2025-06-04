<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CompanyManagementController;

Route::middleware('auth')->prefix('company-management')->group(function () {
    Route::get('/', [CompanyManagementController::class, 'index'])->name('company.management.index');
    Route::post('/store', [CompanyManagementController::class, 'store'])->name('company.management.store');
    Route::put('/update/{id}', [CompanyManagementController::class, 'update'])->name('company.management.update');
    Route::delete('/delete/{id}', [CompanyManagementController::class, 'destroy'])->name('company.management.destroy');
});
