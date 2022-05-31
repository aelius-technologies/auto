<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Order;

class OrdersSeeder extends Seeder{
    public function run(){
        Order::create([
            'id' => 1,
            'order_id' => '0001',
            'obf_id' => 1,
            'branch_id' => 1,
            'status' => 'waiting',
            'created_at' => date('Y-m-d H:i:s'),
            'created_by' => 1,
            'updated_at' => date('Y-m-d H:i:s'),
            'updated_by' => 1
        ]);     
    }
}
