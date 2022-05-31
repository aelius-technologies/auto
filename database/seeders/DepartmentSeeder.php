<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Department;

class DepartmentSeeder extends Seeder{
    public function run(){
        $tax = Department::create([
            'name' => 'HR Department',
            'branch_id' => '1',
            'email' => 'aanhonda@rajkot.com',
            'number' => '1234567890',
            'authorised_person' => 'john doh',
            'status' => 'active',
            'created_at' => date('Y-m-d H:i:s'),
            'created_by' => 1,
            'updated_at' => date('Y-m-d H:i:s'),
            'updated_by' => 1
        ]);
    }
}
