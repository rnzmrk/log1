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
                    <a href="{{ route('admin.procurement.create-contract-reports') }}" class="text-gray-700 hover:text-blue-600 ml-1 md:ml-2">Contracts & Reports</a>
                </div>
            </li>
            <li aria-current="page">
                <div class="flex items-center">
                    <i class='bx bx-chevron-right text-gray-400'></i>
                    <span class="ml-1 text-gray-500 md:ml-2">Edit Contract</span>
                </div>
            </li>
        </ol>
    </nav>

    <!-- Page Header -->
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-900">Edit Contract</h1>
        <a href="{{ route('admin.procurement.create-contract-reports') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-medium py-2 px-4 rounded-lg flex items-center gap-2 transition-colors">
            <i class='bx bx-arrow-back text-xl'></i>
            Back to Contracts
        </a>
    </div>

    <!-- Form Container -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-lg font-semibold text-gray-900">Edit Contract Information</h2>
        </div>
        
        <form method="POST" action="{{ route('contracts.update', $contract->id) }}" class="p-6">
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
                <!-- Contract Name -->
                <div>
                    <label for="contract_name" class="block text-sm font-medium text-gray-700 mb-2">Contract Name *</label>
                    <input type="text" 
                           id="contract_name" 
                           name="contract_name" 
                           value="{{ old('contract_name', $contract->contract_name) }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                           placeholder="e.g., Office Supplies Annual Contract"
                           required>
                </div>

                <!-- Contract Number -->
                <div>
                    <label for="contract_number" class="block text-sm font-medium text-gray-700 mb-2">Contract Number *</label>
                    <input type="text" 
                           id="contract_number" 
                           name="contract_number" 
                           value="{{ old('contract_number', $contract->contract_number) }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                           placeholder="e.g., CTR-2024-0001"
                           required>
                </div>

                <!-- Vendor -->
                <div>
                    <label for="vendor" class="block text-sm font-medium text-gray-700 mb-2">Vendor *</label>
                    <div class="relative">
                        <input type="text" 
                               id="vendor" 
                               name="vendor" 
                               value="{{ old('vendor', $contract->vendor) }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                               placeholder="Type vendor name or code..."
                               autocomplete="off"
                               required>
                        <div id="vendor-search-results" class="absolute z-10 w-full mt-1 bg-white border border-gray-300 rounded-lg shadow-lg hidden max-h-60 overflow-y-auto">
                            <!-- Search results will appear here -->
                        </div>
                    </div>
                    <p class="mt-1 text-sm text-gray-500">Search and select a vendor to auto-fill contact details</p>
                </div>

                <!-- Vendor Contact -->
                <div>
                    <label for="vendor_contact" class="block text-sm font-medium text-gray-700 mb-2">Vendor Contact</label>
                    <input type="text" 
                           id="vendor_contact" 
                           name="vendor_contact" 
                           value="{{ old('vendor_contact', $contract->vendor_contact) }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                           placeholder="e.g., John Smith">
                    <p class="mt-1 text-sm text-gray-500">Optional: Contact person name</p>
                </div>

                <!-- Vendor Email -->
                <div>
                    <label for="vendor_email" class="block text-sm font-medium text-gray-700 mb-2">Vendor Email</label>
                    <input type="email" 
                           id="vendor_email" 
                           name="vendor_email" 
                           value="{{ old('vendor_email', $contract->vendor_email) }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                           placeholder="e.g., john@vendor.com">
                    <p class="mt-1 text-sm text-gray-500">Optional: Contact email</p>
                </div>

                <!-- Vendor Phone -->
                <div>
                    <label for="vendor_phone" class="block text-sm font-medium text-gray-700 mb-2">Vendor Phone</label>
                    <input type="text" 
                           id="vendor_phone" 
                           name="vendor_phone" 
                           value="{{ old('vendor_phone', $contract->vendor_phone) }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                           placeholder="e.g., +1 (555) 123-4567">
                    <p class="mt-1 text-sm text-gray-500">Optional: Contact phone number</p>
                </div>

                <!-- Contract Type -->
                <div>
                    <label for="contract_type" class="block text-sm font-medium text-gray-700 mb-2">Contract Type *</label>
                    <select id="contract_type" 
                            name="contract_type" 
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            required>
                        <option value="">Select contract type</option>
                        <option value="Service" {{ old('contract_type', $contract->contract_type) === 'Service' ? 'selected' : '' }}>Service</option>
                        <option value="Supply" {{ old('contract_type', $contract->contract_type) === 'Supply' ? 'selected' : '' }}>Supply</option>
                        <option value="Maintenance" {{ old('contract_type', $contract->contract_type) === 'Maintenance' ? 'selected' : '' }}>Maintenance</option>
                        <option value="Consulting" {{ old('contract_type', $contract->contract_type) === 'Consulting' ? 'selected' : '' }}>Consulting</option>
                        <option value="Software License" {{ old('contract_type', $contract->contract_type) === 'Software License' ? 'selected' : '' }}>Software License</option>
                        <option value="Hardware Lease" {{ old('contract_type', $contract->contract_type) === 'Hardware Lease' ? 'selected' : '' }}>Hardware Lease</option>
                        <option value="Other" {{ old('contract_type', $contract->contract_type) === 'Other' ? 'selected' : '' }}>Other</option>
                    </select>
                </div>

                <!-- Contract Value -->
                <div>
                    <label for="contract_value" class="block text-sm font-medium text-gray-700 mb-2">Contract Value *</label>
                    <input type="number" 
                           id="contract_value" 
                           name="contract_value" 
                           value="{{ old('contract_value', $contract->contract_value) }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                           placeholder="0.00"
                           step="0.01"
                           min="0"
                           required>
                    <p class="mt-1 text-sm text-gray-500">Total contract value in PHP</p>
                </div>

                <!-- Status -->
                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Status *</label>
                    <select id="status" 
                            name="status" 
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            required>
                        <option value="">Select status</option>
                        <option value="Draft" {{ old('status', $contract->status) === 'Draft' ? 'selected' : '' }}>Draft</option>
                        <option value="Under Review" {{ old('status', $contract->status) === 'Under Review' ? 'selected' : '' }}>Under Review</option>
                        <option value="Active" {{ old('status', $contract->status) === 'Active' ? 'selected' : '' }}>Active</option>
                        <option value="Expired" {{ old('status', $contract->status) === 'Expired' ? 'selected' : '' }}>Expired</option>
                        <option value="Terminated" {{ old('status', $contract->status) === 'Terminated' ? 'selected' : '' }}>Terminated</option>
                        <option value="Renewed" {{ old('status', $contract->status) === 'Renewed' ? 'selected' : '' }}>Renewed</option>
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
                        <option value="Low" {{ old('priority', $contract->priority) === 'Low' ? 'selected' : '' }}>Low</option>
                        <option value="Medium" {{ old('priority', $contract->priority) === 'Medium' ? 'selected' : '' }}>Medium</option>
                        <option value="High" {{ old('priority', $contract->priority) === 'High' ? 'selected' : '' }}>High</option>
                        <option value="Urgent" {{ old('priority', $contract->priority) === 'Urgent' ? 'selected' : '' }}>Urgent</option>
                    </select>
                </div>

                <!-- Start Date -->
                <div>
                    <label for="start_date" class="block text-sm font-medium text-gray-700 mb-2">Start Date *</label>
                    <input type="date" 
                           id="start_date" 
                           name="start_date" 
                           value="{{ old('start_date', $contract->start_date->format('Y-m-d')) }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                           required>
                </div>

                <!-- End Date -->
                <div>
                    <label for="end_date" class="block text-sm font-medium text-gray-700 mb-2">End Date *</label>
                    <input type="date" 
                           id="end_date" 
                           name="end_date" 
                           value="{{ old('end_date', $contract->end_date->format('Y-m-d')) }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                           required>
                    <p class="mt-1 text-sm text-gray-500">Must be after start date</p>
                </div>

                <!-- Renewal Date -->
                <div>
                    <label for="renewal_date" class="block text-sm font-medium text-gray-700 mb-2">Renewal Date</label>
                    <input type="date" 
                           id="renewal_date" 
                           name="renewal_date" 
                           value="{{ old('renewal_date', $contract->renewal_date ? $contract->renewal_date->format('Y-m-d') : '') }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <p class="mt-1 text-sm text-gray-500">Optional: Auto-filled when contract is renewed</p>
                </div>

                <!-- Auto Renewal -->
                <div class="flex items-center pt-6">
                    <input type="checkbox" 
                           id="auto_renewal" 
                           name="auto_renewal" 
                           value="1"
                           {{ old('auto_renewal', $contract->auto_renewal) ? 'checked' : '' }}
                           class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                    <label for="auto_renewal" class="ml-2 block text-sm text-gray-900">
                        Enable Auto-Renewal
                    </label>
                </div>

                <!-- Renewal Terms -->
                <div class="md:col-span-2">
                    <label for="renewal_terms" class="block text-sm font-medium text-gray-700 mb-2">Renewal Terms</label>
                    <textarea id="renewal_terms" 
                              name="renewal_terms" 
                              rows="3"
                              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                              placeholder="Enter renewal terms and conditions...">{{ old('renewal_terms', $contract->renewal_terms) }}</textarea>
                    <p class="mt-1 text-sm text-gray-500">Optional: Specific terms for contract renewal</p>
                </div>

                <!-- Created By -->
                <div>
                    <label for="created_by" class="block text-sm font-medium text-gray-700 mb-2">Created By *</label>
                    <input type="text" 
                           id="created_by" 
                           name="created_by" 
                           value="{{ old('created_by', $contract->created_by) }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-gray-50"
                           readonly>
                    <p class="mt-1 text-sm text-gray-500">Original creator of the contract</p>
                </div>
            </div>

            <!-- Description -->
            <div class="mt-6">
                <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                <textarea id="description" 
                          name="description" 
                          rows="4"
                          class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                          placeholder="Enter contract description...">{{ old('description', $contract->description) }}</textarea>
                <p class="mt-1 text-sm text-gray-500">Optional: Brief description of the contract scope and purpose</p>
            </div>

            <!-- Terms & Conditions -->
            <div class="mt-6">
                <label for="terms_conditions" class="block text-sm font-medium text-gray-700 mb-2">Terms & Conditions</label>
                <textarea id="terms_conditions" 
                          name="terms_conditions" 
                          rows="4"
                          class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                          placeholder="Enter terms and conditions...">{{ old('terms_conditions', $contract->terms_conditions) }}</textarea>
                <p class="mt-1 text-sm text-gray-500">Optional: Key terms, conditions, and obligations</p>
            </div>

            <!-- Notes -->
            <div class="mt-6">
                <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">Notes</label>
                <textarea id="notes" 
                          name="notes" 
                          rows="4"
                          class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                          placeholder="Enter additional notes...">{{ old('notes', $contract->notes) }}</textarea>
                <p class="mt-1 text-sm text-gray-500">Optional: Additional information about the contract</p>
            </div>

            <!-- Form Actions -->
            <div class="flex justify-end gap-3 mt-8">
                <a href="{{ route('admin.procurement.create-contract-reports') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-medium py-2 px-6 rounded-lg transition-colors">
                    Cancel
                </a>
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-6 rounded-lg transition-colors">
                    <i class='bx bx-save mr-2'></i>
                    Update Contract
                </button>
            </div>
        </form>
    </div>
