<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Department;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Sample users
        $users = [
            [
            'name' => 'Admin User',
            'email' => 'admin@logistics.com',
            'role' => 'admin',
            'department' => 'Administration',
            ],
            [
            'name' => 'Dispatcher One',
            'email' => 'dispatcher@logistics.com',
            'role' => 'dispatcher',
            'department' => 'Dispatch',
            ],
            [
            'name' => 'Driver One',
            'email' => 'driver@logistics.com',
            'role' => 'driver',
            'department' => 'Drivers',
            ],
            [
            'name' => 'Operation Manager',
            'email' => 'manager@logistics.com',
            'role' => 'operation-manager',
            'department' => 'Operations',
            ],
            [
            'name' => 'Managing Director',
            'email' => 'director@logistics.com',
            'role' => 'managing-director',
            'department' => 'Management',
            ],
            [
            'name' => 'Accountant User',
            'email' => 'accountant@logistics.com',
            'role' => 'accountant',
            'department' => 'Accounts',
            ],
            [
            'name' => 'Customer Service',
            'email' => 'service@logistics.com',
            'role' => 'customer-service',
            'department' => 'Customer Service',
            ],
        ];

        foreach ($users as $data) {
            $department = Department::where('name', $data['department'])->first();

            $user = User::firstOrCreate(
                ['email' => $data['email']],
                [
                    'name' => $data['name'],
                    'password' => Hash::make(value: 'password'), // Default password
                    'department_id' => $department->id,
                ]
            );

            $user->assignRole($data['role']);
        }
    }
}
