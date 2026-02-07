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
                    <span class="ml-1 text-gray-500 md:ml-2">Edit Request</span>
                </div>
            </li>
        </ol>
    </nav>

    <!-- Page Header -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Edit Document Request</h1>
            <p class="text-gray-600 mt-1">Request ID: {{ $request->request_number }}</p>
        </div>
        <div class="flex gap-3">
            <a href="{{ route('document-requests.show', $request->id) }}" class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-medium py-2 px-4 rounded-lg flex items-center gap-2 transition-colors">
                <i class='bx bx-arrow-back text-xl'></i>
                Back to Request
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

    <!-- Edit Form Container -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Form -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-900">Edit Document Request Details</h2>
                </div>
                <form method="POST" action="{{ route('document-requests.update', $request->id) }}" class="p-6 space-y-6">
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

                    <!-- Request Information -->
                    <div class="space-y-4">
                        <h3 class="text-md font-semibold text-gray-900">Request Information</h3>
                        
                        <!-- Request Title -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Request Title *</label>
                            <input type="text" 
                                   name="request_title" 
                                   value="{{ old('request_title', $request->request_title) }}"
                                   placeholder="Enter request title" 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            @error('request_title')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Description -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Description *</label>
                            <textarea name="description" 
                                      rows="4" 
                                      placeholder="Provide detailed description of your document requirements..." 
                                      class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">{{ old('description', $request->description) }}</textarea>
                            @error('description')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Priority and Urgency -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Priority *</label>
                                <select name="priority" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                    <option value="">Select Priority</option>
                                    <option value="Low" {{ old('priority', $request->priority) === 'Low' ? 'selected' : '' }}>Low</option>
                                    <option value="Medium" {{ old('priority', $request->priority) === 'Medium' ? 'selected' : '' }}>Medium</option>
                                    <option value="High" {{ old('priority', $request->priority) === 'High' ? 'selected' : '' }}>High</option>
                                    <option value="Critical" {{ old('priority', $request->priority) === 'Critical' ? 'selected' : '' }}>Critical</option>
                                </select>
                                @error('priority')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Urgency *</label>
                                <select name="urgency" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                    <option value="">Select Urgency</option>
                                    <option value="Normal (3-5 days)" {{ old('urgency', $request->urgency) === 'Normal (3-5 days)' ? 'selected' : '' }}>Normal (3-5 days)</option>
                                    <option value="Urgent (1-2 days)" {{ old('urgency', $request->urgency) === 'Urgent (1-2 days)' ? 'selected' : '' }}>Urgent (1-2 days)</option>
                                    <option value="Critical (Same day)" {{ old('urgency', $request->urgency) === 'Critical (Same day)' ? 'selected' : '' }}>Critical (Same day)</option>
                                </select>
                                @error('urgency')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Document Details -->
                    <div class="space-y-4">
                        <h3 class="text-md font-semibold text-gray-900">Document Details</h3>
                        
                        <!-- Document Type -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Document Type *</label>
                            <select name="document_type" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                <option value="">Select Document Type</option>
                                <option value="Contract" {{ old('document_type', $request->document_type) === 'Contract' ? 'selected' : '' }}>Contract</option>
                                <option value="Invoice" {{ old('document_type', $request->document_type) === 'Invoice' ? 'selected' : '' }}>Invoice</option>
                                <option value="Receipt" {{ old('document_type', $request->document_type) === 'Receipt' ? 'selected' : '' }}>Receipt</option>
                                <option value="Report" {{ old('document_type', $request->document_type) === 'Report' ? 'selected' : '' }}>Report</option>
                                <option value="Certificate" {{ old('document_type', $request->document_type) === 'Certificate' ? 'selected' : '' }}>Certificate</option>
                                <option value="License" {{ old('document_type', $request->document_type) === 'License' ? 'selected' : '' }}>License</option>
                                <option value="Permit" {{ old('document_type', $request->document_type) === 'Permit' ? 'selected' : '' }}>Permit</option>
                                <option value="Identification" {{ old('document_type', $request->document_type) === 'Identification' ? 'selected' : '' }}>Identification</option>
                                <option value="Other" {{ old('document_type', $request->document_type) === 'Other' ? 'selected' : '' }}>Other</option>
                            </select>
                            @error('document_type')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Document Category -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Document Category *</label>
                            <select name="document_category" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                <option value="">Select Category</option>
                                <option value="Legal" {{ old('document_category', $request->document_category) === 'Legal' ? 'selected' : '' }}>Legal</option>
                                <option value="Financial" {{ old('document_category', $request->document_category) === 'Financial' ? 'selected' : '' }}>Financial</option>
                                <option value="HR" {{ old('document_category', $request->document_category) === 'HR' ? 'selected' : '' }}>HR</option>
                                <option value="Operations" {{ old('document_category', $request->document_category) === 'Operations' ? 'selected' : '' }}>Operations</option>
                                <option value="Compliance" {{ old('document_category', $request->document_category) === 'Compliance' ? 'selected' : '' }}>Compliance</option>
                                <option value="Technical" {{ old('document_category', $request->document_category) === 'Technical' ? 'selected' : '' }}>Technical</option>
                                <option value="Administrative" {{ old('document_category', $request->document_category) === 'Administrative' ? 'selected' : '' }}>Administrative</option>
                            </select>
                            @error('document_category')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Document Specifications -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Document Specifications</label>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-medium text-gray-600 mb-1">Format Required *</label>
                                    <select name="format_required" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                        <option value="">Select Format</option>
                                        <option value="Original Copy" {{ old('format_required', $request->format_required) === 'Original Copy' ? 'selected' : '' }}>Original Copy</option>
                                        <option value="Certified Copy" {{ old('format_required', $request->format_required) === 'Certified Copy' ? 'selected' : '' }}>Certified Copy</option>
                                        <option value="Digital Copy (PDF)" {{ old('format_required', $request->format_required) === 'Digital Copy (PDF)' ? 'selected' : '' }}>Digital Copy (PDF)</option>
                                        <option value="Scanned Copy" {{ old('format_required', $request->format_required) === 'Scanned Copy' ? 'selected' : '' }}>Scanned Copy</option>
                                        <option value="Hard Copy" {{ old('format_required', $request->format_required) === 'Hard Copy' ? 'selected' : '' }}>Hard Copy</option>
                                    </select>
                                    @error('format_required')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-gray-600 mb-1">Number of Copies</label>
                                    <input type="number" 
                                           name="number_of_copies"
                                           value="{{ old('number_of_copies', $request->number_of_copies) }}"
                                           min="1"
                                           placeholder="Enter number"
                                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                    @error('number_of_copies')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-gray-600 mb-1">Date Range</label>
                                    <input type="text" 
                                           name="date_range"
                                           value="{{ old('date_range', $request->date_range) }}"
                                           placeholder="e.g., Jan 2023 - Dec 2023"
                                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                    @error('date_range')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-gray-600 mb-1">Reference Number</label>
                                    <input type="text" 
                                           name="reference_number"
                                           value="{{ old('reference_number', $request->reference_number) }}"
                                           placeholder="If applicable"
                                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                    @error('reference_number')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Additional Requirements -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Additional Requirements</label>
                            <div class="space-y-2">
                                <label class="flex items-center">
                                    <input type="checkbox" name="notarization_required" value="1" {{ old('notarization_required', $request->notarization_required) ? 'checked' : '' }} class="mr-2 rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                    <span class="text-sm text-gray-700">Notarization Required</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="checkbox" name="apostille_needed" value="1" {{ old('apostille_needed', $request->apostille_needed) ? 'checked' : '' }} class="mr-2 rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                    <span class="text-sm text-gray-700">Apostille Needed</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="checkbox" name="translation_required" value="1" {{ old('translation_required', $request->translation_required) ? 'checked' : '' }} class="mr-2 rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                    <span class="text-sm text-gray-700">Translation Required</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="checkbox" name="certified_true_copy" value="1" {{ old('certified_true_copy', $request->certified_true_copy) ? 'checked' : '' }} class="mr-2 rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                    <span class="text-sm text-gray-700">Certified True Copy</span>
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- Delivery Information -->
                    <div class="space-y-4">
                        <h3 class="text-md font-semibold text-gray-900">Delivery Information</h3>
                        
                        <!-- Delivery Method -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Delivery Method *</label>
                            <select name="delivery_method" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                <option value="">Select Method</option>
                                <option value="Digital Download" {{ old('delivery_method', $request->delivery_method) === 'Digital Download' ? 'selected' : '' }}>Digital Download</option>
                                <option value="Email Attachment" {{ old('delivery_method', $request->delivery_method) === 'Email Attachment' ? 'selected' : '' }}>Email Attachment</option>
                                <option value="Office Pickup" {{ old('delivery_method', $request->delivery_method) === 'Office Pickup' ? 'selected' : '' }}>Office Pickup</option>
                                <option value="Courier Delivery" {{ old('delivery_method', $request->delivery_method) === 'Courier Delivery' ? 'selected' : '' }}>Courier Delivery</option>
                                <option value="Registered Mail" {{ old('delivery_method', $request->delivery_method) === 'Registered Mail' ? 'selected' : '' }}>Registered Mail</option>
                            </select>
                            @error('delivery_method')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Delivery Address -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Delivery Address</label>
                            <textarea name="delivery_address" 
                                      rows="2" 
                                      placeholder="Enter delivery address (if applicable)" 
                                      class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">{{ old('delivery_address', $request->delivery_address) }}</textarea>
                            @error('delivery_address')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Contact Information -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Contact Person *</label>
                                <input type="text" 
                                       name="contact_person" 
                                       value="{{ old('contact_person', $request->contact_person) }}"
                                       placeholder="Name of contact person" 
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                @error('contact_person')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Contact Number *</label>
                                <input type="tel" 
                                       name="contact_number" 
                                       value="{{ old('contact_number', $request->contact_number) }}"
                                       placeholder="Phone number" 
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                @error('contact_number')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Purpose & Justification -->
                    <div class="space-y-4">
                        <h3 class="text-md font-semibold text-gray-900">Purpose & Justification</h3>
                        
                        <!-- Purpose -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Purpose of Request *</label>
                            <textarea name="purpose" 
                                      rows="3" 
                                      placeholder="Explain why these documents are needed..." 
                                      class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">{{ old('purpose', $request->purpose) }}</textarea>
                            @error('purpose')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Project/Department -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Project Name</label>
                                <input type="text" 
                                       name="project_name" 
                                       value="{{ old('project_name', $request->project_name) }}"
                                       placeholder="Associated project (if applicable)" 
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                @error('project_name')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Cost Center</label>
                                <input type="text" 
                                       name="cost_center" 
                                       value="{{ old('cost_center', $request->cost_center) }}"
                                       placeholder="Department cost center" 
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                @error('cost_center')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Requester Information -->
                    <div class="space-y-4">
                        <h3 class="text-md font-semibold text-gray-900">Requester Information</h3>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Requested By *</label>
                                <input type="text" 
                                       name="requested_by" 
                                       value="{{ old('requested_by', $request->requested_by) }}"
                                       placeholder="Your name" 
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                @error('requested_by')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Department *</label>
                                <input type="text" 
                                       name="department" 
                                       value="{{ old('department', $request->department) }}"
                                       placeholder="Your department" 
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                @error('department')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Status -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Status *</label>
                            <select name="status" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                <option value="">Select Status</option>
                                <option value="Draft" {{ old('status', $request->status) === 'Draft' ? 'selected' : '' }}>Draft</option>
                                <option value="Submitted" {{ old('status', $request->status) === 'Submitted' ? 'selected' : '' }}>Submitted</option>
                                <option value="Under Review" {{ old('status', $request->status) === 'Under Review' ? 'selected' : '' }}>Under Review</option>
                                <option value="Approved" {{ old('status', $request->status) === 'Approved' ? 'selected' : '' }}>Approved</option>
                                <option value="Processing" {{ old('status', $request->status) === 'Processing' ? 'selected' : '' }}>Processing</option>
                                <option value="Completed" {{ old('status', $request->status) === 'Completed' ? 'selected' : '' }}>Completed</option>
                                <option value="Rejected" {{ old('status', $request->status) === 'Rejected' ? 'selected' : '' }}>Rejected</option>
                                <option value="Cancelled" {{ old('status', $request->status) === 'Cancelled' ? 'selected' : '' }}>Cancelled</option>
                            </select>
                            @error('status')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Notes -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Additional Notes</label>
                            <textarea name="notes" 
                                      rows="3" 
                                      placeholder="Any additional information..." 
                                      class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">{{ old('notes', $request->notes) }}</textarea>
                            @error('notes')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Submit Actions -->
                    <div class="flex gap-3 pt-4 border-t border-gray-200">
                        <a href="{{ route('document-requests.show', $request->id) }}" class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-medium py-2 px-6 rounded-lg transition-colors">
                            <i class='bx bx-x mr-2'></i>
                            Cancel
                        </a>
                        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-6 rounded-lg transition-colors">
                            <i class='bx bx-save mr-2'></i>
                            Update Request
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Current Request Info -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-900">Current Request Info</h2>
                </div>
                <div class="p-6">
                    <div class="space-y-4">
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600">Request ID</span>
                            <span class="text-sm font-medium text-gray-900">{{ $request->request_number }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600">Current Status</span>
                            <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-{{ $request->status_color }}-100 text-{{ $request->status_color }}-800">
                                {{ $request->status }}
                            </span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600">Created Date</span>
                            <span class="text-sm font-medium text-gray-900">{{ $request->created_at->format('M d, Y') }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600">Last Updated</span>
                            <span class="text-sm font-medium text-gray-900">{{ $request->updated_at->format('M d, Y') }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-900">Quick Actions</h2>
                </div>
                <div class="p-6">
                    <div class="space-y-3">
                        <a href="{{ route('document-requests.show', $request->id) }}" class="w-full bg-gray-200 hover:bg-gray-300 text-gray-700 font-medium py-2 px-4 rounded-lg flex items-center justify-center gap-2 transition-colors">
                            <i class='bx bx-show text-xl'></i>
                            View Request
                        </a>
                        
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
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
