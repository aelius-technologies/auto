<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Inventory;

class InventorySeeder extends Seeder{
    public function run(){
        $branch_1 = [
            [
                'category_id' => 1,
                'name' => 'Karnival',
                'branch_id' => 1,
                'veriant' => 'Prestige 6 STR',
                'key_number' => '0001111',
                'engine_number' => '0002221',
                'chassis_number' => '0003331',
                'vin_number' => '0004441',
                'ex_showroom_price' => '1999000',
                'interior_color' => 'Monotone',
                'exterior_color' => 'Browne',
            ],
            [
                'category_id' => 1,
                'name' => 'Karnival',
                'branch_id' => 1,
                'veriant' => 'Prestige',
                'key_number' => '0001112',
                'engine_number' => '0002222',
                'chassis_number' => '0003332',
                'vin_number' => '0004442',
                'ex_showroom_price' => '2399000',
                'interior_color' => 'Monotone',
                'exterior_color' => 'Black',
            ],
            [
                'category_id' => 1,
                'name' => 'Seltos',
                'branch_id' => 1,
                'veriant' => 'HTE',
                'key_number' => '0001113',
                'engine_number' => '0002223',
                'chassis_number' => '0003333',
                'vin_number' => '0004443',
                'ex_showroom_price' => '1019000',
                'interior_color' => 'Monotone',
                'exterior_color' => 'Browne',
            ],
            [
                'category_id' => 1,
                'name' => 'Seltos',
                'branch_id' => 1,
                'veriant' => 'HTK',
                'key_number' => '0001114',
                'engine_number' => '0002224',
                'chassis_number' => '0003334',
                'vin_number' => '0004444',
                'ex_showroom_price' => '1019000',
                'interior_color' => 'Monotone',
                'exterior_color' => 'Black',
            ]
        ];

        foreach($branch_1 as $row){
            Inventory::create([
                'category_id' => $row['category_id'],
                'name' => $row['name'],
                'branch_id' => $row['branch_id'],
                'veriant' => $row['veriant'],
                'key_number' => $row['key_number'],
                'engine_number' => $row['engine_number'],
                'chassis_number' => $row['chassis_number'],
                'vin_number' => $row['vin_number'],
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

        $branch_2 = [
            [
                'category_id' => 1,
                'name' => 'Karnival',
                'branch_id' => 2,
                'veriant' => 'Prestige 6 STR',
                'key_number' => '0005551',
                'engine_number' => '0006661',
                'chassis_number' => '0007771',
                'vin_number' => '0008881',
                'ex_showroom_price' => '1999000',
                'interior_color' => 'Monotone',
                'exterior_color' => 'Browne',
            ],
            [
                'category_id' => 1,
                'name' => 'Karnival',
                'branch_id' => 2,
                'veriant' => 'Prestige',
                'key_number' => '0005552',
                'engine_number' => '0006662',
                'chassis_number' => '0007772',
                'vin_number' => '0008882',
                'ex_showroom_price' => '2399000',
                'interior_color' => 'Monotone',
                'exterior_color' => 'Black',
            ],
            [
                'category_id' => 1,
                'name' => 'Seltos',
                'branch_id' => 2,
                'veriant' => 'HTE',
                'key_number' => '0005553',
                'engine_number' => '0006663',
                'chassis_number' => '0007773',
                'vin_number' => '0008883',
                'ex_showroom_price' => '1019000',
                'interior_color' => 'Monotone',
                'exterior_color' => 'Browne',
            ],
            [
                'category_id' => 1,
                'name' => 'Seltos',
                'branch_id' => 2,
                'veriant' => 'HTK',
                'key_number' => '0005554',
                'engine_number' => '0006664',
                'chassis_number' => '0007774',
                'vin_number' => '0008884',
                'ex_showroom_price' => '1019000',
                'interior_color' => 'Monotone',
                'exterior_color' => 'Black',
            ],
            [
                'category_id' => 1,
                'name' => 'Sonet',
                'branch_id' => 2,
                'veriant' => 'HTE Diesel',
                'key_number' => '0005555',
                'engine_number' => '0006665',
                'chassis_number' => '0007775',
                'vin_number' => '0008885',
                'ex_showroom_price' => '1119000',
                'interior_color' => 'Monotone',
                'exterior_color' => 'Black',
            ],
            [
                'category_id' => 1,
                'name' => 'Sonet',
                'branch_id' => 2,
                'veriant' => 'HTK Diesel',
                'key_number' => '0005556',
                'engine_number' => '0006666',
                'chassis_number' => '0007776',
                'vin_number' => '0008886',
                'ex_showroom_price' => '1219000',
                'interior_color' => 'Monotone',
                'exterior_color' => 'Black',
            ],
            [
                'category_id' => 1,
                'name' => 'Sonet',
                'branch_id' => 2,
                'veriant' => 'HTK Diesel',
                'key_number' => '0005557',
                'engine_number' => '0006667',
                'chassis_number' => '0007777',
                'vin_number' => '0008887',
                'ex_showroom_price' => '1219000',
                'interior_color' => 'Monotone',
                'exterior_color' => 'Brown',
            ]
        ];

        foreach($branch_2 as $row){
            Inventory::create([
                'category_id' => $row['category_id'],
                'name' => $row['name'],
                'branch_id' => $row['branch_id'],
                'veriant' => $row['veriant'],
                'key_number' => $row['key_number'],
                'engine_number' => $row['engine_number'],
                'chassis_number' => $row['chassis_number'],
                'vin_number' => $row['vin_number'],
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
