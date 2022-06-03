<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CarExchangeCategory;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use DB, Validator ,DataTables;


class CarExchangeCategoryController extends Controller{
    /** construct */
        public function __construct(){
            $this->middleware('permission:car_exchange_category-create', ['only' => ['create']]);
            $this->middleware('permission:car_exchange_category-edit', ['only' => ['edit']]);
            $this->middleware('permission:car_exchange_category-view', ['only' => ['view']]);
            $this->middleware('permission:car_exchange_category-delete', ['only' => ['delete']]);
        }
    /** construct */
    /** index */
        public function index(Request $request){
            if($request->ajax()){
                $data = CarExchangeCategory::orderBy('id' , 'desc')->get();
                
                return Datatables::of($data)
                        ->addIndexColumn()
                        ->addColumn('action', function($data){
                            $return = '<div class="btn-group">';

                            if(auth()->user()->can('car_exchange_category-view')){
                                $return .= '<a href="'.route('car_exchange_category.view', ['id' => base64_encode($data->id)]).'" class="btn btn-default btn-xs">
                                                <i class="fa fa-eye"></i>
                                            </a> &nbsp;';
                            }   
                            
                            if(auth()->user()->can('car_exchange_category-edit')){
                                $return .= '<a href="'.route('car_exchange_category.edit', ['id' => base64_encode($data->id)]).'" class="btn btn-default btn-xs">
                                                <i class="fa fa-pencil"></i>
                                            </a> &nbsp;';
                            }   

                            if (auth()->user()->can('car_exchange_category-delete')) {
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
            return view('car_exchange_category.index');
        }
    /** index */
 
    /** insert */
        public function insert(Request $request)
        {
            if($request->ajax()){ 
                return true ;
            }

            $crud = [
                'name' => ucfirst($request->name),
                'status' => 'active',
                'created_at' => date('Y-m-d H:i:s'),
                'created_by' => auth()->user()->id,
                'updated_at' => date('Y-m-d H:i:s'),
                'updated_by' => auth()->user()->id
            ];
            
            DB::beginTransaction();
            try {
                DB::enableQueryLog();
                $last_id = CarExchangeCategory::insertGetId($crud);
                if ($last_id) {
                    
                    DB::commit();
                    return redirect()->route('car_exchange_category')->with('success', 'Record inserted successfully');
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
        public function view(Request $request)
        {
            $id = base64_decode($request->id);
            $data = CarExchangeCategory::where(['id' => $id])->first();
            return view('car_exchange_category.view')->with(['data' => $data]);
        }
    /** view */

    /** Edit */
        public function edit(Request $request){
            $id = base64_decode($request->id);
            $data = CarExchangeCategory::where(['id' => $id])->first();
            return view('car_exchange_category.edit')->with(['data' => $data]);
        }
    /** Edit */

    /** update */
        public function update(Request $request)
        {
            if($request->ajax()){ 
                return true ;
            }

            $crud = [
                'name' => ucfirst($request->name),
                'status' => 'active',
                'created_at' => date('Y-m-d H:i:s'),
                'created_by' => auth()->user()->id,
                'updated_at' => date('Y-m-d H:i:s'),
                'updated_by' => auth()->user()->id
            ];
            
            DB::beginTransaction();
            try {
                DB::enableQueryLog();
                $update = CarExchangeCategory::where(['id' => $request->id])->update($crud);
                if ($update) {
                    
                    DB::commit();
                    return redirect()->route('car_exchange_category')->with('success', 'Record updated successfully');
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
            $data = CarExchangeCategory::where(['id' => $id])->first();

            if(!empty($data)){
                $update = CarExchangeCategory::where(['id' => $id])->update(['status' => $request->status, 'updated_at' => date('Y-m-d H:i:s'), 'updated_by' => auth('sanctum')->user()->id]);
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
