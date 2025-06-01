<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RouteController;


// Route Management Routes
Route::get('/routes', [RouteController::class, 'index'])->name('routes.list');
Route::get('/routes/active', [RouteController::class, 'active'])->name('routes.active');
Route::get('/routes/inactive', [RouteController::class, 'inactive'])->name('routes.inactive');
Route::get('/routes/create', [RouteController::class, 'create'])->name('routes.create');
Route::post('/routes', [RouteController::class, 'store'])->name('routes.store');
Route::get('/routes/{id}', [RouteController::class, 'show'])->name('routes.show');
Route::get('/routes/{id}/edit', [RouteController::class, 'edit'])->name('routes.edit');
Route::put('/routes/{id}', [RouteController::class, 'update'])->name('routes.update');
Route::delete('/routes/{id}', [RouteController::class, 'destroy'])->name('routes.delete');
Route::any('routes/activate-route/{id}', [RouteController::class, 'activateRoute'])->name('flex.activate-route');
Route::any('routes/deactivate-route/{id}', [RouteController::class, 'deactivateRoute'])->name('flex.deactivate-route');

Route::any('routes/print-route-costs/{id}', [RouteController::class, 'print_route_costs'])->name('flex.print-route-costs');
Route::any('routes/save-route-cost', [RouteController::class, 'saveRouteCost'])->name('flex.save-route-cost');
Route::any('routes/edit-route-cost/{id}', [RouteController::class, 'edit_route_cost'])->name('flex.edit-route-cost');
Route::any('routes/update-route-cost', [RouteController::class, 'updateRouteCost'])->name('flex.update-route-cost');
Route::any('routes/inactive-routes', [RouteController::class, 'inactive_routes'])->name('flex.inactive-routes');
Route::any('routes/delete-route-cost/{id}', [RouteController::class, 'deleteRouteCost'])->name('flex.delete-route-cost');
Route::get('routes/export', [RouteController::class, 'export'])->name('routes.export');
Route::post('routes/import', [RouteController::class, 'import'])->name('routes.import');
Route::get('routes/download-template', [RouteController::class, 'downloadTemplate'])->name('routes.downloadTemplate');
Route::get('route-costs/export', [RouteController::class, 'export'])->name('route-costs.export');
Route::post('route-costs/import', [RouteController::class, 'import'])->name('route-costs.import');
Route::get('route-costs/download-template', [RouteController::class, 'downloadTemplate'])->name('route-costs.downloadTemplate');
