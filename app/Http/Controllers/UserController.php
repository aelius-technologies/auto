<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Models\User;
use App\Http\Requests\UserRequest;
use Spatie\Permission\Models\Role;
use App\Mail\UserRegister;
use App\Models\Branch;
use Illuminate\Support\Str;
use Auth, DB, Mail, Validator, File, DataTables;

class UserController extends Controller{
    /** construct */
        public function __construct(){
            $this->middleware('permission:user-create', ['only' => ['create']]);
            $this->middleware('permission:user-edit', ['only' => ['edit']]);
            $this->middleware('permission:user-view', ['only' => ['view']]);
            $this->middleware('permission:user-delete', ['only' => ['delete']]);
        }
    /** construct */

    /** index */
        public function index(Request $request){
            if ($request->ajax()) {
                $data = User::orderBy('id', 'desc')->get();

                return Datatables::of($data)
                    ->addIndexColumn()
                    ->addColumn('action', function ($data) {
                        $return = '<div class="btn-group">';

                        if (auth()->user()->can('user-view')) {
                            $return .=  '<a href="'.route('user.view', ['id' => base64_encode($data->id)]).'" class="btn btn-default btn-xs">
                                                <i class="fa fa-eye"></i>
                                            </a> &nbsp;';
                        }

                        if (auth()->user()->can('user-edit')) {
                            $return .= '<a href="'.route('user.edit', ['id' => base64_encode($data->id)]).'" class="btn btn-default btn-xs">
                                                <i class="fa fa-edit"></i>
                                            </a> &nbsp;';
                        }

                        if (auth()->user()->can('user-delete')) {
                            $return .= '<a href="javascript:;" class="btn btn-default btn-xs dropdown-toggle" data-toggle="dropdown">
                                                <i class="fa fa-bars"></i>
                                            </a> &nbsp;
                                            <ul class="dropdown-menu">
                                                <li><a class="dropdown-item" href="javascript:;" onclick="change_status(this);" data-status="active" data-id="' . base64_encode($data->id) . '">Active</a></li>
                                                <li><a class="dropdown-item" href="javascript:;" onclick="change_status(this);" data-status="inactive" data-id="' . base64_encode($data->id) . '">Inactive</a></li>
                                                <li><a class="dropdown-item" href="javascript:;" onclick="change_status(this);" data-status="delete" data-id="' . base64_encode($data->id) . '">Delete</a></li>
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
                        } else {
                            return '-';
                        }
                    })

                    ->editColumn('name', function ($data) {
                        return $data->first_name.' '.$data->last_name;
                    })

                    ->editColumn('role', function ($data) {
                        return ucfirst(str_replace('_', ' ', $data->roles->first()->name));
                    })

                    ->rawColumns(['name', 'role', 'action', 'status'])
                    ->make(true);
            }

            return view('user.index');
        }
    /** index */

    /** create */
        public function create(Request $request){
            $roles = Role::all();
            $branch = Branch::where(['status' => 'active'])->get();
            return view('user.create')->with(['roles' => $roles ,'branch' => $branch]);
        }
    /** create */

