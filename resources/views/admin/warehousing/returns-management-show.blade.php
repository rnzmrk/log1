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
                    <a href="#" class="text-gray-700 hover:text-blue-600 ml-1 md:ml-2">Smart Warehousing</a>
                </div>
            </li>
            <li>
                <div class="flex items-center">
                    <i class='bx bx-chevron-right text-gray-400'></i>
                    <a href="{{ route('admin.warehousing.returns-management') }}" class="text-gray-700 hover:text-blue-600 ml-1 md:ml-2">Returns Management</a>
                </div>
            </li>
            <li aria-current="page">
                <div class="flex items-center">
                    <i class='bx bx-chevron-right text-gray-400'></i>
                    <span class="ml-1 text-gray-500 md:ml-2">View Return</span>
                </div>
            </li>
        </ol>
    </nav>

    <!-- Page Header -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">{{ $returnRefund->return_id }}</h1>
            <p class="text-gray-600 mt-1">Order: {{ $returnRefund->order_number }}</p>
        </div>
        <div class="flex gap-3">
            <a href="{{ route('returns-management.edit', $returnRefund->id) }}" class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg flex items-center gap-2 transition-colors">
                <i class='bx bx-edit text-xl'></i>
                Edit
            </a>
            <a href="{{ route('admin.warehousing.returns-management') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-medium py-2 px-4 rounded-lg flex items-center gap-2 transition-colors">
                <i class='bx bx-arrow-back text-xl'></i>
                Back to Returns
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
            <!-- Return Details Card -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-900">Return Details</h2>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">Return ID</label>
                            <p class="text-gray-900 font-medium">{{ $returnRefund->return_id }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">Order Number</label>
                            <p class="text-gray-900 font-medium">{{ $returnRefund->order_number }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">Customer Name</label>
                            <p class="text-gray-900">{{ $returnRefund->customer_name }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">Product Name</label>
                            <p class="text-gray-900">{{ $returnRefund->product_name }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">SKU</label>
                            <p class="text-gray-900 font-mono">{{ $returnRefund->sku }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">Quantity</label>
                            <p class="text-gray-900">{{ $returnRefund->quantity }}</p>
                        </div>
                        @if ($returnRefund->refund_method)
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">Refund Method</label>
                            <p class="text-gray-900">{{ $returnRefund->refund_method }}</p>
                        </div>
                        @endif
                        @if ($returnRefund->tracking_number)
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">Tracking Number</label>
                            <p class="text-gray-900 font-mono">{{ $returnRefund->tracking_number }}</p>
                        </div>
                        @endif
                    </div>
                    
                    @if ($returnRefund->notes)
                    <div class="mt-6">
                        <label class="block text-sm font-medium text-gray-500 mb-1">Notes</label>
                        <p class="text-gray-900">{{ $returnRefund->notes }}</p>
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
                            <div class="text-3xl font-bold text-gray-900">${{ number_format($returnRefund->refund_amount, 2) }}</div>
                            <div class="text-sm text-gray-500 mt-1">Refund Amount</div>
                        </div>
                        <div class="text-center">
                            <div class="text-3xl font-bold text-gray-900">{{ $returnRefund->quantity }}</div>
                            <div class="text-sm text-gray-500 mt-1">Items Returned</div>
                        </div>
                        <div class="text-center">
                            @if ($returnRefund->status === 'Pending')
                                <span class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full bg-orange-100 text-orange-800">
                                    Pending
                                </span>
                            @elseif ($returnRefund->status === 'Approved')
                                <span class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                    Approved
                                </span>
                            @elseif ($returnRefund->status === 'Rejected')
                                <span class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                    Rejected
                                </span>
                            @elseif ($returnRefund->status === 'Processed')
                                <span class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full bg-purple-100 text-purple-800">
                                    Processed
                                </span>
                            @else
                                <span class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                    Refunded
                                </span>
                            @endif
                            <div class="text-sm text-gray-500 mt-1">Current Status</div>
                        </div>
                    </div>
                    
                    <!-- Return Reason Indicator -->
                    <div class="mt-6">
                        <div class="flex justify-between text-sm text-gray-600 mb-2">
                            <span>Return Reason</span>
                            <span>{{ $returnRefund->return_reason }}</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-3">
                            @if ($returnRefund->return_reason === 'Defective')
                                <div class="bg-red-500 h-3 rounded-full" style="width: 100%"></div>
                            @elseif ($returnRefund->return_reason === 'Wrong Item')
                                <div class="bg-orange-500 h-3 rounded-full" style="width: 80%"></div>
                            @elseif ($returnRefund->return_reason === 'Damaged')
                                <div class="bg-yellow-500 h-3 rounded-full" style="width: 60%"></div>
                            @elseif ($returnRefund->return_reason === 'Not Satisfied')
                                <div class="bg-blue-500 h-3 rounded-full" style="width: 40%"></div>
                            @else
                                <div class="bg-gray-500 h-3 rounded-full" style="width: 20%"></div>
                            @endif
                        </div>
                        <div class="mt-2 text-xs text-gray-500">
                            @if ($returnRefund->return_reason === 'Defective')
                                High Priority - Product defect investigation required
                            @elseif ($returnRefund->return_reason === 'Wrong Item')
                                Medium Priority - Shipping error investigation
                            @elseif ($returnRefund->return_reason === 'Damaged')
                                Medium Priority - Damage assessment needed
                            @elseif ($returnRefund->return_reason === 'Not Satisfied')
                                Low Priority - Customer satisfaction review
                            @else
                                Low Priority - Custom review required
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Timeline Card -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-900">Timeline</h2>
                </div>
                <div class="p-6">
                    <div class="space-y-4">
                        <div class="flex items-start gap-3">
                            <div class="flex-shrink-0 w-2 h-2 bg-green-500 rounded-full mt-2"></div>
                            <div>
                                <p class="text-sm font-medium text-gray-900">Return Initiated</p>
                                <p class="text-sm text-gray-600">{{ $returnRefund->return_date->format('M d, Y') }}</p>
                            </div>
                        </div>
                        
                        @if ($returnRefund->refund_date)
                        <div class="flex items-start gap-3">
                            <div class="flex-shrink-0 w-2 h-2 bg-green-500 rounded-full mt-2"></div>
                            <div>
                                <p class="text-sm font-medium text-gray-900">Refund Processed</p>
                                <p class="text-sm text-gray-600">{{ $returnRefund->refund_date->format('M d, Y') }}</p>
                            </div>
                        </div>
                        @endif
                        
                        <div class="flex items-start gap-3">
                            <div class="flex-shrink-0 w-2 h-2 bg-gray-500 rounded-full mt-2"></div>
                            <div>
                                <p class="text-sm font-medium text-gray-900">Last Updated</p>
                                <p class="text-sm text-gray-600">{{ $returnRefund->updated_at->format('M d, Y H:i') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Actions Sidebar -->
        <div class="space-y-6">
            <!-- Quick Actions -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-900">Quick Actions</h2>
                </div>
                <div class="p-6 space-y-3">
                    <a href="{{ route('returns-management.edit', $returnRefund->id) }}" class="w-full text-left px-4 py-3 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors flex items-center gap-3">
                        <i class='bx bx-edit text-blue-600 text-xl'></i>
                        <div>
                            <p class="font-medium text-gray-900">Edit Return</p>
                            <p class="text-sm text-gray-600">Update return details</p>
                        </div>
                    </a>
                    
                    @if ($returnRefund->tracking_number)
                    <a href="https://www.google.com/search?q={{ $returnRefund->tracking_number }}" target="_blank" class="w-full text-left px-4 py-3 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors flex items-center gap-3">
                        <i class='bx bx-map-pin text-green-600 text-xl'></i>
                        <div>
                            <p class="font-medium text-gray-900">Track Return</p>
                            <p class="text-sm text-gray-600">Track with carrier</p>
                        </div>
                    </a>
                    @endif
                    
                    <form action="{{ route('returns-management.destroy', $returnRefund->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this return? This action cannot be undone.')" class="w-full">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="w-full text-left px-4 py-3 border border-red-200 rounded-lg hover:bg-red-50 transition-colors flex items-center gap-3">
                            <i class='bx bx-trash text-red-600 text-xl'></i>
                            <div>
                                <p class="font-medium text-red-900">Delete Return</p>
                                <p class="text-sm text-red-600">Remove from system</p>
                            </div>
                        </button>
                    </form>
                </div>
            </div>

            <!-- System Information -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-900">System Information</h2>
                </div>
                <div class="p-6 space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-500 mb-1">Created At</label>
                        <p class="text-gray-900">{{ $returnRefund->created_at->format('M d, Y H:i') }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500 mb-1">Last Updated</label>
                        <p class="text-gray-900">{{ $returnRefund->updated_at->format('M d, Y H:i') }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500 mb-1">ID</label>
                        <p class="text-gray-900 font-mono text-sm">#{{ $returnRefund->id }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
