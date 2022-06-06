<?php

namespace App\Exports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class ExportUser implements FromCollection, WithHeadings,WithStyles ,ShouldAutoSize
{
    /**
    * @return \Illuminate\Support\Collection
    */
    function __construct($slug) {
        $this->slug = $slug;
    }
    public function collection()
    {
        if($this->slug != 'all'){
            return User::select('first_name','last_name','email','status')->where(['status' => $this->slug])->get();
        }else{
            return User::select('first_name','last_name','email','status')->get();
        }
    }

    public function headings() :array{
        return ["First Name" , "Last Name" , "Email" ,"Status"];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            // Style the first row as bold text.
            1    => ['font' => ['bold' => true]],
        ];
    }
}