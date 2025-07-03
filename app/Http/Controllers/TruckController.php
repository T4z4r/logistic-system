<?php

namespace App\Http\Controllers;

use App\Models\Truck;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TruckController extends Controller
{
    public function index()
    {
        $trucks = Truck::with('addedBy')->latest()->paginate(10);
        return view('trucks.index', compact('trucks'));
    }

    public function active()
    {
        $trucks = Truck::with('addedBy')
            ->where('status', 1)
            ->paginate(10);
        return view('trucks.active', compact('trucks'));
    }

    public function inactive()
    {
        $trucks = Truck::with('addedBy')
            ->where('status', 0)
            ->paginate(10);
        return view('trucks.inactive', compact('trucks'));
    }

    public function trashed()
    {
        $trucks = Truck::onlyTrashed()->with('addedBy')->paginate(10);
        return view('trucks.trashed', compact('trucks'));
    }

    public function restore($id)
    {
        $truck = Truck::onlyTrashed()->findOrFail($id);
        $truck->restore();
        return redirect()->route('trucks.trashed')->with('success', 'Truck restored successfully.');
    }

    public function forceDelete($id)
    {
        $truck = Truck::onlyTrashed()->findOrFail($id);
        $truck->forceDelete();
        return redirect()->route('trucks.trashed')->with('success', 'Truck permanently deleted.');
    }

    public function create()
    {
        $users = User::where('status', 1)->get();
        return view('trucks.create', compact('users'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'purchase_date' => 'required',
            'plate_number' => 'required|string|max:255|unique:trucks',
            'body_type' => 'required|string|max:255',
            'truck_type' => 'required|string|max:255',
            'fuel_type' => 'required',
            'fuel_capacity' => 'required',
            'trailer_connection' => 'required|string|max:255',
            'trailer_capacity' => 'required|integer|min:0',
            'transmission' => 'required|string|max:255',
            'mileage' => 'required|string|max:255',
            'vehicle_model' => 'required|string|max:255',
            'manufacturer' => 'required|string|max:255',
            'year' => 'nullable|string|max:255',
            'color' => 'nullable|string|max:255',
            'engine_number' => 'nullable|string|max:255',
            'engine_capacity' => 'nullable|string|max:255',
            'gross_weight' => 'nullable|numeric|min:0',
            'location' => 'nullable|string|max:255',
            'status' => 'required|boolean',
            'amount' => 'nullable|numeric|min:0',
            'capacity' => 'nullable|numeric|min:0',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $request->merge(['added_by' => auth()->id()]);
        Truck::create($request->only([
            'purchase_date',
            'plate_number',
            'body_type',
            'truck_type',
            'fuel_type',
            'fuel_capacity',
            'trailer_connection',
            'trailer_capacity',
            'transmission',
            'mileage',
            'vehicle_model',
            'manufacturer',
            'year',
            'color',
            'engine_number',
            'engine_capacity',
            'gross_weight',
            'location',
            'status',
            'added_by',
            'amount',
            'capacity',
        ]));

        return redirect()->route('trucks.list')->with('success', 'Truck created successfully.');
    }

    public function edit($id)
    {
        $truck = Truck::findOrFail($id);
        $users = User::where('status', 1)->get();
        return view('trucks.edit', compact('truck', 'users'));
    }

    public function show($id)
    {
        $truck = Truck::findOrFail($id);
        $users = User::where('status', 1)->get();
        return view('trucks.show', compact('truck', 'users'));
    }

    public function update(Request $request, $id)
    {
        $truck = Truck::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'purchase_date' => 'required|date',
            'plate_number' => 'required',
            'body_type' => 'required|string|max:255',
            'truck_type' => 'required',
            'fuel_type' => 'required',
            'fuel_capacity' => 'required|string|max:255',
            'trailer_connection' => 'required|string|max:255',
            'trailer_capacity' => 'required|integer|min:0',
            'transmission' => 'required|string|max:255',
            'mileage' => 'required|string|max:255',
            'vehicle_model' => 'required|string|max:255',
            'manufacturer' => 'required|string|max:255',
            'year' => 'nullable|string|max:255',
            'color' => 'nullable|string|max:255',
            'engine_number' => 'nullable|string|max:255',
            'engine_capacity' => 'nullable|string|max:255',
            'gross_weight' => 'nullable|numeric|min:0',
            'location' => 'nullable|string|max:255',
            'status' => 'required|boolean',
            'amount' => 'nullable|numeric|min:0',
            'capacity' => 'nullable|numeric|min:0',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $truck->update($request->only([
            'purchase_date',
            'plate_number',
            'body_type',
            'truck_type',
            'fuel_type',
            'fuel_capacity',
            'trailer_connection',
            'trailer_capacity',
            'transmission',
            'mileage',
            'vehicle_model',
            'manufacturer',
            'year',
            'color',
            'engine_number',
            'engine_capacity',
            'gross_weight',
            'location',
            'status',
            'amount',
            'capacity',
        ]));

        return redirect()->route('trucks.list')->with('success', 'Truck updated successfully.');
    }

    public function destroy($id)
    {
        $truck = Truck::findOrFail($id);
        $truck->delete();
        return redirect()->route('trucks.list')->with('success', 'Truck deleted successfully.');
    }
}
