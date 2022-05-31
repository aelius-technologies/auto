<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tax;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Auth, DB, Mail, Validator, File, DataTables;

class TaxesController extends Controller{
    /** construct */
        public function __construct(){
            $this->middleware('permission:taxes-create', ['only' => ['create']]);
            $this->middleware('permission:taxes-edit', ['only' => ['edit']]);
            $this->middleware('permission:taxes-view', ['only' => ['view']]);
            $this->middleware('permission:taxes-delete', ['only' => ['delete']]);
        }
    /** construct */

    /** index */
        public function index(Request $request){
            if($request->ajax()){
                $data = Tax::all();
                
                return Datatables::of($data)
                        ->addIndexColumn()
                        ->addColumn('action', function($data){
                            $return = '<div class="btn-group">';

                            if(auth()->user()->can('tax-view')){
                                $return .= '<a href="'.route('tax.view', ['id' => base64_encode($data->id)]).'" class="btn btn-default btn-xs">
                                                <i class="fa fa-eye"></i>
                                            </a> &nbsp;';
                            }   

                            if (auth()->user()->can('tax-delete')) {
                                $return .= '<a href="javascript:;" class="btn btn-default btn-xs dropdown-toggle" data-toggle="dropdown">
                                                    <i class="fa fa-bars"></i>
                                                </a> &nbsp;
                                                <ul class="dropdown-menu">
                                                    <li><a class="dropdown-item" href="javascript:;" onclick="change_status(this);" data-status="obf_accepted" data-id="' . base64_encode($data->id) . '">Obf Accepted</a></li>
                                                    <li><a class="dropdown-item" href="javascript:;" onclick="change_status(this);" data-status="obf_rejected" data-id="' . base64_encode($data->id) . '">Obf Rejected</a></li>
                                                </ul>';
                            }

                            $return .= '</div>';

                            return $return;
                        })

                        ->editColumn('status', function ($data) {
                            if ($data->status == 'active') {
                                return '<span class="badge badge-pill badge-success">Active</span>';
                            } else if ($data->status == 'inactive') {
                                return '<span class="badge badge-pill badge-warning">Pending</span>';
                            } else if ($data->status == 'deleted') {
                                return '<span class="badge badge-pill badge-danger">Deleted</span>';
                            }
                        })

                        ->rawColumns(['action' ,'status'])
                        ->make(true);
            }
            return view('taxes.index');
        }
    /** index */

    /** create */
        public function create(Request $request){
            return view('taxes.create');
        }
    /** create */

    /** insert */
        public function insert(Request $request){
            if(auth()->user()->can('products-create')){
                $rules = [
                    'category_id' => 'required',
                    'name' => 'required',
                    'ex_showroom_price' => 'required',
                    'veriant' => 'required',
                    'interior_color' => 'required',
                    'exterior_color' => 'required',
                    'is_applicable_for_mcp' => 'required'
                ];

                $validator = Validator::make($request->all(), $rules);

                if ($validator->fails())
                    return response()->json(['status' => 422, 'message' => $validator->errors()]);

                $crud = [
                    'category_id' => $request->category_id,
                    'name' => ucfirst($request->name),
                    'ex_showroom_price' => $request->ex_showroom_price,
                    'veriant' => $request->veriant,
                    'interior_color' => $request->interior_color,
                    'exterior_color' => $request->exterior_color,
                    'is_applicable_for_mcp' => $request->is_applicable_for_mcp,
                    'status' => 'active',
                    'created_at' => date('Y-m-d H:i:s'),
                    'created_by' => auth()->user()->id,
                    'updated_at' => date('Y-m-d H:i:s'),
                    'updated_by' => auth()->user()->id
                ];

                $last_id = Tax::insertGetId($crud);

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
            $data = Tax::where(['id' => $request->id])->first();

            return view('taxes.view')->with(['data'=>$data]);
        }
    /** view */

    /** edit */
        public function edit(Request $request){
            $data = Tax::where(['id' => $request->id])->first();

            return view('taxes.edit')->with(['data'=>$data]);
        }
    /** edit */

    /** update */
        public function update(Request $request){
            if(auth()->user()->can('taxes-edit')){
                $rules = [
                    'id' => 'required',
                    'name' => 'required',
                    'percentage' => 'required',
                ];

                $validator = Validator::make($request->all(), $rules);

                if ($validator->fails())
                    return response()->json(['status' => 422, 'message' => $validator->errors()]);

                $crud = [
                    'category_id' => $request->category_id,
                    'name' => ucfirst($request->name),
                    'ex_showroom_price' => $request->ex_showroom_price,
                    'veriant' => $request->veriant,
                    'interior_color' => $request->interior_color,
                    'exterior_color' => $request->exterior_color,
                    'is_applicable_for_mcp' => $request->is_applicable_for_mcp,
                    'updated_at' => date('Y-m-d H:i:s'),
                    'updated_by' => auth('sanctum')->user()->id
                ];


                $update = Tax::where(['id' => $request->id])->update($crud);
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
            if(auth()->user()->can('taxes-delete')){
                $rules = [
                    'id' => 'required',
                    'status' => 'required'
                ];

                $validator = Validator::make($request->all(), $rules);

                if ($validator->fails())
                    return response()->json(['status' => 422, 'message' => $validator->errors()]);

                $data = Tax::where(['id' => $request->id])->first();

                if (!empty($data)) {
                    $update = Tax::where(['id' => $request->id])->update(['status' => $request->status, 'updated_at' => date('Y-m-d H:i:s'), 'updated_by' => auth('sanctum')->user()->id]);
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
