<?php

namespace App\Imports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class ImportUser implements ToModel ,WithStartRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function startRow(): int
    {
        return 5;
    }
    public function model(array $row)
    {
        $data = [
            'first_name' => $row[0],
            'last_name' => $row[1],
            'contact_number' =>"$row[2]",
            'email' => $row[3],
            'password' => bcrypt($row[4]),
            'status' => 'active',
            'created_at' => date('Y-m-d H:i:s'),
            'created_by' => 1,
            'updated_at' => date('Y-m-d H:i:s'),
            'updated_by' => 1
        ];
        $user = User::create($data);
        $user->assignRole($row[5]);
        return $user;
    }
}
