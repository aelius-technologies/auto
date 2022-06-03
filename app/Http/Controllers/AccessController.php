<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Http\Requests\AccessRequest;
use Spatie\Permission\PermissionRegistrar;
use Auth, DB, Mail, Validator, File, DataTables;

class AccessController extends Controller{
    /** construct */
        public function __construct(){
            $this->middleware('permission:access-edit', ['only' => ['edit']]);
            $this->middleware('permission:access-view', ['only' => ['view']]);
        }
    /** construct */

    /** index */
        public function index(Request $request){
            if($request->ajax()){
                $data = Role::select('id', 'name')->orderBy('id', 'desc')->get();

                return Datatables::of($data)
                        ->addIndexColumn()
                        ->addColumn('action', function($data){
                            $return = '<div class="btn-group">';

                            if(auth()->user()->can('access-view')){
                                $return .= '<a href="'.route('access_permission.view', ['id' => base64_encode($data->id)]).'" class="btn btn-default btn-xs">
                                                <i class="fa fa-eye"></i>
                                            </a> &nbsp;';
                            }

                            if(auth()->user()->can('access-edit')){
                                $return .= '<a href="'.route('access_permission.edit', ['id' => base64_encode($data->id)]).'" class="btn btn-default btn-xs">
                                                <i class="fa fa-edit"></i>
                                            </a> &nbsp;';
                            }

                            $return .= '</div>';

                            return $return;
                        })

                        ->editColumn('permissions', function($data) {
                            return 'permissions';
                        })

                        ->editColumn('name', function($data) {
                            return ucfirst(str_replace('_', ' ', $data->name));
                        })

                        ->rawColumns(['name', 'action', 'permissions'])
                        ->make(true);
            }

            return view('access.index');
        }
    /** index */
    
    /** edit */
        public function edit(Request $request){
            if(isset($request->id) && $request->id != '' && $request->id != null)
                $id = base64_decode($request->id);
            else
                return redirect()->route('access')->with('error', 'Something went wrong');

            $permissions = Permission::select('id', 'name')->get();
            $roles = Role::select('id', 'name')->get();
            $data = Role::select('id', 'name')->where(['id' => $id])->first();
            $permission = DB::table('role_has_permissions')->select('permission_id')->where(['role_id' => $id])->get()->toArray();
            $data->permissions = array_map(function($row) { return $row->permission_id; }, $permission);

            return view('access.edit')->with(['data' => $data, 'roles' => $roles, 'permissions' => $permissions]);
        }
    /** edit */

    /** update */
        public function update(AccessRequest $request){
            if($request->ajax()){ return true ;}
            app()[PermissionRegistrar::class]->forgetCachedPermissions();
            $permissions = array_map(function($row) { return $row; }, $request->permissions);
            
            $role = Role::find($request->role);
            // dd($role->givePermissionTo(95));
            if($role->givePermissionTo($permissions))
                return redirect()->route('access_permission')->with('success', 'Record updated successfully');
            else
                return redirect()->back()->with('error', 'Failed to update record')->withInput();
        }
    /** update */

    /** view */
        public function view(Request $request){
            if(isset($request->id) && $request->id != '' && $request->id != null)
                $id = base64_decode($request->id);
            else
                return redirect()->route('access_permission')->with('error', 'Something went wrong');

            $permissions = Permission::select('id', 'name')->get();
            $roles = Role::select('id', 'name')->get();
            $data = Role::select('id', 'name')->where(['id' => $id])->first();
            $permission = DB::table('role_has_permissions')->select('permission_id')->where(['role_id' => $id])->get()->toArray();
            $data->permissions = array_map(function($row) { return $row->permission_id; }, $permission);

            return view('access.view')->with(['data' => $data, 'roles' => $roles, 'permissions' => $permissions]);
        }
    /** view */
}
