<?php

namespace Database\Seeders;

use App\Models\Ledger;
use App\Models\AccountGroup;
use App\Models\Company;
use Illuminate\Database\Seeder;

class LedgerSeeder extends Seeder
{
    public function run(): void
    {
        $company = Company::first();
        $groups = AccountGroup::where('company_id', $company->id)->pluck('id', 'name');

        $ledgers = [
            ['name' => 'Cash', 'group_id' => $groups['Cash-in-hand'], 'opening_balance' => 10000.00],
            ['name' => 'Sales Account', 'group_id' => $groups['Direct Income'], 'opening_balance' => 0],
            ['name' => 'Purchase Account', 'group_id' => $groups['Direct Expenses'], 'opening_balance' => 0],
            ['name' => 'John Doe', 'group_id' => $groups['Sundry Debtors'], 'opening_balance' => 5000.00, 'contact_details' => '123 Customer St'],
            ['name' => 'ABC Supplier', 'group_id' => $groups['Sundry Creditors'], 'opening_balance' => 3000.00, 'contact_details' => '456 Supplier Rd'],
        ];

        foreach ($ledgers as $ledger) {
            Ledger::create(array_merge($ledger, ['company_id' => $company->id]));
        }
    }
}
