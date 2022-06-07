<?php

namespace App\Exports;

use App\Models\Product;
use Illuminate\Validation\ValidationException;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Validators\Failure;

class ExportProduct implements FromCollection, WithHeadings,WithStyles ,ShouldAutoSize
{
    
    /**
    * @return \Illuminate\Support\Collection
    */
    function __construct($slug) {
        $this->slug = $slug;
    }
    public function collection()
    {
        $product = new Product();
        $data = $product->export($this->slug);

        if($data == null){
            return throw new \ErrorException('No Data Found!');
        }else{
            return $data;
        }
    }

    public function headings() :array{
        return ["Name" , "Veriant" , "Ex Showroom Price","Interior Color","Exterior Color","Is Applicable For MCP","Category Name" ,"Status"];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            // Style the first row as bold text.
            1    => ['font' => ['bold' => true]],
        ];
    }
    public function onFailure(Failure ...$failures){
        $exception = ValidationException::withMessages(collect($failures)->map->toArray()->all());
        throw $exception;
    }
 
}