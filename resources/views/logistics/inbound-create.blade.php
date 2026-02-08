@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1>Accept Inbound Delivery</h1>
                <a href="{{ route('logistics.dashboard') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Back to Dashboard
                </a>
            </div>

            <div class="card">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0"><i class="fas fa-box"></i> Accept Incoming Supplies</h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('logistics.inbound.store') }}">
                        @csrf

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="supply_request_id" class="form-label">Select Purchase Order *</label>
                                <select class="form-select @error('supply_request_id') is-invalid @enderror" 
                                        id="supply_request_id" name="supply_request_id" required>
                                    <option value="">Choose a purchase order...</option>
                                    @foreach($orderedItems as $item)
                                    <option value="{{ $item->id }}" 
                                            data-item="{{ $item->item_name }}"
                                            data-quantity="{{ $item->quantity_approved }}"
                                            data-price="{{ $item->unit_price }}">
                                        {{ $item->request_id }} - {{ $item->item_name }} ({{ $item->quantity_approved }} units)
                                    </option>
                                    @endforeach
                                </select>
                                @error('supply_request_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="quantity_received" class="form-label">Quantity Received *</label>
                                <input type="number" class="form-control @error('quantity_received') is-invalid @enderror" 
                                       id="quantity_received" name="quantity_received" min="1" required>
                                @error('quantity_received')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="form-text text-muted">Enter the actual quantity received</small>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="condition" class="form-label">Item Condition *</label>
                                <select class="form-select @error('condition') is-invalid @enderror" 
                                        id="condition" name="condition" required>
                                    <option value="">Select condition...</option>
                                    <option value="Good">Good</option>
                                    <option value="Damaged">Damaged</option>
                                    <option value="Defective">Defective</option>
                                </select>
                                @error('condition')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Order Details</label>
                                <div class="form-control-plaintext bg-light p-2 rounded">
                                    <div id="order-details">
                                        <small class="text-muted">Select a purchase order to see details</small>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="notes" class="form-label">Notes</label>
                            <textarea class="form-control @error('notes') is-invalid @enderror" 
                                      id="notes" name="notes" rows="3" 
                                      placeholder="Any additional notes about the delivery..."></textarea>
                            @error('notes')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="alert alert-info">
                            <i class="fas fa-info-circle"></i>
                            <strong>Note:</strong> When you accept this delivery, the inventory will be automatically updated with the received quantity.
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('logistics.dashboard') }}" class="btn btn-secondary">
                                <i class="fas fa-times"></i> Cancel
                            </a>
                            <button type="submit" class="btn btn-success">
                                <i class="fas fa-check"></i> Accept Delivery & Update Inventory
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const supplyRequestSelect = document.getElementById('supply_request_id');
    const quantityReceivedInput = document.getElementById('quantity_received');
    const orderDetailsDiv = document.getElementById('order-details');

    supplyRequestSelect.addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        
        if (selectedOption.value) {
            const itemName = selectedOption.dataset.item;
            const expectedQuantity = selectedOption.dataset.quantity;
            const unitPrice = selectedOption.dataset.price;

            orderDetailsDiv.innerHTML = `
                <div><strong>Item:</strong> ${itemName}</div>
                <div><strong>Expected Quantity:</strong> ${expectedQuantity} units</div>
                <div><strong>Unit Price:</strong> ₱${parseFloat(unitPrice).toFixed(2)}</div>
            `;

            // Set max quantity to expected quantity
            quantityReceivedInput.max = expectedQuantity;
            quantityReceivedInput.placeholder = `Max: ${expectedQuantity}`;
        } else {
            orderDetailsDiv.innerHTML = '<small class="text-muted">Select a purchase order to see details</small>';
            quantityReceivedInput.max = '';
            quantityReceivedInput.placeholder = '';
        }
    });
});
</script>
@endsection
@endsection
