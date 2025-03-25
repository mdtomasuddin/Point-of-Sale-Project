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

            return response()->json([
                'status' => 'success',
                'message' => 'user registration successfully !',
                'data' => $user,
            ]);
        } catch (Exception $e) {
            return response()->json([
                'status' => 'Fail',
                'message' => $e->getMessage(),
            ]);
        }
    }

    public function userlogin(Request $request)
    {
        $count = User::where('email', $request->input('email'))
            ->where('password', $request->input('password'))
            ->select('id')->first();

        if ($count !== null) {
            // $token = JWTToken::CreateToken($request->input('email'), $count->id);
            // return response()->json([
            //     'status' => 'success',
            //     'message' => 'user Login successfully !',
            //     'token' => $token,
            // ])->cookie('token', $token, 60 * 24 * 30);

            $email = $request->input('email');
            $user_id = $count->id;

            $request->session()->put('email', $email);
            $request->session()->put('user_id', $user_id);

            $data=['message'=>"user Login Successfully ", 'status'=>true, 'error'=>''];
            return redirect('/DeshboardPage')->with($data);

        } else {
            // return response()->json([
            //     'status' => 'Fail',
            //     'message' => 'unauthorized',
            // ]);
            $data=['message'=>"user failed", 'status'=>true, 'error'=>''];
            return redirect('/login')->with($data);
        }
    }




    public function logout(Request $request)
    {
        // return response()->json([
        //     'status' => 'success',
        //     'message' => 'user Logout successfully !',
        // ])->cookie('token', '', -1);
        $request->session()->forget('email');
        $request->session()->forget('user_id');

        $data=['message'=>"user logout Successfully ", 'status'=>true, 'error'=>''];
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
            return response()->json([
                'status' => 'success',
                'message' => "4 Digits {$otp} OTP Send Successfully ",
            ]);
        } else {
            return response()->json([
                'status' => 'Fail',
                'message' => 'unauthorized',
            ]);
        }
    }

    public function VerifyOTP(Request $request)
    {
        $email = $request->input('email');
        $otp = $request->input('otp');
        $count = User::where('email', $email)->where('otp', $otp)->count();

        if ($count == 1) {
            User::where('email', $email)->update(['otp' => 0]);
            $token = JWTToken::CreateTokenVerify($request->input('email'));

            return response()->json([
                'status' => 'success',
                'message' => 'OTP Verification successfully !',
                // 'token' => $token,
            ])->cookie('token', $token, 60 * 24 * 30);
        } else {
            return response()->json([
                'status' => 'Fail',
                'message' => 'unauthorized',
            ]);
        }
    }

    public function resetPassword(Request $request)
    {
        try {
            $email = $request->header('email');
            $password = $request->input('password');

            User::where('email', $email)->update([
                'password' => $password,
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Password Reset successfully!',
            ]);
        } catch (Exception $e) {
            return response()->json([
                'status' => 'Fail',
                'message' => $e->getMessage(),
            ]);
        }
    }
}
