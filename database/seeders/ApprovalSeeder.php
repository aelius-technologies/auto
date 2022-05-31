<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Approval;

class ApprovalSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $accessory = Approval::create([
            'obf_id' => '1',
            'status' => 'pending',
            'reason' => null,
            'created_at' => date('Y-m-d H:i:s'),
            'created_by' => 1,
            'updated_at' => date('Y-m-d H:i:s'),
            'updated_by' => 1
        ]);  
    }
}
