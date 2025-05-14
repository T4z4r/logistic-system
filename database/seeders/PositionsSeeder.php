<?php

namespace Database\Seeders;

use App\Models\Position;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class PositionsSeeder extends Seeder
{
    public function run(): void
    {
        $positions = [
            'Driver',
            'Dispatcher',
            'Warehouse Manager',
            'Logistics Coordinator',
            'Operations Supervisor',
            'Fleet Manager',
            'Delivery Associate',
            'Route Planner',
            'Inventory Controller',
            'Customs Clearance Officer',
            'Freight Forwarding Agent',
            'Supply Chain Analyst',
        ];

        foreach ($positions as $name) {
            Position::updateOrCreate(
                ['slug' => Str::slug($name)],
                ['name' => $name, 'status' => 1]
            );
        }
    }
}
