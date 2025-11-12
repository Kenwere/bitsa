<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AdminAuthController extends Controller
{
    /**
     * Handle admin registration
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:admins'],
            'username' => ['required', 'string', 'max:255', 'unique:admins'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'terms' => ['required', 'accepted'],
            'verification_code' => ['required', 'string', 'size:6'],
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        // Verify the email verification code
        $storedCode = session()->get('admin_verification_code');
        $storedEmail = session()->get('admin_email');

        if (!$storedCode || $storedEmail !== $request->email || $storedCode !== $request->verification_code) {
            return back()->withErrors(['verification_code' => 'Invalid verification code.'])->withInput();
        }

        // Clear the verification session
        session()->forget(['admin_verification_code', 'admin_email']);

        $admin = Admin::create([
            'name' => $request->name,
            'email' => $request->email,
            'username' => $request->username,
            'password' => Hash::make($request->password),
            'role' => 'admin',
            'department' => 'General',
            'is_active' => true,
        ]);

        Auth::guard('admin')->login($admin);

        return redirect()->route('admin.dashboard')->with('success', 'Admin account created successfully!');
    }

    /**
     * Handle admin login
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

        $admin = Admin::where('email', $request->email)->where('is_active', true)->first();
        
        if (!$admin) {
            return back()->withErrors(['email' => 'No active admin account found with this email.'])->withInput()->with('error', 'Admin not found');
        }

        if (Auth::guard('admin')->attempt($request->only('email', 'password'), $request->filled('remember'))) {
            $request->session()->regenerate();
            return redirect()->intended(route('admin.dashboard'))->with('success', 'Admin login successful!');
        }

        return back()->withErrors(['email' => 'Invalid credentials.'])->withInput()->with('error', 'Authentication failed');
    }

    /**
     * Handle admin logout
     */
    public function logout(Request $request)
    {
        Auth::guard('admin')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('welcome')->with('success', 'Admin logged out successfully!');
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
                'admin_verification_code' => $verificationCode,
                'admin_email' => $request->email
            ]);

            // Here you would integrate with your email service
            // For now, we'll just return success
            return response()->json([
                'success' => true,
                'message' => 'Admin verification code sent successfully',
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