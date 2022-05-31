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
use App\Models\OBF;
use App\Models\User;
use App\Models\Product;
use App\Models\Tax;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Auth, DB, Mail, Validator, File, DataTables;

class ObfController extends Controller{
    /** construct */
        public function __construct(){
            $this->middleware('permission:obf-create', ['only' => ['create']]);
            $this->middleware('permission:obf-edit', ['only' => ['edit']]);
            $this->middleware('permission:obf-view', ['only' => ['view']]);
            $this->middleware('permission:obf-delete', ['only' => ['delete']]);
        }
    /** construct */
    
     /** index */
        public function index(Request $request){
            if($request->ajax()){

                $data = OBF::select('obf.id', 'obf.customer_name', 'obf.booking_date' ,'products.name AS product','obf.status')
                        ->leftjoin('products' ,'obf.product_id' ,'products.id')
                        ->orderBy('id', 'desc')
                        ->get();

                return Datatables::of($data)
                        ->addIndexColumn()
                        ->addColumn('action', function ($data) {
                            $return = '<div class="btn-group">';
    
                            if (auth()->user()->can('obf-view')) {
                                $return .=  '<a href="'.route('obf.view', ['id' => base64_encode($data->id)]).'" class="btn btn-default btn-xs">
                                                    <i class="fa fa-eye"></i>
                                                </a> &nbsp;';
                            }
    
                            if (auth()->user()->can('obf-edit')) {
                                $return .= '<a href="'.route('obf.edit', ['id' => base64_encode($data->id)]).'" class="btn btn-default btn-xs">
                                                    <i class="fa fa-edit"></i>
                                                </a> &nbsp;';
                            }
    
                            if (auth()->user()->can('obf-delete')) {
                                $return .= '<a href="javascript:;" class="btn btn-default btn-xs dropdown-toggle" data-toggle="dropdown">
                                                    <i class="fa fa-bars"></i>
                                                </a> &nbsp;
                                                <ul class="dropdown-menu">
                                                    <li><a class="dropdown-item" href="javascript:;" onclick="change_status(this);" data-status="pending" data-id="' . base64_encode($data->id) . '">Pending</a></li>
                                                    <li><a class="dropdown-item" href="javascript:;" onclick="change_status(this);" data-status="delete" data-id="' . base64_encode($data->id) . '">Delete</a></li>
                                                </ul>';
                            }
    
                            $return .= '</div>';
    
                            return $return;
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
                        ->rawColumns(['action' ,'status'])
                        ->make(true);
                }
            return view('obf.index');
        }
    /** index */
    
    /** Create */
        public function create(Request $request){
            
            $sales = User::role('sales')->select('id' , DB::raw("CONCAT(first_name,' ',last_name) AS sales_person_name"))->where(['status' => 'active'])->get();
        
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
            $car_exchange = CarExchange::select('car_exchange.id','car_exchange.price','cep.name AS product_name')->leftjoin('car_exchange_product AS cep' ,'car_exchange.product_id' ,'cep.id')->get();

            return view('obf.create')->with(['sales' => $sales ,'product' => $product ,'taxOne' => $tax_1,'taxTwo' => $tax_2 ,'taxThree' => $tax_3,'accessory' => $accessory ,'extanded_warranty' => $extanded_warranty ,'fasttag' => $fasttag ,'branch' => $branch ,'insurance' => $insurance ,'car_exchange' => $car_exchange ,'finance' => $finance ,'lead' => $lead]);
        }
    /** Create */
    
    /** insert */
        public function insert(Request $request){
            if(request()->ajax()){
                return true;
            } 
                $obf = OBF::count();
                $temp_number = sprintf('%04d',$obf+1);

                $crud = [
                    'temporary_id' => $temp_number,
                    'booking_date' => $request->booking_date,
                    'customer_name' => $request->customer_name,
                    'customer_type' => $request->customer_type,
                    'branch_id' => $request->branch,
                    'company_name' => $request->company_name ?? null,
                    'gst' => $request->gst ?? null,
                    'address' => $request->address_1,
                    'registration' => $request->address_2,
                    'email' => $request->email,
                    'pan_number' => $request->pan_number,
                    'adhar_number' => $request->adhar_number,
                    'licance_number' => $request->license_number,
                    'contact_number' => $request->contact_number,
                    'dob' => $request->dob,
                    'nominee_name' => $request->nominee_name,
                    'nominee_reletion' => $request->nominee_relation,
                    'nominee_age' => $request->nominee_age,
                    'occupation' => $request->occupation,
                    'sales_person_id' => $request->sales_person_name,
                    'product_id' => $request->model,
                    'varient_id' => $request->veriant,
                    'exterior_color' => $request->exterior_color,
                    'interior_color' => $request->interior_color,
                    'ex_showroom_price' => $request->ex_showroom_price,
                    'registration_tax_id' => $request->registration_tax,
                    'insurance_id' => $request->insurance,
                    'municipal_tax_id' => $request->municipal_tax,
                    'tcs_tax_id' => $request->tcs_tax,
                    'accessory_id' => implode(" ,",$request->accessories),
                    'extanded_warranty_id' => $request->extended_warranty,
                    'fasttag_id' => $request->fastag,
                    'trad_in_value' => $request->trade_in_value,
                    'on_road_price' => $request->on_road_price,
                    'on_road_price_word' => $request->on_road_price_word,
                    'finance_id' => $request->finance_name,
                    'finance_branch_id' => $request->finance_branch_name,
                    'lead_id' => $request->lead,
                    'booking_amount' => $request->booking_amount,
                    'mode_of_payment' => $request->mode_of_payment,
                    'status' => 'pending',
                    'created_at' => date('Y-m-d H:i:s'),
                    'created_by' => auth()->user()->id,
                    'updated_at' => date('Y-m-d H:i:s'),
                    'updated_by' => auth()->user()->id
                ];
                // dd($request->file('licance_image'));
                // Pan Image
                    if(!empty($request->file('pan_image'))){
                        $file = $request->file('pan_image');
                        $filenameWithExtension = $request->file('pan_image')->getClientOriginalName();
                        $filename = pathinfo($filenameWithExtension, PATHINFO_FILENAME);
                        $extension = $request->file('pan_image')->getClientOriginalExtension();
                        $filenameToStore = "pan_".time()."_".$filename.'.'.$extension;
        
                        $folder_to_upload = public_path().'/uploads/kyc/';
        
                        if(!\File::exists($folder_to_upload))
                            \File::makeDirectory($folder_to_upload, 0777, true, true);
        
                        $crud['pan_image'] = $filenameToStore;
                    }else{
                        $crud['pan_image'] = 'default.jpg';
                    }
                // Pan Image

                // Aadhar Image
                    if(!empty($request->file('adhar_image'))){
                        $file_two = $request->file('adhar_image');
                        $filenameWithExtension_two = $request->file('adhar_image')->getClientOriginalName();
                        $filename_two = pathinfo($filenameWithExtension_two, PATHINFO_FILENAME);
                        $extension_two = $request->file('adhar_image')->getClientOriginalExtension();
                        $filenameToStore_two = "adhar_".time()."_".$filename_two.'.'.$extension_two;
        
                        $folder_to_upload = public_path().'/uploads/kyc/';
        
                        if(!\File::exists($folder_to_upload))
                            \File::makeDirectory($folder_to_upload, 0777, true, true);
        
                        $crud['adhar_image'] = $filenameToStore_two;
                    }else{
                        $crud['adhar_image'] = 'default.jpg';
                    }
                // Aadhar Image

                // Licance Image
                    if(!empty($request->file('licance_image'))){
                        $file_three = $request->file('licance_image');
                        $filenameWithExtension_three = $request->file('licance_image')->getClientOriginalName();
                        $filename_three = pathinfo($filenameWithExtension_three, PATHINFO_FILENAME);
                        $extension_three = $request->file('licance_image')->getClientOriginalExtension();
                        $filenameToStore_three = "licance_".time()."_".$filename_three.'.'.$extension_three;
        
                        $folder_to_upload = public_path().'/uploads/kyc/';
        
                        if(!\File::exists($folder_to_upload))
                            \File::makeDirectory($folder_to_upload, 0777, true, true);
        
                        $crud['licance_image'] = $filenameToStore_three;
                    }else{
                        $crud['licance_image'] = 'default.jpg';
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
                                $file_two->move($folder_to_upload, $filenameToStore_two);
                            }
                            if (!empty($request->file('licance_image'))){
                                $file_three->move($folder_to_upload, $filenameToStore_three);
                            }
                            // Move Files to Folder
                            DB::commit();
                            return redirect()->route('obf')->with('success', 'Record inserted successfully');
                    } else {
                        DB::rollback();
                        return redirect()->back()->with('error', 'Failed to insert record')->withInput();
                    }
                }catch (\Throwable $th){
                    DB::rollback();
                    return redirect()->back()->with('error', 'Something went wrong, please try again later')->withInput();
                }
            
        }
    /** insert */

