<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Insurance;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Auth, DB, Mail, Validator, File, DataTables;

class InsuranceController extends Controller{
    /** construct */
        public function __construct(){
            $this->middleware('permission:insurance-create', ['only' => ['create']]);
            $this->middleware('permission:insurance-edit', ['only' => ['edit']]);
            $this->middleware('permission:insurance-view', ['only' => ['view']]);
            $this->middleware('permission:insurance-delete', ['only' => ['delete']]);
        }
    /** construct */

    /** index */
        public function index(Request $request){
            return view('insurance.index');
        }
    /** index */

    /** insert */
        public function insert(Request $request){
            if(auth()->user()->can('insurance-create')){
                $rules = [
                    'name' => 'required',
                    'type' => 'required',
                    'years' => 'required',
                    'amount' => 'required'
                ];

                $validator = Validator::make($request->all(), $rules);

                if ($validator->fails())
                    return response()->json(['status' => 422, 'message' => $validator->errors()]);

                $crud = [
                    'name' => ucfirst($request->name),
                    'type' => $request->type,
                    'years' => $request->years,
                    'amount' => $request->amount,
                    'status' => 'active',
                    'created_at' => date('Y-m-d H:i:s'),
                    'created_by' => auth()->user()->id,
                    'updated_at' => date('Y-m-d H:i:s'),
                    'updated_by' => auth()->user()->id
                ];

                $last_id = Insurance::insertGetId($crud);

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

    /** create */
        public function create(Request $request){
            return view('insurance.create');
        }
    /** create */

    /** view */
        public function view(Request $request){
            return view('insurance.view');
        }
    /** view */

    /** edit */
        public function edit(Request $request){
            return view('insurance.edit');
        }
    /** edit */

    /** update */
        public function update(Request $request){
                $crud = [
                    'name' => ucfirst($request->name),
                    'type' => $request->type,
                    'years' => $request->years,
                    'amount' => $request->amount,
                    'updated_at' => date('Y-m-d H:i:s'),
                    'updated_by' => auth()->user()->id
                ];


                $update = Insurance::where(['id' => $request->id])->update($crud);
                if ($update){
                    return redirect()->route('insurance')->with('success', 'Record updated successfully');
                } else { 
                    return redirect()->route('insurance')->with('error', 'Failed to update the record!');
                }
                return view('insurance');
        }
    /** update */

    /** change-status */
        public function change_status(Request $request){
            if(auth()->user()->can('insurance-delete')){
                $rules = [
                    'id' => 'required',
                    'status' => 'required'
                ];

                $validator = Validator::make($request->all(), $rules);

                if ($validator->fails())
                    return response()->json(['status' => 422, 'message' => $validator->errors()]);

                $data = Insurance::where(['id' => $request->id])->first();

                if (!empty($data)) {
                    $update = Insurance::where(['id' => $request->id])->update(['status' => $request->status, 'updated_at' => date('Y-m-d H:i:s'), 'updated_by' => auth('sanctum')->user()->id]);
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
