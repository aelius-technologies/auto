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
        if($this->slug != 'all'){
            return OBF::select('obf.temporary_id',
            DB::raw("CONCAT(users.first_name,' ',users.last_name) AS sales_person_name") ,'obf.booking_date' ,'obf.customer_name' ,'obf.customer_type' ,'branches.name AS branch_name' ,'obf.company_name' ,'obf.gst' ,'obf.address' ,'obf.registration' ,'obf.email' ,'obf.pan_number','obf.adhar_number' ,'obf.licance_number','obf.contact_number' ,'obf.dob' ,'obf.nominee_name','obf.nominee_reletion' ,'obf.nominee_age' ,'obf.occupation','products.name AS product_name' ,'products.veriant','products.is_applicable_for_mcp' ,'obf.exterior_color' ,'obf.interior_color' ,'obf.ex_showroom_price' ,'registration_tax.percentage AS registration_tax' ,'insurance.name AS insurance' ,'municipal_tax.percentage AS municipal_tax' ,'tcs_tax.percentage AS tcs_tax' ,'accessories.name AS accessory_name' ,'extand_warranties.years AS extand_warranties_years' ,'extand_warranties.amount AS extand_warranties_amount' ,'fasttags.tag_id AS tag_id' ,'fasttags.amount AS fasttag_amount',
            DB::raw("CONCAT(car_exchange_product.name,' - ',car_exchange.price) AS trad_in_value") ,'obf.on_road_price' ,'obf.on_road_price_word' ,'obf.on_road_price_word','finance.name AS finance_name' ,'finance_branch.name AS finance_branch_name' ,'lead.name AS lead_name','obf.booking_amount' ,'obf.mode_of_payment' ,'obf.status')
            ->leftjoin('users' ,'obf.sales_person_id' ,'users.id')
            ->leftjoin('branches' ,'obf.branch_id' ,'branches.id')
            ->leftjoin('products' ,'obf.product_id' ,'products.id')
            ->leftjoin('taxes AS registration_tax' ,'obf.registration_tax_id' ,'registration_tax.id')
            ->leftjoin('taxes AS municipal_tax' ,'obf.municipal_tax_id' ,'municipal_tax.id')
            ->leftjoin('taxes AS tcs_tax' ,'obf.tcs_tax_id' ,'tcs_tax.id')
            ->leftjoin('insurance' ,'obf.insurance_id' ,'insurance.id')
            ->leftjoin('accessories' ,'obf.accessory_id' ,'accessories.id')
            ->leftjoin('extand_warranties' ,'obf.extanded_warranty_id' ,'extand_warranties.id')
            ->leftjoin('fasttags' ,'obf.fasttag_id' ,'fasttags.id')
            ->leftjoin('finance' ,'obf.finance_id' ,'finance.id')
            ->leftjoin('branches AS finance_branch' ,'obf.finance_branch_id' ,'finance_branch.id')
            ->leftjoin('lead' ,'obf.lead_id' ,'lead.id')
            ->leftjoin('car_exchange' ,'obf.trad_in_value' ,'car_exchange.id')
            ->leftjoin('car_exchange_product' ,'car_exchange.product_id' ,'car_exchange_product.id')
            ->where(['obf.status' => $this->slug])
            ->get();
        }else{
            return OBF::select('obf.temporary_id',
            DB::raw("CONCAT(users.first_name,' ',users.last_name) AS sales_person_name") ,'obf.booking_date' ,'obf.customer_name' ,'obf.customer_type' ,'branches.name AS branch_name' ,'obf.company_name' ,'obf.gst' ,'obf.address' ,'obf.registration' ,'obf.email' ,'obf.pan_number','obf.adhar_number' ,'obf.licance_number','obf.contact_number' ,'obf.dob' ,'obf.nominee_name','obf.nominee_reletion' ,'obf.nominee_age' ,'obf.occupation','products.name AS product_name' ,'products.veriant','products.is_applicable_for_mcp' ,'obf.exterior_color' ,'obf.interior_color' ,'obf.ex_showroom_price' ,'registration_tax.percentage AS registration_tax' ,'insurance.name AS insurance' ,'municipal_tax.percentage AS municipal_tax' ,'tcs_tax.percentage AS tcs_tax' ,'accessories.name AS accessory_name' ,'extand_warranties.years AS extand_warranties_years' ,'extand_warranties.amount AS extand_warranties_amount' ,'fasttags.tag_id AS tag_id' ,'fasttags.amount AS fasttag_amount','obf.trad_in_value' ,'obf.on_road_price' ,'obf.on_road_price_word' ,'obf.on_road_price_word','finance.name AS finance_name' ,'finance_branch.name AS finance_branch_name' ,'lead.name AS lead_name','obf.booking_amount' ,'obf.mode_of_payment' ,'obf.status')
            ->leftjoin('users' ,'obf.sales_person_id' ,'users.id')
            ->leftjoin('branches' ,'obf.branch_id' ,'branches.id')
            ->leftjoin('products' ,'obf.product_id' ,'products.id')
            ->leftjoin('taxes AS registration_tax' ,'obf.registration_tax_id' ,'registration_tax.id')
            ->leftjoin('taxes AS municipal_tax' ,'obf.municipal_tax_id' ,'municipal_tax.id')
            ->leftjoin('taxes AS tcs_tax' ,'obf.tcs_tax_id' ,'tcs_tax.id')
            ->leftjoin('insurance' ,'obf.insurance_id' ,'insurance.id')
            ->leftjoin('accessories' ,'obf.accessory_id' ,'accessories.id')
            ->leftjoin('extand_warranties' ,'obf.extanded_warranty_id' ,'extand_warranties.id')
            ->leftjoin('fasttags' ,'obf.fasttag_id' ,'fasttags.id')
            ->leftjoin('finance' ,'obf.finance_id' ,'finance.id')
            ->leftjoin('branches AS finance_branch' ,'obf.finance_branch_id' ,'finance_branch.id')
            ->leftjoin('lead' ,'obf.lead_id' ,'lead.id')
            ->get();
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