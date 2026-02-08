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
                    <span class="ml-1 text-gray-500 md:ml-2">Edit Purchase Order</span>
                </div>
            </li>
        </ol>
    </nav>

    <!-- Page Header -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Edit Purchase Order</h1>
            <p class="text-gray-600 mt-1">PO Number: {{ $purchaseOrder->po_number }}</p>
        </div>
        <div class="flex gap-3">
            <a href="{{ route('purchase-orders.show', $purchaseOrder->id) }}" class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-medium py-2 px-4 rounded-lg flex items-center gap-2 transition-colors">
                <i class='bx bx-show text-xl'></i>
                View
            </a>
            <a href="{{ route('admin.procurement.create-purchase-order') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-medium py-2 px-4 rounded-lg flex items-center gap-2 transition-colors">
                <i class='bx bx-arrow-back text-xl'></i>
                Back to Purchase Orders
            </a>
        </div>
    </div>

    <!-- Form Container -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-lg font-semibold text-gray-900">Edit Purchase Order Information</h2>
        </div>
        
        <form method="POST" action="{{ route('purchase-orders.update', $purchaseOrder->id) }}" class="p-6">
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
                <!-- PO Number -->
                <div>
                    <label for="po_number" class="block text-sm font-medium text-gray-700 mb-2">PO Number *</label>
                    <input type="text" 
                           id="po_number" 
                           name="po_number" 
                           value="{{ old('po_number', $purchaseOrder->po_number) }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                           placeholder="e.g., PO-2026-0001"
                           required>
                    <p class="mt-1 text-sm text-gray-500">Unique purchase order identifier</p>
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
                                    data-contact="{{ $supplier->contact_person ?? '' }}"
                                    data-email="{{ $supplier->email ?? '' }}"
                                    data-phone="{{ $supplier->phone ?? '' }}"
                                    data-address="{{ $supplier->address ?? '' }}"
                                    {{ old('supplier', $purchaseOrder->supplier) == $supplier->name ? 'selected' : '' }}>
                                {{ $supplier->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Supplier Contact -->
                <div>
                    <label for="supplier_contact" class="block text-sm font-medium text-gray-700 mb-2">Supplier Contact</label>
                    <input type="text" 
                           id="supplier_contact" 
                           name="supplier_contact" 
                           value="{{ old('supplier_contact', $purchaseOrder->supplier_contact) }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                           placeholder="e.g., John Smith">
                    <p class="mt-1 text-sm text-gray-500">Optional: Contact person name</p>
                </div>

                <!-- Supplier Email -->
                <div>
                    <label for="supplier_email" class="block text-sm font-medium text-gray-700 mb-2">Supplier Email</label>
                    <input type="email" 
                           id="supplier_email" 
                           name="supplier_email" 
                           value="{{ old('supplier_email', $purchaseOrder->supplier_email) }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                           placeholder="e.g., john@supplier.com">
                    <p class="mt-1 text-sm text-gray-500">Optional: Contact email</p>
                </div>

                <!-- Supplier Phone -->
                <div>
                    <label for="supplier_phone" class="block text-sm font-medium text-gray-700 mb-2">Supplier Phone</label>
                    <input type="text" 
                           id="supplier_phone" 
                           name="supplier_phone" 
                           value="{{ old('supplier_phone', $purchaseOrder->supplier_phone) }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                           placeholder="e.g., +1 (555) 123-4567">
                    <p class="mt-1 text-sm text-gray-500">Optional: Contact phone number</p>
                </div>

                <!-- Billing Address -->
                <div>
                    <label for="billing_address" class="block text-sm font-medium text-gray-700 mb-2">Billing Address</label>
                    <textarea id="billing_address" 
                              name="billing_address" 
                              rows="3"
                              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                              placeholder="Enter billing address...">{{ old('billing_address', $purchaseOrder->billing_address) }}</textarea>
                    <p class="mt-1 text-sm text-gray-500">Optional: Billing address</p>
                </div>

                <!-- Shipping Address -->
                <div>
                    <label for="shipping_address" class="block text-sm font-medium text-gray-700 mb-2">Shipping Address</label>
                    <textarea id="shipping_address" 
                              name="shipping_address" 
                              rows="3"
                              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                              placeholder="Enter shipping address...">{{ old('shipping_address', $purchaseOrder->shipping_address) }}</textarea>
                    <p class="mt-1 text-sm text-gray-500">Optional: Shipping address</p>
                </div>

                <!-- Subtotal -->
                <div>
                    <label for="subtotal" class="block text-sm font-medium text-gray-700 mb-2">Subtotal *</label>
                    <input type="number" 
                           id="subtotal" 
                           name="subtotal" 
                           value="{{ old('subtotal', $purchaseOrder->subtotal) }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                           placeholder="0.00"
                           step="0.01"
                           min="0"
                           required>
                    <p class="mt-1 text-sm text-gray-500">Total cost before tax and shipping</p>
                </div>

                <!-- Tax Amount -->
                <div>
                    <label for="tax_amount" class="block text-sm font-medium text-gray-700 mb-2">Tax Amount *</label>
                    <input type="number" 
                           id="tax_amount" 
                           name="tax_amount" 
                           value="{{ old('tax_amount', $purchaseOrder->tax_amount) }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                           placeholder="0.00"
                           step="0.01"
                           min="0"
                           required>
                    <p class="mt-1 text-sm text-gray-500">Tax amount (e.g., 10% of subtotal)</p>
                </div>

                <!-- Shipping Cost -->
                <div>
                    <label for="shipping_cost" class="block text-sm font-medium text-gray-700 mb-2">Shipping Cost *</label>
                    <input type="number" 
                           id="shipping_cost" 
                           name="shipping_cost" 
                           value="{{ old('shipping_cost', $purchaseOrder->shipping_cost) }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                           placeholder="0.00"
                           step="0.01"
                           min="0"
                           required>
                    <p class="mt-1 text-sm text-gray-500">Shipping and handling costs</p>
                </div>

                <!-- Total Amount Display -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Total Amount</label>
                    <div class="w-full px-3 py-2 border border-gray-300 rounded-lg bg-gray-50">
                        <span class="text-gray-900 font-medium text-lg">₱{{ number_format($purchaseOrder->total_amount, 2) }}</span>
                    </div>
                    <p class="mt-1 text-sm text-gray-500">Auto-calculated: Subtotal + Tax + Shipping</p>
                </div>

                <!-- Priority -->
                <div>
                    <label for="priority" class="block text-sm font-medium text-gray-700 mb-2">Priority *</label>
                    <select id="priority" 
                            name="priority" 
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            required>
                        <option value="">Select priority</option>
                        <option value="Low" {{ old('priority', $purchaseOrder->priority) === 'Low' ? 'selected' : '' }}>Low</option>
                        <option value="Medium" {{ old('priority', $purchaseOrder->priority) === 'Medium' ? 'selected' : '' }}>Medium</option>
                        <option value="High" {{ old('priority', $purchaseOrder->priority) === 'High' ? 'selected' : '' }}>High</option>
                        <option value="Urgent" {{ old('priority', $purchaseOrder->priority) === 'Urgent' ? 'selected' : '' }}>Urgent</option>
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
                        <option value="Draft" {{ old('status', $purchaseOrder->status) === 'Draft' ? 'selected' : '' }}>Draft</option>
                        <option value="Sent" {{ old('status', $purchaseOrder->status) === 'Sent' ? 'selected' : '' }}>Sent</option>
                        <option value="Approved" {{ old('status', $purchaseOrder->status) === 'Approved' ? 'selected' : '' }}>Approved</option>
                        <option value="Rejected" {{ old('status', $purchaseOrder->status) === 'Rejected' ? 'selected' : '' }}>Rejected</option>
                        <option value="Partially Received" {{ old('status', $purchaseOrder->status) === 'Partially Received' ? 'selected' : '' }}>Partially Received</option>
                        <option value="Received" {{ old('status', $purchaseOrder->status) === 'Received' ? 'selected' : '' }}>Received</option>
                        <option value="Cancelled" {{ old('status', $purchaseOrder->status) === 'Cancelled' ? 'selected' : '' }}>Cancelled</option>
                    </select>
                </div>

                <!-- Order Date -->
                <div>
                    <label for="order_date" class="block text-sm font-medium text-gray-700 mb-2">Order Date *</label>
                    <input type="date" 
                           id="order_date" 
                           name="order_date" 
                           value="{{ old('order_date', $purchaseOrder->order_date ? $purchaseOrder->order_date->format('Y-m-d') : '') }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                           required>
                </div>

                <!-- Expected Delivery Date -->
                <div>
                    <label for="expected_delivery_date" class="block text-sm font-medium text-gray-700 mb-2">Expected Delivery Date *</label>
                    <input type="date" 
                           id="expected_delivery_date" 
                           name="expected_delivery_date" 
                           value="{{ old('expected_delivery_date', $purchaseOrder->expected_delivery_date ? $purchaseOrder->expected_delivery_date->format('Y-m-d') : '') }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                           required>
                </div>

                <!-- Actual Delivery Date -->
                <div>
                    <label for="actual_delivery_date" class="block text-sm font-medium text-gray-700 mb-2">Actual Delivery Date</label>
                    <input type="date" 
                           id="actual_delivery_date" 
                           name="actual_delivery_date" 
                           value="{{ old('actual_delivery_date', $purchaseOrder->actual_delivery_date ? $purchaseOrder->actual_delivery_date->format('Y-m-d') : '') }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <p class="mt-1 text-sm text-gray-500">Optional: Auto-filled when marked as received</p>
                </div>

                <!-- Created By -->
                <div>
                    <label for="created_by" class="block text-sm font-medium text-gray-700 mb-2">Created By *</label>
                    <input type="text" 
                           id="created_by" 
                           name="created_by" 
                           value="{{ old('created_by', $purchaseOrder->created_by) }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                           placeholder="e.g., John Smith"
                           required>
                </div>

                <!-- Approved By -->
                <div>
                    <label for="approved_by" class="block text-sm font-medium text-gray-700 mb-2">Approved By</label>
                    <input type="text" 
                           id="approved_by" 
                           name="approved_by" 
                           value="{{ old('approved_by', $purchaseOrder->approved_by) }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                           placeholder="e.g., Jane Manager">
                    <p class="mt-1 text-sm text-gray-500">Optional: Name of approving manager</p>
                </div>
            </div>

            <!-- Notes -->
            <div class="mt-6">
                <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">Notes</label>
                <textarea id="notes" 
                          name="notes" 
                          rows="4"
                          class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                          placeholder="Enter additional notes about this purchase order...">{{ old('notes', $purchaseOrder->notes) }}</textarea>
                <p class="mt-1 text-sm text-gray-500">Optional: Additional information about the purchase order</p>
            </div>

            <!-- Current Status Info -->
            <div class="mt-6 p-4 bg-gray-50 rounded-lg">
                <h3 class="text-sm font-medium text-gray-700 mb-2">Current Status</h3>
                <div class="flex items-center gap-4">
                    <span class="text-sm text-gray-600">Status:</span>
                    @if ($purchaseOrder->status === 'Draft')
                        <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                            Draft
                        </span>
                    @elseif ($purchaseOrder->status === 'Sent')
                        <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                            Sent
                        </span>
                    @elseif ($purchaseOrder->status === 'Approved')
                        <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                            Approved
                        </span>
                    @elseif ($purchaseOrder->status === 'Rejected')
                        <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                            Rejected
                        </span>
                    @elseif ($purchaseOrder->status === 'Partially Received')
                        <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-purple-100 text-purple-800">
                            Partially Received
                        </span>
                    @elseif ($purchaseOrder->status === 'Received')
                        <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                            Received
                        </span>
                    @else
                        <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                            Cancelled
                        </span>
                    @endif
                    <span class="text-sm text-gray-600">| Created: {{ $purchaseOrder->created_at->format('M d, Y') }}</span>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="flex justify-end gap-3 mt-8">
                <a href="{{ route('admin.procurement.create-purchase-order') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-medium py-2 px-6 rounded-lg transition-colors">
                    Cancel
                </a>
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-6 rounded-lg transition-colors">
                    <i class='bx bx-save mr-2'></i>
                    Update Purchase Order
                </button>
            </div>
        </form>
    </div>
</div>

<script>
// Auto-fill supplier fields when supplier is selected
document.addEventListener('DOMContentLoaded', function() {
    const supplierSelect = document.getElementById('supplier');
    const contactField = document.getElementById('supplier_contact');
    const emailField = document.getElementById('supplier_email');
    const phoneField = document.getElementById('supplier_phone');
    const billingAddressField = document.getElementById('billing_address');
    const shippingAddressField = document.getElementById('shipping_address');
    
    supplierSelect.addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        
        if (selectedOption.value) {
            // Auto-fill supplier contact information
            contactField.value = selectedOption.dataset.contact || '';
            emailField.value = selectedOption.dataset.email || '';
            phoneField.value = selectedOption.dataset.phone || '';
            
            // Auto-fill addresses if they're empty
            if (!billingAddressField.value) {
                billingAddressField.value = selectedOption.dataset.address || '';
            }
            if (!shippingAddressField.value) {
                shippingAddressField.value = selectedOption.dataset.address || '';
            }
        } else {
            // Clear fields if no supplier selected
            contactField.value = '';
            emailField.value = '';
            phoneField.value = '';
        }
    });
    
    // Auto-calculate total amount
    const subtotal = document.getElementById('subtotal');
    const taxAmount = document.getElementById('tax_amount');
    const shippingCost = document.getElementById('shipping_cost');
    const totalDisplay = document.querySelector('.bg-gray-50 span');
    
    function calculateTotal() {
        const subtotalValue = parseFloat(subtotal.value) || 0;
        const taxValue = parseFloat(taxAmount.value) || 0;
        const shippingValue = parseFloat(shippingCost.value) || 0;
        const total = subtotalValue + taxValue + shippingValue;
        totalDisplay.textContent = '₱' + total.toFixed(2);
    }
    
    subtotal.addEventListener('input', calculateTotal);
    taxAmount.addEventListener('input', calculateTotal);
    shippingCost.addEventListener('input', calculateTotal);
    
    // Initialize with current values
    calculateTotal();
});
</script>
@endsection
