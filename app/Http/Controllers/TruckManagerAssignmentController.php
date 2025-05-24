<?php

namespace App\Http\Controllers;

use App\Models\TruckManagerAssignment;
use Illuminate\Http\Request;

class TruckManagerAssignmentController extends Controller
{
    public function index()
    {
        $assignments = TruckManagerAssignment::all();
        return view('truck_manager_assignments.index', compact('assignments'));
    }

    public function create()
    {
        return view('truck_manager_assignments.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'manager_id' => 'nullable|integer',
            'truck_id' => 'nullable|integer',
            'assigned_by' => 'nullable|integer',
            'status' => 'required|boolean',
        ]);

        TruckManagerAssignment::create($request->all());

        return redirect()->route('truck_manager_assignments.index')->with('success', 'Assignment created successfully.');
    }

    public function edit(TruckManagerAssignment $truck_manager_assignment)
    {
        return view('truck_manager_assignments.edit', compact('truck_manager_assignment'));
    }

    public function update(Request $request, TruckManagerAssignment $truck_manager_assignment)
    {
        $request->validate([
            'manager_id' => 'nullable|integer',
            'truck_id' => 'nullable|integer',
            'assigned_by' => 'nullable|integer',
            'status' => 'required|boolean',
        ]);

        $truck_manager_assignment->update($request->all());

        return redirect()->route('truck_manager_assignments.index')->with('success', 'Assignment updated successfully.');
    }

    public function destroy(TruckManagerAssignment $truck_manager_assignment)
    {
        $truck_manager_assignment->delete();

        return redirect()->route('truck_manager_assignments.index')->with('success', 'Assignment deleted successfully.');
    }
}
