<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\OBF;

class ObfSeeder extends Seeder{
    public function run(){
        $data = [
            [
                'temporary_id' => '0001',
                'booking_date' => '2022-05-14',
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
                'status' => 'account_accepted'
            ],
            [
                'temporary_id' => '0002',
                'booking_date' => '2022-05-14',
                'customer_name' => 'Bruce wayne',
                'customer_type' => 'individual',
                'branch_id' => 1,
                'company_name' => null,
                'gst' => 'abcde',
                'address' => 'Rajkot',
                'registration' => '1232',
                'email' => 'bruce@wayne.com',
                'pan_number' => '1234567891',
                'pan_image' => null,
                'adhar_number' => '1234',
                'adhar_image' => null,
                'licance_number' => '1234',
                'licance_image' => null,
                'contact_number' => '9898989891',
                'dob' => '1991-05-14',
                'nominee_name' => 'Ronin',
                'nominee_reletion' => 'Son',
                'nominee_age' => '25',
                'occupation' => 'Fighter',
                'sales_person_id' => '1',
                'product_id' => '2',
                'varient_id' => '1',
                'exterior_color' => 'white',
                'interior_color' => 'monotone',
                'ex_showroom_price' => '190000',
                'registration_tax_id' => '1',
                'insurance_id' => '1',
                'municipal_tax_id' => '1',
                'tcs_tax_id' => '3',
                'accessory_id' => '1',
                'extanded_warranty_id' => '1',
                'fasttag_id' => '1',
                'trad_in_value' => '1',
                'on_road_price' => '2200000',
                'on_road_price_word' => 'twenty Lakh',
                'finance_id'=> '1',
                'finance_branch_id'=> '1',
                'lead_id'=> '1',
                'booking_amount'=> '1800000',
                'mode_of_payment'=> 'cash',
                'reason'=> null,
                'status' => 'pending'
            ]
        ];

        foreach($data as $row){
            OBF::create([
                'temporary_id' => $row['temporary_id'],
                'booking_date' => $row['booking_date'],
                'customer_name' => $row['customer_name'],
                'customer_type' => $row['customer_type'],
                'branch_id' => $row['branch_id'],
                'company_name' => $row['company_name'],
                'gst' => $row['gst'],
                'address' => $row['address'],
                'registration' => $row['registration'],
                'email' => $row['email'],
                'pan_number' => $row['pan_number'],
                'pan_image' => $row['pan_image'],
                'adhar_number' => $row['adhar_number'],
                'adhar_image' => $row['adhar_image'],
                'licance_number' => $row['licance_number'],
                'licance_image' => $row['licance_image'],
                'contact_number' => $row['contact_number'],
                'dob' => $row['dob'],
                'nominee_name' => $row['nominee_name'],
                'nominee_reletion' => $row['nominee_reletion'],
                'nominee_age' => $row['nominee_age'],
                'occupation' => $row['occupation'],
                'sales_person_id' => $row['sales_person_id'],
                'product_id' => $row['product_id'],
                'varient_id' => $row['varient_id'],
                'exterior_color' => $row['exterior_color'],
                'interior_color' => $row['interior_color'],
                'ex_showroom_price' => $row['ex_showroom_price'],
                'registration_tax_id' => $row['registration_tax_id'],
                'insurance_id' => $row['insurance_id'],
                'municipal_tax_id' => $row['municipal_tax_id'],
                'tcs_tax_id' => $row['tcs_tax_id'],
                'accessory_id' => $row['accessory_id'],
                'extanded_warranty_id' => $row['extanded_warranty_id'],
                'fasttag_id' => $row['fasttag_id'],
                'trad_in_value' => $row['trad_in_value'],
                'on_road_price' => $row['on_road_price'],
                'on_road_price_word' => $row['on_road_price_word'],
                'finance_id'=> $row['finance_id'],
                'finance_branch_id'=> $row['finance_branch_id'],
                'lead_id'=> $row['lead_id'],
                'booking_amount'=> $row['booking_amount'],
                'mode_of_payment'=> $row['mode_of_payment'],
                'reason'=> $row['reason'],
                'status' => $row['status'],
                'created_at' => date('Y-m-d H:i:s'),
                'created_by' => 1,
                'updated_at' => date('Y-m-d H:i:s'),
                'updated_by' => 1
            ]);
        }
    }
}
