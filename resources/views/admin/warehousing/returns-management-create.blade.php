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
                <!-- SKU Dropdown -->
                <div>
                    <label for="sku" class="block text-sm font-medium text-gray-700 mb-2">SKU *</label>
                    <select id="sku" 
                            name="sku" 
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            required>
                        <option value="">Select SKU</option>
                        @foreach ($returnedItems as $item)
                            <option value="{{ $item->sku }}" 
                                    data-po-number="{{ $item->po_number ?? '-' }}"
                                    data-department="{{ $item->department ?? '-' }}"
                                    data-item-name="{{ $item->item_name }}"
                                    data-stock="{{ $item->stock }}"
                                    data-supplier="{{ $item->supplier ?? '-' }}"
                                    data-notes="{{ $item->notes ?? '' }}">
                                {{ $item->sku }} - {{ $item->item_name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- PO Number (Auto-filled) -->
                <div>
                    <label for="po_number" class="block text-sm font-medium text-gray-700 mb-2">PO Number</label>
                    <input type="text" 
                           id="po_number" 
                           name="po_number" 
                           value="{{ old('po_number') }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg bg-gray-50"
                           placeholder="Auto-filled from SKU"
                           readonly>
                </div>

                <!-- Department (Auto-filled) -->
                <div>
                    <label for="department" class="block text-sm font-medium text-gray-700 mb-2">Department</label>
                    <input type="text" 
                           id="department" 
                           name="department" 
                           value="{{ old('department') }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg bg-gray-50"
                           placeholder="Auto-filled from SKU"
                           readonly>
                </div>

                <!-- Item Name (Auto-filled) -->
                <div>
                    <label for="item_name" class="block text-sm font-medium text-gray-700 mb-2">Item Name</label>
                    <input type="text" 
                           id="item_name" 
                           name="item_name" 
                           value="{{ old('item_name') }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg bg-gray-50"
                           placeholder="Auto-filled from SKU"
                           readonly>
                </div>

                <!-- Stock (Auto-filled) -->
                <div>
                    <label for="stock" class="block text-sm font-medium text-gray-700 mb-2">Stock</label>
                    <input type="text" 
                           id="stock" 
                           name="stock" 
                           value="{{ old('stock') }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg bg-gray-50"
                           placeholder="Auto-filled from SKU"
                           readonly>
                </div>

                <!-- Supplier (Auto-filled) -->
                <div>
                    <label for="supplier" class="block text-sm font-medium text-gray-700 mb-2">Supplier</label>
                    <input type="text" 
                           id="supplier" 
                           name="supplier" 
                           value="{{ old('supplier') }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg bg-gray-50"
                           placeholder="Auto-filled from SKU"
                           readonly>
                </div>

                <!-- Notes (Auto-filled) -->
                <div>
                    <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">Notes</label>
                    <textarea id="notes" 
                              name="notes" 
                              rows="4"
                              class="w-full px-3 py-2 border border-gray-300 rounded-lg bg-gray-50"
                              placeholder="Auto-filled from SKU"
                              readonly></textarea>
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
    
<script>
// Auto-fill fields when SKU is selected
document.getElementById('sku').addEventListener('change', function() {
    const selectedOption = this.options[this.selectedIndex];
    
    if (selectedOption.value) {
        // Auto-fill all fields with selected item data
        document.getElementById('po_number').value = selectedOption.dataset.poNumber;
        document.getElementById('department').value = selectedOption.dataset.department;
        document.getElementById('item_name').value = selectedOption.dataset.itemName;
        document.getElementById('stock').value = selectedOption.dataset.stock;
        document.getElementById('supplier').value = selectedOption.dataset.supplier;
        document.getElementById('notes').value = selectedOption.dataset.notes;
    } else {
        // Clear fields if no selection
        document.getElementById('po_number').value = '';
        document.getElementById('department').value = '';
        document.getElementById('item_name').value = '';
        document.getElementById('stock').value = '';
        document.getElementById('supplier').value = '';
        document.getElementById('notes').value = '';
    }
});

// Set default return date to today
document.getElementById('return_date').value = new Date().toISOString().split('T')[0];

// Set default status to Pending
document.getElementById('status').value = 'Pending';
</script>
@endsection
