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
                    <a href="{{ route('admin.assetlifecycle.request-asset') }}" class="text-gray-700 hover:text-blue-600 ml-1 md:ml-2">Request Asset</a>
                </div>
            </li>
            <li aria-current="page">
                <div class="flex items-center">
                    <i class='bx bx-chevron-right text-gray-400'></i>
                    <span class="ml-1 text-gray-500 md:ml-2">New Asset Request</span>
                </div>
            </li>
        </ol>
    </nav>

    <!-- Page Header -->
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-900">Create Asset Request</h1>
        <a href="{{ route('admin.assetlifecycle.request-asset') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-medium py-2 px-4 rounded-lg flex items-center gap-2 transition-colors">
            <i class='bx bx-arrow-back text-xl'></i>
            Back to Requests
        </a>
    </div>

    <!-- Form Container -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-lg font-semibold text-gray-900">Asset Request Information</h2>
        </div>
        
        <form method="POST" action="{{ route('asset-requests.store') }}" class="p-6">
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
                           placeholder="e.g., Dell Laptop XPS 15"
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
                    <p class="mt-1 text-sm text-gray-500">Optional: Brand preference</p>
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
                    <p class="mt-1 text-sm text-gray-500">Optional: Specific model</p>
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
                    <p class="mt-1 text-sm text-gray-500">Optional: For replacement/repair requests</p>
                </div>

                <!-- Request Type -->
                <div>
                    <label for="request_type" class="block text-sm font-medium text-gray-700 mb-2">Request Type *</label>
                    <select id="request_type" 
                            name="request_type" 
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            required>
                        <option value="">Select request type</option>
                        <option value="New" {{ old('request_type') === 'New' ? 'selected' : '' }}>New</option>
                        <option value="Replacement" {{ old('request_type') === 'Replacement' ? 'selected' : '' }}>Replacement</option>
                        <option value="Upgrade" {{ old('request_type') === 'Upgrade' ? 'selected' : '' }}>Upgrade</option>
                        <option value="Repair" {{ old('request_type') === 'Repair' ? 'selected' : '' }}>Repair</option>
                        <option value="Other" {{ old('request_type') === 'Other' ? 'selected' : '' }}>Other</option>
                    </select>
                </div>

                <!-- Priority -->
                <div>
                    <label for="priority" class="block text-sm font-medium text-gray-700 mb-2">Priority *</label>
                    <select id="priority" 
                            name="priority" 
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            required>
                        <option value="">Select priority</option>
                        <option value="Low" {{ old('priority') === 'Low' ? 'selected' : '' }}>Low</option>
                        <option value="Medium" {{ old('priority') === 'Medium' ? 'selected' : '' }}>Medium</option>
                        <option value="High" {{ old('priority') === 'High' ? 'selected' : '' }}>High</option>
                        <option value="Urgent" {{ old('priority') === 'Urgent' ? 'selected' : '' }}>Urgent</option>
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
                        <option value="Pending" {{ old('status') === 'Pending' ? 'selected' : '' }}>Pending</option>
                        <option value="Approved" {{ old('status') === 'Approved' ? 'selected' : '' }}>Approved</option>
                        <option value="Rejected" {{ old('status') === 'Rejected' ? 'selected' : '' }}>Rejected</option>
                        <option value="Processing" {{ old('status') === 'Processing' ? 'selected' : '' }}>Processing</option>
                        <option value="Completed" {{ old('status') === 'Completed' ? 'selected' : '' }}>Completed</option>
                        <option value="Cancelled" {{ old('status') === 'Cancelled' ? 'selected' : '' }}>Cancelled</option>
                    </select>
                </div>

                <!-- Estimated Cost -->
                <div>
                    <label for="estimated_cost" class="block text-sm font-medium text-gray-700 mb-2">Estimated Cost</label>
                    <input type="number" 
                           id="estimated_cost" 
                           name="estimated_cost" 
                           value="{{ old('estimated_cost') }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                           placeholder="0.00"
                           step="0.01"
                           min="0">
                    <p class="mt-1 text-sm text-gray-500">Optional: Estimated cost in PHP</p>
                </div>

                <!-- Request Date -->
                <div>
                    <label for="request_date" class="block text-sm font-medium text-gray-700 mb-2">Request Date *</label>
                    <input type="date" 
                           id="request_date" 
                           name="request_date" 
                           value="{{ old('request_date', now()->format('Y-m-d')) }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                           required>
                </div>

                <!-- Required Date -->
                <div>
                    <label for="required_date" class="block text-sm font-medium text-gray-700 mb-2">Required Date</label>
                    <input type="date" 
                           id="required_date" 
                           name="required_date" 
                           value="{{ old('required_date') }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <p class="mt-1 text-sm text-gray-500">Optional: When the asset is needed</p>
                </div>

                <!-- Requested By -->
                <div>
                    <label for="requested_by" class="block text-sm font-medium text-gray-700 mb-2">Requested By *</label>
                    <input type="text" 
                           id="requested_by" 
                           name="requested_by" 
                           value="{{ old('requested_by') }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                           placeholder="e.g., John Smith"
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

                <!-- Approved By -->
                <div>
                    <label for="approved_by" class="block text-sm font-medium text-gray-700 mb-2">Approved By</label>
                    <input type="text" 
                           id="approved_by" 
                           name="approved_by" 
                           value="{{ old('approved_by') }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                           placeholder="e.g., Jane Manager">
                    <p class="mt-1 text-sm text-gray-500">Optional: Name of approving manager</p>
                </div>
            </div>

            <!-- Justification -->
            <div class="mt-6">
                <label for="justification" class="block text-sm font-medium text-gray-700 mb-2">Business Justification</label>
                <textarea id="justification" 
                          name="justification" 
                          rows="4"
                          class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                          placeholder="Explain why this asset is needed for your work...">{{ old('justification') }}</textarea>
                <p class="mt-1 text-sm text-gray-500">Optional: Business justification for the request</p>
            </div>

            <!-- Specifications -->
            <div class="mt-6">
                <label for="specifications" class="block text-sm font-medium text-gray-700 mb-2">Asset Specifications</label>
                <textarea id="specifications" 
                          name="specifications" 
                          rows="4"
                          class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                          placeholder="Enter detailed specifications...">{{ old('specifications') }}</textarea>
                <p class="mt-1 text-sm text-gray-500">Optional: Technical specifications, requirements, or configuration details</p>
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
                <a href="{{ route('admin.assetlifecycle.request-asset') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-medium py-2 px-6 rounded-lg transition-colors">
                    Cancel
                </a>
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-6 rounded-lg transition-colors">
                    <i class='bx bx-save mr-2'></i>
                    Create Request
                </button>
            </div>
        </form>
    </div>
</div>

<script>
// Validate required date is after request date
document.addEventListener('DOMContentLoaded', function() {
    const requestDate = document.getElementById('request_date');
    const requiredDate = document.getElementById('required_date');
    
    function validateDates() {
        if (requestDate.value && requiredDate.value) {
            if (new Date(requiredDate.value) <= new Date(requestDate.value)) {
                requiredDate.setCustomValidity('Required date must be after request date');
            } else {
                requiredDate.setCustomValidity('');
            }
        }
    }
    
    requestDate.addEventListener('change', validateDates);
    requiredDate.addEventListener('change', validateDates);
});
</script>
@endsection
