<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Database\Seeders\CurrencySeeder;
use Database\Seeders\TruckTypeSeeder;

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
            TruckTypeSeeder::class;
s
        ]);
    }
}

