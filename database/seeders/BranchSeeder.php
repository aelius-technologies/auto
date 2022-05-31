<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Branch;

class BranchSeeder extends Seeder{
    public function run(){
        $data = [
            [
                'name' => 'Shivam Kia',
                'city' => 'Rajkot',
                'address' => 'Nana Mava Chowk',
                'contact_number' => '8888800001',
                'manager' => 'Kiran Patel',
                'manager_contact_number' => '9999900001',
                'email' => 'shivamkia@kia.com',
            ],
            [
                'name' => 'Riya Kia',
                'city' => 'Rajkot',
                'address' => 'Lal Chowk',
                'contact_number' => '8888800002',
                'manager' => 'Raj Patel',
                'manager_contact_number' => '9999900002',
                'email' => 'riyakia@kia.com',
            ]
        ];

        foreach($data as $row){
            Branch::create([
                'name' => $row['name'],
                'city' => $row['city'],
                'address' => $row['address'],
                'contact_number' => $row['contact_number'],
                'manager' => $row['manager'],
                'manager_contact_number' => $row['manager_contact_number'],
                'gst' => null,
                'email' => $row['email'],
                'status' => 'active',
                'created_at' => date('Y-m-d H:i:s'),
                'created_by' => 1,
                'updated_at' => date('Y-m-d H:i:s'),
                'updated_by' => 1
            ]);
        }
    }
}
