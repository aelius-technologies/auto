<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Http\Requests\AccessRequest;
use Auth, DB, Mail, Validator, File, DataTables;

class AccessController extends Controller{
    /** construct */
        public function __construct(){
            $this->middleware('permission:access-create', ['only' => ['create']]);
            $this->middleware('permission:access-edit', ['only' => ['edit']]);
            $this->middleware('permission:access-view', ['only' => ['view']]);
            $this->middleware('permission:access-delete', ['only' => ['delete']]);
        }
    /** construct */

    /** index */
        public function index(Request $request){
            if(auth()->user()->can('access-view')){
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

    /** update */
        public function update(Request $request){
            if(auth()->user()->can('access-edit')){
                
                $permissions = json_decode($request->permission);
                $permission = array_map(function($row) { return $row; }, $permissions);
                // dd($permission);
                $role = Role::find($request->id);
                if($role->givePermissionTo($permissions)){
                    return response()->json(['status' => 200 ,'message' => 'Record Updated Successfully.']);
                }else{
                    return response()->json(['status' => 404 ,'message' => 'Faild To Update Record.']);
                }
            }else{
                return response()->json(['status' => 401, 'message' => 'Not Authorized.']);
            }

        }
    /** update */

    /** view */
        public function view(Request $request){
            if(auth()->user()->can('access-view')){
                $permissions = Permission::select('id', 'name')->get();
                $roles = Role::select('id', 'name')->get();
                $data = Role::select('id', 'name')->where(['id' => $request->id])->first();
                $permission = DB::table('role_has_permissions')->select('permission_id')->where(['role_id' => $request->id])->get()->toArray();
                $data->permissions = array_map(function($row) { return $row->permission_id; }, $permission);
               
                return response()->json(['status' => 200 ,'message' => 'Data Found.' , 'data' => $data, 'roles' => $roles, 'permissions' => $permissions]);

            }else{
                return response()->json(['status' => 401, 'message' => 'Not Authorized.']);
            }
        }
    /** view */
}
