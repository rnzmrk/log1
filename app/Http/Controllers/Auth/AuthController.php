<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use App\Models\User;
use App\Mail\OTPVerificationMail;

class AuthController extends Controller
{
    /**
     * Show the combined authentication form (login and register).
     */
    public function showAuthForm()
    {
        return view('auth.auth');
    }

    /**
     * Show the registration form.
     */
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    /**
     * Handle registration request.
     */
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:admin,manager,user',
            'agree_terms' => 'accepted',
        ]);

        try {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => bcrypt($request->password),
            ]);

            // Log the user in immediately after registration
            Auth::login($user);

            return redirect('/admin/dashboard')->with('success', 'Account created successfully! Welcome to the dashboard.');
            
        } catch (\Exception $e) {
            return back()->with('error', 'Registration failed. Please try again.')->withInput();
        }
    }

    /**
     * Show the login form.
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * Handle login request without OTP verification.
     */
    public function login(Request $request)
    {
        // Validate credentials
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
            'remember' => 'sometimes'
        ]);

        // Check if user exists and credentials are valid
        if (Auth::attempt($credentials)) {
            // Regenerate session to prevent fixation
            session()->regenerate();
            
            // Redirect to admin dashboard
            return redirect()->intended('/admin/dashboard');
        } else {
            // Invalid credentials
            return back()
                ->with('error', 'Invalid email or password')
                ->withInput($request->except('password'));
        }
    }

    /**
     * Generate a 6-digit OTP.
     */
    private function generateOTP()
    {
        return str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
    }

    /**
     * Resend OTP.
     */
    public function resendOTP(Request $request)
    {
        $userId = Session::get('login_user_id');
        
        if (!$userId) {
            return response()->json([
                'success' => false,
                'message' => 'Session expired. Please login again.'
            ], 400);
        }

        $user = User::find($userId);
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User not found.'
            ], 400);
        }

        // Generate new OTP
        $otp = $this->generateOTP();
        Session::put('login_otp', $otp);
        Session::put('otp_expires_at', now()->addMinutes(10));

        try {
            Mail::to($user->email)->send(new OTPVerificationMail($otp));
            
            return response()->json([
                'success' => true,
                'message' => 'New OTP sent to your email'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to send OTP. Please try again.'
            ], 500);
        }
    }

    /**
     * Logout the user.
     */
    public function logout(Request $request)
    {
        Auth::logout();
        Session::invalidate();
        Session::regenerateToken();

        return redirect('/login');
    }
}
