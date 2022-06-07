<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tax;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\ImportTax;
use App\Exports\ExportTax;
use Auth, DB, Mail, Validator, File, DataTables ,Exception;

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

                            if(auth()->user()->can('taxes-view')){
                                $return .= '<a href="'.route('tax.view', ['id' => base64_encode($data->id)]).'" class="btn btn-default btn-xs">
                                                <i class="fa fa-eye"></i>
                                            </a> &nbsp;';
                            }   
                            
                            if(auth()->user()->can('taxes-edit')){
                                $return .= '<a href="'.route('tax.edit', ['id' => base64_encode($data->id)]).'" class="btn btn-default btn-xs">
                                                <i class="fa fa-pencil"></i>
                                            </a> &nbsp;';
                            }   

                            if (auth()->user()->can('taxes-delete')) {
                                $return .= '<a href="javascript:;" class="btn btn-default btn-xs dropdown-toggle" data-toggle="dropdown">
                                                    <i class="fa fa-bars"></i>
                                                </a> &nbsp;
                                                <ul class="dropdown-menu">
                                                    <li><a class="dropdown-item" href="javascript:;" onclick="change_status(this);" data-status="active" data-id="' . base64_encode($data->id) . '">Active</a></li>
                                                    <li><a class="dropdown-item" href="javascript:;" onclick="change_status(this);" data-status="inactive" data-id="' . base64_encode($data->id) . '">Inactive</a></li>
                                                    <li><a class="dropdown-item" href="javascript:;" onclick="change_status(this);" data-status="deleted" data-id="' . base64_encode($data->id) . '">Deleted</a></li>
                                                </ul>';
                            }

                            $return .= '</div>';

                            return $return;
                        })

                        ->editColumn('percentage', function ($data) {
                            return $data->percentage."%";
                        })
                        ->editColumn('status', function ($data) {
                            if ($data->status == 'active') {
                                return '<span class="badge badge-pill badge-success">Active</span>';
                            } else if ($data->status == 'inactive') {
                                return '<span class="badge badge-pill badge-warning">Inactive</span>';
                            } else if ($data->status == 'deleted') {
                                return '<span class="badge badge-pill badge-danger">Deleted</span>';
                            }
                        })

                        ->rawColumns(['action' ,'percentage' ,'status'])
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
            if($request->ajax()) { return true; }
            $crud = [
                'name' => $request->name,
                'percentage' => $request->percentage,
                'status' => 'active',
                'created_at' => date('Y-m-d H:i:s'),
                'created_by' => auth()->user()->id,
                'updated_at' => date('Y-m-d H:i:s'),
                'updated_by' => auth()->user()->id
            ];

            $last_id = Tax::insertGetId($crud);

            if ($last_id) {
                return redirect()->route('tax')->with(['success' => 'Record inserted successfully']);
            } else {
                return redirect()->back()->with(['error'=>'Faild to insert Record!'])->withInput();
            }
        
        }
    /** insert */

    /** view */
        public function view(Request $request){
            $id = base64_decode($request->id);
            $data = Tax::where(['id' => $id])->first();
            
            return view('taxes.view')->with(['data'=>$data]);
        }
        /** view */
        
        /** edit */
        public function edit(Request $request){
            $id = base64_decode($request->id);
            $data = Tax::where(['id' => $id])->first();

            return view('taxes.edit')->with(['data'=>$data]);
        }
    /** edit */

    /** update */
        public function update(Request $request){
            if($request->ajax()){ return true; }
            $id = $request->id;
            
            $crud = [
                'name' => $request->name,
                'percentage' => $request->percentage,
                'updated_at' => date('Y-m-d H:i:s'),
                'updated_by' => auth()->user()->id
            ];
            DB::enableQueryLog();
            $update = Tax::where(['id' => $id])->update($crud);
            // dd(DB::getQueryLog());
            if ($update){
                return redirect()->route('tax')->with(['success' => 'Record updated successfully']);
            }else{
                return redirect()->back()->with(['error'=>'Faild to update Record!'])->withInput();
            }
        }
    /** update */

    /** change-status */
        public function change_status(Request $request){
            if (!$request->ajax()) { exit('No direct script access allowed'); }
            $id = base64_decode($request->id);
            
            $data = Tax::where(['id' => $id])->first();

            if (!empty($data)) {
                $update = Tax::where(['id' => $id])->update(['status' => $request->status, 'updated_at' => date('Y-m-d H:i:s'), 'updated_by' => auth('sanctum')->user()->id]);
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
            $import = Excel::import(new ImportTax(), $request->file('file'));    
            if($import){
                Excel::store(new ImportTax(), 'Tax_'.Date('YmdHis').'.xlsx' ,'excel_import');
                return redirect()->route('tax')->with('sucess' ,'File Imported Sucessfully');
            }
        }
    /** Import */

    /** Export */
        public function export(Request $request){
            $slug = $request->slug;
            $name = 'Tax_'.Date('YmdHis').'.xlsx';
      
            try {
                return Excel::download(new ExportTax($slug), $name);
            }catch(\Exception $e){
                return redirect()->back()->with('error' ,$e->getMessage());
            }
        }
    /** Export */
}
