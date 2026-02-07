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
                    <a href="{{ route('admin.documenttracking.document-request') }}" class="text-gray-700 hover:text-blue-600 ml-1 md:ml-2">Document Request</a>
                </div>
            </li>
            <li>
                <div class="flex items-center">
                    <i class='bx bx-chevron-right text-gray-400'></i>
                    <a href="{{ route('document-requests.show', $request->id) }}" class="text-gray-700 hover:text-blue-600 ml-1 md:ml-2">{{ $request->request_title }}</a>
                </div>
            </li>
            <li aria-current="page">
                <div class="flex items-center">
                    <i class='bx bx-chevron-right text-gray-400'></i>
                    <span class="ml-1 text-gray-500 md:ml-2">Details</span>
                </div>
            </li>
        </ol>
    </nav>

    <!-- Page Header -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">{{ $request->request_title }}</h1>
            <p class="text-gray-600 mt-1">Request ID: {{ $request->request_number }}</p>
        </div>
        <div class="flex gap-3">
            @if(in_array($request->status, ['Draft', 'Under Review']))
                <a href="{{ route('document-requests.edit', $request->id) }}" class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg flex items-center gap-2 transition-colors">
                    <i class='bx bx-edit text-xl'></i>
                    Edit Request
                </a>
            @endif
            @if($request->status === 'Completed')
                <a href="#" class="bg-green-600 hover:bg-green-700 text-white font-medium py-2 px-4 rounded-lg flex items-center gap-2 transition-colors">
                    <i class='bx bx-download text-xl'></i>
                    Download Documents
                </a>
            @endif
            <a href="{{ route('admin.documenttracking.document-request') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-medium py-2 px-4 rounded-lg flex items-center gap-2 transition-colors">
                <i class='bx bx-arrow-back text-xl'></i>
                Back to Requests
            </a>
        </div>
    </div>

    <!-- Request Details -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Content -->
        <div class="lg:col-span-2">
            <!-- Basic Information -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 mb-6">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-900">Request Information</h2>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <p class="text-sm font-medium text-gray-600">Request Number</p>
                            <p class="mt-1 text-sm text-gray-900">{{ $request->request_number }}</p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-600">Status</p>
                            <p class="mt-1">
                                <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-{{ $request->status_color }}-100 text-{{ $request->status_color }}-800">
                                    {{ $request->status }}
                                </span>
                            </p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-600">Priority</p>
                            <p class="mt-1">
                                <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-{{ $request->priority_color }}-100 text-{{ $request->priority_color }}-800">
                                    {{ $request->priority }}
                                </span>
                            </p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-600">Urgency</p>
                            <p class="mt-1">
                                <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-{{ $request->urgency_color }}-100 text-{{ $request->urgency_color }}-800">
                                    {{ $request->urgency }}
                                </span>
                            </p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-600">Requested By</p>
                            <p class="mt-1 text-sm text-gray-900">{{ $request->requested_by }}</p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-600">Department</p>
                            <p class="mt-1 text-sm text-gray-900">{{ $request->department }}</p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-600">Request Date</p>
                            <p class="mt-1 text-sm text-gray-900">{{ $request->request_date->format('F d, Y') }}</p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-600">Contact Person</p>
                            <p class="mt-1 text-sm text-gray-900">{{ $request->contact_person }}</p>
                        </div>
                    </div>
                    
                    <div class="mt-6">
                        <p class="text-sm font-medium text-gray-600">Description</p>
                        <p class="mt-1 text-sm text-gray-900">{{ $request->description }}</p>
                    </div>
                    
                    <div class="mt-6">
                        <p class="text-sm font-medium text-gray-600">Purpose</p>
                        <p class="mt-1 text-sm text-gray-900">{{ $request->purpose }}</p>
                    </div>
                    
                    @if($request->notes)
                    <div class="mt-6">
                        <p class="text-sm font-medium text-gray-600">Additional Notes</p>
                        <p class="mt-1 text-sm text-gray-900">{{ $request->notes }}</p>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Document Details -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 mb-6">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-900">Document Details</h2>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <p class="text-sm font-medium text-gray-600">Document Type</p>
                            <p class="mt-1 text-sm text-gray-900">{{ $request->document_type }}</p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-600">Document Category</p>
                            <p class="mt-1 text-sm text-gray-900">{{ $request->document_category }}</p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-600">Format Required</p>
                            <p class="mt-1 text-sm text-gray-900">{{ $request->format_required }}</p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-600">Number of Copies</p>
                            <p class="mt-1 text-sm text-gray-900">{{ $request->number_of_copies }}</p>
                        </div>
                        @if($request->date_range)
                        <div>
                            <p class="text-sm font-medium text-gray-600">Date Range</p>
                            <p class="mt-1 text-sm text-gray-900">{{ $request->date_range }}</p>
                        </div>
                        @endif
                        @if($request->reference_number)
                        <div>
                            <p class="text-sm font-medium text-gray-600">Reference Number</p>
                            <p class="mt-1 text-sm text-gray-900">{{ $request->reference_number }}</p>
                        </div>
                        @endif
                        @if($request->project_name)
                        <div>
                            <p class="text-sm font-medium text-gray-600">Project Name</p>
                            <p class="mt-1 text-sm text-gray-900">{{ $request->project_name }}</p>
                        </div>
                        @endif
                        @if($request->cost_center)
                        <div>
                            <p class="text-sm font-medium text-gray-600">Cost Center</p>
                            <p class="mt-1 text-sm text-gray-900">{{ $request->cost_center }}</p>
                        </div>
                        @endif
                    </div>
                    
                    <!-- Additional Requirements -->
                    <div class="mt-6">
                        <p class="text-sm font-medium text-gray-600 mb-3">Additional Requirements</p>
                        <div class="flex flex-wrap gap-2">
                            @if($request->notarization_required)
                                <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                    <i class='bx bx-check mr-1'></i> Notarization Required
                                </span>
                            @endif
                            @if($request->apostille_needed)
                                <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-purple-100 text-purple-800">
                                    <i class='bx bx-check mr-1'></i> Apostille Needed
                                </span>
                            @endif
                            @if($request->translation_required)
                                <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                    <i class='bx bx-check mr-1'></i> Translation Required
                                </span>
                            @endif
                            @if($request->certified_true_copy)
                                <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-orange-100 text-orange-800">
                                    <i class='bx bx-check mr-1'></i> Certified True Copy
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Delivery Information -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-900">Delivery Information</h2>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <p class="text-sm font-medium text-gray-600">Delivery Method</p>
                            <p class="mt-1 text-sm text-gray-900">{{ $request->delivery_method }}</p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-600">Contact Number</p>
                            <p class="mt-1 text-sm text-gray-900">{{ $request->contact_number }}</p>
                        </div>
                    </div>
                    
                    @if($request->delivery_address)
                    <div class="mt-6">
                        <p class="text-sm font-medium text-gray-600">Delivery Address</p>
                        <p class="mt-1 text-sm text-gray-900">{{ $request->delivery_address }}</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="lg:col-span-1">
            <!-- Quick Actions -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 mb-6">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-900">Quick Actions</h2>
                </div>
                <div class="p-6">
                    <div class="space-y-3">
                        @if(in_array($request->status, ['Draft', 'Under Review']))
                            <a href="{{ route('document-requests.edit', $request->id) }}" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg flex items-center justify-center gap-2 transition-colors">
                                <i class='bx bx-edit text-xl'></i>
                                Edit Request
                            </a>
                        @endif
                        
                        @if($request->status === 'Draft')
                            <form action="{{ route('document-requests.update', $request->id) }}" method="POST" class="w-full">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="status" value="Submitted">
                                <button type="submit" class="w-full bg-green-600 hover:bg-green-700 text-white font-medium py-2 px-4 rounded-lg flex items-center justify-center gap-2 transition-colors">
                                    <i class='bx bx-send text-xl'></i>
                                    Submit Request
                                </button>
                            </form>
                        @endif
                        
                        @if($request->status === 'Submitted')
                            <form action="{{ route('document-requests.update', $request->id) }}" method="POST" class="w-full">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="status" value="Under Review">
                                <button type="submit" class="w-full bg-orange-600 hover:bg-orange-700 text-white font-medium py-2 px-4 rounded-lg flex items-center justify-center gap-2 transition-colors">
                                    <i class='bx bx-search text-xl'></i>
                                    Start Review
                                </button>
                            </form>
                        @endif
                        
                        @if($request->status === 'Under Review')
                            <form action="{{ route('document-requests.update', $request->id) }}" method="POST" class="w-full">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="status" value="Approved">
                                <button type="submit" class="w-full bg-green-600 hover:bg-green-700 text-white font-medium py-2 px-4 rounded-lg flex items-center justify-center gap-2 transition-colors">
                                    <i class='bx bx-check text-xl'></i>
                                    Approve Request
                                </button>
                            </form>
                        @endif
                        
                        @if($request->status === 'Approved')
                            <form action="{{ route('document-requests.update', $request->id) }}" method="POST" class="w-full">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="status" value="Processing">
                                <button type="submit" class="w-full bg-purple-600 hover:bg-purple-700 text-white font-medium py-2 px-4 rounded-lg flex items-center justify-center gap-2 transition-colors">
                                    <i class='bx bx-loader text-xl'></i>
                                    Start Processing
                                </button>
                            </form>
                        @endif
                        
                        @if($request->status === 'Processing')
                            <form action="{{ route('document-requests.update', $request->id) }}" method="POST" class="w-full">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="status" value="Completed">
                                <button type="submit" class="w-full bg-green-600 hover:bg-green-700 text-white font-medium py-2 px-4 rounded-lg flex items-center justify-center gap-2 transition-colors">
                                    <i class='bx bx-check-double text-xl'></i>
                                    Mark Complete
                                </button>
                            </form>
                        @endif
                        
                        @if($request->status === 'Completed')
                            <a href="#" class="w-full bg-green-600 hover:bg-green-700 text-white font-medium py-2 px-4 rounded-lg flex items-center justify-center gap-2 transition-colors">
                                <i class='bx bx-download text-xl'></i>
                                Download Documents
                            </a>
                        @endif
                        
                        @if(in_array($request->status, ['Draft', 'Submitted', 'Rejected']))
                            <form action="{{ route('document-requests.destroy', $request->id) }}" method="POST" class="w-full">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="w-full bg-red-600 hover:bg-red-700 text-white font-medium py-2 px-4 rounded-lg flex items-center justify-center gap-2 transition-colors" onclick="return confirm('Are you sure you want to delete this request?')">
                                    <i class='bx bx-trash text-xl'></i>
                                    Delete Request
                                </button>
                            </form>
                        @endif
                        
                        @if(in_array($request->status, ['Submitted', 'Under Review']))
                            <form action="{{ route('document-requests.update', $request->id) }}" method="POST" class="w-full">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="status" value="Rejected">
                                <button type="submit" class="w-full bg-red-600 hover:bg-red-700 text-white font-medium py-2 px-4 rounded-lg flex items-center justify-center gap-2 transition-colors" onclick="return confirm('Are you sure you want to reject this request?')">
                                    <i class='bx bx-x text-xl'></i>
                                    Reject Request
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Request Timeline -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-900">Request Timeline</h2>
                </div>
                <div class="p-6">
                    <div class="space-y-4">
                        <div class="flex items-start gap-3">
                            <div class="bg-blue-100 rounded-full p-2 mt-1">
                                <i class='bx bx-plus text-blue-600 text-sm'></i>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-900">Request Created</p>
                                <p class="text-xs text-gray-600">{{ $request->created_at->format('M d, Y - h:i A') }}</p>
                            </div>
                        </div>
                        
                        @if($request->updated_at != $request->created_at)
                        <div class="flex items-start gap-3">
                            <div class="bg-orange-100 rounded-full p-2 mt-1">
                                <i class='bx bx-edit text-orange-600 text-sm'></i>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-900">Last Updated</p>
                                <p class="text-xs text-gray-600">{{ $request->updated_at->format('M d, Y - h:i A') }}</p>
                            </div>
                        </div>
                        @endif
                        
                        @if($request->status === 'Completed')
                        <div class="flex items-start gap-3">
                            <div class="bg-green-100 rounded-full p-2 mt-1">
                                <i class='bx bx-check text-green-600 text-sm'></i>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-900">Request Completed</p>
                                <p class="text-xs text-gray-600">{{ $request->updated_at->format('M d, Y - h:i A') }}</p>
                            </div>
                        </div>
                        @endif
                        
                        @if($request->status === 'Processing')
                        <div class="flex items-start gap-3">
                            <div class="bg-purple-100 rounded-full p-2 mt-1">
                                <i class='bx bx-loader text-purple-600 text-sm animate-spin'></i>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-900">Processing</p>
                                <p class="text-xs text-gray-600">Request is being processed...</p>
                            </div>
                        </div>
                        @endif
                        
                        @if($request->status === 'Rejected')
                        <div class="flex items-start gap-3">
                            <div class="bg-red-100 rounded-full p-2 mt-1">
                                <i class='bx bx-x text-red-600 text-sm'></i>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-900">Request Rejected</p>
                                <p class="text-xs text-gray-600">{{ $request->updated_at->format('M d, Y - h:i A') }}</p>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
