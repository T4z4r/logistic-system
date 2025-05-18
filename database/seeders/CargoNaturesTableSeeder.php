<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CargoNaturesTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('cargo_natures')->insert([
            [
                'id' => 1,
                'name' => 'Container',
                'status' => 0,
                'created_at' => '2023-03-23 23:51:14',
                'updated_at' => '2023-03-23 23:51:15',
            ],
            [
                'id' => 2,
                'name' => 'Loose Cargo',
                'status' => 0,
                'created_at' => '2023-03-23 23:51:14',
                'updated_at' => '2023-03-23 23:51:15',
            ],
            [
                'id' => 3,
                'name' => 'Out of grade',
                'status' => 0,
                'created_at' => '2023-03-23 23:51:14',
                'updated_at' => '2023-03-23 23:51:15',
            ],
        ]);
    }
}
