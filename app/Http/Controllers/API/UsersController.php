<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\ImportUser;
use App\Exports\ExportUser;
use App\Models\User;
use Illuminate\Support\Facades\Storage;
use Auth, Validator, DB, Mail ,File;

class UsersController extends Controller
{
    public function __construct(){
        $this->middleware('permission:user-create', ['only' => ['create']]);
        $this->middleware('permission:user-edit', ['only' => ['edit']]);
        $this->middleware('permission:user-view', ['only' => ['view']]);
        $this->middleware('permission:user-delete', ['only' => ['delete']]);
    }

    public function import(Request $request){
        Excel::import(new ImportUser, $request->file('file')->store('files'));
        return response()->json(['status' => 200 ,'message' => 'Success.']);
    }
    
    public function export(Request $request){
        $name = 'Users'.Date('YmdHis').'.xlsx';
        $excel = Excel::store(new ExportUser, $name, 'excel_store');
        if($excel){
            if(Storage::disk('excel_store')->exists($name)){
                $path = public_path().'/uploads/excel/'.$name;
                return response()->json(['status' => 200 ,'message' => 'Export Success.' ,'data' => $path]);
            }else{
                return response()->json(['status' => 200 ,'message' => 'No Export Found.']);
            }
        }else{
            return response()->json(['status' => 201 ,'message' => 'Error.']);
        }
    }
    /** index */
        public function index(Request $request)
        {
            if(auth()->user()->can('user-view')){

                $user = DB::table('users')->where(['status' => 'active'])->get();
                if (sizeof($user)) {
                    return response()->json(['status' => 200, 'message' => 'Data Found.', 'Data' => $user]);
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
            if(auth()->user()->can('user-create')){
                $rules = [
                    'first_name' => 'required',
                    'last_name' => 'required',
                    'email' => 'required|email|unique:users,email'
                ];

                $validator = Validator::make($request->all(), $rules);

                if ($validator->fails())
                    return response()->json(['status' => 422, 'message' => $validator->errors()]);

                $password = $request->password ?? 'Abcd@1234';
                $crud = [
                    'first_name' => ucfirst($request->first_name),
                    'last_name' => ucfirst($request->last_name),
                    'email' => $request->email,
                    'role' => $request->role,
                    'contact_number' => $request->contact_number,
                    'branch' => $request->branch,
                    'status' => 'active',
                    'password' => bcrypt($password),
                    'created_at' => date('Y-m-d H:i:s'),
                    'created_by' => auth()->user()->id,
                    'updated_at' => date('Y-m-d H:i:s'),
                    'updated_by' => auth()->user()->id
                ];

                $user = User::create($crud);
                $user->assignRole($request->role);

                if ($user) {
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
            if(auth()->user()->can('user-view')){   
                $data = User::where(['id' => $request->id])->first();

                if (sizeof($data))
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
            if(auth()->user()->can('user-edit')){ 
                $rules = [
                    'first_name' => 'required',
                    'last_name' => 'required',
                    'email' => 'required|email|unique:users,email,' . $request->id
                ];

                $validator = Validator::make($request->all(), $rules);

                if ($validator->fails())
                    return response()->json(['status' => 422, 'message' => $validator->errors()]);

                $crud = [
                    'first_name' => ucfirst($request->first_name) ?? '',
                    'last_name' => ucfirst($request->last_name) ?? '',
                    'email' => $request->email ?? '',
                    'role' => $request->role ?? '',
                    'contact_number' => $request->contact_number ?? '',
                    'branch' => $request->branch ?? null,
                    'updated_at' => date('Y-m-d H:i:s'),
                    'updated_by' => auth('sanctum')->user()->id
                ];

                if (isset($request->password) && !empty($request->password))
                    $crud['password'] = bcrypt($request->password);

                $update = User::where(['id' => $request->id])->update($crud);
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
            if(auth()->user()->can('user-delete')){ 
                $rules = [
                    'id' => 'required',
                    'status' => 'required'
                ];

                $validator = Validator::make($request->all(), $rules);

                if ($validator->fails())
                    return response()->json(['status' => 422, 'message' => $validator->errors()]);

                $data = User::where(['id' => $request->id])->first();

                if (!empty($data)) {
                    $update = User::where(['id' => $request->id])->update(['status' => $request->status, 'updated_at' => date('Y-m-d H:i:s'), 'updated_by' => auth('sanctum')->user()->id]);
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
