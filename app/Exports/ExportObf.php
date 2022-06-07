<?php

namespace App\Exports;

use App\Models\OBF;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use DB;

class ExportObf implements FromCollection, WithHeadings,WithStyles ,ShouldAutoSize
{
    /**
    * @return \Illuminate\Support\Collection
    */
    function __construct($slug) {
        $this->slug = $slug;
    }
    public function collection()
    {
        $obf = new OBF();
        $data = $obf->export($this->slug);

        if($data == null){
            return throw new \ErrorException('No Data Found!');
        }else{
            return $data;
        }
    }

    public function headings() :array{
        return ["Temporary ID" , "Sales Person Name" ,"Booking Date" , "Customer Name","Customer Type" ,"Branch Name" ,"Company Name" ,"GST" ,"Address" ,"Registration Address" ,"Email" ,"Pan Number","Adhar Number","Licance Number","Contact Number" ,"Date Of Birth","Nominee Name" ,"Nominee Reletion" ,"Nominee Age" ,"Occupation" ,"Product Name","Product Veriant","Is Applicable For MCP" ,"Exterior Color" ,"Interior Color","Ex Showroom Price","Registration Tax","Insurance","Municipal Tax","TCS Tax","Accessory Name","Extand Warranty Years","Extand Warranty Amount" ,"Fast Tag ID","Fast Tag Amount","Trad-in-Value","On Road Price","On Road Price In Words","Finance Name","Finance Branch Name","Lead Source" ,"Booking Amount" ,"Mode Of Payment" ,"Status"];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            // Style the first row as bold text.
            1    => ['font' => ['bold' => true]],
        ];
    }
}