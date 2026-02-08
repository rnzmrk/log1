@extends('layouts.auth')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-purple-950 py-12 px-4 sm:px-6 lg:px-8 relative overflow-hidden">
    <div class="max-w-md w-full relative z-10">
        <!-- Auth Card -->
        <div class="bg-white/90 backdrop-blur-xl rounded-3xl shadow-2xl overflow-hidden border border-white/10 ring-1 ring-white/20">
            <!-- Tab Navigation -->
            <div class="flex bg-gray-50/50 backdrop-blur-sm">
                <button type="button" id="loginTab" onclick="switchTab('login')" 
                        class="flex-1 py-4 text-center font-semibold text-purple-600 border-b-3 border-purple-600 transition-all duration-300 relative">
                    Login
                    <div class="absolute bottom-0 left-0 right-0 h-1 bg-purple-600 transform scale-x-100 transition-transform duration-300"></div>
                </button>
                <button type="button" id="registerTab" onclick="switchTab('register')" 
                        class="flex-1 py-4 text-center font-semibold text-gray-400 border-b-3 border-gray-200 transition-all duration-300 relative">
                    Sign up
                    <div class="absolute bottom-0 left-0 right-0 h-1 bg-purple-600 transform scale-x-0 transition-transform duration-300"></div>
                </button>
            </div>

            <!-- Login Form -->
            <div id="loginForm" class="p-8">
                <div class="text-center mb-8">
                    <h2 class="text-2xl font-bold text-gray-900 mb-2">Welcome Back</h2>
                    <p class="text-gray-600">Enter your credentials to access your account</p>
                </div>

                @if(session('status'))
                    <div class="bg-blue-50 border border-blue-200 text-blue-800 px-4 py-3 rounded-lg mb-6">
                        <div class="flex items-center">
                            <i class='bx bx-info-circle text-xl mr-2'></i>
                            <span>{{ session('status') }}</span>
                        </div>
                    </div>
                @endif

                <form class="space-y-6" method="POST" action="{{ route('login.submit') }}" onsubmit="console.log('Form submitting...'); return true;">
                    @csrf
                    
                    <!-- Debug info -->
                    <input type="hidden" name="debug" value="1">
                    
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

                    <div class="space-y-5">
                        <div id="credentialsFields">
                        <!-- Email -->
                        <div>
                            <label for="email" class="block text-sm font-semibold text-gray-700 mb-2">Email Address</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class='bx bx-envelope text-gray-400 text-lg'></i>
                                </div>
                                <input id="email" name="email" type="email" autocomplete="email" required
                                       value="{{ old('email') }}"
                                       placeholder="Enter your email"
                                       class="appearance-none block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-lg placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all">
                            </div>
                            @error('email')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Password -->
                        <div>
                            <label for="password" class="block text-sm font-semibold text-gray-700 mb-2">Password</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class='bx bx-lock text-gray-400 text-lg'></i>
                                </div>
                                <input id="password" name="password" type="password" autocomplete="current-password" required
                                       placeholder="Enter your password"
                                       class="appearance-none block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-lg placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all">
                            </div>
                            @error('password')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        </div>

                        <!-- OTP Field (hidden by default, shown after credentials are validated) -->
                        <div id="otpField" class="hidden">
                            <label for="otp" class="block text-sm font-semibold text-gray-700 mb-2">Verification Code</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class='bx bx-shield-check text-gray-400 text-lg'></i>
                                </div>
                                <input id="otp" name="otp" type="text" maxlength="6" autocomplete="one-time-code"
                                       placeholder="Enter 6-digit code"
                                       class="appearance-none block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-lg placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all text-center text-xl font-mono">
                            </div>
                            @error('otp')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div id="rememberRow" class="flex items-center justify-between mt-5">
                        <div class="flex items-center">
                            <input id="remember-me" name="remember" type="checkbox" class="h-4 w-4 text-purple-600 focus:ring-purple-500 border-gray-300 rounded">
                            <label for="remember-me" class="ml-2 block text-sm text-gray-700">Remember me</label>
                        </div>

                        <div class="text-sm">
                            <a href="#" class="font-medium text-purple-600 hover:text-purple-500 transition-colors">Forgot password?</a>
                        </div>
                    </div>

                    <div class="mt-6">
                        <button type="submit" id="loginButton" class="w-full flex justify-center py-3 px-4 border border-transparent text-sm font-semibold rounded-lg text-white bg-purple-800 hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500 transform hover:scale-[1.02] transition-all duration-200 shadow-lg" onclick="console.log('Button clicked!');">
                            <i class='bx bx-log-in mr-2' id="buttonIcon"></i>
                            <span id="buttonText">Sign In</span>
                        </button>
                    </div>
                </form>
            </div>

            <!-- Register Form -->
            <div id="registerForm" class="p-8 hidden">
                <form id="registerFormElement" class="space-y-6" method="POST" action="{{ route('register.submit') }}">
                    @csrf
                    
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

                    @if(session('success'))
                        <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg">
                            <div class="flex items-center">
                                <i class='bx bx-check-circle text-xl mr-2'></i>
                                <span>{{ session('success') }}</span>
                            </div>
                        </div>
                    @endif

                    <div class="space-y-5">
                        <!-- Name -->
                        <div>
                            <label for="name" class="block text-sm font-semibold text-gray-700 mb-2">Name</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class='bx bx-user text-gray-400 text-lg'></i>
                                </div>
                                <input id="name" name="name" type="text" autocomplete="name" required
                                       value="{{ old('name') }}"
                                       placeholder="Enter your name"
                                       class="appearance-none block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-lg placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all">
                            </div>
                            @error('name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Email -->
                        <div>
                            <label for="register_email" class="block text-sm font-semibold text-gray-700 mb-2">Email</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class='bx bx-envelope text-gray-400 text-lg'></i>
                                </div>
                                <input id="register_email" name="email" type="email" autocomplete="email" required
                                       value="{{ old('email') }}"
                                       placeholder="Enter your email"
                                       class="appearance-none block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-lg placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all">
                            </div>
                            @error('email')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Password -->
                        <div>
                            <label for="register_password" class="block text-sm font-semibold text-gray-700 mb-2">Password</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class='bx bx-lock text-gray-400 text-lg'></i>
                                </div>
                                <input id="register_password" name="password" type="password" autocomplete="new-password" required
                                       placeholder="Create a strong password"
                                       class="appearance-none block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-lg placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all">
                            </div>
                            @error('password')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Confirm Password -->
                        <div>
                            <label for="password_confirmation" class="block text-sm font-semibold text-gray-700 mb-2">Confirm password</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class='bx bx-lock-alt text-gray-400 text-lg'></i>
                                </div>
                                <input id="password_confirmation" name="password_confirmation" type="password" autocomplete="new-password" required
                                       placeholder="Confirm your password"
                                       class="appearance-none block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-lg placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all">
                            </div>
                            @error('password_confirmation')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Role Selection -->
                        <div>
                            <label for="role" class="block text-sm font-semibold text-gray-700 mb-2">Account Type</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class='bx bx-briefcase text-gray-400 text-lg'></i>
                                </div>
                                <select id="role" name="role" required
                                        class="appearance-none block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-lg placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all">
                                    <option value="">Select account type</option>
                                    <option value="admin" {{ old('role') === 'admin' ? 'selected' : '' }}>Administrator</option>
                                    <option value="manager" {{ old('role') === 'manager' ? 'selected' : '' }}>Manager</option>
                                    <option value="user" {{ old('role') === 'user' ? 'selected' : '' }}>User</option>
                                </select>
                            </div>
                            @error('role')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Terms and Privacy -->
                    <div class="flex items-start">
                        <input id="agree_terms" name="agree_terms" type="checkbox" required
                               class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded mt-1">
                        <label for="agree_terms" class="ml-3 block text-sm text-gray-700">
                            I agree to the 
                            <a href="#" class="text-blue-600 hover:text-blue-500 font-medium transition-colors">Terms of Service</a> 
                            and 
                            <a href="#" class="text-blue-600 hover:text-blue-500 font-medium transition-colors">Privacy Policy</a>
                        </label>
                    </div>
                    @error('agree_terms')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror

                    <div>
                        <button type="submit" class="w-full flex justify-center py-3 px-4 border border-transparent text-sm font-semibold rounded-lg text-white bg-purple-800 hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500 transform hover:scale-[1.02] transition-all duration-200 shadow-lg">
                            Sign up
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function switchTab(tab) {
    const loginTab = document.getElementById('loginTab');
    const registerTab = document.getElementById('registerTab');
    const loginForm = document.getElementById('loginForm');
    const registerForm = document.getElementById('registerForm');

    if (tab === 'login') {
        // Show login
        loginTab.classList.add('text-purple-600');
        loginTab.classList.remove('text-gray-400');
        registerTab.classList.remove('text-purple-600');
        registerTab.classList.add('text-gray-400');
        
        // Animate underline
        loginTab.querySelector('div').classList.remove('scale-x-0');
        loginTab.querySelector('div').classList.add('scale-x-100');
        registerTab.querySelector('div').classList.add('scale-x-0');
        registerTab.querySelector('div').classList.remove('scale-x-100');
        
        loginForm.classList.remove('hidden');
        registerForm.classList.add('hidden');
    } else {
        // Show register
        registerTab.classList.add('text-purple-600');
        registerTab.classList.remove('text-gray-400');
        loginTab.classList.remove('text-purple-600');
        loginTab.classList.add('text-gray-400');
        
        // Animate underline
        registerTab.querySelector('div').classList.remove('scale-x-0');
        registerTab.querySelector('div').classList.add('scale-x-100');
        loginTab.querySelector('div').classList.add('scale-x-0');
        loginTab.querySelector('div').classList.remove('scale-x-100');
        
        registerForm.classList.remove('hidden');
        loginForm.classList.add('hidden');
    }
}

// Check if OTP was sent and show verification UI
document.addEventListener('DOMContentLoaded', function() {
    console.log('Page loaded, setting up form...');
    
    // Show OTP field if there's a status message (OTP sent)
    const statusMessage = '{{ session('status') }}';
    if (statusMessage && statusMessage.includes('verification code')) {
        showOTPVerification();
    }
    
    // Focus on OTP field if it's visible
    const otpField = document.getElementById('otp');
    if (otpField && !document.getElementById('otpField').classList.contains('hidden')) {
        otpField.focus();
    }
    
    // Ensure form submission works
    const loginForm = document.querySelector('form[action*="login"]');
    if (loginForm) {
        console.log('Login form found');
        loginForm.addEventListener('submit', function(e) {
            console.log('Form submit event triggered');
        });
    }
});

function showOTPVerification() {
    // Show OTP field
    const otpField = document.getElementById('otpField');
    if (otpField) {
        otpField.classList.remove('hidden');
    }

    const credentialsFields = document.getElementById('credentialsFields');
    if (credentialsFields) {
        credentialsFields.classList.add('hidden');
    }

    const rememberRow = document.getElementById('rememberRow');
    if (rememberRow) {
        rememberRow.classList.add('hidden');
    }

    const emailInput = document.getElementById('email');
    if (emailInput) {
        emailInput.removeAttribute('required');
        emailInput.setAttribute('disabled', 'disabled');
    }

    const passwordInput = document.getElementById('password');
    if (passwordInput) {
        passwordInput.removeAttribute('required');
        passwordInput.setAttribute('disabled', 'disabled');
    }

    const otpInput = document.getElementById('otp');
    if (otpInput) {
        otpInput.setAttribute('required', 'required');
    }
    
    // Update button
    const buttonIcon = document.getElementById('buttonIcon');
    const buttonText = document.getElementById('buttonText');
    if (buttonIcon && buttonText) {
        buttonIcon.className = 'bx bx-check-shield mr-2';
        buttonText.textContent = 'Verify & Login';
    }
    
    // Focus on OTP input
    setTimeout(() => {
        const otpInput = document.getElementById('otp');
        if (otpInput) {
            otpInput.focus();
        }
    }, 100);
}
</script>

<style>
.letter-spacing-2 {
    letter-spacing: 0.5em;
}

.border-b-3 {
    border-bottom-width: 3px;
}

.transform.scale-x-0 {
    transform: scaleX(0);
}

.transform.scale-x-100 {
    transform: scaleX(1);
}
</style>
@endsection
