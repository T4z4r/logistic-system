<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DepartmentController extends Controller
{
    public function index()
    {
        $departments = Department::with('headOfDepartment', 'creator')->get();
        $users = User::all(); // For dropdowns
        return view('departments.index', compact('departments', 'users'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:departments,name',
            'status' => 'required|in:active,inactive',
            'hod' => 'nullable|exists:users,id'
        ]);

        Department::create([
            'name' => $request->name,
            'status' => $request->status,
            'hod' => $request->hod,
            'created_by' => Auth::id(),
        ]);

        return redirect()->back()->with('success', 'Department created successfully.');
    }

    public function update(Request $request, Department $department)
    {
        $request->validate([
            'name' => 'required|unique:departments,name,' . $department->id,
            'status' => 'required|in:active,inactive',
            'hod' => 'nullable|exists:users,id'
        ]);

        $department->update([
            'name' => $request->name,
            'status' => $request->status,
            'hod' => $request->hod,
        ]);

        return redirect()->back()->with('success', 'Department updated successfully.');
    }

    public function destroy(Department $department)
    {
        $department->delete();
        return redirect()->back()->with('success', 'Department deleted.');
    }
}
