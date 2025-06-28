<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TripExpensePayment;

// For Trip Expense Payment
Route::any('/payables/trip_expenses', [TripExpensePayment::class, 'index'])->name('flex.all_trip_expenses');
Route::any('/payables/trip_expenses1', [TripExpensePayment::class, 'all_expenses'])->name('flex.all_trip_expenses1');

Route::any('/payables/show_trip_expense/{id}', [TripExpensePayment::class, 'show'])->name('flex.show_trip_expense');
Route::any('/payables/save_trip_expense', [TripExpensePayment::class, 'save'])->name('flex.save_trip_expense_payments');
Route::any('/payables/save_truck_expense', [TripExpensePayment::class, 'save_truck_expense'])->name('flex.save_truck_expense_payments');
Route::any('/payables/payment_history', [TripExpensePayment::class, 'payment_history'])->name('flex.payment_history');
Route::get('/payables/payment_history/{id}', [TripExpensePayment::class, 'view_payment_history'])->name('flex.view_payment_history');
Route::get('/payables/delete_payment_history/{id}', [TripExpensePayment::class, 'delete_allocation_history'])->name('flex.delete_payment_history');