<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Accessory;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Auth, Validator , DB, Mail ,DataTables;

class AccessoryController extends Controller
{
    public function __construct(){
        $this->middleware('permission:accessories-create', ['only' => ['create']]);
        $this->middleware('permission:accessories-edit', ['only' => ['edit']]);
        $this->middleware('permission:accessories-view', ['only' => ['view']]);
        $this->middleware('permission:accessories-delete', ['only' => ['delete']]);
    }
    /** index */
        public function index(Request $request){
            if($request->ajax()){
                $data = Accessory::orderBy('id' , 'desc')->get();
                
                return Datatables::of($data)
                    ->addIndexColumn()
                    ->addColumn('action', function($data){
                        $return = '<div class="btn-group">';

                        if(auth()->user()->can('accessories-view')){
                            $return .= '<a href="'.route('accessory.view', ['id' => base64_encode($data->id)]).'" class="btn btn-default btn-xs">
                                            <i class="fa fa-eye"></i>
                                        </a> &nbsp;';
                        }   
                        
                        if(auth()->user()->can('accessories-edit')){
                            $return .= '<a href="'.route('accessory.edit', ['id' => base64_encode($data->id)]).'" class="btn btn-default btn-xs">
                                            <i class="fa fa-pencil"></i>
                                        </a> &nbsp;';
                        }   

                        if (auth()->user()->can('accessories-delete')) {
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
            return view('accessory.index');
        }
    /** index */

    /** create */
        public function create(Request $request){
            return view('accessory.create');
        }
    /** create */

    /** insert */
        public function insert(Request $request){
            if($request->ajax()){
                return true ;
            }

            $crud = [
                'name' => ucfirst($request->name),
                'type' => $request->type,
                'hsn_number' => $request->hsn_number,
                'price' => $request->price,
                'model_number' => $request->model_number,
                'warranty' => $request->warranty,
                'status' => 'active',
                'created_at' => date('Y-m-d H:i:s'),
                'created_by' => auth()->user()->id,
                'updated_at' => date('Y-m-d H:i:s'),
                'updated_by' => auth()->user()->id
            ];

            DB::beginTransaction();
            try {
                DB::enableQueryLog();
                $last_id = Accessory::insertGetId($crud);
                if ($last_id) {
                    DB::commit();
                    return redirect()->route('accessory')->with('success', 'Record inserted successfully');
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
            $data = Accessory::where(['id' => $id])->first();
            return view('accessory.view')->with(['data' => $data]);
        }
    /** view */

    /** edit */
        public function edit(Request $request){
            $id = base64_decode($request->id);
            $data = Accessory::where(['id' => $id])->first();
            return view('accessory.edit')->with(['data' => $data]);
        }
    /** edit */     

    /** update */
        public function update(Request $request){
            if($request->ajax()){
                return true ;
            }
            $id = $request->id;
            
            $crud = [
                'name' => ucfirst($request->name),
                'type' => $request->type,
                'hsn_number' => $request->hsn_number,
                'price' => $request->price,
                'model_number' => $request->model_number,
                'warranty' => $request->warranty,
                'updated_at' => date('Y-m-d H:i:s'),
                'updated_by' => auth()->user()->id
            ];

            DB::beginTransaction();
            try {
                DB::enableQueryLog();
                $update = Accessory::where(['id' => $id])->update($crud);
      
                if ($update) {
                    DB::commit();
                    return redirect()->route('accessory')->with('success', 'Record updated successfully');
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
            $data = Accessory::where(['id' => $id])->first();

            if(!empty($data)){
                $update = Accessory::where(['id' => $id])->update(['status' => $request->status, 'updated_at' => date('Y-m-d H:i:s'), 'updated_by' => auth()->user()->id]);
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
