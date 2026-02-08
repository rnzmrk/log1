@extends('layouts.auth')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-purple-100 via-purple-50 to-pink-50 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full">
        <!-- Logo and Brand -->
        <div class="text-center mb-8">
            <div class="mx-auto h-16 w-16 bg-gradient-to-r from-blue-600 to-purple-600 rounded-2xl flex items-center justify-center shadow-lg mb-4">
                <i class='bx bx-store text-white text-3xl'></i>
            </div>
            <h1 class="text-3xl font-bold bg-gradient-to-r from-blue-600 to-purple-600 bg-clip-text text-transparent">
                iMarket
            </h1>
            <p class="text-gray-600 mt-1">Supply Chain Management System</p>
        </div>

        <!-- Login Card -->
        <div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-xl border border-white/20 p-8">
            <div class="text-center mb-6">
                <h2 class="text-2xl font-bold text-gray-900 mb-2">Welcome Back</h2>
                <p class="text-gray-600">Sign in to access your dashboard</p>
            </div>
        
        <form class="space-y-6" method="POST" action="{{ route('login.submit') }}">
            @csrf
            <input type="hidden" name="login_step" value="credentials">
            
            <!-- Error Messages -->
            @if ($errors->any())
                <div class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg">
                    <div class="flex items-center mb-2">
                        <i class='bx bx-error-circle text-xl mr-2'></i>
                        <span class="font-medium">Please fix the following errors:</span>
                    </div>
                    <ul class="list-disc list-inside space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if(session('error'))
                <div class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg">
                    <div class="flex items-center">
                        <i class='bx bx-error-circle text-xl mr-2'></i>
                        <span>{{ session('error') }}</span>
                    </div>
                </div>
            @endif

            <!-- Credentials Step -->
            <div id="credentialsStep">
                <div class="space-y-5">
                    <div>
                        <label for="email" class="block text-sm font-semibold text-gray-700 mb-2">Email Address</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class='bx bx-envelope text-gray-400 text-xl'></i>
                            </div>
                            <input id="email" name="email" type="email" autocomplete="email" required
                                   value="{{ old('email') }}"
                                   placeholder="Enter your email"
                                   class="appearance-none block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-xl placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all">
                        </div>
                        @error('email')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="password" class="block text-sm font-semibold text-gray-700 mb-2">Password</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class='bx bx-lock text-gray-400 text-xl'></i>
                            </div>
                            <input id="password" name="password" type="password" autocomplete="current-password" required
                                   placeholder="Enter your password"
                                   class="appearance-none block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-xl placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all">
                        </div>
                        @error('password')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="flex items-center justify-between mt-5">
                    <div class="flex items-center">
                        <input id="remember-me" name="remember" type="checkbox" class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                        <label for="remember-me" class="ml-2 block text-sm text-gray-700">Remember me</label>
                    </div>

                    <div class="text-sm">
                        <a href="#" class="font-medium text-blue-600 hover:text-blue-500 transition-colors">Forgot password?</a>
                    </div>
                </div>

                <div class="mt-6">
                    <button type="submit" class="w-full flex justify-center py-3 px-4 border border-transparent text-sm font-semibold rounded-xl text-white bg-gradient-to-r from-blue-600 to-purple-600 hover:from-blue-700 hover:to-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transform hover:scale-[1.02] transition-all duration-200 shadow-lg">
                        <i class='bx bx-lock-alt mr-2'></i>
                        Sign In
                    </button>
                </div>

                <div class="mt-6 text-center">
                    <p class="text-sm text-gray-600">
                        Don't have an account?
                        <a href="/register" class="font-semibold text-blue-600 hover:text-blue-500 transition-colors">
                            Create one here
                        </a>
                    </p>
                </div>
            </div>

            <!-- OTP Step -->
            <div id="otpStep" class="hidden">
                <div class="text-center">
                    <div class="mx-auto h-16 w-16 bg-gradient-to-r from-green-500 to-emerald-600 rounded-2xl flex items-center justify-center shadow-lg mb-4">
                        <i class='bx bx-mail-send text-white text-3xl'></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">Check Your Email</h3>
                    <p class="text-gray-600 mb-6">
                        We've sent a 6-digit verification code to your email address.
                    </p>
                </div>

                <div class="mb-6">
                    <label for="otp" class="block text-sm font-semibold text-gray-700 mb-3 text-center">Enter Verification Code</label>
                    <div class="relative">
                        <input id="otp" name="otp" type="text" maxlength="6" pattern="[0-9]{6}" required
                               placeholder="000000"
                               class="appearance-none block w-full px-4 py-4 border-2 border-gray-300 rounded-xl placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent text-center text-2xl font-bold tracking-widest letter-spacing-2 transition-all">
                    </div>
                    @error('otp')
                        <p class="mt-2 text-sm text-red-600 text-center">{{ $message }}</p>
                    @enderror
                </div>

                <div class="space-y-3">
                    <button type="submit" name="login_step" value="otp" class="w-full flex justify-center py-3 px-4 border border-transparent text-sm font-semibold rounded-xl text-white bg-gradient-to-r from-green-600 to-emerald-600 hover:from-green-700 hover:to-emerald-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transform hover:scale-[1.02] transition-all duration-200 shadow-lg">
                        <i class='bx bx-check-shield mr-2'></i>
                        Verify & Continue
                    </button>

                    <div class="text-center">
                        <button type="button" onclick="resendOTP()" class="text-sm text-blue-600 hover:text-blue-500 transition-colors">
                            <i class='bx bx-refresh mr-1'></i>
                            Resend code
                        </button>
                    </div>
                </div>
            </div>
        </form>
        </div>

        <!-- Footer -->
        <div class="text-center mt-8 text-sm text-gray-500">
            <p>&copy; 2024 iMarket. All rights reserved.</p>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('form');
    
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(form);
        const loginStep = formData.get('login_step');
        
        if (loginStep === 'credentials') {
            // Handle credentials step with AJAX
            fetch(form.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => {
                if (!response.ok) {
                    throw response;
                }
                return response.json();
            })
            .then(data => {
                if (data.success && data.step === 'otp') {
                    showOTPStep();
                } else {
                    // Reload page to show errors
                    window.location.reload();
                }
            })
            .catch(error => {
                console.error('Error:', error);
                // Fallback to normal form submission so Laravel can render validation errors
                form.submit();
            });
        } else {
            // Submit OTP form normally
            form.submit();
        }
    });
});

function showOTPStep() {
    document.getElementById('credentialsStep').classList.add('hidden');
    document.getElementById('otpStep').classList.remove('hidden');
}

function resendOTP() {
    fetch('/resend-otp', {
        method: 'POST',
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('New OTP sent to your email');
        } else {
            alert('Failed to resend OTP: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Failed to resend OTP. Please try again.');
    });
}
</script>

<style>
.letter-spacing-2 {
    letter-spacing: 0.5em;
}
</style>
@endsection
