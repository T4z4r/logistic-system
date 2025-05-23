<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TripController;
use App\Http\Controllers\TrailerController;
use App\Http\Controllers\AllocationController;


Route::get('/allocations', [AllocationController::class, 'index'])->name('flex.allocation-requests');
 // Start Trips Management Routes
    Route::prefix('trips/')->controller(TripController::class)->group(function () {
        Route::any('request-change', 'truck_change_request')->name('flex.change-request');
        Route::any('truck-request', 'truck_requests')->name('flex.truck-request');
        Route::any('approve-change', 'approve_change')->name('flex.approve-change');
        Route::any('disapprove-change', 'disapprove_change')->name('flex.disapprove-change');
        Route::any('change-driver', 'change_driver')->name('flex.change_driver');
        Route::any('change-trailer', 'change_trailer')->name('flex.change_trailer');

        Route::any('add_plan', 'add_plan')->name('flex.add_plan');
        Route::any('edit_plan', 'edit_plan')->name('flex.edit_plan');
        Route::any('remove-truck/{id}', 'remove_truck')->name('flex.remove-truck');
        Route::any('replace-truck', 'replace_truck')->name('flex.replace-truck');

        Route::any('link-trip', 'link_trip')->name('flex.link-trip');
        Route::any('unlink-trip/{id}', 'unlink_trip')->name('flex.unlink-trip');

        // For Trip Truck Remarks

        Route::any('save-truck-remark', 'save_truck_remark')->name('flex.save-truck-remark');
        Route::any('update-truck-remark', 'update_truck_remark')->name('flex.update-truck-remark');
        Route::any('delete-truck-remark/{id}', 'delete_truck_remark')->name('flex.delete-truck-remark');

        Route::any('trip-requests', 'goingload_requests')->name('flex.trip-requests');
        Route::any('goingload-trip/{id}', 'goingload_trip')->name('flex.going-trip');
        Route::any('delete-trip/{id}', 'delete_trip')->name('flex.delete-trip');

        Route::any('print-goingload-trip/{id}', 'print_goingload_trip')->name('flex.print-going-trip');
        Route::any('resubmit-trip/{id}', 'resubmit_trip')->name('flex.resubmit-trip');

        Route::any('backload-requests', 'backload_requests')->name('flex.backload-requests');
        Route::any('backload-trip/{id}', 'backload_trip')->name('flex.backload-trip');
    });