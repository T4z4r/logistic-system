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


// For Finance trips routes
// Start of Trip Payments Routes
Route::prefix('finance/')->controller(TripPaymentController::class)->group(function () {
    // Start of Trip Invoiceds
    Route::any('all-trips', 'all_trips')->name('finance.all-trips');
    Route::get('invoice-tabs/{tab}', 'tab_index')->name('trip_invoices.tab_index');
    Route::any('trip-detail/{id}', 'trip_detail')->name('finance.trip-detail');
    Route::any('create-invoice/{id}', 'create_invoice')->name('finance.create-invoice');
    Route::any('save-invoice', 'save_invoice')->name('finance.save-invoice');
    Route::any('edit-invoice/{id}', 'edit_invoice')->name('finance.edit-invoice');
    Route::any('edit-other-invoice/{id}', 'edit_other_invoice')->name('finance.edit-other-invoice');
    Route::any('update-invoice', 'update_invoice')->name('finance.update-invoice');
    Route::any('delete-invoice/{id}', 'delete_invoice')->name('finance.delete-invoice');
    Route::any('correct-invoice/{id}', 'correct_invoice')->name('finance.correct-invoice');
    Route::any('view-invoice/{id}', 'view_invoice')->name('finance.view-invoice');
    Route::any('view-other-invoice/{id}', 'view_other_invoice')->name('finance.view-other-invoice');
    Route::any('print-invoice/{id}', 'print_invoice')->name('finance.print-invoice');
    Route::any('print-other-invoice/{id}', 'print_other_invoice')->name('finance.print-other-invoice');
    Route::any('submit-invoice/{id}', 'submit_invoice')->name('finance.submit-invoice');
    Route::any('approve-invoice', 'approve_invoice')->name('finance.approve-invoice');
    Route::any('disapprove-invoice', 'disapprove_invoice')->name('finance.disapprove-invoice');
    Route::any('delete-invoiced_truck/{id}', 'delete_invoiced_truck')->name('finance.delete-invoiced-truck');
    Route::any('add-truck', 'add_invoiced_truck')->name('finance.add_truck');
    Route::any('add-truck-income', 'add_invoiced_truck_income')->name('finance.add_truck_income');
    Route::any('delete-invoiced-truck-income/{id}', 'delete_invoiced_truck_income')->name('finance.delete-invoiced-truck-income');


    // For Customer Invoice
    Route::any('customer-invoices', 'customer_invoice')->name('finance.customer-invoice');
    Route::any('create-invoice', 'create_customer_invoice')->name('finance.create-customer-invoice');
});

// Start of Accounting Routes
Route::any('/finance/allocation_cost_payment', [TripCostPaymentController::class, 'allocation_cost_payment'])->name('flex.allocation_cost_payment');
Route::any('/finance/bulk_truck_cost_payment', [TripCostPaymentController::class, 'bulk_truck_cost_payment'])->name('flex.bulk_truck_cost_payment');
Route::any('/finance/bulk_allocation_cost_payment', [TripCostPaymentController::class, 'bulk_allocation_cost_payment'])->name('flex.bulk_allocation_cost_payment');
Route::any('/finance/advance_allocation_cost_payment', [TripCostPaymentController::class, 'advance_payment'])->name('flex.advance_cost_payment');
Route::any('/finance/truck_cost_payment', [TripCostPaymentController::class, 'truck_cost_payment'])->name('flex.truck_cost_payment');