<?php

namespace Database\Seeders;
use Spatie\Permission\Models\Role;

use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder{
    public function run(){
        $roles = ['admin', 'branch', 'hr', 'sales', 'gm'];

        foreach ($roles as $role) {
            Role::create(['name' => $role, 'guard_name' => 'web', 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')]);
        }
    }
}