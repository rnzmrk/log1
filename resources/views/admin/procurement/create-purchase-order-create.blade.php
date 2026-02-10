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
                    <a href="{{ route('admin.procurement.create-purchase-order') }}" class="text-gray-700 hover:text-blue-600 ml-1 md:ml-2">Purchase Orders</a>
                </div>
            </li>
            <li aria-current="page">
                <div class="flex items-center">
                    <i class='bx bx-chevron-right text-gray-400'></i>
                    <span class="ml-1 text-gray-500 md:ml-2">New Purchase Order</span>
                </div>
            </li>
        </ol>
    </nav>

    <!-- Page Header -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Create Purchase Order</h1>
            @if($supplyRequest)
                <p class="text-gray-600 mt-1">Creating PO from Supply Request #{{ $supplyRequest->request_id }}</p>
            @endif
        </div>
        <a href="{{ route('admin.procurement.create-purchase-order') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-medium py-2 px-4 rounded-lg flex items-center gap-2 transition-colors">
            <i class='bx bx-arrow-back text-xl'></i>
            Back to Purchase Orders
        </a>
    </div>

    @if($supplyRequest)
    <!-- Supply Request Information -->
    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
        <div class="flex items-center mb-3">
            <i class='bx bx-info-circle text-blue-600 text-xl mr-2'></i>
            <h3 class="text-lg font-semibold text-blue-900">Supply Request Information</h3>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            <div>
                <span class="text-sm text-blue-600 font-medium">Request ID:</span>
                <p class="text-gray-900">{{ $supplyRequest->request_id }}</p>
            </div>
            <div>
                <span class="text-sm text-blue-600 font-medium">Item:</span>
                <p class="text-gray-900">{{ $supplyRequest->item_name }}</p>
            </div>
            <div>
                <span class="text-sm text-blue-600 font-medium">Quantity:</span>
                <p class="text-gray-900">{{ $supplyRequest->quantity_approved ?? $supplyRequest->quantity_requested }}</p>
            </div>
            <div>
                <span class="text-sm text-blue-600 font-medium">Total Cost:</span>
                <p class="text-gray-900">₱{{ number_format($supplyRequest->total_cost, 2) }}</p>
            </div>
        </div>
        <input type="hidden" name="supply_request_id" value="{{ $supplyRequest->id }}">
    </div>
    @endif

    <!-- Form Container -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-lg font-semibold text-gray-900">Purchase Order Information</h2>
        </div>
        
        <form method="POST" action="{{ route('purchase-orders.store') }}" class="p-6">
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
                <!-- Supply Request (Approved Only) -->
                <div>
                    <label for="supply_request" class="block text-sm font-medium text-gray-700 mb-2">Select Supply Request *</label>
                    <select id="supply_request" 
                            name="supply_request" 
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            required>
                        <option value="">Select an approved supply request...</option>
                        @foreach($approvedSupplyRequests as $request)
                            <option value="{{ $request->id }}" 
                                    data-item="{{ $request->item_name }}"
                                    data-category="{{ $request->category }}"
                                    data-quantity="{{ $request->quantity_requested }}"
                                    data-unit-price="{{ $request->unit_price }}"
                                    data-total-cost="{{ $request->total_cost }}"
                                    data-supplier="{{ $request->supplier }}"
                                    data-needed-by="{{ $request->needed_by_date->format('Y-m-d') }}"
                                    data-priority="{{ $request->priority }}"
                                    {{ old('supply_request') == $request->id ? 'selected' : '' }}>
                                {{ $request->request_id }} - {{ $request->item_name }} ({{ $request->quantity_requested }} units)
                            </option>
                        @endforeach
                    </select>
                    <p class="mt-1 text-sm text-gray-500">Select from approved supply requests only</p>
                </div>

                <!-- Supplier -->
                <div>
                    <label for="supplier" class="block text-sm font-medium text-gray-700 mb-2">Supplier *</label>
                    <input type="text" 
                           id="supplier" 
                           name="supplier" 
                           value="{{ old('supplier') }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                           placeholder="Auto-filled from supply request or enter manually"
                           required>
                    <p class="mt-1 text-sm text-gray-500">Auto-filled from supply request selection</p>
                </div>

                <!-- Shipping Address -->
                <div>
                    <label for="shipping_address" class="block text-sm font-medium text-gray-700 mb-2">Shipping Address</label>
                    <textarea id="shipping_address" 
                              name="shipping_address" 
                              rows="3"
                              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                              placeholder="Enter shipping address...">{{ old('shipping_address') }}</textarea>
                    <p class="mt-1 text-sm text-gray-500">Optional: Shipping address (auto-filled from supplier if available)</p>
                </div>

                <!-- Item Details (Auto-filled from Supply Request) -->
                <div class="md:col-span-2">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Item Details</h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <!-- Item Name -->
                        <div>
                            <label for="item_name" class="block text-sm font-medium text-gray-700 mb-2">Item Name *</label>
                            <input type="text" 
                                   id="item_name" 
                                   name="item_name" 
                                   value="{{ old('item_name') }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg bg-gray-50"
                                   placeholder="Auto-filled from supply request"
                                   readonly>
                            <p class="mt-1 text-sm text-gray-500">Auto-populated from selected supply request</p>
                        </div>

                        <!-- Category -->
                        <div>
                            <label for="item_category" class="block text-sm font-medium text-gray-700 mb-2">Category *</label>
                            <input type="text" 
                                   id="item_category" 
                                   name="item_category" 
                                   value="{{ old('item_category') }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg bg-gray-50"
                                   placeholder="Auto-filled from supply request"
                                   readonly>
                            <p class="mt-1 text-sm text-gray-500">Auto-populated from selected supply request</p>
                        </div>

                        <!-- Quantity -->
                        <div>
                            <label for="item_quantity" class="block text-sm font-medium text-gray-700 mb-2">Quantity *</label>
                            <input type="number" 
                                   id="item_quantity" 
                                   name="item_quantity" 
                                   value="{{ old('item_quantity') }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg bg-gray-50"
                                   placeholder="Auto-filled from supply request"
                                   min="1"
                                   readonly>
                            <p class="mt-1 text-sm text-gray-500">Auto-populated from selected supply request</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                        <!-- Unit Price -->
                        <div>
                            <label for="unit_price" class="block text-sm font-medium text-gray-700 mb-2">Unit Price *</label>
                            <input type="number" 
                                   id="unit_price" 
                                   name="unit_price" 
                                   value="{{ old('unit_price') }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg bg-gray-50"
                                   placeholder="Auto-filled from supply request"
                                   step="0.01"
                                   min="0"
                                   readonly>
                            <p class="mt-1 text-sm text-gray-500">Auto-populated from selected supply request</p>
                        </div>

                        <!-- Total Cost -->
                        <div>
                            <label for="total_cost" class="block text-sm font-medium text-gray-700 mb-2">Total Cost *</label>
                            <input type="number" 
                                   id="total_cost" 
                                   name="total_cost" 
                                   value="{{ old('total_cost') }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg bg-gray-50 text-green-700 font-medium"
                                   placeholder="Auto-filled from supply request"
                                   step="0.01"
                                   min="0"
                                   readonly>
                            <p class="mt-1 text-sm text-gray-500">Auto-populated from selected supply request</p>
                        </div>
                    </div>
                </div>

                <!-- Total Amount Display -->
                <div class="md:col-span-2">
                    <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                        <div class="flex items-center justify-between">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Total Amount</label>
                                <p class="text-sm text-gray-500">Total cost from supply request</p>
                            </div>
                            <div class="text-right">
                                <span id="total_amount_display" class="text-2xl font-bold text-green-700">₱0.00</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Hidden Status Field (automatically set to Sent) -->
