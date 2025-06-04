<?php

namespace Database\Seeders;

use App\Models\StockGroup;
use App\Models\Company;
use Illuminate\Database\Seeder;

class StockGroupSeeder extends Seeder
{
    public function run(): void
    {
        $company = Company::first();

        $stockGroups = [
            ['name' => 'Primary', 'parent_id' => null],
            ['name' => 'Electronics', 'parent_id' => 1],
            ['name' => 'Clothing', 'parent_id' => 1],
            ['name' => 'Mobile Phones', 'parent_id' => 2],
            ['name' => 'Shirts', 'parent_id' => 3],
        ];

        foreach ($stockGroups as $index => $group) {
            StockGroup::create(array_merge($group, ['company_id' => $company->id]));
            $stockGroups[$index]['id'] = $index + 1; // Store ID for parent_id references
        }
    }
}
