<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Auth, DB, Mail, Validator, File, DataTables;

class ProductController extends Controller{
    /** construct */
        public function __construct(){
            $this->middleware('permission:products-create', ['only' => ['create']]);
            $this->middleware('permission:products-edit', ['only' => ['edit']]);
            $this->middleware('permission:products-view', ['only' => ['view']]);
            $this->middleware('permission:products-delete', ['only' => ['delete']]);
        }
    /** construct */

    /** index */
        public function index(Request $request){
            if($request->ajax()){
                $data = Product::select('product.id' ,'obf.customer_name' ,'obf.booking_date' ,'obf.status','products.name AS product')->leftjoin('products' ,'obf.product_id' ,'products.id')->where('obf.status' ,'pending')->get();
                
                return Datatables::of($data)
                        ->addIndexColumn()
                        ->addColumn('action', function($data){
                            $return = '<div class="btn-group">';

                            if(auth()->user()->can('obf_approval-view')){
                                $return .= '<a href="'.route('obf_approval.view', ['id' => base64_encode($data->id)]).'" class="btn btn-default btn-xs">
                                                <i class="fa fa-eye"></i>
                                            </a> &nbsp;';
                            }   

                            if (auth()->user()->can('obf_approval-delete')) {
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

                        ->editColumn('name', function($data) {
                            return $data->customer_name;
                        })
                        ->editColumn('status', function ($data) {
                            if ($data->status == 'accepted') {
                                return '<span class="badge badge-pill badge-success">Active</span>';
                            } else if ($data->status == 'pending') {
                                return '<span class="badge badge-pill badge-warning">Pending</span>';
                            } else if ($data->status == 'deleted') {
                                return '<span class="badge badge-pill badge-danger">Deleted</span>';
                            }else if ($data->status == 'account_rejected') {
                                return '<span class="badge badge-pill badge-danger">Account Rejected</span>';
                            }else if ($data->status == 'obf_rejected') {
                                return '<span class="badge badge-pill badge-danger">OBF Rejected</span>';
                            }else if ($data->status == 'rejected') {
                                return '<span class="badge badge-pill badge-danger">Rejected</span>';
                            }else if ($data->status == 'obf_accepted') {
                                return '<span class="badge badge-pill badge-success">Obf Accepted</span>';
                            }else if ($data->status == 'account_accepted') {
                                return '<span class="badge badge-pill badge-success">Account Accepted</span>';
                            }
                        })

                        ->rawColumns(['name', 'action' ,'status'])
                        ->make(true);
            }
            return view('product.index');
        }
    /** index */

    /**create */
        public function create(Request $request){
            return view('product.create');
        }
    /**create */

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

                $last_id = Product::insertGetId($crud);

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
            $data = Product::where(['id' => $request->id])->first();

            return view('product.view')->with(['data'=>$data]);
        }
    /** view */

    /** edit */
        public function edit(Request $request){
            $data = Product::where(['id' => $request->id])->first();

            return view('product.edit')->with(['data'=>$data]);
        }
    /** edit */

    /** update */
        public function update(Request $request){
            if(auth()->user()->can('products-edit')){
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
                    'updated_at' => date('Y-m-d H:i:s'),
                    'updated_by' => auth('sanctum')->user()->id
                ];


                $update = Product::where(['id' => $request->id])->update($crud);
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
            if(auth()->user()->can('products-delete')){
                $rules = [
                    'id' => 'required',
                    'status' => 'required'
                ];

                $validator = Validator::make($request->all(), $rules);

                if ($validator->fails())
                    return response()->json(['status' => 422, 'message' => $validator->errors()]);

                $data = Product::where(['id' => $request->id])->first();

                if (!empty($data)) {
                    $update = Product::where(['id' => $request->id])->update(['status' => $request->status, 'updated_at' => date('Y-m-d H:i:s'), 'updated_by' => auth('sanctum')->user()->id]);
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
