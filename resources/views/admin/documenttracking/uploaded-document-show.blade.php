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
            <li aria-current="page">
                <div class="flex items-center">
                    <i class='bx bx-chevron-right text-gray-400'></i>
                    <span class="ml-1 text-gray-500 md:ml-2">Document Details</span>
                </div>
            </li>
        </ol>
    </nav>

    <!-- Page Header -->
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-900">Document Details</h1>
        <div class="flex gap-3">
            <a href="{{ route('admin.documenttracking.upload-document-tracking') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-medium py-2 px-4 rounded-lg flex items-center gap-2 transition-colors">
                <i class='bx bx-arrow-left text-xl'></i>
                Back to Upload
            </a>
            @if($uploadedDocument->canBeDownloaded())
                <a href="{{ route('uploaded-documents.download', $uploadedDocument->id) }}" class="bg-green-600 hover:bg-green-700 text-white font-medium py-2 px-4 rounded-lg flex items-center gap-2 transition-colors">
                    <i class='bx bx-download text-xl'></i>
                    Download
                </a>
            @endif
            @if($uploadedDocument->canBeEdited())
                <a href="{{ route('uploaded-documents.edit', $uploadedDocument->id) }}" class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg flex items-center gap-2 transition-colors">
                    <i class='bx bx-edit text-xl'></i>
                    Edit
                </a>
            @endif
        </div>
    </div>

    <!-- Document Information -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Document Details -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-900">Document Information</h2>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Document ID -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Document ID</label>
                            <p class="text-sm text-gray-900 font-medium">{{ $uploadedDocument->document_number }}</p>
                        </div>

                        <!-- Title -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Document Title</label>
                            <p class="text-sm text-gray-900 font-medium">{{ $uploadedDocument->document_title }}</p>
                        </div>

                        <!-- Document Type -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Document Type</label>
                            <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                {{ $uploadedDocument->document_type }}
                            </span>
                        </div>

                        <!-- Category -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Category</label>
                            <p class="text-sm text-gray-900">{{ $uploadedDocument->category }}</p>
                        </div>

                        <!-- Reference Number -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Reference Number</label>
                            <p class="text-sm text-gray-900">{{ $uploadedDocument->reference_number ?? 'N/A' }}</p>
                        </div>

                        <!-- Status -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                            <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-{{ $uploadedDocument->status_color }}-100 text-{{ $uploadedDocument->status_color }}-800">
                                {{ $uploadedDocument->status }}
                            </span>
                        </div>

                        <!-- Effective Date -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Effective Date</label>
                            <p class="text-sm text-gray-900">{{ $uploadedDocument->effective_date ? $uploadedDocument->effective_date->format('M d, Y') : 'N/A' }}</p>
                        </div>

                        <!-- Expiration Date -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Expiration Date</label>
                            <p class="text-sm text-gray-900">{{ $uploadedDocument->expiration_date ? $uploadedDocument->expiration_date->format('M d, Y') : 'N/A' }}</p>
                            @if($uploadedDocument->isExpired())
                                <p class="text-xs text-red-600 mt-1">⚠️ Document has expired</p>
                            @endif
                        </div>
                    </div>

                    <!-- Description -->
                    <div class="mt-6">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                        <p class="text-sm text-gray-900 bg-gray-50 p-3 rounded-lg">{{ $uploadedDocument->description }}</p>
                    </div>

                    <!-- Tags -->
                    @if($uploadedDocument->tags)
                    <div class="mt-6">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Tags</label>
                        <div class="flex flex-wrap gap-2">
                            @foreach(explode(',', $uploadedDocument->tags) as $tag)
                                <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                    {{ trim($tag) }}
                                </span>
                            @endforeach
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            <!-- File Information -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-900">File Information</h2>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- File Name -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">File Name</label>
                            <p class="text-sm text-gray-900 font-medium">{{ $uploadedDocument->file_name }}</p>
                        </div>

                        <!-- File Size -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">File Size</label>
                            <p class="text-sm text-gray-900">{{ $uploadedDocument->file_size_in_mb }}</p>
                        </div>

                        <!-- File Type -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">File Type</label>
                            <p class="text-sm text-gray-900">{{ $uploadedDocument->file_type }}</p>
                        </div>

                        <!-- Upload Date -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Upload Date</label>
                            <p class="text-sm text-gray-900">{{ $uploadedDocument->upload_date->format('M d, Y h:i A') }}</p>
                        </div>
                    </div>

                    <!-- File Preview -->
                    <div class="mt-6">
                        <label class="block text-sm font-medium text-gray-700 mb-1">File Preview</label>
                        <div class="border-2 border-dashed border-gray-300 rounded-lg p-8 text-center">
                            <i class='bx bx-file text-6xl text-gray-400 mb-3'></i>
                            <p class="text-lg font-medium text-gray-700 mb-2">{{ $uploadedDocument->file_name }}</p>
                            <p class="text-sm text-gray-500 mb-4">{{ $uploadedDocument->file_size_in_mb }}</p>
                            @if($uploadedDocument->canBeDownloaded())
                                <a href="{{ route('uploaded-documents.download', $uploadedDocument->id) }}" class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-6 rounded-lg inline-flex items-center gap-2 transition-colors">
                                    <i class='bx bx-download'></i>
                                    Download File
                                </a>
                            @else
                                <p class="text-sm text-red-600">Download not available - permission denied or document expired</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Access Control -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-900">Access Control</h2>
                </div>
                <div class="p-6">
                    <div class="space-y-4">
                        <!-- Visibility -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Visibility</label>
                            <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-{{ $uploadedDocument->visibility_color }}-100 text-{{ $uploadedDocument->visibility_color }}-800">
                                {{ $uploadedDocument->visibility }}
                            </span>
                        </div>

                        <!-- Permissions -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Permissions</label>
                            <div class="space-y-2">
                                <div class="flex items-center justify-between">
                                    <span class="text-sm text-gray-700">View Only Access</span>
                                    @if($uploadedDocument->view_only_access)
                                        <i class='bx bx-check-circle text-green-600'></i>
                                    @else
                                        <i class='bx bx-x-circle text-red-600'></i>
                                    @endif
                                </div>
                                <div class="flex items-center justify-between">
                                    <span class="text-sm text-gray-700">Download Permission</span>
                                    @if($uploadedDocument->download_permission)
                                        <i class='bx bx-check-circle text-green-600'></i>
                                    @else
                                        <i class='bx bx-x-circle text-red-600'></i>
                                    @endif
                                </div>
                                <div class="flex items-center justify-between">
                                    <span class="text-sm text-gray-700">Edit Permission</span>
                                    @if($uploadedDocument->edit_permission)
                                        <i class='bx bx-check-circle text-green-600'></i>
                                    @else
                                        <i class='bx bx-x-circle text-red-600'></i>
                                    @endif
                                </div>
                                <div class="flex items-center justify-between">
                                    <span class="text-sm text-gray-700">Share Permission</span>
                                    @if($uploadedDocument->share_permission)
                                        <i class='bx bx-check-circle text-green-600'></i>
                                    @else
                                        <i class='bx bx-x-circle text-red-600'></i>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Authorized Users -->
                        @if($uploadedDocument->authorized_users)
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Authorized Users</label>
                            <div class="space-y-1">
                                @foreach($uploadedDocument->authorized_users as $user)
                                    <p class="text-sm text-gray-900">{{ $user }}</p>
                                @endforeach
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Upload Information -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-900">Upload Information</h2>
                </div>
                <div class="p-6">
                    <div class="space-y-4">
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600">Uploaded By</span>
                            <span class="text-sm font-medium text-gray-900">{{ $uploadedDocument->uploaded_by }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600">Department</span>
                            <span class="text-sm font-medium text-gray-900">{{ $uploadedDocument->department }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600">Upload Date</span>
                            <span class="text-sm font-medium text-gray-900">{{ $uploadedDocument->upload_date->format('M d, Y') }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600">Last Updated</span>
                            <span class="text-sm font-medium text-gray-900">{{ $uploadedDocument->updated_at->format('M d, Y h:i A') }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-900">Quick Actions</h2>
                </div>
                <div class="p-6 space-y-3">
                    @if($uploadedDocument->canBeDownloaded())
                        <a href="{{ route('uploaded-documents.download', $uploadedDocument->id) }}" class="w-full text-left px-4 py-3 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors flex items-center gap-3">
                            <i class='bx bx-download text-green-600 text-xl'></i>
                            <div>
                                <p class="font-medium text-gray-900">Download</p>
                                <p class="text-xs text-gray-600">Get file</p>
                            </div>
                        </a>
                    @endif
                    
                    @if($uploadedDocument->canBeEdited())
                        <a href="{{ route('uploaded-documents.edit', $uploadedDocument->id) }}" class="w-full text-left px-4 py-3 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors flex items-center gap-3">
                            <i class='bx bx-edit text-blue-600 text-xl'></i>
                            <div>
                                <p class="font-medium text-gray-900">Edit</p>
                                <p class="text-xs text-gray-600">Update info</p>
                            </div>
                        </a>
                    @endif
                    
                    @if($uploadedDocument->canBeShared())
                        <button class="w-full text-left px-4 py-3 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors flex items-center gap-3">
                            <i class='bx bx-share text-purple-600 text-xl'></i>
                            <div>
                                <p class="font-medium text-gray-900">Share</p>
                                <p class="text-xs text-gray-600">Send link</p>
                            </div>
                        </button>
                    @endif
                    
                    <form method="POST" action="{{ route('uploaded-documents.destroy', $uploadedDocument->id) }}" onsubmit="return confirm('Are you sure you want to delete this document?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="w-full text-left px-4 py-3 border border-red-200 rounded-lg hover:bg-red-50 transition-colors flex items-center gap-3">
                            <i class='bx bx-trash text-red-600 text-xl'></i>
                            <div>
                                <p class="font-medium text-red-900">Delete</p>
                                <p class="text-xs text-red-600">Remove file</p>
                            </div>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
