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
                    <a href="{{ route('admin.warehousing.storage-inventory') }}" class="text-gray-700 hover:text-blue-600 ml-1 md:ml-2">Storage & Inventory</a>
                </div>
            </li>
            <li aria-current="page">
                <div class="flex items-center">
                    <i class='bx bx-chevron-right text-gray-400'></i>
                    <span class="ml-1 text-gray-500 md:ml-2">Add Inventory Item</span>
                </div>
            </li>
        </ol>
    </nav>

    <!-- Page Header -->
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-900">Add Inventory Item</h1>
        <a href="{{ route('admin.warehousing.storage-inventory') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-medium py-2 px-4 rounded-lg flex items-center gap-2 transition-colors">
            <i class='bx bx-arrow-back text-xl'></i>
            Back to Inventory
        </a>
    </div>

    <!-- Form Container -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-lg font-semibold text-gray-900">Item Information</h2>
        </div>
        
        <form method="POST" action="{{ route('inventory.store') }}" class="p-6">
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
                <!-- Inbound Logistics Selection -->
                <div>
                    <label for="inbound_logistic_id" class="block text-sm font-medium text-gray-700 mb-2">Select from Inbound Storage</label>
                    <select id="inbound_logistic_id" 
                            name="inbound_logistic_id" 
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            onchange="autoFillFromInbound(this)">
                        <option value="">-- Select Inbound Shipment --</option>
                        @foreach($inboundLogistics as $inbound)
                            <option value="{{ $inbound->id }}" 
                                    data-po-number="{{ $inbound->po_number }}"
                                    data-item-name="{{ $inbound->item_name }}"
                                    data-quantity="{{ $inbound->quantity }}"
                                    data-supplier="{{ $inbound->supplier }}"
                                    data-department="{{ $inbound->department }}"
                                    data-category="{{ $inbound->category }}">
                                {{ $inbound->po_number }} - {{ $inbound->item_name }} ({{ $inbound->quantity }} units)
                            </option>
                        @endforeach
                    </select>
                    <p class="mt-1 text-sm text-gray-500">Select to auto-fill PO details</p>
                </div>

                <!-- PO Number (Auto-filled) -->
                <div>
                    <label for="po_number" class="block text-sm font-medium text-gray-700 mb-2">PO Number</label>
                    <input type="text" 
                           id="po_number" 
                           name="po_number" 
                           value="{{ old('po_number') }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                           placeholder="Auto-filled from inbound selection"
                           readonly>
                </div>

                <!-- Item Name -->
                <div>
                    <label for="item_name" class="block text-sm font-medium text-gray-700 mb-2">Item Name *</label>
                    <input type="text" 
                           id="item_name" 
                           name="item_name" 
                           value="{{ old('item_name') }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                           placeholder="e.g., Wireless Headphones"
                           required>
                </div>

                <!-- Department -->
                <div>
                    <label for="department" class="block text-sm font-medium text-gray-700 mb-2">Department</label>
                    <input type="text" 
                           id="department" 
                           name="department" 
                           value="{{ old('department') }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                           placeholder="Auto-filled from inbound selection"
                           readonly>
                    <p class="mt-1 text-sm text-gray-500">Auto-filled from inbound selection</p>
                </div>

                <!-- Stock -->
                <div>
                    <label for="stock" class="block text-sm font-medium text-gray-700 mb-2">Stock Quantity *</label>
                    <input type="number" 
                           id="stock" 
                           name="stock" 
                           value="{{ old('stock') }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                           placeholder="0"
                           min="0"
                           readonly>
                    <p class="mt-1 text-sm text-gray-500">Auto-filled from inbound selection</p>
                </div>

                <!-- Supplier -->
                <div>
                    <label for="supplier" class="block text-sm font-medium text-gray-700 mb-2">Supplier</label>
                    <input type="text" 
                           id="supplier" 
                           name="supplier" 
                           value="{{ old('supplier') }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                           placeholder="Auto-filled from inbound selection"
                           readonly>
                    <p class="mt-1 text-sm text-gray-500">Auto-filled from inbound selection</p>
                </div>
            </div>

            <!-- Description -->
            <div class="mt-6">
                <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                <textarea id="description" 
                          name="description" 
                          rows="4"
                          class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                          placeholder="Enter item description...">{{ old('description') }}</textarea>
                <p class="mt-1 text-sm text-gray-500">Optional: Additional details about the item</p>
            </div>

            <!-- Form Actions -->
            <div class="flex justify-end gap-3 mt-8">
                <a href="{{ route('admin.warehousing.storage-inventory') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-medium py-2 px-6 rounded-lg transition-colors">
                    Cancel
                </a>
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-6 rounded-lg transition-colors">
                    <i class='bx bx-save mr-2'></i>
                    Add Item
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function autoFillFromInbound(select) {
    const selectedOption = select.options[select.selectedIndex];
    
    if (select.value === '') {
        // Clear all fields if no selection
        document.getElementById('po_number').value = '';
        document.getElementById('item_name').value = '';
        document.getElementById('stock').value = '';
        document.getElementById('supplier').value = '';
        document.getElementById('department').value = '';
        return;
    }
    
    // Auto-fill fields from selected inbound shipment
    document.getElementById('po_number').value = selectedOption.getAttribute('data-po-number') || '';
    document.getElementById('item_name').value = selectedOption.getAttribute('data-item-name') || '';
    document.getElementById('stock').value = selectedOption.getAttribute('data-quantity') || '';
    document.getElementById('supplier').value = selectedOption.getAttribute('data-supplier') || '';
    document.getElementById('department').value = selectedOption.getAttribute('data-department') || '';
}
</script>

@endsection
