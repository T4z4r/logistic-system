<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Position;
use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class DriverController extends Controller
{
    public function index()
    {

        $departments = Department::all();
        $managers = User::where('status', 1)->get();
        $drivers = User::with(['department', 'lineManager', 'position'])
            ->where('position_id', 1)
            ->latest()
            ->paginate(10);
        return view('drivers.index', compact('drivers', 'departments', 'managers'));
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
            'name' => 'required|string|max:255|regex:/^[a-zA-Z\s]+$/', // Allow only letters and spaces
            'department_id' => 'nullable|exists:departments,id',
            'line_manager_id' => 'nullable|exists:users,id',
            'status' => 'required|boolean',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Generate email from name
        $name = trim($request->name);
        $email = strtolower(str_replace(' ', '.', $name)) . '@sudenergy.co.tz';

        // Check if email is unique
        $validator = Validator::make(['email' => $email], [
            'email' => 'required|email|unique:users,email',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors(['name' => 'This name generates an email that already exists'])
                ->withInput();
        }

        User::create([
            'name' => $name,
            'email' => $email,
            'password' => Hash::make('Password123'),
            'department_id' => $request->department_id,
            'line_manager_id' => $request->line_manager_id,
            'position_id' => 1, // Fixed to Driver position
            'status' => $request->status,
            'created_by' => Auth::user()->id,
        ]);

        return redirect()->route('drivers.list')->with('success', 'Driver created successfully.');
    }
    public function edit($id)
    {
        $driver = User::where('position_id', 1)->findOrFail($id);
        $departments = Department::all();
        $managers = User::where('status', 1)->where('id', '!=', $driver->id)->get();
        $users = User::where('status', 1)->get(); // For created_by
        return view('drivers.edit', compact('driver', 'departments', 'managers', 'users'));
    }

    public function show($id)
    {

        $driver = User::where('position_id', 1)->findOrFail($id);
        $departments = Department::all();
        $managers = User::where('status', 1)->where('id', '!=', $driver->id)->get();
        $users = User::where('status', 1)->get(); // For created_by
        return view('drivers.show', compact('driver', 'departments', 'managers', 'users'));
    }

    public function update(Request $request, $id)
    {
        $driver = User::where('position_id', 1)->findOrFail($id);

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|regex:/^[a-zA-Z\s]+$/', // Allow only letters and spaces
            'department_id' => 'nullable|exists:departments,id',
            'line_manager_id' => 'nullable|exists:users,id',
            'status' => 'required|boolean',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Generate email from name
        $name = trim($request->name);
        $email = strtolower(str_replace(' ', '.', $name)) . '@sudenergy.co.tz';

        // Check if email is unique (excluding current driver)
        $emailValidator = Validator::make(['email' => $email], [
            'email' => 'required|email|unique:users,email,' . $driver->id,
        ]);

        if ($emailValidator->fails()) {
            return redirect()->back()
                ->withErrors(['name' => 'This name generates an email that already exists'])
                ->withInput();
        }

        $driver->update([
            'name' => $name,
            'email' => $email,
            'department_id' => $request->department_id,
            'line_manager_id' => $request->line_manager_id,
            // 'position_id' => 5, // Ensure remains Driver
            'status' => $request->status,
        ]);

        return redirect()->route('drivers.list')->with('success', 'Driver updated successfully.');
    }
    public function destroy($id)
    {
        $driver = User::where('position_id', 1)->findOrFail($id);
        $driver->delete();
        return redirect()->route('drivers.list')->with('success', 'Driver deleted successfully.');
    }
}
