<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CarExchangeCategory;
use App\Models\CarExchangeProduct;
use App\Models\CarExchange;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use DB, Validator ,DataTables;


class CarExchangeController extends Controller{
    /** construct */
        public function __construct(){
            $this->middleware('permission:car_exchange-create', ['only' => ['create']]);
            $this->middleware('permission:car_exchange-edit', ['only' => ['edit']]);
            $this->middleware('permission:car_exchange-view', ['only' => ['view']]);
            $this->middleware('permission:car_exchange-delete', ['only' => ['delete']]);
        }
    /** construct */
    /** index */
        public function index(Request $request){
            if($request->ajax()){
                $data = CarExchange::select('car_exchange.*' ,'car_exchange_product.name AS product_name' ,'car_exchange_category.name AS category_name')->leftjoin('car_exchange_product' ,'car_exchange.product_id' ,'car_exchange_product.id')->leftjoin('car_exchange_category' ,'car_exchange.category_id' ,'car_exchange_category.id')->orderBy('id' , 'desc')->get();
                
                return Datatables::of($data)
                        ->addIndexColumn()
                        ->addColumn('action', function($data){
                            $return = '<div class="btn-group">';

                            if(auth()->user()->can('car_exchange-view')){
                                $return .= '<a href="'.route('car_exchange.view', ['id' => base64_encode($data->id)]).'" class="btn btn-default btn-xs">
                                                <i class="fa fa-eye"></i>
                                            </a> &nbsp;';
                            }   
                            
                            if(auth()->user()->can('car_exchange-edit')){
                                $return .= '<a href="'.route('car_exchange.edit', ['id' => base64_encode($data->id)]).'" class="btn btn-default btn-xs">
                                                <i class="fa fa-pencil"></i>
                                            </a> &nbsp;';
                            }   

                            if (auth()->user()->can('car_exchange-delete')) {
                                $return .= '<a href="javascript:;" class="btn btn-default btn-xs dropdown-toggle" data-toggle="dropdown">
                                                    <i class="fa fa-bars"></i>
                                                </a> &nbsp;
                                                <ul class="dropdown-menu">
                                                    <li><a class="dropdown-item" href="javascript:;" onclick="change_status(this);" data-status="active" data-id="' . base64_encode($data->id) . '">Active</a></li>
                                                    <li><a class="dropdown-item" href="javascript:;" onclick="change_status(this);" data-status="inactive" data-id="' . base64_encode($data->id) . '">Inactive</a></li>
                                                    <li><a class="dropdown-item" href="javascript:;" onclick="change_status(this);" data-status="deleted" data-id="' . base64_encode($data->id) . '">Delete</a></li>
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
                            }else{
                                return '-';
                            }
                        })

                        ->rawColumns([ 'action' ,'status'])
                        ->make(true);
            }
            return view('car_exchange.index');
        }
    /** index */
    
    /** Create */
        public function create(Request $request){
            $product = CarExchangeProduct::where(['status' => 'active'])->get();
            $category = CarExchangeCategory::where(['status' => 'active'])->get();
            return view('car_exchange.create')->with(['product' => $product ,'category' => $category]);
        }
    /** Create */

    /** insert */
        public function insert(Request $request)
        {
            if($request->ajax()){ 
                return true ;
            }

            $crud = [
                'product_id' => $request->name,
                'category_id' => $request->category,
                'engine_number' => $request->engine_number,
                'chassis_number' => $request->chassis_number,
                'price' => $request->price,
                'status' => 'active',
                'created_at' => date('Y-m-d H:i:s'),
                'created_by' => auth()->user()->id,
                'updated_at' => date('Y-m-d H:i:s'),
                'updated_by' => auth()->user()->id
            ];
            
            DB::beginTransaction();
            try {
                $last_id = CarExchange::insertGetId($crud);
                if ($last_id) {
                    DB::commit();
                    return redirect()->route('car_exchange')->with('success', 'Record updated successfully');
                } else {
                    DB::rollback();
                    return redirect()->back()->with('error', 'Failed to update record')->withInput();
                }
            } catch (\Throwable $th) {
                DB::rollback();
                return redirect()->back()->with('error', 'Something went wrong, please try again later')->withInput();
            }
        }
    /** insert */

    /** view */
        public function view(Request $request)
        {
            $id = base64_decode($request->id);
            $data = CarExchange::where(['id' => $id])->first();
            $category = CarExchangeCategory::where(['status' => 'active'])->get();
            $product = CarExchangeProduct::where(['status' => 'active'])->get();
            return view('car_exchange.view')->with(['data' => $data ,'category' => $category ,'product' => $product]);
        }
    /** view */

    /** Edit */
        public function edit(Request $request){
            $id = base64_decode($request->id);
            $data = CarExchange::where(['id' => $id])->first();
            $category = CarExchangeCategory::where(['status' => 'active'])->get();
            $product = CarExchangeProduct::where(['status' => 'active'])->get();
            return view('car_exchange.edit')->with(['data' => $data ,'category' =>$category ,'product' =>$product]);
        }
    /** Edit */

    /** update */
        public function update(Request $request)
        {
            if($request->ajax()){ 
                return true ;
            }

            $crud = [
                'product_id' => $request->name,
                'category_id' => $request->category,
                'engine_number' => $request->engine_number,
                'chassis_number' => $request->chassis_number,
                'price' => $request->price,
                'status' => 'active',
                'updated_at' => date('Y-m-d H:i:s'),
                'updated_by' => auth()->user()->id
            ];
            
            DB::beginTransaction();
            try {
                DB::enableQueryLog();
                $update = CarExchange::where(['id' => $request->id])->update($crud);
                if ($update) {
                    
                    DB::commit();
                    return redirect()->route('car_exchange')->with('success', 'Record updated successfully');
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
            $data = CarExchange::where(['id' => $id])->first();

            if(!empty($data)){
                $update = CarExchange::where(['id' => $id])->update(['status' => $request->status, 'updated_at' => date('Y-m-d H:i:s'), 'updated_by' => auth('sanctum')->user()->id]);
                if($update){
                    return response()->json(['code' => 200]);
                }else{
                    return response()->json(['code' => 201]);
                }
            }else{
                return response()->json(['code' => 201]);
            }
        }
    /** change-status */
}
