<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Route;
use App\Models\RouteCost;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RouteController extends Controller
{
    public function index()
    {
        $routes = Route::with('createdBy')->paginate(10);
        return view('routes.index', compact('routes'));
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
        $route = Route::findOrFail($id);
        return view('routes.show', compact('route'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:routes',
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

        Route::create($request->only([
            'name',
            'start_point',
            'destination',
            'estimated_distance',
            'estimated_days',
            'status',
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
        $route = Route::findOrFail($id);
        $route->delete();
        return redirect()->route('routes.list')->with('success', 'Route deleted successfully.');
    }
}
