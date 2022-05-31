<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\Branch;
use App\Models\Transfer;
use App\Models\Product;
use App\Http\Requests\TransferRequest;
use Auth, DB, Mail, Validator, File, DataTables;

class TransferController extends Controller{
    /** construct */
        public function __construct(){
            $this->middleware('permission:transfer-create', ['only' => ['create']]);
            $this->middleware('permission:transfer-edit', ['only' => ['edit']]);
            $this->middleware('permission:transfer-view', ['only' => ['view']]);
            $this->middleware('permission:transfer-delete', ['only' => ['delete']]);
        }
    /** construct */

    /** index */
        public function index(Request $request){
            if($request->ajax()){
                $data = DB::table('transfer as t')
                            ->select('t.id', 'tb.name as to_branch', 'fb.name as from_branch', 'p.name as product', 't.status')
                            ->leftjoin('branches as tb', 'tb.id', 't.to_branch')
                            ->leftjoin('branches as fb', 'fb.id', 't.from_branch')
                            ->leftjoin('products as p', 'p.id', 't.product_id')
                            ->get();
                
                return Datatables::of($data)
                        ->addIndexColumn()
                        ->addColumn('action', function($data){
                            $return = '<div class="btn-group">';

                            if(auth()->user()->can('transfer-view')){
                                $return .= '<a href="'.route('transfer.view', ['id' => base64_encode($data->id)]).'" class="btn btn-default btn-xs">
                                                <i class="fa fa-eye"></i>
                                            </a> &nbsp;';
                            }   

                            if (auth()->user()->can('transfer-delete')) {
                                $return .= '<a href="javascript:;" class="btn btn-default btn-xs dropdown-toggle" data-toggle="dropdown">
                                                    <i class="fa fa-bars"></i>
                                                </a> &nbsp;
                                                <ul class="dropdown-menu">
                                                    <li><a class="dropdown-item" href="javascript:;" onclick="change_status(this);" data-status="pending" data-id="' . base64_encode($data->id) . '">Pending</a></li>
                                                    <li><a class="dropdown-item" href="javascript:;" onclick="change_status(this);" data-status="accepted" data-id="' . base64_encode($data->id) . '">Accept</a></li>
                                                    <li><a class="dropdown-item" href="javascript:;" onclick="change_status(this);" data-status="rejected" data-id="' . base64_encode($data->id) . '">Reject</a></li>
                                                </ul>';
                            }

                            $return .= '</div>';

                            return $return;
                        })

                        ->editColumn('status', function ($data) {
                            if ($data->status == 'rejected') {
                                return '<span class="badge badge-pill badge-danger">Rejected</span>';
                            } else if ($data->status == 'accepted') {
                                return '<span class="badge badge-pill badge-success">Accepter</span>';
                            } else {
                                return '<span class="badge badge-pill badge-info">Pending</span>';
                            }
                        })

                        ->rawColumns(['action', 'status'])
                        ->make(true);
            }
            
            return view('transfer.index');
        }
    /** index */

    /** create */
        public function create(Request $request){
            $branches = Branch::where(['status' => 'active'])->get();
            $products = Product::select('id', 'name', 'veriant')->get();

            return view('transfer.create')->with(['branches' => $branches, 'products' => $products]);
        }
    /** create */

    /** insert */
        public function insert(TransferRequest $request){
            if($request->ajax()){ return true; }

            $from_branch = auth()->user()->branch;
            if(auth()->user()->roles->pluck('name')[0] == 'admin')
                $from_branch = $request->from_branch;

            $data = [
                'to_branch' => $request->to_branch,
                'product_id' => $request->product_id,
                'from_branch' => $from_branch,
                'status' => 'pending',
                'created_at' => date('Y-m-d H:i:s'),
                'created_by' => auth()->user()->id,
                'updated_at' => date('Y-m-d H:i:s'),
                'updated_by' => auth()->user()->id
            ];


            $transfer = Transfer::create($data);

            if($transfer){
                return redirect()->route('transfer')->with('success', 'Record inserted successfully');
            }else{
                return redirect()->back()->with('error', 'Failed to insert record')->withInput();
            }
        }
    /** insert */
}
