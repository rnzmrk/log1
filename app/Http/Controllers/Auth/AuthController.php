<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\OTPVerificationMail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

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
     * Handle login request with OTP verification.
     */
    public function login(Request $request)
    {
        $isAjax = $request->ajax() || $request->wantsJson();

        // Step 2: verify OTP if present
        if ($request->filled('otp')) {
            $request->validate([
                'otp' => ['required', 'digits:6'],
            ]);

            $session = $request->session();
            $expectedCode = $session->get('otp_code');
            $expiresAt = $session->get('otp_expires_at');
            $userId = $session->get('otp_user_id');

            if (!$expectedCode || !$expiresAt || !$userId) {
                throw ValidationException::withMessages([
                    'otp' => ['Verification code has expired. Please sign in again.'],
                ]);
            }

            if (now()->greaterThan($expiresAt)) {
                $session->forget(['otp_code', 'otp_expires_at', 'otp_user_id', 'otp_email']);

                throw ValidationException::withMessages([
                    'otp' => ['Verification code has expired. Please sign in again.'],
                ]);
            }

            if ($request->input('otp') !== $expectedCode) {
                throw ValidationException::withMessages([
                    'otp' => ['The verification code is incorrect.'],
                ]);
            }

            // OTP is valid: complete login
            $email = $session->get('otp_email');
            $user = User::find($userId);

            if (!$user) {
                throw ValidationException::withMessages([
                    'otp' => ['User not found. Please sign in again.'],
                ]);
            }

            $session->forget(['otp_code', 'otp_expires_at', 'otp_user_id', 'otp_email']);

            Auth::login($user, $request->boolean('remember'));
            $request->session()->regenerate();

            return redirect()->intended('/admin/dashboard');
        }

        // Step 1: validate credentials and send OTP
        try {
            $request->validate([
                'email' => ['required', 'string', 'email'],
                'password' => ['required', 'string'],
            ]);

            // Normalize input to avoid issues with stray spaces/newlines
            $email = trim($request->input('email'));
            $password = trim($request->input('password'));

            // Validate credentials against local database
            if (!Auth::attempt(['email' => $email, 'password' => $password])) {
                throw ValidationException::withMessages([
                    'email' => ['The provided credentials do not match our records.'],
                ]);
            }

            // Get authenticated user
            $user = Auth::user();

            // Logout user until OTP is verified
            Auth::logout();

            // Generate OTP and send via email
            $otpCode = str_pad((string) random_int(0, 999999), 6, '0', STR_PAD_LEFT);

            $request->session()->put('otp_code', $otpCode);
            $request->session()->put('otp_expires_at', now()->addMinutes(10));
            $request->session()->put('otp_user_id', $user->id);
            $request->session()->put('otp_email', $email);

            try {
                Mail::to($email)->send(new OTPVerificationMail($otpCode));
            } catch (\Throwable $e) {
                throw ValidationException::withMessages([
                    'email' => ['Email configuration required. Please configure mail settings to receive OTP.'],
                ]);
            }

            if ($isAjax) {
                return response()->json([
                    'success' => true,
                    'step' => 'otp',
                    'message' => 'OTP sent',
                ]);
            }

            return back()->with('status', 'We have sent a 6-digit verification code to your email. Please enter it below to continue.')
                ->withInput($request->only('email'));
        } catch (ValidationException $e) {
            if ($isAjax) {
                return response()->json([
                    'success' => false,
                    'errors' => $e->errors(),
                ], 422);
            }

            throw $e;
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
        $userId = Session::get('otp_user_id');
        $email = Session::get('otp_email');
        
        if (!$userId || !$email) {
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
        $otp = str_pad((string) random_int(0, 999999), 6, '0', STR_PAD_LEFT);
        
        // Update session with new OTP
        Session::put('otp_code', $otp);
        Session::put('otp_expires_at', now()->addMinutes(10));

        try {
            Mail::to($email)->send(new OTPVerificationMail($otp));
            
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
