<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\OBF;
use App\Models\ObfApproval;
use Illuminate\Http\Request;

class ObfArrovalController extends Controller
{
    /** construct */
        public function __construct(){
            $this->middleware('permission:obf_approval-create', ['only' => ['create']]);
            $this->middleware('permission:obf_approval-edit', ['only' => ['edit']]);
            $this->middleware('permission:obf_approval-view', ['only' => ['view']]);
            $this->middleware('permission:obf_approval-delete', ['only' => ['delete']]);
        }
    /** construct */
    /** index */
        public function index(Request $request){
            if(auth()->user()->can('obf_approval-view')){
                $data = OBF::where('status' ,'pending')->get();
                if(sizeof($data)){
                    return response()->json(['status' => 200 ,'message' => 'Data Found.' , 'Data' => $data]);
                }else{
                    return response()->json(['status' => 404 ,'message' => 'No Data Found.']);
                }
            }else{
                return response()->json(['status' => 401, 'message' => 'Not Authorized.']);
            }
        }
    /** index */

    /** view */
        public function view(Request $request){
            if(auth()->user()->can('obf_approval-view')){

                $data = OBF::where(['id' => $request->id])->first();
                
                if($data)
                    return response()->json(['status' => 200, 'message' => 'Data found', 'data' => $data]);
                else
                    return response()->json(['status' => 404, 'message' => 'No data found']);
            }else{
                return response()->json(['status' => 401, 'message' => 'Not Authorized.']);
            }
        }
    /** view */

    /** change-status */
        public function change_status(Request $request){
            if(auth()->user()->can('obf_approval-delete')){
                $rules = [
                    'id' => 'required',
                    'status' => 'required'
                ];

                $validator = Validator::make($request->all(), $rules);

                if($validator->fails())
                    return response()->json(['status' => 422, 'message' => $validator->errors()]);

                $data = OBF::where(['id' => $request->id])->first();

                if(!empty($data)){

                    if($data->status == 'obf_rejected'){
                        $update = OBF::where(['id' => $request->id])->update(['status' => $request->status, 'reason' => $request->reason,'updated_at' => date('Y-m-d H:i:s'), 'updated_by' => auth('sanctum')->user()->id]);
                    }else if($data->status == 'obf_accepted'){
                        $update = OBF::where(['id' => $request->id])->update(['status' => $request->status, 'updated_at' => date('Y-m-d H:i:s'), 'updated_by' => auth('sanctum')->user()->id]);
                    }else{
                        $update = OBF::where(['id' => $request->id])->update(['status' => $request->status, 'updated_at' => date('Y-m-d H:i:s'), 'updated_by' => auth('sanctum')->user()->id]);
                    }
                    
                    if($update){
                        return response()->json(['status' => 200 ,'message' => 'Record status change successfully']);
                    }else{
                        return response()->json(['status' => 201, 'message' => 'Faild to update status']);
                    }
                }else{
                    return response()->json(['status' => 201, 'message' => 'Somthing went wrong !']);
                }
            }else{return response()->json(['status' => 401, 'message' => 'Not Authorized.']);}
        }
    /** change-status */
}
