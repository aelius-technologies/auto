<?php

namespace Database\Seeders;

use App\Models\ExtandWarranty;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder{
    public function run(){
        $this->call([
            RoleSeeder::class,
            BranchSeeder::class,
            UserSeeder::class,
            PermissionSeeder::class,
            SettingSeeder::class,
            CategorySeeder::class,
            ProductSeeder::class,
            InventorySeeder::class,
            TaxesSeeder::class,
            InsuranceSeeder::class,
            AccessorySeeder::class,
            ExtandWarrantySeeder::class,
            FasttagSeeder::class,
            CarExchangeCategorySeeder::class,
            CarExchangeProductSeeder::class,
            CarExchangeSeeder::class,
            LeadSeeder::class,
            FinanceSeeder::class,
            SpecialRegistrationNumberSeeder::class,
            DepartmentSeeder::class,
            ObfSeeder::class,
            ApprovalSeeder::class,
            OrdersSeeder::class,
        ]);
    }
}
