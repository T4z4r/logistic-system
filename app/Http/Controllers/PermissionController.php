<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Validator;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class PermissionController extends Controller 
{

    // public static function middleware(): array
    // {
    //     return [
    //         // examples with aliases, pipe-separated names, guards, etc:
    //         new Middleware('permission:add permission', only: ['index']),
    //         new Middleware('permission:view permissions', only: ['index']),
    //         new Middleware('permission:edit permission', only: ['index']),
    //         new Middleware('permission:delete permission', only: ['index']),
    //     ];
    // }


    // This method will show permissions page
    public function index()
    {
        $data['permissions'] = Permission::latest()->paginate(10);
        $data['count'] = 1;
        return view('permissions.index', $data);
    }

    // This method will show create permission page
    public function create()
    {
        return view('permissions.create');
    }

    // This method will insert permission in DB
    public function store(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'name' => 'required|unique:permissions|min:3'
            ]
        );

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        } else {
            Permission::create(['name' => $request->name]);

            return redirect()->route('permissions.index')->with('success', 'Permissions Added Successifully !');
        }
    }

    // This method will show edit permission page
    public function edit($id)
    {
        $data['permission'] = Permission::findOrFail($id);

        return view('permissions.edit', $data);
    }

    // This method will update permission in DB
    public function update($id,Request $request)
    {
        $validator = Validator::make(
            request()->all(),
            [
                'name' => 'required|min:3|unique:permissions,name,' . $id
            ]
        );

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        } else {
            $permission = Permission::findOrFail($id);
            $permission->name = request('name');
            $permission->update();

            return redirect()->route('permissions.index')->with('success', 'Permission Updated Successfully!');
        }
    }

    // This method will delete permission in DB
    public function destroy($id,Request $request)
    {
        $permission = Permission::findOrFail($id);

        // Check if permission is assigned to any role
        if ($permission->roles->count() > 0) {
            return redirect()->route('permissions.index')->with('error', 'Permission is assigned to a role and can not be deleted!');
        }

        // Delete permission from DB
        $permission->delete();

        return redirect()->route('permissions.index')->with('success', 'Permission Deleted Successfully!');
    }
}
