<?php

namespace App\Exports;

use App\Models\Product;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

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
        if($this->slug != 'all'){
            return Product::select('products.name','products.veriant','products.ex_showroom_price','products.interior_color','products.exterior_color','products.is_applicable_for_mcp','products.status', 'categories.name AS category_name')->leftjoin('categories' ,'products.category_id' ,'categories.id')->where(['products.status' => $this->slug])->get();
        }else{
            return Product::select('products.name','products.veriant','products.ex_showroom_price','products.interior_color','products.exterior_color','products.is_applicable_for_mcp','products.status', 'categories.name AS category_name')->leftjoin('categories' ,'products.category_id' ,'categories.id')->get();
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
}