<?php

namespace App\Http\Controllers;

use App\Helper\JWTToken;
use App\Mail\OTPMail;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Redis;

class UserController extends Controller
{
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
            $token = JWTToken::CreateToken($request->input('email'), $count->id);
            return response()->json([
                'status' => 'success',
                'message' => 'user Login successfully !',
                'token' => $token,
            ])->cookie('token', $token, 60 * 24 * 30);
        } else {
            return response()->json([
                'status' => 'Fail',
                'message' => 'unauthorized',
            ]);
        }
    }


    public function DeshboardPage(Request $request)
    {
        $user = $request->header('email');
        return response()->json([
            'status' => 'success',
            'message' => 'user Login successfully !',
            'user' => $user,
        ]);
    }


    public function logout(Request $request)
    {
        return response()->json([
            'status' => 'success',
            'message' => 'user Logout successfully !',
        ])->cookie('token', '', -1);
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
