<?php

namespace Database\Seeders;

use App\Models\Godown;
use App\Models\Company;
use Illuminate\Database\Seeder;

class GodownSeeder extends Seeder
{
    public function run(): void
    {
        $company = Company::first();

        $godowns = [
            ['name' => 'Main Godown', 'address' => '123 Warehouse St, City'],
            ['name' => 'Secondary Godown', 'address' => '456 Storage Rd, Town'],
        ];

        foreach ($godowns as $godown) {
            Godown::create(array_merge($godown, ['company_id' => $company->id]));
        }
    }
}