<input type="hidden" name="status" value="Sent">

                <!-- Priority (Auto-filled from Supply Request) -->
                <div>
                    <label for="priority" class="block text-sm font-medium text-gray-700 mb-2">Priority *</label>
                    <input type="text" 
                           id="priority" 
                           name="priority" 
                           value="{{ old('priority') }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg bg-gray-50"
                           placeholder="Auto-filled from supply request"
                           readonly>
                    <p class="mt-1 text-sm text-gray-500">Auto-populated from selected supply request</p>
                </div>

                <!-- Hidden Order Date (automatically set to creation date) -->
<input type="hidden" name="order_date" value="{{ now()->format('Y-m-d') }}">

                <!-- Expected Delivery Date (Auto-filled from Supply Request) -->
                <div>
                    <label for="expected_delivery_date" class="block text-sm font-medium text-gray-700 mb-2">Expected Delivery Date *</label>
                    <input type="date" 
                           id="expected_delivery_date" 
                           name="expected_delivery_date" 
                           value="{{ old('expected_delivery_date') }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg bg-gray-50"
                           placeholder="Auto-filled from supply request"
                           readonly>
                    <p class="mt-1 text-sm text-gray-500">Auto-populated from selected supply request (needed by date)</p>
                </div>

                <!-- Hidden Actual Delivery Date (auto-filled when status is received) -->
<input type="hidden" name="actual_delivery_date" value="">

                <!-- Created By (Auto-filled with logged-in user) -->
                <div>
                    <label for="created_by" class="block text-sm font-medium text-gray-700 mb-2">Created By</label>
                    <input type="text" 
                           id="created_by" 
                           name="created_by" 
                           value="{{ auth()->user()->name ?? 'Current User' }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg bg-gray-50 text-gray-700"
                           readonly>
                    <p class="mt-1 text-sm text-gray-500">Automatically set to current logged-in user</p>
                </div>

                <!-- Hidden Approved By (auto-filled when approved) -->
