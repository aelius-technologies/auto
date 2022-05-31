<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Branch;

class BranchSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $branch = Branch::create([
            'name' => 'Aan Honda',
            'city' => 'Rajkot',
            'address' => 'Nana Mava Chowk',
            'contact_number' => '9797979797',
            'manager' => 'John doah',
            'manager_contact_number' => '9999999999',
            'gst' => null,
            'email' => 'aanhonda@rajkot.com',
            'status' => 'active',
            'created_at' => date('Y-m-d H:i:s'),
            'created_by' => 1,
            'updated_at' => date('Y-m-d H:i:s'),
            'updated_by' => 1
        ]);
        
    }

}
