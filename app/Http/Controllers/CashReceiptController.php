<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Accessory;
use App\Models\Allocation;
use App\Models\Approval;
use App\Models\Branch;
use App\Models\CarExchange;
use App\Models\ExtandWarranty;
use App\Models\Fasttag;
use App\Models\Finance;
use App\Models\Insurance;
use App\Models\Lead;
use App\Models\User;
use App\Models\Product;
use App\Models\Tax;
use DataTables ,DB;
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
            if($request->ajax()){
                $data = OBF::select('obf.id','obf.booking_date' ,'obf.customer_name' ,'products.name AS product_name' ,'obf.status')
                        ->leftjoin('products' ,'obf.product_id' ,'products.id')
                        ->where(['obf.status' => 'obf_accepted'])
                        ->orWhere(['obf.status' => 'account_accepted'])
                        ->get();
            
                        return Datatables::of($data)
                        ->addIndexColumn()
                        ->addColumn('action', function($data){
                            $return = '<div class="btn-group">';

                            if(auth()->user()->can('cash_receipt-view')){
                                $return .= '<a href="'.route('cash_receipt.view', ['id' => base64_encode($data->id)]).'" class="btn btn-default btn-xs">
                                                <i class="fa fa-eye"></i>
                                            </a> &nbsp;';
                            }  

                            if (auth()->user()->can('cash_receipt-delete')) {
                                $return .= '<a href="javascript:;" class="btn btn-default btn-xs dropdown-toggle" data-toggle="dropdown">
                                                    <i class="fa fa-bars"></i>
                                                </a> &nbsp;
                                                <ul class="dropdown-menu">
                                                    <li><a class="dropdown-item" href="javascript:;" onclick="change_status(this);" data-status="account_accepted" data-id="' . base64_encode($data->id) . '">Account Accepted</a></li>
                                                    <li><a class="dropdown-item" href="javascript:;" onclick="change_status_reject(this);" data-status="account_rejected" data-id="' . base64_encode($data->id) . '">Account Rejected</a></li>
                                                </ul>';
                            }
                            if (auth()->user()->can('cash_receipt-generate_gate_pass')) {
                                $return .= '<a title="Generate Cash Receipt" href="'.route('cash_receipt.generate_cash_receipt', ['id' => base64_encode($data->id)]).'" class="btn btn-default btn-xs">
                                <i class="fas fa-file-pdf"></i> 
                                </a> &nbsp;';
                            }

                            $return .= '</div>';

                            return $return;
                        })

                        ->editColumn('name', function($data) {
                            return $data->customer_name;
                        })
                        ->editColumn('model', function($data) {
                            return $data->product_name;
                        })
                        ->editColumn('status', function ($data) {
                            if ($data->status == 'accepted') {
                                return '<span class="badge badge-pill badge-success">Active</span>';
                            } else if ($data->status == 'pending') {
                                return '<span class="badge badge-pill badge-warning">Pending</span>';
                            } else if ($data->status == 'deleted') {
                                return '<span class="badge badge-pill badge-danger">Deleted</span>';
                            }else if ($data->status == 'account_rejected') {
                                return '<span class="badge badge-pill badge-danger">Account Rejected</span>';
                            }else if ($data->status == 'obf_rejected') {
                                return '<span class="badge badge-pill badge-danger">OBF Rejected</span>';
                            }else if ($data->status == 'rejected') {
                                return '<span class="badge badge-pill badge-danger">Rejected</span>';
                            }else if ($data->status == 'obf_accepted') {
                                return '<span class="badge badge-pill badge-success">Obf Accepted</span>';
                            }else if ($data->status == 'account_accepted') {
                                return '<span class="badge badge-pill badge-success">Account Accepted</span>';
                            }
                        })

                        ->rawColumns(['name', 'action' ,'status' ,'model'])
                        ->make(true);
            }
            return view('cash_receipt.index');
        }
    /** index */

    /** view */
        public function view(Request $request){
            if(auth()->user()->can('cash_receipt-view')){

                $sales = User::role('sales')->select('id' , DB::raw("CONCAT(first_name,' ',last_name) AS sales_person_name"))->where(['status' => 'active'])->get();
                $product = Product::select('id' , 'name' ,'veriant')->where(['status' => 'active'])->get();
                $tax_1 = Tax::select('id' , 'name' ,'percentage')->where(['status' => 'active' ,'name' => 'registration_tax'])->first();
                $tax_2 = Tax::select('id' , 'name' ,'percentage')->where(['status' => 'active' ,'name' => 'municipal_tax'])->first();
                $tax_3 = Tax::select('id' , 'name' ,'percentage')->where(['status' => 'active' ,'name' => 'tcs_tax'])->first();
                $accessory = Accessory::select('id' , 'name' ,'price')->where(['status' => 'active'])->get();
                $extanded_warranty = ExtandWarranty::select('id' , 'years' ,'amount')->where(['status' => 'active'])->get();
                $fasttag = Fasttag::select('id' , 'tag_id' ,'amount')->where(['status' => 'active'])->get();
                $branch = Branch::select('id' , 'name' ,'city')->where(['status' => 'active'])->get();
                $insurance = Insurance::select('id' , 'name')->where(['status' => 'active'])->get();
                $finance = Finance::select('finance.id' ,'finance.name' ,'branches.name AS branch_name' ,'branches.id AS branch_id')->leftjoin('branches' ,'finance.branch_id' ,'branches.id')->where(['finance.status' => 'active'])->get();
                $lead = Lead::select('id' ,'name')->where(['status' => 'active'])->get();
                $car_exchange = CarExchange::select('car_exchange.id' ,'car_exchange.price','cep.name AS product_name')->leftjoin('car_exchange_product AS cep' ,'car_exchange.product_id' ,'cep.id')->get();
                $data = OBF::select('obf.id','obf.sales_person_id',
                DB::raw("CONCAT(users.first_name,' ',users.last_name) AS sales_person_name"),'obf.temporary_id' ,'obf.booking_date' ,'obf.customer_name' ,'obf.customer_type' ,'branches.name AS branch_name' ,'branches.id AS branch_id' ,'obf.company_name' ,'obf.gst' ,'obf.address' ,'obf.registration' ,'obf.email' ,'obf.pan_number','obf.adhar_number' ,'obf.licance_number','obf.contact_number' ,'obf.dob' ,'obf.nominee_name','obf.nominee_reletion' ,'obf.nominee_age' ,'obf.occupation','products.name AS product_name','obf.product_id' ,'products.veriant','products.is_applicable_for_mcp' ,'obf.exterior_color' ,'obf.interior_color' ,'obf.ex_showroom_price' ,'registration_tax.percentage AS registration_tax' ,'insurance.name AS insurance' ,'obf.insurance_id' ,'municipal_tax.percentage AS municipal_tax' ,'tcs_tax.percentage AS tcs_tax' ,'accessories.name AS accessory_name' ,'obf.accessory_id','obf.extanded_warranty_id' ,'extand_warranties.years AS extand_warranties_years' ,'extand_warranties.amount AS extand_warranties_amount' ,'fasttags.tag_id AS tag_id' ,'fasttags.amount AS fasttag_amount' ,'fasttags.id AS fasttag_id','obf.trad_in_value' ,'obf.on_road_price' ,'obf.on_road_price_word' ,'obf.on_road_price_word','obf.finance_id' ,'finance.name AS finance_name' ,'finance_branch.name AS finance_branch_name' ,'lead.name AS lead_name','obf.lead_id' ,'obf.booking_amount' ,'obf.mode_of_payment' ,'obf.status' ,'obf.created_by' ,'obf.updated_by','obf.created_at' ,'obf.updated_at',
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
                        ->where(['obf.id' => $id])
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
            if($request->ajax()){
                if(isset($request->id) && $request->id != null){
                    $id = base64_decode($request->id);
                }else{
                    return response()->json(['status' => 201, 'message' => 'Id not found!']);
                }


                DB::beginTransaction();
                try {
                    $data = OBF::where(['id' => $id])->first();

                    if(!empty($data)){

                        if($request->status == 'account_rejected'){
                            $update = OBF::where(['id' => $id])->update(['status' => $request->status, 'reason' => $request->reason,'updated_at' => date('Y-m-d H:i:s'), 'updated_by' => auth()->user()->id]);
                            if($update){

                                DB::commit();
                                return response()->json(['code' => 200]);
                            }else{
                                DB::rollback();
                                return response()->json(['code' => 201]);
                            }
                        }else if($request->status == 'account_accepted'){
                            $update = OBF::where(['id' => $id])->update(['status' => $request->status, 'updated_at' => date('Y-m-d H:i:s'), 'updated_by' => auth()->user()->id]);

                            $order_crud = [
                                'order_id' => 'Order_ID'.Date('YmdHis'),
                                'obf_id' => $data->id,
                                'branch_id' => $data->branch_id,
                                'created_by' => auth()->user()->id,
                                'created_at' => date('Y-m-d H:i:s'),
                            ];
                            $order = DB::table('orders')->insertGetId($order_crud);
                            if($order){
                                DB::commit();
                                return response()->json(['code' => 200]);
                            }else{
                                DB::rollback();
                                return response()->json(['code' => 201]);
                            }
                        }else{
                            $update = OBF::where(['id' => $id])->update(['status' => $request->status, 'updated_at' => date('Y-m-d H:i:s'), 'updated_by' => auth()->user()->id]);
                            if($update){

                                DB::commit();
                                return response()->json(['code' => 200]);
                            }else{
                                DB::rollback();
                                return response()->json(['code' => 201]);
                            }
                        }
                    }else{
                        DB::rollback();
                        return response()->json(['code' => 201]);
                    }
                } catch (\Throwable $th) {
                    DB::rollback();
                    return response()->json(['code' => 201]);
                }
            }
        }
    /** change-status */

    /** Cash-receipt */
        public function cash_receipt(Request $request){

            $data = OBF::select('obf.id')
                    ->where(['id' => $request->id])
                    ->first();
            if($data){
                
                return view('cash_receipt.cash_receipt')->with(['data' => $data]);
            }else{
                return response()->json(['status' => 404 ,'message' => 'No record found!']);
            }

        }
    /** Cash-receipt */

    /** Generate Cash-receipt */
        public function generate_cash_receipt(Request $request){
            $id = base64_decode($request->id);
            $path = URL('/uploads/kyc').'/';
            $sales = User::role('sales')->select('id' , DB::raw("CONCAT(first_name,' ',last_name) AS sales_person_name"))->where(['status' => 'active'])->get();
            $product = Product::select('id' , 'name' ,'veriant')->where(['status' => 'active'])->get();
            $tax_1 = Tax::select('id' , 'name' ,'percentage')->where(['status' => 'active' ,'name' => 'registration_tax'])->first();
            $tax_2 = Tax::select('id' , 'name' ,'percentage')->where(['status' => 'active' ,'name' => 'municipal_tax'])->first();
            $tax_3 = Tax::select('id' , 'name' ,'percentage')->where(['status' => 'active' ,'name' => 'tcs_tax'])->first();
            $accessory = Accessory::select('id' , 'name' ,'price')->where(['status' => 'active'])->get();
            $extanded_warranty = ExtandWarranty::select('id' , 'years' ,'amount')->where(['status' => 'active'])->get();
            $fasttag = Fasttag::select('id' , 'tag_id' ,'amount')->where(['status' => 'active'])->get();
            $branch = Branch::select('id' , 'name' ,'city')->where(['status' => 'active'])->get();
            $insurance = Insurance::select('id' , 'name')->where(['status' => 'active'])->get();
            $finance = Finance::select('finance.id' ,'finance.name' ,'branches.name AS branch_name' ,'branches.id AS branch_id')->leftjoin('branches' ,'finance.branch_id' ,'branches.id')->where(['finance.status' => 'active'])->get();
            $lead = Lead::select('id' ,'name')->where(['status' => 'active'])->get();
            $car_exchange = CarExchange::select('car_exchange.id' ,'car_exchange.price','cep.name AS product_name')->leftjoin('car_exchange_product AS cep' ,'car_exchange.product_id' ,'cep.id')->get();
            $data = OBF::select('obf.id','obf.sales_person_id',
            DB::raw("CONCAT(users.first_name,' ',users.last_name) AS sales_person_name"),'obf.temporary_id' ,'obf.booking_date' ,'obf.customer_name' ,'obf.customer_type' ,'branches.name AS branch_name' ,'branches.id AS branch_id' ,'obf.company_name' ,'obf.gst' ,'obf.address' ,'obf.registration' ,'obf.email' ,'obf.pan_number','obf.adhar_number' ,'obf.licance_number','obf.contact_number' ,'obf.dob' ,'obf.nominee_name','obf.nominee_reletion' ,'obf.nominee_age' ,'obf.occupation','products.name AS product_name','obf.product_id' ,'products.veriant','products.is_applicable_for_mcp' ,'obf.exterior_color' ,'obf.interior_color' ,'obf.ex_showroom_price' ,'registration_tax.percentage AS registration_tax' ,'insurance.name AS insurance' ,'obf.insurance_id' ,'municipal_tax.percentage AS municipal_tax' ,'tcs_tax.percentage AS tcs_tax' ,'accessories.name AS accessory_name' ,'obf.accessory_id','obf.extanded_warranty_id' ,'extand_warranties.years AS extand_warranties_years' ,'extand_warranties.amount AS extand_warranties_amount' ,'fasttags.tag_id AS tag_id' ,'fasttags.amount AS fasttag_amount' ,'fasttags.id AS fasttag_id','obf.trad_in_value' ,'obf.on_road_price' ,'obf.on_road_price_word' ,'obf.on_road_price_word','obf.finance_id' ,'finance.name AS finance_name' ,'finance_branch.name AS finance_branch_name' ,'lead.name AS lead_name','obf.lead_id' ,'obf.booking_amount' ,'obf.mode_of_payment' ,'obf.status' ,'obf.created_by' ,'obf.updated_by','obf.created_at' ,'obf.updated_at',
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
                    ->where(['obf.id' => $id])
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
