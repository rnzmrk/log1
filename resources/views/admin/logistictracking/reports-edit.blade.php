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
                    <a href="#" class="text-gray-700 hover:text-blue-600 ml-1 md:ml-2">Logistic Tracking</a>
                </div>
            </li>
            <li>
                <div class="flex items-center">
                    <i class='bx bx-chevron-right text-gray-400'></i>
                    <a href="{{ route('admin.logistictracking.reports') }}" class="text-gray-700 hover:text-blue-600 ml-1 md:ml-2">Reports</a>
                </div>
            </li>
            <li>
                <div class="flex items-center">
                    <i class='bx bx-chevron-right text-gray-400'></i>
                    <a href="{{ route('logistics-reports.show', $report->id) }}" class="text-gray-700 hover:text-blue-600 ml-1 md:ml-2">{{ $report->report_name }}</a>
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
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Edit Report</h1>
            <p class="text-gray-600 mt-1">Report ID: {{ $report->report_number }}</p>
        </div>
        <div class="flex gap-3">
            <a href="{{ route('logistics-reports.show', $report->id) }}" class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-medium py-2 px-4 rounded-lg flex items-center gap-2 transition-colors">
                <i class='bx bx-arrow-back text-xl'></i>
                Back to Report
            </a>
        </div>
    </div>

    <!-- Form Container -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-lg font-semibold text-gray-900">Edit Report Information</h2>
        </div>
        
        <form method="POST" action="{{ route('logistics-reports.update', $report->id) }}" class="p-6">
            @csrf
            @method('PUT')
            
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

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Report Name -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Report Name *</label>
                    <input type="text" 
                           name="report_name" 
                           value="{{ old('report_name', $report->report_name) }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                           placeholder="Enter report name">
                    @error('report_name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Report Type -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Report Type *</label>
                    <select name="report_type" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="">Select Report Type</option>
                        <option value="Delivery" {{ old('report_type', $report->report_type) === 'Delivery' ? 'selected' : '' }}>Delivery Report</option>
                        <option value="Vehicle" {{ old('report_type', $report->report_type) === 'Vehicle' ? 'selected' : '' }}>Vehicle Report</option>
                        <option value="Project" {{ old('report_type', $report->report_type) === 'Project' ? 'selected' : '' }}>Project Report</option>
                        <option value="Performance" {{ old('report_type', $report->report_type) === 'Performance' ? 'selected' : '' }}>Performance Report</option>
                        <option value="Financial" {{ old('report_type', $report->report_type) === 'Financial' ? 'selected' : '' }}>Financial Report</option>
                        <option value="Inventory" {{ old('report_type', $report->report_type) === 'Inventory' ? 'selected' : '' }}>Inventory Report</option>
                        <option value="Maintenance" {{ old('report_type', $report->report_type) === 'Maintenance' ? 'selected' : '' }}>Maintenance Report</option>
                    </select>
                    @error('report_type')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Status -->
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

                <!-- Priority -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Priority *</label>
                    <select name="priority" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="">Select Priority</option>
                        <option value="Low" {{ old('priority', $report->priority) === 'Low' ? 'selected' : '' }}>Low</option>
                        <option value="Medium" {{ old('priority', $report->priority) === 'Medium' ? 'selected' : '' }}>Medium</option>
                        <option value="High" {{ old('priority', $report->priority) === 'High' ? 'selected' : '' }}>High</option>
                        <option value="Urgent" {{ old('priority', $report->priority) === 'Urgent' ? 'selected' : '' }}>Urgent</option>
                    </select>
                    @error('priority')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Department -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Department *</label>
                    <select name="department" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="">Select Department</option>
                        <option value="Operations" {{ old('department', $report->department) === 'Operations' ? 'selected' : '' }}>Operations</option>
                        <option value="Logistics" {{ old('department', $report->department) === 'Logistics' ? 'selected' : '' }}>Logistics</option>
                        <option value="IT" {{ old('department', $report->department) === 'IT' ? 'selected' : '' }}>IT</option>
                        <option value="Finance" {{ old('department', $report->department) === 'Finance' ? 'selected' : '' }}>Finance</option>
                        <option value="HR" {{ old('department', $report->department) === 'HR' ? 'selected' : '' }}>HR</option>
                        <option value="Project Management" {{ old('department', $report->department) === 'Project Management' ? 'selected' : '' }}>Project Management</option>
                        <option value="Warehouse" {{ old('department', $report->department) === 'Warehouse' ? 'selected' : '' }}>Warehouse</option>
                        <option value="Maintenance" {{ old('department', $report->department) === 'Maintenance' ? 'selected' : '' }}>Maintenance</option>
                    </select>
                    @error('department')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Generated By -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Generated By *</label>
                    <input type="text" 
                           name="generated_by" 
                           value="{{ old('generated_by', $report->generated_by) }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                           placeholder="Enter your name">
                    @error('generated_by')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
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

                <!-- Start Date -->
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

                <!-- End Date -->
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

            <!-- Additional Fields for Completed Reports -->
            @if($report->status === 'Completed' || old('status') === 'Completed')
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
                <!-- Total Records -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Total Records</label>
                    <input type="number" 
                           name="total_records" 
                           value="{{ old('total_records', $report->total_records) }}"
                           min="0"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                           placeholder="Enter total records">
                    @error('total_records')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Success Rate -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Success Rate (%)</label>
                    <input type="number" 
                           name="success_rate" 
                           value="{{ old('success_rate', $report->success_rate) }}"
                           min="0"
                           max="100"
                           step="0.01"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                           placeholder="Enter success rate">
                    @error('success_rate')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>
            @endif

            <!-- Description -->
            <div class="mt-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                <textarea name="description" 
                          rows="4" 
                          class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                          placeholder="Enter report description...">{{ old('description', $report->description) }}</textarea>
                @error('description')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Notes -->
            <div class="mt-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">Additional Notes</label>
                <textarea name="notes" 
                          rows="3" 
                          class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                          placeholder="Enter any additional notes...">{{ old('notes', $report->notes) }}</textarea>
                @error('notes')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Form Actions -->
            <div class="flex justify-end gap-3 mt-8">
                <a href="{{ route('logistics-reports.show', $report->id) }}" class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-medium py-2 px-6 rounded-lg transition-colors">
                    Cancel
                </a>
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-6 rounded-lg transition-colors">
                    <i class='bx bx-save text-lg mr-2'></i>
                    Update Report
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
