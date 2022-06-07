<?php

namespace App\Exports;

use App\Models\Branch;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class ExportBranch implements FromCollection, WithHeadings,WithStyles ,ShouldAutoSize{
    function __construct($slug) {
        $this->slug = $slug;
    }

    public function collection(){
        $model = new Branch();
        $data = $model->export($this->slug);

        if($data == null){
            return throw new \ErrorException('No data found');
        }else{
            return $data;
        }
    }

    public function headings() :array{
        return ['Name',  'City', 'Address', 'Email', 'Contact Number', 'Manager', 'Manager Contact Number', 'GST', 'Status'];
    }

    public function styles(Worksheet $sheet){
        return [1 => ['font' => ['bold' => true]]];
    }
}