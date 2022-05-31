<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SpecialRegistrationNumber;

class SpecialRegistrationNumberSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $tax = SpecialRegistrationNumber::create([
            'number' => '1',
            'amount' => '10000',
            'status' => 'active',
            'created_at' => date('Y-m-d H:i:s'),
            'created_by' => 1,
            'updated_at' => date('Y-m-d H:i:s'),
            'updated_by' => 1
        ]);
    }
}
