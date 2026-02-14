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
                    <a href="#" class="text-gray-700 hover:text-blue-600 ml-1 md:ml-2">Asset Lifecycle & Maintenance</a>
                </div>
            </li>
            <li>
                <div class="flex items-center">
                    <i class='bx bx-chevron-right text-gray-400'></i>
                    <a href="{{ route('admin.assetlifecycle.request-asset') }}" class="text-gray-700 hover:text-blue-600 ml-1 md:ml-2">Request Asset</a>
                </div>
            </li>
            <li aria-current="page">
                <div class="flex items-center">
                    <i class='bx bx-chevron-right text-gray-400'></i>
                    <span class="ml-1 text-gray-500 md:ml-2">Asset Request Details</span>
                </div>
            </li>
        </ol>
    </nav>

    <!-- Page Header -->
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-900">Asset Request Details</h1>
        <div class="flex gap-3">
            <a href="{{ route('admin.assetlifecycle.request-asset') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-medium py-2 px-4 rounded-lg flex items-center gap-2 transition-colors">
                <i class='bx bx-arrow-back text-xl'></i>
                Back to Requests
            </a>
        </div>
    </div>

    <!-- Success Message -->
    @if (session('success'))
        <div class="mb-6 bg-green-50 border border-green-200 rounded-md p-4">
            <div class="flex">
                <div class="flex-shrink-0">
                    <i class='bx bx-check-circle text-green-400 text-xl'></i>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-green-800">{{ session('success') }}</p>
                </div>
            </div>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Request Information -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-900">Request Information</h2>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <p class="text-sm text-gray-600">Request Number</p>
                            <p class="font-semibold text-gray-900">{{ $assetRequest->request_number }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Asset Name</p>
                            <p class="font-semibold text-gray-900">{{ $assetRequest->asset_name }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Quantity</p>
                            <p class="font-semibold text-gray-900">1</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Request Type</p>
                            <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-purple-100 text-purple-800">
                                {{ $assetRequest->request_type }}
                            </span>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Priority</p>
                            @if ($assetRequest->priority === 'Urgent')
                                <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                    Urgent
                                </span>
                            @elseif ($assetRequest->priority === 'High')
                                <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-orange-100 text-orange-800">
                                    High
                                </span>
                            @elseif ($assetRequest->priority === 'Medium')
                                <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                    Medium
                                </span>
                            @else
                                <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                    Low
                                </span>
                            @endif
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Status</p>
                            @if ($assetRequest->status === 'Pending')
                                <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                    Pending
                                </span>
                            @elseif ($assetRequest->status === 'Approved')
                                <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                    Approved
                                </span>
                            @elseif ($assetRequest->status === 'Rejected')
                                <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                    Rejected
                                </span>
                            @elseif ($assetRequest->status === 'Processing')
                                <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                    Processing
                                </span>
                            @elseif ($assetRequest->status === 'Completed')
                                <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-purple-100 text-purple-800">
                                    Completed
                                </span>
                            @else
                                <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                    Cancelled
                                </span>
                            @endif
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Request Date</p>
                            <p class="font-semibold text-gray-900">{{ $assetRequest->request_date->format('M d, Y') }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Department</p>
                            <p class="font-semibold text-gray-900">{{ $assetRequest->department }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
