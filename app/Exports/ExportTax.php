<?php

namespace App\Exports;

use App\Models\Tax;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class ExportTax implements FromCollection, WithHeadings,WithStyles ,ShouldAutoSize{
    function __construct($slug) {
        $this->slug = $slug;
    }

    public function collection(){
        $tax = new Tax();
        $data = $tax->export($this->slug);

        if($data == null){
            return throw new \ErrorException('No Data Found!');
        }else{
            return $data;
        }
    }

    public function headings() :array{
        return ["Name" , "Percentage","Status"];
    }

    public function styles(Worksheet $sheet){
        return [
            1    => ['font' => ['bold' => true]],
        ];
    }
}