<?php

namespace Database\Seeders;

use App\Models\CostCenter;
use App\Models\CostCategory;
use App\Models\Company;
use Illuminate\Database\Seeder;

class CostCenterSeeder extends Seeder
{
    public function run(): void
    {
        $company = Company::first();
        $categories = CostCategory::where('company_id', $company->id)->pluck('id', 'name');

        $costCenters = [
            ['name' => 'Marketing', 'cost_category_id' => $categories['Sales'] ?? null],
            ['name' => 'IT Department', 'cost_category_id' => $categories['Administration'] ?? null],
            ['name' => 'Project X', 'cost_category_id' => null],
        ];

        foreach ($costCenters as $costCenter) {
            CostCenter::create(array_merge($costCenter, ['company_id' => $company->id]));
        }
    }
}
