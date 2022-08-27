<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Mail\SendOtp;
use App\Models\EmailOtp;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;
class resetPasswordController extends Controller
{
    
    public function resetPassword(Request $request)
    {

        $this->validate($request, [
            "password" => "required|confirmed|min:8",
            "otp" => "required",
        ]);

        $otp = EmailOtp::where(['otp' => $request->otp])->first();

        if ($otp != null) {
            $user = User::where("email", $otp->email)->first();
            $user->password = Hash::make($request->password);
            $user->save();

            # delete otp
            $otp->delete();

            return response()->json([
                'success'=>true,
                'message'=>'password reset successful'
            ], 200);       
         }

         return response()->json([
            'success'=>false,
            'message'=>'wrong OTP'
        ], 401);       
    }


    public function forgotPassword(Request $request)
    {
        $this->validate($request, [
            'email' => 'required'
        ]);

        # check if email exists
        $user = User::where("email", $request->email)->first();
        if ($user) {
            # if it returns true,  create otp and send email OTP to the user
                $otp = rand(10, 9999);

                $details=["otp"=>$otp];

                # send otp
                Mail::to($user->email)->send(new SendOtp($details));

                     # save otp
                EmailOtp::create(['email' => $request->email, 'otp' => $otp]);

                    return response()->json([
                        "success"=>true,
                        "message"=>"OTP sent to mail"
                    ], 200);

            }

            return response()->json([
                "success"=>false,
                "message"=>"Email not found"
            ], 401);
    }
}
