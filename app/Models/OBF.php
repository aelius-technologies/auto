<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OBF extends Model
{
    use HasFactory;
    protected $table = 'obf';

    protected $fillable = ['temporary_number','booking_date','customer_name','customer_type','branch_id','company_name','gst','address','registration','email','pan_number','pan_image','adhar_number','adhar_image','licance_number','licance_image','contact_number','dob','nominee_name','nominee_reletion','nominee_age','occupation','sales_person_id','product_id','varient_id','exterior_color','interior_color','ex_showroom_price','registration_tax_id','insurance_id','municipal_tax_id','tcs_tax_id','accessory_id','extanded_warranty_id','fasttag_id','trad_in_value','on_road_price','on_road_price_word','finance_id','finance_branch_id','lead_id','booking_amount' ,'mode_of_payment','reason'];
}