    /** view */
        public function view(Request $request){
            if(auth()->user()->can('obf-view')){
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
            }
            return view('obf.view')->with(['data' =>$data,'sales' => $sales ,'product' => $product ,'taxOne' => $tax_1,'taxTwo' => $tax_2 ,'taxThree' => $tax_3,'accessory' => $accessory ,'extanded_warranty' => $extanded_warranty ,'fasttag' => $fasttag ,'branch' => $branch ,'insurance' => $insurance ,'car_exchange' => $car_exchange ,'finance' => $finance ,'lead' => $lead]);
        }
    /** view */

    /** Edit */
        public function edit(Request $request){
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
            
            return view('obf.edit')->with(['data' =>$data,'sales' => $sales ,'product' => $product ,'taxOne' => $tax_1,'taxTwo' => $tax_2 ,'taxThree' => $tax_3,'accessory' => $accessory ,'extanded_warranty' => $extanded_warranty ,'fasttag' => $fasttag ,'branch' => $branch ,'insurance' => $insurance ,'car_exchange' => $car_exchange ,'finance' => $finance ,'lead' => $lead]);
        }
    /** Edit */

    /** update */
        public function update(Request $request){
            if($request->ajax()){ 
                return true;
            }

            if(isset($request->id)){
                $ext_record = OBF::find($request->id);
            }

            $crud = [
                'booking_date' => $request->booking_date,
                'customer_name' => $request->customer_name,
                'customer_type' => $request->customer_type,
                'branch_id' => $request->branch,
                'company_name' => $request->company_name ?? null,
                'gst' => $request->gst ?? null,
                'address' => $request->address_1,
                'registration' => $request->address_2,
                'email' => $request->email,
                'pan_number' => $request->pan_number,
                'adhar_number' => $request->adhar_number,
                'licance_number' => $request->license_number,
                'contact_number' => $request->contact_number,
                'dob' => $request->dob,
                'nominee_name' => $request->nominee_name,
                'nominee_reletion' => $request->nominee_relation,
                'nominee_age' => $request->nominee_age,
                'occupation' => $request->occupation,
                'sales_person_id' => $request->sales_person_name,
                'product_id' => $request->model,
                'varient_id' => $request->veriant,
                'exterior_color' => $request->exterior_color,
                'interior_color' => $request->interior_color,
                'ex_showroom_price' => $request->ex_showroom_price,
                'registration_tax_id' => $request->registration_tax,
                'insurance_id' => $request->insurance,
                'municipal_tax_id' => $request->municipal_tax,
                'tcs_tax_id' => $request->tcs_tax,
                'accessory_id' => implode(" ,",$request->accessories),
                'extanded_warranty_id' => $request->extended_warranty,
                'fasttag_id' => $request->fastag,
                'trad_in_value' => $request->trade_in_value,
                'on_road_price' => $request->on_road_price,
                'on_road_price_word' => $request->on_road_price_word,
                'finance_id' => $request->finance_name,
                'finance_branch_id' => $request->finance_branch_name,
                'lead_id' => $request->lead,
                'booking_amount' => $request->booking_amount,
                'mode_of_payment' => $request->mode_of_payment,
                'status' => 'pending',
                'created_at' => date('Y-m-d H:i:s'),
                'created_by' => auth()->user()->id,
                'updated_at' => date('Y-m-d H:i:s'),
                'updated_by' => auth()->user()->id
            ];
            // dd($request->file('pan_image'));
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
                    $data['pan_image'] = $ext_record->pan_image;
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
                    $data['adhar_image'] = $ext_record->adhar_image;
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
                    $data['licance_image'] = $ext_record->licance_image;
                }


