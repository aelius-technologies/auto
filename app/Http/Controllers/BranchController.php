<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Branch;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Auth, Validator , DB, Mail ,DataTables;

class BranchController extends Controller
{
    public function __construct(){
        $this->middleware('permission:branches-create', ['only' => ['create']]);
        $this->middleware('permission:branches-edit', ['only' => ['edit']]);
        $this->middleware('permission:branches-view', ['only' => ['view']]);
        $this->middleware('permission:branches-delete', ['only' => ['delete']]);
    }
    /** index */
        public function index(Request $request){
            if($request->ajax()){
                $data = Branch::orderBy('id' , 'desc')->get();
                
                return Datatables::of($data)
                    ->addIndexColumn()
                    ->addColumn('action', function($data){
                        $return = '<div class="btn-group">';

                        if(auth()->user()->can('branches-view')){
                            $return .= '<a href="'.route('branches.view', ['id' => base64_encode($data->id)]).'" class="btn btn-default btn-xs">
                                            <i class="fa fa-eye"></i>
                                        </a> &nbsp;';
                        }   
                        
                        if(auth()->user()->can('branches-edit')){
                            $return .= '<a href="'.route('branches.edit', ['id' => base64_encode($data->id)]).'" class="btn btn-default btn-xs">
                                            <i class="fa fa-pencil"></i>
                                        </a> &nbsp;';
                        }   

                        if (auth()->user()->can('branches-delete')) {
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
            return view('branches.index');
        }
    /** index */

    /** create */
        public function create(Request $request){
            return view('branches.create');
        }
    /** create */

    /** insert */
        public function insert(Request $request){
            if($request->ajax()){
                return true ;
            }

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
                'created_by' => auth()->user()->id,
                'updated_at' => date('Y-m-d H:i:s'),
                'updated_by' => auth()->user()->id
            ];

            DB::beginTransaction();
            try {
                DB::enableQueryLog();
                $last_id = Branch::insertGetId($crud);
                if ($last_id) {
                    
                    DB::commit();
                    return redirect()->route('branches')->with('success', 'Record inserted successfully');
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
            $data = Branch::where(['id' => $id])->first();
            return view('branches.view')->with(['data' => $data]);
        }
    /** view */

    /** edit */
        public function edit(Request $request){
            $id = base64_decode($request->id);
            $data = Branch::where(['id' => $id])->first();
            return view('branches.edit')->with(['data' => $data]);
        }
    /** edit */     

    /** update */
        public function update(Request $request){
            if($request->ajax()){
                return true ;
            }
            $crud = [
                'name' => ucfirst($request->name) ?? '',
                'city' => $request->city ?? '',
                'address' => $request->address ?? '',
                'contact_number' => $request->contact_number ?? '',
                'manager' => $request->manager ?? '',
                'manager_contact_number' => $request->manager_contact_number ?? '',
                'email' => $request->email ?? '',
                'updated_at' => date('Y-m-d H:i:s'),
                'updated_by' => auth()->user()->id
            ];

            DB::beginTransaction();
            try {
                DB::enableQueryLog();
                $update = Branch::where(['id' => $request->id])->update($crud);
                if ($update) {
                    
                    DB::commit();
                    return redirect()->route('branches')->with('success', 'Record updated successfully');
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
            $data = Branch::where(['id' => $id])->first();

            if(!empty($data)){
                $update = Branch::where(['id' => $id])->update(['status' => $request->status, 'updated_at' => date('Y-m-d H:i:s'), 'updated_by' => auth()->user()->id]);
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
