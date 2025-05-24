<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AllocationController;

// Allocation Routes
Route::get('/allocations', [AllocationController::class, 'index'])->name('allocations.list');
Route::get('/allocations/active', [AllocationController::class, 'active'])->name('allocations.active');
Route::get('/allocations/inactive', [AllocationController::class, 'inactive'])->name('allocations.inactive');
Route::get('/allocations/create', [AllocationController::class, 'create'])->name('allocations.create');
Route::post('/allocations', [AllocationController::class, 'store'])->name('allocations.store');
Route::get('/allocations/{id}/edit', [AllocationController::class, 'edit'])->name('allocations.edit');
Route::put('/allocations/{id}', [AllocationController::class, 'update'])->name('allocations.update');
Route::delete('/allocations/{id}', [AllocationController::class, 'destroy'])->name('allocations.delete');


  Route::prefix('trips/')->controller(AllocationController::class)->middleware('auth')->group(function () {
        Route::any('print-all-trips', 'print_allocations')->name('flex.print-allocations');
        Route::any('add-allocation', 'add_allocation')->name('flex.add-allocation');
        Route::any('save-request', 'save_allocation')->name('flex.save-allocation');
        Route::any('truck-allocation/{id}', 'truck_allocation')->name('flex.truck-allocation');
        Route::any('print-allocation/{id}', 'print_allocation')->name('flex.print-allocation');

        Route::any('add_truck', 'add_truck')->name('flex.add_truck');
        Route::any('/truck-cost/{id}', 'truck_cost')->name('flex.truck_cost');
        Route::any('add-truck-cost', 'add_truck_cost')->name('flex.add-truck-cost');
        Route::any('update-truck-cost', 'update_truck_cost')->name('flex.update-truck-cost');
        Route::any('update-request', 'update_allocation')->name('flex.update-allocation');
        Route::any('submit-allocation/{id}', 'submit_allocation')->name('flex.submit-allocation');
        Route::any('delete-allocation/{id}', 'delete_allocation')->name('flex.deleteAllocation');
        Route::any('cancel-trip/{id}', 'cancel_trip')->name('flex.cancel-trip');
        Route::any('revoke-allocation/{id}', 'revoke_allocation')->name('flex.revoke-allocation');
        Route::any('renotify-allocation/{id}', 'renotify_allocation')->name('flex.renotify-allocation');

        Route::any('change-allocation-route', 'change_allocation_route')->name('flex.change-allocation-route');


        Route::any('revoke-trip/{id}', 'revoke_trip')->name('flex.revoke-trip');
        // start of approvals route
        Route::any('approve-allocation', 'approveAllocation')->name('flex.approveAllocation');
        Route::any('disapprove-allocation', 'disapproveAllocation')->name('flex.disapproveAllocation');
        Route::any('/request-trip/{id}', 'request_trip')->name('flex.request-trip');
        Route::any('add_bulk_trucks', 'add_bulk_trucks')->name('flex.add_bulk_trucks');
        Route::any('remove_bulk_trucks', 'remove_bulk_trucks')->name('flex.remove_bulk_trucks');

        Route::any('mobilize_bulk_trucks', 'mobilize_bulk_trucks')->name('flex.mobilize_bulk_trucks');
        Route::any('demobilize_bulk_trucks', 'demobilize_bulk_trucks')->name('flex.demobilize_bulk_trucks');

        Route::any('demobilize-truck/{id}', 'demobilize_truck')->name('flex.demobilize-truck');
    });