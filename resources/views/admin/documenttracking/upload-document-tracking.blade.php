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
                    <a href="#" class="text-gray-700 hover:text-blue-600 ml-1 md:ml-2">Document Tracking</a>
                </div>
            </li>
            <li>
                <div class="flex items-center">
                    <i class='bx bx-chevron-right text-gray-400'></i>
                    <a href="#" class="text-gray-700 hover:text-blue-600 ml-1 md:ml-2">Upload Document & Tracking</a>
                </div>
            </li>
            <li aria-current="page">
                <div class="flex items-center">
                    <i class='bx bx-chevron-right text-gray-400'></i>
                    <span class="ml-1 text-gray-500 md:ml-2">Upload & Track</span>
                </div>
            </li>
        </ol>
    </nav>

    <!-- Page Header -->
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-900">Upload Document & Tracking</h1>
        <div class="flex gap-3">
            <a href="{{ route('admin.documenttracking.list-document-request') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-medium py-2 px-4 rounded-lg flex items-center gap-2 transition-colors">
                <i class='bx bx-arrow-left text-xl'></i>
                Back to List
            </a>
            <a href="{{ route('uploaded-documents.index') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg flex items-center gap-2 transition-colors">
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

    <!-- Error Messages -->
    @if(session('error'))
        <div class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg mb-6">
            <div class="flex items-center">
                <i class='bx bx-error-circle text-xl mr-2'></i>
                <span>{{ session('error') }}</span>
            </div>
        </div>
    @endif

    <!-- Upload & Tracking Container -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Form -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-900">Document Upload</h2>
                </div>
                <form method="POST" action="{{ route('uploaded-documents.store') }}" enctype="multipart/form-data" class="p-6 space-y-6">
                    @csrf
                    
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
                                   value="{{ old('document_title') }}"
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
                                      class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">{{ old('description') }}</textarea>
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
                                    <option value="Contract" {{ old('document_type') === 'Contract' ? 'selected' : '' }}>Contract</option>
                                    <option value="Invoice" {{ old('document_type') === 'Invoice' ? 'selected' : '' }}>Invoice</option>
                                    <option value="Receipt" {{ old('document_type') === 'Receipt' ? 'selected' : '' }}>Receipt</option>
                                    <option value="Report" {{ old('document_type') === 'Report' ? 'selected' : '' }}>Report</option>
                                    <option value="Certificate" {{ old('document_type') === 'Certificate' ? 'selected' : '' }}>Certificate</option>
                                    <option value="License" {{ old('document_type') === 'License' ? 'selected' : '' }}>License</option>
                                    <option value="Permit" {{ old('document_type') === 'Permit' ? 'selected' : '' }}>Permit</option>
                                    <option value="Identification" {{ old('document_type') === 'Identification' ? 'selected' : '' }}>Identification</option>
                                    <option value="Other" {{ old('document_type') === 'Other' ? 'selected' : '' }}>Other</option>
                                </select>
                                @error('document_type')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Category *</label>
                                <select name="category" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                    <option value="">Select Category</option>
                                    <option value="Legal" {{ old('category') === 'Legal' ? 'selected' : '' }}>Legal</option>
                                    <option value="Financial" {{ old('category') === 'Financial' ? 'selected' : '' }}>Financial</option>
                                    <option value="HR" {{ old('category') === 'HR' ? 'selected' : '' }}>HR</option>
                                    <option value="Operations" {{ old('category') === 'Operations' ? 'selected' : '' }}>Operations</option>
                                    <option value="Compliance" {{ old('category') === 'Compliance' ? 'selected' : '' }}>Compliance</option>
                                    <option value="Technical" {{ old('category') === 'Technical' ? 'selected' : '' }}>Technical</option>
                                    <option value="Administrative" {{ old('category') === 'Administrative' ? 'selected' : '' }}>Administrative</option>
                                </select>
                                @error('category')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- File Upload Section -->
                    <div class="space-y-4">
                        <h3 class="text-md font-semibold text-gray-900">File Upload</h3>
                        
                        <!-- Upload Area -->
                        <div class="border-2 border-dashed border-gray-300 rounded-lg p-8 text-center hover:border-blue-400 transition-colors">
                            <div class="flex flex-col items-center">
                                <i class='bx bx-cloud-upload text-4xl text-gray-400 mb-3'></i>
                                <p class="text-lg font-medium text-gray-700 mb-2">Drop files here or click to upload</p>
                                <p class="text-sm text-gray-500 mb-4">Support for PDF, DOC, DOCX, XLS, XLSX, JPG, PNG (Max 10MB)</p>
                                <button type="button" class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-6 rounded-lg transition-colors" onclick="document.getElementById('fileInput').click()">
                                    <i class='bx bx-upload mr-2'></i>
                                    Choose Files
                                </button>
                                <input type="file" id="fileInput" name="files[]" multiple accept=".pdf,.doc,.docx,.xls,.xlsx,.jpg,.jpeg,.png" class="hidden" onchange="displayFiles(this.files)">
                            </div>
                        </div>

                        <!-- Uploaded Files List -->
                        <div id="filesList" class="space-y-2">
                            <!-- Files will be displayed here dynamically -->
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
                                   value="{{ old('reference_number') }}"
                                   placeholder="Auto-generated or manual entry" 
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
                                   value="{{ old('tags') }}"
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
                                       value="{{ old('effective_date') }}"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                @error('effective_date')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Expiration Date</label>
                                <input type="date" 
                                       name="expiration_date"
                                       value="{{ old('expiration_date') }}"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                @error('expiration_date')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
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
                                <option value="Public" {{ old('visibility') === 'Public' ? 'selected' : '' }}>Public</option>
                                <option value="Internal" {{ old('visibility') === 'Internal' ? 'selected' : '' }}>Internal</option>
                                <option value="Restricted" {{ old('visibility') === 'Restricted' ? 'selected' : '' }}>Restricted</option>
                                <option value="Confidential" {{ old('visibility') === 'Confidential' ? 'selected' : '' }}>Confidential</option>
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
                                    <input type="checkbox" name="view_only_access" value="1" {{ old('view_only_access') ? 'checked' : '' }} class="mr-2 rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                    <span class="text-sm text-gray-700">View Only Access</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="checkbox" name="download_permission" value="1" {{ old('download_permission') ? 'checked' : '' }} class="mr-2 rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                    <span class="text-sm text-gray-700">Download Permission</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="checkbox" name="edit_permission" value="1" {{ old('edit_permission') ? 'checked' : '' }} class="mr-2 rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                    <span class="text-sm text-gray-700">Edit Permission</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="checkbox" name="share_permission" value="1" {{ old('share_permission') ? 'checked' : '' }} class="mr-2 rounded border-gray-300 text-blue-600 focus:ring-blue-500">
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
                                      class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">{{ old('authorized_users') }}</textarea>
                            @error('authorized_users')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Submit Actions -->
                    <div class="flex gap-3 pt-4 border-t border-gray-200">
                        <a href="{{ route('admin.documenttracking.upload-document-tracking') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-medium py-2 px-6 rounded-lg transition-colors">
                            <i class='bx bx-x mr-2'></i>
                            Cancel
                        </a>
                        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-6 rounded-lg transition-colors">
                            <i class='bx bx-cloud-upload mr-2'></i>
                            Upload & Track
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Upload Summary -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-900">Upload Summary</h2>
                </div>
                <div class="p-6">
                    <div class="space-y-4">
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600">Document ID</span>
                            <span class="text-sm font-medium text-gray-900">Auto-generated</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600">Uploaded By</span>
                            <span class="text-sm font-medium text-gray-900">{{ auth()->user()->name ?? 'Admin User' }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600">Department</span>
                            <span class="text-sm font-medium text-gray-900">{{ auth()->user()->department ?? 'Operations' }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600">Upload Date</span>
                            <span class="text-sm font-medium text-gray-900">{{ now()->format('M d, Y') }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600">Status</span>
                            <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-orange-100 text-orange-800">
                                Processing
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Uploads -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-900">Recent Uploads</h2>
                </div>
                <div class="p-6">
                    <div class="space-y-4">
                        @forelse($recentUploads as $recent)
                        <div class="border border-gray-200 rounded-lg p-3">
                            <div class="flex items-center justify-between mb-2">
                                <h4 class="font-medium text-gray-900 text-sm">{{ $recent->document_title }}</h4>
                                <span class="text-xs bg-{{ $recent->status_color }}-100 text-{{ $recent->status_color }}-800 px-2 py-1 rounded-full">{{ $recent->status }}</span>
                            </div>
                            <p class="text-xs text-gray-600">Uploaded {{ $recent->upload_date->diffForHumans() }}</p>
                        </div>
                        @empty
                        <div class="text-center text-gray-500 py-4">
                            <i class='bx bx-file text-2xl text-gray-300 mb-2'></i>
                            <p class="text-sm">No recent uploads</p>
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Storage Information -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-900">Storage Information</h2>
                </div>
                <div class="p-6">
                    <div class="space-y-4">
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600">Total Storage</span>
                            <span class="text-sm font-medium text-gray-900">50 GB</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600">Used</span>
                            <span class="text-sm font-medium text-gray-900">32.4 GB</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600">Available</span>
                            <span class="text-sm font-medium text-gray-900">17.6 GB</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div class="bg-blue-500 h-2 rounded-full" style="width: 65%"></div>
                        </div>
                        <p class="text-xs text-gray-600 text-center">65% storage used</p>
                    </div>
                </div>
            </div>

            <!-- Help & Support -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-900">Help & Support</h2>
                </div>
                <div class="p-6">
                    <div class="space-y-3">
                        <button class="w-full text-left px-4 py-3 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors flex items-center gap-3">
                            <i class='bx bx-help-circle text-blue-600 text-xl'></i>
                            <div>
                                <p class="font-medium text-gray-900 text-sm">Upload Guidelines</p>
                                <p class="text-xs text-gray-600">View policies</p>
                            </div>
                        </button>
                        <button class="w-full text-left px-4 py-3 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors flex items-center gap-3">
                            <i class='bx bx-message text-green-600 text-xl'></i>
                            <div>
                                <p class="font-medium text-gray-900 text-sm">Contact Support</p>
                                <p class="text-xs text-gray-600">Get assistance</p>
                            </div>
                        </button>
                        <button class="w-full text-left px-4 py-3 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors flex items-center gap-3">
                            <i class='bx bx-history text-orange-600 text-xl'></i>
                            <div>
                                <p class="font-medium text-gray-900 text-sm">Upload History</p>
                                <p class="text-xs text-gray-600">View history</p>
                            </div>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Documents Table Section -->
    <div class="mt-8">
        <div class="bg-white rounded-lg shadow-sm border border-gray-200">
            <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                <h2 class="text-lg font-semibold text-gray-900">Uploaded Documents</h2>
                <form method="GET" action="{{ route('admin.documenttracking.upload-document-tracking') }}" class="flex gap-3">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search documents..." class="px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <select name="document_type" class="px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="">All Types</option>
                        <option value="Contract" {{ request('document_type') === 'Contract' ? 'selected' : '' }}>Contract</option>
                        <option value="Invoice" {{ request('document_type') === 'Invoice' ? 'selected' : '' }}>Invoice</option>
                        <option value="Receipt" {{ request('document_type') === 'Receipt' ? 'selected' : '' }}>Receipt</option>
                        <option value="Report" {{ request('document_type') === 'Report' ? 'selected' : '' }}>Report</option>
                        <option value="Certificate" {{ request('document_type') === 'Certificate' ? 'selected' : '' }}>Certificate</option>
                        <option value="License" {{ request('document_type') === 'License' ? 'selected' : '' }}>License</option>
                        <option value="Permit" {{ request('document_type') === 'Permit' ? 'selected' : '' }}>Permit</option>
                        <option value="Identification" {{ request('document_type') === 'Identification' ? 'selected' : '' }}>Identification</option>
                        <option value="Other" {{ request('document_type') === 'Other' ? 'selected' : '' }}>Other</option>
                    </select>
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg transition-colors">
                        <i class='bx bx-search'></i>
                    </button>
                </form>
            </div>
            
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Document ID</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Title</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Category</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">File Name</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Size</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Uploaded By</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Upload Date</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Visibility</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($documents as $document)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $document->document_number }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $document->document_title }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                        {{ $document->document_type }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $document->category }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $document->file_name }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $document->file_size_in_mb }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $document->uploaded_by }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $document->upload_date->format('M d, Y') }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-{{ $document->visibility_color }}-100 text-{{ $document->visibility_color }}-800">
                                        {{ $document->visibility }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-{{ $document->status_color }}-100 text-{{ $document->status_color }}-800">
                                        {{ $document->status }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex space-x-2">
                                        <a href="{{ route('uploaded-documents.show', $document->id) }}" class="text-blue-600 hover:text-blue-900" title="View">
                                            <i class='bx bx-show text-lg'></i>
                                        </a>
                                        @if($document->canBeDownloaded())
                                            <a href="{{ route('uploaded-documents.download', $document->id) }}" class="text-green-600 hover:text-green-900" title="Download">
                                                <i class='bx bx-download text-lg'></i>
                                            </a>
                                        @endif
                                        @if($document->canBeEdited())
                                            <a href="{{ route('uploaded-documents.edit', $document->id) }}" class="text-orange-600 hover:text-orange-900" title="Edit">
                                                <i class='bx bx-edit text-lg'></i>
                                            </a>
                                        @endif
                                        <form method="POST" action="{{ route('uploaded-documents.destroy', $document->id) }}" class="inline" onsubmit="return confirm('Are you sure you want to delete this document?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900" title="Delete">
                                                <i class='bx bx-trash text-lg'></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="11" class="px-6 py-8 text-center text-gray-500">
                                    <div class="flex flex-col items-center">
                                        <i class='bx bx-file text-4xl text-gray-300 mb-3'></i>
                                        <p class="text-lg font-medium">No documents uploaded yet</p>
                                        <p class="text-sm mt-1">Upload your first document using the form above.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($documents && $documents->hasPages())
            <div class="bg-white px-4 py-3 flex items-center justify-between border-t border-gray-200 sm:px-6">
                <div class="flex-1 flex justify-between sm:hidden">
                    {{ $documents->links() }}
                </div>
                <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                    <div>
                        <p class="text-sm text-gray-700">
                            Showing <span class="font-medium">{{ $documents->firstItem() ?? 0 }}</span> to <span class="font-medium">{{ $documents->lastItem() ?? 0 }}</span> of{' '}
                            <span class="font-medium">{{ $documents->total() }}</span> results
                        </p>
                    </div>
                    <div>
                        {{ $documents->links() }}
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

<script>
function displayFiles(files) {
    const filesList = document.getElementById('filesList');
    filesList.innerHTML = '';
    
    Array.from(files).forEach(file => {
        const fileSize = (file.size / 1024 / 1024).toFixed(2) + ' MB';
        const fileItem = document.createElement('div');
        fileItem.className = 'flex items-center justify-between p-3 bg-gray-50 rounded-lg';
        fileItem.innerHTML = `
            <div class="flex items-center gap-3">
                <i class='bx bx-file text-blue-600 text-xl'></i>
                <div>
                    <p class="text-sm font-medium text-gray-900">${file.name}</p>
                    <p class="text-xs text-gray-500">${fileSize}</p>
                </div>
            </div>
            <div class="flex items-center gap-2">
                <span class="text-xs bg-green-100 text-green-800 px-2 py-1 rounded-full">Ready</span>
            </div>
        `;
        filesList.appendChild(fileItem);
    });
}

// Drag and drop functionality
const uploadArea = document.querySelector('.border-dashed');
const fileInput = document.getElementById('fileInput');

uploadArea.addEventListener('dragover', (e) => {
    e.preventDefault();
    uploadArea.classList.add('border-blue-400', 'bg-blue-50');
});

uploadArea.addEventListener('dragleave', (e) => {
    e.preventDefault();
    uploadArea.classList.remove('border-blue-400', 'bg-blue-50');
});

uploadArea.addEventListener('drop', (e) => {
    e.preventDefault();
    uploadArea.classList.remove('border-blue-400', 'bg-blue-50');
    
    const files = e.dataTransfer.files;
    fileInput.files = files;
    displayFiles(files);
});
</script>

<!-- Contracts & Reports Section -->
    <div class="mt-8">
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                <div>
                    <h2 class="text-lg font-semibold text-gray-900">Contracts & Reports</h2>
                    <p class="text-sm text-gray-600 mt-1">Manage contracts and track renewal dates</p>
                </div>
                <div class="flex gap-2">
                    <a href="{{ route('contracts.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg flex items-center gap-2 transition-colors">
                        <i class='bx bx-plus text-xl'></i>
                        New Contract
                    </a>
                    <button onclick="toggleBulkActions()" class="text-gray-600 hover:text-gray-900 p-2 rounded-lg hover:bg-gray-100" title="Bulk Actions">
                        <i class='bx bx-checkbox text-xl'></i>
                    </button>
                    <button onclick="window.location.reload()" class="text-gray-600 hover:text-gray-900 p-2 rounded-lg hover:bg-gray-100" title="Refresh">
                        <i class='bx bx-refresh text-xl'></i>
                    </button>
                    <a href="{{ route('contracts.export') }}" class="text-gray-600 hover:text-gray-900 p-2 rounded-lg hover:bg-gray-100" title="Export">
                        <i class='bx bx-download text-xl'></i>
                    </a>
                </div>
            </div>

            <!-- Bulk Actions (Hidden by default) -->
            <div id="bulkActions" class="hidden px-6 py-3 bg-gray-50 border-b border-gray-200">
                <form method="POST" action="{{ route('contracts.bulk-action') }}" class="flex items-center justify-between">
                    @csrf
                    <div class="flex items-center gap-3">
                        <span class="text-sm text-gray-600">Bulk Actions:</span>
                        <select name="action" class="px-3 py-1 border border-gray-300 rounded text-sm">
                            <option value="">Select action</option>
                            <option value="renew">Renew Selected</option>
                            <option value="terminate">Terminate Selected</option>
                            <option value="export">Export Selected</option>
                        </select>
                        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium py-1 px-3 rounded">
                            Apply
                        </button>
                    </div>
                    <button type="button" onclick="toggleBulkActions()" class="text-gray-600 hover:text-gray-900">
                        <i class='bx bx-x text-xl'></i>
                    </button>
                </form>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50 border-b border-gray-200">
                        <tr>
                            <th class="bulk-select-header hidden px-6 py-3 text-left">
                                <input type="checkbox" id="selectAll" onchange="toggleAllCheckboxes()" class="rounded border-gray-300">
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Contract Number</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Contract Name</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Vendor</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Value</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Start Date</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">End Date</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Days Left</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Priority</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse ($contracts as $contract)
                            <tr class="hover:bg-gray-50">
                                <td class="bulk-select-cell hidden px-6 py-4">
                                    <input type="checkbox" name="contract_ids[]" value="{{ $contract->id }}" class="contract-checkbox rounded border-gray-300">
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $contract->contract_number }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $contract->contract_name }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div>
                                        <p class="text-sm font-medium text-gray-900">{{ $contract->vendor }}</p>
                                        @if ($contract->vendor_contact)
                                            <p class="text-xs text-gray-500">{{ $contract->vendor_contact }}</p>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                        {{ $contract->contract_type }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    @if ($contract->contract_value)
                                        ₱{{ number_format($contract->contract_value, 2) }}
                                    @else
                                        -
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $contract->start_date->format('M d, Y') }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $contract->end_date->format('M d, Y') }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @php
                                        $daysLeft = $contract->end_date->diffInDays(now());
                                        $daysLeftColor = $daysLeft <= 30 ? 'red' : ($daysLeft <= 90 ? 'orange' : 'green');
                                    @endphp
                                    <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-{{ $daysLeftColor }}-100 text-{{ $daysLeftColor }}-800">
                                        {{ $daysLeft > 0 ? $daysLeft . ' days' : 'Expired' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @php
                                        $statusColors = [
                                            'Active' => 'green',
                                            'Expired' => 'red',
                                            'Terminated' => 'gray',
                                            'Pending' => 'yellow',
                                            'Renewed' => 'blue'
                                        ];
                                        $statusColor = $statusColors[$contract->status] ?? 'gray';
                                    @endphp
                                    <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-{{ $statusColor }}-100 text-{{ $statusColor }}-800">
                                        {{ $contract->status }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @php
                                        $priorityColors = [
                                            'High' => 'red',
                                            'Medium' => 'orange',
                                            'Low' => 'green'
                                        ];
                                        $priorityColor = $priorityColors[$contract->priority] ?? 'gray';
                                    @endphp
                                    <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-{{ $priorityColor }}-100 text-{{ $priorityColor }}-800">
                                        {{ $contract->priority }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex space-x-2">
                                        <a href="{{ route('contracts.show', $contract->id) }}" class="text-blue-600 hover:text-blue-900" title="View">
                                            <i class='bx bx-show text-lg'></i>
                                        </a>
                                        <a href="{{ route('contracts.edit', $contract->id) }}" class="text-green-600 hover:text-green-900" title="Edit">
                                            <i class='bx bx-edit text-lg'></i>
                                        </a>
                                        @if ($contract->status === 'Active')
                                            <button onclick="openRenewModal({{ $contract->id }})" class="text-orange-600 hover:text-orange-900" title="Renew">
                                                <i class='bx bx-refresh text-lg'></i>
                                            </button>
                                        @endif
                                        @if (in_array($contract->status, ['Active', 'Expired']))
                                            <button onclick="openTerminateModal({{ $contract->id }})" class="text-red-600 hover:text-red-900" title="Terminate">
                                                <i class='bx bx-x-circle text-lg'></i>
                                            </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="12" class="px-6 py-8 text-center text-gray-500">
                                    <div class="flex flex-col items-center">
                                        <i class='bx bx-file text-4xl text-gray-300 mb-3'></i>
                                        <p class="text-lg font-medium">No contracts found</p>
                                        <p class="text-sm mt-1">Get started by creating your first contract.</p>
                                        <a href="{{ route('contracts.create') }}" class="mt-4 bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg inline-flex items-center gap-2 transition-colors">
                                            <i class='bx bx-plus'></i>
                                            Create First Contract
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="bg-white px-4 py-3 flex items-center justify-between border-t border-gray-200 sm:px-6">
                <div class="flex-1 flex justify-between sm:hidden">
                    {{ $contracts->links() }}
                </div>
                <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                    <div>
                        <p class="text-sm text-gray-700">
                            Showing <span class="font-medium">{{ $contracts->firstItem() ?? 0 }}</span> to <span class="font-medium">{{ $contracts->lastItem() ?? 0 }}</span> of{' '}
                            <span class="font-medium">{{ $contracts->total() }}</span> results
                        </p>
                    </div>
                    <div>
                        {{ $contracts->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Renew Modal -->
    <div id="renewModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-lg bg-white">
            <div class="mt-3">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Renew Contract</h3>
                <form id="renewForm" method="POST">
                    @csrf
                    <input type="hidden" name="contract_id" id="renewContractId">
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">New End Date</label>
                        <input type="date" name="new_end_date" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Renewal Terms (Optional)</label>
                        <textarea name="renewal_terms" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
                    </div>
                    <div class="flex justify-end gap-3">
                        <button type="button" onclick="closeRenewModal()" class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-medium py-2 px-4 rounded-lg">
                            Cancel
                        </button>
                        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg">
                            Renew Contract
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Terminate Modal -->
    <div id="terminateModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-lg bg-white">
            <div class="mt-3">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Terminate Contract</h3>
                <form id="terminateForm" method="POST">
                    @csrf
                    <input type="hidden" name="contract_id" id="terminateContractId">
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Termination Reason</label>
                        <textarea name="termination_reason" rows="3" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Termination Date</label>
                        <input type="date" name="termination_date" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div class="flex justify-end gap-3">
                        <button type="button" onclick="closeTerminateModal()" class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-medium py-2 px-4 rounded-lg">
                            Cancel
                        </button>
                        <button type="submit" class="bg-red-600 hover:bg-red-700 text-white font-medium py-2 px-4 rounded-lg">
                            Terminate Contract
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection

<script>
function toggleBulkActions() {
    const bulkActions = document.getElementById('bulkActions');
    const bulkSelectHeader = document.querySelector('.bulk-select-header');
    const bulkSelectCells = document.querySelectorAll('.bulk-select-cell');
    
    if (bulkActions.classList.contains('hidden')) {
        bulkActions.classList.remove('hidden');
        bulkSelectHeader.classList.remove('hidden');
        bulkSelectCells.forEach(cell => cell.classList.remove('hidden'));
    } else {
        bulkActions.classList.add('hidden');
        bulkSelectHeader.classList.add('hidden');
        bulkSelectCells.forEach(cell => cell.classList.add('hidden'));
    }
}

function toggleAllCheckboxes() {
    const selectAll = document.getElementById('selectAll');
    const checkboxes = document.querySelectorAll('.contract-checkbox');
    checkboxes.forEach(checkbox => checkbox.checked = selectAll.checked);
}

function openRenewModal(contractId) {
    document.getElementById('renewContractId').value = contractId;
    document.getElementById('renewModal').classList.remove('hidden');
}

function closeRenewModal() {
    document.getElementById('renewModal').classList.add('hidden');
}

function openTerminateModal(contractId) {
    document.getElementById('terminateContractId').value = contractId;
    document.getElementById('terminateModal').classList.remove('hidden');
}

function closeTerminateModal() {
    document.getElementById('terminateModal').classList.add('hidden');
}

function displayFiles(files) {
    const filesList = document.getElementById('filesList');
    filesList.innerHTML = '';
    
    Array.from(files).forEach(file => {
        const fileItem = document.createElement('div');
        fileItem.className = 'flex items-center justify-between p-3 bg-gray-50 rounded-lg';
        fileItem.innerHTML = `
            <div class="flex items-center">
                <i class='bx bx-file text-gray-400 mr-3'></i>
                <div>
                    <p class="text-sm font-medium text-gray-900">${file.name}</p>
                    <p class="text-xs text-gray-500">${(file.size / 1024 / 1024).toFixed(2)} MB</p>
                </div>
            </div>
            <button type="button" onclick="this.parentElement.remove()" class="text-red-600 hover:text-red-800">
                <i class='bx bx-x'></i>
            </button>
        `;
        filesList.appendChild(fileItem);
    });
}
</script>
