<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Hash;
use Validator;
use App\Jobs\SendEmail;

class LoginController extends Controller
{
    function login(Request $req)
    {
        Artisan::call('storage:link');

        if (Session::get('user_name')) {
            return redirect('/dashboard');
        }
        
        if($req->isMethod('post')){
            $validate_admin = Admin::where('user_name', $req->user_name)->first();
        
        if ($validate_admin && $validate_admin->status != '1')
        {
            Session::flash('message', 'Admin is Inactive!');
            return back();
        }
        
        if ($validate_admin && Hash::check($req->user_password, $validate_admin->user_password)) {
            $req->session()->put('user_name', $validate_admin['user_name']);
            $req->session()->put('user_type', $validate_admin['user_type']);
            return  redirect('dashboard');
        } else {
            
            Session::flash('message', 'Wrong credentials !');
            return back();
        }
            
        }
        return view('auth.login');
    }

    function checklogin(Request $req)
    {  
        $validate_admin = Admin::where('user_name', $req->user_name)->first();
        
        if ($validate_admin && $validate_admin->status != '1')
        {
            Session::flash('message', 'Admin is Inactive!');
            return back();
        }
        
        if ($validate_admin && Hash::check($req->user_password, $validate_admin->user_password)) {
            $req->session()->put('user_name', $validate_admin['user_name']);
            $req->session()->put('user_type', $validate_admin['user_type']);
            return  redirect('dashboard');
        } else {
            
            Session::flash('message', 'Wrong credentials !');
            return back();
        }
    }
    
    public function verify_forgot_password(Request $request)
    {
        if($request->isMethod('post')){
            $rules = [
                'email' => 'required',
    
            ];
            $check_email=  Admin::where('email',$request->email)->first();
           
            if(empty($check_email)){
                Session::flash('message', 'Email does not exist !');
                return back();  
            }else{
                $opt=rand(111111,999999);
                Session::put('forgot_otp',$opt);
                Session::put('email',$request->email);
                $message="You verification code for forgot password is ".$opt;
                
                $details=[         
                    "subject"=>"OTP for forgot password" ,
                    "message"=>$message,
                    "to"=>$request->email,
                ];
                send_email($details);
                return  redirect('admin-forgot-password'); 
            }
        }
        return view('auth.verify-forgot-password');
    }
    
    public function admin_forgot_password(Request $request)
    {
        if($request->isMethod('post')){
            $rules = [
                'otp' => 'required',
                'password' => 'required',
                'password' => 'required|same:confirm_password'
    
            ];
    
            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                $messages = $validator->errors()->all();
                $msg = $messages[0];
                 return back()->withErrors($validator)->withInput();
            }
            
            $forgot_otp=Session::get('forgot_otp');
            $email=Session::get('email');
            if($forgot_otp==$request->otp){
                Admin::where('email',$email)->update(['user_password' => Hash::make($request->password)]);
                Session::flash('message', 'Password Updated Successfully!');
                return  redirect('admin'); 
            }else{
                Session::flash('message', 'Wrong OTP!');
                return back();
            }
        }
        
        return view('auth.admin_forgotpassword');
    }
    
    function logout()
    {
        session()->pull('user_name');
        session()->pull('user_type');
        return redirect()->route('admin');
    }
}
