<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ProcessLedgerSeeder extends Seeder
{
    public function run(): void
    {
        $now = Carbon::now();

        DB::table('process_ledgers')->insert([
            ['id' => 1, 'name' => 'Truck Registration', 'created_at' => '2023-05-26 02:03:02', 'updated_at' => '2023-05-26 02:03:02'],
            ['id' => 2, 'name' => 'Trailer Registration', 'created_at' => '2023-05-26 02:03:02', 'updated_at' => '2023-05-26 02:03:02'],
            ['id' => 3, 'name' => 'Truck License Payment', 'created_at' => '2023-05-26 02:03:02', 'updated_at' => '2023-05-26 02:03:02'],
            ['id' => 4, 'name' => 'Trip Expense Payment', 'created_at' => '2023-05-26 02:03:02', 'updated_at' => '2023-05-26 02:03:02'],
            ['id' => 5, 'name' => 'Trip Invoice Generation', 'created_at' => '2023-05-26 02:03:02', 'updated_at' => '2023-05-26 02:03:02'],
            ['id' => 6, 'name' => 'Trip Invoice Payment', 'created_at' => '2023-05-26 02:03:02', 'updated_at' => '2023-05-26 02:03:02'],
            ['id' => 7, 'name' => 'Out of Budget Payment', 'created_at' => '2023-05-26 02:03:02', 'updated_at' => '2023-05-26 02:03:02'],
            ['id' => 8, 'name' => 'Breakdown Payment', 'created_at' => '2023-05-26 02:03:02', 'updated_at' => '2023-05-26 02:03:02'],
            ['id' => 9, 'name' => 'LPO Generation', 'created_at' => '2023-05-26 02:03:02', 'updated_at' => '2023-05-26 02:03:02'],
            ['id' => 10, 'name' => 'Goods Delivery Notice', 'created_at' => '2023-05-26 02:03:02', 'updated_at' => '2023-05-26 02:03:02'],
            ['id' => 11, 'name' => 'Purchase Invoice', 'created_at' => '2023-05-26 02:03:02', 'updated_at' => '2023-05-26 02:03:02'],
            ['id' => 12, 'name' => 'Purchase Payment', 'created_at' => '2023-05-26 02:03:02', 'updated_at' => '2023-05-26 02:03:02'],
            ['id' => 13, 'name' => 'Store Item Dispatch', 'created_at' => '2023-05-26 02:03:02', 'updated_at' => '2023-05-26 02:03:02'],
            ['id' => 14, 'name' => 'Procurement Dispatch', 'created_at' => '2023-05-26 02:03:02', 'updated_at' => '2023-05-26 02:03:02'],
            ['id' => 15, 'name' => 'Administration Expense Payment', 'created_at' => '2023-05-26 02:03:02', 'updated_at' => '2023-05-26 02:03:02'],
            ['id' => 16, 'name' => 'Retirement Registration', 'created_at' => '2023-05-26 02:03:02', 'updated_at' => '2023-05-26 02:03:02'],
            ['id' => 17, 'name' => 'Retirement Payment', 'created_at' => '2023-05-26 02:03:02', 'updated_at' => '2023-05-26 02:03:02'],
            ['id' => 18, 'name' => 'Allocation Request Submission', 'created_at' => '2023-05-26 02:03:02', 'updated_at' => '2023-05-26 02:03:02'],
            ['id' => 19, 'name' => 'Artisan Fund', 'created_at' => '2023-09-07 15:31:16', 'updated_at' => '2023-09-07 15:31:16'],
            ['id' => 20, 'name' => 'Artisan Fund', 'created_at' => '2023-09-07 15:36:00', 'updated_at' => '2023-09-07 15:36:00'],
            ['id' => 21, 'name' => 'Artisan Fund', 'created_at' => '2023-09-07 15:36:59', 'updated_at' => '2023-09-07 15:36:59'],
            ['id' => 22, 'name' => 'Labour Charges', 'created_at' => '2023-09-07 15:37:52', 'updated_at' => '2023-09-07 15:37:52'],
            ['id' => 23, 'name' => 'STAFF MEALS', 'created_at' => '2023-09-07 15:38:29', 'updated_at' => '2023-10-12 01:36:28'],
            ['id' => 24, 'name' => 'SPARE PARTS', 'created_at' => '2023-09-07 15:38:53', 'updated_at' => '2023-10-12 01:34:30'],
            ['id' => 25, 'name' => 'Assignment Payment', 'created_at' => null, 'updated_at' => null],
            ['id' => 26, 'name' => 'TRANSPORT FEE', 'created_at' => '2023-11-14 04:30:19', 'updated_at' => '2023-11-14 04:30:19'],
            ['id' => 27, 'name' => 'TRANSPORTING FEE', 'created_at' => '2023-11-14 04:33:07', 'updated_at' => '2023-11-14 04:33:07'],
            ['id' => 28, 'name' => 'OUT OF STATION ALLOWANCE', 'created_at' => '2023-11-16 08:19:31', 'updated_at' => '2023-11-16 08:19:31'],
        ]);
    }
}
