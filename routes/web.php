<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PermissionController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Route::get('/', function () {
//     return view('welcome');
// });

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


// Example Routes
Route::view('/', 'landing');

require __DIR__ . '/auth.php';

Route::middleware('auth')->group(function () {
    Route::match(['get', 'post'], '/dashboard', function () {
    return view('dashboard');
})->name('dashboard');
Route::view('/pages/slick', 'pages.slick');
Route::view('/pages/datatables', 'pages.datatables');
Route::view('/pages/blank', 'pages.blank')->name('blank');
    // start of Permission Routes
    Route::get('permissions', [PermissionController::class, 'index'])->name('permissions.index');

    Route::get('/permissions/create', [PermissionController::class, 'create'])->name('permissions.create');
    Route::post('/permissions/store', [PermissionController::class, 'store'])->name('permissions.store');
    Route::get('/permissions/{id}/edit', [PermissionController::class, 'edit'])->name('permissions.edit');
    Route::put('/permissions/update/{id}', [PermissionController::class, 'update'])->name('permissions.update');
    Route::delete('/permissions/destroy/{id}', [PermissionController::class, 'destroy'])->name('permissions.destroy');

    require __DIR__ . '/users.php';
    require __DIR__ . '/customers.php';
    require __DIR__ . '/drivers.php';
    require __DIR__ . '/trucks.php';
    require __DIR__ . '/trailers.php';
    require __DIR__ . '/driver_assignment.php';
    require __DIR__ . '/trailer_assignment.php';
    require __DIR__ . '/routes.php';
    require __DIR__ . '/route_costs.php';
    require __DIR__ . '/mobilization_routes.php';
    require __DIR__ . '/common_costs.php';
    require __DIR__ . '/fuel_costs.php';
    require __DIR__ . '/currency.php';
    require __DIR__ . '/payment_method.php';
    require __DIR__ . '/payment_modes.php';
    require __DIR__ . '/cargo_nature.php';
    require __DIR__ . '/allocations.php';
    require __DIR__ . '/trips.php';
    require __DIR__ . '/department.php';
    require __DIR__ . '/position.php';
    require __DIR__ . '/approvals.php';
    require __DIR__ . '/settings.php';
});
