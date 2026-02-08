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
                    <span class="ml-1 text-gray-500 md:ml-2">New Shipment</span>
                </div>
            </li>
        </ol>
    </nav>

    <!-- Page Header -->
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-900">Create New Shipment</h1>
        <a href="{{ route('admin.warehousing.outbound-logistics') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-medium py-2 px-4 rounded-lg flex items-center gap-2 transition-colors">
            <i class='bx bx-arrow-back text-xl'></i>
            Back to Shipments
        </a>
    </div>

    <!-- Form Container -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-lg font-semibold text-gray-900">Shipment Information</h2>
        </div>
        
        <form method="POST" action="{{ route('outbound-logistics.store') }}" class="p-6">
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

            <!-- Inventory Selection -->
            <div class="mb-6 p-4 bg-gray-50 rounded-lg border border-gray-200">
                <h3 class="text-sm font-semibold text-gray-900 mb-3">Quick Fill from Inventory</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="inventory_search" class="block text-sm font-medium text-gray-700 mb-2">Select Inventory Item (SKU or Name)</label>
                        <div class="relative">
                            <input type="text" 
                                   id="inventory_search" 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                   placeholder="Type SKU or item name..."
                                   autocomplete="off">
                            <div id="inventory_search_results" class="absolute z-10 w-full bg-white border border-gray-300 rounded-lg shadow-lg max-h-48 overflow-y-auto hidden"></div>
                        </div>
                        <p class="mt-1 text-sm text-gray-500">Start typing to search existing inventory items</p>
                    </div>
                    <div class="flex items-end">
                        <button type="button" id="clear_inventory_selection" class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-medium py-2 px-4 rounded-lg transition-colors">
                            Clear Selection
                        </button>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Shipment ID -->
                <div>
                    <label for="shipment_id" class="block text-sm font-medium text-gray-700 mb-2">Shipment ID *</label>
                    <input type="text" 
                           id="shipment_id" 
                           name="shipment_id" 
                           value="{{ old('shipment_id') }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                           placeholder="e.g., SHIP-2024-001"
                           required>
                </div>

                <!-- Item Name -->
                <div>
                    <label for="item_name" class="block text-sm font-medium text-gray-700 mb-2">Item Name *</label>
                    <input type="text" 
                           id="item_name" 
                           name="item_name" 
                           value="{{ old('item_name') }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                           placeholder="e.g., Laptop, Office Chair"
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
                           placeholder="0"
                           min="1"
                           required>
                </div>

                <!-- Expected Date -->
                <div>
                    <label for="expected_date" class="block text-sm font-medium text-gray-700 mb-2">Expected Date *</label>
                    <input type="date" 
                           id="expected_date" 
                           name="expected_date" 
                           value="{{ old('expected_date') }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                           required>
                </div>

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
                           placeholder="e.g., Acme Corporation"
                           required>
                </div>

                <!-- Destination -->
                <div>
                    <label for="destination" class="block text-sm font-medium text-gray-700 mb-2">Destination *</label>
                    <input type="text" 
                           id="destination" 
                           name="destination" 
                           value="{{ old('destination') }}"
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
                           value="{{ old('total_units') }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                           placeholder="0"
                           min="1"
                           required>
                </div>

                <!-- Priority -->
                <div>
                    <label for="priority" class="block text-sm font-medium text-gray-700 mb-2">Priority *</label>
                    <select id="priority" 
                            name="priority" 
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            required>
                        <option value="">Select priority</option>
                        <option value="Low" {{ old('priority') === 'Low' ? 'selected' : '' }}>Low</option>
                        <option value="Medium" {{ old('priority') === 'Medium' ? 'selected' : '' }}>Medium</option>
                        <option value="High" {{ old('priority') === 'High' ? 'selected' : '' }}>High</option>
                        <option value="Urgent" {{ old('priority') === 'Urgent' ? 'selected' : '' }}>Urgent</option>
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
                        <option value="Processing" {{ old('status') === 'Processing' ? 'selected' : '' }}>Processing</option>
                        <option value="Shipped" {{ old('status') === 'Shipped' ? 'selected' : '' }}>Shipped</option>
                        <option value="Delivered" {{ old('status') === 'Delivered' ? 'selected' : '' }}>Delivered</option>
                        <option value="Cancelled" {{ old('status') === 'Cancelled' ? 'selected' : '' }}>Cancelled</option>
                    </select>
                </div>

                <!-- Total Value -->
                <div>
                    <label for="total_value" class="block text-sm font-medium text-gray-700 mb-2">Total Value</label>
                    <input type="number" 
                           id="total_value" 
                           name="total_value" 
                           value="{{ old('total_value') }}"
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
                           value="{{ old('carrier') }}"
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
                           value="{{ old('tracking_number') }}"
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
                           value="{{ old('shipping_date') }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <p class="mt-1 text-sm text-gray-500">Optional: Planned shipping date</p>
                </div>

                <!-- Delivery Date -->
                <div>
                    <label for="delivery_date" class="block text-sm font-medium text-gray-700 mb-2">Delivery Date</label>
                    <input type="date" 
                           id="delivery_date" 
                           name="delivery_date" 
                           value="{{ old('delivery_date') }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <p class="mt-1 text-sm text-gray-500">Optional: Expected delivery date</p>
                </div>
            </div>

            <!-- Notes -->
            <div class="mt-6">
                <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">Notes</label>
                <textarea id="notes" 
                          name="notes" 
                          rows="4"
                          class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                          placeholder="Enter shipment notes...">{{ old('notes') }}</textarea>
                <p class="mt-1 text-sm text-gray-500">Optional: Additional notes about the shipment</p>
            </div>

            <!-- Form Actions -->
            <div class="flex justify-end gap-3 mt-8">
                <a href="{{ route('admin.warehousing.outbound-logistics') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-medium py-2 px-6 rounded-lg transition-colors">
                    Cancel
                </a>
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-6 rounded-lg transition-colors">
                    <i class='bx bx-save mr-2'></i>
                    Create Shipment
                </button>
            </div>
        </form>
    </div>
