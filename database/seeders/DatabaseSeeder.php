<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Database\Seeders\CompanySeeder;
use Database\Seeders\CurrencySeeder;
use Database\Seeders\TruckTypeSeeder;
use Database\Seeders\CommonCostsSeeder;
use Database\Seeders\VoucherTypeSeeder;
use Database\Seeders\CostCategorySeeder;
use Database\Seeders\FuelCostsTableSeeder;
use Database\Seeders\CargoNaturesTableSeeder;
use Database\Seeders\PaymentModesTableSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            DepartmentSeeder::class,
            PositionsSeeder::class,
            RolePermissionSeeder::class,
            UserSeeder::class,
            CurrencySeeder::class,
            CommonCostsSeeder::class,
            PaymentModesTableSeeder::class,
            CargoNaturesTableSeeder::class,
            TruckTypeSeeder::class,
            FuelCostsTableSeeder::class,
            PermissionSeeder::class,
            RoleSeeder::class,
            CompanySeeder::class,
            AccountGroupSeeder::class,
            LedgerSeeder::class,
            CostCenterSeeder::class,
            CostCategorySeeder::class,
            VoucherTypeSeeder::class,
            UnitSeeder::class,
            StockGroupSeeder::class,
        ]);
    }
}

