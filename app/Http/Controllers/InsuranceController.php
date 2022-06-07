<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Insurance;
use App\Exports\ExportInsurance;
use App\Imports\ImportInsurance;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Maatwebsite\Excel\Facades\Excel;
use Auth, DB, Mail, Validator, File, DataTables, Exception;

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
            if($request->ajax()){
                $data = Insurance::orderBy('id' , 'desc')->get();
                
                return Datatables::of($data)
                        ->addIndexColumn()
                        ->addColumn('action', function($data){
                            $return = '<div class="btn-group">';

                            if(auth()->user()->can('insurance-view')){
                                $return .= '<a href="'.route('insurance.view', ['id' => base64_encode($data->id)]).'" class="btn btn-default btn-xs">
                                                <i class="fa fa-eye"></i>
                                            </a> &nbsp;';
                            }   
                            
                            if(auth()->user()->can('insurance-edit')){
                                $return .= '<a href="'.route('insurance.edit', ['id' => base64_encode($data->id)]).'" class="btn btn-default btn-xs">
                                                <i class="fa fa-pencil"></i>
                                            </a> &nbsp;';
                            }   

                            if (auth()->user()->can('insurance-delete')) {
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
            return view('insurance.index');
        }
    /** index */

    /** create */
        public function create(Request $request){
            return view('insurance.create');
        }
    /** create */
    
    /** insert */
        public function insert(Request $request){
            if($request->ajax()){
                return true;
            }
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

            DB::beginTransaction();
            try {
                DB::enableQueryLog();
                $last_id = Insurance::insertGetId($crud);
                if ($last_id) {
                    
                    DB::commit();
                    return redirect()->route('insurance')->with('success', 'Record inserted successfully');
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
            $data = Insurance::where(['id' => $id])->first();
            return view('insurance.view')->with(['data' => $data]);
        }
    /** view */
        
    /** edit */
        public function edit(Request $request){
            $id = base64_decode($request->id);
            $data = Insurance::where(['id' => $id])->first();
            return view('insurance.edit')->with(['data' => $data]);
        }
    /** edit */

    /** update */
        public function update(Request $request){
            if($request->ajax()){ return true ; }
            $crud = [
                'name' => ucfirst($request->name),
                'type' => $request->type,
                'years' => $request->years,
                'amount' => $request->amount,
                'updated_at' => date('Y-m-d H:i:s'),
                'updated_by' => auth()->user()->id
            ];

            DB::beginTransaction();
            try {
                DB::enableQueryLog();
                $update = Insurance::where(['id' => $request->id])->update($crud);
                if ($update){
                    
                    DB::commit();
                    return redirect()->route('insurance')->with('success', 'Record inserted successfully');
                } else {
                    DB::rollback();
                    return redirect()->back()->with('error', 'Failed to insert record')->withInput();
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

            $data = Insurance::where(['id' => $id])->first();

            if (!empty($data)) {
                $update = Insurance::where(['id' => $id])->update(['status' => $request->status, 'updated_at' => date('Y-m-d H:i:s'), 'updated_by' => auth('sanctum')->user()->id]);
                if ($update) {
                    return response()->json(['code' => 200]);
                } else {
                    return response()->json(['code' => 201]);
                }
            } else {
                return response()->json(['code' => 201]);
            }
        }
    /** change-status */

    /** Import */
        public function import(Request $request){
            $import = Excel::import(new ImportInsurance(), $request->file('file'));    
            if($import){
                Excel::store(new ImportInsurance(), 'Insurance_'.Date('YmdHis').'.xlsx' ,'excel_import');
                return redirect()->route('insurance')->with('sucess' ,'File Imported Sucessfully');
            }
        }
    /** Import */

    /** Export */
        public function export(Request $request){
            $slug = $request->slug;
            $name = 'Insaurance_'.Date('YmdHis').'.xlsx';
    
            try {
                return Excel::download(new ExportInsurance($slug), $name);
            }catch(\Exception $e){
                return redirect()->back()->with('error', $e->getMessage());
            }
        }
    /** Export */
}
