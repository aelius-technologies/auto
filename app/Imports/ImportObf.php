<?php

namespace App\Imports;

use App\Models\Accessory;
use App\Models\Branch;
use App\Models\CarExchange;
use App\Models\ExtandWarranty;
use App\Models\Fasttag;
use App\Models\Finance;
use App\Models\Insurance;
use App\Models\Lead;
use App\Models\OBF;
use App\Models\Product;
use App\Models\Tax;
use App\Models\User;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\SkipsErrors;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Concerns\WithProgressBar;
use DB;

class ImportObf implements ToModel , WithStartRow , SkipsOnError, SkipsOnFailure ,WithProgressBar
{
    use Importable ,SkipsErrors ,SkipsFailures ;
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function startRow(): int
    {
        return 2;
    }
    public function model(array $row)
    {   
        if(isset($row[1]) && !empty($row[1])){
            $user_name = explode(' ',$row[1]);
            $user = User::select('id')->where(['first_name' => $user_name[0] ,'last_name' => $user_name[1]])->first();
            if(!$user){
                session(['error' => 'No User Found In at least one record!']);
                return null;
            }
        }
        
        if(isset($row[5]) && !empty($row[5])){
            $branch = Branch::select('id')->where(['name' => $row[5]])->first();
            if(!$branch){
                return null;    
            }
        }

        $product = Product::select('id')->where(['name' => $row[20]])->first();

        $registration_tax = Tax::select('id')->where(['name' => 'registration_tax' , 'percentage' => $row[26]])->first();
        
        $insurance = Insurance::select('id')->where(['name' => $row[27]])->first();
        // DB::enableQueryLog();
        $municipal_tax = Tax::select('id')->where(['name' => 'municipal_tax' , 'percentage' => $row[28]])->first();
        // dd(DB::getQueryLog());
        
        $tcs_tax = Tax::select('id')->where(['name' => 'tcs_tax' , 'percentage' => $row[29]])->first();
        
        $accessory = Accessory::select('id')->where(['name' => $row[30]])->first();
        
        $warranty = ExtandWarranty::select('id')->where(['years' => $row[31]])->first();
        
        $fasttag = Fasttag::select('id')->where(['tag_id' => $row[33]])->first();
       
        $finance = Finance::select('id' ,'branch_id')->where(['name' => $row[38]])->first();
        
        $lead = Lead::select('id')->where(['name' => $row[40]])->first();

        $trad_in_value = explode(' - ',$row[35]);
        $tred = CarExchange::select('id')->where(['price' => $trad_in_value[1]])->first();
        
        // dd($row[0]);
        $data = [
            'temporary_id' => $row[0],
            'sales_person_id' => $user->id,
            'booking_date' => $row[2],
            'customer_name' => $row[3],
            'customer_type' => $row[4],
            'branch_id' => $branch->id,
            'company_name' => $row[6],
            'gst' => $row[7],
            'address' => $row[8],
            'registration' => $row[9],
            'email' => $row[10],
            'pan_number' => $row[11],
            'adhar_number' => $row[12],
            'licance_number' => $row[13],
            'contact_number' =>"$row[14]",
            'dob' =>"$row[15]",
            'nominee_name' =>"$row[16]",
            'nominee_reletion' =>"$row[17]",
            'nominee_age' =>"$row[18]",
            'occupation' =>"$row[19]",
            'product_id' =>"$product->id",
            'varient_id' =>"$product->id",
            'exterior_color' =>"$row[22]",
            'interior_color' =>"$row[23]",
            'ex_showroom_price' =>"$row[24]",
            'registration_tax_id' =>"$registration_tax->id",
            'insurance_id' =>"$insurance->id",
            'municipal_tax_id' =>"$municipal_tax->id",
            'tcs_tax_id' =>"$tcs_tax->id",
            'accessory_id' =>"$accessory->id",
            'extanded_warranty_id' =>"$warranty->id",
            'fasttag_id' =>"$fasttag->id",
            'trad_in_value' =>"$tred->id",
            'on_road_price' =>"$row[36]",
            'on_road_price_word' =>"$row[37]",
            'finance_id' =>"$finance->id",
            'finance_branch_id' =>"$finance->branch_id",
            'lead_id' =>"$lead->id",
            'booking_amount' =>"$row[41]",
            'mode_of_payment' =>"$row[42]",
            'status' => 'pending',
            'created_at' => date('Y-m-d H:i:s'),
            'created_by' => auth()->user()->id,
            'updated_at' => date('Y-m-d H:i:s'),
            'updated_by' => auth()->user()->id
        ];
        
        $obf = OBF::create($data);
        return $obf;
    }
    
    public function rules(): array
    {
        return [
            '*.10' => ['email' ,'unique:users,email']
        ];
    }
} 
