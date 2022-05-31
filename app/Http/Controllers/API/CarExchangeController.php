<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CarExchange;
use App\Models\CarExchangeCategory;
use App\Models\CarExchangeProduct;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use DB, Validator;

class CarExchangeController extends Controller
{
    /** construct */
        public function __construct(){
            $this->middleware('permission:car_exchange-create', ['only' => ['create']]);
            $this->middleware('permission:car_exchange-edit', ['only' => ['edit']]);
            $this->middleware('permission:car_exchange-view', ['only' => ['view']]);
            $this->middleware('permission:car_exchange-delete', ['only' => ['delete']]);
        }
    /** construct */
    /** index */
        public function index(Request $request)
        {
            if(auth()->user()->can('car_exchange-view')){
                $data = DB::table('car_exchange AS ce')
                            ->select('cec.name AS category_name' ,'cep.name AS product_name' ,'ce.*')
                            ->leftjoin('car_exchange_category AS cec' ,'ce.category_id' ,'cec.id')
                            ->leftjoin('car_exchange_product AS cep' ,'ce.product_id' ,'cep.id')
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
            if(auth()->user()->can('car_exchange-create')){
                $rules = [
                    'category_id' => 'required',
                    'product_id' => 'required',
                    'engine_number' => 'required',
                    'chassis_number' => 'required',
                    'price' => 'required'
                ];

                $validator = Validator::make($request->all(), $rules);

                if ($validator->fails())
                    return response()->json(['status' => 422, 'message' => $validator->errors()]);

                $crud = [
                    'category_id' => $request->category_id,
                    'product_id' => $request->product_id,
                    'engine_number' => $request->engine_number,
                    'chassis_number' => $request->chassis_number,
                    'price' => $request->price,
                    'status' => 'active',
                    'created_at' => date('Y-m-d H:i:s'),
                    'created_by' => auth()->user()->id,
                    'updated_at' => date('Y-m-d H:i:s'),
                    'updated_by' => auth()->user()->id
                ];

                $last_id = CarExchange::insertGetId($crud);

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
            if(auth()->user()->can('car_exchange-view')){
                $data = CarExchange::
                        select('cec.name AS category_name' ,'cep.name AS product_name' ,'car_exchange.*')
                        ->leftjoin('car_exchange_category AS cec' ,'car_exchange.category_id' ,'cec.id')
                        ->leftjoin('car_exchange_product AS cep' ,'car_exchange.product_id' ,'cep.id')
                        ->where(['car_exchange.id' => $request->id])->first();

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
            if(auth()->user()->can('car_exchange-edit')){
                $rules = [
                    'id' => 'required',
                    'category_id' => 'required',
                    'product_id' => 'required',
                    'engine_number' => 'required',
                    'chassis_number' => 'required',
                    'price' => 'required'
                ];

                $validator = Validator::make($request->all(), $rules);

                if ($validator->fails())
                    return response()->json(['status' => 422, 'message' => $validator->errors()]);

                $crud = [
                    'category_id' => $request->category_id,
                    'product_id' => $request->product_id,
                    'engine_number' => $request->engine_number,
                    'chassis_number' => $request->chassis_number,
                    'price' => $request->price,
                    'updated_at' => date('Y-m-d H:i:s'),
                    'updated_by' => auth('sanctum')->user()->id
                ];


                $update = CarExchange::where(['id' => $request->id])->update($crud);
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
            if(auth()->user()->can('car_exchange-delete')){
                $rules = [
                    'id' => 'required',
                    'status' => 'required'
                ];

                $validator = Validator::make($request->all(), $rules);

                if ($validator->fails())
                    return response()->json(['status' => 422, 'message' => $validator->errors()]);

                $data = CarExchange::where(['id' => $request->id])->first();

                if (!empty($data)) {
                    $update = CarExchange::where(['id' => $request->id])->update(['status' => $request->status, 'updated_at' => date('Y-m-d H:i:s'), 'updated_by' => auth('sanctum')->user()->id]);
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

    /** Get Category */
        public function get_category(Request $request)
        {
            $data = CarExchangecategory::select('id' ,'name')->where(['status' => 'active'])->get();

            if ($data)
                return response()->json(['status' => 200, 'message' => 'Data found', 'data' => $data]);
            else
                return response()->json(['status' => 404, 'message' => 'No data found']);
        }
    /** Get Category */
    
    /** Get Model */
        public function get_model(Request $request)
        {
            $rules = [
                'category_id' => 'required'
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails())
                return response()->json(['status' => 422, 'message' => $validator->errors()]);

            $data = CarExchangeProduct::select('id' ,'name')->where(['category_id' => $request->category_id])->get();

            if ($data)
                return response()->json(['status' => 200, 'message' => 'Data found', 'data' => $data]);
            else
                return response()->json(['status' => 404, 'message' => 'No data found']);
        }
    /** Get Model */
}
