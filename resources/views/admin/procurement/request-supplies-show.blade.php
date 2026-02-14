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
                    <a href="#" class="text-gray-700 hover:text-blue-600 ml-1 md:ml-2">Procurement</a>
                </div>
            </li>
            <li>
                <div class="flex items-center">
                    <i class='bx bx-chevron-right text-gray-400'></i>
                    <a href="{{ route('admin.procurement.request-supplies') }}" class="text-gray-700 hover:text-blue-600 ml-1 md:ml-2">Request Supplies</a>
                </div>
            </li>
            <li aria-current="page">
                <div class="flex items-center">
                    <i class='bx bx-chevron-right text-gray-400'></i>
                    <span class="ml-1 text-gray-500 md:ml-2">View Request</span>
                </div>
            </li>
        </ol>
    </nav>

    <!-- Page Header -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">{{ $supplyRequest->request_id }}</h1>
            <p class="text-gray-600 mt-1">{{ $supplyRequest->item_name }}</p>
        </div>
        <div class="flex gap-3">
            <a href="{{ route('admin.procurement.request-supplies') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-medium py-2 px-4 rounded-lg flex items-center gap-2 transition-colors">
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
                    <i class='bx bx-check text-green-400 text-xl'></i>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-green-800">{{ session('success') }}</p>
                </div>
            </div>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Information -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Request Details Card -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-900">Request Details</h2>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">Request ID</label>
                            <p class="text-gray-900 font-medium">{{ $supplyRequest->request_id }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">Item Name</label>
                            <p class="text-gray-900">{{ $supplyRequest->item_name }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">Category</label>
                            <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                {{ $supplyRequest->category }}
                            </span>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">Supplier</label>
                            <p class="text-gray-900">{{ $supplyRequest->supplier }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">Quantity Requested</label>
                            <p class="text-gray-900">{{ $supplyRequest->quantity_requested }}</p>
                        </div>
                        @if ($supplyRequest->quantity_approved)
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">Quantity Approved</label>
                            <p class="text-gray-900 text-green-600 font-medium">{{ $supplyRequest->quantity_approved }}</p>
                        </div>
                        @endif
                        @if ($supplyRequest->unit_price)
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">Unit Price</label>
                            <p class="text-gray-900">₱{{ number_format($supplyRequest->unit_price, 2) }}</p>
                        </div>
                        @endif
                        @if ($supplyRequest->total_cost)
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">Total Cost</label>
                            <p class="text-gray-900 font-medium text-lg">₱{{ number_format($supplyRequest->total_cost, 2) }}</p>
                        </div>
                        @endif
                    </div>
                    
                    @if ($supplyRequest->notes)
                    <div class="mt-6">
                        <label class="block text-sm font-medium text-gray-500 mb-1">Notes</label>
                        <p class="text-gray-900">{{ $supplyRequest->notes }}</p>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Status Information Card -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-900">Status Information</h2>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div class="text-center">
                            <div class="text-3xl font-bold text-gray-900">{{ $supplyRequest->quantity_requested }}</div>
                            <div class="text-sm text-gray-500 mt-1">Items Requested</div>
                        </div>
                        @if ($supplyRequest->quantity_approved)
                        <div class="text-center">
                            <div class="text-3xl font-bold text-green-600">{{ $supplyRequest->quantity_approved }}</div>
                            <div class="text-sm text-gray-500 mt-1">Items Approved</div>
                        </div>
                        @endif
                        <div class="text-center">
                            @if ($supplyRequest->status === 'Pending')
                                <span class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full bg-orange-100 text-orange-800">
                                    Pending
                                </span>
                            @elseif ($supplyRequest->status === 'Approved')
                                <span class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                    Approved
                                </span>
                            @elseif ($supplyRequest->status === 'Rejected')
                                <span class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                    Rejected
                                </span>
                            @elseif ($supplyRequest->status === 'Ordered')
                                <span class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full bg-purple-100 text-purple-800">
                                    Ordered
                                </span>
                            @else
                                <span class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                    Received
                                </span>
                            @endif
                            <div class="text-sm text-gray-500 mt-1">Current Status</div>
                        </div>
                    </div>
                    
                    <!-- Priority Indicator -->
                    <div class="mt-6">
                        <div class="flex justify-between text-sm text-gray-600 mb-2">
                            <span>Priority Level</span>
                            <span>{{ $supplyRequest->priority }}</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-3">
                            @if ($supplyRequest->priority === 'Urgent')
                                <div class="bg-red-500 h-3 rounded-full" style="width: 100%"></div>
                            @elseif ($supplyRequest->priority === 'High')
                                <div class="bg-orange-500 h-3 rounded-full" style="width: 75%"></div>
                            @elseif ($supplyRequest->priority === 'Medium')
                                <div class="bg-yellow-500 h-3 rounded-full" style="width: 50%"></div>
                            @else
                                <div class="bg-green-500 h-3 rounded-full" style="width: 25%"></div>
                            @endif
                        </div>
                        <div class="mt-2 text-xs text-gray-500">
                            @if ($supplyRequest->priority === 'Urgent')
                                Immediate attention required - Critical business need
                            @elseif ($supplyRequest->priority === 'High')
                                High priority - Important for operations
                            @elseif ($supplyRequest->priority === 'Medium')
                                Normal priority - Standard processing time
                            @else
                                Low priority - Can be processed when resources available
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Timeline Card -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-900">Request Timeline</h2>
                </div>
                <div class="p-6">
                    <div class="space-y-4">
                        <div class="flex items-start gap-3">
                            <div class="flex-shrink-0 w-2 h-2 bg-blue-500 rounded-full mt-2"></div>
                            <div>
                                <p class="text-sm font-medium text-gray-900">Request Submitted</p>
                                <p class="text-sm text-gray-600">{{ $supplyRequest->request_date->format('M d, Y') }} by {{ $supplyRequest->requested_by }}</p>
                            </div>
                        </div>
                        
                        <div class="flex items-start gap-3">
                            <div class="flex-shrink-0 w-2 h-2 bg-orange-500 rounded-full mt-2"></div>
                            <div>
                                <p class="text-sm font-medium text-gray-900">Needed By</p>
                                <p class="text-sm text-gray-600">{{ $supplyRequest->needed_by_date->format('M d, Y') }}</p>
                            </div>
                        </div>
                        
                        @if ($supplyRequest->approval_date)
                        <div class="flex items-start gap-3">
                            <div class="flex-shrink-0 w-2 h-2 bg-green-500 rounded-full mt-2"></div>
                            <div>
                                <p class="text-sm font-medium text-gray-900">Approved</p>
                                <p class="text-sm text-gray-600">{{ $supplyRequest->approval_date->format('M d, Y') }} @if($supplyRequest->approved_by) by {{ $supplyRequest->approved_by }} @endif</p>
                            </div>
                        </div>
                        @endif
                        
                        @if ($supplyRequest->order_date)
                        <div class="flex items-start gap-3">
                            <div class="flex-shrink-0 w-2 h-2 bg-purple-500 rounded-full mt-2"></div>
                            <div>
                                <p class="text-sm font-medium text-gray-900">Order Placed</p>
                                <p class="text-sm text-gray-600">{{ $supplyRequest->order_date->format('M d, Y') }}</p>
                            </div>
                        </div>
                        @endif
                        
                        @if ($supplyRequest->expected_delivery)
                        <div class="flex items-start gap-3">
                            <div class="flex-shrink-0 w-2 h-2 bg-yellow-500 rounded-full mt-2"></div>
                            <div>
                                <p class="text-sm font-medium text-gray-900">Expected Delivery</p>
                                <p class="text-sm text-gray-600">{{ $supplyRequest->expected_delivery->format('M d, Y') }}</p>
                            </div>
                        </div>
                        @endif
                        
                        <div class="flex items-start gap-3">
                            <div class="flex-shrink-0 w-2 h-2 bg-gray-500 rounded-full mt-2"></div>
                            <div>
                                <p class="text-sm font-medium text-gray-900">Last Updated</p>
                                <p class="text-sm text-gray-600">{{ $supplyRequest->updated_at->format('M d, Y H:i') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Actions Sidebar -->
        <div class="space-y-6">
            <!-- Quick Actions -->
           

            <!-- System Information -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-900">System Information</h2>
                </div>
                <div class="p-6 space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-500 mb-1">Created At</label>
                        <p class="text-gray-900">{{ $supplyRequest->created_at->format('M d, Y H:i') }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500 mb-1">Last Updated</label>
                        <p class="text-gray-900">{{ $supplyRequest->updated_at->format('M d, Y H:i') }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500 mb-1">ID</label>
                        <p class="text-gray-900 font-mono text-sm">#{{ $supplyRequest->id }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
