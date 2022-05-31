<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use DB, Validator;

class ProductController extends Controller
{
    /** construct */
        public function __construct(){
            $this->middleware('permission:products-create', ['only' => ['create']]);
            $this->middleware('permission:products-edit', ['only' => ['edit']]);
            $this->middleware('permission:products-view', ['only' => ['view']]);
            $this->middleware('permission:products-delete', ['only' => ['delete']]);
        }
    /** construct */
    /** index */
        public function index(Request $request)
        {
            if(auth()->user()->can('products-view')){
                $data = DB::table('products')
                            ->select('categories.name AS category_name','products.*')
                            ->leftjoin('categories' ,'products.category_id' ,'categories.id')
                            ->where('status' ,'!=' ,'sold')
                            ->get();
                if (sizeof($data)) {
                    return response()->json(['status' => 200, 'message' => 'Data Found.', 'Data' => $data]);
                } else {
                    return response()->json(['status' => 404, 'message' => 'No Data Found.']);
                }
            }else{
                return response()->json(['status' => 401, 'message' => 'Not Authorized.']);
            }
        }
    /** index */

    /** insert */
        public function insert(Request $request)
        {
            if(auth()->user()->can('products-create')){
                $rules = [
                    'category_id' => 'required',
                    'name' => 'required',
                    'ex_showroom_price' => 'required',
                    'veriant' => 'required',
                    'interior_color' => 'required',
                    'exterior_color' => 'required',
                    'is_applicable_for_mcp' => 'required'
                ];

                $validator = Validator::make($request->all(), $rules);

                if ($validator->fails())
                    return response()->json(['status' => 422, 'message' => $validator->errors()]);

                $crud = [
                    'category_id' => $request->category_id,
                    'name' => ucfirst($request->name),
                    'ex_showroom_price' => $request->ex_showroom_price,
                    'veriant' => $request->veriant,
                    'interior_color' => $request->interior_color,
                    'exterior_color' => $request->exterior_color,
                    'is_applicable_for_mcp' => $request->is_applicable_for_mcp,
                    'status' => 'active',
                    'created_at' => date('Y-m-d H:i:s'),
                    'created_by' => auth()->user()->id,
                    'updated_at' => date('Y-m-d H:i:s'),
                    'updated_by' => auth()->user()->id
                ];

                $last_id = Product::insertGetId($crud);

                if ($last_id) {
                    return response()->json(['status' => 200, 'message' => 'Record inserted successfully']);
                } else {
                    return response()->json(['status' => 201, 'message' => 'Faild to insert Record!']);
                }
            }else{
                return response()->json(['status' => 401, 'message' => 'Not Authorized.']);
            }
        }
    /** insert */

    /** view */
        public function view(Request $request)
        {
            if(auth()->user()->can('products-view')){
                $data = Product::where(['id' => $request->id])->first();

                if ($data)
                    return response()->json(['status' => 200, 'message' => 'Data found', 'data' => $data]);
                else
                    return response()->json(['status' => 404, 'message' => 'No data found']);
            }else{
                return response()->json(['status' => 401, 'message' => 'Not Authorized.']);
            }
        }
    /** view */

    /** update */
        public function update(Request $request)
        {
            if(auth()->user()->can('products-edit')){
                $rules = [
                    'category_id' => 'required',
                    'name' => 'required',
                    'ex_showroom_price' => 'required',
                    'veriant' => 'required',
                    'interior_color' => 'required',
                    'exterior_color' => 'required',
                    'is_applicable_for_mcp' => 'required'
                ];

                $validator = Validator::make($request->all(), $rules);

                if ($validator->fails())
                    return response()->json(['status' => 422, 'message' => $validator->errors()]);

                $crud = [
                    'category_id' => $request->category_id,
                    'name' => ucfirst($request->name),
                    'ex_showroom_price' => $request->ex_showroom_price,
                    'veriant' => $request->veriant,
                    'interior_color' => $request->interior_color,
                    'exterior_color' => $request->exterior_color,
                    'is_applicable_for_mcp' => $request->is_applicable_for_mcp,
                    'updated_at' => date('Y-m-d H:i:s'),
                    'updated_by' => auth('sanctum')->user()->id
                ];


                $update = Product::where(['id' => $request->id])->update($crud);
                if ($update)
                    return response()->json(['status' => 200, 'message' => 'Record updated successfully']);
                else
                    return response()->json(['status' => 404, 'message' => 'Faild to update record']);
            }else{
                return response()->json(['status' => 401, 'message' => 'Not Authorized.']);
            }
        }
    /** update */

    /** change-status */
        public function change_status(Request $request)
        {
            if(auth()->user()->can('products-delete')){
                $rules = [
                    'id' => 'required',
                    'status' => 'required'
                ];

                $validator = Validator::make($request->all(), $rules);

                if ($validator->fails())
                    return response()->json(['status' => 422, 'message' => $validator->errors()]);

                $data = Product::where(['id' => $request->id])->first();

                if (!empty($data)) {
                    $update = Product::where(['id' => $request->id])->update(['status' => $request->status, 'updated_at' => date('Y-m-d H:i:s'), 'updated_by' => auth('sanctum')->user()->id]);
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
    /** change-status */
}
