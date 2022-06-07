<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\Finance;
use App\Models\Branch;
use App\Exports\ExportFinance;
use Maatwebsite\Excel\Facades\Excel;
use Auth, DB, Mail, Validator, File, DataTables, Exception;

class FinanceController extends Controller{
    /** construct */
        public function __construct(){
            $this->middleware('permission:finance-create', ['only' => ['create']]);
            $this->middleware('permission:finance-edit', ['only' => ['edit']]);
            $this->middleware('permission:finance-view', ['only' => ['view']]);
            $this->middleware('permission:finance-delete', ['only' => ['delete']]);
        }
    /** construct */

    /** index */
        public function index(Request $request){
            if($request->ajax()){
                $data = Finance::select('finance.*' ,'branches.name AS branch_name')->leftjoin('branches' ,'finance.branch_id' ,'branches.id')->orderBy('id' , 'desc')->get();
                
                return Datatables::of($data)
                        ->addIndexColumn()
                        ->addColumn('action', function($data){
                            $return = '<div class="btn-group">';

                            if(auth()->user()->can('finance-view')){
                                $return .= '<a href="'.route('finance.view', ['id' => base64_encode($data->id)]).'" class="btn btn-default btn-xs">
                                                <i class="fa fa-eye"></i>
                                            </a> &nbsp;';
                            }   
                            
                            if(auth()->user()->can('finance-edit')){
                                $return .= '<a href="'.route('finance.edit', ['id' => base64_encode($data->id)]).'" class="btn btn-default btn-xs">
                                                <i class="fa fa-pencil"></i>
                                            </a> &nbsp;';
                            }   

                            if (auth()->user()->can('finance-delete')) {
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
            return view('finance.index');
        }
    /** index */

    /** create */
        public function create(Request $request){
            $branch = Branch::where(['status' => 'active'])->get();
            return view('finance.create')->with(['branch' => $branch]);
        }
    /** create */

    /** insert */
        public function insert(Request $request){
            if($request->ajax()){ 
                return true ;
            }

            $crud = [
                'name' => ucfirst($request->name),
                'branch_id' => $request->branch_id,
                'dsa_or_broker' => $request->dsa_or_broker,
                'status' => 'active',
                'created_at' => date('Y-m-d H:i:s'),
                'created_by' => auth()->user()->id,
                'updated_at' => date('Y-m-d H:i:s'),
                'updated_by' => auth()->user()->id
            ];
            
            DB::beginTransaction();
            try {
                DB::enableQueryLog();
                $last_id = Finance::insertGetId($crud);
                if ($last_id) {
                    
                    DB::commit();
                    return redirect()->route('finance')->with('success', 'Record inserted successfully');
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
            $data = Finance::select('finance.*' ,'branches.name AS branch_name')->leftjoin('branches' ,'finance.branch_id' ,'branches.id')->where(['finance.id' => $id])->first();
            return view('finance.view')->with(['data' => $data]);
        }
    /** view */

    /** edit */
        public function edit(Request $request){
            $id = base64_decode($request->id);
            $branch = Branch::where(['status' => 'active'])->get();
            $data = Finance::where(['finance.id' => $id])->first();
            return view('finance.edit')->with(['data' => $data , 'branch' => $branch]);
        }
    /** edit */

    /** update */
        public function update(Request $request){
            if($request->ajax()){return true ;}

            $crud = [
                'name' => ucfirst($request->name) ?? '',
                'dsa_or_broker' => $request->dsa_or_broker ?? '',
                'updated_at' => date('Y-m-d H:i:s'),
                'updated_by' => auth('sanctum')->user()->id
            ];
            
            DB::beginTransaction();
            try {
                DB::enableQueryLog();
                $update = Finance::where(['id' => $request->id])->update($crud);
                if ($update) {
                    
                    DB::commit();
                    return redirect()->route('finance')->with('success', 'Record updated successfully');
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
                $data = Finance::where(['id' => $id])->first();

                if(!empty($data)){
                    $update = Finance::where(['id' => $id])->update(['status' => $request->status, 'updated_at' => date('Y-m-d H:i:s'), 'updated_by' => auth('sanctum')->user()->id]);
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

    /** Export */
        public function export(Request $request){
            if(isset($request->slug) && $request->slug != null){
                $slug = $request->slug;
            }else{
                $slug = 'all';
            }

            $name = 'Finance_'.Date('YmdHis').'.xlsx';

            try {
                return Excel::download(new ExportFinance($slug), $name);
            }catch(\Exception $e){
                return redirect()->back()->with('error' ,$e->getMessage());
            }
        }
    /** Export */
}
