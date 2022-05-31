<?php

namespace App\Imports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;

class ImportUser implements ToModel ,WithStartRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function startRow(): int
    {
        return 2;
    }
    public function model(array $row)
    {
        
        
        return new User([
            'first_name' => $row[0],
            'last_name' => $row[1],
            'contact_number' => $row[2],
            'email' => $row[3],
            'password' => bcrypt($row[4]),
        ]);
    }
}
