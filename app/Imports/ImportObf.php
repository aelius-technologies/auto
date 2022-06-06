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
use Illuminate\Support\Arr;

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
        $user = (object)'';
        if(isset($row[1]) && !empty($row[1])){
            $user_name = explode(' ',$row[1]);
            $user = User::select('id')->where(['first_name' => $user_name[0] ,'last_name' => $user_name[1]])->first();
            if(!$user){
                session(['error' => 'No User Found In at least one record!']);
                return null;
            }
        }else{
            $user->id = null;
        }
       
        $branch = (object)'';
        if(isset($row[5]) && !empty($row[5])){
            $branch = Branch::select('id')->where(['name' => $row[5]])->first();
            if(!$branch){
                session(['error' => 'No Branch Found In at least one record!']);
                return null;
            }
        }else{
            $branch->id = null;
        }
        
        $product = (object)'';
        if(isset($row[20]) && !empty($row[20])){
            $product = Product::select('id')->where(['name' => $row[20]])->first();
            if(!$product){
                session(['error' => 'No Branch Found In at least one record!']);
                return null;
            }
        }else{
            $product->id = null;
        }
        
        $registration_tax = (object)'';
        if(isset($row[26]) && !empty($row[26])){
            $registration_tax = Tax::select('id')->where(['name' => 'registration_tax' , 'percentage' => $row[26]])->first();
            if(!$registration_tax){
                session(['error' => 'No Registration Tax Found In at least one record!']);
                return null;
            }
        }else{
            $registration_tax->id = null;
        }
        
        $insurance = (object)'';
        if(isset($row[27]) && !empty($row[27])){
            $insurance = Insurance::select('id')->where(['name' => $row[27]])->first();
            if(!$insurance){
                session(['error' => 'No Insurance Found In at least one record!']);
                return null;
            }
            
        }else{
            $insurance->id = null;
        }
        
        $municipal_tax = (object)'';
        if(isset($row[28]) && !empty($row[28])){
            $municipal_tax = Tax::select('id')->where(['name' => 'municipal_tax' , 'percentage' => $row[28]])->first();
            if(!$municipal_tax){
                session(['error' => 'No Municipal Tax Found In at least one record!']);
                return null;
            }
        }else{
            $municipal_tax->id = null;
        }
        
        $tcs_tax = (object)'';
        if(isset($row[29]) && !empty($row[29])){
            $tcs_tax = Tax::select('id')->where(['name' => 'tcs_tax' , 'percentage' => $row[29]])->first();
            if(!$tcs_tax){
                session(['error' => 'No TCS Tax Found In at least one record!']);
                return null;
            }
        }else{
            $tcs_tax->id = null;
        }
        
        $accessory = (object)'';
        if(isset($row[30]) && !empty($row[30])){
            $accessory = Accessory::select('id')->where(['name' => $row[30]])->first();
            if(!$accessory){
                session(['error' => 'No Accessory Found In at least one record!']);
                return null;
            }
        }else{
            $accessory->id = null;
        }
        
        $warranty = (object)'';
        if(isset($row[31]) && $row[31]){
            $warranty = ExtandWarranty::select('id')->where(['years' => $row[31]])->first();
            if(!$warranty){
                session(['error' => 'No Extend Warranty Found In at least one record!']);
                return null;
            }
        }else{
            $warranty->id = null;
        }
        
        $fasttag = (object)'';
        if(isset($row[33]) && !empty($row[33])){
            $fasttag = Fasttag::select('id')->where(['tag_id' => $row[33]])->first();
            if(!$fasttag){
                session(['error' => 'No FastTag Found In at least one record!']);
                return null;
            }
        }else{
            $fasttag->id = null;
        }
        
        $finance = (object)'';
        if(isset($row[38]) && $row[38]){
            $finance = Finance::select('id' ,'branch_id')->where(['name' => $row[38]])->first();
            if(!$finance){
                session(['error' => 'No Finance Found In at least one record!']);
                return null;
            }
        }else{
            $finance->id = null;
        }
        $lead = (object)'';
        if(isset($row[40]) && !empty($row[40])){
            $lead = Lead::select('id')->where(['name' => $row[40]])->first();
            if(!$lead){
                session(['error' => 'No Lead Found In at least one record!']);
                return null;
            }
        }else{
            $lead->id = null;
        }
        
        $tred = (object)'';
        if(isset($row[35]) && !empty($row[35])){
            $trad_in_value = explode(' - ',$row[35]);
            $tred = CarExchange::select('id')->where(['price' => $trad_in_value[1]])->first();
            if(!$tred){
                session(['error' => 'No Tread-In-Value Found In at least one record!']);
                return null;
            }
        }else{
            $tred->id = null;
        }
        
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
        session(['success' => 'Record Imported Succesfully']);
        return $obf;
    }
    
    public function rules(): array
    {
        return [
            '*.10' => ['email' ,'unique:users,email']
        ];
    }
} 
