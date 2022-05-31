<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Allocation;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Models\Inventory;
use App\Models\Order;
use App\Models\OBF;
use App\Models\Product;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use DB, Validator;

class InventoryController extends Controller
{
    /** construct */
        public function __construct(){
            $this->middleware('permission:inventory-create', ['only' => ['create']]);
            $this->middleware('permission:inventory-edit', ['only' => ['edit']]);
            $this->middleware('permission:inventory-view', ['only' => ['view']]);
            $this->middleware('permission:inventory-delete', ['only' => ['delete']]);
        }
    /** construct */
    
    /** index */
        public function index(Request $request)
        {
            if(auth()->user()->can('inventory-view')){
                $data = DB::table('inventory')
                            ->select('categories.name AS category_name','products.*')
                            ->leftjoin('categories' ,'products.category_id' ,'categories.id')
                            ->where('status' ,'!=' ,'sold')
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
    
    /** get_category */
        public function get_category(Request $request)
        {
            $category = Category::where('status' ,'active')->get();
            if(sizeof($category)){
                return response()->json(['status' => 200 ,'message' => 'Data Found' ,'data' => $category]);
            }else{
                return response()->json(['status' => 404, 'message' => 'No Data Found.']);
            }
        }
    /** get_category */
    
    /** get_product */
        public function get_product(Request $request)
        {
            $data = Product::where(['id' => $request->category_id,'status' => 'active'])->get();
            if(sizeof($data)){
                return response()->json(['status' => 200 ,'message' => 'Data Found' ,'data' => $data]);
            }else{
                return response()->json(['status' => 404, 'message' => 'No Data Found.']);
            }
        }
    /** get_product */
    
    /** get_veriant */
        public function get_veriant(Request $request)
        {
            $product = Product::select('name')->where('id' ,$request->id)->first();
            $data = Product::where(['name' => $product->name,'status' => 'active'])->get();
            if(sizeof($data)){
                return response()->json(['status' => 200 ,'message' => 'Data Found' ,'data' => $data]);
            }else{
                return response()->json(['status' => 404, 'message' => 'No Data Found.']);
            }
        }
    /** get_veriant */

    /** insert */
        public function insert(Request $request)
        {
            if(auth()->user()->can('inventory-create')){
                $rules = [
                    'category_id' => 'required',
                    'product_id' => 'required',
                    'veriant_id' => 'required',
                    'key_number' => 'required',
                    'engine_number' => 'required',
                    'chassis_number' => 'required',
                    'vin_number' => 'required',
                    'ex_showroom_price' => 'required',
                    'interior_color' => 'required',
                    'exterior_color' => 'required'
                ];

                $validator = Validator::make($request->all(), $rules);

                if ($validator->fails())
                    return response()->json(['status' => 422, 'message' => $validator->errors()]);

                $crud = [
                    'category_id' => $request->category_id,
                    'product_id' => $request->product_id,
                    'veriant_id' => $request->veriant_id,
                    'key_number' => $request->key_number,
                    'engine_number' => $request->engine_number,
                    'chassis_number' => $request->chassis_number,
                    'vin_number' => $request->vin_number,
                    'ex_showroom_price' => $request->ex_showroom_price,
                    'interior_color' => $request->interior_color,
                    'exterior_color' => $request->exterior_color,
                    'status' => 'active',
                    'created_at' => date('Y-m-d H:i:s'),
                    'created_by' => auth()->user()->id,
                    'updated_at' => date('Y-m-d H:i:s'),
                    'updated_by' => auth()->user()->id
                ];

                $last_id = Inventory::insertGetId($crud);

                if ($last_id) {
                    return response()->json(['status' => 200, 'message' => 'Record inserted successfully']);
                } else {
                    return response()->json(['status' => 201, 'message' => 'Faild to insert Record!']);
                }
            }else{
                return response()->json(['status' => 401, 'message' => 'Not Authorized.']);
            }
        }
    /** insert */

    /** view */
        public function view(Request $request)
        {
            if(auth()->user()->can('inventory-view')){
                $data = Inventory::where(['id' => $request->id])->first();

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
            if(auth()->user()->can('inventory-edit')){
                $rules = [
                    'category_id' => 'required',
                    'product_id' => 'required',
                    'veriant_id' => 'required',
                    'key_number' => 'required',
                    'engine_number' => 'required',
                    'chassis_number' => 'required',
                    'vin_number' => 'required',
                    'ex_showroom_price' => 'required',
                    'interior_color' => 'required',
                    'exterior_color' => 'required'
                ];

                $validator = Validator::make($request->all(), $rules);

                if ($validator->fails())
                    return response()->json(['status' => 422, 'message' => $validator->errors()]);

                $crud = [
                    'category_id' => $request->category_id,
                    'product_id' => $request->product_id,
                    'veriant_id' => $request->veriant_id,
                    'key_number' => $request->key_number,
                    'engine_number' => $request->engine_number,
                    'chassis_number' => $request->chassis_number,
                    'vin_number' => $request->vin_number,
                    'ex_showroom_price' => $request->ex_showroom_price,
                    'interior_color' => $request->interior_color,
                    'exterior_color' => $request->exterior_color,
                    'updated_at' => date('Y-m-d H:i:s'),
                    'updated_by' => auth('sanctum')->user()->id
                ];


                $update = Inventory::where(['id' => $request->id])->update($crud);
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
            if(auth()->user()->can('inventory-delete')){
                $rules = [
                    'id' => 'required',
                    'status' => 'required'
                ];

                $validator = Validator::make($request->all(), $rules);

                if ($validator->fails())
                    return response()->json(['status' => 422, 'message' => $validator->errors()]);

                $data = Inventory::where(['id' => $request->id])->first();

                if (!empty($data)) {
                    $update = Inventory::where(['id' => $request->id])->update(['status' => $request->status, 'updated_at' => date('Y-m-d H:i:s'), 'updated_by' => auth('sanctum')->user()->id]);
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

    /** Get Users */
        public function get_users(Request $request)
        {
            
            $data = OBF::select('id' , 'obf_temporary_id','products.name AS product_name','customer_name',DB::raw('(on_road_price - booking_amount) as total_amount'))->where(['status' => 'account_approved'])
            ->leftjoin('products' , 'obf.product_id' ,'products.id')    
            ->get();

            if (!empty($data)) {
                return response()->json(['status' => 200, 'message' => 'Data Found.' ,'data' => $data]);
            } else {
                return response()->json(['status' => 201, 'message' => 'Somthing went wrong !']);
            }
            
        }
    /** Get Users */

    /** Assign Car */
        public function assign_car(Request $request)
        {
            $rules = [
                'inventory_id' => 'required',
                'customer_id' => 'required'
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails())
                return response()->json(['status' => 422, 'message' => $validator->errors()]);

            $user = OBF::where(['id' => $request->customer_id])->first();

            if (!empty($user)) {
               $inventory = Inventory::where(['id' => $request->inventory_id])->first();
                if(!empty($inventory)){
                    $crud = [
                        'inventory_id' => $request->inventory_id,
                        'obf_id' => $request->customer_id,
                        'product_id' => $request->product_id,
                        'dsa_or_broker' => $request->dsa_or_broker,
                        'disb_amount' => $request->disb_amount,
                        'payment_due' => $request->payment_due,
                        'fatd' => $request->fatd,
                        'iatd' => $request->iatd,
                        'tentetive_delivery_date' => $request->tentetive_delivery_date,
                        'reason' => $request->reason,
                        'status' => $request->status,
                    ];
                    $allocate = Allocation::create($crud);
                    if($allocate){
                        $inventory_status = Inventory::where('id' , $inventory->id)->update(['status' => 'sold']);
                        if($inventory_status){
                            $order = Order::where('obf_id' ,$request->customer_id)->update(['status' => 'delivered']);
                            if($order){
                                return response()->json(['status' => 200, 'message' => 'Car Allocated Succesfully!']);
                            }else{
                                return response()->json(['status' => 201, 'message' => 'Faild To Update Order!']);
                            }
                        }else{
                            return response()->json(['status' => 201, 'message' => 'Faild To Update Inventory!']);
                        }
                    }else{
                        return response()->json(['status' => 201, 'message' => 'Faild To insert in Allocation!']);
                    }
                }else{
                   return response()->json(['status' => 201, 'message' => 'Inventory Not Found!']);
                }
            } else {
                return response()->json(['status' => 201, 'message' => 'User Not Found !']);
            }
            
        }
    /** Assign Car */
}
