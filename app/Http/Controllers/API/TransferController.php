<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Transfer;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Validator , DB;

class TransferController extends Controller
{
    /** Index */
        public function index(Request $request){
            $data = Transfer::where(['to_branch' => $request->id])->orWhere(['from_branch' => $request->id])->get();
            if(sizeof($data)){
                if($data->to_branch == $request->id){
                    $data->request_type = 'Sent';
                }else{
                    $data->request_type = 'Recived';
                }
                return response()->json(['status' => 200 ,'message' => 'Data Found' , 'data' => $data]);
            }else{
                return response()->json(['status' => 404 ,'message' => 'No Data Found!']);
            }
        }
    /** Index */
    
    /** View */
        public function view(Request $request){
            $data = Transfer::select('products.*' ,'from_branch.name AS from_branch_name','to_branch.name AS to_branch_name' ,'transfer.*')
                    ->leftjoin('products', 'products.id' ,'transfer.product_id')
                    ->leftjoin('branches AS from_branch', 'branches.id' ,'transfer.from_branch')
                    ->leftjoin('branches AS to_branch', 'branches.id' ,'transfer.to_branch')
                    ->where(['id' => $request->id])
                    ->first();
            if($data){
                return response()->json(['status' => 200 ,'message' => 'Data Found' , 'data' => $data]);
            }else{
                return response()->json(['status' => 404 ,'message' => 'No Data Found!']);
            }
        }
    /** View */
   
    /** Change Status */
        public function change_status(Request $request)
        {
            if(auth()->user()->can('orders-delete')){
                $rules = [
                    'id' => 'required',
                    'status' => 'required'
                ];

                $validator = Validator::make($request->all(), $rules);

                if ($validator->fails())
                    return response()->json(['status' => 422, 'message' => $validator->errors()]);

                $data = Transfer::where(['id' => $request->id])->first();

                if (!empty($data)) {
                    $update = Transfer::where(['id' => $request->id])->update(['status' => $request->status, 'updated_at' => date('Y-m-d H:i:s'), 'updated_by' => auth('sanctum')->user()->id]);
                    if ($update) {
                        return response()->json(['status' => 200, 'message' => 'Record status change successfully']);
                    } else {
                        return response()->json(['status' => 201, 'message' => 'Faild to update status']);
                    }
                } else {
                    return response()->json(['status' => 201, 'message' => 'Somthing went wrong !']);
                }
            }else{
                return response()->json(['status' => 401, 'message' => 'Not Authorized.']);
            }
        }
    /** Change Status */
}
