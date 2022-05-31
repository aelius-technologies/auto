<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Accessory;
use App\Models\Allocation;
use App\Models\Approval;
use App\Models\Branch;
use App\Models\ExtandWarranty;
use App\Models\Fasttag;
use App\Models\Finance;
use App\Models\OBF;
use App\Models\User;
use App\Models\Product;
use App\Models\Tax;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use DB, Validator;

class ObfController extends Controller
{
    /** construct */
        public function __construct(){
            $this->middleware('permission:obf-create', ['only' => ['create']]);
            $this->middleware('permission:obf-edit', ['only' => ['edit']]);
            $this->middleware('permission:obf-view', ['only' => ['view']]);
            $this->middleware('permission:obf-delete', ['only' => ['delete']]);
        }
    /** construct */
    
     /** index */
        public function index(Request $request)
        {
            if(auth()->user()->can('obf-view')){
                $data = DB::table('obf')->get();
                if (sizeof($data)) {
                    return response()->json(['status' => 200, 'message' => 'Data Found.', 'Data' => $data]);
                } else {
                    return response()->json(['status' => 404, 'message' => 'No Data Found.']);
                }
            }else{
                return response()->json(['status' => 401, 'message' => 'Not Authorized.']);
            }
        }
    /** index */
    
    /** Get OBF Master Data */
        public function get_obf_master_data(Request $request){
            $data = [];
            $sales = User::role('sales')->select('id' , DB::raw("CONCAT(first_name,' ',last_name) AS sales_person_name"))->where(['status' => 'active'])->get();
            
            if(sizeof($sales)){
                $data['sales'] = $sales;
            }else{
                $data['sales'] = '';
            }

            $product = Product::select('id' , 'name' ,'veriant')->where(['status' => 'active'])->get();
            if(sizeof($product)){
                $data['product'] = $product;
            }else{
                $data['product'] = '';
            }
            
            $tax = Tax::select('id' , 'name' ,'percentage')->where(['status' => 'active'])->get();
            if(sizeof($tax)){
                $data['tax'] = $tax;
            }else{
                $data['tax'] = '';
            }
            
            $accessory = Accessory::select('id' , 'name' ,'price')->where(['status' => 'active'])->get();
            if(sizeof($accessory)){
                $data['accessory'] = $accessory;
            }else{
                $data['accessory'] = '';
            }
            
            $extanded_warranty = ExtandWarranty::select('id' , 'years' ,'amount')->where(['status' => 'active'])->get();
            if(sizeof($extanded_warranty)){
                $data['extanded_warranty'] = $extanded_warranty;
            }else{
                $data['extanded_warranty'] = '';
            }
            
            $fasttag = Fasttag::select('id' , 'tag_id' ,'amount')->where(['status' => 'active'])->get();
            if(sizeof($fasttag)){
                $data['fasttag'] = $fasttag;
            }else{
                $data['fasttag'] = '';
            }
            
            $branch = Branch::select('id' , 'name' ,'city')->where(['status' => 'active'])->get();
            if(sizeof($branch)){
                $data['branch'] = $branch;
            }else{
                $data['branch'] = '';
            }

            return response()->json(['status' => 200, 'message' => 'Data found', 'data' => $data]);
        }
    /** Get OBF Master Data */
    
