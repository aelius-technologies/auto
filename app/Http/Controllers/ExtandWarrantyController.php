<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ExtandWarranty;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Auth, DB, Mail, Validator, File, DataTables;

class ExtandWarrantyController extends Controller{
    /** construct */
        public function __construct(){
            $this->middleware('permission:extand_warranties-create', ['only' => ['create']]);
            $this->middleware('permission:extand_warranties-edit', ['only' => ['edit']]);
            $this->middleware('permission:extand_warranties-view', ['only' => ['view']]);
            $this->middleware('permission:extand_warranties-delete', ['only' => ['delete']]);
        }
    /** construct */

    /** index */
        public function index(Request $request){
            return view('extand_warranties.index');
        }
    /** index */

    /** create */
        public function create(Request $request){
            return view('extand_warranties.create');
        }
    /** create */

    /** insert */
        public function insert(Request $request){
            if(auth()->user()->can('extand_warranties-create')){
                $rules = [
                    'years' => 'required',
                    'amount' => 'required'
                ];

                $validator = Validator::make($request->all(), $rules);

                if ($validator->fails())
                    return response()->json(['status' => 422, 'message' => $validator->errors()]);

                $crud = [
                    'years' => $request->years,
                    'amount' => $request->amount,
                    'status' => 'active',
                    'created_at' => date('Y-m-d H:i:s'),
                    'created_by' => auth()->user()->id,
                    'updated_at' => date('Y-m-d H:i:s'),
                    'updated_by' => auth()->user()->id
                ];

                $last_id = ExtandWarranty::insertGetId($crud);

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
        public function view(Request $request){
            return view('extand_warranties.view');
        }
    /** view */

    /** edit */
        public function edit(Request $request){
            return view('extand_warranties.edit');
        }
    /** edit */

    /** update */
        public function update(Request $request){
            if(auth()->user()->can('extand_warranties-edit')){
                $rules = [
                    'id' => 'required',
                    'years' => 'required',
                    'amount' => 'required'
                ];

                $validator = Validator::make($request->all(), $rules);

                if ($validator->fails())
                    return response()->json(['status' => 422, 'message' => $validator->errors()]);

                $crud = [
                    'years' => $request->years,
                    'amount' => $request->amount,
                    'updated_at' => date('Y-m-d H:i:s'),
                    'updated_by' => auth('sanctum')->user()->id
                ];


                $update = ExtandWarranty::where(['id' => $request->id])->update($crud);
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
        public function change_status(Request $request){
            if(auth()->user()->can('extand_warranties-delete')){
                $rules = [
                    'id' => 'required',
                    'status' => 'required'
                ];

                $validator = Validator::make($request->all(), $rules);

                if ($validator->fails())
                    return response()->json(['status' => 422, 'message' => $validator->errors()]);

                $data = ExtandWarranty::where(['id' => $request->id])->first();

                if (!empty($data)) {
                    $update = ExtandWarranty::where(['id' => $request->id])->update(['status' => $request->status, 'updated_at' => date('Y-m-d H:i:s'), 'updated_by' => auth('sanctum')->user()->id]);
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
