<?php

namespace App\Http\Controllers;

use App\Models\TruckChangeRequest;
use App\Models\TruckAllocation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TruckChangeRequestController extends Controller
{
    public function index()
    {
        $requests = TruckChangeRequest::with(['allocation', 'requester'])->get();
        return view('truck_change_requests.index', compact('requests'));
    }

    public function create()
    {
        $allocations = TruckAllocation::all();
        return view('truck_change_requests.create', compact('allocations'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'allocation_id' => 'required|exists:truck_allocations,id',
            'reason' => 'required',
            'status' => 'required|integer',
        ]);

        TruckChangeRequest::create([
            'allocation_id' => $request->allocation_id,
            'reason' => $request->reason,
            'requested_by' => Auth::id(),
            'status' => $request->status,
        ]);

        return redirect()->route('truck-change-requests.index')->with('success', 'Change request created.');
    }

    public function edit($id)
    {
        $request = TruckChangeRequest::findOrFail($id);
        $allocations = TruckAllocation::all();
        return view('truck_change_requests.edit', compact('request', 'allocations'));
    }

    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'allocation_id' => 'required|exists:truck_allocations,id',
            'reason' => 'required',
            'status' => 'required|integer',
            'approval_status' => 'required|integer',
        ]);

        TruckChangeRequest::findOrFail($id)->update($data);

        return redirect()->route('truck-change-requests.index')->with('success', 'Change request updated.');
    }

    public function destroy($id)
    {
        TruckChangeRequest::destroy($id);
        return redirect()->route('truck-change-requests.index')->with('success', 'Change request deleted.');
    }
}
