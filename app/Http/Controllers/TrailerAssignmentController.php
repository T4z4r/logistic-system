<?php

namespace App\Http\Controllers;

use App\Models\TrailerAssignment;
use App\Models\Trailer;
use App\Models\Truck;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class TrailerAssignmentController extends Controller
{
    public function index()
    {
        $assignments = TrailerAssignment::with(['trailer', 'truck', 'assignedBy'])->paginate(10);
        return view('trailer-assignments.index', compact('assignments'));
    }

    public function active()
    {
        $assignments = TrailerAssignment::with(['trailer', 'truck', 'assignedBy'])
            ->where('status', 1)
            ->paginate(10);
        return view('trailer-assignments.index', compact('assignments'));
    }

    public function inactive()
    {
        $assignments = TrailerAssignment::with(['trailer', 'truck', 'assignedBy'])
            ->where('status', 0)
            ->paginate(10);
        return view('trailer-assignments.index', compact('assignments'));
    }

    public function create()
    {
        $trailers = Trailer::where('status', 1)->get();
        $trucks = Truck::where('status', 1)->get();
        $users = User::all();
        return view('trailer-assignments.create', compact('trailers', 'trucks', 'users'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'trailer_id' => 'required|exists:trailers,id',
            'truck_id' => 'required|exists:trucks,id',
            'status' => 'required|boolean',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        TrailerAssignment::create(array_merge(
            $request->only(['trailer_id', 'truck_id', 'status']),
            ['assigned_by' => Auth::id()]
        ));

        return redirect()->route('trailer-assignments.list')->with('success', 'Trailer assignment created successfully.');
    }

    public function edit($id)
    {
        $assignment = TrailerAssignment::findOrFail($id);
        $trailers = Trailer::where('status', 1)->get();
        $trucks = Truck::where('status', 1)->get();
        $users = User::all();
        return view('trailer-assignments.edit', compact('assignment', 'trailers', 'trucks', 'users'));
    }

    public function update(Request $request, $id)
    {
        $assignment = TrailerAssignment::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'trailer_id' => 'required|exists:trailers,id',
            'truck_id' => 'required|exists:trucks,id',
            'status' => 'required|boolean',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $assignment->update(array_merge(
            $request->only(['trailer_id', 'truck_id', 'status']),
            ['assigned_by' => Auth::id()]
        ));

        return redirect()->route('trailer-assignments.list')->with('success', 'Trailer assignment updated successfully.');
    }

    public function destroy($id)
    {
        $assignment = TrailerAssignment::findOrFail($id);
        $assignment->delete();
        return redirect()->route('trailer-assignments.list')->with('success', 'Trailer assignment deleted successfully.');
    }

    public function assignTruck(Request $request, $trailer_id)
    {
        $trailer = Trailer::findOrFail($trailer_id);

        $validator = Validator::make($request->all(), [
            'truck_id' => 'required|exists:trucks,id',
        ]);

        if ($validator->fails()) {
            return redirect()->route('trailers.list')
                ->withErrors($validator)
                ->with('modal', 'assign-truck-' . $trailer_id);
        }

        // Check for existing active assignment for the trailer
        $existingAssignment = TrailerAssignment::where('trailer_id', $trailer_id)
            ->where('status', 1)
            ->first();

        if ($existingAssignment) {
            // Update existing assignment
            $existingAssignment->update([
                'truck_id' => $request->truck_id,
                'assigned_by' => Auth::id(),
                'status' => 1,
            ]);
        } else {
            // Create new assignment
            TrailerAssignment::create([
                'trailer_id' => $trailer_id,
                'truck_id' => $request->truck_id,
                'assigned_by' => Auth::id(),
                'status' => 1,
            ]);
        }

        return redirect()->route('trailers.list')->with('success', 'Truck assigned successfully.');
    }

    public function deassignTruck($trailer_id)
    {
        $trailer = Trailer::findOrFail($trailer_id);

        $assignment = TrailerAssignment::where('trailer_id', $trailer_id)
            ->where('status', 1)
            ->first();

        if (!$assignment) {
            return redirect()->route('trailers.list')->with('error', 'No active truck assignment found for this trailer.');
        }

        // Set status to inactive
        $assignment->update([
            'status' => 0,
            'assigned_by' => Auth::id(),
        ]);

        return redirect()->route('trailers.list')->with('success', 'Truck deassigned successfully.');
    }
}