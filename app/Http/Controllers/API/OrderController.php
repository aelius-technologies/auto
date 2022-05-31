<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Product;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Validator , DB;

class OrderController extends Controller
{
    /** construct */
        public function __construct(){
            $this->middleware('permission:orders-create', ['only' => ['create']]);
            $this->middleware('permission:orders-edit', ['only' => ['edit']]);
            $this->middleware('permission:orders-view', ['only' => ['view']]);
            $this->middleware('permission:orders-delete', ['only' => ['delete']]);
        }
    /** construct */

    /** index */
        public function index(Request $request)
        {
            if(auth()->user()->can('orders-view')){
                $data = DB::table('orders')->
                select('orders.order_id','allocation.id','obf.booking_date' ,'obf.customer_name' ,'obf.customer_type' ,'branches.name AS branch_name' ,'obf.company_name' ,'obf.gst' ,'obf.address' ,'obf.registration' ,'obf.email' ,'obf.pan_number','obf.adhar_number','obf.licance_number' ,'obf.contact_number' ,'obf.dob' ,'obf.nominee_name' ,'obf.nominee_reletion' ,'obf.nominee_age' ,'obf.occupation'  ,'obf.exterior_color' ,'obf.interior_color' ,'obf.ex_showroom_price' ,'registration_tax.percentage AS registration_tax','insurance.name AS insurance' ,'municipal_tax.percentage AS municipal_tax' ,'tcs_tax.percentage AS tcs_tax' ,'accessories.name AS accessory_name' ,'accessories.price AS accessory_price' ,'extand_warranties.years AS extand_warranties_years' ,'extand_warranties.amount AS extand_warranties_amount' ,'fasttags.tag_id AS fast_tag_id' ,'fasttags.amount AS fast_tag_amount' ,'obf.trad_in_value' ,'obf.on_road_price' ,'obf.on_road_price_word' ,'finance.name AS finance_name' ,'finance_branch.name AS finance_branch_name','lead.name AS lead_name' ,'categories.name AS category_name','products.name AS product_name' ,'products.veriant','obf.booking_amount','obf.mode_of_payment','obf.reason',
                
                DB::raw("CONCAT(users.first_name,' ',users.last_name) AS sales_person_name")
                            )
                            ->leftjoin('obf' ,'orders.obf_id' ,'obf.id')
                            ->leftjoin('users' ,'obf.sales_person_id' ,'users.id')
                            ->leftjoin('taxes AS registration_tax' ,'obf.registration_tax_id' ,'registration_tax.id')
                            ->leftjoin('taxes AS municipal_tax' ,'obf.municipal_tax_id' ,'municipal_tax.id')
                            ->leftjoin('taxes AS tcs_tax' ,'obf.tcs_tax_id' ,'tcs_tax.id')
                            ->leftjoin('insurance' ,'obf.insurance_id' ,'insurance.id')
                            ->leftjoin('accessories' ,'obf.accessory_id' ,'accessories.id')
                            ->leftjoin('extand_warranties' ,'obf.extanded_warranty_id' ,'extand_warranties.id')
                            ->leftjoin('fasttags' ,'obf.fasttag_id' ,'fasttags.id')
                            ->leftjoin('finance' ,'obf.finance_id' ,'finance.id')
                            ->leftjoin('branches AS finance_branch' ,'obf.finance_branch_id' ,'finance_branch.id')
                            ->leftjoin('branches' ,'obf.branch_id' ,'branches.id')
                            ->leftjoin('lead' ,'obf.lead_id' ,'lead.id')
                            ->leftjoin('products' ,'obf.product_id' ,'products.id')
                            ->leftjoin('categories' ,'products.category_id' ,'categories.id')
                            ->get();
               
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


    /** view */
        public function view(Request $request)
        {
            if(auth()->user()->can('orders-view')){
                $data = DB::table('orders')->
                select('orders.order_id','allocation.id','obf.booking_date' ,'obf.customer_name' ,'obf.customer_type' ,'branches.name AS branch_name' ,'obf.company_name' ,'obf.gst' ,'obf.address' ,'obf.registration' ,'obf.email' ,'obf.pan_number','obf.adhar_number','obf.licance_number' ,'obf.contact_number' ,'obf.dob' ,'obf.nominee_name' ,'obf.nominee_reletion' ,'obf.nominee_age' ,'obf.occupation'  ,'obf.exterior_color' ,'obf.interior_color' ,'obf.ex_showroom_price' ,'registration_tax.percentage AS registration_tax','insurance.name AS insurance' ,'municipal_tax.percentage AS municipal_tax' ,'tcs_tax.percentage AS tcs_tax' ,'accessories.name AS accessory_name' ,'accessories.price AS accessory_price' ,'extand_warranties.years AS extand_warranties_years' ,'extand_warranties.amount AS extand_warranties_amount' ,'fasttags.tag_id AS fast_tag_id' ,'fasttags.amount AS fast_tag_amount' ,'obf.trad_in_value' ,'obf.on_road_price' ,'obf.on_road_price_word' ,'finance.name AS finance_name' ,'finance_branch.name AS finance_branch_name','lead.name AS lead_name' ,'categories.name AS category_name','products.name AS product_name' ,'products.veriant','obf.booking_amount','obf.mode_of_payment','obf.reason',
                
                DB::raw("CONCAT(users.first_name,' ',users.last_name) AS sales_person_name")
                            )
                ->leftjoin('obf' ,'orders.obf_id' ,'obf.id')
                ->leftjoin('users' ,'obf.sales_person_id' ,'users.id')
                ->leftjoin('taxes AS registration_tax' ,'obf.registration_tax_id' ,'registration_tax.id')
                ->leftjoin('taxes AS municipal_tax' ,'obf.municipal_tax_id' ,'municipal_tax.id')
                ->leftjoin('taxes AS tcs_tax' ,'obf.tcs_tax_id' ,'tcs_tax.id')
                ->leftjoin('insurance' ,'obf.insurance_id' ,'insurance.id')
                ->leftjoin('accessories' ,'obf.accessory_id' ,'accessories.id')
                ->leftjoin('extand_warranties' ,'obf.extanded_warranty_id' ,'extand_warranties.id')
                ->leftjoin('fasttags' ,'obf.fasttag_id' ,'fasttags.id')
                ->leftjoin('finance' ,'obf.finance_id' ,'finance.id')
                ->leftjoin('branches AS finance_branch' ,'obf.finance_branch_id' ,'finance_branch.id')
                ->leftjoin('branches' ,'obf.branch_id' ,'branches.id')
                ->leftjoin('lead' ,'obf.lead_id' ,'lead.id')
                ->leftjoin('products' ,'obf.product_id' ,'products.id')
                ->leftjoin('categories' ,'products.category_id' ,'categories.id')
                ->where(['orders.id' => $request->id])
                ->first();
                

                if ($data){
                    return response()->json(['status' => 200, 'message' => 'Data found', 'data' => $data]);
                }else{
                    return response()->json(['status' => 404, 'message' => 'No data found']);
                }
            }else{
                return response()->json(['status' => 401, 'message' => 'Not Authorized.']);
            }
        }
    /** view */

    /** change-status */
        public function change_status(Request $request)
        {
            if(auth()->user()->can('orders-delete')){
                $rules = [
                    'id' => 'required',
                    'status' => 'required'
                ];

                $validator = Validator::make($request->all(), $rules);

                if ($validator->fails())
                    return response()->json(['status' => 422, 'message' => $validator->errors()]);

                $data = Order::where(['id' => $request->id])->first();

                if (!empty($data)) {
                    $update = Order::where(['id' => $request->id])->update(['status' => $request->status, 'updated_at' => date('Y-m-d H:i:s'), 'updated_by' => auth('sanctum')->user()->id]);
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
    
    /** Get Gate Pass */
        public function get_gate_pass(Request $request){
            $rules = [
                'id' => 'required',
            ];
            
            $validator = Validator::make($request->all(), $rules);
            
            if ($validator->fails())
                return response()->json(['status' => 422, 'message' => $validator->errors()]);
            
            $data = Order::where(['id' => $request->id])->first();
            if($data){
                $gate = Order::select('obf.customer_name' , 'products.engine_number' ,'products.chassis_number' ,'products.verient' ,'obf.registration_number')->leftjoin('obf' , 'orders.obf_id' ,'obf.id')->leftjoin('products' , 'obf.product_id' ,'products.id')->where('orders.id' ,$request->id)->first();
                
                if($gate){
                    return response()->json(['status' => 200, 'message' => 'Data Found' , 'data' => $gate]); 
                }else{
                    return response()->json(['status' => 201, 'message' => 'Somthing went wrong!']);
                }
            }else{
                return response()->json(['status' => 404, 'message' => 'No Data Found !']);
            }
        }
    /** Get Gate Pass */
    
    /** Check Inventory */
        public function check_inventory(Request $request){
            $rules = [
                'product_name' => 'required',
                'veriant' => 'required',
                'branch_id' => 'required',
            ];
            
            $validator = Validator::make($request->all(), $rules);
            
            if ($validator->fails())
                return response()->json(['status' => 422, 'message' => $validator->errors()]);
                
            $data = Product::where(['product_name' => $request->product_name ,'veriant' => $request->veriant ,'branch_id' => $request->branch_id ,'status' => 'active'])->get();
            
            if(sizeof($data)){
                return response()->json(['status' => 200, 'message' => "Car Found" ,'data' => $data]);
            }else{
                return response()->json(['status' => 201, 'message' => "No Cars in this branch's inventory!"]);
            }
        }
    /** Check Inventory */

    /** Change Car Status to sold */
        public function change_car_status(Request $request){
            $rules = [
                'product_id' => 'required',
            ];
            
            $validator = Validator::make($request->all(), $rules);
            
            if ($validator->fails())
                return response()->json(['status' => 422, 'message' => $validator->errors()]);
            
            $data = Product::where(['id' => $request->product_id])->update(['status' => 'sold', 'updated_at' => date('Y-m-d H:i:s'), 'updated_by' => auth('sanctum')->user()->id]);
        }
    /** Change Car Status to sold */
}