</div>

<script>
let selectedVendor = null;
let searchTimeout;

// Vendor search functionality
document.getElementById('vendor').addEventListener('input', function(e) {
    const query = e.target.value.trim();
    const resultsDiv = document.getElementById('vendor-search-results');
    
    clearTimeout(searchTimeout);
    
    if (query.length < 2) {
        resultsDiv.classList.add('hidden');
        return;
    }
    
    searchTimeout = setTimeout(() => {
        fetch(`/contracts/search-vendors?q=${encodeURIComponent(query)}`)
            .then(response => response.json())
            .then(data => {
                if (data.length === 0) {
                    resultsDiv.innerHTML = '<div class="p-4 text-gray-500 text-center">No vendors found</div>';
                } else {
                    resultsDiv.innerHTML = data.map(vendor => `
                        <div class="vendor-result px-4 py-3 hover:bg-gray-50 cursor-pointer border-b border-gray-100 last:border-b-0" 
                             data-vendor-name="${vendor.name}"
                             data-contact-person="${vendor.contact_person || ''}"
                             data-email="${vendor.email || ''}"
                             data-phone="${vendor.phone || ''}"
                             data-address="${vendor.address || ''}"
                             data-city="${vendor.city || ''}"
                             data-state="${vendor.state || ''}"
                             data-country="${vendor.country || ''}">
                            <div class="font-medium text-gray-900">${vendor.name}</div>
                            <div class="text-sm text-gray-500">${vendor.vendor_code || 'No code'}</div>
                            ${vendor.contact_person ? `<div class="text-sm text-gray-600">${vendor.contact_person}</div>` : ''}
                        </div>
                    `).join('');
                }
                resultsDiv.classList.remove('hidden');
            })
            .catch(error => {
                console.error('Error searching vendors:', error);
                resultsDiv.innerHTML = '<div class="p-4 text-red-500 text-center">Error searching vendors</div>';
                resultsDiv.classList.remove('hidden');
            });
    }, 300);
});

