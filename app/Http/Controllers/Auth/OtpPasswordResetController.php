<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\PasswordResetOtp;
use App\Mail\OtpResetPasswordMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class OtpPasswordResetController extends Controller
{
    public function showRequestForm()
    {
        return view('auth.forgot-password-otp');
    }

    public function sendOtp(Request $request)
    {
        $request->validate(['email' => 'required|email|exists:users,email']);
        
        $otp = rand(100000, 999999);
        
        PasswordResetOtp::updateOrCreate(
            ['email' => $request->email],
            ['otp' => $otp, 'expires_at' => Carbon::now()->addMinutes(10)]
        );

        Mail::to($request->email)->send(new OtpResetPasswordMail($otp));

        return redirect()->route('password.verify.form', ['email' => $request->email])
            ->with('status', 'We have emailed your OTP code! Please check your inbox.');
    }

    public function showVerifyForm(Request $request)
    {
        return view('auth.verify-otp', ['email' => $request->email]);
    }

    public function verifyOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'otp' => 'required|numeric'
        ]);

        $record = PasswordResetOtp::where('email', $request->email)->where('otp', $request->otp)->first();

        if (!$record || Carbon::parse($record->expires_at)->isPast()) {
            return back()->withErrors(['otp' => 'The OTP is invalid or has expired.']);
        }

        return redirect()->route('password.reset.form', ['email' => $request->email, 'otp' => $request->otp]);
    }

    public function showResetForm(Request $request)
    {
        return view('auth.reset-password-otp', ['email' => $request->email, 'otp' => $request->otp]);
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'otp' => 'required',
            'password' => 'required|confirmed|min:8',
        ]);

        $record = PasswordResetOtp::where('email', $request->email)->where('otp', $request->otp)->first();

        if (!$record || Carbon::parse($record->expires_at)->isPast()) {
            return redirect()->route('login')->withErrors(['email' => 'Invalid or expired OTP. Please try again.']);
        }

        $user = User::where('email', $request->email)->first();
        $user->password = Hash::make($request->password);
        $user->save();

        $record->delete(); 

        return redirect()->route('login')->with('status', 'Your password has been successfully reset! Please log in.');
    }
}