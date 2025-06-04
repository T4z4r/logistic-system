<?php

namespace Database\Seeders;

use App\Models\AccountGroup;
use App\Models\Company;
use Illuminate\Database\Seeder;

class AccountGroupSeeder extends Seeder
{
    public function run(): void
    {
        $company = Company::first();

        $groups = [
            ['name' => 'Assets', 'type' => 'asset', 'parent_id' => null],
            ['name' => 'Liabilities', 'type' => 'liability', 'parent_id' => null],
            ['name' => 'Income', 'type' => 'income', 'parent_id' => null],
            ['name' => 'Expenses', 'type' => 'expense', 'parent_id' => null],
            ['name' => 'Current Assets', 'type' => 'asset', 'parent_id' => 1],
            ['name' => 'Current Liabilities', 'type' => 'liability', 'parent_id' => 2],
            ['name' => 'Direct Income', 'type' => 'income', 'parent_id' => 3],
            ['name' => 'Direct Expenses', 'type' => 'expense', 'parent_id' => 4],
            ['name' => 'Cash-in-hand', 'type' => 'asset', 'parent_id' => 5],
            ['name' => 'Sundry Debtors', 'type' => 'asset', 'parent_id' => 5],
            ['name' => 'Sundry Creditors', 'type' => 'liability', 'parent_id' => 6],
        ];

        foreach ($groups as $index => $group) {
            AccountGroup::create(array_merge($group, ['company_id' => $company->id]));
            $groups[$index]['id'] = $index + 1; // Store ID for parent_id references
        }
    }

}
