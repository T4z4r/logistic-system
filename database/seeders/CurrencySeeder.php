<?php

// database/seeders/CurrencySeeder.php
namespace Database\Seeders;

use App\Models\Currency;
use Illuminate\Database\Seeder;

class CurrencySeeder extends Seeder
{
    public function run()
    {
        Currency::insert([
            [
                'name' => 'Tanzania Shilling', 'symbol' => 'Tsh', 'currency' => 'TZS',
                'rate' => '1', 'status' => 1, 'created_by' => 1,
                'code' => 'TZS', 'value' => 1, 'created_at' => now(), 'updated_at' => now()
            ],
            [
                'name' => 'Zambia Kwacha', 'symbol' => 'ZMW', 'currency' => 'ZMK',
                'rate' => '99', 'status' => 1, 'created_by' => 1,
                'code' => 'ZMK', 'value' => 102.4197, 'created_at' => now(), 'updated_at' => now()
            ],
            [
                'name' => 'US Dollar', 'symbol' => '$', 'currency' => 'USD',
                'rate' => '2600', 'status' => 1, 'created_by' => 1,
                'code' => 'USD', 'value' => 2703.88, 'created_at' => now(), 'updated_at' => now()
            ]
        ]);
    }
}
