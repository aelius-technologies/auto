<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use App\Models\Product;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Auth, DB, Mail, Validator, File, DataTables;
use Illuminate\Support\Facades\DB as FacadesDB;

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
                $data = Product::orderBy('id' , 'desc')->get();
                
                return Datatables::of($data)
                        ->addIndexColumn()
                        ->addColumn('action', function($data){
                            $return = '<div class="btn-group">';

                            if(auth()->user()->can('products-view')){
                                $return .= '<a href="'.route('products.view', ['id' => base64_encode($data->id)]).'" class="btn btn-default btn-xs">
                                                <i class="fa fa-eye"></i>
                                            </a> &nbsp;';
                            }   
                            
                            if(auth()->user()->can('products-edit')){
                                $return .= '<a href="'.route('products.edit', ['id' => base64_encode($data->id)]).'" class="btn btn-default btn-xs">
                                                <i class="fa fa-pencil"></i>
                                            </a> &nbsp;';
                            }   

                            if (auth()->user()->can('products-delete')) {
                                $return .= '<a href="javascript:;" class="btn btn-default btn-xs dropdown-toggle" data-toggle="dropdown">
                                                    <i class="fa fa-bars"></i>
                                                </a> &nbsp;
                                                <ul class="dropdown-menu">
                                                    <li><a class="dropdown-item" href="javascript:;" onclick="change_status(this);" data-status="active" data-id="' . base64_encode($data->id) . '">Active</a></li>
                                                    <li><a class="dropdown-item" href="javascript:;" onclick="change_status(this);" data-status="inactive" data-id="' . base64_encode($data->id) . '">Inactive</a></li>
                                                    <li><a class="dropdown-item" href="javascript:;" onclick="change_status(this);" data-status="delete" data-id="' . base64_encode($data->id) . '">Delete</a></li>
                                                </ul>';
                            }

                            $return .= '</div>';

                            return $return;
                        })

                     
                        ->editColumn('status', function ($data) {
                            if ($data->status == 'active') {
                                return '<span class="badge badge-pill badge-success">Active</span>';
                            } else if ($data->status == 'inactive') {
                                return '<span class="badge badge-pill badge-warning">Inactive</span>';
                            } else if ($data->status == 'deleted') {
                                return '<span class="badge badge-pill badge-danger">Deleted</span>';
                            }else if ($data->status == 'pdi_hold') {
                                return '<span class="badge badge-pill badge-warning">Hold By PDI</span>';
                            }
                        })

                        ->rawColumns([ 'action' ,'status'])
                        ->make(true);
            }
            return view('product.index');
        }
    /** index */

    /**create */
        public function create(Request $request){
            $data = Category::where(['status' => 'active'])->get();
            return view('product.create')->with(['data' => $data]);
        }
    /**create */

    /** insert */
        public function insert(Request $request){
            if($request->ajax()) { return true; }
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
                DB::beginTransaction();
                try {
                    DB::enableQueryLog();
                    $last_id = Product::insertGetId($crud);
                    if ($last_id) {
                        
                        DB::commit();
                        return redirect()->route('products')->with('success', 'Record inserted successfully');
                    } else {
                        DB::rollback();
                        return redirect()->back()->with('error', 'Failed to insert record')->withInput();
                    }
                } catch (\Throwable $th) {
                    DB::rollback();
                    return redirect()->back()->with('error', 'Something went wrong, please try again later')->withInput();
                }
           
        }
    /** insert */

    /** view */
        public function view(Request $request){
            $id = base64_decode($request->id);
            $data = Product::select('products.*' ,'categories.name AS category_name')->leftjoin('categories' ,'products.category_id' ,'categories.id')->where(['products.id' => $id])->first();
            // dd($data);
            return view('product.view')->with(['data'=>$data]);
        }
    /** view */

    /** edit */
        public function edit(Request $request){
            $id = base64_decode($request->id);
            $category = Category::where(['status' => 'active'])->get();
            $data = Product::where(['id' => $id])->first();

            return view('product.edit')->with(['data'=>$data ,'category' => $category]);
        }
    /** edit */

    /** update */
        public function update(Request $request){
            if($request->ajax()) { return true; }
            $crud = [
                    'category_id' => $request->category_id,
                    'name' => ucfirst($request->name),
                    'ex_showroom_price' => $request->ex_showroom_price,
                    'veriant' => $request->veriant,
                    'interior_color' => $request->interior_color,
                    'exterior_color' => $request->exterior_color,
                    'is_applicable_for_mcp' => $request->is_applicable_for_mcp,
                    'updated_at' => date('Y-m-d H:i:s'),
                    'updated_by' => auth()->user()->id
                ];
                DB::beginTransaction();
                try {
                    DB::getQueryLog();
                    $last_id = Product::where(['id' => $request->id])->update($crud);
                    if ($last_id) {
                        DB::commit();
                        return redirect()->route('products')->with('success', 'Record updated successfully');
                    } else {
                        DB::rollback();
                        return redirect()->back()->with('error', 'Failed to update record')->withInput();
                    }
                } catch (\Throwable $th) {
                    DB::rollback();
                    return redirect()->back()->with('error', 'Something went wrong, please try again later')->withInput();
                }
        }
    /** update */

    /** change-status */
        public function change_status(Request $request){
            if (!$request->ajax()) { exit('No direct script access allowed'); }
            $id = base64_decode($request->id);
            $data = Product::where(['id' => $id])->first();
            if (!empty($data)) {
                $update = Product::where(['id' => $id])->update(['status' => $request->status, 'updated_at' => date('Y-m-d H:i:s'), 'updated_by' => auth('sanctum')->user()->id]);
                    if ($update) {
                        return response()->json(['code' => 200]);
                    }else{
                        return response()->json(['code' => 201]);
                    }
            } else {
                return response()->json(['code' => 201]);
            }
        }
    /** change-status */
}
