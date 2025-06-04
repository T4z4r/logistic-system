<?php

namespace Database\Seeders;

use App\Models\Unit;
use App\Models\Company;
use Illuminate\Database\Seeder;

class UnitSeeder extends Seeder
{
    public function run(): void
    {
        $company = Company::first();

        $units = [
            ['name' => 'Unit', 'symbol' => 'unit', 'is_compound' => false, 'base_unit_id' => null, 'conversion_factor' => null],
            ['name' => 'Dozen', 'symbol' => 'doz', 'is_compound' => true, 'base_unit_id' => 1, 'conversion_factor' => 12],
            ['name' => 'Kilogram', 'symbol' => 'kg', 'is_compound' => false, 'base_unit_id' => null, 'conversion_factor' => null],
        ];

        foreach ($units as $index => $unit) {
            Unit::create(array_merge($unit, ['company_id' => $company->id]));
            if ($index === 0) {
                $units[1]['base_unit_id'] = 1; // Update base_unit_id for Dozen
            }
        }
    }
}
