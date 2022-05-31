<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Validator , DB ,Auth;

class PermissionController extends Controller
{
    public function __construct(){
        $this->middleware('permission:permission-create', ['only' => ['create']]);
        $this->middleware('permission:permission-edit', ['only' => ['edit']]);
        $this->middleware('permission:permission-view', ['only' => ['view']]);
        $this->middleware('permission:permission-delete', ['only' => ['delete']]);
    }
    /** index */
        public function index(Request $request){

            if(auth()->user()->can('permission-view')){
                $data = Permission::all();
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
            if(auth()->user()->can('permission-create')){
                $rules = [
                    'name' => 'required',
                ];

                $validator = Validator::make($request->all(), $rules);

                if($validator->fails())
                return response()->json(['status' => 422, 'message' => $validator->errors()]);
                
                $password = $request->password ?? 'Abcd@1234';
                $crud = [
                    'name' => $request->name,
                    'guard_name' => 'web',
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ];
                
                $last_id = Permission::insertGetId($crud);
                
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
            if(auth()->user()->can('permission-view')){
                $data = Permission::where(['id' => $request->id])->first();
                
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
            if(auth()->user()->can('permission-edit')){
                $rules = [
                    'id' => 'required',
                    'name' => 'required',
                ];

                $validator = Validator::make($request->all(), $rules);

                if($validator->fails())
                    return response()->json(['status' => 422, 'message' => $validator->errors()]);

                $crud = [
                    'name' => $request->name ?? '',
                    'updated_at' => date('Y-m-d H:i:s'),
                ];

                $update = Permission::where(['id' => $request->id])->update($crud);
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
            if(auth()->user()->can('permission-delete')){
                $rules = [
                    'id' => 'required',
                    'status' => 'required'
                ];

                $validator = Validator::make($request->all(), $rules);

                if($validator->fails())
                    return response()->json(['status' => 422, 'message' => $validator->errors()]);

                $data = Permission::where(['id' => $request->id])->first();

                if(!empty($data)){
                    if($request->status == 'deleted'){
                        $update = Permission::where(['id' => $request->id])->delete();
                        if($update){
                            return response()->json(['status' => 200 ,'message' => 'Record status change successfully']);
                        }else{
                            return response()->json(['status' => 201, 'message' => 'Faild to update status']);
                        }
                    }else{
                        return response()->json(['status' => 201, 'message' => 'Status is not valid !']);
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
