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
                    <a href="#" class="text-gray-700 hover:text-blue-600 ml-1 md:ml-2">Document Tracking</a>
                </div>
            </li>
            <li>
                <div class="flex items-center">
                    <i class='bx bx-chevron-right text-gray-400'></i>
                    <a href="{{ route('admin.documenttracking.verification') }}" class="text-gray-700 hover:text-blue-600 ml-1 md:ml-2">Verification</a>
                </div>
            </li>
            <li>
                <div class="flex items-center">
                    <i class='bx bx-chevron-right text-gray-400'></i>
                    <span class="text-gray-500 ml-1 md:ml-2">Verification Details</span>
                </div>
            </li>
        </ol>
    </nav>

    <!-- Page Header -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Verification Details</h1>
            <p class="text-gray-600 mt-1">Complete supplier verification information and results</p>
        </div>
        <div class="flex gap-3">
            <a href="{{ route('admin.documenttracking.verification') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-medium py-2 px-4 rounded-lg flex items-center gap-2 transition-colors">
                <i class='bx bx-arrow-back text-xl'></i>
                Back to Verification
            </a>
            @if ($verification->status === 'pending' || $verification->status === 'scheduled')
                <a href="{{ route('admin.documenttracking.edit-verification', $verification->id) }}" class="bg-green-600 hover:bg-green-700 text-white font-medium py-2 px-4 rounded-lg flex items-center gap-2 transition-colors">
                    <i class='bx bx-check-circle text-xl'></i>
                    Complete Verification
                </a>
            @endif
        </div>
    </div>

    <!-- Success Messages -->
    @if(session('success'))
        <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg mb-6">
            <div class="flex items-center">
                <i class='bx bx-check-circle text-xl mr-2'></i>
                <span>{{ session('success') }}</span>
            </div>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Verification Information -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-xl font-semibold text-gray-900">Verification Information</h2>
                    <span class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full 
                        @if ($verification->status === 'passed') bg-green-100 text-green-800
                        @elseif ($verification->status === 'failed') bg-red-100 text-red-800
                        @elseif ($verification->status === 'pending') bg-orange-100 text-orange-800
                        @elseif ($verification->status === 'scheduled') bg-blue-100 text-blue-800
                        @else bg-gray-100 text-gray-800 @endif">
                        {{ ucfirst($verification->status) }}
                    </span>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Supplier Name</label>
                        <p class="text-gray-900 font-medium">{{ $verification->supplier->name ?? 'N/A' }}</p>
                        @if ($verification->supplier->vendor_code)
                            <p class="text-xs text-gray-500">{{ $verification->supplier->vendor_code }}</p>
                        @endif
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Verification Type</label>
                        <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-purple-100 text-purple-800">
                            {{ ucfirst($verification->verification_type) }}
                        </span>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Verification Date</label>
                        <p class="text-gray-900">
                            @if ($verification->verification_date)
                                {{ $verification->verification_date->format('M d, Y') }}
                            @else
                                Not scheduled
                            @endif
                        </p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Score & Grade</label>
                        @if ($verification->score !== null)
                            <div class="flex items-center">
                                <span class="text-lg font-bold text-gray-900">{{ $verification->score }}/100</span>
                                <span class="ml-2 px-2 py-1 text-sm font-semibold rounded 
                                    @if ($verification->score >= 80) bg-green-100 text-green-800
                                    @elseif ($verification->score >= 60) bg-orange-100 text-orange-800
                                    @else bg-red-100 text-red-800 @endif">
                                    Grade {{ $verification->grade }}
                                </span>
                            </div>
                        @else
                            <span class="text-gray-500">Not scored</span>
                        @endif
                    </div>
                </div>

                @if ($verification->findings)
                    <div class="mt-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Findings</label>
                        <div class="bg-gray-50 rounded-lg p-4">
                            <p class="text-gray-900 whitespace-pre-wrap">{{ $verification->findings }}</p>
                        </div>
                    </div>
                @endif

                @if ($verification->recommendations)
                    <div class="mt-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Recommendations</label>
                        <div class="bg-blue-50 rounded-lg p-4">
                            <p class="text-gray-900 whitespace-pre-wrap">{{ $verification->recommendations }}</p>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Supplier Information -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-6">Supplier Information</h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Contact Person</label>
                        <p class="text-gray-900">{{ $verification->supplier->contact_person ?? 'N/A' }}</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Category</label>
                        <p class="text-gray-900">{{ $verification->supplier->category ?? 'N/A' }}</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                        <p class="text-gray-900">{{ $verification->supplier->email ?? 'N/A' }}</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Phone</label>
                        <p class="text-gray-900">{{ $verification->supplier->phone ?? 'N/A' }}</p>
                    </div>
                </div>

                @if ($verification->supplier->address)
                    <div class="mt-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Address</label>
                        <p class="text-gray-900">{{ $verification->supplier->address }}</p>
                        @if ($verification->supplier->city || $verification->supplier->state || $verification->supplier->postal_code)
                            <p class="text-gray-900">
                                {{ $verification->supplier->city ?? '' }}{{ $verification->supplier->city && $verification->supplier->state ? ', ' : '' }}{{ $verification->supplier->state ?? '' }}{{ ($verification->supplier->city || $verification->supplier->state) && $verification->supplier->postal_code ? ' ' : '' }}{{ $verification->supplier->postal_code ?? '' }}
                            </p>
                        @endif
                    </div>
                @endif
            </div>

            <!-- Report Documents -->
            @if ($verification->report_path)
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-6">Verification Report</h2>
                    <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                        <div class="flex items-center">
                            <i class='bx bx-file-pdf text-red-500 text-2xl mr-3'></i>
                            <div>
                                <p class="text-sm font-medium text-gray-900">Verification Report</p>
                                <p class="text-xs text-gray-500">{{ basename($verification->report_path) }}</p>
                            </div>
                        </div>
                        <a href="{{ asset($verification->report_path) }}" target="_blank" class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg flex items-center gap-2 transition-colors">
                            <i class='bx bx-download text-xl'></i>
                            Download
                        </a>
                    </div>
                </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Verification Status -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Verification Status</h3>
                
                <div class="space-y-4">
                    <div class="flex items-center justify-between">
                        <span class="text-sm font-medium text-gray-700">Current Status:</span>
                        <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full 
                            @if ($verification->status === 'passed') bg-green-100 text-green-800
                            @elseif ($verification->status === 'failed') bg-red-100 text-red-800
                            @elseif ($verification->status === 'pending') bg-orange-100 text-orange-800
                            @elseif ($verification->status === 'scheduled') bg-blue-100 text-blue-800
                            @else bg-gray-100 text-gray-800 @endif">
                            {{ ucfirst($verification->status) }}
                        </span>
                    </div>

                    <div class="flex items-center justify-between">
                        <span class="text-sm font-medium text-gray-700">Created:</span>
                        <span class="text-sm text-gray-900">{{ $verification->created_at->format('M d, Y') }}</span>
                    </div>

                    @if ($verification->verification_date)
                        <div class="flex items-center justify-between">
                            <span class="text-sm font-medium text-gray-700">Verified:</span>
                            <span class="text-sm text-gray-900">{{ $verification->verification_date->format('M d, Y') }}</span>
                        </div>
                    @endif

                    @if ($verification->verifier)
                        <div class="flex items-center justify-between">
                            <span class="text-sm font-medium text-gray-700">Verified By:</span>
                            <span class="text-sm text-gray-900">{{ $verification->verifier->name }}</span>
                        </div>
                    @endif

                    @if ($verification->scheduled_at)
                        <div class="flex items-center justify-between">
                            <span class="text-sm font-medium text-gray-700">Scheduled:</span>
                            <span class="text-sm text-gray-900">{{ $verification->scheduled_at->format('M d, Y H:i') }}</span>
                        </div>
                    @endif

                    @if ($verification->scheduler)
                        <div class="flex items-center justify-between">
                            <span class="text-sm font-medium text-gray-700">Scheduled By:</span>
                            <span class="text-sm text-gray-900">{{ $verification->scheduler->name }}</span>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Score Breakdown -->
            @if ($verification->score !== null)
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Score Breakdown</h3>
                    
                    <div class="space-y-4">
                        <div class="flex items-center justify-between">
                            <span class="text-sm font-medium text-gray-700">Final Score:</span>
                            <span class="text-lg font-bold text-gray-900">{{ $verification->score }}/100</span>
                        </div>

                        <div class="flex items-center justify-between">
                            <span class="text-sm font-medium text-gray-700">Grade:</span>
                            <span class="px-2 py-1 text-sm font-semibold rounded 
                                @if ($verification->score >= 80) bg-green-100 text-green-800
                                @elseif ($verification->score >= 60) bg-orange-100 text-orange-800
                                @else bg-red-100 text-red-800 @endif">
                                Grade {{ $verification->grade }}
                            </span>
                        </div>

                        <!-- Score Progress Bar -->
                        <div class="mt-4">
                            <div class="flex justify-between text-xs text-gray-600 mb-1">
                                <span>0</span>
                                <span>Score</span>
                                <span>100</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2">
                                <div class="h-2 rounded-full 
                                    @if ($verification->score >= 80) bg-green-500
                                    @elseif ($verification->score >= 60) bg-orange-500
                                    @else bg-red-500 @endif" 
                                    style="width: {{ $verification->score }}%"></div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
