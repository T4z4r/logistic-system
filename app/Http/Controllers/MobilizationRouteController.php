<?php

namespace App\Http\Controllers;

use App\Models\MobilizationRoute;
use Illuminate\Http\Request;

class MobilizationRouteController extends Controller
{
    public function index()
    {
        $routes = MobilizationRoute::all();
        return view('mobilization_routes.index', compact('routes'));
    }

    public function create()
    {
        return view('mobilization_routes.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'start_point' => 'required|string|max:255',
            'end_point' => 'required|string|max:255',
        ]);

        MobilizationRoute::create($request->all());

        return redirect()->route('mobilization_routes.index')->with('success', 'Route created successfully.');
    }

    public function edit(MobilizationRoute $mobilization_route)
    {
        return view('mobilization_routes.edit', compact('mobilization_route'));
    }

    public function update(Request $request, MobilizationRoute $mobilization_route)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'start_point' => 'required|string|max:255',
            'end_point' => 'required|string|max:255',
        ]);

        $mobilization_route->update($request->all());

        return redirect()->route('mobilization_routes.index')->with('success', 'Route updated successfully.');
    }

    public function destroy(MobilizationRoute $mobilization_route)
    {
        $mobilization_route->delete();

        return redirect()->route('mobilization_routes.index')->with('success', 'Route deleted successfully.');
    }
}
