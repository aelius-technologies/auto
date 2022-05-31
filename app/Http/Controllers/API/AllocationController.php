<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Allocation;
use App\Models\OBF;
use App\Models\Order;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use DB , Validator;

class AllocationController extends Controller
{
    /** construct */
        public function __construct(){
            $this->middleware('permission:allocation-create', ['only' => ['create']]);
            $this->middleware('permission:allocation-edit', ['only' => ['edit']]);
            $this->middleware('permission:allocation-view', ['only' => ['view']]);
            $this->middleware('permission:allocation-delete', ['only' => ['delete']]);
        }
    /** construct */
    
    /** index */
        public function index(Request $request){
            if(auth()->user()->can('allocation-view')){
                $data = Allocation::all();
                if(sizeof($data)){
                    return response()->json(['status' => 200, 'message' => 'Data Found.', 'Data' => $data]);
                }else{
                    return response()->json(['status' => 404, 'message' => 'No Data Found.']);
                }
            }else{
                return response()->json(['status' => 401, 'message' => 'Not Authorized.']);
            }        
        }
    /** index */
    
    /** View */
        public function view(Request $request){
            if(auth()->user()->can('allocation-view')){
                // Validation
                    $rules = [
                        'id' => 'required',
                    ];
                    
                    $validator = Validator::make($request->all(), $rules);
                    
                    if ($validator->fails())
                        return response()->json(['status' => 422, 'message' => $validator->errors()]);
                // Validation
                $data = Allocation::select('allocation.id','obf.booking_date' ,'allocation.billing_date' ,'obf.customer_name' ,'obf.place' ,'obf.company_name' ,'obf.gst' ,'obf.address' ,'obf.registration' ,'obf.email' ,'obf.pan_number','obf.adhar_number','obf.licance_number' ,'obf.contact_number' ,'obf.dob' ,'obf.nominee_name' ,'obf.nominee_reletion' ,'obf.nominee_age' ,'obf.occupation'  ,'obf.exterior_color' ,'obf.interior_color' ,'obf.ex_showroom_price' ,'registration_tax.percentage AS registration_tax', 'insurance.name AS insurance' ,'municipal_tax.percentage AS municipal_tax' ,'tcs_tax.percentage AS tcs_tax' ,'accessories.name AS accessory_name' ,'accessories.price AS accessory_price' ,'extand_warranties.years AS extand_warranties_years' ,'extand_warranties.amount AS extand_warranties_amount' ,'fasttags.tag_id AS fast_tag_id' ,'fasttags.amount AS fast_tag_amount' ,'obf.trad_in_value' ,'obf.on_road_price' ,'obf.on_road_price_word' ,'finance.name AS finance_name' ,'branches.name AS branch_name' ,'finance.dsa_or_broker' ,'allocation.disb_amount' ,'allocation.payment_due' ,'allocation.fatd' ,'allocation.iatd' ,'lead.name AS lead_name' ,'allocation.mode_of_payment' ,'allocation.tentetive_delivery_date' ,'allocation.booking_amount' ,'categories.name AS category_name','products.name AS product_name' ,'products.veriant',
                
                DB::raw("CONCAT(users.first_name,' ',users.last_name) AS sales_person_name")
                )
                ->leftjoin('obf' ,'allocation.obf_temporary_id' ,'obf.temporary_id')
                ->leftjoin('users' ,'obf.sales_person_id' ,'users.id')
                ->leftjoin('taxes AS registration_tax' ,'obf.registration_tax_id' ,'registration_tax.id')
                ->leftjoin('taxes AS municipal_tax' ,'obf.municipal_tax_id' ,'municipal_tax.id')
                ->leftjoin('taxes AS tcs_tax' ,'obf.tcs_tax_id' ,'tcs_tax.id')
                ->leftjoin('insurance' ,'obf.insurance_id' ,'insurance.id')
                ->leftjoin('accessories' ,'obf.accessory_id' ,'accessories.id')
                ->leftjoin('extand_warranties' ,'obf.extanded_warranty_id' ,'extand_warranties.id')
                ->leftjoin('fasttags' ,'obf.fasttag_id' ,'fasttags.id')
                ->leftjoin('finance' ,'allocation.finance_id' ,'finance.id')
                ->leftjoin('branches' ,'allocation.finance_branch_id' ,'branches.id')
                ->leftjoin('lead' ,'allocation.lead_id' ,'lead.id')
                ->leftjoin('products' ,'obf.product_id' ,'products.id')
                ->leftjoin('categories' ,'products.category_id' ,'categories.id')
                ->where(['allocation.id' => $request->id])
                ->first();
                if($data){
                    return response()->json(['status' => 200, 'message' => 'Data Found.', 'Data' => $data]);
                }else{
                    return response()->json(['status' => 404, 'message' => 'No Data Found.']);
                }
            }else{
                return response()->json(['status' => 401, 'message' => 'Not Authorized.']);
            }        
        }
    /** View */

