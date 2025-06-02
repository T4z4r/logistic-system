<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class OffBudgetCategorySeeder extends Seeder
{
    public function run(): void
    {
        foreach ([
            ['name' => 'OOB FUEL EXPENSE', 'created_by' => 1, 'cost_id' => 16, 'created_at' => '2023-09-12 06:37:04', 'updated_at' => '2023-09-12 06:37:04'],
            ['name' => 'OOB PER DIEM(TZS)', 'created_by' => 1, 'cost_id' => 18, 'created_at' => '2023-09-12 06:38:37', 'updated_at' => '2023-09-12 06:38:37'],
            ['name' => 'OOB LASHING FEE', 'created_by' => 1, 'cost_id' => 34, 'created_at' => '2023-09-12 06:39:55', 'updated_at' => '2023-09-12 06:39:55'],
            ['name' => 'OOB COUCIL FEE-TUNDUMA', 'created_by' => 1, 'cost_id' => 28, 'created_at' => '2023-09-12 06:44:14', 'updated_at' => '2023-09-12 06:44:14'],
            ['name' => 'OOB ROAD PERMIT', 'created_by' => 1, 'cost_id' => 20, 'created_at' => '2023-09-12 06:49:50', 'updated_at' => '2023-09-12 06:49:50'],
            ['name' => 'OOB CARBON TAX', 'created_by' => 1, 'cost_id' => 20, 'created_at' => '2023-09-12 06:50:17', 'updated_at' => '2023-09-12 06:50:17'],
            ['name' => 'OOB COUNCIL FEES NAKONDE/ISOKA', 'created_by' => 1, 'cost_id' => 28, 'created_at' => '2023-09-12 06:58:45', 'updated_at' => '2023-09-12 06:58:45'],
            ['name' => 'OBB Council fees Kapiri', 'created_by' => 1, 'cost_id' => 28, 'created_at' => '2023-09-12 07:04:51', 'updated_at' => '2023-09-12 07:04:51'],
            ['name' => 'OBB Toll gate Chilonga', 'created_by' => 1, 'cost_id' => 21, 'created_at' => '2023-09-12 07:05:49', 'updated_at' => '2023-09-12 07:06:13'],
            ['name' => 'OOB Toll gate Kapiri', 'created_by' => 1, 'cost_id' => 21, 'created_at' => '2023-09-12 07:32:03', 'updated_at' => '2023-09-12 07:32:03'],
            ['name' => 'OOB Toll gate Kafulafuta', 'created_by' => 1, 'cost_id' => 21, 'created_at' => '2023-09-12 07:45:25', 'updated_at' => '2023-09-12 07:45:25'],
            ['name' => 'OOB Toll gate Michael Chilufya', 'created_by' => 1, 'cost_id' => 21, 'created_at' => '2023-09-12 07:47:43', 'updated_at' => '2023-09-12 07:50:49'],
            ['name' => 'OOB Toll gate Wilson Mofya', 'created_by' => 1, 'cost_id' => 21, 'created_at' => '2023-09-12 07:50:26', 'updated_at' => '2023-09-12 07:50:26'],
            ['name' => 'OOB Kasumbalesa Chilabombwe council', 'created_by' => 1, 'cost_id' => 28, 'created_at' => '2023-09-12 09:23:43', 'updated_at' => '2023-09-12 09:23:43'],
            ['name' => 'OOB ROAD TOLL(ZAMBIA)', 'created_by' => 1, 'cost_id' => 26, 'created_at' => '2023-09-12 09:32:25', 'updated_at' => '2023-09-12 09:32:25'],
            ['name' => 'OOB DRIVER PER DIEM(USD)', 'created_by' => 1, 'cost_id' => 18, 'created_at' => '2023-09-12 09:32:53', 'updated_at' => '2023-09-12 09:32:53'],
            ['name' => 'OOB PEAGE LUBUMBASHI(USD)', 'created_by' => 1, 'cost_id' => 26, 'created_at' => '2023-09-12 09:33:14', 'updated_at' => '2023-09-12 09:33:14'],
            ['name' => 'OTHER OOB', 'created_by' => 1, 'cost_id' => 42, 'created_at' => '2023-09-24 22:47:19', 'updated_at' => '2023-09-24 22:47:19'],
            ['name' => 'RADIATION FEE', 'created_by' => 862, 'cost_id' => 1, 'created_at' => '2024-08-24 05:14:23', 'updated_at' => '2024-08-24 05:14:23'],
        ] as $row) {
            DB::table('off_budget_categories')->updateOrInsert(
            ['name' => $row['name']],
            $row
            );
        }
        
    }
}
