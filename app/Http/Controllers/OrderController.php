<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
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
use App\Models\Order;
use Auth, DB, Mail, Validator, File, DataTables;

class OrderController extends Controller{
    /** construct */
        public function __construct(){
            $this->middleware('permission:orders-create', ['only' => ['create']]);
            $this->middleware('permission:orders-edit', ['only' => ['edit']]);
            $this->middleware('permission:orders-view', ['only' => ['view']]);
            $this->middleware('permission:orders-delete', ['only' => ['delete']]);
        }
    /** construct */

    /** index */
        public function index(Request $request){
            if($request->ajax()){
                $data = DB::table('orders as o')
                            ->select('o.id', 'o.order_id', 'b.name as branch_name')
                            ->leftjoin('branches as b', 'b.id', 'o.branch_id')
                            ->orderBy('o.id' ,'desc')
                            ->get();
                
                return Datatables::of($data)
                        ->addIndexColumn()
                        ->addColumn('action', function($data){
                            $return = '<div class="btn-group">';

                            if(auth()->user()->can('orders-view')){
                                $return .= '<a href="'.route('order.view', ['id' => base64_encode($data->id)]).'" class="btn btn-default btn-xs">
                                                <i class="fa fa-eye"></i>
                                            </a> &nbsp;';
                            }   

                            $return .= '</div>';

                            return $return;
                        })

                        ->rawColumns(['action'])
                        ->make(true);
            }
            
            return view('order.index');
        }
    /** index */

    /** view */
        public function view(Request $request){
            if(isset($request->id) && $request->id != '' && $request->id != null)
                $id = base64_decode($request->id);
            else
                return redirect()->back()->with('error', 'Something went wrong');

            $path = URL('/uploads/kyc').'/';

            $sales = User::role('sales')->select('id', DB::raw("CONCAT(first_name ,' ', last_name) AS sales_person_name"))->where(['status' => 'active'])->get();
            $product = Product::select('id', 'name', 'veriant')->where(['status' => 'active'])->get();
            $tax_1 = Tax::select('id', 'name', 'percentage')->where(['status' => 'active', 'name' => 'registration_tax'])->first();
            $tax_2 = Tax::select('id', 'name', 'percentage')->where(['status' => 'active', 'name' => 'municipal_tax'])->first();
            $tax_3 = Tax::select('id', 'name', 'percentage')->where(['status' => 'active', 'name' => 'tcs_tax'])->first();
            $accessory = Accessory::select('id', 'name', 'price')->where(['status' => 'active'])->get();
            $extanded_warranty = ExtandWarranty::select('id', 'years', 'amount')->where(['status' => 'active'])->get();
            $fasttag = Fasttag::select('id', 'tag_id', 'amount')->where(['status' => 'active'])->get();
            $branch = Branch::select('id', 'name', 'city')->where(['status' => 'active'])->get();
            $insurance = Insurance::select('id', 'name')->where(['status' => 'active'])->get();
            $finance = Finance::select('finance.id', 'finance.name', 'branches.name AS branch_name', 'branches.id AS branch_id')->leftjoin('branches', 'finance.branch_id', 'branches.id')->where(['finance.status' => 'active'])->get();
            $lead = Lead::select('id', 'name')->where(['status' => 'active'])->get();
            $car_exchange = CarExchange::select('car_exchange.id', 'car_exchange.price', 'cep.name AS product_name')->leftjoin('car_exchange_product AS cep' ,'car_exchange.product_id', 'cep.id')->get();

            $data = DB::table('orders as o')
                        ->select('o.id', 'o.order_id', 'b.name as branch_name',
                                'obf.id as obf_id', 'obf.sales_person_id', 'obf.temporary_id', 'obf.booking_date', 'obf.customer_name', 'obf.customer_type', 
                                'branches.name AS branch_name', 'branches.id AS branch_id', 'obf.company_name', 'obf.gst', 'obf.address', 'obf.registration',
                                'obf.email', 'obf.pan_number', 'obf.adhar_number', 'obf.licance_number', 'obf.contact_number', 'obf.dob', 'obf.nominee_name',
                                'obf.nominee_reletion', 'obf.nominee_age', 'obf.occupation', 'products.name AS product_name', 'obf.product_id', 'products.veriant',
                                'products.is_applicable_for_mcp', 'obf.exterior_color', 'obf.interior_color', 'obf.ex_showroom_price', 'registration_tax.percentage AS registration_tax',
                                'insurance.name AS insurance', 'obf.insurance_id', 'municipal_tax.percentage AS municipal_tax', 'tcs_tax.percentage AS tcs_tax', 
                                'accessories.name AS accessory_name', 'obf.accessory_id', 'obf.extanded_warranty_id', 'extand_warranties.years AS extand_warranties_years',
                                'extand_warranties.amount AS extand_warranties_amount', 'fasttags.tag_id AS tag_id', 'fasttags.amount AS fasttag_amount', 'fasttags.id AS fasttag_id',
                                'obf.trad_in_value', 'obf.on_road_price', 'obf.on_road_price_word', 'obf.on_road_price_word', 'obf.finance_id', 'finance.name AS finance_name',
                                'finance_branch.name AS finance_branch_name', 'lead.name AS lead_name', 'obf.lead_id', 'obf.booking_amount', 'obf.mode_of_payment', 'obf.status',
                                'obf.created_by', 'obf.updated_by', 'obf.created_at', 'obf.updated_at',
                                DB::raw("CONCAT(users.first_name, ' ', users.last_name) AS sales_person_name"),
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
                                        END as licance_image")
                            )
                        ->leftjoin('branches as b', 'b.id', 'o.branch_id')
                        ->leftjoin('obf', 'obf.id', 'o.obf_id')
                        ->leftjoin('users', 'obf.sales_person_id', 'users.id')
                        ->leftjoin('branches', 'obf.branch_id', 'branches.id')
                        ->leftjoin('products', 'obf.product_id', 'products.id')
                        ->leftjoin('taxes AS registration_tax', 'obf.registration_tax_id', 'registration_tax.id')
                        ->leftjoin('taxes AS municipal_tax', 'obf.municipal_tax_id', 'municipal_tax.id')
                        ->leftjoin('taxes AS tcs_tax', 'obf.tcs_tax_id', 'tcs_tax.id')
                        ->leftjoin('insurance', 'obf.insurance_id', 'insurance.id')
                        ->leftjoin('accessories','obf.accessory_id', 'accessories.id')
                        ->leftjoin('extand_warranties', 'obf.extanded_warranty_id', 'extand_warranties.id')
                        ->leftjoin('fasttags', 'obf.fasttag_id', 'fasttags.id')
                        ->leftjoin('finance', 'obf.finance_id', 'finance.id')
                        ->leftjoin('branches AS finance_branch', 'obf.finance_branch_id', 'finance_branch.id')
                        ->leftjoin('lead', 'obf.lead_id', 'lead.id')
                        ->where(['o.id' => $id])
                        ->first();

            return view('order.view')
                ->with(['data' => $data, 'sales' => $sales, 'product' => $product, 'taxOne' => $tax_1, 'taxTwo' => $tax_2, 'taxThree' => $tax_3, 
                        'accessory' => $accessory, 'extanded_warranty' => $extanded_warranty, 'fasttag' => $fasttag, 'branch' => $branch,
                        'insurance' => $insurance, 'car_exchange' => $car_exchange, 'finance' => $finance, 'lead' => $lead]);
        }
    /** view */
}