    /** insert */
        public function insert(Request $request)
        {
            if(auth()->user()->can('obf-create')){
                $rules = [
                    'customer_name' => 'required',
                ];

                $validator = Validator::make($request->all(), $rules);

                if ($validator->fails())
                    return response()->json(['status' => 422, 'message' => $validator->errors()]);
                $obf = OBF::count();
                $temp_number = sprintf('%04d',$obf+1);

                $crud = [
                    'temporary_id' => $temp_number,
                    'booking_date' => $request->booking_date,
                    'customer_name' => $request->customer_name,
                    'customer_type' => $request->customer_type,
                    'branch_id' => $request->branch_id,
                    'company_name' => $request->company_name ?? null,
                    'gst' => $request->gst ?? null,
                    'address' => $request->address,
                    'registration' => $request->registration,
                    'email' => $request->email,
                    'pan_number' => $request->pan_number,
                    'adhar_number' => $request->adhar_number,
                    'licance_number' => $request->licance_number,
                    'contact_number' => $request->contact_number,
                    'dob' => $request->dob,
                    'nominee_name' => $request->nominee_name,
                    'nominee_reletion' => $request->nominee_reletion,
                    'nominee_age' => $request->nominee_age,
                    'occupation' => $request->occupation,
                    'sales_person_id' => $request->sales_person_id,
                    'product_id' => $request->product_id,
                    'varient_id' => $request->varient_id,
                    'exterior_color' => $request->exterior_color,
                    'interior_color' => $request->interior_color,
                    'ex_showroom_price' => $request->ex_showroom_price,
                    'registration_tax_id' => $request->registration_tax_id,
                    'insurance_id' => $request->insurance_id,
                    'municipal_tax_id' => $request->municipal_tax_id,
                    'tcs_tax_id' => $request->tcs_tax_id,
                    'accessory_id' => $request->accessory_id,
                    'extanded_warranty_id' => $request->extanded_warranty_id,
                    'fasttag_id' => $request->fasttag_id,
                    'trad_in_value' => $request->trad_in_value,
                    'on_road_price' => $request->on_road_price,
                    'on_road_price_word' => $request->on_road_price_word,
                    'finance_id' => $request->finance_id,
                    'finance_branch_id' => $request->finance_branch_id,
                    'lead_id' => $request->lead_id,
                    'booking_amount' => $request->booking_amount,
                    'mode_of_payment' => $request->mode_of_payment,
                    'status' => 'active',
                    'created_at' => date('Y-m-d H:i:s'),
                    'created_by' => auth()->user()->id,
                    'updated_at' => date('Y-m-d H:i:s'),
                    'updated_by' => auth()->user()->id
                ];

                // Pan Image
                    if(!empty($request->file('pan_image'))){
                        $file = $request->file('pan_image');
                        $filenameWithExtension = $request->file('pan_image')->getClientOriginalName();
                        $filename = pathinfo($filenameWithExtension, PATHINFO_FILENAME);
                        $extension = $request->file('pan_image')->getClientOriginalExtension();
                        $filenameToStore = time()."_".$filename.'.'.$extension;
        
                        $folder_to_upload = public_path().'/uploads/kyc/';
        
                        if(!\File::exists($folder_to_upload))
                            \File::makeDirectory($folder_to_upload, 0777, true, true);
        
                        $data['pan_image'] = $filenameToStore;
                    }else{
                        $data['pan_image'] = 'default.jpg';
                    }
                // Pan Image

                // Aadhar Image
                    if(!empty($request->file('adhar_image'))){
                        $file_two = $request->file('adhar_image');
                        $filenameWithExtension_two = $request->file('adhar_image')->getClientOriginalName();
                        $filename_two = pathinfo($filenameWithExtension_two, PATHINFO_FILENAME);
                        $extension_two = $request->file('adhar_image')->getClientOriginalExtension();
                        $filenameToStore_two = time()."_".$filename_two.'.'.$extension_two;
        
                        $folder_to_upload = public_path().'/uploads/kyc/';
        
                        if(!\File::exists($folder_to_upload))
                            \File::makeDirectory($folder_to_upload, 0777, true, true);
        
                        $data['adhar_image'] = $filenameToStore_two;
                    }else{
                        $data['adhar_image'] = 'default.jpg';
                    }
                // Aadhar Image

                // Licance Image
                    if(!empty($request->file('licance_image'))){
                        $file_three = $request->file('licance_image');
                        $filenameWithExtension_three = $request->file('licance_image')->getClientOriginalName();
                        $filename_three = pathinfo($filenameWithExtension_three, PATHINFO_FILENAME);
                        $extension_three = $request->file('licance_image')->getClientOriginalExtension();
                        $filenameToStore_three = time()."_".$filename_three.'.'.$extension_three;
        
                        $folder_to_upload = public_path().'/uploads/kyc/';
        
                        if(!\File::exists($folder_to_upload))
                            \File::makeDirectory($folder_to_upload, 0777, true, true);
        
                        $data['licance_image'] = $filenameToStore_three;
                    }else{
                        $data['licance_image'] = 'default.jpg';
                    }
                // Licance Image
                DB::beginTransaction();
                try {
                    $last_id = OBF::insertGetId($crud);
                    if ($last_id) {
                        // Move Files to Folder
                            if (!empty($request->file('pan_image'))){
                                $file->move($folder_to_upload, $filenameToStore);
                            }
                            if (!empty($request->file('adhar_image'))){
                                $file_two->move($folder_to_upload_two, $filenameToStore_two);
                            }
                            if (!empty($request->file('licance_image'))){
                                $file_three->move($folder_to_upload_three, $filenameToStore_three);
                            }
                        // Move Files to Folder
                        DB::commit();
                        return response()->json(['status' => 200, 'message' => 'Record inserted successfully']);
                    
                    } else {
                        DB::rollback();
                        return response()->json(['status' => 201, 'message' => 'Faild to insert Record in OBF!']);
                    }
                }catch (\Throwable $th){
                    DB::rollback();
                    return response()->json(['status' => 201, 'message' => 'Something went wrong, please try again later!']);
                }
            }else{
                return response()->json(['status' => 401, 'message' => 'Not Authorized.']);
            }
            
        }
    /** insert */

