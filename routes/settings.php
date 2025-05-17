<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SettingController;



    Route::any('admin-mode', [SettingController::class, 'mode'])->name('erp.change-mode');

