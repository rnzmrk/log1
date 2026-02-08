@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1>Create Outbound Delivery</h1>
                <a href="{{ route('logistics.dashboard') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Back to Dashboard
                </a>
            </div>

            <div class="card">
                <div class="card-header bg-warning text-dark">
                    <h5 class="mb-0"><i class="fas fa-truck"></i> Process Outbound Delivery</h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('logistics.outbound.store') }}">
                        @csrf

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="inventory_id" class="form-label">Select Item *</label>
                                <select class="form-select @error('inventory_id') is-invalid @enderror" 
                                        id="inventory_id" name="inventory_id" required>
                                    <option value="">Choose an item...</option>
                                    @foreach($inventoryItems as $item)
                                    <option value="{{ $item->id }}" 
                                            data-stock="{{ $item->stock }}"
                                            data-name="{{ $item->item_name }}"
                                            data-location="{{ $item->location }}">
                                        {{ $item->item_name }} ({{ $item->stock }} in stock) - {{ $item->location }}
                                    </option>
                                    @endforeach
                                </select>
                                @error('inventory_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="quantity" class="form-label">Quantity *</label>
                                <input type="number" class="form-control @error('quantity') is-invalid @enderror" 
                                       id="quantity" name="quantity" min="1" required>
                                @error('quantity')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="form-text text-muted">Enter quantity to deliver</small>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="destination" class="form-label">Destination *</label>
                                <input type="text" class="form-control @error('destination') is-invalid @enderror" 
                                       id="destination" name="destination" 
                                       placeholder="e.g., Department A, Building 2" required>
                                @error('destination')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="recipient" class="form-label">Recipient *</label>
                                <input type="text" class="form-control @error('recipient') is-invalid @enderror" 
                                       id="recipient" name="recipient" 
                                       placeholder="e.g., Juan Dela Cruz" required>
                                @error('recipient')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
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

                        <div class="alert alert-warning">
                            <i class="fas fa-exclamation-triangle"></i>
                            <strong>Important:</strong> This will reduce the inventory stock. Make sure you have sufficient stock before processing.
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('logistics.dashboard') }}" class="btn btn-secondary">
                                <i class="fas fa-times"></i> Cancel
                            </a>
                            <button type="submit" class="btn btn-warning">
                                <i class="fas fa-truck"></i> Process Outbound Delivery
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
    const inventorySelect = document.getElementById('inventory_id');
    const quantityInput = document.getElementById('quantity');

    inventorySelect.addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        
        if (selectedOption.value) {
            const availableStock = parseInt(selectedOption.dataset.stock);
            const itemName = selectedOption.dataset.name;
            const location = selectedOption.dataset.location;

            // Set max quantity to available stock
            quantityInput.max = availableStock;
            quantityInput.placeholder = `Available: ${availableStock} units`;

            // Show warning if stock is low
            if (availableStock <= 10) {
                quantityInput.classList.add('is-warning');
                const warning = document.createElement('div');
                warning.className = 'alert alert-warning mt-2';
                warning.innerHTML = `<i class="fas fa-exclamation-triangle"></i> Warning: Low stock item (${availableStock} units remaining)`;
                
                // Remove existing warning if any
                const existingWarning = quantityInput.parentNode.querySelector('.alert');
                if (existingWarning) {
                    existingWarning.remove();
                }
                
                quantityInput.parentNode.appendChild(warning);
            }
        } else {
            quantityInput.max = '';
            quantityInput.placeholder = '';
            quantityInput.classList.remove('is-warning');
            
            // Remove warning if exists
            const existingWarning = quantityInput.parentNode.querySelector('.alert');
            if (existingWarning) {
                existingWarning.remove();
            }
        }
    });

    // Validate quantity on input
    quantityInput.addEventListener('input', function() {
        const maxStock = parseInt(this.max);
        const currentValue = parseInt(this.value);
        
        if (currentValue > maxStock) {
            this.setCustomValidity(`Cannot exceed available stock (${maxStock} units)`);
        } else {
            this.setCustomValidity('');
        }
    });
});
</script>
@endsection
@endsection
