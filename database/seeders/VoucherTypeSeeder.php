<?php

namespace Database\Seeders;

use App\Models\VoucherType;
use App\Models\Company;
use Illuminate\Database\Seeder;

class VoucherTypeSeeder extends Seeder
{
    public function run(): void
    {
        $company = Company::first();

        $voucherTypes = [
            ['name' => 'Payment', 'prefix' => 'PAY', 'numbering' => 'auto'],
            ['name' => 'Receipt', 'prefix' => 'REC', 'numbering' => 'auto'],
            ['name' => 'Sales', 'prefix' => 'SAL', 'numbering' => 'auto'],
            ['name' => 'Purchase', 'prefix' => 'PUR', 'numbering' => 'auto'],
            ['name' => 'Journal', 'prefix' => 'JRN', 'numbering' => 'auto'],
            ['name' => 'Contra', 'prefix' => 'CON', 'numbering' => 'auto'],
        ];

        foreach ($voucherTypes as $voucherType) {
            VoucherType::create(array_merge($voucherType, ['company_id' => $company->id]));
        }
    }
}
