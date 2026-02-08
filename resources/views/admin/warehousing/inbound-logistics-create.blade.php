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
                    <a href="{{ route('admin.warehousing.inbound-logistics') }}" class="ml-1 text-gray-700 hover:text-blue-600 md:ml-2">Inbound Logistics</a>
                </div>
            </li>
            <li aria-current="page">
                <div class="flex items-center">
                    <i class='bx bx-chevron-right text-gray-400'></i>
                    <span class="ml-1 text-gray-500 md:ml-2">Add Shipment</span>
                </div>
            </li>
        </ol>
    </nav>

    <!-- Page Header -->
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-900">Add New Shipment</h1>
        <a href="{{ route('admin.warehousing.inbound-logistics') }}" class="bg-gray-600 hover:bg-gray-700 text-white font-medium py-2 px-4 rounded-lg flex items-center gap-2 transition-colors">
            <i class='bx bx-arrow-back text-xl'></i>
            Back to List
        </a>
    </div>

    <!-- Form -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <form action="{{ route('inbound-logistics.store') }}" method="POST">
            @csrf
            
            <!-- Error Messages -->
            @if ($errors->any())
                <div class="bg-red-50 border border-red-200 rounded-md p-4 mb-6">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <i class='bx bx-error-circle text-red-400 text-xl'></i>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-red-800">There were some errors with your submission:</h3>
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
            <div class="mb-6 p-4 bg-blue-50 rounded-lg border border-blue-200">
                <h3 class="text-sm font-semibold text-gray-900 mb-3">Search by SKU or Item Name</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="inventory_search" class="block text-sm font-medium text-gray-700 mb-2">Search SKU or Item Name</label>
                        <div class="relative">
                            <input type="text" 
                                   id="inventory_search" 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                   placeholder="Type SKU or item name..."
                                   autocomplete="off">
                            <div id="inventory_search_results" class="absolute z-10 w-full bg-white border border-gray-300 rounded-lg shadow-lg max-h-48 overflow-y-auto hidden"></div>
                        </div>
                        <p class="mt-1 text-sm text-gray-500">Search existing inventory items to auto-fill all details</p>
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
                           placeholder="e.g., SHP-2024-001"
                           required>
                </div>

                <!-- PO Number -->
                <div>
                    <label for="po_number" class="block text-sm font-medium text-gray-700 mb-2">PO Number *</label>
                    <input type="text" 
                           id="po_number" 
                           name="po_number" 
                           value="{{ old('po_number') }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                           placeholder="Select from PO search above"
                           readonly>
                    <p class="mt-1 text-sm text-gray-500">Auto-filled from PO search</p>
                </div>

                <!-- SKU -->
                <div>
                    <label for="sku" class="block text-sm font-medium text-gray-700 mb-2">SKU</label>
                    <input type="text" 
                           id="sku" 
                           name="sku" 
                           value="{{ old('sku') }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                           placeholder="Auto-filled from PO or inventory"
                           readonly>
                    <p class="mt-1 text-sm text-gray-500">Auto-filled from PO or inventory selection</p>
                </div>

                <!-- Supplier -->
                <div>
                    <label for="supplier" class="block text-sm font-medium text-gray-700 mb-2">Supplier *</label>
                    <select id="supplier" 
                            name="supplier" 
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            required>
                        <option value="">Select a supplier...</option>
                        @foreach($suppliers as $supplier)
                            <option value="{{ $supplier->name }}" 
                                    {{ old('supplier') == $supplier->name ? 'selected' : '' }}>
                                {{ $supplier->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Item Name -->
                <div>
                    <label for="item_name" class="block text-sm font-medium text-gray-700 mb-2">Item Name</label>
                    <input type="text" 
                           id="item_name" 
                           name="item_name" 
                           value="{{ old('item_name') }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                           placeholder="Auto-filled from SKU search"
                           readonly>
                    <p class="mt-1 text-sm text-gray-500">Auto-filled from SKU selection</p>
                </div>

                <!-- Expected Units -->
                <div>
                    <label for="expected_units" class="block text-sm font-medium text-gray-700 mb-2">Expected Units *</label>
                    <input type="number" 
                           id="expected_units" 
                           name="expected_units" 
                           value="{{ old('expected_units') }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                           placeholder="e.g., 100"
                           min="1"
                           required>
                </div>

                <!-- Expected Date -->
                <div>
                    <label for="expected_date" class="block text-sm font-medium text-gray-700 mb-2">Expected Date *</label>
                    <div class="relative">
                        <input type="date" 
                               id="expected_date" 
                               name="expected_date" 
                               value="{{ old('expected_date') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                               required>
                        <i class='bx bx-calendar absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 text-xl pointer-events-none'></i>
                    </div>
                </div>

                <!-- Description -->
                <div class="md:col-span-2">
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Notes</label>
                    <textarea id="description" 
                              name="description" 
                              rows="4"
                              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                              placeholder="Additional notes about this shipment...">{{ old('description') }}</textarea>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="flex justify-end gap-3 mt-8 pt-6 border-t border-gray-200">
                <a href="{{ route('admin.warehousing.inbound-logistics') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-medium py-2 px-6 rounded-lg transition-colors">
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
                        div.className = 'px-3 py-2 hover:bg-blue-50 cursor-pointer border-b border-gray-100 last:border-b-0';
                        div.innerHTML = `
                            <div class="font-medium text-sm">${item.item_name}</div>
                            <div class="text-xs text-gray-500">SKU: ${item.sku} | Stock: ${item.stock} | Location: ${item.location}</div>
                            <div class="text-xs text-blue-600">Category: ${item.category} | Supplier: ${item.supplier || 'N/A'}</div>
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
    
    // Auto-fill all form fields
    document.getElementById('shipment_id').value = `IN-${item.sku}`;
    document.getElementById('po_number').value = `PO-${item.sku}`;
    document.getElementById('sku').value = item.sku;
    document.getElementById('item_name').value = item.item_name;
    document.getElementById('expected_units').value = 1;

    // Auto-select supplier if available
    if (item.supplier && document.getElementById('supplier')) {
        const supplierSelect = document.getElementById('supplier');
        const options = supplierSelect.options;
        for (let i = 0; i < options.length; i++) {
            if (options[i].value === item.supplier) {
                supplierSelect.selectedIndex = i;
                break;
            }
        }
    }
    
    // Set default expected date to today + 3 days
    const expectedDate = new Date();
    expectedDate.setDate(expectedDate.getDate() + 3);
    document.getElementById('expected_date').value = expectedDate.toISOString().split('T')[0];
}

// Clear selection
document.getElementById('clear_inventory_selection').addEventListener('click', function() {
    selectedInventory = null;
    document.getElementById('inventory_search').value = '';
    document.getElementById('inventory_search_results').classList.add('hidden');
    // Clear auto-filled fields
    document.getElementById('shipment_id').value = '';
    document.getElementById('po_number').value = '';
    document.getElementById('sku').value = '';
    document.getElementById('item_name').value = '';
    document.getElementById('expected_units').value = '';
    document.getElementById('expected_date').value = '';
    // Reset supplier dropdown
    if (document.getElementById('supplier')) {
        document.getElementById('supplier').selectedIndex = 0;
    }
    if (document.getElementById('description')) {
        document.getElementById('description').value = '';
    }
});

// Hide results when clicking outside
document.addEventListener('click', function(e) {
    if (!e.target.closest('#inventory_search') && !e.target.closest('#inventory_search_results')) {
        document.getElementById('inventory_search_results').classList.add('hidden');
    }
});
</script>
@endsection
