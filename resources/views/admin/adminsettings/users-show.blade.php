@extends('layouts.app')

@section('content')
<div class="container-fluid px-6 py-8">
    <!-- Breadcrumbs -->
    <nav class="flex mb-6" aria-label="Breadcrumb">
        <ol class="inline-flex items-center space-x-1 md:space-x-3">
            <li class="inline-flex items-center">
                <a href="{{ route('admin.dashboard') }}" class="text-gray-700 hover:text-blue-600 inline-flex items-center">
                    <i class='bx bx-home text-xl mr-2'></i>
                    Home
                </a>
            </li>
            <li>
                <div class="flex items-center">
                    <i class='bx bx-chevron-right text-gray-400'></i>
                    <a href="#" class="text-gray-700 hover:text-blue-600 ml-1 md:ml-2">Admin Settings</a>
                </div>
            </li>
            <li>
                <div class="flex items-center">
                    <i class='bx bx-chevron-right text-gray-400'></i>
                    <a href="{{ route('admin.adminsettings.users-roles') }}" class="text-gray-700 hover:text-blue-600 ml-1 md:ml-2">Users & Roles</a>
                </div>
            </li>
            <li aria-current="page">
                <div class="flex items-center">
                    <i class='bx bx-chevron-right text-gray-400'></i>
                    <span class="ml-1 text-gray-500 md:ml-2">User Details</span>
                </div>
            </li>
        </ol>
    </nav>

    <!-- Page Header -->
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-900">User Details</h1>
        <div class="flex gap-3">
            <a href="{{ route('users.edit', $user->id) }}" class="bg-orange-600 hover:bg-orange-700 text-white font-medium py-2 px-4 rounded-lg flex items-center gap-2 transition-colors">
                <i class='bx bx-edit text-xl'></i>
                Edit User
            </a>
            <a href="{{ route('admin.adminsettings.users-roles') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-medium py-2 px-4 rounded-lg flex items-center gap-2 transition-colors">
                <i class='bx bx-arrow-back text-xl'></i>
                Back to Users
            </a>
        </div>
    </div>

    <!-- User Details -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
        <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
            <h2 class="text-lg font-semibold text-gray-900">{{ $user->name }}</h2>
            <span class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full bg-{{ $user->status_color }}-100 text-{{ $user->status_color }}-800">
                {{ $user->status ?? 'Active' }}
            </span>
        </div>
        
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Profile Information -->
                <div class="space-y-4">
                    <h3 class="text-md font-semibold text-gray-900">Profile Information</h3>
                    
                    <div class="flex items-center space-x-4">
                        <div class="h-20 w-20 rounded-full bg-{{ $user->avatar_color }}-600 flex items-center justify-center text-white font-bold text-2xl">
                            {{ $user->initials }}
                        </div>
                        <div>
                            <p class="text-lg font-medium text-gray-900">{{ $user->name }}</p>
                            <p class="text-sm text-gray-500">{{ $user->user_number ?? 'ID-' . $user->id }}</p>
                            <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-{{ $user->role_color }}-100 text-{{ $user->role_color }}-800">
                                {{ $user->role ?? 'User' }}
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Contact Information -->
                <div class="space-y-4">
                    <h3 class="text-md font-semibold text-gray-900">Contact Information</h3>
                    
                    <div class="grid grid-cols-1 gap-4">
                        <div>
                            <p class="text-sm font-medium text-gray-500">Email Address</p>
                            <p class="text-sm text-gray-900">{{ $user->email }}</p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-500">Phone Number</p>
                            <p class="text-sm text-gray-900">{{ $user->phone ?? 'Not provided' }}</p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-500">Department</p>
                            <p class="text-sm text-gray-900">{{ $user->department ?? 'Not assigned' }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Account Information -->
            <div class="mt-6">
                <h3 class="text-md font-semibold text-gray-900 mb-4">Account Information</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <p class="text-sm font-medium text-gray-500">Account Status</p>
                        <p class="text-sm text-gray-900">{{ $user->status ?? 'Active' }}</p>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500">Member Since</p>
                        <p class="text-sm text-gray-900">{{ $user->created_at->format('M d, Y') }}</p>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500">Last Login</p>
                        <p class="text-sm text-gray-900">{{ $user->last_login_at ? $user->last_login_at->format('M d, Y H:i') : 'Never' }}</p>
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="flex gap-3 mt-8 pt-6 border-t border-gray-200">
                <a href="{{ route('users.edit', $user->id) }}" class="bg-orange-600 hover:bg-orange-700 text-white font-medium py-2 px-6 rounded-lg transition-colors">
                    <i class='bx bx-edit mr-2'></i>
                    Edit User
                </a>
                <form action="{{ route('users.toggle-status', $user->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to change this user\'s status?')" class="inline">
                    @csrf
                    @method('PUT')
                    <button type="submit" class="bg-purple-600 hover:bg-purple-700 text-white font-medium py-2 px-6 rounded-lg transition-colors">
                        <i class='bx bx-user-voice mr-2'></i>
                        Toggle Status
                    </button>
                </form>
                <form action="{{ route('users.destroy', $user->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this user? This action cannot be undone.')" class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="bg-red-600 hover:bg-red-700 text-white font-medium py-2 px-6 rounded-lg transition-colors">
                        <i class='bx bx-trash mr-2'></i>
                        Delete User
                    </button>
                </form>
                <a href="{{ route('admin.adminsettings.users-roles') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-medium py-2 px-6 rounded-lg transition-colors">
                    <i class='bx bx-arrow-back mr-2'></i>
                    Back to Users
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
