<?php

namespace App\Http\Controllers;

use App\Helper\JWTToken;
use App\Mail\OTPMail;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Redis;
use Inertia\Inertia;

class UserController extends Controller
{

    public function LoginPage(Request $request)
    {
        return Inertia::render('LoginPage');
    }


    public function registrationPage(Request $request)
    {
        return Inertia::render('registrationPage');
    }


    public function SendOTPPage(Request $request)
    {
        return Inertia::render('SendOTPPage');
    }


    public function VerifyOTPPage(Request $request)
    {
        return Inertia::render('VerifyOTPPage');
    }


    public function ResetPasswordPage(Request $request)
    {
        return Inertia::render('ResetPassword');
    }




    public function userRegistration(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required',
                'email' => 'required|email|unique:users,email',
                'password' => 'required',
                'mobile' => 'required'
            ]);

            $user = User::create([
                "name" => $request->input('name'),
                "email" => $request->input('email'),
                "password" => $request->input('password'),
                "mobile" => $request->input('mobile'),
            ]);

            // return response()->json([
            //     'status' => 'success',
            //     'message' => 'user registration successfully !',
            //     'data' => $user,
            // ]);

            $data = ['message' => "user Registration Successfully ", 'status' => true, 'error' => ""];
            return redirect('/login')->with($data);
        } catch (Exception $e) {
            // return response()->json([
            //     'status' => 'Fail',
            //     'message' => $e->getMessage(),
            // ]);
            $data = ['message' => "user Registration Failed ", 'status' => false, 'error' => ""];
            return redirect('/user-registration')->with($data);
        }
    }

    public function userlogin(Request $request)
    {
        $count = User::where('email', $request->input('email'))
            ->where('password', $request->input('password'))
            ->select('id')->first();

        if ($count !== null) {
            $token = JWTToken::CreateToken($request->input('email'), $count->id);
          

            $data = ['message' => "user Login Successfully ", 'status' => true, 'error' => ''];
            return redirect('/DeshboardPage')->with($data,$token);
        } else {
        
            $data = ['message' => "user failed", 'status' => false, 'error' => ''];
            return redirect('/login')->with($data);
        }
    }




    public function logout(Request $request)
    {
       
        $request->session()->forget('email');
        $request->session()->forget('user_id');

        $data = ['message' => "user logout Successfully ", 'status' => true, 'error' => ''];
        return redirect('/login')->with($data);
    }


    public function SendOTPCode(Request $request)
    {
        $email = $request->input('email');
        $otp = rand(9999, 1000);

        $count = User::where('email', $email)->count();
        if ($count == 1) {
            Mail::to($email)->send(new OTPMail($otp));
            User::where('email', $email)->update(['otp' => $otp]);
            $request->session()->put('email', $email);
            
            $data = ['message' => "4 Digits {$otp} OTP Send Successfully ", 'status' => true, 'error' => ''];
            return redirect('/verify-OTPPage')->with($data);
        } else {
           
            $data = ['message' => "Unauthorized", 'status' => false, 'error' => ""];
            return redirect('/registration')->with($data);
        }
    }

    public function VerifyOTP(Request $request)
    {
      
        $email = $request->session()->get('email');
        $otp = $request->input('otp');
        $count = User::where('email', $email)->where('otp', $otp)->count();

        if ($count == 1) {
            User::where('email', $email)->update(['otp' => 0]);
         
            $request->session()->put('otp_verify', 'yes');
            $data = ['message' => " OTP Verify Successfully ", 'status' => true, 'error' => ''];
            return redirect('/reset-password')->with($data);
        } else {
           
            $data = ['message' => "unauthorized", 'status' => false, 'error' => ''];
            return redirect('/login')->with($data);
        }
    }

    public function resetPassword(Request $request)
    {
        try {
          
            $email = $request->session()->get('email', 'default');
            $password = $request->input('password');

            $otp_verify = $request->session()->get('otp_verify', 'default');
            if ($otp_verify === 'yes') {
                User::where('email', $email)->update([
                    'password' => $password,
                ]);

                $request->session()->flush();
                $data = ['message' => "Password Reset Successfully ", 'status' => true, 'error' => ''];
                return redirect('/login')->with($data);
            } else {
                $data = ['message' => "Password Reset Failed ", 'status' => false, 'error' => ''];
                return redirect('/reset-password')->with($data);
            }
           
        } catch (Exception $e) {
         
            $data = ['message' => $e->getMessage(), 'status' => false, 'error' => ''];
            return redirect('/reset-password')->with($data);
        }
    }

    public function ProfileInfo(Request $request)
    {
        $user_id = request()->header('id');
        $user = User::where('id', $user_id)->first();
        return Inertia::render('profilePage', ['user' => $user]);
    }

    public function UpdateProfile(Request $request)
    {
        $user_id = request()->header('id');
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email,' . $user_id,
            'mobile' => 'required'
        ]);

        User::where('id', $user_id)->update([
            "name" => $request->input('name'),
            "email" => $request->input('email'),
            "mobile" => $request->input('mobile'),
        ]);
        $data = ['message' => "Profile updated Successfully ", 'status' => true, 'error' => ""];
        return redirect('profilePage')->with($data);
    }
}
