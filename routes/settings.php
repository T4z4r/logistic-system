<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\UserRoleController;



    Route::any('admin-mode', [SettingController::class, 'mode'])->name('erp.change-mode');


        // start of Roles Routes
    Route::get('/roles', [RoleController::class, 'index'])->name('roles.index');
    Route::get('/roles/create', [RoleController::class, 'create'])->name('roles.create');
    Route::post('/roles/store', [RoleController::class, 'store'])->name('roles.store');
    Route::get('/roles/{id}/edit', [RoleController::class, 'edit'])->name('roles.edit');
    Route::get('/roles/{id}/edit', [RoleController::class, 'edit'])->name('roles.edit');
    Route::put('/roles/update/{id}', [RoleController::class, 'update'])->name('roles.update');
    Route::delete('/roles/destroy/{id}', [RoleController::class, 'destroy'])->name('roles.destroy');


     // Users Routes
    Route::get('/users-roles', [UserRoleController::class, 'index'])->name('users-roles.index');
    Route::get('/users-roles/create', [UserRoleController::class, 'create'])->name('users-roles.create');
    Route::post('/users-roles/store', [UserRoleController::class, 'store'])->name('users-roles.store');
    Route::get('/users-roles/{id}/show', [UserRoleController::class, 'show'])->name('users-roles.show');
    Route::get('/users-roles/{id}/edit', [UserRoleController::class, 'edit'])->name('users-roles.edit');
    Route::put('/users-roles/update/{id}', [UserRoleController::class, 'update'])->name('users-roles.update');
    Route::delete('/users-roles/destroy/{id}', [UserRoleController::class, 'destroy'])->name('users-roles.destroy');