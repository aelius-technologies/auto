<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Validator , DB;

class RolesController extends Controller
{
    public function __construct(){
        $this->middleware('permission:role-create', ['only' => ['create']]);
        $this->middleware('permission:role-edit', ['only' => ['edit']]);
        $this->middleware('permission:role-view', ['only' => ['view']]);
        $this->middleware('permission:role-delete', ['only' => ['delete']]);
    }
    /** index */
        public function index(Request $request){
            if(auth()->user()->can('role-view')){
                $data = Role::all();
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
            if(auth()->user()->can('role-create')){
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
                
                $last_id = Role::create($crud);
                
                if($last_id){
                    $last_id->syncPermissions($request->permissions);

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
            if(auth()->user()->can('role-view')){
                $roles = Role::find($request->id);
                $permissions = Permission::get();
                $role_permissions = DB::table("role_has_permissions")
                                        ->where("role_has_permissions.role_id", $request->id)
                                        ->pluck('role_has_permissions.permission_id','role_has_permissions.permission_id')
                                        ->all();
                $data['roles'] = $roles ?? '';
                $data['permissions'] = $permissions ?? '';
                $data['role_permissions'] = $role_permissions ?? '';
                
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
            if(auth()->user()->can('role-edit')){
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

                $update = Role::where(['id' => $request->id])->update($crud);
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
            if(auth()->user()->can('role-delete')){
                $rules = [
                    'id' => 'required',
                    'status' => 'required'
                ];

                $validator = Validator::make($request->all(), $rules);

                if($validator->fails())
                    return response()->json(['status' => 422, 'message' => $validator->errors()]);

                $data = Role::where(['id' => $request->id])->first();

                if(!empty($data)){
                    if($request->status == 'deleted'){
                        $update = Role::where(['id' => $request->id])->delete();
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
