<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $product = Product::create([
            'category_id' => 1,
            'name' => 'City',
            'veriant' => 'City 1.5 X Line DCT Diesal AT - Monotone - Browne',
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
