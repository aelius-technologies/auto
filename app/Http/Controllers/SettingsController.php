<?php    

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Setting;
use Auth, DB, Mail, Validator, File, DataTables;

class SettingsController extends Controller{
    /** construct */
        public function __construct(){
            $this->middleware('permission:setting-edit', ['only' => ['edit']]);
        }
    /** construct */

    /** index */
        public function index(){
            $general = Setting::select('id', 'key', 'value')->where(['type' => 'general'])->get();
            $smtp = Setting::select('id', 'key', 'value')->where(['type' => 'smtp'])->get();
            $sms = Setting::select('id', 'key', 'value')->where(['type' => 'sms'])->get();
            $social = Setting::select('id', 'key', 'value')->where(['type' => 'social'])->get();
            $logo = Setting::select('id', 'key', 'value')->where(['type' => 'logo'])->get();

            return view('settings.index', ['general' => $general, 'smtp' => $smtp, 'sms' => $sms, 'social' => $social,'logo' => $logo]);
        }
    /** index */

    /** update */
        public function update(Request $request){
            $data = $request->all();
            unset($data['_method']);
            unset($data['_token']);
            $tab = $data['tab'];
            unset($data['tab']);
            session(['tab' => $tab]);

            if(!empty($data)){
                foreach($data as $key => $value){
                    $collection = Setting::where(['id' => $key])->first();
                    if(!empty($collection)){
                        $collection->value = $value;
                        $collection->save();
                    }
                }
            }
            
            return redirect()->back()->with(['success' => 'Settings updated successfully', 'tab' => $tab]);
        }
    /** update */

    /** logo-update */
        public function logo_update(Request $request){
            $data = $request->all();
            unset($data['_method']);
            unset($data['_token']);
            $tab = $data['tab'];
            unset($data['tab']);

            if(!empty($data)){
                foreach($data as $key => $value){
                    $collection = Setting::where(['key' => $key])->first();
                    if(!empty($collection)){
                        if(!empty($request->file($key))){
                            $image_name = $collection->value;

                            $file = $request->file($key);
                            $filenameWithExtension = $request->file($key)->getClientOriginalName();
                            $filename = pathinfo($filenameWithExtension, PATHINFO_FILENAME);
                            $extension = $request->file($key)->getClientOriginalExtension();
                            $filenameToStore = strtolower($key).'.'.$extension;

                            $folder_to_upload = public_path().'/uploads/logo/';

                            if (!\File::exists($folder_to_upload)) {
                                \File::makeDirectory($folder_to_upload, 0777, true, true);
                            }

                            $collection->value = $filenameToStore;
                            $collection->save();

                            $file_path = public_path().'/uploads/logo/'.$image_name;

                            if(File::exists($file_path) && $file_path != ''){
                                @unlink($file_path);
                            }

                            if(!empty($request->file($key)))
                                $file->move($folder_to_upload, $filenameToStore);
                        }
                    }
                }
            }

            return redirect()->back()->with(['success' => 'Settings updated successfully', 'tab' => $tab]);
        }
    /** logo-update */
}

