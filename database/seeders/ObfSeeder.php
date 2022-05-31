<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\OBF;

class ObfSeeder extends Seeder{
    public function run(){
        $tax = OBF::create([
            'temporary_id' => '0001',
            'booking_date' => '2023-05-14',
            'customer_name' => 'Peter Parker',
            'customer_type' => 'individual',
            'branch_id' => 1,
            'company_name' => null,
            'gst' => 'abcd',
            'address' => 'Rajkot',
            'registration' => '123',
            'email' => 'parker@peter.com',
            'pan_number' => '1234567890',
            'pan_image' => null,
            'adhar_number' => '123',
            'adhar_image' => null,
            'licance_number' => '123',
            'licance_image' => null,
            'contact_number' => '9898989898',
            'dob' => '1997-05-14',
            'nominee_name' => 'Marry Jane',
            'nominee_reletion' => 'wife',
            'nominee_age' => '35',
            'occupation' => 'reporter',
            'sales_person_id' => '1',
            'product_id' => '1',
            'varient_id' => '1',
            'exterior_color' => 'white',
            'interior_color' => 'monotone',
            'ex_showroom_price' => '250000',
            'registration_tax_id' => '1',
            'insurance_id' => '1',
            'municipal_tax_id' => '1',
            'tcs_tax_id' => '3',
            'accessory_id' => '1',
            'extanded_warranty_id' => '1',
            'fasttag_id' => '1',
            'trad_in_value' => '1',
            'on_road_price' => '3000000',
            'on_road_price_word' => 'thirty Lakh',
            'finance_id'=> '1',
            'finance_branch_id'=> '1',
            'lead_id'=> '1',
            'booking_amount'=> '25000',
            'mode_of_payment'=> 'cash',
            'reason'=> null,
            'status' => 'pending',
            'created_at' => date('Y-m-d H:i:s'),
            'created_by' => 1,
            'updated_at' => date('Y-m-d H:i:s'),
            'updated_by' => 1
        ]);
    }
}
