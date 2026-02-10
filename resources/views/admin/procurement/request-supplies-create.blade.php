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
                    <span class="ml-1 text-gray-500 md:ml-2">New Request</span>
                </div>
            </li>
        </ol>
    </nav>

    <!-- Page Header -->
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-900">Create Supply Request</h1>
        <a href="{{ route('admin.procurement.request-supplies') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-medium py-2 px-4 rounded-lg flex items-center gap-2 transition-colors">
            <i class='bx bx-arrow-back text-xl'></i>
            Back to Requests
        </a>
    </div>

    <!-- Form Container -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-lg font-semibold text-gray-900">Request Information</h2>
        </div>
        
        <form method="POST" action="{{ route('supply-requests.store') }}" class="p-6">
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
                <!-- Item Name -->
                <div>
                    <label for="item_name" class="block text-sm font-medium text-gray-700 mb-2">Item Name *</label>
                    <input type="text" 
                           id="item_name" 
                           name="item_name" 
                           value="{{ old('item_name') }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                           placeholder="e.g., Laptop Computer"
                           required>
                </div>

                <!-- Category -->
                <div>
                    <label for="category" class="block text-sm font-medium text-gray-700 mb-2">Category *</label>
                    <input type="text" 
                           id="category" 
                           name="category" 
                           value="{{ old('category') }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                           placeholder="Auto-filled from vendor selection"
                           required>
                    <p class="mt-1 text-sm text-gray-500">Automatically filled when vendor is selected</p>
                </div>

                <!-- Supplier (Searchable Vendor) -->
                <div>
                    <label for="supplier" class="block text-sm font-medium text-gray-700 mb-2">Supplier *</label>
                    <div class="relative">
                        <input type="text" 
                               id="supplier" 
                               name="supplier" 
                               value="{{ old('supplier') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                               placeholder="Search vendor by name or code..."
                               autocomplete="off"
                               required>
                        <div id="supplier-search-results" class="absolute z-10 w-full mt-1 bg-white border border-gray-300 rounded-lg shadow-lg hidden max-h-60 overflow-y-auto">
                            <!-- Search results will appear here -->
                        </div>
                    </div>
                    <p class="mt-1 text-sm text-gray-500">Search and select a vendor to auto-fill category</p>
                </div>

                <!-- Hidden Request Date (automatically set to creation date) -->
<input type="hidden" name="request_date" value="{{ now()->format('Y-m-d') }}">

                <!-- Quantity Requested -->
                <div>
                    <label for="quantity_requested" class="block text-sm font-medium text-gray-700 mb-2">Quantity Requested *</label>
                    <input type="number" 
                           id="quantity_requested" 
                           name="quantity_requested" 
                           value="{{ old('quantity_requested') }}"
                           class="cost-input w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                           placeholder="1"
                           min="1"
                           required>
                </div>

                <!-- Unit Price -->
                <div>
                    <label for="unit_price" class="block text-sm font-medium text-gray-700 mb-2">Unit Price</label>
                    <input type="number" 
                           id="unit_price" 
                           name="unit_price" 
                           value="{{ old('unit_price') }}"
                           class="cost-input w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                           placeholder="0.00"
                           step="0.01"
                           min="0">
                    <p class="mt-1 text-sm text-gray-500">Optional: Price per unit</p>
                </div>

                <!-- Total Cost (Auto-calculated) -->
                <div>
                    <label for="total_cost" class="block text-sm font-medium text-gray-700 mb-2">Total Cost</label>
                    <div class="bg-green-50 border border-green-200 rounded-lg p-3">
                        <div class="flex items-center gap-2">
                            <span class="text-lg font-bold text-green-600">₱</span>
                            <input type="number" 
                                   id="total_cost" 
                                   name="total_cost" 
                                   value="{{ old('total_cost', '0') }}"
                                   class="text-lg font-bold text-green-600 bg-transparent border-0 focus:outline-none focus:ring-0 w-32"
                                   placeholder="0.00"
                                   step="0.01"
                                   min="0"
                                   readonly>
                        </div>
                        <p class="mt-1 text-sm text-green-600">Automatically calculated (Quantity × Unit Price)</p>
                    </div>
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

                <!-- Hidden Status Field (automatically set to Pending) -->
<input type="hidden" name="status" value="Pending">

