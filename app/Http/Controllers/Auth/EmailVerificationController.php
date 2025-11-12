<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EmailVerificationController extends Controller
{
    /**
     * Show the email verification notice
     */
    public function notice()
    {
        return view('auth.verify-email');
    }

    /**
     * Verify the user's email address
     */
    public function verify(Request $request)
    {
        if (!$request->hasValidSignature()) {
            return redirect()->route('verification.notice')
                ->with('error', 'Invalid verification link.');
        }

        $user = Auth::guard('web')->user();

        if (!$user->hasVerifiedEmail()) {
            $user->markEmailAsVerified();
        }

        return redirect()->route('user.dashboard')->with('success', 'Email verified successfully!');
    }

    /**
     * Resend verification email
     */
    public function resend(Request $request)
    {
        $user = Auth::guard('web')->user();

        if ($user->hasVerifiedEmail()) {
            return redirect()->route('user.dashboard');
        }

        $user->sendEmailVerificationNotification();

        return back()->with('success', 'Verification link sent!');
    }
}