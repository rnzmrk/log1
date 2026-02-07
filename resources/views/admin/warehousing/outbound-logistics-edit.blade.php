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
                    <a href="{{ route('admin.warehousing.outbound-logistics') }}" class="text-gray-700 hover:text-blue-600 ml-1 md:ml-2">Outbound Logistics</a>
                </div>
            </li>
            <li aria-current="page">
                <div class="flex items-center">
                    <i class='bx bx-chevron-right text-gray-400'></i>
                    <span class="ml-1 text-gray-500 md:ml-2">Edit Shipment</span>
                </div>
            </li>
        </ol>
    </nav>

    <!-- Page Header -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Edit Shipment</h1>
            <p class="text-gray-600 mt-1">Shipment ID: {{ $outboundLogistic->shipment_id }}</p>
        </div>
        <div class="flex gap-3">
            <a href="{{ route('outbound-logistics.show', $outboundLogistic->id) }}" class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-medium py-2 px-4 rounded-lg flex items-center gap-2 transition-colors">
                <i class='bx bx-show text-xl'></i>
                View
            </a>
            <a href="{{ route('admin.warehousing.outbound-logistics') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-medium py-2 px-4 rounded-lg flex items-center gap-2 transition-colors">
                <i class='bx bx-arrow-back text-xl'></i>
                Back to Shipments
            </a>
        </div>
    </div>

    <!-- Form Container -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-lg font-semibold text-gray-900">Edit Shipment Information</h2>
        </div>
        
        <form method="POST" action="{{ route('outbound-logistics.update', $outboundLogistic->id) }}" class="p-6">
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
                <!-- Shipment ID -->
                <div>
                    <label for="shipment_id" class="block text-sm font-medium text-gray-700 mb-2">Shipment ID *</label>
                    <input type="text" 
                           id="shipment_id" 
                           name="shipment_id" 
                           value="{{ old('shipment_id', $outboundLogistic->shipment_id) }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                           placeholder="e.g., SHIP-2024-001"
                           required>
                    <p class="mt-1 text-sm text-gray-500">Unique shipment identifier</p>
                </div>

                <!-- Order Number -->
                <div>
                    <label for="order_number" class="block text-sm font-medium text-gray-700 mb-2">Order Number *</label>
                    <input type="text" 
                           id="order_number" 
                           name="order_number" 
                           value="{{ old('order_number', $outboundLogistic->order_number) }}"
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
                           value="{{ old('customer_name', $outboundLogistic->customer_name) }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                           placeholder="e.g., Acme Corporation"
                           required>
                </div>

                <!-- Destination -->
                <div>
                    <label for="destination" class="block text-sm font-medium text-gray-700 mb-2">Destination *</label>
                    <input type="text" 
                           id="destination" 
                           name="destination" 
                           value="{{ old('destination', $outboundLogistic->destination) }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                           placeholder="e.g., New York, NY"
                           required>
                </div>

                <!-- Total Units -->
                <div>
                    <label for="total_units" class="block text-sm font-medium text-gray-700 mb-2">Total Units *</label>
                    <input type="number" 
                           id="total_units" 
                           name="total_units" 
                           value="{{ old('total_units', $outboundLogistic->total_units) }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                           placeholder="0"
                           min="1"
                           required>
                </div>

                <!-- Shipped Units -->
                <div>
                    <label for="shipped_units" class="block text-sm font-medium text-gray-700 mb-2">Shipped Units</label>
                    <input type="number" 
                           id="shipped_units" 
                           name="shipped_units" 
                           value="{{ old('shipped_units', $outboundLogistic->shipped_units) }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                           placeholder="0"
                           min="0">
                    <p class="mt-1 text-sm text-gray-500">Number of units already shipped</p>
                </div>

                <!-- Priority -->
                <div>
                    <label for="priority" class="block text-sm font-medium text-gray-700 mb-2">Priority *</label>
                    <select id="priority" 
                            name="priority" 
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            required>
                        <option value="">Select priority</option>
                        <option value="Low" {{ old('priority', $outboundLogistic->priority) === 'Low' ? 'selected' : '' }}>Low</option>
                        <option value="Medium" {{ old('priority', $outboundLogistic->priority) === 'Medium' ? 'selected' : '' }}>Medium</option>
                        <option value="High" {{ old('priority', $outboundLogistic->priority) === 'High' ? 'selected' : '' }}>High</option>
                        <option value="Urgent" {{ old('priority', $outboundLogistic->priority) === 'Urgent' ? 'selected' : '' }}>Urgent</option>
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
                        <option value="Pending" {{ old('status', $outboundLogistic->status) === 'Pending' ? 'selected' : '' }}>Pending</option>
                        <option value="Processing" {{ old('status', $outboundLogistic->status) === 'Processing' ? 'selected' : '' }}>Processing</option>
                        <option value="Shipped" {{ old('status', $outboundLogistic->status) === 'Shipped' ? 'selected' : '' }}>Shipped</option>
                        <option value="Delivered" {{ old('status', $outboundLogistic->status) === 'Delivered' ? 'selected' : '' }}>Delivered</option>
                        <option value="Cancelled" {{ old('status', $outboundLogistic->status) === 'Cancelled' ? 'selected' : '' }}>Cancelled</option>
                    </select>
                </div>

                <!-- Total Value -->
                <div>
                    <label for="total_value" class="block text-sm font-medium text-gray-700 mb-2">Total Value</label>
                    <input type="number" 
                           id="total_value" 
                           name="total_value" 
                           value="{{ old('total_value', $outboundLogistic->total_value) }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                           placeholder="0.00"
                           step="0.01"
                           min="0">
                    <p class="mt-1 text-sm text-gray-500">Optional: Total shipment value</p>
                </div>

                <!-- Carrier -->
                <div>
                    <label for="carrier" class="block text-sm font-medium text-gray-700 mb-2">Carrier</label>
                    <input type="text" 
                           id="carrier" 
                           name="carrier" 
                           value="{{ old('carrier', $outboundLogistic->carrier) }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                           placeholder="e.g., FedEx, UPS, DHL">
                    <p class="mt-1 text-sm text-gray-500">Optional: Shipping carrier</p>
                </div>

                <!-- Tracking Number -->
                <div>
                    <label for="tracking_number" class="block text-sm font-medium text-gray-700 mb-2">Tracking Number</label>
                    <input type="text" 
                           id="tracking_number" 
                           name="tracking_number" 
                           value="{{ old('tracking_number', $outboundLogistic->tracking_number) }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                           placeholder="e.g., 1234567890">
                    <p class="mt-1 text-sm text-gray-500">Optional: Tracking number</p>
                </div>

                <!-- Shipping Date -->
                <div>
                    <label for="shipping_date" class="block text-sm font-medium text-gray-700 mb-2">Shipping Date</label>
                    <input type="date" 
                           id="shipping_date" 
                           name="shipping_date" 
                           value="{{ old('shipping_date', $outboundLogistic->shipping_date ? $outboundLogistic->shipping_date->format('Y-m-d') : '') }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <p class="mt-1 text-sm text-gray-500">Optional: Shipping date</p>
                </div>

                <!-- Delivery Date -->
                <div>
                    <label for="delivery_date" class="block text-sm font-medium text-gray-700 mb-2">Delivery Date</label>
                    <input type="date" 
                           id="delivery_date" 
                           name="delivery_date" 
                           value="{{ old('delivery_date', $outboundLogistic->delivery_date ? $outboundLogistic->delivery_date->format('Y-m-d') : '') }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <p class="mt-1 text-sm text-gray-500">Optional: Delivery date</p>
                </div>
            </div>

            <!-- Notes -->
            <div class="mt-6">
                <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">Notes</label>
                <textarea id="notes" 
                          name="notes" 
                          rows="4"
                          class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                          placeholder="Enter shipment notes...">{{ old('notes', $outboundLogistic->notes) }}</textarea>
                <p class="mt-1 text-sm text-gray-500">Optional: Additional notes about the shipment</p>
            </div>

            <!-- Current Status Info -->
            <div class="mt-6 p-4 bg-gray-50 rounded-lg">
                <h3 class="text-sm font-medium text-gray-700 mb-2">Current Status</h3>
                <div class="flex items-center gap-4">
                    <span class="text-sm text-gray-600">Status:</span>
                    @if ($outboundLogistic->status === 'Pending')
                        <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-orange-100 text-orange-800">
                            Pending
                        </span>
                    @elseif ($outboundLogistic->status === 'Processing')
                        <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                            Processing
                        </span>
                    @elseif ($outboundLogistic->status === 'Shipped')
                        <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                            Shipped
                        </span>
                    @elseif ($outboundLogistic->status === 'Delivered')
                        <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                            Delivered
                        </span>
                    @else
                        <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                            Cancelled
                        </span>
                    @endif
                    <span class="text-sm text-gray-600">| Created: {{ $outboundLogistic->created_at->format('M d, Y') }}</span>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="flex justify-end gap-3 mt-8">
                <a href="{{ route('admin.warehousing.outbound-logistics') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-medium py-2 px-6 rounded-lg transition-colors">
                    Cancel
                </a>
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-6 rounded-lg transition-colors">
                    <i class='bx bx-save mr-2'></i>
                    Update Shipment
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
