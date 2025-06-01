<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class FuelCostsTableSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('fuel_costs')->insert([
            [
                'id' => 2,
                'name' => 'Fuel Expenses (Olympic Tunduma)',
                'ledger_id' => 64,
                'created_by' => 17,
                'created_at' => '2023-06-05 13:25:52',
                'updated_at' => '2023-06-18 02:57:37',
                'vat' => 0,
                'editable' => 1,
            ],
            [
                'id' => 3,
                'name' => 'Fuel Expenses (Olympic Mikese)',
                'ledger_id' => 64,
                'created_by' => 17,
                'created_at' => '2023-06-05 13:30:22',
                'updated_at' => '2023-09-29 00:54:45',
                'vat' => 0,
                'editable' => 1,
            ],
            [
                'id' => 4,
                'name' => 'Fuel Expenses (Mount Meru Mbezi)',
                'ledger_id' => 63,
                'created_by' => 17,
                'created_at' => '2023-06-05 13:30:47',
                'updated_at' => '2023-06-18 02:57:02',
                'vat' => 0,
                'editable' => 1,
            ],
            [
                'id' => 5,
                'name' => 'Fuel Expenses( Mount Meru Misugusugu)',
                'ledger_id' => 63,
                'created_by' => 844,
                'created_at' => '2023-12-29 11:29:42',
                'updated_at' => '2023-12-29 11:29:42',
                'vat' => 0,
                'editable' => 1,
            ],
            [
                'id' => 6,
                'name' => 'Fuel expenses(Acer Petrol ltd Tunduma)',
                'ledger_id' => 304,
                'created_by' => 1017,
                'created_at' => '2024-10-03 06:10:00',
                'updated_at' => '2024-10-03 06:18:13',
                'vat' => 0,
                'editable' => 1,
            ],
        ]);
    }
}
