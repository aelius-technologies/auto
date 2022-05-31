<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Branch;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Auth, Validator , DB, Mail;

class BranchController extends Controller
{
    public function __construct(){
        $this->middleware('permission:branch-create', ['only' => ['create']]);
        $this->middleware('permission:branch-edit', ['only' => ['edit']]);
        $this->middleware('permission:branch-view', ['only' => ['view']]);
        $this->middleware('permission:branch-delete', ['only' => ['delete']]);
    }
    /** index */
        public function index(Request $request){
            if(auth()->user()->can('branch-view')){
                $data = Branch::all();
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
            if(auth()->user()->can('branch-create')){
                $rules = [
                    'name' => 'required',
                    'city' => 'required',
                    'address' => 'required',
                    'contact_number' => 'required',
                    'manager' => 'required',
                    'manager_contact_number' => 'required',
                    'email' => 'required|email|unique:users,email'
                ];

                $validator = Validator::make($request->all(), $rules);

                if($validator->fails())
                    return response()->json(['status' => 422, 'message' => $validator->errors()]);

                $password = $request->password ?? 'Abcd@1234';
                $crud = [
                    'name' => ucfirst($request->name),
                    'city' => ucfirst($request->city),
                    'address' => ucfirst($request->address),
                    'contact_number' => $request->contact_number,
                    'manager' => $request->manager,
                    'manager_contact_number' => $request->manager_contact_number,
                    'gst' => $request->gst ?? null,
                    'email' => $request->email,
                    'status' => 'active',
                    'created_at' => date('Y-m-d H:i:s'),
                    'created_by' => auth('sanctum')->user()->id,
                    'updated_at' => date('Y-m-d H:i:s'),
                    'updated_by' => auth('sanctum')->user()->id
                ];
                
                $last_id = Branch::insertGetId($crud);
                
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
            if(auth()->user()->can('branch-view')){
                $data = Branch::where(['id' => $request->id])->first();
                
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
            if(auth()->user()->can('branch-edit')){
                $rules = [
                    'id' => 'required',
                    'name' => 'required',
                    'city' => 'required',
                    'address' => 'required',
                    'contact_number' => 'required',
                    'manager' => 'required',
                    'manager_contact_number' => 'required',
                    'email' => 'required|email|unique:users,email,'.$request->id
                ];

                $validator = Validator::make($request->all(), $rules);

                if($validator->fails())
                    return response()->json(['status' => 422, 'message' => $validator->errors()]);

                $crud = [
                    'name' => ucfirst($request->name) ?? '',
                    'city' => $request->city ?? '',
                    'address' => $request->address ?? '',
                    'contact_number' => $request->contact_number ?? '',
                    'manager' => $request->manager ?? '',
                    'manager_contact_number' => $request->manager_contact_number ?? '',
                    'email' => $request->email ?? '',
                    'updated_at' => date('Y-m-d H:i:s'),
                    'updated_by' => auth('sanctum')->user()->id
                ];

                $update = Branch::where(['id' => $request->id])->update($crud);
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
            if(auth()->user()->can('branch-delete')){
                $rules = [
                    'id' => 'required',
                    'status' => 'required'
                ];

                $validator = Validator::make($request->all(), $rules);

                if($validator->fails())
                    return response()->json(['status' => 422, 'message' => $validator->errors()]);

                $data = Branch::where(['id' => $request->id])->first();

                if(!empty($data)){
                    $update = Branch::where(['id' => $request->id])->update(['status' => $request->status, 'updated_at' => date('Y-m-d H:i:s'), 'updated_by' => auth('sanctum')->user()->id]);
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
