<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Lead;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Validator , DB;

class LeadController extends Controller
{
    /** construct */
        public function __construct(){
            $this->middleware('permission:lead-create', ['only' => ['create']]);
            $this->middleware('permission:lead-edit', ['only' => ['edit']]);
            $this->middleware('permission:lead-view', ['only' => ['view']]);
            $this->middleware('permission:lead-delete', ['only' => ['delete']]);
        }
    /** construct */
    /** index */
        public function index(Request $request){
            if(auth()->user()->can('lead-view')){
                $data = Lead::all();
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

    /** insert */
        public function insert(Request $request){
            if(auth()->user()->can('lead-create')){
                $rules = [
                    'name' => 'required',
                ];

                $validator = Validator::make($request->all(), $rules);

                if($validator->fails())
                    return response()->json(['status' => 422, 'message' => $validator->errors()]);

                $password = $request->password ?? 'Abcd@1234';
                $crud = [
                    'name' => ucfirst($request->name),
                    'status' => 'active',
                    'created_at' => date('Y-m-d H:i:s'),
                    'created_by' => auth('sanctum')->user()->id,
                    'updated_at' => date('Y-m-d H:i:s'),
                    'updated_by' => auth('sanctum')->user()->id
                ];
                
                $last_id = Lead::insertGetId($crud);
                
                if($last_id){
                    return response()->json(['status' => 200 ,'message' => 'Record inserted successfully']);
                } else {
                    return response()->json(['status' => 201 ,'message' => 'Faild to insert Record!']);
                }
            }else{
                return response()->json(['status' => 401, 'message' => 'Not Authorized.']);
            }
        
        }
    /** insert */

    /** view */
        public function view(Request $request){
            if(auth()->user()->can('lead-view')){
                $data = Lead::where(['id' => $request->id])->first();
                
                if($data)
                    return response()->json(['status' => 200, 'message' => 'Data found', 'data' => $data]);
                else
                    return response()->json(['status' => 404, 'message' => 'No data found']);
            }else{
                return response()->json(['status' => 401, 'message' => 'Not Authorized.']);
            }
        }
    /** view */

    /** update */
        public function update(Request $request){
            if(auth()->user()->can('lead-edit')){
                $rules = [
                    'id' => 'required',
                    'name' => 'required',
                ];

                $validator = Validator::make($request->all(), $rules);

                if($validator->fails())
                    return response()->json(['status' => 422, 'message' => $validator->errors()]);

                $crud = [
                    'name' => ucfirst($request->name) ?? '',
                    'updated_at' => date('Y-m-d H:i:s'),
                    'updated_by' => auth('sanctum')->user()->id
                ];

                $update = Lead::where(['id' => $request->id])->update($crud);
                if($update)
                    return response()->json(['status' => 200, 'message' => 'Record updated successfully']);
                else
                    return response()->json(['status' => 201, 'message' => 'Faild to update record']);
            }else{
                return response()->json(['status' => 401, 'message' => 'Not Authorized.']);
            }
            
        }
    /** update */

    /** change-status */
        public function change_status(Request $request){
            if(auth()->user()->can('lead-delete')){
                $rules = [
                    'id' => 'required',
                    'status' => 'required'
                ];

                $validator = Validator::make($request->all(), $rules);

                if($validator->fails())
                    return response()->json(['status' => 422, 'message' => $validator->errors()]);

                $data = Lead::where(['id' => $request->id])->first();

                if(!empty($data)){
                    $update = Lead::where(['id' => $request->id])->update(['status' => $request->status, 'updated_at' => date('Y-m-d H:i:s'), 'updated_by' => auth('sanctum')->user()->id]);
                    if($update){
                        return response()->json(['status' => 200 ,'message' => 'Record status change successfully']);
                    }else{
                        return response()->json(['status' => 201, 'message' => 'Faild to update status']);
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
