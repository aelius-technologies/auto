<?php

namespace App\Exports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class ExportUser implements FromCollection, WithHeadings, WithStyles, ShouldAutoSize{
    function __construct($slug) {
        $this->slug = $slug;
    }

    public function collection(){
        $user = new User();
        $data = $user->export($this->slug);

        if($data == null){
            return throw new \ErrorException('No Data Found!');
        }else{
            return $data;
        }
    }

    public function headings() :array{
        return ["First Name", "Last Name", "Email", "Status"];
    }

    public function styles(Worksheet $sheet){
        return [1 => ['font' => ['bold' => true]]];
    }
}