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
                    <a href="{{ route('project-planning.index') }}" class="text-gray-700 hover:text-blue-600 ml-1 md:ml-2">Project Planning & Request</a>
                </div>
            </li>
            <li aria-current="page">
                <div class="flex items-center">
                    <i class='bx bx-chevron-right text-gray-400'></i>
                    <span class="ml-1 text-gray-500 md:ml-2">Edit Project</span>
                </div>
            </li>
        </ol>
    </nav>

    <!-- Page Header -->
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-900">Edit Project</h1>
        <div class="flex gap-3">
            <a href="{{ route('project-planning.index') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-medium py-2 px-4 rounded-lg flex items-center gap-2 transition-colors">
                <i class='bx bx-arrow-left text-xl'></i>
                Back to List
            </a>
        </div>
    </div>

    <!-- Project Form Container -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Form -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-900">Edit Project Details</h2>
                </div>
                <form method="POST" action="{{ route('project-planning.update', $project->id) }}" class="p-6 space-y-6">
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

                    <!-- Basic Information -->
                    <div class="space-y-4">
                        <h3 class="text-md font-semibold text-gray-900">Basic Information</h3>
                        
                        <!-- Project Name -->
                        <div>
                            <label for="project_name" class="block text-sm font-medium text-gray-700 mb-2">Project Name *</label>
                            <input type="text" 
                                   id="project_name"
                                   name="project_name" 
                                   value="{{ old('project_name', $project->project_name) }}"
                                   placeholder="Enter project name" 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                   required>
                        </div>

                        <!-- Project Description -->
                        <div>
                            <label for="project_description" class="block text-sm font-medium text-gray-700 mb-2">Project Description *</label>
                            <textarea id="project_description"
                                      name="project_description" 
                                      rows="4"
                                      placeholder="Provide detailed description of the project..." 
                                      class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                      required>{{ old('project_description', $project->project_description) }}</textarea>
                        </div>

                        <!-- Project Type and Priority -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="project_type" class="block text-sm font-medium text-gray-700 mb-2">Project Type *</label>
                                <select id="project_type"
                                        name="project_type" 
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                        required>
                                    <option value="">Select Type</option>
                                    @foreach(config('categories.project_types', ['Construction', 'Renovation', 'Maintenance', 'Installation', 'Inspection', 'Other']) as $type)
                                        <option value="{{ $type }}" {{ old('project_type', $project->project_type) === $type ? 'selected' : '' }}>{{ $type }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label for="priority" class="block text-sm font-medium text-gray-700 mb-2">Priority *</label>
                                <select id="priority"
                                        name="priority" 
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                        required>
                                    <option value="">Select Priority</option>
                                    @foreach(config('categories.priorities', ['Low', 'Medium', 'High', 'Critical']) as $priority)
                                        <option value="{{ $priority }}" {{ old('priority', $project->priority) === $priority ? 'selected' : '' }}>{{ $priority }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <!-- Status -->
                        <div>
                            <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Status *</label>
                            <select id="status"
                                    name="status" 
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                    required>
                                <option value="">Select Status</option>
                                <option value="Draft" {{ old('status', $project->status) === 'Draft' ? 'selected' : '' }}>Draft</option>
                                <option value="Submitted" {{ old('status', $project->status) === 'Submitted' ? 'selected' : '' }}>Submitted</option>
                                <option value="Under Review" {{ old('status', $project->status) === 'Under Review' ? 'selected' : '' }}>Under Review</option>
                                <option value="Approved" {{ old('status', $project->status) === 'Approved' ? 'selected' : '' }}>Approved</option>
                                <option value="In Progress" {{ old('status', $project->status) === 'In Progress' ? 'selected' : '' }}>In Progress</option>
                                <option value="On Hold" {{ old('status', $project->status) === 'On Hold' ? 'selected' : '' }}>On Hold</option>
                                <option value="Completed" {{ old('status', $project->status) === 'Completed' ? 'selected' : '' }}>Completed</option>
                                <option value="Cancelled" {{ old('status', $project->status) === 'Cancelled' ? 'selected' : '' }}>Cancelled</option>
                            </select>
                        </div>
                    </div>

                    <!-- Timeline -->
                    <div class="space-y-4">
                        <h3 class="text-md font-semibold text-gray-900">Project Timeline</h3>
                        
                        <!-- Start and End Dates -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="start_date" class="block text-sm font-medium text-gray-700 mb-2">Start Date *</label>
                                <input type="date" 
                                       id="start_date"
                                       name="start_date"
                                       value="{{ old('start_date', $project->start_date ? $project->start_date->format('Y-m-d') : '') }}"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                       required>
                            </div>
                            <div>
                                <label for="end_date" class="block text-sm font-medium text-gray-700 mb-2">End Date *</label>
                                <input type="date" 
                                       id="end_date"
                                       name="end_date"
                                       value="{{ old('end_date', $project->end_date ? $project->end_date->format('Y-m-d') : '') }}"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                       required>
                            </div>
                        </div>
                    </div>

                    <!-- Location Information -->
                    <div class="space-y-4">
                        <h3 class="text-md font-semibold text-gray-900">Location Information</h3>
                        
                        <!-- Project Address -->
                        <div>
                            <label for="project_address" class="block text-sm font-medium text-gray-700 mb-2">Project Address *</label>
                            <input type="text" 
                                   id="project_address"
                                   name="project_address"
                                   value="{{ old('project_address', $project->project_address) }}"
                                   placeholder="Enter project address" 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                   required>
                        </div>

                        <!-- City, State, ZIP -->
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <label for="project_city" class="block text-sm font-medium text-gray-700 mb-2">City *</label>
                                <input type="text" 
                                       id="project_city"
                                       name="project_city"
                                       value="{{ old('project_city', $project->project_city) }}"
                                       placeholder="City" 
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                       required>
                            </div>
                            <div>
                                <label for="project_state" class="block text-sm font-medium text-gray-700 mb-2">State/Province *</label>
                                <input type="text" 
                                       id="project_state"
                                       name="project_state"
                                       value="{{ old('project_state', $project->project_state) }}"
                                       placeholder="State" 
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                       required>
                            </div>
                            <div>
                                <label for="project_zipcode" class="block text-sm font-medium text-gray-700 mb-2">ZIP/Postal Code *</label>
                                <input type="text" 
                                       id="project_zipcode"
                                       name="project_zipcode"
                                       value="{{ old('project_zipcode', $project->project_zipcode) }}"
                                       placeholder="ZIP Code" 
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                       required>
                            </div>
                        </div>
                    </div>

                    <!-- Requester Information -->
                    <div class="space-y-4">
                        <h3 class="text-md font-semibold text-gray-900">Requester Information</h3>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="requested_by" class="block text-sm font-medium text-gray-700 mb-2">Requested By *</label>
                                <input type="text" 
                                       id="requested_by"
                                       name="requested_by"
                                       value="{{ old('requested_by', $project->requested_by) }}"
                                       placeholder="Your name" 
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                       required>
                            </div>
                            <div>
                                <label for="department" class="block text-sm font-medium text-gray-700 mb-2">Department *</label>
                                <input type="text" 
                                       id="department"
                                       name="department"
                                       value="{{ old('department', $project->department) }}"
                                       placeholder="Your department" 
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                       required>
                            </div>
                        </div>

                        <!-- Approved By (only show if project is approved) -->
                        @if($project->status === 'Approved')
                            <div>
                                <label for="approved_by" class="block text-sm font-medium text-gray-700 mb-2">Approved By</label>
                                <input type="text" 
                                       id="approved_by"
                                       name="approved_by"
                                       value="{{ old('approved_by', $project->approved_by) }}"
                                       placeholder="Name of approver" 
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            </div>
                        @endif

                        <div>
                            <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">Additional Notes</label>
                            <textarea id="notes"
                                      name="notes" 
                                      rows="3"
                                      placeholder="Any additional notes or special requirements..." 
                                      class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">{{ old('notes', $project->notes) }}</textarea>
                        </div>
                    </div>

                    <!-- Submit Actions -->
                    <div class="flex gap-3 pt-4 border-t border-gray-200">
                        <a href="{{ route('project-planning.index') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-medium py-2 px-6 rounded-lg transition-colors">
                            <i class='bx bx-x mr-2'></i>
                            Cancel
                        </a>
                        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-6 rounded-lg transition-colors">
                            <i class='bx bx-save mr-2'></i>
                            Update Project
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Project Summary -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-900">Project Summary</h2>
                </div>
                <div class="p-6">
                    <div class="space-y-4">
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600">Project ID</span>
                            <span class="text-sm font-medium text-gray-900">{{ $project->project_number ?: '#' . $project->id }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600">Created Date</span>
                            <span class="text-sm font-medium text-gray-900">{{ $project->created_at->format('M j, Y') }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600">Last Updated</span>
                            <span class="text-sm font-medium text-gray-900">{{ $project->updated_at->format('M j, Y') }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600">Status</span>
                            <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full
                                @if($project->status === 'Draft') bg-gray-100 text-gray-800
                                @elseif($project->status === 'Submitted') bg-blue-100 text-blue-800
                                @elseif($project->status === 'Under Review') bg-orange-100 text-orange-800
                                @elseif($project->status === 'Approved') bg-green-100 text-green-800
                                @elseif($project->status === 'In Progress') bg-purple-100 text-purple-800
                                @elseif($project->status === 'On Hold') bg-yellow-100 text-yellow-800
                                @elseif($project->status === 'Completed') bg-teal-100 text-teal-800
                                @elseif($project->status === 'Cancelled') bg-red-100 text-red-800
                                @endif">
                                {{ $project->status }}
                            </span>
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
                        <a href="{{ route('project-planning.show', $project->id) }}" class="w-full text-left px-4 py-3 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors flex items-center gap-3">
                            <i class='bx bx-show text-blue-600 text-xl'></i>
                            <div>
                                <p class="font-medium text-gray-900 text-sm">View Project</p>
                                <p class="text-xs text-gray-600">See full details</p>
                            </div>
                        </a>
                        <button onclick="window.print()" class="w-full text-left px-4 py-3 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors flex items-center gap-3">
                            <i class='bx bx-printer text-green-600 text-xl'></i>
                            <div>
                                <p class="font-medium text-gray-900 text-sm">Print Project</p>
                                <p class="text-xs text-gray-600">Generate PDF</p>
                            </div>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
