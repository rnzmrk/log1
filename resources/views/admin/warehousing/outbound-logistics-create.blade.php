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
                <h3 class="text-sm font-semibold text-gray-900 mb-3">Select from Moved Inventory</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="sku_dropdown" class="block text-sm font-medium text-gray-700 mb-2">Select SKU (Moved Items Only)</label>
                        <select id="sku_dropdown" 
                                name="sku" 
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                required>
                            <option value="">Select an item...</option>
                            @foreach ($movedInventory as $item)
                                <option value="{{ $item->sku }}" 
                                        data-po="{{ $item->po_number ?? '' }}"
                                        data-department="{{ $item->department ?? '' }}"
                                        data-supplier="{{ $item->supplier ?? '' }}"
                                        data-item-name="{{ $item->item_name }}"
                                        data-stock="{{ $item->stock }}">
                                    {{ $item->sku }} - {{ $item->item_name }} (Stock: {{ $item->stock }})
                                </option>
                            @endforeach
                        </select>
                        <p class="mt-1 text-sm text-gray-500">Only items with "Moved" status are shown</p>
                    </div>
                    <div class="flex items-end">
                        <button type="button" id="clear_selection" class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-medium py-2 px-4 rounded-lg transition-colors">
                            Clear Selection
                        </button>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <!-- SKU -->
                <div>
                    <label for="sku" class="block text-sm font-medium text-gray-700 mb-2">SKU *</label>
                    <input type="text" 
                           id="sku" 
                           name="sku" 
                           value="{{ old('sku') }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                           placeholder="e.g., SKU-12345"
                           required>
                </div>

                <!-- PO Number -->
                <div>
                    <label for="po_number" class="block text-sm font-medium text-gray-700 mb-2">PO Number *</label>
                    <input type="text" 
                           id="po_number" 
                           name="po_number" 
                           value="{{ old('po_number') }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                           placeholder="e.g., PO2024-001"
                           required>
                </div>

                <!-- Department -->
                <div>
                    <label for="department" class="block text-sm font-medium text-gray-700 mb-2">Department *</label>
                    <input type="text" 
                           id="department" 
                           name="department" 
                           value="{{ old('department') }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                           placeholder="e.g., IT Department"
                           required>
                </div>

                <!-- Supplier -->
                <div>
                    <label for="supplier" class="block text-sm font-medium text-gray-700 mb-2">Supplier *</label>
                    <input type="text" 
                           id="supplier" 
                           name="supplier" 
                           value="{{ old('supplier') }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                           placeholder="e.g., TechCorp"
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
                           placeholder="e.g., Laptop Computer"
                           required>
                </div>

                <!-- Stock -->
                <div>
                    <label for="stock" class="block text-sm font-medium text-gray-700 mb-2">Stock *</label>
                    <input type="number" 
                           id="stock" 
                           name="stock" 
                           value="{{ old('stock') }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                           placeholder="e.g., 10"
                           min="1"
                           required>
                </div>

                <!-- Address -->
                <div class="lg:col-span-2">
                    <label for="address" class="block text-sm font-medium text-gray-700 mb-2">Address *</label>
                    <input type="text" 
                           id="address" 
                           name="address" 
                           value="{{ old('address') }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                           placeholder="e.g., 123 Main St, City, State"
                           required>
                </div>

                <!-- Contact -->
                <div class="lg:col-span-2">
                    <label for="contact" class="block text-sm font-medium text-gray-700 mb-2">Contact *</label>
                    <input type="text" 
                           id="contact" 
                           name="contact" 
                           value="{{ old('contact') }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                           placeholder="e.g., John Doe - john@example.com - 555-0123"
                           required>
                </div>
            </div>

            <!-- Submit Button -->
            <div class="flex justify-end gap-3 mt-8">
                <a href="{{ route('admin.warehousing.outbound-logistics') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-medium py-2 px-4 rounded-lg transition-colors">
                    Cancel
                </a>
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg transition-colors">
                    Create Shipment
                </button>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const skuDropdown = document.getElementById('sku_dropdown');
    const clearButton = document.getElementById('clear_selection');
    
    // Auto-fill form fields when SKU is selected
    skuDropdown.addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        
        if (selectedOption.value) {
            document.getElementById('sku').value = selectedOption.value;
            document.getElementById('po_number').value = selectedOption.getAttribute('data-po') || '';
            document.getElementById('department').value = selectedOption.getAttribute('data-department') || '';
            document.getElementById('supplier').value = selectedOption.getAttribute('data-supplier') || '';
            document.getElementById('item_name').value = selectedOption.getAttribute('data-item-name') || '';
            document.getElementById('stock').value = selectedOption.getAttribute('data-stock') || '';
        }
    });
    
    // Clear selection
    clearButton.addEventListener('click', function() {
        skuDropdown.value = '';
        document.getElementById('sku').value = '';
        document.getElementById('po_number').value = '';
        document.getElementById('department').value = '';
        document.getElementById('supplier').value = '';
        document.getElementById('item_name').value = '';
        document.getElementById('stock').value = '';
        document.getElementById('address').value = '';
        document.getElementById('contact').value = '';
    });
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
