<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CarExchangeCategory;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use DB, Validator;


class CarExchangeCategoryController extends Controller
{
    /** construct */
        public function __construct(){
            $this->middleware('permission:car_exchange_category-create', ['only' => ['create']]);
            $this->middleware('permission:car_exchange_category-edit', ['only' => ['edit']]);
            $this->middleware('permission:car_exchange_category-view', ['only' => ['view']]);
            $this->middleware('permission:car_exchange_category-delete', ['only' => ['delete']]);
        }
    /** construct */
    /** index */
        public function index(Request $request)
        {
            if(auth()->user()->can('car_exchange_category-view')){
                $data = DB::table('car_exchange_category')->get();
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
            if(auth()->user()->can('car_exchange_category-create')){
                $rules = [
                    'name' => 'required',
                ];

                $validator = Validator::make($request->all(), $rules);

                if ($validator->fails())
                    return response()->json(['status' => 422, 'message' => $validator->errors()]);

                $crud = [
                    'name' => ucfirst($request->name),
                    'status' => 'active',
                    'created_at' => date('Y-m-d H:i:s'),
                    'created_by' => auth()->user()->id,
                    'updated_at' => date('Y-m-d H:i:s'),
                    'updated_by' => auth()->user()->id
                ];

                $last_id = CarExchangeCategory::insertGetId($crud);

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
            if(auth()->user()->can('car_exchange_category-view')){
                $data = CarExchangeCategory::where(['id' => $request->id])->first();

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
            if(auth()->user()->can('car_exchange_category-edit')){
                $rules = [
                    'id' => 'required',
                    'name' => 'required'
                ];

                $validator = Validator::make($request->all(), $rules);

                if ($validator->fails())
                    return response()->json(['status' => 422, 'message' => $validator->errors()]);

                $crud = [
                    'name' => ucfirst($request->name),
                    'updated_at' => date('Y-m-d H:i:s'),
                    'updated_by' => auth('sanctum')->user()->id
                ];


                $update = CarExchangeCategory::where(['id' => $request->id])->update($crud);
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
            if(auth()->user()->can('car_exchange_category-delete')){
                $rules = [
                    'id' => 'required',
                    'status' => 'required'
                ];

                $validator = Validator::make($request->all(), $rules);

                if ($validator->fails())
                    return response()->json(['status' => 422, 'message' => $validator->errors()]);

                $data = CarExchangeCategory::where(['id' => $request->id])->first();

                if (!empty($data)) {
                    $update = CarExchangeCategory::where(['id' => $request->id])->update(['status' => $request->status, 'updated_at' => date('Y-m-d H:i:s'), 'updated_by' => auth('sanctum')->user()->id]);
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