    /** update */
        public function update(Request $request)
        {
            if(auth()->user()->can('allocation-edit')){
                $rules = [
                    'id' => 'required',
                ];

                $validator = Validator::make($request->all(), $rules);

                if ($validator->fails())
                    return response()->json(['status' => 422, 'message' => $validator->errors()]);

                $crud = [
                    'billing_date' => Date('Y-m-d'),
                    'finance_id' => $request->finance_id,
                    'finance_branch_id' => $request->finance_branch_id,
                    'dsa_or_broker_id' => $request->dsa_or_broker_id,
                    'disb_amount' => $request->disb_amount,
                    'payment_due'  => $request->payment_due,
                    'fatd'  => $request->fatd,
                    'iatd'  => $request->iatd,
                    'lead_id' => $request->lead_id,
                    'tentetive_delivery_date'  => $request->tentetive_delivery_date,
                    'booking_amount'  => $request->booking_amount,
                    'updated_at' => date('Y-m-d H:i:s'),
                    'updated_by' => auth('sanctum')->user()->id
                ];
                $update = Allocation::where(['id' => $request->id])->update($crud);
                if ($update)
                    return response()->json(['status' => 200, 'message' => 'Record updated successfully']);
                else
                    return response()->json(['status' => 201, 'message' => 'Faild to update record']);
            }else{
                return response()->json(['status' => 401, 'message' => 'Not Authorized.']);
            }
        }
    /** update */

    
    /** change-status */
        public function change_status(Request $request)
        {
            if(auth()->user()->can('allocation-delete')){
                $rules = [
                    'id' => 'required',
                    'status' => 'required'
                ];

                $validator = Validator::make($request->all(), $rules);

                if ($validator->fails())
                    return response()->json(['status' => 422, 'message' => $validator->errors()]);

                $data = Allocation::where(['id' => $request->id])->first();

                if (!empty($data)) {
                    // If Request is Rejected
                        if($data->status == 'rejected'){
                            $update = Allocation::where(['id' => $request->id])
                                    ->update(['status' => $request->status, 
                                            'reason' => $request->reason ?? '',
                                            'updated_at' => date('Y-m-d H:i:s'), 
                                            'updated_by' => auth('sanctum')->user()->id
                                        ]);
                            if ($update) {
                                return response()->json(['status' => 200, 'message' => 'Record status change successfully']);
                            } else {
                                return response()->json(['status' => 201, 'message' => 'Faild to update status']);
                            }
                        }
                    // If Request is Rejected

                    // If Request is Allocated
                        else if($data->status == 'allocated'){
                            
                            $update = Allocation::where(['id' => $request->id])
                                                ->update(['status' => $request->status,
                                                'reason' => NULL,
                                                'updated_at' => date('Y-m-d H:i:s'),
                                                'updated_by' => auth('sanctum')->user()->id]);
                            if ($update) {
                                $obf_temporary_id = Allocation::select('obf_temporary_id')->where('id',$request->id)->first();
                                $obf_id = OBF::select('id' ,'branch_id')->where('temporary_id' ,$obf_temporary_id->obf_temporary_id)->first();

                                $order_crud = [
                                    'order_id' => 'Order_ID'.Date('YmdHis'),
                                    'obf_id' => $obf_id->id,
                                    'branch_id' => $obf_id->branch_id,
                                    'created_by' => auth('sanctum')->user()->id,
                                    'created_at' => date('Y-m-d H:i:s'),
                                ];

                                $order = DB::table('orders')->insertGetId($order_crud);
                                if($order){
                                    return response()->json(['status' => 200, 'message' => 'Record status change successfully']);
                                }else{
                                    return response()->json(['status' => 201, 'message' => 'Faild to update status']);
                                }
                            } else {
                                return response()->json(['status' => 201, 'message' => 'Faild to update status']);
                            }
                        }
                    // If Request is Allocated

                    // If Request is Pending
                        else{
                            $update = Allocation::where(['id' => $request->id])
                                                ->update(['status' => $request->status,
                                                'reason' => '',
                                                'updated_at' => date('Y-m-d H:i:s'),
                                                'updated_by' => auth('sanctum')->user()->id]);
                            if ($update) {
                                return response()->json(['status' => 200, 'message' => 'Record status change successfully']);
                            } else {
                                return response()->json(['status' => 201, 'message' => 'Faild to update status']);
                            }
                        }
                    // If Request is Pending
                   
                } else {
                    return response()->json(['status' => 201, 'message' => 'Somthing went wrong !']);
                }
            }else{
                return response()->json(['status' => 401, 'message' => 'Not Authorized.']);
            }
        }
    /** change-status */
}
