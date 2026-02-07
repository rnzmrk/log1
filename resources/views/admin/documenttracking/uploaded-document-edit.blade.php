@extends('layouts.app')

@section('content')
<div class="container-fluid px-6 py-8">
    <!-- Breadcrumbs -->
    <nav class="flex mb-6" aria-label="Breadcrumb">
        <ol class="inline-flex items-center space-x-1 md:space-x-3">
            <li class="inline-flex items-center">
                <a href="#" class="text-gray-700 hover:text-blue-600 inline-flex items-center">
                    <i class='bx bx-home text-xl mr-2'></i>
                    Home
                </a>
            </li>
            <li>
                <div class="flex items-center">
                    <i class='bx bx-chevron-right text-gray-400'></i>
                    <a href="{{ route('admin.documenttracking.upload-document-tracking') }}" class="text-gray-700 hover:text-blue-600 ml-1 md:ml-2">Document Tracking</a>
                </div>
            </li>
            <li>
                <div class="flex items-center">
                    <i class='bx bx-chevron-right text-gray-400'></i>
                    <a href="{{ route('admin.documenttracking.upload-document-tracking') }}" class="text-gray-700 hover:text-blue-600 ml-1 md:ml-2">Upload Document & Tracking</a>
                </div>
            </li>
            <li>
                <div class="flex items-center">
                    <i class='bx bx-chevron-right text-gray-400'></i>
                    <a href="{{ route('uploaded-documents.show', $uploadedDocument->id) }}" class="text-gray-700 hover:text-blue-600 ml-1 md:ml-2">Document Details</a>
                </div>
            </li>
            <li aria-current="page">
                <div class="flex items-center">
                    <i class='bx bx-chevron-right text-gray-400'></i>
                    <span class="ml-1 text-gray-500 md:ml-2">Edit Document</span>
                </div>
            </li>
        </ol>
    </nav>

    <!-- Page Header -->
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-900">Edit Document</h1>
        <div class="flex gap-3">
            <a href="{{ route('uploaded-documents.show', $uploadedDocument->id) }}" class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-medium py-2 px-4 rounded-lg flex items-center gap-2 transition-colors">
                <i class='bx bx-arrow-left text-xl'></i>
                Back to Details
            </a>
            <a href="{{ route('admin.documenttracking.upload-document-tracking') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-medium py-2 px-4 rounded-lg flex items-center gap-2 transition-colors">
                <i class='bx bx-list text-xl'></i>
                View All Documents
            </a>
        </div>
    </div>

    <!-- Success Messages -->
    @if(session('success'))
        <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg mb-6">
            <div class="flex items-center">
                <i class='bx bx-check-circle text-xl mr-2'></i>
                <span>{{ session('success') }}</span>
            </div>
        </div>
    @endif

    <!-- Edit Form -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-lg font-semibold text-gray-900">Edit Document Information</h2>
        </div>
        
        <form method="POST" action="{{ route('uploaded-documents.update', $uploadedDocument->id) }}" class="p-6 space-y-6">
            @csrf
            @method('PUT')
            
            <!-- Error Messages -->
            @if ($errors->any())
                <div class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg">
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

            <!-- Document Information -->
            <div class="space-y-4">
                <h3 class="text-md font-semibold text-gray-900">Document Information</h3>
                
                <!-- Document Title -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Document Title *</label>
                    <input type="text" 
                           name="document_title"
                           value="{{ old('document_title', $uploadedDocument->document_title) }}"
                           placeholder="Enter document title" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    @error('document_title')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Description -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Description *</label>
                    <textarea name="description" 
                              rows="3"
                              placeholder="Provide description of the document..." 
                              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">{{ old('description', $uploadedDocument->description) }}</textarea>
                    @error('description')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Document Type and Category -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Document Type *</label>
                        <select name="document_type" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="">Select Type</option>
                            <option value="Contract" {{ old('document_type', $uploadedDocument->document_type) === 'Contract' ? 'selected' : '' }}>Contract</option>
                            <option value="Invoice" {{ old('document_type', $uploadedDocument->document_type) === 'Invoice' ? 'selected' : '' }}>Invoice</option>
                            <option value="Receipt" {{ old('document_type', $uploadedDocument->document_type) === 'Receipt' ? 'selected' : '' }}>Receipt</option>
                            <option value="Report" {{ old('document_type', $uploadedDocument->document_type) === 'Report' ? 'selected' : '' }}>Report</option>
                            <option value="Certificate" {{ old('document_type', $uploadedDocument->document_type) === 'Certificate' ? 'selected' : '' }}>Certificate</option>
                            <option value="License" {{ old('document_type', $uploadedDocument->document_type) === 'License' ? 'selected' : '' }}>License</option>
                            <option value="Permit" {{ old('document_type', $uploadedDocument->document_type) === 'Permit' ? 'selected' : '' }}>Permit</option>
                            <option value="Identification" {{ old('document_type', $uploadedDocument->document_type) === 'Identification' ? 'selected' : '' }}>Identification</option>
                            <option value="Other" {{ old('document_type', $uploadedDocument->document_type) === 'Other' ? 'selected' : '' }}>Other</option>
                        </select>
                        @error('document_type')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Category *</label>
                        <select name="category" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="">Select Category</option>
                            <option value="Legal" {{ old('category', $uploadedDocument->category) === 'Legal' ? 'selected' : '' }}>Legal</option>
                            <option value="Financial" {{ old('category', $uploadedDocument->category) === 'Financial' ? 'selected' : '' }}>Financial</option>
                            <option value="HR" {{ old('category', $uploadedDocument->category) === 'HR' ? 'selected' : '' }}>HR</option>
                            <option value="Operations" {{ old('category', $uploadedDocument->category) === 'Operations' ? 'selected' : '' }}>Operations</option>
                            <option value="Compliance" {{ old('category', $uploadedDocument->category) === 'Compliance' ? 'selected' : '' }}>Compliance</option>
                            <option value="Technical" {{ old('category', $uploadedDocument->category) === 'Technical' ? 'selected' : '' }}>Technical</option>
                            <option value="Administrative" {{ old('category', $uploadedDocument->category) === 'Administrative' ? 'selected' : '' }}>Administrative</option>
                        </select>
                        @error('category')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Tracking Information -->
            <div class="space-y-4">
                <h3 class="text-md font-semibold text-gray-900">Tracking Information</h3>
                
                <!-- Reference Number -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Reference Number</label>
                    <input type="text" 
                           name="reference_number"
                           value="{{ old('reference_number', $uploadedDocument->reference_number) }}"
                           placeholder="Enter reference number" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    @error('reference_number')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Tags and Keywords -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Tags and Keywords</label>
                    <input type="text" 
                           name="tags"
                           value="{{ old('tags', $uploadedDocument->tags) }}"
                           placeholder="Enter tags separated by commas" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    @error('tags')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Expiration Date -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Effective Date</label>
                        <input type="date" 
                               name="effective_date"
                               value="{{ old('effective_date', $uploadedDocument->effective_date?->format('Y-m-d')) }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        @error('effective_date')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Expiration Date</label>
                        <input type="date" 
                               name="expiration_date"
                               value="{{ old('expiration_date', $uploadedDocument->expiration_date?->format('Y-m-d')) }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        @error('expiration_date')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Status -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                    <select name="status" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="Active" {{ old('status', $uploadedDocument->status) === 'Active' ? 'selected' : '' }}>Active</option>
                        <option value="Processing" {{ old('status', $uploadedDocument->status) === 'Processing' ? 'selected' : '' }}>Processing</option>
                        <option value="Archived" {{ old('status', $uploadedDocument->status) === 'Archived' ? 'selected' : '' }}>Archived</option>
                        <option value="Expired" {{ old('status', $uploadedDocument->status) === 'Expired' ? 'selected' : '' }}>Expired</option>
                        <option value="Restricted" {{ old('status', $uploadedDocument->status) === 'Restricted' ? 'selected' : '' }}>Restricted</option>
                    </select>
                    @error('status')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Access Control -->
            <div class="space-y-4">
                <h3 class="text-md font-semibold text-gray-900">Access Control</h3>
                
                <!-- Visibility -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Visibility *</label>
                    <select name="visibility" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="">Select Visibility</option>
                        <option value="Public" {{ old('visibility', $uploadedDocument->visibility) === 'Public' ? 'selected' : '' }}>Public</option>
                        <option value="Internal" {{ old('visibility', $uploadedDocument->visibility) === 'Internal' ? 'selected' : '' }}>Internal</option>
                        <option value="Restricted" {{ old('visibility', $uploadedDocument->visibility) === 'Restricted' ? 'selected' : '' }}>Restricted</option>
                        <option value="Confidential" {{ old('visibility', $uploadedDocument->visibility) === 'Confidential' ? 'selected' : '' }}>Confidential</option>
                    </select>
                    @error('visibility')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Access Permissions -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Access Permissions</label>
                    <div class="space-y-2">
                        <label class="flex items-center">
                            <input type="checkbox" name="view_only_access" value="1" {{ old('view_only_access', $uploadedDocument->view_only_access) ? 'checked' : '' }} class="mr-2 rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                            <span class="text-sm text-gray-700">View Only Access</span>
                        </label>
                        <label class="flex items-center">
                            <input type="checkbox" name="download_permission" value="1" {{ old('download_permission', $uploadedDocument->download_permission) ? 'checked' : '' }} class="mr-2 rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                            <span class="text-sm text-gray-700">Download Permission</span>
                        </label>
                        <label class="flex items-center">
                            <input type="checkbox" name="edit_permission" value="1" {{ old('edit_permission', $uploadedDocument->edit_permission) ? 'checked' : '' }} class="mr-2 rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                            <span class="text-sm text-gray-700">Edit Permission</span>
                        </label>
                        <label class="flex items-center">
                            <input type="checkbox" name="share_permission" value="1" {{ old('share_permission', $uploadedDocument->share_permission) ? 'checked' : '' }} class="mr-2 rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                            <span class="text-sm text-gray-700">Share Permission</span>
                        </label>
                    </div>
                </div>

                <!-- Authorized Users -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Authorized Users</label>
                    <textarea name="authorized_users" 
                              rows="2"
                              placeholder="Enter email addresses separated by commas" 
                              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">{{ old('authorized_users', $uploadedDocument->authorized_users ? implode(', ', $uploadedDocument->authorized_users) : '') }}</textarea>
                    @error('authorized_users')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- File Information (Read-only) -->
            <div class="space-y-4">
                <h3 class="text-md font-semibold text-gray-900">File Information</h3>
                <div class="bg-gray-50 p-4 rounded-lg">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">File Name</label>
                            <p class="text-sm text-gray-900">{{ $uploadedDocument->file_name }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">File Size</label>
                            <p class="text-sm text-gray-900">{{ $uploadedDocument->file_size_in_mb }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">File Type</label>
                            <p class="text-sm text-gray-900">{{ $uploadedDocument->file_type }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Upload Date</label>
                            <p class="text-sm text-gray-900">{{ $uploadedDocument->upload_date->format('M d, Y h:i A') }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Submit Actions -->
            <div class="flex gap-3 pt-4 border-t border-gray-200">
                <a href="{{ route('uploaded-documents.show', $uploadedDocument->id) }}" class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-medium py-2 px-6 rounded-lg transition-colors">
                    <i class='bx bx-x mr-2'></i>
                    Cancel
                </a>
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-6 rounded-lg transition-colors">
                    <i class='bx bx-save mr-2'></i>
                    Save Changes
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
