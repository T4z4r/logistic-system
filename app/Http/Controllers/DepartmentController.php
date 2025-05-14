<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\User;
use Illuminate\Http\Request;

class DepartmentController extends Controller
{
    public function index()
    {
        $departments = Department::with('head')->get();
        $users = User::all();
        return view('departments.index', compact('departments', 'users'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'hod' => 'nullable|exists:users,id',
            'status' => 'required|in:active,inactive',
        ]);

        Department::create([
            'name' => $request->name,
            'status' => $request->status,
            'hod' => $request->hod,
            'created_by' => auth()->id(),
        ]);

        return redirect()->route('departments.index')->with('success', 'Department created successfully.');
    }

    public function edit($id)
    {
        return response()->json(Department::findOrFail($id));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'status' => 'required|in:active,inactive',
            'hod' => 'nullable|exists:users,id',
        ]);

        Department::findOrFail($id)->update([
            'name' => $request->name,
            'status' => $request->status,
            'hod' => $request->hod,
        ]);

        return redirect()->route('departments.index')->with('success', 'Department updated successfully.');
    }

    public function destroy($id)
    {
        Department::findOrFail($id)->delete();
        return redirect()->route('departments.index')->with('success', 'Department deleted successfully.');
    }
}