// Handle vendor selection
document.getElementById('vendor-search-results').addEventListener('click', function(e) {
    const vendorResult = e.target.closest('.vendor-result');
    if (vendorResult) {
        selectedVendor = {
            name: vendorResult.dataset.vendorName,
            contact_person: vendorResult.dataset.contactPerson,
            email: vendorResult.dataset.email,
            phone: vendorResult.dataset.phone,
            address: vendorResult.dataset.address,
            city: vendorResult.dataset.city,
            state: vendorResult.dataset.state,
            country: vendorResult.dataset.country
        };
        
        // Fill the vendor input and hide results
        document.getElementById('vendor').value = selectedVendor.name;
        document.getElementById('vendor-search-results').classList.add('hidden');
        
        // Auto-fill vendor contact fields
        autoFillVendorFields();
    }
});

// Auto-fill vendor contact fields
function autoFillVendorFields() {
    if (!selectedVendor) return;
    
    // Auto-fill contact person if empty
    if (selectedVendor.contact_person && !document.getElementById('vendor_contact').value) {
        document.getElementById('vendor_contact').value = selectedVendor.contact_person;
    }
    
    // Auto-fill email if empty
    if (selectedVendor.email && !document.getElementById('vendor_email').value) {
        document.getElementById('vendor_email').value = selectedVendor.email;
    }
    
    // Auto-fill phone if empty
    if (selectedVendor.phone && !document.getElementById('vendor_phone').value) {
        document.getElementById('vendor_phone').value = selectedVendor.phone;
    }
    
    // Auto-fill contract name if empty
    if (!document.getElementById('contract_name').value) {
        document.getElementById('contract_name').value = `Contract with ${selectedVendor.name}`;
    }
}

