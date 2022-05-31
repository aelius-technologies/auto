<?php

namespace App\Http\Controllers;

use App\Models\OBF;
use App\Models\ObfApproval;
use Illuminate\Http\Request;
use App\Http\Requests\ObfApprovalRequest;
use Auth, DB, Mail, Validator, File, DataTables;

class AccountApprovalController extends Controller{
    /** construct */
        public function __construct(){
            $this->middleware('permission:account_approval-create', ['only' => ['create']]);
            $this->middleware('permission:account_approval-edit', ['only' => ['edit']]);
            $this->middleware('permission:account_approval-view', ['only' => ['view']]);
            $this->middleware('permission:account_approval-delete', ['only' => ['delete']]);
        }
    /** construct */

    /** index */
        public function index(Request $request){
            if($request->ajax()){
                $data = OBF::select('obf.id' ,'obf.customer_name' ,'obf.booking_date' ,'obf.status','products.name AS product')->leftjoin('products' ,'obf.product_id' ,'products.id')->where('obf.status' ,'account_rejected')->get();
                
                return Datatables::of($data)
                        ->addIndexColumn()
                        ->addColumn('action', function($data){
                            $return = '<div class="btn-group">';

                            if(auth()->user()->can('account_approval-view')){
                                $return .= '<a href="'.route('account_approval.view', ['id' => base64_encode($data->id)]).'" class="btn btn-default btn-xs">
                                                <i class="fa fa-eye"></i>
                                            </a> &nbsp;';
                            }   

                            if (auth()->user()->can('account_approval-delete')) {
                                $return .= '<a href="javascript:;" class="btn btn-default btn-xs dropdown-toggle" data-toggle="dropdown">
                                                    <i class="fa fa-bars"></i>
                                                </a> &nbsp;
                                                <ul class="dropdown-menu">
                                                    <li><a class="dropdown-item" href="javascript:;" onclick="change_status(this);" data-status="account_accepted" data-id="' . base64_encode($data->id) . '">Accept</a></li>
                                                    <li><a class="dropdown-item" href="javascript:;" onclick="change_status_reject(this);" data-status="rejected" data-id="' . base64_encode($data->id) . '">Reject</a></li>
                                                </ul>';
                            }

                            $return .= '</div>';

                            return $return;
                        })

                        ->editColumn('name', function($data) {
                            return $data->customer_name;
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

                        ->rawColumns(['name', 'action' ,'status'])
                        ->make(true);
            }

            return view('account_approval.index');
        }
    /** index */

    /** view */
        public function view(Request $request){
            if(isset($request->id) && $request->id != null){
                $id = base64_decode($request->id);
            }else{
                return view('obf_approval')->with('error', 'No data found');
            }
            DB::enableQueryLog();
            $path = URL('/uploads/kyc').'/';

            $data = OBF::select('obf.id' ,'obf.temporary_id' ,'obf.booking_date' ,'obf.customer_name' ,'obf.customer_type' ,'branches.name AS branch_name' ,'obf.company_name' ,'obf.gst' ,'obf.address', 'obf.registration' ,'obf.email' ,'obf.pan_number' ,
            DB::Raw("CASE
                    WHEN ".'obf.pan_image'." != '' THEN CONCAT("."'".$path."'".", ".'obf.pan_image'.")
                    ELSE CONCAT("."'".$path."'".", 'user-icon.jpg')
                END as pan_image"),
            'obf.adhar_number',
            DB::Raw("CASE
                    WHEN ".'obf.adhar_image'." != '' THEN CONCAT("."'".$path."'".", ".'obf.adhar_image'.")
                    ELSE CONCAT("."'".$path."'".", 'user-icon.jpg')
                END as adhar_image"),
            'obf.licance_number',
            DB::Raw("CASE
                    WHEN ".'obf.licance_image'." != '' THEN CONCAT("."'".$path."'".", ".'obf.licance_image'.")
                    ELSE CONCAT("."'".$path."'".", 'user-icon.jpg')
                END as licance_image"),
            'obf.contact_number','obf.dob','obf.nominee_name' ,'obf.nominee_reletion' ,'obf.nominee_age' ,'obf.occupation','sales.first_name AS sales_person_name' ,'products.name AS product_name' ,'products.veriant','obf.exterior_color' ,'obf.interior_color' ,'obf.ex_showroom_price' ,'registration_tax.percentage AS registration_tax' ,'insurance.name AS insurance_name' ,'insurance.amount AS insurance_amount' ,'insurance.years AS insurance_years','municipal_tax.percentage AS municipal_tax' ,'tcs_tax.percentage AS tcs_tax','accessories.name AS accessory_name' ,'accessories.price AS accessory_price','extand_warranties.years AS warranty_years' ,'extand_warranties.amount AS warranty_amount' ,'fasttags.tag_id AS fasttag_id' ,'fasttags.amount AS fasttag_amount' ,'obf.trad_in_value' ,'obf.on_road_price','obf.on_road_price_word' ,'finance.name AS finance_name', 'finance_branch.name AS finance_branch' ,'lead.name AS lead_name' ,'obf.booking_amount' ,'obf.mode_of_payment' ,'obf.reason'
            )
            ->leftjoin('branches' ,'obf.branch_id' ,'branches.id')
            ->leftjoin('users AS sales' ,'obf.sales_person_id' ,'sales.id')
            ->leftjoin('products' ,'obf.product_id' ,'products.id')
            ->leftjoin('taxes AS registration_tax' ,'obf.registration_tax_id' ,'registration_tax.id')
            ->leftjoin('insurance' ,'obf.insurance_id' ,'insurance.id')
            ->leftjoin('taxes AS municipal_tax' ,'obf.municipal_tax_id' ,'municipal_tax.id')
            ->leftjoin('taxes AS tcs_tax' ,'obf.tcs_tax_id' ,'tcs_tax.id')
            ->leftjoin('accessories' ,'obf.accessory_id' ,'accessories.id')
            ->leftjoin('extand_warranties' ,'obf.extanded_warranty_id' ,'extand_warranties.id')
            ->leftjoin('fasttags' ,'obf.fasttag_id' ,'fasttags.id')
            ->leftjoin('finance' ,'obf.finance_id' ,'finance.id')
            ->leftjoin('branches AS finance_branch' ,'finance.branch_id' ,'finance_branch.id')
            ->leftjoin('lead' ,'obf.lead_id' ,'lead.id')
            ->where(['obf.id' => $id])->first();
            // dd(DB::getQueryLog());
            if($data)
                return view('account_approval.view')->with(['data' => $data]);
            else
                return redirect()->back()->with('error', 'No data found')->withInput();
        
        }
    /** view */

    /** change-status */
        public function change_status(ObfApprovalRequest $request){
            if($request->ajax()){
                if(isset($request->id) && $request->id != null){
                    $id = base64_decode($request->id);
                }else{
                    return response()->json(['status' => 201, 'message' => 'Id not found!']);
                }
                
                $data = OBF::where(['id' => $id])->first();

                if(!empty($data)){
                    DB::enableQueryLog();
                    if($request->status == 'rejected'){
                     
                        $update = OBF::where(['id' => $id])->update(['status' => $request->status, 'reason' => $request->reason,'updated_at' => date('Y-m-d H:i:s'), 'updated_by' => auth()->user()->id]);
                        if($update){
                            return response()->json(['code' => 200]);
                        }else{
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
                            return response()->json(['code' => 200 ]);
                        }else{
                            return response()->json(['code' => 201]);
                        }
                    }else{
                        $update = OBF::where(['id' => $id])->update(['status' => $request->status, 'updated_at' => date('Y-m-d H:i:s'), 'updated_by' => auth()->user()->id]);
                        dd('else');
                        if($update){
                            return response()->json(['code' => 200]);
                        }else{
                            return response()->json(['code' => 201]);
                        }
                    }
                }else{
                    return response()->json(['code' => 201, 'message' => 'Somthing went wrong !']);
                }
            }
        }
    /** change-status */
}
