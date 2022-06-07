<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ExtandWarranty;
use App\Exports\ExportExtandWarranty;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Maatwebsite\Excel\Facades\Excel;
use Auth, DB, Mail, Validator, File, DataTables, Exception;

class ExtandWarrantyController extends Controller{
    /** construct */
        public function __construct(){
            $this->middleware('permission:extand_warranties-create', ['only' => ['create']]);
            $this->middleware('permission:extand_warranties-edit', ['only' => ['edit']]);
            $this->middleware('permission:extand_warranties-view', ['only' => ['view']]);
            $this->middleware('permission:extand_warranties-delete', ['only' => ['delete']]);
        }
    /** construct */

    /** index */ 
        public function index(Request $request){
            if($request->ajax()){
                $data = ExtandWarranty::orderBy('id' , 'desc')->get();
                
                return Datatables::of($data)
                        ->addIndexColumn()
                        ->addColumn('action', function($data){
                            $return = '<div class="btn-group">';

                            if(auth()->user()->can('extand_warranties-view')){
                                $return .= '<a href="'.route('extand_warranties.view', ['id' => base64_encode($data->id)]).'" class="btn btn-default btn-xs">
                                                <i class="fa fa-eye"></i>
                                            </a> &nbsp;';
                            }   
                            
                            if(auth()->user()->can('extand_warranties-edit')){
                                $return .= '<a href="'.route('extand_warranties.edit', ['id' => base64_encode($data->id)]).'" class="btn btn-default btn-xs">
                                                <i class="fa fa-pencil"></i>
                                            </a> &nbsp;';
                            }   

                            if (auth()->user()->can('extand_warranties-delete')) {
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

                     
                        ->editColumn('years', function ($data) {
                            return $data->years.' Years';
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

                        ->rawColumns(['years', 'action' ,'status'])
                        ->make(true);
            }
            return view('extand_warranties.index');
        }
    /** index */

    /** create */
        public function create(Request $request){
            return view('extand_warranties.create');
        }
    /** create */

    /** insert */
        public function insert(Request $request){
            if($request->ajax()){
                return true;
            }
            $crud = [
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
                $last_id = ExtandWarranty::insertGetId($crud);
                if ($last_id) {
                    
                    DB::commit();
                    return redirect()->route('extand_warranties')->with('success', 'Record inserted successfully');
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
            $data = ExtandWarranty::where(['id' => $id])->first();
            return view('extand_warranties.view')->with(['data' => $data]);
        }
    /** view */
        
    /** edit */
        public function edit(Request $request){
            $id = base64_decode($request->id);
            $data = ExtandWarranty::where(['id' => $id])->first();
            return view('extand_warranties.edit')->with(['data' => $data]);
        }
    /** edit */

    /** update */
        public function update(Request $request){
            if($request->ajax()){
                return true;
            }
   
            $crud = [
                'years' => $request->years,
                'amount' => $request->amount,
                'updated_at' => date('Y-m-d H:i:s'),
                'updated_by' => auth('sanctum')->user()->id
            ];

            DB::beginTransaction();
            try {
                DB::enableQueryLog();
                $update = ExtandWarranty::where(['id' => $request->id])->update($crud);
                if ($update) {
                    
                    DB::commit();
                    return redirect()->route('extand_warranties')->with('success', 'Record updated successfully');
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
            $data = ExtandWarranty::where(['id' => $id])->first();

            if (!empty($data)) {
                $update = ExtandWarranty::where(['id' => $id])->update(['status' => $request->status, 'updated_at' => date('Y-m-d H:i:s'), 'updated_by' => auth()->user()->id]);
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

    /** Export */
        public function export(Request $request){
            $slug = $request->slug;
            $name = 'Extand_warranty_'.Date('YmdHis').'.xlsx';

            try {
                return Excel::download(new ExportExtandWarranty($slug), $name);
            }catch(\Exception $e){
                return redirect()->back()->with('error', $e->getMessage());
            }
        }
    /** Export */
}
