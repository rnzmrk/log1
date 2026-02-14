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
                    <a href="#" class="text-gray-700 hover:text-blue-600 ml-1 md:ml-2">Document Tracking</a>
                </div>
            </li>
            <li>
                <div class="flex items-center">
                    <i class='bx bx-chevron-right text-gray-400'></i>
                    <a href="{{ route('admin.documenttracking.upload-document-tracking') }}" class="text-gray-700 hover:text-blue-600 ml-1 md:ml-2">Contracts & Reports</a>
                </div>
            </li>
            <li aria-current="page">
                <div class="flex items-center">
                    <i class='bx bx-chevron-right text-gray-400'></i>
                    <span class="ml-1 text-gray-500 md:ml-2">New Contract</span>
                </div>
            </li>
        </ol>
    </nav>
    
    <!-- Page Header -->
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-900">Create New Contract</h1>
        <a href="{{ route('admin.documenttracking.upload-document-tracking') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-medium py-2 px-4 rounded-lg flex items-center gap-2 transition-colors">
            <i class='bx bx-arrow-back text-xl'></i>
            Back to Contracts
        </a>
    </div>

    <!-- Form Container -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-lg font-semibold text-gray-900">Contract Information</h2>
        </div>
        
        <form method="POST" action="{{ route('contracts.store') }}" class="p-6">
            @csrf
            
            <!-- Error Messages -->
            @if ($errors->any())
                <div class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg mb-6">
                    <div class="flex items-center mb-2">
                        <i class='bx bx-error-circle text-xl mr-2'></i>
                        <span class="font-medium">Please fix the following errors:</span>
                    </div>
                    <ul class="list-disc list-inside space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Basic Information -->
            <div class="space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Contract Name -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Contract Name *</label>
                        <input type="text" 
                               name="contract_name" 
                               value="{{ old('contract_name') }}"
                               required
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        @error('contract_name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Contract Number -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Contract Number</label>
                        <input type="text" 
                               name="contract_number" 
                               value="{{ old('contract_number') }}"
                               placeholder="Auto-generated if left blank"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        @error('contract_number')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Vendor -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Vendor *</label>
                        <input type="text" 
                               name="vendor" 
                               value="{{ old('vendor') }}"
                               required
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        @error('vendor')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Vendor Contact -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Vendor Contact</label>
                        <input type="text" 
                               name="vendor_contact" 
                               value="{{ old('vendor_contact') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        @error('vendor_contact')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <!-- Contract Type -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Contract Type *</label>
                        <select name="contract_type" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="">Select Type</option>
                            <option value="Service" {{ old('contract_type') === 'Service' ? 'selected' : '' }}>Service</option>
                            <option value="Supply" {{ old('contract_type') === 'Supply' ? 'selected' : '' }}>Supply</option>
                            <option value="Consultancy" {{ old('contract_type') === 'Consultancy' ? 'selected' : '' }}>Consultancy</option>
                            <option value="Maintenance" {{ old('contract_type') === 'Maintenance' ? 'selected' : '' }}>Maintenance</option>
                            <option value="Lease" {{ old('contract_type') === 'Lease' ? 'selected' : '' }}>Lease</option>
                            <option value="Other" {{ old('contract_type') === 'Other' ? 'selected' : '' }}>Other</option>
                        </select>
                        @error('contract_type')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Contract Value -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Contract Value</label>
                        <input type="number" 
                               name="contract_value" 
                               value="{{ old('contract_value') }}"
                               step="0.01"
                               min="0"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        @error('contract_value')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Priority -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Priority *</label>
                        <select name="priority" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="">Select Priority</option>
                            <option value="Low" {{ old('priority') === 'Low' ? 'selected' : '' }}>Low</option>
                            <option value="Medium" {{ old('priority') === 'Medium' ? 'selected' : '' }}>Medium</option>
                            <option value="High" {{ old('priority') === 'High' ? 'selected' : '' }}>High</option>
                        </select>
                        @error('priority')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Date Information -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Start Date -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Start Date *</label>
                        <input type="date" 
                               name="start_date" 
                               value="{{ old('start_date') }}"
                               required
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        @error('start_date')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- End Date -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">End Date *</label>
                        <input type="date" 
                               name="end_date" 
                               value="{{ old('end_date') }}"
                               required
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        @error('end_date')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Description -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                    <textarea name="description" 
                              rows="4"
                              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">{{ old('description') }}</textarea>
                    @error('description')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Terms and Conditions -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Terms and Conditions</label>
                    <textarea name="terms_conditions" 
                              rows="4"
                              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">{{ old('terms_conditions') }}</textarea>
                    @error('terms_conditions')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Status -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Status *</label>
                    <select name="status" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="">Select Status</option>
                        <option value="Pending" {{ old('status') === 'Pending' ? 'selected' : '' }}>Pending</option>
                        <option value="Active" {{ old('status') === 'Active' ? 'selected' : '' }}>Active</option>
                        <option value="Expired" {{ old('status') === 'Expired' ? 'selected' : '' }}>Expired</option>
                        <option value="Terminated" {{ old('status') === 'Terminated' ? 'selected' : '' }}>Terminated</option>
                        <option value="Renewed" {{ old('status') === 'Renewed' ? 'selected' : '' }}>Renewed</option>
                    </select>
                    @error('status')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Form Actions -->
            <div class="flex gap-3 pt-6 border-t border-gray-200 mt-6">
                <a href="{{ route('admin.documenttracking.upload-document-tracking') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-medium py-2 px-6 rounded-lg transition-colors">
                    <i class='bx bx-x mr-2'></i>
                    Cancel
                </a>
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-6 rounded-lg transition-colors">
                    <i class='bx bx-save mr-2'></i>
                    Create Contract
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
