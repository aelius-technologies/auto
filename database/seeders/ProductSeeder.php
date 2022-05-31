<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;

class ProductSeeder extends Seeder{
    public function run(){
        $data = [
            [
                'category_id' => 1,
                'name' => 'Karnival',
                'veriant' => 'Prestige 6 STR',
                'ex_showroom_price' => '1999000',
                'interior_color' => 'Monotone',
                'exterior_color' => 'Browne',
            ],
            [
                'category_id' => 1,
                'name' => 'Karnival',
                'veriant' => 'Prestige',
                'ex_showroom_price' => '2399000',
                'interior_color' => 'Monotone',
                'exterior_color' => 'Black',
            ],
            [
                'category_id' => 1,
                'name' => 'Seltos',
                'veriant' => 'HTE',
                'ex_showroom_price' => '1019000',
                'interior_color' => 'Monotone',
                'exterior_color' => 'Browne',
            ],
            [
                'category_id' => 1,
                'name' => 'Seltos',
                'veriant' => 'HTK',
                'ex_showroom_price' => '1019000',
                'interior_color' => 'Monotone',
                'exterior_color' => 'Black',
            ],
            [
                'category_id' => 1,
                'name' => 'Sonet',
                'veriant' => 'HTE Diesel',
                'ex_showroom_price' => '1119000',
                'interior_color' => 'Monotone',
                'exterior_color' => 'Black',
            ],
            [
                'category_id' => 1,
                'name' => 'Sonet',
                'veriant' => 'HTK Diesel',
                'ex_showroom_price' => '1219000',
                'interior_color' => 'Monotone',
                'exterior_color' => 'Black',
            ]
        ];

        foreach($data as $row){
            Product::create([
                'category_id' => $row['category_id'],
                'name' => $row['name'],
                'veriant' => $row['veriant'],
                'ex_showroom_price' => $row['ex_showroom_price'],
                'interior_color' => $row['interior_color'],
                'exterior_color' => $row['exterior_color'],
                'status' => 'active',
                'created_at' => date('Y-m-d H:i:s'),
                'created_by' => 1,
                'updated_at' => date('Y-m-d H:i:s'),
                'updated_by' => 1
            ]);     
        }
    }
}
