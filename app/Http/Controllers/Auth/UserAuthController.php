<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserAuthController extends Controller
{
    /**
     * Handle user registration
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'username' => ['required', 'string', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'verification_code' => ['required', 'string', 'size:6'],
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        // Verify the email verification code
        $storedCode = session()->get('verification_code');
        $storedEmail = session()->get('user_email');

        if (!$storedCode || $storedEmail !== $request->email || $storedCode !== $request->verification_code) {
            return back()->withErrors(['verification_code' => 'Invalid verification code.'])->withInput();
        }

        // Clear the verification session
        session()->forget(['verification_code', 'user_email']);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'username' => $request->username,
            'password' => Hash::make($request->password),
            'role' => 'user',
            'is_active' => true,
            'email_verified_at' => now(), // Mark email as verified
        ]);

        Auth::login($user);

        return redirect()->route('user.dashboard')->with('success', 'Account created successfully! Welcome to Bitsa Club!');
    }

    /**
     * Handle user login
     */
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput()->with('error', 'Validation failed');
        }

        $user = User::where('email', $request->email)->where('is_active', true)->first();
        
        if (!$user) {
            return back()->withErrors(['email' => 'No active account found with this email.'])->withInput()->with('error', 'User not found');
        }

        if (Auth::attempt($request->only('email', 'password'), $request->filled('remember'))) {
            $request->session()->regenerate();
            return redirect()->intended(route('user.dashboard'))->with('success', 'Login successful!');
        }

        return back()->withErrors(['email' => 'Invalid credentials.'])->withInput()->with('error', 'Authentication failed');
    }

    /**
     * Handle user logout
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('welcome')->with('success', 'Logged out successfully!');
    }

    /**
     * Send verification email (API endpoint)
     */
    public function sendVerificationEmail(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'name' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => 'Invalid data'], 400);
        }

        try {
            $verificationCode = rand(100000, 999999);
            
            // Store in session for verification
            session([
                'verification_code' => $verificationCode,
                'user_email' => $request->email
            ]);

            // Here you would integrate with your email service
            // For now, we'll just return success
            return response()->json([
                'success' => true,
                'message' => 'Verification code sent successfully',
                'code' => $verificationCode // Remove this in production
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to send verification email'
            ], 500);
        }
    }
}