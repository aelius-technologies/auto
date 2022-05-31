<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Accessory;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use DB, Validator;

class AccessoryController extends Controller
{
    /** construct */
        public function __construct(){
            $this->middleware('permission:accessories-create', ['only' => ['create']]);
            $this->middleware('permission:accessories-edit', ['only' => ['edit']]);
            $this->middleware('permission:accessories-view', ['only' => ['view']]);
            $this->middleware('permission:accessories-delete', ['only' => ['delete']]);
        }
    /** construct */
     /** index */
        public function index(Request $request)
        {
            if(auth()->user()->can('accessories-view')){
                $data = DB::table('accessories')->get();
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
            if(auth()->user()->can('accessories-create')){
                $rules = [
                    'name' => 'required',
                    'type' => 'required',
                    'price' => 'required',
                    'hsn_number' => 'required',
                    'model_number' => 'required',
                    'model_type' => 'required',
                    'warranty' => 'required',
                ];

                $validator = Validator::make($request->all(), $rules);

                if ($validator->fails())
                    return response()->json(['status' => 422, 'message' => $validator->errors()]);

                $crud = [
                    'name' => ucfirst($request->name),
                    'type' => $request->type,
                    'price' => $request->price,
                    'hsn_number' => $request->hsn_number,
                    'model_number' => $request->model_number,
                    'model_type' => $request->model_type,
                    'warranty' => $request->warranty,
                    'status' => 'active',
                    'created_at' => date('Y-m-d H:i:s'),
                    'created_by' => auth()->user()->id,
                    'updated_at' => date('Y-m-d H:i:s'),
                    'updated_by' => auth()->user()->id
                ];

                $last_id = Accessory::insertGetId($crud);

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
            if(auth()->user()->can('accessories-view')){
                $data = Accessory::where(['id' => $request->id])->first();

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
            if(auth()->user()->can('accessories-edit')){
                $rules = [
                    'id' => 'required',
                    'name' => 'required',
                    'type' => 'required',
                    'price' => 'required',
                    'hsn_number' => 'required',
                    'model_number' => 'required',
                    'model_type' => 'required',
                    'warranty' => 'required'
                ];

                $validator = Validator::make($request->all(), $rules);

                if ($validator->fails())
                    return response()->json(['status' => 422, 'message' => $validator->errors()]);

                $crud = [
                    'name' => ucfirst($request->name),
                    'type' => $request->type,
                    'price' => $request->price,
                    'hsn_number' => $request->hsn_number,
                    'model_number' => $request->model_number,
                    'model_type' => $request->model_type,
                    'warranty' => $request->warranty,
                    'updated_at' => date('Y-m-d H:i:s'),
                    'updated_by' => auth('sanctum')->user()->id
                ];


                $update = Accessory::where(['id' => $request->id])->update($crud);
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
            if(auth()->user()->can('accessories-delete')){
                $rules = [
                    'id' => 'required',
                    'status' => 'required'
                ];

                $validator = Validator::make($request->all(), $rules);

                if ($validator->fails())
                    return response()->json(['status' => 422, 'message' => $validator->errors()]);

                $data = Accessory::where(['id' => $request->id])->first();

                if (!empty($data)) {
                    $update = Accessory::where(['id' => $request->id])->update(['status' => $request->status, 'updated_at' => date('Y-m-d H:i:s'), 'updated_by' => auth('sanctum')->user()->id]);
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
