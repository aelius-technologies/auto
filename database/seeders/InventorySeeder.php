<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Inventory;

class InventorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $inventory = Inventory::create([
            'category_id' => 1,
            'name' => 'City',
            'branch_id' => 1,
            'veriant' => 'City 1.5 X Line DCT Diesal AT - Monotone - Browne',
            'key_number' => '123',
            'engine_number' => '456',
            'chassis_number' => '657',
            'vin_number' => '563',
            'ex_showroom_price' => '1000000',
            'interior_color' => 'Monotone',
            'exterior_color' => 'Browne',
            'status' => 'active',
            'created_at' => date('Y-m-d H:i:s'),
            'created_by' => 1,
            'updated_at' => date('Y-m-d H:i:s'),
            'updated_by' => 1
        ]);     
    }
}
