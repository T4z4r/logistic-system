<?php

namespace Database\Seeders;

use App\Models\Company;
use App\Models\Currency;
use App\Models\User;
use Illuminate\Database\Seeder;

class CompanySeeder extends Seeder
{
    public function run(): void
    {
        $user = User::first();
        $currency = Currency::first();

        Company::updateOrCreate(
            [
            'user_id' => $user->id,
            'name' => 'SudEnery Logistics',
            ],
            [
            'address' => '123 Main St, City',
            'tax_number' => 'TAX123456',
            'currency_id' => $currency->id,
            'financial_year_start' => '2025-01-01',
            ]
        );
    }
}
