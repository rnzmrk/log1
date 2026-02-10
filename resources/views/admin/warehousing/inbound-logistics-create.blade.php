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

            <!-- PO Selection -->
            <div class="mb-6 p-4 bg-blue-50 rounded-lg border border-blue-200">
                <h3 class="text-sm font-semibold text-gray-900 mb-3">Select Purchase Order</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="po_select" class="block text-sm font-medium text-gray-700 mb-2">Purchase Order *</label>
                        <select id="po_select" 
                                name="po_id" 
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                required>
                            <option value="">Select a purchase order...</option>
                            @foreach($purchaseOrders as $po)
                                <option value="{{ $po->id }}" 
                                        data-po-number="{{ $po->po_number }}"
                                        data-item-name="{{ $po->item_name ?? 'N/A' }}"
                                        data-supplier="{{ $po->supplier }}"
                                        data-quantity="{{ $po->item_quantity ?? 'N/A' }}"
                                        data-status="{{ $po->status }}">
                                    {{ $po->po_number }} - {{ $po->item_name ?? 'N/A' }}
                                </option>
                            @endforeach
                        </select>
                        <p class="mt-1 text-sm text-gray-500">Select a purchase order with "To Received" status</p>
                    </div>
                    <div class="flex items-end">
                        <button type="button" id="clear_po_selection" class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-medium py-2 px-4 rounded-lg transition-colors">
                            Clear Selection
                        </button>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- PO Number -->
                <div>
                    <label for="po_number" class="block text-sm font-medium text-gray-700 mb-2">PO Number *</label>
                    <input type="text" 
                           id="po_number" 
                           name="po_number" 
                           value="{{ old('po_number') }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                           placeholder="Auto-filled from PO selection"
                           readonly>
                    <p class="mt-1 text-sm text-gray-500">Auto-filled from PO selection</p>
                </div>

                <!-- Supplier -->
                <div>
                    <label for="supplier" class="block text-sm font-medium text-gray-700 mb-2">Supplier *</label>
                    <input type="text" 
                           id="supplier" 
                           name="supplier" 
                           value="{{ old('supplier') }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                           placeholder="Auto-filled from PO"
                           readonly>
                    <p class="mt-1 text-sm text-gray-500">Auto-filled from PO selection</p>
                </div>

                <!-- Item Name -->
                <div>
                    <label for="item_name" class="block text-sm font-medium text-gray-700 mb-2">Item Name</label>
                    <input type="text" 
                           id="item_name" 
                           name="item_name" 
                           value="{{ old('item_name') }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                           placeholder="Auto-filled from PO"
                           readonly>
                    <p class="mt-1 text-sm text-gray-500">Auto-filled from PO selection</p>
                </div>

                <!-- Quantity -->
                <div>
                    <label for="quantity" class="block text-sm font-medium text-gray-700 mb-2">Quantity *</label>
                    <input type="number" 
                           id="quantity" 
                           name="quantity" 
                           value="{{ old('quantity') }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                           placeholder="Auto-filled from PO"
                           readonly>
                    <p class="mt-1 text-sm text-gray-500">Auto-filled from PO selection</p>
                </div>

                <!-- Department -->
                <div>
                    <label for="department" class="block text-sm font-medium text-gray-700 mb-2">Department *</label>
                    <input type="text" 
                           id="department" 
                           name="department" 
                           value="{{ old('department') }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                           placeholder="e.g., IT Department, HR Department"
                           required>
                </div>

                <!-- Status -->
                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Status *</label>
                    <select id="status" 
                            name="status" 
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            required>
                        <option value="">Select status...</option>
                        <option value="Pending">Pending</option>
                        <option value="In Transit">In Transit</option>
                        <option value="Delivered">Delivered</option>
                    </select>
                </div>

                <!-- Quality Check -->
                <div>
                    <label for="quality" class="block text-sm font-medium text-gray-700 mb-2">Quality Check</label>
                    <select id="quality" 
                            name="quality" 
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="">Select quality status...</option>
                        <option value="Pass">Pass</option>
                        <option value="Fail">Fail</option>
                        <option value="Pending">Pending</option>
                    </select>
                </div>

                <!-- Received By -->
                <div>
                    <label for="received_by" class="block text-sm font-medium text-gray-700 mb-2">Received By *</label>
                    <input type="text" 
                           id="received_by" 
                           name="received_by" 
                           value="{{ Auth::user()->name }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                           readonly>
                    <p class="mt-1 text-sm text-gray-500">Auto-filled with logged-in user</p>
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
let selectedPO = null;

// PO selection change handler
document.getElementById('po_select').addEventListener('change', function(e) {
    const selectedOption = e.target.options[e.target.selectedIndex];
    
    if (e.target.value === '') {
        // Clear all fields if no selection
        clearPOSelection();
        return;
    }
    
    // Auto-fill all form fields from selected option data attributes
    document.getElementById('po_number').value = selectedOption.dataset.poNumber;
    document.getElementById('supplier').value = selectedOption.dataset.supplier;
    document.getElementById('item_name').value = selectedOption.dataset.itemName;
    document.getElementById('quantity').value = selectedOption.dataset.quantity;

    // Set default expected date to today + 3 days
    const expectedDate = new Date();
    expectedDate.setDate(expectedDate.getDate() + 3);
    document.getElementById('expected_date').value = expectedDate.toISOString().split('T')[0];
});

// Clear selection
document.getElementById('clear_po_selection').addEventListener('click', function() {
    clearPOSelection();
});

function clearPOSelection() {
    document.getElementById('po_select').selectedIndex = 0;
    // Clear auto-filled fields
    document.getElementById('po_number').value = '';
    document.getElementById('supplier').value = '';
    document.getElementById('item_name').value = '';
    document.getElementById('quantity').value = '';
    document.getElementById('expected_date').value = '';
    // Reset status dropdown
    if (document.getElementById('status')) {
        document.getElementById('status').selectedIndex = 0;
    }
    // Reset quality dropdown
    if (document.getElementById('quality')) {
        document.getElementById('quality').selectedIndex = 0;
    }
    // Clear department
    if (document.getElementById('department')) {
        document.getElementById('department').value = '';
    }
    if (document.getElementById('description')) {
        document.getElementById('description').value = '';
    }
}
</script>
@endsection