                DB::beginTransaction();
                try {
                    $update = OBF::where(['id' => $request->id])->update($crud);
                    
                    if ($update) {
                        // Move Files to Folder
                            if (!empty($request->file('pan_image'))){
                                $file->move($folder_to_upload, $filenameToStore);
                            }
                            if (!empty($request->file('adhar_image'))){
                                $file_two->move($folder_to_upload, $filenameToStore_two);
                            }
                            if (!empty($request->file('licance_image'))){
                                $file_three->move($folder_to_upload, $filenameToStore_three);
                            }
                            // Move Files to Folder
                            DB::commit();
                        return redirect()->route('obf')->with('success', 'Record updated successfully');
                    } else {
                        DB::rollback();
                        return redirect()->back()->with('error', 'Failed to update record')->withInput();
                    }
                }catch (\Throwable $th){
                    DB::rollback();
                    return redirect()->back()->with('error', 'Something went wrong, please try again later')->withInput();
                }
        }
    /** update */

    /** change-status */
        public function change_status(Request $request){
            if (!$request->ajax()) { exit('No direct script access allowed'); }
            $id = base64_decode($request->id);
            $data = OBF::where(['id' => $id])->first();

            if (!empty($data)) {
                $update = OBF::where(['id' => $id])->update(['status' => $request->status, 'updated_at' => date('Y-m-d H:i:s'), 'updated_by' => auth()->user()->id]);
                if ($update) {
                    return response()->json(['code' => 200]);
                } else {
                    return response()->json(['code' => 201]);
                }
            } else {
                return response()->json(['code' => 201]);
            }
            
        }
    /** change-status */

    /** remove-profile */
        public function obf_profile_remove(Request $request){
            if(!$request->ajax()) { exit('No direct script access allowed'); }

            if(!empty($request->all())){
                $id = base64_decode($request->id);
                $data = OBF::find($id);

                if($data){
                    if($data->pan_image != ''){
                        $file_path = public_path().'/uploads/kyc/'.$data->pan_image;

                        if(File::exists($file_path) && $file_path != ''){
                            if($data->pan_image != 'user-icon.jpg') {
                                @unlink($file_path);
                            }
                        }

                        $update = OBF::where(['id' => $id])->update(['pan_image' => '']);

                        if($update)
                            return response()->json(['code' => 200]);
                        else
                            return response()->json(['code' => 201]);
                    }else{
                        return response()->json(['code' => 200]);
                    }
                }else{
                    return response()->json(['code' => 201]);
                }
            }else{
                return response()->json(['code' => 201]);
            }
        }
    /** remove-profile */
}
