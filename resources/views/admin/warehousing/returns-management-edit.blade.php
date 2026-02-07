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
                    <span class="ml-1 text-gray-500 md:ml-2">Edit Return</span>
                </div>
            </li>
        </ol>
    </nav>

    <!-- Page Header -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Edit Return</h1>
            <p class="text-gray-600 mt-1">Return ID: {{ $returnRefund->return_id }}</p>
        </div>
        <div class="flex gap-3">
            <a href="{{ route('returns-management.show', $returnRefund->id) }}" class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-medium py-2 px-4 rounded-lg flex items-center gap-2 transition-colors">
                <i class='bx bx-show text-xl'></i>
                View
            </a>
            <a href="{{ route('admin.warehousing.returns-management') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-medium py-2 px-4 rounded-lg flex items-center gap-2 transition-colors">
                <i class='bx bx-arrow-back text-xl'></i>
                Back to Returns
            </a>
        </div>
    </div>

    <!-- Form Container -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-lg font-semibold text-gray-900">Edit Return Information</h2>
        </div>
        
        <form method="POST" action="{{ route('returns-management.update', $returnRefund->id) }}" class="p-6">
            @csrf
            @method('PUT')
            
            <!-- Error Messages -->
            @if ($errors->any())
                <div class="mb-4 bg-red-50 border border-red-200 rounded-md p-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <i class='bx bx-error text-red-400 text-xl'></i>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-red-800">There were errors with your submission:</h3>
                            <div class="mt-2 text-sm text-red-700">
                                <ul class="list-disc list-inside space-y-1">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Return ID -->
                <div>
                    <label for="return_id" class="block text-sm font-medium text-gray-700 mb-2">Return ID *</label>
                    <input type="text" 
                           id="return_id" 
                           name="return_id" 
                           value="{{ old('return_id', $returnRefund->return_id) }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                           placeholder="e.g., RET-2024-001"
                           required>
                    <p class="mt-1 text-sm text-gray-500">Unique return identifier</p>
                </div>

                <!-- Order Number -->
                <div>
                    <label for="order_number" class="block text-sm font-medium text-gray-700 mb-2">Order Number *</label>
                    <input type="text" 
                           id="order_number" 
                           name="order_number" 
                           value="{{ old('order_number', $returnRefund->order_number) }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                           placeholder="e.g., ORD-2024-001"
                           required>
                </div>

                <!-- Customer Name -->
                <div>
                    <label for="customer_name" class="block text-sm font-medium text-gray-700 mb-2">Customer Name *</label>
                    <input type="text" 
                           id="customer_name" 
                           name="customer_name" 
                           value="{{ old('customer_name', $returnRefund->customer_name) }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                           placeholder="e.g., John Smith"
                           required>
                </div>

                <!-- Product Name -->
                <div>
                    <label for="product_name" class="block text-sm font-medium text-gray-700 mb-2">Product Name *</label>
                    <input type="text" 
                           id="product_name" 
                           name="product_name" 
                           value="{{ old('product_name', $returnRefund->product_name) }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                           placeholder="e.g., Wireless Headphones"
                           required>
                </div>

                <!-- SKU -->
                <div>
                    <label for="sku" class="block text-sm font-medium text-gray-700 mb-2">SKU *</label>
                    <input type="text" 
                           id="sku" 
                           name="sku" 
                           value="{{ old('sku', $returnRefund->sku) }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                           placeholder="e.g., WH-001-BLK"
                           required>
                </div>

                <!-- Quantity -->
                <div>
                    <label for="quantity" class="block text-sm font-medium text-gray-700 mb-2">Quantity *</label>
                    <input type="number" 
                           id="quantity" 
                           name="quantity" 
                           value="{{ old('quantity', $returnRefund->quantity) }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                           placeholder="1"
                           min="1"
                           required>
                </div>

                <!-- Refund Amount -->
                <div>
                    <label for="refund_amount" class="block text-sm font-medium text-gray-700 mb-2">Refund Amount *</label>
                    <input type="number" 
                           id="refund_amount" 
                           name="refund_amount" 
                           value="{{ old('refund_amount', $returnRefund->refund_amount) }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                           placeholder="0.00"
                           step="0.01"
                           min="0"
                           required>
                </div>

                <!-- Return Reason -->
                <div>
                    <label for="return_reason" class="block text-sm font-medium text-gray-700 mb-2">Return Reason *</label>
                    <select id="return_reason" 
                            name="return_reason" 
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            required>
                        <option value="">Select reason</option>
                        <option value="Defective" {{ old('return_reason', $returnRefund->return_reason) === 'Defective' ? 'selected' : '' }}>Defective</option>
                        <option value="Wrong Item" {{ old('return_reason', $returnRefund->return_reason) === 'Wrong Item' ? 'selected' : '' }}>Wrong Item</option>
                        <option value="Damaged" {{ old('return_reason', $returnRefund->return_reason) === 'Damaged' ? 'selected' : '' }}>Damaged</option>
                        <option value="Not Satisfied" {{ old('return_reason', $returnRefund->return_reason) === 'Not Satisfied' ? 'selected' : '' }}>Not Satisfied</option>
                        <option value="Other" {{ old('return_reason', $returnRefund->return_reason) === 'Other' ? 'selected' : '' }}>Other</option>
                    </select>
                </div>

                <!-- Status -->
                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Status *</label>
                    <select id="status" 
                            name="status" 
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            required>
                        <option value="">Select status</option>
                        <option value="Pending" {{ old('status', $returnRefund->status) === 'Pending' ? 'selected' : '' }}>Pending</option>
                        <option value="Approved" {{ old('status', $returnRefund->status) === 'Approved' ? 'selected' : '' }}>Approved</option>
                        <option value="Rejected" {{ old('status', $returnRefund->status) === 'Rejected' ? 'selected' : '' }}>Rejected</option>
                        <option value="Processed" {{ old('status', $returnRefund->status) === 'Processed' ? 'selected' : '' }}>Processed</option>
                        <option value="Refunded" {{ old('status', $returnRefund->status) === 'Refunded' ? 'selected' : '' }}>Refunded</option>
                    </select>
                </div>

                <!-- Return Date -->
                <div>
                    <label for="return_date" class="block text-sm font-medium text-gray-700 mb-2">Return Date *</label>
                    <input type="date" 
                           id="return_date" 
                           name="return_date" 
                           value="{{ old('return_date', $returnRefund->return_date ? $returnRefund->return_date->format('Y-m-d') : '') }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                           required>
                </div>

                <!-- Refund Date -->
                <div>
                    <label for="refund_date" class="block text-sm font-medium text-gray-700 mb-2">Refund Date</label>
                    <input type="date" 
                           id="refund_date" 
                           name="refund_date" 
                           value="{{ old('refund_date', $returnRefund->refund_date ? $returnRefund->refund_date->format('Y-m-d') : '') }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <p class="mt-1 text-sm text-gray-500">Optional: Date refund was processed</p>
                </div>

                <!-- Refund Method -->
                <div>
                    <label for="refund_method" class="block text-sm font-medium text-gray-700 mb-2">Refund Method</label>
                    <select id="refund_method" 
                            name="refund_method" 
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="">Select method</option>
                        <option value="Bank Transfer" {{ old('refund_method', $returnRefund->refund_method) === 'Bank Transfer' ? 'selected' : '' }}>Bank Transfer</option>
                        <option value="Credit Card" {{ old('refund_method', $returnRefund->refund_method) === 'Credit Card' ? 'selected' : '' }}>Credit Card</option>
                        <option value="Store Credit" {{ old('refund_method', $returnRefund->refund_method) === 'Store Credit' ? 'selected' : '' }}>Store Credit</option>
                        <option value="Cash" {{ old('refund_method', $returnRefund->refund_method) === 'Cash' ? 'selected' : '' }}>Cash</option>
                    </select>
                    <p class="mt-1 text-sm text-gray-500">Optional: How the refund was processed</p>
                </div>

                <!-- Tracking Number -->
                <div>
                    <label for="tracking_number" class="block text-sm font-medium text-gray-700 mb-2">Tracking Number</label>
                    <input type="text" 
                           id="tracking_number" 
                           name="tracking_number" 
                           value="{{ old('tracking_number', $returnRefund->tracking_number) }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                           placeholder="e.g., 1234567890">
                    <p class="mt-1 text-sm text-gray-500">Optional: Return shipment tracking</p>
                </div>
            </div>

            <!-- Notes -->
            <div class="mt-6">
                <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">Notes</label>
                <textarea id="notes" 
                          name="notes" 
                          rows="4"
                          class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                          placeholder="Enter return notes...">{{ old('notes', $returnRefund->notes) }}</textarea>
                <p class="mt-1 text-sm text-gray-500">Optional: Additional notes about the return</p>
            </div>

            <!-- Current Status Info -->
            <div class="mt-6 p-4 bg-gray-50 rounded-lg">
                <h3 class="text-sm font-medium text-gray-700 mb-2">Current Status</h3>
                <div class="flex items-center gap-4">
                    <span class="text-sm text-gray-600">Status:</span>
                    @if ($returnRefund->status === 'Pending')
                        <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-orange-100 text-orange-800">
                            Pending
                        </span>
                    @elseif ($returnRefund->status === 'Approved')
                        <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                            Approved
                        </span>
                    @elseif ($returnRefund->status === 'Rejected')
                        <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                            Rejected
                        </span>
                    @elseif ($returnRefund->status === 'Processed')
                        <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-purple-100 text-purple-800">
                            Processed
                        </span>
                    @else
                        <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                            Refunded
                        </span>
                    @endif
                    <span class="text-sm text-gray-600">| Created: {{ $returnRefund->created_at->format('M d, Y') }}</span>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="flex justify-end gap-3 mt-8">
                <a href="{{ route('admin.warehousing.returns-management') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-medium py-2 px-6 rounded-lg transition-colors">
                    Cancel
                </a>
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-6 rounded-lg transition-colors">
                    <i class='bx bx-save mr-2'></i>
                    Update Return
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