</div>

<script>
let inventorySearchTimeout;
let selectedInventory = null;

// Inventory search autocomplete
document.getElementById('inventory_search').addEventListener('input', function(e) {
    const query = e.target.value.trim();
    const resultsDiv = document.getElementById('inventory_search_results');
    
    clearTimeout(inventorySearchTimeout);
    
    if (query.length < 2) {
        resultsDiv.classList.add('hidden');
        return;
    }
    
    inventorySearchTimeout = setTimeout(() => {
        fetch(`/inventory/search?q=${encodeURIComponent(query)}`)
            .then(response => response.json())
            .then(data => {
                resultsDiv.innerHTML = '';
                if (data.length === 0) {
                    resultsDiv.innerHTML = '<div class="p-3 text-gray-500 text-sm">No items found</div>';
                } else {
                    data.forEach(item => {
                        const div = document.createElement('div');
                        div.className = 'px-3 py-2 hover:bg-gray-100 cursor-pointer border-b border-gray-100 last:border-b-0';
                        div.innerHTML = `
                            <div class="font-medium text-sm">${item.item_name}</div>
                            <div class="text-xs text-gray-500">SKU: ${item.sku} | Stock: ${item.stock} | Location: ${item.location}</div>
                        `;
                        div.addEventListener('click', () => selectInventory(item));
                        resultsDiv.appendChild(div);
                    });
                }
                resultsDiv.classList.remove('hidden');
            })
            .catch(error => {
                console.error('Search error:', error);
                resultsDiv.innerHTML = '<div class="p-3 text-red-500 text-sm">Search failed</div>';
                resultsDiv.classList.remove('hidden');
            });
    }, 300);
});

// Select inventory and populate form
function selectInventory(item) {
    selectedInventory = item;
    document.getElementById('inventory_search').value = `${item.sku} - ${item.item_name}`;
    document.getElementById('inventory_search_results').classList.add('hidden');
    
    // Auto-fill form fields
    document.getElementById('shipment_id').value = `SHIP-${item.sku}`;
    document.getElementById('item_name').value = item.item_name;
    document.getElementById('quantity').value = item.stock;
    document.getElementById('total_units').value = item.stock;
    
    // Set default expected date to today + 3 days
    const expectedDate = new Date();
    expectedDate.setDate(expectedDate.getDate() + 3);
    document.getElementById('expected_date').value = expectedDate.toISOString().split('T')[0];
    
    // Optional: fill destination with location
    if (!document.getElementById('destination').value) {
        document.getElementById('destination').value = item.location;
    }
}

// Clear selection
document.getElementById('clear_inventory_selection').addEventListener('click', function() {
    selectedInventory = null;
    document.getElementById('inventory_search').value = '';
    document.getElementById('inventory_search_results').classList.add('hidden');
    // Clear auto-filled fields
    document.getElementById('shipment_id').value = '';
    document.getElementById('item_name').value = '';
    document.getElementById('quantity').value = '';
    document.getElementById('total_units').value = '';
    document.getElementById('expected_date').value = '';
});

// Hide results when clicking outside
document.addEventListener('click', function(e) {
    if (!e.target.closest('#inventory_search') && !e.target.closest('#inventory_search_results')) {
        document.getElementById('inventory_search_results').classList.add('hidden');
    }
});
</script>
@endsection
