<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PaymentModesTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('payment_modes')->insert([
            [
                'id' => 1,
                'name' => 'Per Truck',
                'status' => 0,
                'created_at' => null,
                'updated_at' => null,
            ],
            [
                'id' => 2,
                'name' => 'Per Ton',
                'status' => 0,
            'created_at' => null,
                'updated_at' => null,
            ],
        ]);
    }
}
