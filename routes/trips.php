<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TripController;
use App\Http\Controllers\TrailerController;
use App\Http\Controllers\AllocationController;


// Route::get('/allocations', [AllocationController::class, 'index'])->name('flex.allocation-requests');
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


Route::any('/trips/allocation-quotation', [TripController::class, 'view_allocation'])->name('flex.allocation-quotation');
Route::any('/trips/trips', [TripController::class, 'management_trips'])->name('flex.trips'); //for management

Route::any('/trips/initiate-truck/{id}', [TripController::class, 'initiate_truck'])->name('flex.initiate-truck');
Route::any('/trips/approve_truck/{id}', [TripController::class, 'approve_truck'])->name('flex.approve-truck');
Route::any('/trips/disapprove_truck/{id}', [TripController::class, 'disapprove_truck'])->name('flex.disapprove-truck');

Route::any('/trips/submit-trip/{id}', [TripController::class, 'submit_trip'])->name('flex.submit-trip');
Route::any('/trips/finance-trips', [TripController::class, 'finance_trips'])->name('flex.finance_trips');
Route::any('/trips/procurement-trips', [TripController::class, 'procurement_trips'])->name('flex.procurement_trips');
Route::any('trips/procurement-detail/{id}', [TripController::class, 'procurement_detail'])->name('flex.procurementDetails');

Route::any('trips/trip-detail/{id}', [TripController::class, 'trip_detail'])->name('flex.tripDetails');
Route::any('trips/trip-truck/{id}', [TripController::class, 'trip_truck'])->name('flex.tripTruck');
Route::any('trips/procurement-trip-truck/{id}', [TripController::class, 'procurement_trip_truck'])->name('flex.procurementtripTruck');

Route::get('/trips/finance-trips/{tab}', [TripController::class, 'tab_index'])->name('finance_trips.tab_index');
Route::any('/trips/issue-cost/{id}', [TripController::class, 'issue_cost'])->name('flex.issue_cost');
Route::any('trips/bulk-truck-payments/{id}', [TripController::class, 'bulk_cost_payment'])->name('flex.bulk_cost_payment');


Route::any('/trips/submit-trip/{id}', [TripController::class, 'submit_trip'])->name('flex.submit-trip');
Route::any('/trips/delete-truck-cost/{id}', [TripController::class, 'delete_truck_cost'])->name('flex.delete_truck_cost');
Route::any('/trips/delete-cost/{id}', [TripController::class, 'delete_cost'])->name('flex.delete_cost');


Route::any('/trips/update-allocation-cost', [TripController::class, 'update_cost'])->name('flex.update-allocation-cost');
Route::any('/trips/add-allocation-cost', [TripController::class, 'add_cost'])->name('flex.add-allocation-cost');
