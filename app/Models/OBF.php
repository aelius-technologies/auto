<?php

namespace App\Models;

use GuzzleHttp\Psr7\Request;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;

class OBF extends Model{
    use HasFactory;
    protected $table = 'obf';

    protected $fillable = [
        'temporary_id',
        'booking_date',
        'customer_name',
        'customer_type',
        'branch_id',
        'company_name',
        'gst',
        'address',
        'registration',
        'email',
        'pan_number',
        'pan_image',
        'adhar_number',
        'adhar_image',
        'licance_number',
        'licance_image',
        'contact_number',
        'dob',
        'nominee_name',
        'nominee_reletion',
        'nominee_age',
        'occupation',
        'sales_person_id',
        'product_id',
        'varient_id',
        'exterior_color',
        'interior_color',
        'ex_showroom_price',
        'registration_tax_id',
        'insurance_id',
        'municipal_tax_id',
        'tcs_tax_id',
        'accessory_id',
        'extanded_warranty_id',
        'fasttag_id',
        'trad_in_value',
        'on_road_price',
        'on_road_price_word',
        'finance_id',
        'finance_branch_id',
        'lead_id',
        'booking_amount',
        'mode_of_payment',
        'reason',
        'created_by',
        'created_at',
        'updated_by',
        'updated_at'
    ];

    public function export($slug){
        $collection = 
                OBF::select('obf.temporary_id',DB::raw("CONCAT(users.first_name,' ',users.last_name) AS sales_person_name") ,'obf.booking_date' ,'obf.customer_name' ,'obf.customer_type' ,'branches.name AS branch_name' ,'obf.company_name' ,'obf.gst' ,'obf.address' ,'obf.registration' ,'obf.email' ,'obf.pan_number','obf.adhar_number' ,'obf.licance_number','obf.contact_number' ,'obf.dob' ,'obf.nominee_name','obf.nominee_reletion' ,'obf.nominee_age' ,'obf.occupation','products.name AS product_name' ,'products.veriant','products.is_applicable_for_mcp' ,'obf.exterior_color' ,'obf.interior_color' ,'obf.ex_showroom_price' ,'registration_tax.percentage AS registration_tax' ,'insurance.name AS insurance' ,'municipal_tax.percentage AS municipal_tax' ,'tcs_tax.percentage AS tcs_tax' ,'accessories.name AS accessory_name' ,'extand_warranties.years AS extand_warranties_years' ,'extand_warranties.amount AS extand_warranties_amount' ,'fasttags.tag_id AS tag_id' ,'fasttags.amount AS fasttag_amount',DB::raw("CONCAT(car_exchange_product.name,' - ',car_exchange.price) AS trad_in_value") ,'obf.on_road_price' ,'obf.on_road_price_word' ,'obf.on_road_price_word','finance.name AS finance_name' ,'finance_branch.name AS finance_branch_name' ,'lead.name AS lead_name','obf.booking_amount' ,'obf.mode_of_payment' ,'obf.status')
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
                ->leftjoin('car_exchange_product' ,'car_exchange.product_id' ,'car_exchange_product.id');
        if($slug != 'all'){
            $collection->where(['obf.status' => $slug]);
        }
            $data = $collection->get();
        
            if($data->isNotEmpty()){
                return $data;
            }else{
                return null;
            }
    }
}