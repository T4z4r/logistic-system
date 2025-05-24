<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TruckType;

class TruckTypeController extends Controller
{
    public function index()
    {
        $truck_types = TruckType::latest()->get();
        return view('truck_types.index', compact('truck_types'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'added_by' => 'required|exists:users,id',
        ]);

        TruckType::create($request->all());

        return back()->with('success', 'Truck type added successfully.');
    }

    public function update(Request $request, TruckType $truckType)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $truckType->update($request->only('name'));

        return back()->with('success', 'Truck type updated successfully.');
    }

    public function destroy(TruckType $truckType)
    {
        $truckType->delete();

        return back()->with('success', 'Truck type deleted.');
    }
}
