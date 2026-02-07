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
                    <span class="ml-1 text-gray-500 md:ml-2">Edit Request</span>
                </div>
            </li>
        </ol>
    </nav>

    <!-- Page Header -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Edit Supply Request</h1>
            <p class="text-gray-600 mt-1">Request ID: {{ $supplyRequest->request_id }}</p>
        </div>
        <div class="flex gap-3">
            <a href="{{ route('supply-requests.show', $supplyRequest->id) }}" class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-medium py-2 px-4 rounded-lg flex items-center gap-2 transition-colors">
                <i class='bx bx-show text-xl'></i>
                View
            </a>
            <a href="{{ route('admin.procurement.request-supplies') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-medium py-2 px-4 rounded-lg flex items-center gap-2 transition-colors">
                <i class='bx bx-arrow-back text-xl'></i>
                Back to Requests
            </a>
        </div>
    </div>

    <!-- Form Container -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-lg font-semibold text-gray-900">Edit Request Information</h2>
        </div>
        
        <form method="POST" action="{{ route('supply-requests.update', $supplyRequest->id) }}" class="p-6">
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
                <!-- Request ID -->
                <div>
                    <label for="request_id" class="block text-sm font-medium text-gray-700 mb-2">Request ID *</label>
                    <input type="text" 
                           id="request_id" 
                           name="request_id" 
                           value="{{ old('request_id', $supplyRequest->request_id) }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                           placeholder="e.g., REQ-2026-0001"
                           required>
                    <p class="mt-1 text-sm text-gray-500">Unique request identifier</p>
                </div>

                <!-- Item Name -->
                <div>
                    <label for="item_name" class="block text-sm font-medium text-gray-700 mb-2">Item Name *</label>
                    <input type="text" 
                           id="item_name" 
                           name="item_name" 
                           value="{{ old('item_name', $supplyRequest->item_name) }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                           placeholder="e.g., Laptop Computer"
                           required>
                </div>

                <!-- Category -->
                <div>
                    <label for="category" class="block text-sm font-medium text-gray-700 mb-2">Category *</label>
                    <select id="category" 
                            name="category" 
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            required>
                        <option value="">Select category</option>
                        <option value="Office Supplies" {{ old('category', $supplyRequest->category) === 'Office Supplies' ? 'selected' : '' }}>Office Supplies</option>
                        <option value="IT Equipment" {{ old('category', $supplyRequest->category) === 'IT Equipment' ? 'selected' : '' }}>IT Equipment</option>
                        <option value="Furniture" {{ old('category', $supplyRequest->category) === 'Furniture' ? 'selected' : '' }}>Furniture</option>
                        <option value="Safety Equipment" {{ old('category', $supplyRequest->category) === 'Safety Equipment' ? 'selected' : '' }}>Safety Equipment</option>
                    </select>
                </div>

                <!-- Supplier -->
                <div>
                    <label for="supplier" class="block text-sm font-medium text-gray-700 mb-2">Supplier *</label>
                    <input type="text" 
                           id="supplier" 
                           name="supplier" 
                           value="{{ old('supplier', $supplyRequest->supplier) }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                           placeholder="e.g., Tech Solutions Inc."
                           required>
                </div>

                <!-- Quantity Requested -->
                <div>
                    <label for="quantity_requested" class="block text-sm font-medium text-gray-700 mb-2">Quantity Requested *</label>
                    <input type="number" 
                           id="quantity_requested" 
                           name="quantity_requested" 
                           value="{{ old('quantity_requested', $supplyRequest->quantity_requested) }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                           placeholder="1"
                           min="1"
                           required>
                </div>

                <!-- Quantity Approved -->
                <div>
                    <label for="quantity_approved" class="block text-sm font-medium text-gray-700 mb-2">Quantity Approved</label>
                    <input type="number" 
                           id="quantity_approved" 
                           name="quantity_approved" 
                           value="{{ old('quantity_approved', $supplyRequest->quantity_approved) }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                           placeholder="Leave blank if not approved"
                           min="1">
                    <p class="mt-1 text-sm text-gray-500">Optional: Fill when request is approved</p>
                </div>

                <!-- Unit Price -->
                <div>
                    <label for="unit_price" class="block text-sm font-medium text-gray-700 mb-2">Unit Price</label>
                    <input type="number" 
                           id="unit_price" 
                           name="unit_price" 
                           value="{{ old('unit_price', $supplyRequest->unit_price) }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                           placeholder="0.00"
                           step="0.01"
                           min="0">
                    <p class="mt-1 text-sm text-gray-500">Optional: Price per unit</p>
                </div>

                <!-- Total Cost Display -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Total Cost</label>
                    <div class="w-full px-3 py-2 border border-gray-300 rounded-lg bg-gray-50">
                        <span class="text-gray-900 font-medium">
                            @if ($supplyRequest->total_cost)
                                ${{ number_format($supplyRequest->total_cost, 2) }}
                            @else
                                $0.00
                            @endif
                        </span>
                    </div>
                    <p class="mt-1 text-sm text-gray-500">Auto-calculated from unit price × approved quantity</p>
                </div>

                <!-- Priority -->
                <div>
                    <label for="priority" class="block text-sm font-medium text-gray-700 mb-2">Priority *</label>
                    <select id="priority" 
                            name="priority" 
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            required>
                        <option value="">Select priority</option>
                        <option value="Low" {{ old('priority', $supplyRequest->priority) === 'Low' ? 'selected' : '' }}>Low</option>
                        <option value="Medium" {{ old('priority', $supplyRequest->priority) === 'Medium' ? 'selected' : '' }}>Medium</option>
                        <option value="High" {{ old('priority', $supplyRequest->priority) === 'High' ? 'selected' : '' }}>High</option>
                        <option value="Urgent" {{ old('priority', $supplyRequest->priority) === 'Urgent' ? 'selected' : '' }}>Urgent</option>
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
                        <option value="Pending" {{ old('status', $supplyRequest->status) === 'Pending' ? 'selected' : '' }}>Pending</option>
                        <option value="Approved" {{ old('status', $supplyRequest->status) === 'Approved' ? 'selected' : '' }}>Approved</option>
                        <option value="Rejected" {{ old('status', $supplyRequest->status) === 'Rejected' ? 'selected' : '' }}>Rejected</option>
                        <option value="Ordered" {{ old('status', $supplyRequest->status) === 'Ordered' ? 'selected' : '' }}>Ordered</option>
                        <option value="Received" {{ old('status', $supplyRequest->status) === 'Received' ? 'selected' : '' }}>Received</option>
                    </select>
                </div>

                <!-- Request Date -->
                <div>
                    <label for="request_date" class="block text-sm font-medium text-gray-700 mb-2">Request Date *</label>
                    <input type="date" 
                           id="request_date" 
                           name="request_date" 
                           value="{{ old('request_date', $supplyRequest->request_date ? $supplyRequest->request_date->format('Y-m-d') : '') }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                           required>
                </div>

                <!-- Needed By Date -->
                <div>
                    <label for="needed_by_date" class="block text-sm font-medium text-gray-700 mb-2">Needed By Date *</label>
                    <input type="date" 
                           id="needed_by_date" 
                           name="needed_by_date" 
                           value="{{ old('needed_by_date', $supplyRequest->needed_by_date ? $supplyRequest->needed_by_date->format('Y-m-d') : '') }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                           required>
                </div>

                <!-- Approval Date -->
                <div>
                    <label for="approval_date" class="block text-sm font-medium text-gray-700 mb-2">Approval Date</label>
                    <input type="date" 
                           id="approval_date" 
                           name="approval_date" 
                           value="{{ old('approval_date', $supplyRequest->approval_date ? $supplyRequest->approval_date->format('Y-m-d') : '') }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <p class="mt-1 text-sm text-gray-500">Optional: Auto-filled when approved</p>
                </div>

                <!-- Order Date -->
                <div>
                    <label for="order_date" class="block text-sm font-medium text-gray-700 mb-2">Order Date</label>
                    <input type="date" 
                           id="order_date" 
                           name="order_date" 
                           value="{{ old('order_date', $supplyRequest->order_date ? $supplyRequest->order_date->format('Y-m-d') : '') }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <p class="mt-1 text-sm text-gray-500">Optional: Auto-filled when ordered</p>
                </div>

                <!-- Expected Delivery -->
                <div>
                    <label for="expected_delivery" class="block text-sm font-medium text-gray-700 mb-2">Expected Delivery</label>
                    <input type="date" 
                           id="expected_delivery" 
                           name="expected_delivery" 
                           value="{{ old('expected_delivery', $supplyRequest->expected_delivery ? $supplyRequest->expected_delivery->format('Y-m-d') : '') }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <p class="mt-1 text-sm text-gray-500">Optional: Estimated delivery date</p>
                </div>

                <!-- Requested By -->
                <div>
                    <label for="requested_by" class="block text-sm font-medium text-gray-700 mb-2">Requested By *</label>
                    <input type="text" 
                           id="requested_by" 
                           name="requested_by" 
                           value="{{ old('requested_by', $supplyRequest->requested_by) }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                           placeholder="e.g., John Smith"
                           required>
                </div>

                <!-- Approved By -->
                <div>
                    <label for="approved_by" class="block text-sm font-medium text-gray-700 mb-2">Approved By</label>
                    <input type="text" 
                           id="approved_by" 
                           name="approved_by" 
                           value="{{ old('approved_by', $supplyRequest->approved_by) }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                           placeholder="e.g., Jane Manager">
                    <p class="mt-1 text-sm text-gray-500">Optional: Name of approving manager</p>
                </div>
            </div>

            <!-- Notes -->
            <div class="mt-6">
                <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">Notes</label>
                <textarea id="notes" 
                          name="notes" 
                          rows="4"
                          class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                          placeholder="Enter additional notes about this request...">{{ old('notes', $supplyRequest->notes) }}</textarea>
                <p class="mt-1 text-sm text-gray-500">Optional: Additional information about the request</p>
            </div>

            <!-- Current Status Info -->
            <div class="mt-6 p-4 bg-gray-50 rounded-lg">
                <h3 class="text-sm font-medium text-gray-700 mb-2">Current Status</h3>
                <div class="flex items-center gap-4">
                    <span class="text-sm text-gray-600">Status:</span>
                    @if ($supplyRequest->status === 'Pending')
                        <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-orange-100 text-orange-800">
                            Pending
                        </span>
                    @elseif ($supplyRequest->status === 'Approved')
                        <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                            Approved
                        </span>
                    @elseif ($supplyRequest->status === 'Rejected')
                        <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                            Rejected
                        </span>
                    @elseif ($supplyRequest->status === 'Ordered')
                        <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-purple-100 text-purple-800">
                            Ordered
                        </span>
                    @else
                        <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                            Received
                        </span>
                    @endif
                    <span class="text-sm text-gray-600">| Created: {{ $supplyRequest->created_at->format('M d, Y') }}</span>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="flex justify-end gap-3 mt-8">
                <a href="{{ route('admin.procurement.request-supplies') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-medium py-2 px-6 rounded-lg transition-colors">
                    Cancel
                </a>
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-6 rounded-lg transition-colors">
                    <i class='bx bx-save mr-2'></i>
                    Update Request
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
