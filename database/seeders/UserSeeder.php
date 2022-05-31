<?php

namespace Database\Seeders;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

class UserSeeder extends Seeder{
    public function run(){
        $data = [
            [
                'first_name' => 'Super',
                'last_name' => 'Admin',
                'contact_number' => '9898989800',
                'email' => 'admin@admin.com',
                'role' => 'admin',
                'branch' => null
            ],
            [
                'first_name' => 'Gajjar',
                'last_name' => 'Mitul',
                'contact_number' => '9898989801',
                'email' => 'mitul@mail.com',
                'role' => 'hr',
                'branch' => 1
            ],
            [
                'first_name' => 'HardIk',
                'last_name' => 'Patel',
                'contact_number' => '9898989802',
                'email' => 'hardik@mail.com',
                'role' => 'branch',
                'branch' => 1
            ],
            [
                'first_name' => 'Nitish',
                'last_name' => 'Kurmi',
                'contact_number' => '9898989803',
                'email' => 'nitish@mail.com',
                'role' => 'branch',
                'branch' => 2
            ],
        ];
        
        foreach($data as $row){
            $user = User::create([
                'first_name' => $row['first_name'],
                'last_name' => $row['last_name'],
                'contact_number' => $row['contact_number'],
                'email' => $row['email'],
                'branch' => $row['branch'],
                'password' => bcrypt('Admin@123'),
                'status' => 'active',
                'created_at' => date('Y-m-d H:i:s'),
                'created_by' => 1,
                'updated_at' => date('Y-m-d H:i:s'),
                'updated_by' => 1
            ]);
 
            $user->assignRole(Role::findByName($row['role']));
        }
    }
}