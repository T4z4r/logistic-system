<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $modules = [
            'dashboard',
            'users',
            'logistics-modules',
            'customers',
            'drivers',
            'trucks',
            'trailers',
            'routes',
            'allocations',
            'truck-change',
            'truck-loading',
            'breakdowns',
            'accidents',
            'out-of-budget',
            'reports',
            'view-finance-modules',
            'accounts-modules',
            'tally-modules',
            'finance-settings',
            'logistics-settings',
            'system-settings',
            'currencies',
            'taxes',
            'banks',
            'payment-methods',

        ];

        $actions = ['view', 'create', 'edit', 'delete'];

        foreach ($modules as $module) {
            foreach ($actions as $action) {
                Permission::firstOrCreate(['name' => "$action-$module"]);
            }
        }

        // Optional: Give superadmin all permissions
        $role = \Spatie\Permission\Models\Role::firstOrCreate(['name' => 'admin']);
        $role->syncPermissions(Permission::all());
    }
}
