<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\TruckType;

class TruckTypeSeeder extends Seeder
{
    public function run()
    {
        TruckType::insert([
            ['name' => 'Semi', 'added_by' => 1, 'created_at' => now()],
            ['name' => 'Pulling', 'added_by' => 1, 'created_at' => now()],
            ['name' => 'Private', 'added_by' => 1, 'created_at' => now()],
        ]);
    }
}