<input type="hidden" name="approved_by" value="">
            </div>

            <!-- Notes -->
            <div class="mt-6">
                <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">Notes</label>
                <textarea id="notes" 
                          name="notes" 
                          rows="4"
                          class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                          placeholder="Enter additional notes about this purchase order...">{{ old('notes') }}</textarea>
                <p class="mt-1 text-sm text-gray-500">Optional: Additional information about the purchase order</p>
            </div>

            <!-- Hidden fields for item data (to ensure submission) -->
            <input type="hidden" id="hidden_item_name" name="item_name" value="">
            <input type="hidden" id="hidden_item_category" name="item_category" value="">
            <input type="hidden" id="hidden_item_quantity" name="item_quantity" value="">
            <input type="hidden" id="hidden_unit_price" name="unit_price" value="">
            <input type="hidden" id="hidden_total_cost" name="total_cost" value="">
            <input type="hidden" id="hidden_priority" name="priority" value="">

            <!-- Form Actions -->
            <div class="flex justify-end gap-3 mt-8">
                <a href="{{ route('admin.procurement.create-purchase-order') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-medium py-2 px-6 rounded-lg transition-colors">
                    Cancel
                </a>
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-6 rounded-lg transition-colors">
                    <i class='bx bx-save mr-2'></i>
                    Create Purchase Order
                </button>
            </div>
        </form>
    </div>
</div>

<script>
// Supplier data for auto-fill
const supplierData = [
    @foreach($suppliers as $supplier)
    {
        name: "{{ $supplier->name ?? '' }}",
        contact: "{{ $supplier->contact_person ?? '' }}",
        email: "{{ $supplier->email ?? '' }}",
        phone: "{{ $supplier->phone ?? '' }}",
        address: "{{ $supplier->address ?? '' }}"
    }@if(!$loop->last),@endif
    @endforeach
];

// Function to auto-fill supplier details
function autoFillSupplierDetails(supplierName) {
    const supplier = supplierData.find(s => s.name === supplierName);
    if (supplier) {
        // Auto-fill shipping address if it's empty
        const shippingAddress = document.getElementById('shipping_address');
        if (shippingAddress && !shippingAddress.value) {
            shippingAddress.value = supplier.address || '';
        }
    }
}

// Auto-fill supplier fields when supplier is selected
document.addEventListener('DOMContentLoaded', function() {
    const supplyRequestSelect = document.getElementById('supply_request');
    
    // Auto-fill item details when supply request is selected
    supplyRequestSelect.addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        
        if (selectedOption.value) {
            // Auto-fill supplier from supply request
            const supplierName = selectedOption.dataset.supplier || '';
            document.getElementById('supplier').value = supplierName;
            
            // Auto-fill supplier details based on supplier name
            autoFillSupplierDetails(supplierName);
            
            // Auto-fill item details (visible fields)
            document.getElementById('item_name').value = selectedOption.dataset.item || '';
            document.getElementById('item_category').value = selectedOption.dataset.category || '';
            document.getElementById('item_quantity').value = selectedOption.dataset.quantity || '';
            document.getElementById('unit_price').value = selectedOption.dataset.unitPrice || '';
            document.getElementById('total_cost').value = selectedOption.dataset.totalCost || '';
            
            // Auto-fill item details (hidden fields for submission)
            document.getElementById('hidden_item_name').value = selectedOption.dataset.item || '';
            document.getElementById('hidden_item_category').value = selectedOption.dataset.category || '';
            document.getElementById('hidden_item_quantity').value = selectedOption.dataset.quantity || '';
            document.getElementById('hidden_unit_price').value = selectedOption.dataset.unitPrice || '';
            document.getElementById('hidden_total_cost').value = selectedOption.dataset.totalCost || '';
            document.getElementById('hidden_priority').value = selectedOption.dataset.priority || '';
            
            // Auto-fill expected delivery date from needed by date
            document.getElementById('expected_delivery_date').value = selectedOption.dataset.neededBy || '';
            
            // Calculate total amount from supply request (quantity × unit price)
            const quantity = parseFloat(selectedOption.dataset.quantity) || 0;
            const unitPrice = parseFloat(selectedOption.dataset.unitPrice) || 0;
            const calculatedTotal = quantity * unitPrice;
            document.getElementById('total_amount_display').textContent = '₱' + calculatedTotal.toFixed(2);
        } else {
            // Clear item fields if no supply request selected
            document.getElementById('supplier').value = '';
            document.getElementById('item_name').value = '';
            document.getElementById('item_category').value = '';
            document.getElementById('item_quantity').value = '';
            document.getElementById('unit_price').value = '';
            document.getElementById('total_cost').value = '';
            document.getElementById('priority').value = '';
            document.getElementById('expected_delivery_date').value = '';
            
            // Clear hidden fields
            document.getElementById('hidden_item_name').value = '';
            document.getElementById('hidden_item_category').value = '';
            document.getElementById('hidden_item_quantity').value = '';
            document.getElementById('hidden_unit_price').value = '';
            document.getElementById('hidden_total_cost').value = '';
            document.getElementById('hidden_priority').value = '';
            
            // Reset total amount display
            document.getElementById('total_amount_display').textContent = '₱0.00';
        }
    });
});
</script>
@endsection
