<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\Branch;
use App\Models\Inventory;
use App\Models\Category;
use App\Http\Requests\InventoryRequest;
use Auth, DB, Mail, Validator, File, DataTables;

class InventoryController extends Controller{
    /** construct */
        public function __construct(){
            $this->middleware('permission:inventory-create', ['only' => ['create']]);
            $this->middleware('permission:inventory-edit', ['only' => ['edit']]);
            $this->middleware('permission:inventory-view', ['only' => ['view']]);
            $this->middleware('permission:inventory-delete', ['only' => ['delete']]);
        }
    /** construct */

    /** index */
        public function index(Request $request){
            if($request->ajax()){
                $data = DB::table('inventory as i')
                            ->select('i.id', 'c.name as category', 'i.name', 'b.name as branch', 'i.veriant', 'i.ex_showroom_price', 'i.status')
                            ->leftjoin('categories as c', 'c.id', 'i.category_id')
                            ->leftjoin('branches as b', 'b.id', 'i.branch_id')
                            ->orderBy('i.id' , 'desc')
                            ->get();

                return Datatables::of($data)
                        ->addIndexColumn()
                        ->addColumn('action', function ($data) {
                            $return = '<div class="btn-group">';
    
                            if (auth()->user()->can('inventory-view')) {
                                $return .=  '<a href="'.route('inventory.view', ['id' => base64_encode($data->id)]).'" class="btn btn-default btn-xs">
                                                    <i class="fa fa-eye"></i>
                                                </a> &nbsp;';
                            }
    
                            if (auth()->user()->can('inventory-edit')) {
                                $return .= '<a href="'.route('inventory.edit', ['id' => base64_encode($data->id)]).'" class="btn btn-default btn-xs">
                                                    <i class="fa fa-edit"></i>
                                                </a> &nbsp;';
                            }
    
                            if (auth()->user()->can('inventory-delete')) {
                                if($data->status == 'sold' || $data->status == 'pdi_hold'){
                                    $return .= '';
                                }else{
                                    $return .= '<a href="javascript:;" class="btn btn-default btn-xs dropdown-toggle" data-toggle="dropdown">
                                                        <i class="fa fa-bars"></i>
                                                    </a> &nbsp;
                                                    <ul class="dropdown-menu">
                                                        <li><a class="dropdown-item" href="javascript:;" onclick="change_status(this);" data-status="active" data-id="' . base64_encode($data->id) . '">Active</a></li>
                                                        <li><a class="dropdown-item" href="javascript:;" onclick="change_status(this);" data-status="inactive" data-id="' . base64_encode($data->id) . '">Inactive</a></li>
                                                        <li><a class="dropdown-item" href="javascript:;" onclick="change_status(this);" data-status="deleted" data-id="' . base64_encode($data->id) . '">Delete</a></li>
                                                    </ul>';
                                }
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
                            } else if ($data->status == 'pdi_hold') {
                                return '<span class="badge badge-pill badge-info">PDI Hold</span>';
                            } else if ($data->status == 'sold') {
                                return '<span class="badge badge-pill badge-danger">Sold</span>';
                            } else {
                                return '-';
                            }
                        })
                        ->rawColumns(['action' ,'status'])
                        ->make(true);
                }
            return view('inventory.index');
        }
    /** index */

    /** create */
        public function create(Request $request){
            $categories = Category::select('id', 'name')->where(['status' => 'active'])->get();
            $branches = Branch::select('id', 'name')->where(['status' => 'active'])->get();

            return view('inventory.create')->with(['categories' => $categories, 'branches' => $branches]);
        }
    /** create */

    /** insert */
        public function insert(InventoryRequest $request){
            if($request->ajax()){ return true ;}

            $data = [
                'category_id' => $request->category_id ?? NULL,
                'name' => $request->name ?? NULL,
                'branch_id' => $request->branch_id ?? NULL,
                'veriant' => $request->veriant ?? NULL,
                'key_number' => $request->key_number ?? NULL,
                'engine_number' => $request->engine_number ?? NULL,
                'chassis_number' => $request->chassis_number ?? NULL,
                'vin_number' => $request->vin_number ?? NULL,
                'ex_showroom_price' => $request->ex_showroom_price ?? NULL,
                'interior_color' => $request->interior_color ?? NULL,
                'exterior_color' => $request->exterior_color ?? NULL,
                'status' => 'active',
                'created_at' => date('Y-m-d H:i:s'),
                'created_by' => auth()->user()->id,
                'updated_at' => date('Y-m-d H:i:s'),
                'updated_by' => auth()->user()->id
            ];
            
            $last_id = Inventory::insertGetId($data);
            
            if($last_id)
                return redirect()->route('inventory')->with('success', 'Record inserted successfully');
            else
                return redirect()->back()->with('error', 'Failed to insert record')->withInput();
        }
    /** insert */

    /** view */
        public function view(Request $request){
            $id = base64_decode($request->id);
            
            $categories = Category::select('id', 'name')->where(['status' => 'active'])->get();
            $branches = Branch::select('id', 'name')->where(['status' => 'active'])->get();
            $data = Inventory::where(['id' => $id])->first();

            return view('inventory.view')->with(['data' => $data, 'branches' => $branches, 'categories' => $categories]);
        }
    /** view */

    /** edit */
        public function edit(Request $request){
            $id = base64_decode($request->id);

            $categories = Category::select('id', 'name')->where(['status' => 'active'])->get();
            $branches = Branch::select('id', 'name')->where(['status' => 'active'])->get();
            $data = Inventory::where(['id' => $id])->first();
            
            return view('inventory.edit')->with(['data' => $data, 'branches' => $branches, 'categories' => $categories]);
        }
    /** edit */

    /** update */
        public function update(InventoryRequest $request){
            if($request->ajax()){ return true; }

            $data = [
                'category_id' => $request->category_id ?? NULL,
                'name' => $request->name ?? NULL,
                'branch_id' => $request->branch_id ?? NULL,
                'veriant' => $request->veriant ?? NULL,
                'key_number' => $request->key_number ?? NULL,
                'engine_number' => $request->engine_number ?? NULL,
                'chassis_number' => $request->chassis_number ?? NULL,
                'vin_number' => $request->vin_number ?? NULL,
                'ex_showroom_price' => $request->ex_showroom_price ?? NULL,
                'interior_color' => $request->interior_color ?? NULL,
                'exterior_color' => $request->exterior_color ?? NULL,
                'updated_at' => date('Y-m-d H:i:s'),
                'updated_by' => auth('sanctum')->user()->id
            ];

            $update = Inventory::where(['id' => $request->id])->update($data);

            if ($update)
                return redirect()->route('inventory')->with('success', 'Record updated successfully');
            else
                return redirect()->back()->with('error', 'Failed to update record')->withInput();
        }
    /** update */

    /** change-status */
        public function change_status(Request $request){
            if (!$request->ajax()) { exit('No direct script access allowed'); }

            $id = base64_decode($request->id);

            $data = Inventory::where(['id' => $id])->first();

            if(!empty($data)){
                $update = Inventory::where(['id' => $id])->update(['status' => $request->status, 'updated_at' => date('Y-m-d H:i:s'), 'updated_by' => auth()->user()->id]);

                if($update)
                    return response()->json(['code' => 200]);
                else
                    return response()->json(['code' => 201]);
            }else{
                return response()->json(['code' => 201]);
            }       
        }
    /** change-status */
}
