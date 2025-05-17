<?php

namespace App\Http\Controllers;

use App\Models\DriverAssignment;
use App\Models\Driver;
use App\Models\Truck;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class DriverAssignmentController extends Controller
{
    public function index()
    {
        $assignments = DriverAssignment::with(['driver', 'truck', 'assignedBy'])->paginate(10);
        return view('driver-assignments.index', compact('assignments'));
    }

    public function active()
    {
        $assignments = DriverAssignment::with(['driver', 'truck', 'assignedBy'])
            ->where('status', 1)
            ->paginate(10);
        return view('driver-assignments.index', compact('assignments'));
    }

    public function inactive()
    {
        $assignments = DriverAssignment::with(['driver', 'truck', 'assignedBy'])
            ->where('status', 0)
            ->paginate(10);
        return view('driver-assignments.index', compact('assignments'));
    }

    public function create()
    {
        $drivers = User::where('status', 1)->get();
        $trucks = Truck::where('status', 1)->get();
        $users = User::all();
        return view('driver-assignments.create', compact('drivers', 'trucks', 'users'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'driver_id' => 'nullable|exists:drivers,id',
            'truck_id' => 'nullable|exists:trucks,id',
            'status' => 'required|boolean',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        DriverAssignment::create(array_merge(
            $request->only(['driver_id', 'truck_id', 'status']),
            ['assigned_by' => Auth::id()]
        ));

        return redirect()->route('driver-assignments.list')->with('success', 'Driver assignment created successfully.');
    }

    public function edit($id)
    {
        $assignment = DriverAssignment::findOrFail($id);
        $drivers = User::where('status', 1)->get();
        $trucks = Truck::where('status', 1)->get();
        $users = User::all();
        return view('driver-assignments.edit', compact('assignment', 'drivers', 'trucks', 'users'));
    }

    public function update(Request $request, $id)
    {
        $assignment = DriverAssignment::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'driver_id' => 'nullable|exists:drivers,id',
            'truck_id' => 'nullable|exists:trucks,id',
            'status' => 'required|boolean',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $assignment->update(array_merge(
            $request->only(['driver_id', 'truck_id', 'status']),
            ['assigned_by' => Auth::id()]
        ));

        return redirect()->route('driver-assignments.list')->with('success', 'Driver assignment updated successfully.');
    }

    public function destroy($id)
    {
        $assignment = DriverAssignment::findOrFail($id);
        $assignment->delete();
        return redirect()->route('driver-assignments.list')->with('success', 'Driver assignment deleted successfully.');
    }



    public function assignDriver(Request $request, $truck_id)
    {
        $truck = Truck::findOrFail($truck_id);

        $validator = Validator::make($request->all(), [
            'driver_id' => 'required|exists:users,id',
        ]);

        if ($validator->fails()) {
            return redirect()->route('trucks.list')
                ->withErrors($validator)
                ->with('modal', 'assign-driver-' . $truck_id);
        }

        // Check for existing active assignment for the truck
        $existingAssignment = DriverAssignment::where('truck_id', $truck_id)
            ->where('status', 1)
            ->first();

        if ($existingAssignment) {
            // Update existing assignment
            $existingAssignment->update([
                'driver_id' => $request->driver_id,
                'assigned_by' => Auth::id(),
                'status' => 1,
            ]);
        } else {
            // Create new assignment
            DriverAssignment::create([
                'driver_id' => $request->driver_id,
                'truck_id' => $truck_id,
                'assigned_by' => Auth::id(),
                'status' => 1,
            ]);
        }

        return redirect()->route('trucks.list')->with('success', 'Driver assigned successfully.');
    }

    public function deassignDriver($truck_id)
    {
        $truck = Truck::findOrFail($truck_id);

        $assignment = DriverAssignment::where('truck_id', $truck_id)
            ->where('status', 1)
            ->first();

        if (!$assignment) {
            return redirect()->route('trucks.list')->with('error', 'No active driver assignment found for this truck.');
        }

        // Set status to inactive (or delete if preferred)
        $assignment->update([
            'status' => 0,
            'assigned_by' => Auth::id(),
        ]);

        return redirect()->route('trucks.list')->with('success', 'Driver deassigned successfully.');
    }
}