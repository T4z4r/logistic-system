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
