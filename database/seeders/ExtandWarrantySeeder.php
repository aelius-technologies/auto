<?php

namespace Database\Seeders;

use App\Models\ExtandWarranty;
use Illuminate\Database\Seeder;

class ExtandWarrantySeeder extends Seeder{
    public function run(){
        $extandwarranty = ExtandWarranty::create([
            'years' => '1 year',
            'amount' => '10000',
            'status' => 'active',
            'created_at' => date('Y-m-d H:i:s'),
            'created_by' => 1,
            'updated_at' => date('Y-m-d H:i:s'),
            'updated_by' => 1
        ]);
    }
}
