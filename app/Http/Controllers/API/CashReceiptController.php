<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\CashReceipt;
use App\Models\OBF;
use App\Models\Order;
use Illuminate\Http\Request;

class CashReceiptController extends Controller
{
    /** construct */
        public function __construct(){
            $this->middleware('permission:cash_receipt-create', ['only' => ['create']]);
            $this->middleware('permission:cash_receipt-edit', ['only' => ['edit']]);
            $this->middleware('permission:cash_receipt-view', ['only' => ['view']]);
            $this->middleware('permission:cash_receipt-delete', ['only' => ['delete']]);
        }
    /** construct */
    /** index */
        public function index(Request $request){
            if(auth()->user()->can('cash_receipt-view')){
                $data = OBF::select('obf.id',
                DB::raw("CONCAT(users.first_name,' ',users.last_name) AS sales_person_name"),'obf.temporary_id' ,'obf.booking_date' ,'obf.customer_name' ,'obf.customer_type' ,'branches.name AS branch_name','branches.id AS branch_id' ,'obf.company_name' ,'obf.gst' ,'obf.address' ,'obf.registration' ,'obf.email' ,'obf.pan_number','obf.adhar_number' ,'obf.licance_number','obf.contact_number' ,'obf.dob' ,'obf.nominee_name','obf.nominee_reletion' ,'obf.nominee_age' ,'obf.occupation','products.name AS product_name' ,'products.veriant' ,'obf.exterior_color' ,'obf.interior_color' ,'obf.ex_showroom_price' ,'registration_tax.percentage AS registration_tax' ,'insurance.name AS insurance' ,'municipal_tax.percentage AS municipal_tax' ,'tcs_tax.percentage AS tcs_tax' ,'accessories.name AS accessory_name' ,'extand_warranties.years AS extand_warranties_years' ,'extand_warranties.amount AS extand_warranties_amount' ,'fasttags.tag_id AS fasttag_id' ,'fasttags.amount AS fasttag_amount','obf.trad_in_value' ,'obf.on_road_price' ,'obf.on_road_price_word' ,'obf.on_road_price_word' ,'finance.name AS finance_name' ,'finance_branch.name AS finance_branch_name' ,'lead.name AS lead_name' ,'obf.booking_amount' ,'obf.mode_of_payment' ,'obf.status' ,'obf.created_by' ,'obf.updated_by','obf.created_at' ,'obf.updated_at',
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
                        ->where(['obf.status' => 'obf_accepted'])
                        ->get();
            
                if(sizeof($data)){
                    return response()->json(['status' => 200 ,'message' => 'Data Found.' , 'Data' => $data]);
                }else{
                    return response()->json(['status' => 404 ,'message' => 'No Data Found.']);
                }
            }else{
                return response()->json(['status' => 401, 'message' => 'Not Authorized.']);
            }
        }
    /** index */

    /** view */
        public function view(Request $request){
            if(auth()->user()->can('cash_receipt-view')){

                $data = OBF::where(['id' => $request->id])->first();
                
                if($data)
                    return response()->json(['status' => 200, 'message' => 'Data found', 'data' => $data]);
                else
                    return response()->json(['status' => 404, 'message' => 'No data found']);
            }else{
                return response()->json(['status' => 401, 'message' => 'Not Authorized.']);
            }
        }
    /** view */

    /** change-status */
        public function change_status(Request $request){
            if(auth()->user()->can('cash_receipt-delete')){
                $rules = [
                    'id' => 'required',
                    'status' => 'required'
                ];

                $validator = Validator::make($request->all(), $rules);

                if($validator->fails())
                    return response()->json(['status' => 422, 'message' => $validator->errors()]);

                $data = OBF::where(['id' => $request->id])->first();

                if(!empty($data)){

                    if($data->status == 'account_rejected'){
                        $update = OBF::where(['id' => $request->id])->update(['status' => $request->status, 'reason' => $request->reason,'updated_at' => date('Y-m-d H:i:s'), 'updated_by' => auth('sanctum')->user()->id]);
                    }else if($data->status == 'account_accepted'){
                        $update = OBF::where(['id' => $request->id])->update(['status' => $request->status, 'updated_at' => date('Y-m-d H:i:s'), 'updated_by' => auth('sanctum')->user()->id]);
                    }else{
                        $update = OBF::where(['id' => $request->id])->update(['status' => $request->status, 'updated_at' => date('Y-m-d H:i:s'), 'updated_by' => auth('sanctum')->user()->id]);
                    }

                    if($update){
                        return response()->json(['status' => 200 ,'message' => 'Record status change successfully']);
                    }else{
                        return response()->json(['status' => 201, 'message' => 'Faild to update status']);
                    }
                }else{
                    return response()->json(['status' => 201, 'message' => 'Somthing went wrong !']);
                }
            }else{return response()->json(['status' => 401, 'message' => 'Not Authorized.']);}
        }
    /** change-status */

    /** Generate Cash-receipt */
        public function generate_cash_receipt(Request $request){
            $rules = [
                'id' => 'required',
                'status' => 'required'
            ];

            $validator = Validator::make($request->all(), $rules);

            if($validator->fails()){
                return response()->json(['status' => 422, 'message' => $validator->errors()]);
            }
            $path = URL('/uploads/kyc').'/';
            $data = OBF::select('obf.id',
            DB::raw("CONCAT(users.first_name,' ',users.last_name) AS sales_person_name"),'obf.temporary_id' ,'obf.booking_date' ,'obf.customer_name' ,'obf.customer_type' ,'branches.name AS branch_name','branches.id AS branch_id' ,'obf.company_name' ,'obf.gst' ,'obf.address' ,'obf.registration' ,'obf.email' ,'obf.pan_number','obf.adhar_number' ,'obf.licance_number','obf.contact_number' ,'obf.dob' ,'obf.nominee_name','obf.nominee_reletion' ,'obf.nominee_age' ,'obf.occupation','products.name AS product_name' ,'products.veriant' ,'obf.exterior_color' ,'obf.interior_color' ,'obf.ex_showroom_price' ,'registration_tax.percentage AS registration_tax' ,'insurance.name AS insurance' ,'municipal_tax.percentage AS municipal_tax' ,'tcs_tax.percentage AS tcs_tax' ,'accessories.name AS accessory_name' ,'extand_warranties.years AS extand_warranties_years' ,'extand_warranties.amount AS extand_warranties_amount' ,'fasttags.tag_id AS fasttag_id' ,'fasttags.amount AS fasttag_amount','obf.trad_in_value' ,'obf.on_road_price' ,'obf.on_road_price_word' ,'obf.on_road_price_word' ,'finance.name AS finance_name' ,'finance_branch.name AS finance_branch_name' ,'lead.name AS lead_name' ,'obf.booking_amount' ,'obf.mode_of_payment' ,'obf.status' ,'obf.created_by' ,'obf.updated_by','obf.created_at' ,'obf.updated_at',
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
                    ->where(['obf.id' => $request->id , 'obf.status' => 'account_accepted'])
                    ->first();
            if($data){
                $crud = [
                    'order_id' =>  'Order_ID'.Date('YmdHis'),
                    'obf_id' => $data->id,
                    'branch_id' => $data->branch_id,
                    'status' => 'waiting'
                ];
                $order = Order::insertGetId($crud);
                if($order){
                    return response()->json(['status' => 200 ,'message' => 'Record Inserted Succesfully.']);
                }else{
                    return response()->json(['status' => 201 ,'message' => 'Faild to insert record in order.']);
                }
            }else{
                return response()->json(['status' => 404 ,'message' => 'No record found!']);
            }

        }
    /** Generate Cash-receipt */
}
