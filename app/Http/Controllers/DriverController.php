<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\Position;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class DriverController extends Controller
{
    public function index()
    {
        $drivers = User::with(['department', 'lineManager', 'position'])
            ->where('position_id', 5)
            ->paginate(10);
        return view('drivers.index', compact('drivers'));
    }

    public function active()
    {
        $drivers = User::with(['department', 'lineManager', 'position'])
            ->where('position_id', 5)
            ->where('status', 1)
            ->paginate(10);
        return view('drivers.active', compact('drivers'));
    }

    public function inactive()
    {
        $drivers = User::with(['department', 'lineManager', 'position'])
            ->where('position_id', 5)
            ->where('status', 0)
            ->paginate(10);
        return view('drivers.inactive', compact('drivers'));
    }

    public function create()
    {
        $departments = Department::all();
        $managers = User::where('status', 1)->get();
        return view('drivers.create', compact('departments', 'managers'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'department_id' => 'nullable|exists:departments,id',
            'line_manager_id' => 'nullable|exists:users,id',
            'status' => 'required|boolean',
            'created_by' => 'required|exists:users,id',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'department_id' => $request->department_id,
            'line_manager_id' => $request->line_manager_id,
            'position_id' => 5, // Fixed to Driver position
            'status' => $request->status,
            'created_by' => $request->created_by,
        ]);

        return redirect()->route('drivers.list')->with('success', 'Driver created successfully.');
    }

    public function edit($id)
    {
        $driver = User::where('position_id', 5)->findOrFail($id);
        $departments = Department::all();
        $managers = User::where('status', 1)->where('id', '!=', $driver->id)->get();
        $users = User::where('status', 1)->get(); // For created_by
        return view('drivers.edit', compact('driver', 'departments', 'managers', 'users'));
    }

    public function update(Request $request, $id)
    {
        $driver = User::where('position_id', 5)->findOrFail($id);

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $driver->id,
            'password' => 'nullable|string|min:8|confirmed',
            'department_id' => 'nullable|exists:departments,id',
            'line_manager_id' => 'nullable|exists:users,id',
            'status' => 'required|boolean',
            'created_by' => 'required|exists:users,id',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'department_id' => $request->department_id,
            'line_manager_id' => $request->line_manager_id,
            'position_id' => 5, // Ensure remains Driver
            'status' => $request->status,
            'created_by' => $request->created_by,
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $driver->update($data);

        return redirect()->route('drivers.list')->with('success', 'Driver updated successfully.');
    }

    public function destroy($id)
    {
        $driver = User::where('position_id', 5)->findOrFail($id);
        $driver->delete();
        return redirect()->route('drivers.list')->with('success', 'Driver deleted successfully.');
    }
}
