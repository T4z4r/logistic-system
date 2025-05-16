<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Department;
use App\Models\Position;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function index()
    {
        $users = User::with(['department', 'lineManager', 'position'])->paginate(10);
        return view('users.index', compact('users'));
    }

    public function active()
    {
        $users = User::with(['department', 'lineManager', 'position'])
            ->where('status', 1)
            ->paginate(10);
        return view('users.active', compact('users'));
    }

    public function inactive()
    {
        $users = User::with(['department', 'lineManager', 'position'])
            ->where('status', 0)
            ->paginate(10);
        return view('users.inactive', compact('users'));
    }

    public function create()
    {
        $departments = Department::all();
        $positions = Position::all();
        $managers = User::where('status', 1)->get();
        return view('users.create', compact('departments', 'positions', 'managers'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'department_id' => 'nullable|exists:departments,id',
            'line_manager_id' => 'nullable|exists:users,id',
            'position_id' => 'nullable|exists:positions,id',
            'status' => 'required|boolean',
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
            'position_id' => $request->position_id,
            'status' => $request->status,
        ]);

        return redirect()->route('users.index')->with('success', 'User created successfully.');
    }

    public function edit( $id)
    {
        $user=User::where('id',$id)->first();
        $departments = Department::all();
        $positions = Position::all();
        $managers = User::where('status', 1)->where('id', '!=', $user->id)->get();
        return view('users.edit', compact('user', 'departments', 'positions', 'managers'));
    }

    public function update(Request $request,  $id)
    {
        $user=User::where('id',$id)->first();
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8|confirmed',
            'department_id' => 'nullable|exists:departments,id',
            'line_manager_id' => 'nullable|exists:users,id',
            'position_id' => 'nullable|exists:positions,id',
            'status' => 'required|boolean',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'department_id' => $request->department_id,
            'line_manager_id' => $request->line_manager_id,
            'position_id' => $request->position_id,
            'status' => $request->status,
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return redirect()->route('users.index')->with('success', 'User updated successfully.');
    }

    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('users.index')->with('success', 'User deleted successfully.');
    }
}
