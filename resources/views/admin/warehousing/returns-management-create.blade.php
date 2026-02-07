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
                    <span class="ml-1 text-gray-500 md:ml-2">New Return</span>
                </div>
            </li>
        </ol>
    </nav>

    <!-- Page Header -->
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-900">Create New Return</h1>
        <a href="{{ route('admin.warehousing.returns-management') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-medium py-2 px-4 rounded-lg flex items-center gap-2 transition-colors">
            <i class='bx bx-arrow-back text-xl'></i>
            Back to Returns
        </a>
    </div>

    <!-- Form Container -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-lg font-semibold text-gray-900">Return Information</h2>
        </div>
        
        <form method="POST" action="{{ route('returns-management.store') }}" class="p-6">
            @csrf
            
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
                <!-- Order Number -->
                <div>
                    <label for="order_number" class="block text-sm font-medium text-gray-700 mb-2">Order Number *</label>
                    <input type="text" 
                           id="order_number" 
                           name="order_number" 
                           value="{{ old('order_number') }}"
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
                           value="{{ old('customer_name') }}"
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
                           value="{{ old('product_name') }}"
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
                           value="{{ old('sku') }}"
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
                           value="{{ old('quantity') }}"
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
                           value="{{ old('refund_amount') }}"
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
                        <option value="Defective" {{ old('return_reason') === 'Defective' ? 'selected' : '' }}>Defective</option>
                        <option value="Wrong Item" {{ old('return_reason') === 'Wrong Item' ? 'selected' : '' }}>Wrong Item</option>
                        <option value="Damaged" {{ old('return_reason') === 'Damaged' ? 'selected' : '' }}>Damaged</option>
                        <option value="Not Satisfied" {{ old('return_reason') === 'Not Satisfied' ? 'selected' : '' }}>Not Satisfied</option>
                        <option value="Other" {{ old('return_reason') === 'Other' ? 'selected' : '' }}>Other</option>
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
                        <option value="Pending" {{ old('status') === 'Pending' ? 'selected' : '' }}>Pending</option>
                        <option value="Approved" {{ old('status') === 'Approved' ? 'selected' : '' }}>Approved</option>
                        <option value="Rejected" {{ old('status') === 'Rejected' ? 'selected' : '' }}>Rejected</option>
                        <option value="Processed" {{ old('status') === 'Processed' ? 'selected' : '' }}>Processed</option>
                        <option value="Refunded" {{ old('status') === 'Refunded' ? 'selected' : '' }}>Refunded</option>
                    </select>
                </div>

                <!-- Return Date -->
                <div>
                    <label for="return_date" class="block text-sm font-medium text-gray-700 mb-2">Return Date *</label>
                    <input type="date" 
                           id="return_date" 
                           name="return_date" 
                           value="{{ old('return_date') }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                           required>
                </div>

                <!-- Refund Date -->
                <div>
                    <label for="refund_date" class="block text-sm font-medium text-gray-700 mb-2">Refund Date</label>
                    <input type="date" 
                           id="refund_date" 
                           name="refund_date" 
                           value="{{ old('refund_date') }}"
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
                        <option value="Bank Transfer" {{ old('refund_method') === 'Bank Transfer' ? 'selected' : '' }}>Bank Transfer</option>
                        <option value="Credit Card" {{ old('refund_method') === 'Credit Card' ? 'selected' : '' }}>Credit Card</option>
                        <option value="Store Credit" {{ old('refund_method') === 'Store Credit' ? 'selected' : '' }}>Store Credit</option>
                        <option value="Cash" {{ old('refund_method') === 'Cash' ? 'selected' : '' }}>Cash</option>
                    </select>
                    <p class="mt-1 text-sm text-gray-500">Optional: How the refund was processed</p>
                </div>

                <!-- Tracking Number -->
                <div>
                    <label for="tracking_number" class="block text-sm font-medium text-gray-700 mb-2">Tracking Number</label>
                    <input type="text" 
                           id="tracking_number" 
                           name="tracking_number" 
                           value="{{ old('tracking_number') }}"
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
                          placeholder="Enter return notes...">{{ old('notes') }}</textarea>
                <p class="mt-1 text-sm text-gray-500">Optional: Additional notes about the return</p>
            </div>

            <!-- Form Actions -->
            <div class="flex justify-end gap-3 mt-8">
                <a href="{{ route('admin.warehousing.returns-management') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-medium py-2 px-6 rounded-lg transition-colors">
                    Cancel
                </a>
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-6 rounded-lg transition-colors">
                    <i class='bx bx-save mr-2'></i>
                    Create Return
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
