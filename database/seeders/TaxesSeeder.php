<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Tax;

class TaxesSeeder extends Seeder{
    public function run(){
        $data = [
            ['name' => 'registration_tax', 'percentage' => '1%'],
            ['name' => 'tcs_tax', 'percentage' => '10%'],
            ['name' => 'municipal_tax', 'percentage' => '15%']
        ];

        foreach($data as $row){
            $tax = Tax::create([
                'name' =>  $row['name'],
                'percentage' => $row['percentage'],
                'status' => 'active',
                'created_at' => date('Y-m-d H:i:s'),
                'created_by' => 1,
                'updated_at' => date('Y-m-d H:i:s'),
                'updated_by' => 1
            ]);
        }
    }
}