<!-- Needed By Date -->
                <div>
                    <label for="needed_by_date" class="block text-sm font-medium text-gray-700 mb-2">Needed By Date *</label>
                    <input type="date" 
                           id="needed_by_date" 
                           name="needed_by_date" 
                           value="{{ old('needed_by_date') }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                           required>
                </div>

                <!-- Expected Delivery -->
                <div>
                    <label for="expected_delivery" class="block text-sm font-medium text-gray-700 mb-2">Expected Delivery</label>
                    <input type="date" 
                           id="expected_delivery" 
                           name="expected_delivery" 
                           value="{{ old('expected_delivery') }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                           placeholder="Optional: Estimated delivery date">
                </div>

                <!-- Requested By (Auto-filled with logged-in user) -->
                <div>
                    <label for="requested_by" class="block text-sm font-medium text-gray-700 mb-2">Requested By</label>
                    <input type="text" 
                           id="requested_by" 
                           name="requested_by" 
                           value="{{ auth()->user()->name ?? 'Current User' }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg bg-gray-50 text-gray-700"
                           readonly>
                    <p class="mt-1 text-sm text-gray-500">Automatically set to current logged-in user</p>
                </div>
            </div>

            <!-- Notes -->
            <div class="mt-6">
                <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">Notes</label>
                <textarea id="notes" 
                          name="notes" 
                          rows="4"
                          class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                          placeholder="Enter additional notes about this request...">{{ old('notes') }}</textarea>
                <p class="mt-1 text-sm text-gray-500">Optional: Additional information about the request</p>
            </div>

            <!-- Form Actions -->
            <div class="flex justify-end gap-3 mt-8">
                <a href="{{ route('admin.procurement.request-supplies') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-medium py-2 px-6 rounded-lg transition-colors">
                    Cancel
                </a>
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-6 rounded-lg transition-colors">
                    <i class='bx bx-save mr-2'></i>
                    Create Request
                </button>
            </div>
        </form>
    </div>
</div>

<script>
let selectedSupplier = null;
let searchTimeout;

// Vendor Search Functionality for Supply Request
document.getElementById('supplier').addEventListener('input', function(e) {
    const query = e.target.value.trim();
    const resultsDiv = document.getElementById('supplier-search-results');
    
    clearTimeout(searchTimeout);
    
    if (query.length < 2) {
        resultsDiv.classList.add('hidden');
        return;
    }
    
    searchTimeout = setTimeout(() => {
        fetch(`/contracts/search-vendors?q=${encodeURIComponent(query)}`)
            .then(response => response.json())
            .then(data => {
                resultsDiv.innerHTML = '';
                
                if (data.length === 0) {
                    resultsDiv.innerHTML = '<div class="p-3 text-gray-500 text-sm">No vendors found</div>';
                } else {
                    data.forEach(supplier => {
                        const item = document.createElement('div');
                        item.className = 'p-3 hover:bg-gray-50 cursor-pointer border-b border-gray-100 last:border-b-0';
                        item.innerHTML = `
                            <div class="flex justify-between items-start">
                                <div>
                                    <div class="font-medium text-gray-900">${supplier.name}</div>
                                    <div class="text-sm text-gray-500">Code: ${supplier.vendor_code}</div>
                                    ${supplier.category ? `<div class="text-xs text-blue-600">Category: ${supplier.category}</div>` : ''}
                                    <div class="text-xs text-gray-400">${supplier.email || 'No email'}</div>
                                </div>
                                <div class="text-xs text-blue-600 bg-blue-50 px-2 py-1 rounded">
                                    ${supplier.city || 'Location'}
                                </div>
                            </div>
                        `;
                        
                        item.addEventListener('click', () => selectSupplier(supplier));
                        resultsDiv.appendChild(item);
                    });
                }
                
                resultsDiv.classList.remove('hidden');
            })
            .catch(error => {
                console.error('Error searching suppliers:', error);
                resultsDiv.innerHTML = '<div class="p-3 text-red-500 text-sm">Error searching suppliers</div>';
                resultsDiv.classList.remove('hidden');
            });
    }, 300);
});

function selectSupplier(supplier) {
    selectedSupplier = supplier;
    document.getElementById('supplier').value = supplier.name;
    document.getElementById('supplier-search-results').classList.add('hidden');
    
    // Auto-fill category based on vendor
    if (supplier.category && !document.getElementById('category').value) {
        document.getElementById('category').value = supplier.category;
    }
    
    // You can also auto-fill other fields if needed
    if (supplier.contact_person) {
        // If you have a contact field, you could fill it here
    }
}

// Hide search results when clicking outside
document.addEventListener('click', function(e) {
    if (!e.target.closest('#supplier') && !e.target.closest('#supplier-search-results')) {
        document.getElementById('supplier-search-results').classList.add('hidden');
    }
});

// Automatic Total Cost Calculation for Supply Request
function calculateTotalCost() {
    const quantity = parseFloat(document.getElementById('quantity_requested').value) || 0;
    const unitPrice = parseFloat(document.getElementById('unit_price').value) || 0;
    
    const totalCost = quantity * unitPrice;
    
    // Update the total cost field
    document.getElementById('total_cost').value = totalCost.toFixed(2);
    
    return totalCost;
}

// Add event listeners for automatic calculation
document.addEventListener('DOMContentLoaded', function() {
    const quantityInput = document.getElementById('quantity_requested');
    const unitPriceInput = document.getElementById('unit_price');
    
    // Calculate on input change
    quantityInput.addEventListener('input', calculateTotalCost);
    unitPriceInput.addEventListener('input', calculateTotalCost);
    
    // Calculate on change (for when values are pasted or changed via arrows)
    quantityInput.addEventListener('change', calculateTotalCost);
    unitPriceInput.addEventListener('change', calculateTotalCost);
    
    // Initial calculation
    calculateTotalCost();
});
</script>
@endsection
