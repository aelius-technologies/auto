<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Accessory;

class AccessorySeeder extends Seeder{
    public function run(){
        $accessory = Accessory::create([
            'name' => 'sound system',
            'type' => 'sound',
            'price' => '10000',
            'hsn_number' => '123',
            'model_number' => '123',
            'model_type' => 'touchpad screen',
            'warranty' => '1 year',
            'status' => 'active',
            'created_at' => date('Y-m-d H:i:s'),
            'created_by' => 1,
            'updated_at' => date('Y-m-d H:i:s'),
            'updated_by' => 1
        ]);  
    }
}
