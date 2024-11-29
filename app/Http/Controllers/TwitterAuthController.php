<?php

namespace App\Http\Controllers;

use App\Mail\OtpEmail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Abraham\TwitterOAuth\TwitterOAuth;
use Illuminate\Support\Facades\Session;

class TwitterAuthController extends Controller
{
    public function sendOtp(Request $request)
    {
        $request->validate([
            'twitterEmail' => 'required|string',
        ]);
        
        try {
            $otp = rand(100000, 999999);

            session(['otp' => $otp]);
            session(['email' => $request->input('twitterEmail')]);
          
            Mail::to($request->input('twitterEmail'))->send(new OtpEmail($otp));
            return response()->json(['message' => 'OTP sent to email successfully!']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to send OTP', 'details' => $e->getMessage()], 500);
        }
    }

    public function verifyOtp(Request $request)
    {
        $request->validate([
            'otp' => 'required|numeric',
            'email' => 'required|email',
        ]);
        Log::info('All Session Data:', session()->all());

        Log::info('Session Data at OTP Verification:', context: [
            'otp_from_session' => session('otp'),
            'email_from_session' => session('email'),
            'input_email' => $request->input('email'),
            'input_otp' => $request->input('otp')
        ]);

      
        $sessionOtp = session('otp');
        $sessionEmail = session('email');

        if ($sessionOtp == $request->input('otp') && $sessionEmail == $request->input('email')) {
           
            return response()->json(['success' => true]);
        } else {
            
            return response()->json(['success' => false, 'message' => 'Invalid OTP or email'], 400);
        }
    }
}
