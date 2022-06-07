<?php

namespace App\Imports;

use App\Models\Insurance;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;


class ImportInsurance implements ToModel ,WithStartRow
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
            'years' => $row[1],
            'type' => $row[2],
            'amount' => $row[3],
            'status' => 'active',
            'created_at' => date('Y-m-d H:i:s'),
            'created_by' => auth()->user()->id,
            'updated_at' => date('Y-m-d H:i:s'),
            'updated_by' => auth()->user()->id
        ];
        $insurance = Insurance::create($data);
        return $insurance;
    }
}
