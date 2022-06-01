<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\Branch;
use App\Models\Transfer;
use App\Models\Product;
use App\Models\Inventory;
use App\Http\Requests\TransferRequest;
use App\Http\Requests\TransferAcceptRequest;
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
                $collection = DB::table('transfer as t')
                                ->select('t.id', 'tb.name as to_branch', 'fb.name as from_branch', 'p.name as product', 't.status')
                                ->leftjoin('branches as tb', 'tb.id', 't.to_branch')
                                ->leftjoin('branches as fb', 'fb.id', 't.from_branch')
                                ->leftjoin('products as p', 'p.id', 't.product_id');
                    
                if(auth()->user()->roles->pluck('name')[0] != 'admin')
                    $collection->where(['t.to_branch' => auth()->user()->branch]);
                
                $data = $collection->get();
                
                return Datatables::of($data)
                        ->addIndexColumn()
                        ->addColumn('action', function($data){
                            $return = '<div class="btn-group">';

                            if (auth()->user()->can('transfer-delete')) {
                                $return .= '<a href="javascript:;" class="btn btn-default btn-xs dropdown-toggle" data-toggle="dropdown">
                                                <i class="fa fa-bars"></i>
                                            </a> &nbsp;
                                            <ul class="dropdown-menu">
                                                <li><a class="dropdown-item" href="'.route('transfer.accept.check', ['id' => base64_encode($data->id)]).'">Accept</a></li>
                                                <li><button type="button" class="dropdown-item" data-toggle="modal" data-target="#model_reject_'.$data->id.'">Reject</button></li>
                                            </ul>';
                            }

                            $return .= '</div>';

                            $return .= '<div class="modal fade" id="model_reject_'.$data->id.'" tabindex="-1" role="dialog" aria-labelledby="model_reject_'.$data->id.'" aria-hidden="true">
                                            <div class="modal-dialog" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="model_reject_'.$data->id.'">Reject</h5>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <form class="transfer_reject" method="post" action="'.route('transfer.reject').'">
                                                        <div class="modal-body">
                                                            <input type="hidden" name="id" value="'.$data->id.'" />

                                                            <div class="row">
                                                                <div class="form-group col-sm-6">
                                                                    <label for="from_branch">From Branch</label>
                                                                    <input type="text" class="form-control" placeholder="Plese enter from branch" value="'.$data->from_branch.'" readonly>
                                                                </div>
                                                                <div class="form-group col-sm-6">
                                                                    <label for="to_branch">To Branch</label>
                                                                    <input type="text" class="form-control" placeholder="Plese enter from branch" value="'.$data->to_branch.'" readonly>
                                                                </div>
                                                                <div class="form-group col-sm-12">
                                                                    <label for="reason'.$data->id.'">To Branch</label>
                                                                    <textarea id="reason_'.$data->id.'" name="reason" class="form-control" rows="3" cols="30" required></textarea>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="submit" class="btn btn-primary">Save</button>
                                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>';

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
            $products = Product::select('id', 'name', 'veriant')->get();

            $collection = Branch::where(['status' => 'active']);
            
            if(auth()->user()->roles->pluck('name')[0] != 'admin')
                $collection->where('id', '!=', auth()->user()->branch);

            $branches = $collection->get();

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

    /** reject */
        public function reject(Request $request){
            if (!$request->ajax()) { exit('No direct script access allowed'); }

            if (!empty($request->all())) {
                $data = Transfer::where(['id' => $request->id])->first();

                if(!empty($data)){
                    $process = Transfer::where(['id' => $request->id])->update(['status' => 'rejected', 'reason' => $request->reason, 'updated_by' => auth()->user()->id]);

                    if($process)
                        return response()->json(['code' => 200]);
                    else
                        return response()->json(['code' => 201]);
                } else {
                    return response()->json(['code' => 201]);
                }
            } else {
                return response()->json(['code' => 201]);
            }
        }
    /** reject */

    /** accept-check */
        public function accept_check(Request $request, $id = ''){
            if (isset($id) && $id != '' && $id != null)
                $id = base64_decode($id);
            else
                return redirect()->route('user')->with('error', 'Something went wrong');
            
            $transfer = Transfer::select('from_branch', 'to_branch', 'product_id')->where(['id' => $id])->first();
            if(!$transfer){
                return redirect()->back()->with(['error' => 'Record not found']);
            }

            $product = Product::select('id', 'name', 'veriant')->where(['id' => $transfer->product_id])->first();
            if(!$product){
                return redirect()->back()->with(['error' => 'Product not found']);
            }

            $inventories = Inventory::select('id', 'name', 'veriant')
                                    ->where('name', 'like', '%'.$product->name.'%')
                                    ->where('veriant', 'like', '%'.$product->veriant.'%')
                                    ->where(['branch_id' => $transfer->to_branch])
                                    ->get();

            return view('transfer.accept')->with(['id' => $id, 'inventories' => $inventories]);
        }
    /** accept-check */

    /** inventory-details */
        public function inventory_details(Request $request, $id = ''){
            $data = Inventory::select('name', 'veriant', 'key_number', 'engine_number', 'chassis_number', 'vin_number', 'ex_showroom_price', 
                                        'interior_color', 'exterior_color', 'status')
                                        ->where(['id' => $request->id])
                                        ->first();

            if($data)
                return response()->json(['code' => 200, 'data' => $data]);
            else
                return response()->json(['code' => 201]);

        }
    /** inventory-details */

    /** accept */
        public function accept(TransferAcceptRequest $request){
            if($request->ajax()){ return true; }

            DB::beginTransaction();
            try {
                $transfer = Transfer::where(['id' => $request->id])->update(['inventory_id' => $request->product, 'transfer_fee' => $request->transfer_fee, 'status' => 'accepted', 'updated_at' => date('Y-m-d H:i:s'), 'updated_by' => auth()->user()->id]);

                if(!$transfer){
                    DB::rollback();
                    return redirect()->back()->with('error', 'Something went wrong, please try again later')->withInput();    
                }

                $inventory = Inventory::where(['id' => $request->product])->update(['status' => 'pdi_hold', 'updated_at' => date('Y-m-d H:i:s'), 'updated_by' => auth()->user()->id]);

                if(!$inventory){
                    DB::rollback();
                    return redirect()->back()->with('error', 'Something went wrong, please try again later')->withInput();    
                }

                DB::commit();
                return redirect()->route('transfer')->with(['success' => 'Transfer accepted']);
            }catch (\Throwable $th){
                DB::rollback();
                return redirect()->back()->with('error', 'Something went wrong, please try again later')->withInput();
            }
        }
    /** accept */
}
