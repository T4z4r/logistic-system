<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Define permissions
        $permissions = [
            'manage users',
            'manage customers',
            'manage vehicles',
            'manage routes',
            'manage shipments',
            'track shipments',
            'assign vehicles',
            'view reports'
        ];

        // Create permissions
        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Define roles with their permissions
        $roles = [
            'admin' => $permissions,
            'dispatcher' => [
                'manage shipments',
                'assign vehicles',
                'track shipments',
                'manage customers',
            ],
            'driver' => [
                'track shipments',
            ]
        ];

        // Create roles and assign permissions
        foreach ($roles as $roleName => $rolePermissions) {
            $role = Role::firstOrCreate(['name' => $roleName]);
            $role->syncPermissions($rolePermissions);
        }
    }
}
