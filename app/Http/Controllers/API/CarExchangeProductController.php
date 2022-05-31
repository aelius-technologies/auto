<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CarExchangeProduct;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use DB ,Validator;

class CarExchangeProductController extends Controller
{
    /** construct */
        public function __construct(){
            $this->middleware('permission:car_exchange_product-create', ['only' => ['create']]);
            $this->middleware('permission:car_exchange_product-edit', ['only' => ['edit']]);
            $this->middleware('permission:car_exchange_product-view', ['only' => ['view']]);
            $this->middleware('permission:car_exchange_product-delete', ['only' => ['delete']]);
        }
    /** construct */
    /** index */
        public function index(Request $request)
        {
            if(auth()->user()->can('car_exchange_product-view')){
                $data = DB::table('car_exchange_product')
                ->select('car_exchange_category.name AS category_name' , 'car_exchange_product.*')
                ->leftjoin('car_exchange_category' , 'car_exchange_product.category_id' ,'car_exchange_category.id')
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

    /** insert */
        public function insert(Request $request)
        {
            if(auth()->user()->can('car_exchange_product-create')){

                $rules = [
                    'name' => 'required',
                    'category_id' => 'required',
                ];
                
                $validator = Validator::make($request->all(), $rules);
                
                if ($validator->fails())
                return response()->json(['status' => 422, 'message' => $validator->errors()]);
                
                $crud = [
                    'name' => ucfirst($request->name),
                    'category_id' => $request->category_id,
                    'status' => 'active',
                    'created_at' => date('Y-m-d H:i:s'),
                    'created_by' => auth()->user()->id,
                    'updated_at' => date('Y-m-d H:i:s'),
                    'updated_by' => auth()->user()->id
                ];
                
                $last_id = CarExchangeProduct::insertGetId($crud);
                
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
            if(auth()->user()->can('car_exchange_product-view')){

                $data = CarExchangeProduct::where(['id' => $request->id])->first();
                
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
            if(auth()->user()->can('car_exchange_product-edit')){

                $rules = [
                    'id' => 'required',
                    'name' => 'required',
                    'category_id' => 'required'
                ];

                $validator = Validator::make($request->all(), $rules);

                if ($validator->fails())
                    return response()->json(['status' => 422, 'message' => $validator->errors()]);

                $crud = [
                    'name' => ucfirst($request->name),
                    'category_id' => $request->category_id,
                    'updated_at' => date('Y-m-d H:i:s'),
                    'updated_by' => auth('sanctum')->user()->id
                ];

                $update = CarExchangeProduct::where(['id' => $request->id])->update($crud);
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
            if(auth()->user()->can('car_exchange_product-delete')){

                $rules = [
                    'id' => 'required',
                    'status' => 'required'
                ];

                $validator = Validator::make($request->all(), $rules);
                
                if ($validator->fails())
                return response()->json(['status' => 422, 'message' => $validator->errors()]);
                
                $data = CarExchangeProduct::where(['id' => $request->id])->first();
                
                if (!empty($data)) {
                    $update = CarExchangeProduct::where(['id' => $request->id])->update(['status' => $request->status, 'updated_at' => date('Y-m-d H:i:s'), 'updated_by' => auth('sanctum')->user()->id]);
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
