<?php

namespace Database\Seeders;

use App\Models\CostCategory;
use App\Models\Company;
use Illuminate\Database\Seeder;

class CostCategorySeeder extends Seeder
{
    public function run(): void
    {
        $company = Company::first();

        $categories = [
            ['name' => 'Sales'],
            ['name' => 'Administration'],
            ['name' => 'Production'],
        ];

        foreach ($categories as $category) {
            CostCategory::create(array_merge($category, ['company_id' => $company->id]));
        }
    }
}