    /** view */
        public function view(Request $request)
        {
            if(auth()->user()->can('obf-view')){
                $path = URL('/uploads/kyc').'/';
                $data = OBF::select('obf.id',
                DB::raw("CONCAT(users.first_name,' ',users.last_name) AS sales_person_name"),'obf.temporary_id' ,'obf.booking_date' ,'obf.customer_name' ,'obf.customer_type' ,'branches.name AS branch_name' ,'obf.company_name' ,'obf.gst' ,'obf.address' ,'obf.registration' ,'obf.email' ,'obf.pan_number','obf.adhar_number' ,'obf.licance_number','obf.contact_number' ,'obf.dob' ,'obf.nominee_name','obf.nominee_reletion' ,'obf.nominee_age' ,'obf.occupation','products.name AS product_name' ,'products.veriant' ,'obf.exterior_color' ,'obf.interior_color' ,'obf.ex_showroom_price' ,'registration_tax.percentage AS registration_tax' ,'insurance.name AS insurance' ,'municipal_tax.percentage AS municipal_tax' ,'tcs_tax.percentage AS tcs_tax' ,'accessories.name AS accessory_name' ,'extand_warranties.years AS extand_warranties_years' ,'extand_warranties.amount AS extand_warranties_amount' ,'fasttags.tag_id AS fasttag_id' ,'fasttags.amount AS fasttag_amount','obf.trad_in_value' ,'obf.on_road_price' ,'obf.on_road_price_word' ,'obf.on_road_price_word' ,'finance.name AS finance_name' ,'finance_branch.name AS finance_branch_name' ,'lead.name AS lead_name' ,'obf.booking_amount' ,'obf.mode_of_payment' ,'obf.status' ,'obf.created_by' ,'obf.updated_by','obf.created_at' ,'obf.updated_at',
                DB::Raw("CASE
                            WHEN ".'pan_image'." != '' THEN CONCAT("."'".$path."'".", ".'pan_image'.")
                            ELSE CONCAT("."'".$path."'".", 'default.jpg')
                        END as pan_image"),
                DB::Raw("CASE
                            WHEN ".'adhar_image'." != '' THEN CONCAT("."'".$path."'".", ".'adhar_image'.")
                            ELSE CONCAT("."'".$path."'".", 'default.jpg')
                        END as adhar_image"),
                DB::Raw("CASE
                            WHEN ".'licance_image'." != '' THEN CONCAT("."'".$path."'".", ".'licance_image'.")
                            ELSE CONCAT("."'".$path."'".", 'default.jpg')
                        END as licance_image"),
                )
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
                        ->where(['obf.id' => $request->id])
                        ->first();

                if ($data)
                    return response()->json(['status' => 200, 'message' => 'Data found', 'data' => $data]);
                else
                    return response()->json(['status' => 404, 'message' => 'No data found']);
            }else{
                return response()->json(['status' => 401, 'message' => 'Not Authorized.']);
            }
        }
    /** view */

    /** update */
        public function update(Request $request)
        {
            if(auth()->user()->can('obf-edit')){
                $rules = [
                    'id' => 'required',
                    'customer_name' => 'required',
                ];

                $validator = Validator::make($request->all(), $rules);

                if ($validator->fails())
                    return response()->json(['status' => 422, 'message' => $validator->errors()]);

                $crud = [
                    'booking_date' => $request->booking_date,
                    'customer_name' => $request->customer_name,
                    'customer_type' => $request->customer_type,
                    'place' => $request->place,
                    'company_name' => $request->company_name ?? null,
                    'gst' => $request->gst ?? null,
                    'address' => $request->address,
                    'registration' => $request->registration,
                    'email' => $request->email,
                    'pan_number' => $request->pan_number,
                    'adhar_number' => $request->adhar_number,
                    'licance_number' => $request->licance_number,
                    'contact_number' => $request->contact_number,
                    'dob' => $request->dob,
                    'nominee_name' => $request->nominee_name,
                    'nominee_reletion' => $request->nominee_reletion,
                    'nominee_age' => $request->nominee_age,
                    'occupation' => $request->occupation,
                    'sales_person_id' => $request->sales_person_id,
                    'product_id' => $request->product_id,
                    'varient_id' => $request->varient_id,
                    'exterior_color' => $request->exterior_color,
                    'interior_color' => $request->interior_color,
                    'ex_showroom_price' => $request->ex_showroom_price,
                    'registration_tax_id' => $request->registration_tax_id,
                    'insurance_id' => $request->insurance_id,
                    'municipal_tax_id' => $request->municipal_tax_id,
                    'tcs_tax_id' => $request->tcs_tax_id,
                    'accessory_id' => $request->accessory_id,
                    'extanded_warranty_id' => $request->extanded_warranty_id,
                    'fasttag_id' => $request->fasttag_id,
                    'trad_in_value' => $request->trad_in_value,
                    'on_road_price' => $request->on_road_price,
                    'on_road_price_word' => $request->on_road_price_word,
                    'finance_id' => $request->finance_id,
                    'finance_branch_id' => $request->finance_branch_id,
                    'lead_id' => $request->lead_id,
                    'booking_amount' => $request->booking_amount,
                    'mode_of_payment' => $request->mode_of_payment,
                    'updated_at' => date('Y-m-d H:i:s'),
                    'updated_by' => auth('sanctum')->user()->id
                ];


                $update = OBF::where(['id' => $request->id])->update($crud);
                if ($update)
                    return response()->json(['status' => 200, 'message' => 'Record updated successfully']);
                else
                    return response()->json(['status' => 404, 'message' => 'Faild to update record']);
            }else{
                return response()->json(['status' => 401, 'message' => 'Not Authorized.']);
            }
        }
    /** update */

    /** change-status */
        public function change_status(Request $request)
        {
            if(auth()->user()->can('obf-edit')){
                $rules = [
                    'id' => 'required',
                    'status' => 'required'
                ];

                $validator = Validator::make($request->all(), $rules);

                if ($validator->fails())
                    return response()->json(['status' => 422, 'message' => $validator->errors()]);

                $data = OBF::where(['id' => $request->id])->first();

                if (!empty($data)) {
                    $update = OBF::where(['id' => $request->id])->update(['status' => $request->status, 'updated_at' => date('Y-m-d H:i:s'), 'updated_by' => auth('sanctum')->user()->id]);
                    if ($update) {
                        return response()->json(['status' => 200, 'message' => 'Record status change successfully']);
                    } else {
                        return response()->json(['status' => 201, 'message' => 'Faild to update status']);
                    }
                } else {
                    return response()->json(['status' => 201, 'message' => 'Somthing went wrong !']);
                }
            }else{
                return response()->json(['status' => 401, 'message' => 'Not Authorized.']);
            }
        }
    /** change-status */
}