// Hide search results when clicking outside
document.addEventListener('click', function(e) {
    if (!e.target.closest('#vendor') && !e.target.closest('#vendor-search-results')) {
        document.getElementById('vendor-search-results').classList.add('hidden');
    }
});

// Clear vendor selection if search is manually cleared
document.getElementById('vendor').addEventListener('blur', function() {
    if (this.value.trim() === '') {
        selectedVendor = null;
    }
});

// Validate end date is after start date
document.addEventListener('DOMContentLoaded', function() {
    const startDate = document.getElementById('start_date');
    const endDate = document.getElementById('end_date');
    const renewalDate = document.getElementById('renewal_date');
    
    function validateDates() {
        if (startDate.value && endDate.value) {
            if (new Date(endDate.value) <= new Date(startDate.value)) {
                endDate.setCustomValidity('End date must be after start date');
            } else {
                endDate.setCustomValidity('');
            }
        }
        
        if (endDate.value && renewalDate.value) {
            if (new Date(renewalDate.value) <= new Date(endDate.value)) {
                renewalDate.setCustomValidity('Renewal date must be after end date');
            } else {
                renewalDate.setCustomValidity('');
            }
        }
    }
    
    startDate.addEventListener('change', validateDates);
    endDate.addEventListener('change', validateDates);
    renewalDate.addEventListener('change', validateDates);
});
</script>
@endsection
