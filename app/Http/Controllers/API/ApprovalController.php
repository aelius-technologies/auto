<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Approval;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Validator , DB;

class ApprovalController extends Controller
{
    /** construct */
        public function __construct(){
            $this->middleware('permission:approval-create', ['only' => ['create']]);
            $this->middleware('permission:approval-edit', ['only' => ['edit']]);
            $this->middleware('permission:approval-view', ['only' => ['view']]);
            $this->middleware('permission:approval-delete', ['only' => ['delete']]);
        }
    /** construct */
    /** index */
        public function index(Request $request){
            if(auth()->user()->can('approval-view')){

                $data = Approval::select('approval.*' ,'categories.name AS category_name','products.name AS product_name' ,'products.veriant' )
                ->leftjoin('obf' ,'approval.obf_id' ,'obf.id')
                ->leftjoin('products' ,'obf.product_id' ,'products.id')
                ->leftjoin('categories' ,'products.category_id' ,'categories.id')
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
            if(auth()->user()->can('approval-view')){
                $data = Approval::select('approval.id','obf.booking_date' ,'obf.delivery_date' ,'obf.customer_name' ,'obf.place' ,'obf.company_name' ,'obf.gst' ,'obf.address' ,'obf.registration' ,'obf.email' ,'obf.pan' ,'obf.contact_number' ,'obf.dob' ,'obf.nominee_name' ,'obf.nominee_reletion' ,'obf.nominee_age' ,'obf.occupation' ,'obf.manufacture_year' ,'obf.exterior_color' ,'obf.interior_color' ,'obf.ex_showroom_price' ,'registration_tax.percentage AS registration_tax', 'insurance.name AS insurance' ,'local_tax.percentage AS local_tax' ,'tcs_tax.percentage AS tcs_tax' ,'accessories.name AS accessory_name' ,'accessories.price AS accessory_price' ,'extand_warranties.years AS extand_warranties_years' ,'extand_warranties.amount AS extand_warranties_amount' ,'fasttags.tag_id AS fast_tag_id' ,'fasttags.amount AS fast_tag_amount' ,'obf.road_side_assist' ,'obf.trad_in_value' ,'obf.on_road_price' ,'obf.on_road_price_word' ,'finance.name AS finance_name' ,'branches.name AS branch_name' ,'finance.dsa_or_broker' ,'obf.disb_amount' ,'obf.payment_due' ,'obf.fatd' ,'obf.iatd' ,'lead.name AS lead_name' ,'special_registration_number.number AS special_registration_number' ,'obf.mode_of_payment' ,'obf.tentetive_delivery_date' ,'obf.booking_amount' ,'categories.name AS category_name','products.name AS product_name' ,'products.veriant',
                
                DB::raw("CONCAT(users.first_name,' ',users.last_name) AS sales_person_name")
                )
                                ->leftjoin('obf' ,'approval.obf_id' ,'obf.id')
                                ->leftjoin('users' ,'obf.sales_person_id' ,'users.id')
                                ->leftjoin('taxes AS registration_tax' ,'obf.registration_tax_id' ,'registration_tax.id')
                                ->leftjoin('taxes AS local_tax' ,'obf.local_tax_id' ,'local_tax.id')
                                ->leftjoin('taxes AS tcs_tax' ,'obf.tcs_tax_id' ,'tcs_tax.id')
                                ->leftjoin('insurance' ,'obf.insurance_id' ,'insurance.id')
                                ->leftjoin('accessories' ,'obf.accessory_id' ,'accessories.id')
                                ->leftjoin('extand_warranties' ,'obf.extanded_warranty_id' ,'extand_warranties.id')
                                ->leftjoin('fasttags' ,'obf.fasttag_id' ,'fasttags.id')
                                ->leftjoin('finance' ,'obf.finance_id' ,'finance.id')
                                ->leftjoin('branches' ,'obf.finance_branch_id' ,'branches.id')
                                ->leftjoin('lead' ,'obf.lead_id' ,'lead.id')
                                ->leftjoin('special_registration_number' ,'obf.special_register_number_id' ,'special_registration_number.id')
                                ->leftjoin('products' ,'obf.product_id' ,'products.id')
                                ->leftjoin('categories' ,'products.category_id' ,'categories.id')
                                ->where('approval.id' , $request->id)
                                ->first();
                
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
            if(auth()->user()->can('approval-delete')){
                $rules = [
                    'id' => 'required',
                    'status' => 'required'
                ];

                $validator = Validator::make($request->all(), $rules);

                if($validator->fails())
                    return response()->json(['status' => 422, 'message' => $validator->errors()]);

                $data = Approval::where(['id' => $request->id])->first();
                if(!empty($data)){
                    
                    if($request->status == 'rejected'){

                        $update = Approval::where(['id' => $request->id])->update(['status' => $request->status,'reason' => $request->reason ,'updated_at' => date('Y-m-d H:i:s'), 'updated_by' => auth('sanctum')->user()->id]);
                        if($update){
                            return response()->json(['status' => 200 ,'message' => 'Record status change successfully']);
                        }else{
                            return response()->json(['status' => 201, 'message' => 'Faild to update status']);
                        }

                    }else if($request->status == 'accepted'){

                        $update = Approval::where(['id' => $request->id])->update(['status' => $request->status, 'updated_at' => date('Y-m-d H:i:s'), 'updated_by' => auth('sanctum')->user()->id]);

                        if($update){
                            $get_branch = OBF::select('branch_id')->where(['temporary_id' => $data->obf_temporary_id])->first();
                            $order_crud = [
                                'order_id' => 'Order_ID'.Date('YmdHis'),
                                'obf_id' => $data->obf_id,
                                'branch_id' => $get_branch->branch_id,
                            ];
                            $order = DB::table('orders')->insertGetId($order_crud);
                            if($order){
                                return response()->json(['status' => 200 ,'message' => 'Record status change successfully']);
                            }else{
                                return response()->json(['status' => 201, 'message' => 'Faild to update status']);
                            }
                        }else{
                            return response()->json(['status' => 201, 'message' => 'Faild to update status']);
                        }
                    }
                    
                }else{
                    return response()->json(['status' => 201, 'message' => 'Somthing went wrong !']);
                }
            }else{
                return response()->json(['status' => 401, 'message' => 'Not Authorized.']);
            }
        }
    /** change-status */
}
