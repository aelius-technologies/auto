<?php

namespace Database\Seeders;

use App\Models\Fasttag;
use Illuminate\Database\Seeder;

class FasttagSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $tax = Fasttag::create([
            'tag_id' => 'FastTag_2022051301',
            'amount' => '500',
            'status' => 'active',
            'created_at' => date('Y-m-d H:i:s'),
            'created_by' => 1,
            'updated_at' => date('Y-m-d H:i:s'),
            'updated_by' => 1
        ]);  
    }
}
