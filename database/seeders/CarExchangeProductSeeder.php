<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\CarExchangeProduct;

class CarExchangeProductSeeder extends Seeder{
    public function run(){
        $branch = CarExchangeProduct::create([
            'name' => 'City',
            'category_id' => 1,
            'status' => 'active',
            'created_at' => date('Y-m-d H:i:s'),
            'created_by' => 1,
            'updated_at' => date('Y-m-d H:i:s'),
            'updated_by' => 1
        ]);
    }
}
