<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Validator;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Routing\Controllers\HasMiddleware;


class RoleController extends Controller 
{

    // public static function middleware(): array
    // {
    //     return [
    //         // examples with aliases, pipe-separated names, guards, etc:
    //         new Middleware('permission:view roles', only: ['index']),
    //         new Middleware('permission:add roles', only: ['index']),
    //         new Middleware('permission:edit roles', only: ['index']),
    //         new Middleware('permission:delete roles', only: ['index']),
    //     ];
    // }

    // This method will show roles page
    public function index()
    {

        $data['roles'] = Role::orderBy('name', 'Asc')->paginate(10);
        $data['count'] = 1;
        return view('roles.list', $data);
    }

    // This method will show create role page
    public function create()
    {

        $data['permissions'] = Permission::latest()->get();
        return view('roles.create', $data);
    }

    // This method will store a new role in DB
    public function store(Request $request)
    {

        $validator = Validator::make(
            $request->all(),
            [
                'name' => 'required|unique:roles|min:3'
            ]
        );

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        } else {

            $role = Role::create(['name' => $request->name]);

            if (!empty($request->permissions)) {
                foreach ($request->permissions as $name) {
                    $role->givePermissionTo($name);
                }
            }

            return redirect()->route('roles.index')->with('success', 'Role Added Successifully !');
        }
    }

    // This method will show  edit role page
    public function edit($id)
    {

        $role = Role::findOrFail($id);
        $data['role'] = $role;
        $data['hasPermissions'] = $role->permissions->pluck('name');
        $data['permissions'] = Permission::latest()->get();


        return view('roles.edit', $data);
    }

    // This method will update a role in DB
    public function update($id, Request $request)
    {

        $role = Role::findOrFail($id);
        $validator = Validator::make(
            $request->all(),
            [
                'name' => 'required|unique:roles,name,' . $id . '|min:3'
            ]
        );
        if ($validator->passes()) {
            $role->name = $request->name;
            $role->save();

            if (!empty($request->permissions)) {
                $role->syncPermissions($request->permissions);
            } else {
                $role->syncPermissions([]);
            }

            return redirect()->route('roles.index')->with('success', 'Role Updated Successifully !');
        } else {
            return redirect()->back()->withErrors($validator)->withInput();
        }
    }

    // This method will delete a role from DB
    public function destroy($id)
    {
        $role = Role::findOrFail($id);

        // Check if role is assigned to any role

        if ($role == null) {
            session()->flash('error', 'Role not found');

            return response()->json(
                [
                    'message' => 'Role not found!',
                    'status' => true
                ]
            );
        }
        // Delete role from DB
        $role->delete();

        session()->flash('success', 'Role deleted successfully');

        return redirect()->route('roles.index')->with('success', 'role Deleted Successfully!');
    }
}
