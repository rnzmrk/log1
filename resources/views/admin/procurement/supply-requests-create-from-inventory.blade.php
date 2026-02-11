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
                    <span class="ml-1 text-gray-500 md:ml-2">Request Item</span>
                </div>
            </li>
        </ol>
    </nav>

    <!-- Page Header -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Request Item from Inventory</h1>
            <p class="text-gray-600 mt-1">Create a supply request for items from inventory</p>
        </div>
        <a href="{{ route('admin.warehousing.storage-inventory') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-medium py-2 px-4 rounded-lg flex items-center gap-2 transition-colors">
            <i class='bx bx-arrow-back text-xl'></i>
            Back to Inventory
        </a>
    </div>

    <!-- Form Container -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
        <form action="{{ route('supply-requests.store') }}" method="POST">
            @csrf
            <div class="p-6">
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

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Item Selection -->
                    <div>
                        <label class="block text-sm font-medium text-gray-500 mb-1">Select Item *</label>
                        <select id="item_name" 
                                name="item_name" 
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                required>
                            <option value="">Select an item from inventory</option>
                            @foreach ($inventoryItems as $item)
                                <option value="{{ $item->item_name }}" 
                                        data-sku="{{ $item->sku }}"
                                        data-supplier="{{ $item->supplier }}"
                                        data-stock="{{ $item->stock }}">
                                    {{ $item->item_name }} (Stock: {{ $item->stock }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- SKU (Auto-filled) -->
                    <div>
                        <label class="block text-sm font-medium text-gray-500 mb-1">SKU</label>
                        <input type="text" 
                               id="sku" 
                               name="sku" 
                               value="{{ old('sku') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-gray-50"
                               placeholder="Auto-filled from item selection"
                               readonly>
                    </div>

                    <!-- Supplier (Auto-filled) -->
                    <div>
                        <label class="block text-sm font-medium text-gray-500 mb-1">Supplier</label>
                        <input type="text" 
                               id="supplier" 
                               name="supplier" 
                               value="{{ old('supplier') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-gray-50"
                               placeholder="Auto-filled from item selection"
                               readonly>
                    </div>

                    <!-- Quantity -->
                    <div>
                        <label class="block text-sm font-medium text-gray-500 mb-1">Quantity *</label>
                        <input type="number" 
                               id="quantity" 
                               name="quantity" 
                               value="{{ old('quantity') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                               placeholder="Enter quantity requested"
                               min="1"
                               required>
                    </div>

                    <!-- Unit Price -->
                    <div>
                        <label class="block text-sm font-medium text-gray-500 mb-1">Unit Price</label>
                        <input type="number" 
                               id="unit_price" 
                               name="unit_price" 
                               value="{{ old('unit_price') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                               placeholder="Enter unit price"
                               step="0.01"
                               min="0">
                    </div>

                    <!-- Priority -->
                    <div>
                        <label class="block text-sm font-medium text-gray-500 mb-1">Priority *</label>
                        <select id="priority" 
                                name="priority" 
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                required>
                            <option value="">Select priority</option>
                            <option value="Low" {{ old('priority') === 'Low' ? 'selected' : '' }}>Low</option>
                            <option value="Medium" {{ old('priority') === 'Medium' ? 'selected' : '' }}>Medium</option>
                            <option value="High" {{ old('priority') === 'High' ? 'selected' : '' }}>High</option>
                        </select>
                    </div>

                    <!-- Department -->
                    <div>
                        <label class="block text-sm font-medium text-gray-500 mb-1">Department</label>
                        <input type="text" 
                               id="department" 
                               name="department" 
                               value="{{ old('department', auth()->user()->department ?? '') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                               placeholder="e.g., IT Department">
                    </div>

                    <!-- Expected Delivery Date -->
                    <div>
                        <label class="block text-sm font-medium text-gray-500 mb-1">Expected Delivery Date</label>
                        <div class="relative">
                            <input type="date" 
                                   id="expected_date" 
                                   name="expected_date" 
                                   value="{{ old('expected_date') }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <i class='bx bx-calendar absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 text-xl pointer-events-none'></i>
                        </div>
                    </div>

                    <!-- Reason/Notes -->
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-500 mb-1">Reason for Request *</label>
                        <textarea id="reason" 
                                  name="reason" 
                                  rows="4"
                                  class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                  placeholder="Explain why this item is needed..."
                                  required>{{ old('reason') }}</textarea>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="mt-8 flex justify-end gap-3">
                    <a href="{{ route('admin.warehousing.storage-inventory') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-medium py-2 px-6 rounded-lg transition-colors">
                        Cancel
                    </a>
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-6 rounded-lg transition-colors">
                        <i class='bx bx-send mr-2'></i>
                        Submit Request
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const itemSelect = document.getElementById('item_name');
    const skuInput = document.getElementById('sku');
    const supplierInput = document.getElementById('supplier');
    
    itemSelect.addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        
        if (selectedOption.value) {
            skuInput.value = selectedOption.dataset.sku || '';
            supplierInput.value = selectedOption.dataset.supplier || '';
        } else {
            skuInput.value = '';
            supplierInput.value = '';
        }
    });
});
</script>
@endsection
