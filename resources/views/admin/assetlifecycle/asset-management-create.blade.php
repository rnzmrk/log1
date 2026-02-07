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
                    <a href="#" class="text-gray-700 hover:text-blue-600 ml-1 md:ml-2">Asset Lifecycle & Maintenance</a>
                </div>
            </li>
            <li>
                <div class="flex items-center">
                    <i class='bx bx-chevron-right text-gray-400'></i>
                    <a href="{{ route('admin.assetlifecycle.asset-management') }}" class="text-gray-700 hover:text-blue-600 ml-1 md:ml-2">Asset Management</a>
                </div>
            </li>
            <li aria-current="page">
                <div class="flex items-center">
                    <i class='bx bx-chevron-right text-gray-400'></i>
                    <span class="ml-1 text-gray-500 md:ml-2">Add New Asset</span>
                </div>
            </li>
        </ol>
    </nav>

    <!-- Page Header -->
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-900">Add New Asset</h1>
        <a href="{{ route('admin.assetlifecycle.asset-management') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-medium py-2 px-4 rounded-lg flex items-center gap-2 transition-colors">
            <i class='bx bx-arrow-back text-xl'></i>
            Back to Assets
        </a>
    </div>

    <!-- Form Container -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-lg font-semibold text-gray-900">Asset Information</h2>
        </div>
        
        <form method="POST" action="{{ route('asset-management.store') }}" class="p-6">
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
                <!-- Asset Name -->
                <div>
                    <label for="asset_name" class="block text-sm font-medium text-gray-700 mb-2">Asset Name *</label>
                    <input type="text" 
                           id="asset_name" 
                           name="asset_name" 
                           value="{{ old('asset_name') }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                           placeholder="e.g., Dell XPS 15 Laptop"
                           required>
                </div>

                <!-- Asset Type -->
                <div>
                    <label for="asset_type" class="block text-sm font-medium text-gray-700 mb-2">Asset Type *</label>
                    <input type="text" 
                           id="asset_type" 
                           name="asset_type" 
                           value="{{ old('asset_type') }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                           placeholder="e.g., Laptop, Desktop, Monitor"
                           required>
                </div>

                <!-- Category -->
                <div>
                    <label for="category" class="block text-sm font-medium text-gray-700 mb-2">Category *</label>
                    <select id="category" 
                            name="category" 
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            required>
                        <option value="">Select category</option>
                        <option value="Laptop" {{ old('category') === 'Laptop' ? 'selected' : '' }}>Laptop</option>
                        <option value="Desktop" {{ old('category') === 'Desktop' ? 'selected' : '' }}>Desktop</option>
                        <option value="Monitor" {{ old('category') === 'Monitor' ? 'selected' : '' }}>Monitor</option>
                        <option value="Phone" {{ old('category') === 'Phone' ? 'selected' : '' }}>Phone</option>
                        <option value="Tablet" {{ old('category') === 'Tablet' ? 'selected' : '' }}>Tablet</option>
                        <option value="Printer" {{ old('category') === 'Printer' ? 'selected' : '' }}>Printer</option>
                        <option value="Server" {{ old('category') === 'Server' ? 'selected' : '' }}>Server</option>
                        <option value="Network Equipment" {{ old('category') === 'Network Equipment' ? 'selected' : '' }}>Network Equipment</option>
                        <option value="Other" {{ old('category') === 'Other' ? 'selected' : '' }}>Other</option>
                    </select>
                </div>

                <!-- Brand -->
                <div>
                    <label for="brand" class="block text-sm font-medium text-gray-700 mb-2">Brand</label>
                    <input type="text" 
                           id="brand" 
                           name="brand" 
                           value="{{ old('brand') }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                           placeholder="e.g., Dell, HP, Apple">
                    <p class="mt-1 text-sm text-gray-500">Optional: Asset brand</p>
                </div>

                <!-- Model -->
                <div>
                    <label for="model" class="block text-sm font-medium text-gray-700 mb-2">Model</label>
                    <input type="text" 
                           id="model" 
                           name="model" 
                           value="{{ old('model') }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                           placeholder="e.g., XPS 15, EliteDesk">
                    <p class="mt-1 text-sm text-gray-500">Optional: Asset model</p>
                </div>

                <!-- Serial Number -->
                <div>
                    <label for="serial_number" class="block text-sm font-medium text-gray-700 mb-2">Serial Number</label>
                    <input type="text" 
                           id="serial_number" 
                           name="serial_number" 
                           value="{{ old('serial_number') }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                           placeholder="e.g., SN123456789">
                    <p class="mt-1 text-sm text-gray-500">Optional: Asset serial number</p>
                </div>

                <!-- Status -->
                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Status *</label>
                    <select id="status" 
                            name="status" 
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            required>
                        <option value="">Select status</option>
                        <option value="Available" {{ old('status') === 'Available' ? 'selected' : '' }}>Available</option>
                        <option value="In Use" {{ old('status') === 'In Use' ? 'selected' : '' }}>In Use</option>
                        <option value="Under Maintenance" {{ old('status') === 'Under Maintenance' ? 'selected' : '' }}>Under Maintenance</option>
                        <option value="Retired" {{ old('status') === 'Retired' ? 'selected' : '' }}>Retired</option>
                        <option value="Lost" {{ old('status') === 'Lost' ? 'selected' : '' }}>Lost</option>
                        <option value="Damaged" {{ old('status') === 'Damaged' ? 'selected' : '' }}>Damaged</option>
                    </select>
                </div>

                <!-- Condition -->
                <div>
                    <label for="condition" class="block text-sm font-medium text-gray-700 mb-2">Condition *</label>
                    <select id="condition" 
                            name="condition" 
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            required>
                        <option value="">Select condition</option>
                        <option value="Excellent" {{ old('condition') === 'Excellent' ? 'selected' : '' }}>Excellent</option>
                        <option value="Good" {{ old('condition') === 'Good' ? 'selected' : '' }}>Good</option>
                        <option value="Fair" {{ old('condition') === 'Fair' ? 'selected' : '' }}>Fair</option>
                        <option value="Poor" {{ old('condition') === 'Poor' ? 'selected' : '' }}>Poor</option>
                    </select>
                </div>

                <!-- Purchase Cost -->
                <div>
                    <label for="purchase_cost" class="block text-sm font-medium text-gray-700 mb-2">Purchase Cost</label>
                    <input type="number" 
                           id="purchase_cost" 
                           name="purchase_cost" 
                           value="{{ old('purchase_cost') }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                           placeholder="0.00"
                           step="0.01"
                           min="0">
                    <p class="mt-1 text-sm text-gray-500">Optional: Purchase cost in USD</p>
                </div>

                <!-- Purchase Date -->
                <div>
                    <label for="purchase_date" class="block text-sm font-medium text-gray-700 mb-2">Purchase Date</label>
                    <input type="date" 
                           id="purchase_date" 
                           name="purchase_date" 
                           value="{{ old('purchase_date') }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <p class="mt-1 text-sm text-gray-500">Optional: When the asset was purchased</p>
                </div>

                <!-- Warranty Expiry -->
                <div>
                    <label for="warranty_expiry" class="block text-sm font-medium text-gray-700 mb-2">Warranty Expiry</label>
                    <input type="date" 
                           id="warranty_expiry" 
                           name="warranty_expiry" 
                           value="{{ old('warranty_expiry') }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <p class="mt-1 text-sm text-gray-500">Optional: Warranty expiration date</p>
                </div>

                <!-- Last Maintenance Date -->
                <div>
                    <label for="last_maintenance_date" class="block text-sm font-medium text-gray-700 mb-2">Last Maintenance Date</label>
                    <input type="date" 
                           id="last_maintenance_date" 
                           name="last_maintenance_date" 
                           value="{{ old('last_maintenance_date') }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <p class="mt-1 text-sm text-gray-500">Optional: Last maintenance date</p>
                </div>

                <!-- Next Maintenance Date -->
                <div>
                    <label for="next_maintenance_date" class="block text-sm font-medium text-gray-700 mb-2">Next Maintenance Date</label>
                    <input type="date" 
                           id="next_maintenance_date" 
                           name="next_maintenance_date" 
                           value="{{ old('next_maintenance_date') }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <p class="mt-1 text-sm text-gray-500">Optional: Next scheduled maintenance</p>
                </div>

                <!-- Assigned To -->
                <div>
                    <label for="assigned_to" class="block text-sm font-medium text-gray-700 mb-2">Assigned To</label>
                    <input type="text" 
                           id="assigned_to" 
                           name="assigned_to" 
                           value="{{ old('assigned_to') }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                           placeholder="e.g., John Smith">
                    <p class="mt-1 text-sm text-gray-500">Optional: Person assigned to this asset</p>
                </div>

                <!-- Department -->
                <div>
                    <label for="department" class="block text-sm font-medium text-gray-700 mb-2">Department</label>
                    <input type="text" 
                           id="department" 
                           name="department" 
                           value="{{ old('department') }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                           placeholder="e.g., IT Department">
                    <p class="mt-1 text-sm text-gray-500">Optional: Department location</p>
                </div>

                <!-- Location -->
                <div>
                    <label for="location" class="block text-sm font-medium text-gray-700 mb-2">Location</label>
                    <input type="text" 
                           id="location" 
                           name="location" 
                           value="{{ old('location') }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                           placeholder="e.g., Office 101, Storage Room">
                    <p class="mt-1 text-sm text-gray-500">Optional: Physical location</p>
                </div>

                <!-- Created By -->
                <div>
                    <label for="created_by" class="block text-sm font-medium text-gray-700 mb-2">Created By *</label>
                    <input type="text" 
                           id="created_by" 
                           name="created_by" 
                           value="{{ old('created_by') }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                           placeholder="e.g., Admin User"
                           required>
                </div>
            </div>

            <!-- Specifications -->
            <div class="mt-6">
                <label for="specifications" class="block text-sm font-medium text-gray-700 mb-2">Asset Specifications</label>
                <textarea id="specifications" 
                          name="specifications" 
                          rows="4"
                          class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                          placeholder="Enter detailed specifications...">{{ old('specifications') }}</textarea>
                <p class="mt-1 text-sm text-gray-500">Optional: Technical specifications, configuration details, etc.</p>
            </div>

            <!-- Notes -->
            <div class="mt-6">
                <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">Additional Notes</label>
                <textarea id="notes" 
                          name="notes" 
                          rows="4"
                          class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                          placeholder="Enter additional notes...">{{ old('notes') }}</textarea>
                <p class="mt-1 text-sm text-gray-500">Optional: Any additional information or special requirements</p>
            </div>

            <!-- Form Actions -->
            <div class="flex justify-end gap-3 mt-8">
                <a href="{{ route('admin.assetlifecycle.asset-management') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-medium py-2 px-6 rounded-lg transition-colors">
                    Cancel
                </a>
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-6 rounded-lg transition-colors">
                    <i class='bx bx-save mr-2'></i>
                    Create Asset
                </button>
            </div>
        </form>
    </div>
</div>

<script>
// Validate warranty expiry is after purchase date
document.addEventListener('DOMContentLoaded', function() {
    const purchaseDate = document.getElementById('purchase_date');
    const warrantyExpiry = document.getElementById('warranty_expiry');
    
    function validateWarranty() {
        if (purchaseDate.value && warrantyExpiry.value) {
            if (new Date(warrantyExpiry.value) <= new Date(purchaseDate.value)) {
                warrantyExpiry.setCustomValidity('Warranty expiry must be after purchase date');
            } else {
                warrantyExpiry.setCustomValidity('');
            }
        }
    }
    
    purchaseDate.addEventListener('change', validateWarranty);
    warrantyExpiry.addEventListener('change', validateWarranty);
});
</script>
@endsection
