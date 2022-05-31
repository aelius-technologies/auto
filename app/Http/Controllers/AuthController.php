<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Mail\ForgotPassword;
use Illuminate\Support\Str;
use Auth, DB, Mail, Validator, File, DataTables;

class AuthController extends Controller{
    /** login */
        public function login(Request $request){
            return view('auth.login');
        }
    /** login */

    /** signin */
        public function signin(Request $request){
            $validator = Validator::make(
                                        ['email' => $request->email, 'password' => $request->password],
                                        ['email' => 'required', 'password' => 'required']
                                    );

            if($validator->fails()){
                return redirect()->route('login')->withErrors($validator)->withInput();
            }else{
                $auth = auth()->attempt(['email' => $request->email, 'password' => $request->password]);

                if($auth != false){
                    $user = auth()->user();

                    if($user->status == 'inactive'){
                        Auth::logout();
                        return redirect()->route('login')->with('error', 'Account belongs to this credentials is inactive, please contact administrator');
                    }elseif($user->status == 'deleted'){
                        Auth::logout();
                        return redirect()->route('login')->with('error', 'Account belongs to this credentials is deleted, please contact administrator');
                    }else{
                        return redirect()->route('dashboard')->with('success', 'Login successfully');
                    }
                }else{
                    return redirect()->route('login')->with('error', 'invalid credentials, please check credentials');
                }
            }
        }
    /** signin */

    /** logout */
        public function logout(Request $request){
            Auth::logout();
            return redirect()->route('login')->with('success','Logout successfully');
        }
    /** logout */
    
    /** forgot-password */
        public function forgot_password(Request $request){
            return view('auth.forgot-password');
        }
    /** forgot-password */

    /** password-forgot */
        public function password_forgot(Request $request){
            $validator = Validator::make(
                        ['email' => $request->email],
                        ['email' => 'required']
                    );

            if($validator->fails())
                return redirect()->back()->withErrors($validator)->withInput();
        
            $user = DB::table('users')->where(['email' => $request->email])->first();

            if(!isset($user) && $user == null)
                return redirect()->back()->withErrors(['email' => 'Entered email address does not exists in records, please check email address']);

            $token = Str::random(60);
            $link = url('/reset-password').'/'.$token.'?email='.urlencode($user->email);

            DB::table('password_resets')->insert([
                'email' => $request->email,
                'token' => $token,
                'created_at' => date('Y-m-d H:i:s')
            ]);

            $data = array('from_email' => _settings('MAIL_FROM_ADDRESS'), 'email' => $request->email, 'link' => $link);

            try{
                Mail::to($request->email)->send(new ForgotPassword($data));

                return redirect()->route('login')->with('success', 'please check your email and follow steps for reset password');
            }catch(\Exception $e){
                DB::table('password_resets')->where(['email' => $request->email])->delete();
                return redirect()->back()->with('error', 'something went wrong, please try again later');
            }
        }
    /** password-forgot */

    /** reset-password */
        public function reset_password(Request $request, $string){
            $email = $request->email;
            return view('auth.reset-password', compact('email', 'string'));
        }
    /** reset-password */

    /** recover-password */
        public function recover_password(Request $request){
            $validator = Validator::make($request->all(), [
                'email' => 'required|email|exists:users,email',
                'password' => 'required|string|min:6|max:12|confirmed',
                'token' => 'required'
            ]);

            if($validator->fails())
                return redirect()->back()->withErrors($validator)->withInput();

            $data = \DB::table('password_resets')->where('token', $request->token)->OrderBy('created_at', 'desc')->first();

            if(!isset($data) && $data == null)
                return redirect()->route('login')->with('error', 'Reset password token mismatch, Please regenerate link again')->withInput();

            $user = \DB::table('users')->where('email', $request->email)->first();

            if(!isset($user) && $user == null)
                return redirect()->back()->with('error', 'Email address does not exists, Please check email address')->withInput();

            $crud = array(
                'password' => bcrypt($request->password),
                'updated_at' => date('Y-m-d H:i:s'),
            );

            DB::table('users')->where('email', $request->email)->limit(1)->update($crud);

            DB::table('password_resets')->where('email', $user->email)->delete();

            return redirect()->route('login')->with('success', 'Password resetted successgully');
        }
    /** recover-password */
}
