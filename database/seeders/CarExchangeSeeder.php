<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\CarExchange;

class CarExchangeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $branch = CarExchange
        ::create([
            'category_id' => 1,
            'product_id' => 1,
            'engine_number' => '123',
            'chassis_number' => '123',
            'price' => '50000',
            'status' => 'active',
            'created_at' => date('Y-m-d H:i:s'),
            'created_by' => 1,
            'updated_at' => date('Y-m-d H:i:s'),
            'updated_by' => 1
        ]);
    }
}
