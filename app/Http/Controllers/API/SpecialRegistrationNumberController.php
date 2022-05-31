<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SpecialRegistrationNumber;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Validator , DB;

class SpecialRegistrationNumberController extends Controller
{
    /** index */
        public function index(Request $request){
            $data = SpecialRegistrationNumber::all();
            if(sizeof($data)){
                return response()->json(['status' => 200 ,'message' => 'Data Found.' , 'Data' => $data]);
            }else{
                return response()->json(['status' => 404 ,'message' => 'No Data Found.']);
            }
        }
    /** index */

    /** insert */
        public function insert(Request $request){
            $rules = [
                'number' => 'required',
                'amount' => 'required'
            ];

            $validator = Validator::make($request->all(), $rules);

            if($validator->fails())
                return response()->json(['status' => 422, 'message' => $validator->errors()]);

            $password = $request->password ?? 'Abcd@1234';
            $crud = [
                'number' => $request->number,
                'amount' => $request->amount,
                'status' => 'active',
                'created_at' => date('Y-m-d H:i:s'),
                'created_by' => auth('sanctum')->user()->id,
                'updated_at' => date('Y-m-d H:i:s'),
                'updated_by' => auth('sanctum')->user()->id
            ];
            
            $last_id = SpecialRegistrationNumber::insertGetId($crud);
            
            if($last_id){
                return response()->json(['status' => 200 ,'message' => 'Record inserted successfully']);
            } else {
                return response()->json(['status' => 201 ,'message' => 'Faild to insert Record!']);
            }
        
        }
    /** insert */

    /** view */
        public function view(Request $request){
            $data = SpecialRegistrationNumber::where(['id' => $request->id])->first();
            
            if($data)
                return response()->json(['status' => 200, 'message' => 'Data found', 'data' => $data]);
            else
                return response()->json(['status' => 404, 'message' => 'No data found']);
            }
    /** view */

    /** update */
        public function update(Request $request){
            $rules = [
                'id' => 'required',
                'number' => 'required',
                'amount' => 'required'
            ];

            $validator = Validator::make($request->all(), $rules);

            if($validator->fails())
                return response()->json(['status' => 422, 'message' => $validator->errors()]);

            $crud = [
                'number' => $request->number ?? '',
                'amount' => $request->amont ?? '',
                'updated_at' => date('Y-m-d H:i:s'),
                'updated_by' => auth('sanctum')->user()->id
            ];

            $update = SpecialRegistrationNumber::where(['id' => $request->id])->update($crud);
            if($update)
                return response()->json(['status' => 200, 'message' => 'Record updated successfully']);
            else
                return response()->json(['status' => 201, 'message' => 'Faild to update record']);
            
        }
    /** update */

    /** change-status */
        public function change_status(Request $request){
            $rules = [
                'id' => 'required',
                'status' => 'required'
            ];

            $validator = Validator::make($request->all(), $rules);

            if($validator->fails())
                return response()->json(['status' => 422, 'message' => $validator->errors()]);

            $data = SpecialRegistrationNumber::where(['id' => $request->id])->first();

            if(!empty($data)){
                $update = SpecialRegistrationNumber::where(['id' => $request->id])->update(['status' => $request->status, 'updated_at' => date('Y-m-d H:i:s'), 'updated_by' => auth('sanctum')->user()->id]);
                if($update){
                    return response()->json(['status' => 200 ,'message' => 'Record status change successfully']);
                }else{
                    return response()->json(['status' => 201, 'message' => 'Faild to update status']);
                }
            }else{
                return response()->json(['status' => 201, 'message' => 'Somthing went wrong !']);
            }
        }
    /** change-status */
}
