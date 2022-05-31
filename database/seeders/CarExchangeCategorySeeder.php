<?php

namespace Database\Seeders;


use Illuminate\Database\Seeder;
use App\Models\CarExchangeCategory;

class CarExchangeCategorySeeder extends Seeder{
    public function run(){
        $branch = CarExchangeCategory::create([
            'name' => 'Honda',
            'status' => 'active',
            'created_at' => date('Y-m-d H:i:s'),
            'created_by' => 1,
            'updated_at' => date('Y-m-d H:i:s'),
            'updated_by' => 1
        ]);
    }
}
