<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Department;

class DepartmentSeeder extends Seeder
{
    public function run(): void
    {
        $departments = [
            'Logistics',
            'Customer Service',
            'IT Support',
            'Dispatch',
            'Drivers',
            'Administration',
            'Operations',
            'Management',
            'Accounts'
        ];

        foreach ($departments as $name) {
            Department::firstOrCreate(['name' => $name]);
        }
    }
}
