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
                    <span class="ml-1 text-gray-500 md:ml-2">Project Details</span>
                </div>
            </li>
        </ol>
    </nav>

    <!-- Page Header -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">{{ $project->project_name }}</h1>
            <p class="text-gray-600 mt-1">Project ID: {{ $project->project_number ?: '#' . $project->id }}</p>
        </div>
        <div class="flex gap-3">
            <a href="{{ route('project-planning.index') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-medium py-2 px-4 rounded-lg flex items-center gap-2 transition-colors">
                <i class='bx bx-arrow-left text-xl'></i>
                Back to List
            </a>
            <a href="{{ route('project-planning.edit', $project->id) }}" class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg flex items-center gap-2 transition-colors">
                <i class='bx bx-edit text-xl'></i>
                Edit Project
            </a>
        </div>
    </div>

    <!-- Status Badge -->
    <div class="mb-6">
        <span class="px-4 py-2 inline-flex text-sm leading-5 font-semibold rounded-full
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

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Basic Information -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-900">Basic Information</h2>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <h3 class="text-sm font-medium text-gray-500 mb-1">Project Name</h3>
                            <p class="text-gray-900">{{ $project->project_name }}</p>
                        </div>
                        <div>
                            <h3 class="text-sm font-medium text-gray-500 mb-1">Project Type</h3>
                            <p class="text-gray-900">{{ $project->project_type }}</p>
                        </div>
                        <div>
                            <h3 class="text-sm font-medium text-gray-500 mb-1">Priority</h3>
                            <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full
                                @if($project->priority === 'Low') bg-gray-100 text-gray-800
                                @elseif($project->priority === 'Medium') bg-blue-100 text-blue-800
                                @elseif($project->priority === 'High') bg-orange-100 text-orange-800
                                @elseif($project->priority === 'Critical') bg-red-100 text-red-800
                                @endif">
                                {{ $project->priority }}
                            </span>
                        </div>
                        <div>
                            <h3 class="text-sm font-medium text-gray-500 mb-1">Status</h3>
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
                    <div class="mt-6">
                        <h3 class="text-sm font-medium text-gray-500 mb-1">Project Description</h3>
                        <p class="text-gray-900 whitespace-pre-wrap">{{ $project->project_description ?: 'No description provided' }}</p>
                    </div>
                </div>
            </div>

            <!-- Timeline Information -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-900">Project Timeline</h2>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <h3 class="text-sm font-medium text-gray-500 mb-1">Start Date</h3>
                            <p class="text-gray-900">{{ $project->start_date ? \Carbon\Carbon::parse($project->start_date)->format('F j, Y') : 'Not specified' }}</p>
                        </div>
                        <div>
                            <h3 class="text-sm font-medium text-gray-500 mb-1">End Date</h3>
                            <p class="text-gray-900">{{ $project->end_date ? \Carbon\Carbon::parse($project->end_date)->format('F j, Y') : 'Not specified' }}</p>
                        </div>
                    </div>
                    @if($project->start_date && $project->end_date)
                        <div class="mt-6">
                            <h3 class="text-sm font-medium text-gray-500 mb-1">Duration</h3>
                            <p class="text-gray-900">
                                {{ \Carbon\Carbon::parse($project->start_date)->diffInDays(\Carbon\Carbon::parse($project->end_date)) }} days
                                ({{ \Carbon\Carbon::parse($project->start_date)->diffInWeeks(\Carbon\Carbon::parse($project->end_date)) }} weeks)
                            </p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Location Information -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-900">Location Information</h2>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="md:col-span-2">
                            <h3 class="text-sm font-medium text-gray-500 mb-1">Project Address</h3>
                            <p class="text-gray-900">{{ $project->project_address }}</p>
                        </div>
                        <div>
                            <h3 class="text-sm font-medium text-gray-500 mb-1">City</h3>
                            <p class="text-gray-900">{{ $project->project_city }}</p>
                        </div>
                        <div>
                            <h3 class="text-sm font-medium text-gray-500 mb-1">State/Province</h3>
                            <p class="text-gray-900">{{ $project->project_state }}</p>
                        </div>
                        <div>
                            <h3 class="text-sm font-medium text-gray-500 mb-1">ZIP/Postal Code</h3>
                            <p class="text-gray-900">{{ $project->project_zipcode }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Additional Information -->
            @if($project->notes)
                <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h2 class="text-lg font-semibold text-gray-900">Additional Notes</h2>
                    </div>
                    <div class="p-6">
                        <p class="text-gray-900 whitespace-pre-wrap">{{ $project->notes }}</p>
                    </div>
                </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Requester Information -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-900">Requester Information</h2>
                </div>
                <div class="p-6">
                    <div class="space-y-4">
                        <div>
                            <h3 class="text-sm font-medium text-gray-500 mb-1">Requested By</h3>
                            <p class="text-gray-900">{{ $project->requested_by }}</p>
                        </div>
                        <div>
                            <h3 class="text-sm font-medium text-gray-500 mb-1">Department</h3>
                            <p class="text-gray-900">{{ $project->department }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Approval Information -->
            @if($project->status === 'Approved' || $project->approved_by)
                <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h2 class="text-lg font-semibold text-gray-900">Approval Information</h2>
                    </div>
                    <div class="p-6">
                        <div class="space-y-4">
                            @if($project->approved_by)
                                <div>
                                    <h3 class="text-sm font-medium text-gray-500 mb-1">Approved By</h3>
                                    <p class="text-gray-900">{{ $project->approved_by }}</p>
                                </div>
                            @endif
                            @if($project->approved_date)
                                <div>
                                    <h3 class="text-sm font-medium text-gray-500 mb-1">Approval Date</h3>
                                    <p class="text-gray-900">{{ \Carbon\Carbon::parse($project->approved_date)->format('F j, Y') }}</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @endif

            <!-- Project Metadata -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-900">Project Metadata</h2>
                </div>
                <div class="p-6">
                    <div class="space-y-4">
                        <div>
                            <h3 class="text-sm font-medium text-gray-500 mb-1">Project ID</h3>
                            <p class="text-gray-900">{{ $project->project_number ?: '#' . $project->id }}</p>
                        </div>
                        <div>
                            <h3 class="text-sm font-medium text-gray-500 mb-1">Created Date</h3>
                            <p class="text-gray-900">{{ $project->created_at->format('F j, Y g:i A') }}</p>
                        </div>
                        <div>
                            <h3 class="text-sm font-medium text-gray-500 mb-1">Last Updated</h3>
                            <p class="text-gray-900">{{ $project->updated_at->format('F j, Y g:i A') }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-900">Actions</h2>
                </div>
                <div class="p-6">
                    <div class="space-y-3">
                        <a href="{{ route('project-planning.edit', $project->id) }}" class="w-full text-left px-4 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors flex items-center gap-3">
                            <i class='bx bx-edit text-xl'></i>
                            <div>
                                <p class="font-medium">Edit Project</p>
                                <p class="text-xs opacity-90">Update project details</p>
                            </div>
                        </a>
                        <button onclick="window.print()" class="w-full text-left px-4 py-3 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors flex items-center gap-3">
                            <i class='bx bx-printer text-gray-600 text-xl'></i>
                            <div>
                                <p class="font-medium text-gray-900">Print Project</p>
                                <p class="text-xs text-gray-600">Generate PDF copy</p>
                            </div>
                        </button>
                        <form action="{{ route('project-planning.destroy', $project->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this project? This action cannot be undone.')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="w-full text-left px-4 py-3 border border-red-200 rounded-lg hover:bg-red-50 transition-colors flex items-center gap-3">
                                <i class='bx bx-trash text-red-600 text-xl'></i>
                                <div>
                                    <p class="font-medium text-red-600">Delete Project</p>
                                    <p class="text-xs text-red-500">Permanently remove</p>
                                </div>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
