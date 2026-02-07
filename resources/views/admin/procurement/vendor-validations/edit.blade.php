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
                    <a href="{{ route('procurement.vendors') }}" class="text-gray-700 hover:text-blue-600 ml-1 md:ml-2">Vendors</a>
                </div>
            </li>
            <li>
                <div class="flex items-center">
                    <i class='bx bx-chevron-right text-gray-400'></i>
                    <a href="{{ route('vendors.show', $supplier->id) }}" class="text-gray-700 hover:text-blue-600 ml-1 md:ml-2">{{ $supplier->name }}</a>
                </div>
            </li>
            <li>
                <div class="flex items-center">
                    <i class='bx bx-chevron-right text-gray-400'></i>
                    <a href="{{ route('vendors.validations.index', $supplier->id) }}" class="text-gray-700 hover:text-blue-600 ml-1 md:ml-2">Validations</a>
                </div>
            </li>
            <li aria-current="page">
                <div class="flex items-center">
                    <i class='bx bx-chevron-right text-gray-400'></i>
                    <span class="ml-1 text-gray-500 md:ml-2">Edit Validation</span>
                </div>
            </li>
        </ol>
    </nav>

    <!-- Page Header -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Edit Validation</h1>
            <p class="text-gray-600 mt-1">Update document validation for {{ $supplier->name }}</p>
        </div>
        <a href="{{ route('vendors.validations.index', $supplier->id) }}" class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-medium py-2 px-4 rounded-lg flex items-center gap-2 transition-colors">
            <i class='bx bx-arrow-back text-xl'></i>
            Back to Validations
        </a>
    </div>

    <!-- Form -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
        <form method="POST" action="{{ route('vendors.validations.update', [$supplier->id, $validation->id]) }}">
            @csrf
            @method('PUT')
            <div class="p-6 space-y-6">
                <!-- Basic Information -->
                <div>
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Document Information</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="validation_type" class="block text-sm font-medium text-gray-700 mb-2">Document Type *</label>
                            <select name="validation_type" 
                                    id="validation_type" 
                                    required
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                <option value="">Select Document Type</option>
                                @foreach($validationTypes as $key => $type)
                                    <option value="{{ $key }}" {{ old('validation_type', $validation->validation_type) === $key ? 'selected' : '' }}>{{ $type }}</option>
                                @endforeach
                            </select>
                            @error('validation_type')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="document_number" class="block text-sm font-medium text-gray-700 mb-2">Document Number</label>
                            <input type="text" 
                                   name="document_number" 
                                   id="document_number" 
                                   value="{{ old('document_number', $validation->document_number) }}"
                                   placeholder="e.g., LIC-2024-001"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            @error('document_number')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="issue_date" class="block text-sm font-medium text-gray-700 mb-2">Issue Date</label>
                            <input type="date" 
                                   name="issue_date" 
                                   id="issue_date" 
                                   value="{{ old('issue_date', $validation->issue_date ? $validation->issue_date->format('Y-m-d') : '') }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            @error('issue_date')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="expiry_date" class="block text-sm font-medium text-gray-700 mb-2">Expiry Date</label>
                            <input type="date" 
                                   name="expiry_date" 
                                   id="expiry_date" 
                                   value="{{ old('expiry_date', $validation->expiry_date ? $validation->expiry_date->format('Y-m-d') : '') }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            @error('expiry_date')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Status *</label>
                            <select name="status" 
                                    id="status" 
                                    required
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                <option value="">Select Status</option>
                                <option value="pending" {{ old('status', $validation->status) === 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="valid" {{ old('status', $validation->status) === 'valid' ? 'selected' : '' }}>Valid</option>
                                <option value="expired" {{ old('status', $validation->status) === 'expired' ? 'selected' : '' }}>Expired</option>
                                <option value="rejected" {{ old('status', $validation->status) === 'rejected' ? 'selected' : '' }}>Rejected</option>
                            </select>
                            @error('status')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="document_path" class="block text-sm font-medium text-gray-700 mb-2">Document Path</label>
                            <input type="text" 
                                   name="document_path" 
                                   id="document_path" 
                                   value="{{ old('document_path', $validation->document_path) }}"
                                   placeholder="e.g., /documents/validations/license.pdf"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            @error('document_path')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="md:col-span-2">
                            <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">Notes</label>
                            <textarea name="notes" 
                                      id="notes" 
                                      rows="3"
                                      placeholder="Additional notes about this validation..."
                                      class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">{{ old('notes', $validation->notes) }}</textarea>
                            @error('notes')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Validation Information -->
                @if($validation->validated_at)
                    <div class="bg-blue-50 p-4 rounded-lg">
                        <h4 class="text-sm font-medium text-blue-900 mb-2">Validation Information</h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                            <div>
                                <span class="text-blue-700">Validated By:</span>
                                <span class="font-medium text-blue-900 ml-2">{{ $validation->validator ? $validation->validator->name : 'Unknown' }}</span>
                            </div>
                            <div>
                                <span class="text-blue-700">Validated At:</span>
                                <span class="font-medium text-blue-900 ml-2">{{ $validation->validated_at->format('M d, Y H:i') }}</span>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Supplier Information -->
                <div class="bg-gray-50 p-4 rounded-lg">
                    <h4 class="text-sm font-medium text-gray-900 mb-2">Supplier Information</h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                        <div>
                            <span class="text-gray-600">Name:</span>
                            <span class="font-medium text-gray-900 ml-2">{{ $supplier->name }}</span>
                        </div>
                        <div>
                            <span class="text-gray-600">Vendor Code:</span>
                            <span class="font-medium text-gray-900 ml-2">{{ $supplier->vendor_code }}</span>
                        </div>
                        <div>
                            <span class="text-gray-600">Contact:</span>
                            <span class="font-medium text-gray-900 ml-2">{{ $supplier->contact_person ?? 'N/A' }}</span>
                        </div>
                        <div>
                            <span class="text-gray-600">Email:</span>
                            <span class="font-medium text-gray-900 ml-2">{{ $supplier->email ?? 'N/A' }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 flex justify-end gap-3">
                <a href="{{ route('vendors.validations.index', $supplier->id) }}" class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-medium py-2 px-6 rounded-lg transition-colors">
                    Cancel
                </a>
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-6 rounded-lg transition-colors">
                    <i class='bx bx-save mr-2'></i>
                    Update Validation
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
