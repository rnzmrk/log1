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
                    <a href="{{ route('admin.documenttracking.reports') }}" class="text-gray-700 hover:text-blue-600 ml-1 md:ml-2">Reports</a>
                </div>
            </li>
            <li aria-current="page">
                <div class="flex items-center">
                    <i class='bx bx-chevron-right text-gray-400'></i>
                    <span class="ml-1 text-gray-500 md:ml-2">Edit Report</span>
                </div>
            </li>
        </ol>
    </nav>

    <!-- Page Header -->
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-900">Edit Report</h1>
        <div class="flex gap-3">
            <a href="{{ route('document-reports.show', $report->id) }}" class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg flex items-center gap-2 transition-colors">
                <i class='bx bx-show text-xl'></i>
                View Report
            </a>
            <a href="{{ route('admin.documenttracking.reports') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-medium py-2 px-4 rounded-lg flex items-center gap-2 transition-colors">
                <i class='bx bx-arrow-back text-xl'></i>
                Back to Reports
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
            <h2 class="text-lg font-semibold text-gray-900">Edit Report Information</h2>
        </div>
        
        <form method="POST" action="{{ route('document-reports.update', $report->id) }}" class="p-6 space-y-6">
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

            <!-- Basic Information -->
            <div class="space-y-4">
                <h3 class="text-md font-semibold text-gray-900">Basic Information</h3>
                
                <!-- Report Name -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Report Name *</label>
                    <input type="text" 
                           name="report_name"
                           value="{{ old('report_name', $report->report_name) }}"
                           placeholder="Enter report name" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    @error('report_name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Report Type and Status -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Report Type *</label>
                        <select name="report_type" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="">Select Type</option>
                            <option value="Summary" {{ old('report_type', $report->report_type) === 'Summary' ? 'selected' : '' }}>Summary Reports</option>
                            <option value="Storage" {{ old('report_type', $report->report_type) === 'Storage' ? 'selected' : '' }}>Storage Reports</option>
                            <option value="Access" {{ old('report_type', $report->report_type) === 'Access' ? 'selected' : '' }}>Access Logs</option>
                            <option value="Compliance" {{ old('report_type', $report->report_type) === 'Compliance' ? 'selected' : '' }}>Compliance Reports</option>
                            <option value="Upload" {{ old('report_type', $report->report_type) === 'Upload' ? 'selected' : '' }}>Upload Activity</option>
                            <option value="Document Requests" {{ old('report_type', $report->report_type) === 'Document Requests' ? 'selected' : '' }}>Document Requests</option>
                        </select>
                        @error('report_type')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Status *</label>
                        <select name="status" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="">Select Status</option>
                            <option value="Completed" {{ old('status', $report->status) === 'Completed' ? 'selected' : '' }}>Completed</option>
                            <option value="Processing" {{ old('status', $report->status) === 'Processing' ? 'selected' : '' }}>Processing</option>
                            <option value="Scheduled" {{ old('status', $report->status) === 'Scheduled' ? 'selected' : '' }}>Scheduled</option>
                            <option value="Failed" {{ old('status', $report->status) === 'Failed' ? 'selected' : '' }}>Failed</option>
                            <option value="Cancelled" {{ old('status', $report->status) === 'Cancelled' ? 'selected' : '' }}>Cancelled</option>
                        </select>
                        @error('status')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Generated By and Department -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Generated By *</label>
                        <input type="text" 
                               name="generated_by"
                               value="{{ old('generated_by', $report->generated_by) }}"
                               placeholder="Enter your name" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        @error('generated_by')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Department *</label>
                        <select name="department" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="">Select Department</option>
                            <option value="Operations" {{ old('department', $report->department) === 'Operations' ? 'selected' : '' }}>Operations</option>
                            <option value="Finance" {{ old('department', $report->department) === 'Finance' ? 'selected' : '' }}>Finance</option>
                            <option value="HR" {{ old('department', $report->department) === 'HR' ? 'selected' : '' }}>HR</option>
                            <option value="IT" {{ old('department', $report->department) === 'IT' ? 'selected' : '' }}>IT</option>
                            <option value="Legal" {{ old('department', $report->department) === 'Legal' ? 'selected' : '' }}>Legal</option>
                            <option value="Security" {{ old('department', $report->department) === 'Security' ? 'selected' : '' }}>Security</option>
                        </select>
                        @error('department')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Report Date -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Report Date *</label>
                    <input type="date" 
                           name="report_date"
                           value="{{ old('report_date', $report->report_date->format('Y-m-d')) }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    @error('report_date')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Date Range -->
            <div class="space-y-4">
                <h3 class="text-md font-semibold text-gray-900">Data Period</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Start Date</label>
                        <input type="date" 
                               name="start_date"
                               value="{{ old('start_date', $report->start_date?->format('Y-m-d')) }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        @error('start_date')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">End Date</label>
                        <input type="date" 
                               name="end_date"
                               value="{{ old('end_date', $report->end_date?->format('Y-m-d')) }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        @error('end_date')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Description -->
            <div class="space-y-4">
                <h3 class="text-md font-semibold text-gray-900">Description</h3>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Report Description</label>
                    <textarea name="description" 
                              rows="4"
                              placeholder="Provide a detailed description of the report content and purpose..." 
                              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">{{ old('description', $report->description) }}</textarea>
                    @error('description')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Parameters -->
            <div class="space-y-4">
                <h3 class="text-md font-semibold text-gray-900">Additional Parameters</h3>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Parameters</label>
                    <textarea name="parameters" 
                              rows="3"
                              placeholder="Any additional parameters or special instructions..." 
                              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">{{ old('parameters', $report->parameters) }}</textarea>
                    @error('parameters')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Submit Actions -->
            <div class="flex gap-3 pt-4 border-t border-gray-200">
                <a href="{{ route('document-reports.show', $report->id) }}" class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-medium py-2 px-6 rounded-lg transition-colors">
                    <i class='bx bx-x mr-2'></i>
                    Cancel
                </a>
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-6 rounded-lg transition-colors">
                    <i class='bx bx-save mr-2'></i>
                    Update Report
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
