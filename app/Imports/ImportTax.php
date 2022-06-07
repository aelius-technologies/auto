<?php

namespace App\Imports;

use App\Models\Tax;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class ImportTax implements ToModel ,WithStartRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function startRow(): int
    {
        return 6;
    }
    public function model(array $row)
    {
        $data = [
            'name' => $row[0],
            'percentage' => $row[1],
            'status' => 'active',
            'created_at' => date('Y-m-d H:i:s'),
            'created_by' => auth()->user()->id,
            'updated_at' => date('Y-m-d H:i:s'),
            'updated_by' => auth()->user()->id
        ];
        $tax = Tax::create($data);
        return $tax;
    }
}
