<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Insurance;

class InsuranceSeeder extends Seeder{
    public function run(){
        $insurance = Insurance::create([
            'name' => 'LIC',
            'type' => '3rd Party',
            'years' => 'two',
            'amount' => '150000',
            'status' => 'active',
            'created_at' => date('Y-m-d H:i:s'),
            'created_by' => 1,
            'updated_at' => date('Y-m-d H:i:s'),
            'updated_by' => 1
        ]);     
    }
}