    /** insert */
        public function insert(UserRequest $request){
            if($request->ajax()){ return true; }
            $password = 'Abcd@123?';

            if($request->password != '' && $request->password != NULL)
                $password = $request->password;

            $data = [
                'first_name' => ucfirst($request->first_name),
                'last_name' => ucfirst($request->last_name),
                'email' => $request->email,
                'contact_number' => $request->contact_number,
                'branch' => $request->branch,
                'status' => 'active',
                'password' => bcrypt($password),
                'created_at' => date('Y-m-d H:i:s'),
                'created_by' => auth()->user()->id,
                'updated_at' => date('Y-m-d H:i:s'),
                'updated_by' => auth()->user()->id
            ];

            DB::beginTransaction();
            try {
                $user = User::create($data);
                $user->assignRole($request->role);

                if($user){
                    DB::commit();
                    return redirect()->route('user')->with('success', 'Record inserted successfully');
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

    /** edit */
        public function edit(Request $request, $id = ''){
            if (isset($id) && $id != '' && $id != null)
                $id = base64_decode($id);
            else
                return redirect()->route('user')->with('error', 'Something went wrong');

            $roles = Role::all();
            $branch = Branch::where(['status' => 'active'])->get();
            $data = User::select('id','first_name', 'last_name','email', 'contact_number', 'password')->where(['id' => $id])->first();

            return view('user.edit')->with(['data' => $data, 'roles' => $roles ,'branch' => $branch]);
        }
    /** edit */

    /** update */
        public function update(UserRequest $request){
            if($request->ajax()) { return true; }

            $id = $request->id;
            $exst_rec = User::where(['id' => $id])->first();

            $data = [
                'first_name' => ucfirst($request->first_name),
                'last_name' => ucfirst($request->last_name),
                'contact_number' => $request->contact_number,
                'email' => $request->email,
                'branch' => $request->branch,
                'updated_at' => date('Y-m-d H:i:s'),
                'updated_by' => auth()->user()->id
            ];

            if($request->password != '' && $request->password != NULL)
                $data['password'] = $request->password;

            DB::beginTransaction();
            try {
                $update = User::where(['id' => $id])->update($data);

                if ($update) {
                    DB::table('model_has_roles')->where(['model_id' => $id])->delete();

                    $exst_rec->assignRole($request->role);

                    DB::commit();
                    return redirect()->route('user')->with('success', 'Record updated successfully');
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

    /** view */
        public function view(Request $request, $id = ''){
            if (isset($id) && $id != '' && $id != null)
                $id = base64_decode($id);
            else
                return redirect()->route('user')->with('error', 'Something went wrong');

            $roles = Role::all();
            
            $data = User::select( 'id', 'first_name' ,'last_name', 'email', 'contact_number', 'password')
                                ->where(['id' => $id])
                                ->first();

            return view('user.view')->with(['data' => $data, 'roles' => $roles]);
        }
    /** view */

    /** change-status */
        public function change_status(Request $request){
            if (!$request->ajax()) { exit('No direct script access allowed'); }

            if (!empty($request->all())) {
                $id = base64_decode($request->id);
                $status = $request->status;

                $data = User::where(['id' => $id])->first();

                if(!empty($data)){
                    if($status == 'delete'){
                        $process = User::where(['id' => $id])->delete();

                        $file_path = public_path().'/uploads/users/'.$data->photo;

                        if(File::exists($file_path) && $file_path != ''){
                            if($data->photo != 'user-icon.jpg'){
                                @unlink($file_path);
                            }
                        }
                    }else{
                        $process = User::where(['id' => $id])->update(['status' => $status, 'updated_by' => auth()->user()->id]);
                    }

                    if($process){
                        return response()->json(['code' => 200]);
                    }else{
                        return response()->json(['code' => 201]);
                    }
                } else {
                    return response()->json(['code' => 201]);
                }
            } else {
                return response()->json(['code' => 201]);
            }
        }
    /** change-status */

    /** remove-profile */
        public function profile_remove(Request $request){
            if(!$request->ajax()) { exit('No direct script access allowed'); }

            if(!empty($request->all())){
                $id = base64_decode($request->id);
                $data = User::find($id);

                if($data){
                    if($data->photo != ''){
                        $file_path = public_path().'/uploads/users/'.$data->photo;

                        if(File::exists($file_path) && $file_path != ''){
                            if($data->photo != 'user-icon.jpg') {
                                @unlink($file_path);
                            }
                        }

                        $update = User::where(['id' => $id])->update(['photo' => '']);

                        if($update)
                            return response()->json(['code' => 200]);
                        else
                            return response()->json(['code' => 201]);
                    }else{
                        return response()->json(['code' => 200]);
                    }
                }else{
                    return response()->json(['code' => 201]);
                }
            }else{
                return response()->json(['code' => 201]);
            }
        }
    /** remove-profile */
}
