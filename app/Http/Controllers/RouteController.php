<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Route;
use App\Models\Currency;
use App\Models\FuelCost;
use App\Models\RouteCost;
use App\Models\CommonCost;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class RouteController extends Controller
{


     public function index()
    {
        DB::beginTransaction(); // Start the transaction

        try {
            // Fetch active routes
            $data['active'] = Route::latest()->where('status', '1')->get();

            // Fetch inactive routes
            $data['inactive'] = Route::latest()->where('status', '0')->get();

            DB::commit(); // Commit the transaction if no exception occurs

            // Return the view with the fetched data
            return view('routes.index', $data);
        } catch (\Exception $e) {
            DB::rollBack(); // Rollback the transaction if an exception occurs

            // Log the error message
            Log::error('Error fetching routes: ' . $e->getMessage());

            // Return a response or redirect with an error message
            return response()->json([
                'status' => 500,
                'error' => 'An error occurred while fetching routes. Please try again later.'
            ]);
        }
    }

    public function active()
    {
        $routes = Route::with('createdBy')
            ->where('status', 1)
            ->paginate(10);
        return view('routes.active', compact('routes'));
    }

    public function inactive()
    {
        $routes = Route::with('createdBy')
            ->where('status', 0)
            ->paginate(10);
        return view('routes.inactive', compact('routes'));
    }

    public function create()
    {
        $users = User::where('status', 1)->get();
        return view('routes.create', compact('users'));
    }



     public function show($id)
    {
        $data['route'] = Route::find($id);
        $data['common'] = CommonCost::latest()->get();
        // $data['accounts'] = AccountingCodes::get();
        $data['costs'] = RouteCost::where('route_id', $id)->whereNot('status', -1)->latest()->where('quantity', NULL)->get();
        $data['fuels'] = FuelCost::latest()->get();
        $data['fuel_costs'] = RouteCost::where('route_id', $id)->whereNot('status', -1)->whereNot('quantity', NULL)->latest()->get();
        $data['currencies'] = Currency::latest()->get();
        $data['total_expenses'] = RouteCost::where('route_id', $id)->sum('real_amount');


        return view('routes.show', $data);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:routes',
            'start_point' => 'required|string|max:255',
            'destination' => 'required|string|max:255',
            'estimated_distance' => 'required|numeric|min:0',
            'estimated_days' => 'required|integer|min:1',
            // 'status' => 'required|boolean',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        $request['created_by'] = Auth::id();

        Route::create($request->only([
            'name',
            'start_point',
            'destination',
            'estimated_distance',
            'estimated_days',
            // 'status',
            'created_by',
        ]));

        return redirect()->route('routes.list')->with('success', 'Route created successfully.');
    }

    public function edit($id)
    {

        $route = Route::findOrFail($id);
        $users = User::where('status', 1)->get();
        return view('routes.edit', compact('route', 'users'));
    }

    public function update(Request $request, $id)
    {
        $route = Route::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:routes,name,' . $route->id,
            'start_point' => 'required|string|max:255',
            'destination' => 'required|string|max:255',
            'estimated_distance' => 'required|numeric|min:0',
            'estimated_days' => 'required|integer|min:1',
            'status' => 'required|boolean',
            'created_by' => 'required|exists:users,id',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $route->update($request->only([
            'name',
            'start_point',
            'destination',
            'estimated_distance',
            'estimated_days',
            'status',
            'created_by',
        ]));

        return redirect()->route('routes.list')->with('success', 'Route updated successfully.');
    }

    public function destroy($id)
    {
        DB::beginTransaction();

        try {
            $route = Route::where('id', $id)->first();

            if (!$route) {
                return redirect()->route('routes.list')->withErrors(['error' => 'Route not found.']);
            }

            $route->delete();

            DB::commit();

            return redirect()->route('routes.list')->with('success', 'Route deleted successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error deleting route: ' . $e->getMessage());
            return redirect()->route('routes.list')->withErrors(['error' => 'An error occurred while deleting the route. Please try again later.']);
        }
    }


        // For Saving Route Cost

    public function saveRouteCost(Request $request)
    {
        DB::beginTransaction(); // Start the transaction

        try {
            // Validate the request
            request()->validate(
                [
                    'amount' => 'required',
                    'route_id' => 'required',
                    'cost_id' => 'required',
                ]
            );

            // Create a new RouteCost instance
            $cost = new RouteCost();

            // If quantity is provided, fetch FuelCost
            if ($request->quantity != NULL) {
                $common = FuelCost::find($request->cost_id);
                if (!$common) {
                    return redirect()->back()->withErrors(['error' => 'Fuel cost not found.']);
                }
                $cost->vat = $common->vat;
                $cost->editable = $common->editable;
            } else {
                // If quantity is not provided, fetch CommonCost
                $common = CommonCost::find($request->cost_id);
                if (!$common) {
                    return redirect()->back()->withErrors(['error' => 'Common cost not found.']);
                }
                $cost->vat = $common->vat;
                $cost->editable = $common->editable;
                $cost->return = $common->return;
            }

            // Set the cost properties
            $cost->name = $common->name;
            $cost->cost_id = $request->cost_id;
            $cost->route_id = $request->route_id;
            $cost->account_code = $common->account?->code??0000;
            $cost->amount = $request->amount;
            $cost->type = $request->type;
            $cost->quantity = $request->quantity;
            $cost->editable = $request->editable == true ? '1' : '0';
            $cost->advancable = $request->advancable == true ? '1' : '0';
            $cost->currency_id = $request->currency_id;
            $cost->created_by = Auth::user()->id;

            // Get the currency and calculate the real amount
            $currency = Currency::find($request->currency_id);
            if (!$currency) {
                return redirect()->back()->withErrors(['error' => 'Currency not found.']);
            }
            $cost->real_amount = $currency->rate * $request->amount;
            $cost->rate = $currency->rate;

            // Save the cost to the database
            $cost->save();

            DB::commit(); // Commit the transaction

            // Success message
            $success = "Route Expense Was Added Successfully !";

            return redirect()->back()->with('success', $success);
        } catch (\Exception $e) {
            dd($e);
            DB::rollBack(); // Rollback the transaction in case of error

            // Log the exception for debugging purposes
            Log::error('Error saving route cost: ' . $e->getMessage());

            // Return the error message
            return redirect()->back()->withErrors(['error' => 'An error occurred while saving the route cost. Please try again later.']);
        }
    }


        public function deleteRouteCost($id)
    {
        DB::beginTransaction(); // Start the transaction

        try {
            // Find the RouteCost record
            $route = RouteCost::find($id);

            if (!$route) {
                return back()->withErrors(['error' => 'Route cost not found.']);
            }

            $routeId = $route->route_id;
            $route->status = -1;
            $route->update();

            // Delete the route cost
            // $route->delete();

            DB::commit(); // Commit the transaction

            $msg = "Route Cost Was Deleted Successfully !";
            return back()->with('success', $msg);

        } catch (\Exception $e) {
            DB::rollBack(); // Rollback the transaction in case of error

            // Log the exception for debugging purposes
            Log::error('Error deleting route cost: ' . $e->getMessage());

            // Return the error message
            return back()->withErrors(['error' => 'An error occurred while deleting the route cost. Please try again later.']);
        }
    }


     // For Activate Route
    public function activateRoute($id)
    {
        DB::beginTransaction(); // Start the transaction

        try {
            // Find the route by ID
            $route = Route::find($id);

            if (!$route) {
                return back()->withErrors(['error' => 'Route not found.']);
            }

            // Activate the route by setting the status to 1
            $route->status = 1;

            // Update the route in the database
            $route->update();

            DB::commit(); // Commit the transaction if everything goes smoothly

            // Success message
            $msg = "Route Was Activated Successfully!";
            return back()->with('success', $msg);
        } catch (\Exception $e) {
            DB::rollBack(); // Rollback the transaction in case of an error

            // Log the exception message for debugging purposes
            Log::error('Error activating route: ' . $e->getMessage());

            // Return an error message to the user
            return back()->withErrors(['error' => 'An error occurred while activating the route. Please try again later.']);
        }
    }

    // For Deactivate Route
    public function deactivateRoute($id)
    {
        DB::beginTransaction(); // Start the transaction

        try {
            // Find the route by ID
            $route = Route::find($id);

            if (!$route) {
                return back()->withErrors(['error' => 'Route not found.']);
            }

            // Deactivate the route by setting the status to 0
            $route->status = 0;

            // Update the route in the database
            $route->update();

            DB::commit(); // Commit the transaction if everything goes smoothly

            // Success message
            $msg1 = "Route Was Deactivated Successfully!";
            return back()->with('success', $msg1);
        } catch (\Exception $e) {
            DB::rollBack(); // Rollback the transaction in case of an error

            // Log the exception message for debugging purposes
            Log::error('Error deactivating route: ' . $e->getMessage());

            // Return an error message to the user
            return back()->withErrors(['error' => 'An error occurred while deactivating the route. Please try again later.']);
        }
    }

}
