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
                    <span class="ml-1 text-gray-500 md:ml-2">New Project</span>
                </div>
            </li>
        </ol>
    </nav>

    <!-- Page Header -->
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-900">Project Planning & Request</h1>
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
                    <h2 class="text-lg font-semibold text-gray-900">Project Details</h2>
                </div>
                <form method="POST" action="{{ route('project-planning.store') }}" class="p-6 space-y-6">
                    @csrf
                    
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
                                   value="{{ old('project_name') }}"
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
                                      required>{{ old('project_description') }}</textarea>
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
                                    @foreach(config('categories.project_types') as $type)
                                        <option value="{{ $type }}" {{ old('project_type') === $type ? 'selected' : '' }}>{{ $type }}</option>
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
                                    @foreach(config('categories.priorities') as $priority)
                                        <option value="{{ $priority }}" {{ old('priority') === $priority ? 'selected' : '' }}>{{ $priority }}</option>
                                    @endforeach
                                </select>
                            </div>
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
                                       value="{{ old('start_date') }}"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                       required>
                            </div>
                            <div>
                                <label for="end_date" class="block text-sm font-medium text-gray-700 mb-2">End Date *</label>
                                <input type="date" 
                                       id="end_date"
                                       name="end_date"
                                       value="{{ old('end_date') }}"
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
                                   value="{{ old('project_address') }}"
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
                                       value="{{ old('project_city') }}"
                                       placeholder="City" 
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                       required>
                            </div>
                            <div>
                                <label for="project_state" class="block text-sm font-medium text-gray-700 mb-2">State/Province *</label>
                                <input type="text" 
                                       id="project_state"
                                       name="project_state"
                                       value="{{ old('project_state') }}"
                                       placeholder="State" 
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                       required>
                            </div>
                            <div>
                                <label for="project_zipcode" class="block text-sm font-medium text-gray-700 mb-2">ZIP/Postal Code *</label>
                                <input type="text" 
                                       id="project_zipcode"
                                       name="project_zipcode"
                                       value="{{ old('project_zipcode') }}"
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
                                       value="{{ old('requested_by', 'John Doe') }}"
                                       placeholder="Your name" 
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                       required>
                            </div>
                            <div>
                                <label for="department" class="block text-sm font-medium text-gray-700 mb-2">Department *</label>
                                <input type="text" 
                                       id="department"
                                       name="department"
                                       value="{{ old('department', 'Operations') }}"
                                       placeholder="Your department" 
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                       required>
                            </div>
                        </div>

                        <div>
                            <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">Additional Notes</label>
                            <textarea id="notes"
                                      name="notes" 
                                      rows="3"
                                      placeholder="Any additional notes or special requirements..." 
                                      class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">{{ old('notes') }}</textarea>
                        </div>
                    </div>

                    <!-- Submit Actions -->
                    <div class="flex gap-3 pt-4 border-t border-gray-200">
                        <a href="{{ route('project-planning.index') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-medium py-2 px-6 rounded-lg transition-colors">
                            <i class='bx bx-x mr-2'></i>
                            Cancel
                        </a>
                        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-6 rounded-lg transition-colors">
                            <i class='bx bx-send mr-2'></i>
                            Submit Project
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
                            <span class="text-sm font-medium text-gray-900">Auto-generated</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600">Requester</span>
                            <span class="text-sm font-medium text-gray-900">{{ old('requested_by', 'John Doe') }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600">Department</span>
                            <span class="text-sm font-medium text-gray-900">{{ old('department', 'Operations') }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600">Date</span>
                            <span class="text-sm font-medium text-gray-900">{{ now()->format('M j, Y') }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600">Status</span>
                            <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                Draft
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Active Projects -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-900">Recent Projects</h2>
                </div>
                <div class="p-6">
                    <div class="space-y-4">
                        @php
                            $recentProjects = \App\Models\ProjectPlanning::orderBy('created_at', 'desc')->limit(3)->get();
                        @endphp
                        @if($recentProjects->count() > 0)
                            @foreach($recentProjects as $recentProject)
                                <div class="border border-gray-200 rounded-lg p-3">
                                    <div class="flex items-center justify-between mb-2">
                                        <h4 class="font-medium text-gray-900 text-sm">{{ $recentProject->project_name }}</h4>
                                        <span class="text-xs 
                                            @if($recentProject->status === 'Approved') bg-green-100 text-green-800 
                                            @elseif($recentProject->status === 'Under Review') bg-orange-100 text-orange-800 
                                            @elseif($recentProject->status === 'Draft') bg-gray-100 text-gray-800 
                                            @else bg-blue-100 text-blue-800 @endif 
                                            px-2 py-1 rounded-full">{{ $recentProject->status }}</span>
                                    </div>
                                    <p class="text-xs text-gray-600">ID: {{ $recentProject->project_number }}</p>
                                </div>
                            @endforeach
                        @else
                            <p class="text-sm text-gray-500">No recent projects found.</p>
                        @endif
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
                                <p class="font-medium text-gray-900 text-sm">Project Guidelines</p>
                                <p class="text-xs text-gray-600">View policies</p>
                            </div>
                        </button>
                        <button class="w-full text-left px-4 py-3 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors flex items-center gap-3">
                            <i class='bx bx-message text-green-600 text-xl'></i>
                            <div>
                                <p class="font-medium text-gray-900 text-sm">Contact Project Manager</p>
                                <p class="text-xs text-gray-600">Get assistance</p>
                            </div>
                        </button>
                        <button class="w-full text-left px-4 py-3 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors flex items-center gap-3">
                            <i class='bx bx-history text-orange-600 text-xl'></i>
                            <div>
                                <p class="font-medium text-gray-900 text-sm">My Previous Projects</p>
                                <p class="text-xs text-gray-600">View history</p>
                            </div>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
    <!-- Data Table Section -->
    <div class="mt-8">
        <div class="bg-white rounded-lg shadow-sm border border-gray-200">
            <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                <h2 class="text-lg font-semibold text-gray-900">Existing Project Requests</h2>
                <div class="flex items-center gap-3">
                    <!-- Search -->
                    <div class="relative">
                        <input type="text" 
                               id="searchInput" 
                               placeholder="Search projects..." 
                               class="pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <i class='bx bx-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400'></i>
                    </div>
                    <!-- Filters -->
                    <select id="statusFilter" class="px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="">All Status</option>
                        <option value="Draft">Draft</option>
                        <option value="Submitted">Submitted</option>
                        <option value="Under Review">Under Review</option>
                        <option value="Approved">Approved</option>
                        <option value="In Progress">In Progress</option>
                        <option value="On Hold">On Hold</option>
                        <option value="Completed">Completed</option>
                        <option value="Cancelled">Cancelled</option>
                    </select>
                    <select id="priorityFilter" class="px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="">All Priority</option>
                        <option value="Low">Low</option>
                        <option value="Medium">Medium</option>
                        <option value="High">High</option>
                        <option value="Critical">Critical</option>
                    </select>
                    <button onclick="refreshTable()" class="bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium py-2 px-4 rounded-lg transition-colors flex items-center gap-2">
                        <i class='bx bx-refresh text-xl'></i>
                        Refresh
                    </button>
                </div>
            </div>
            
            <!-- Table -->
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Project ID</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Project Name</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Priority</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Start Date</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">End Date</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Requested By</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="projectTableBody" class="bg-white divide-y divide-gray-200">
                        <!-- Data will be loaded here -->
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            <div class="px-6 py-4 border-t border-gray-200 flex justify-between items-center">
                <div class="text-sm text-gray-700">
                    Showing <span id="showingFrom">0</span> to <span id="showingTo">0</span> of <span id="totalRecords">0</span> results
                </div>
                <div class="flex gap-2" id="paginationLinks">
                    <!-- Pagination will be loaded here -->
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('styles')
<style>
.status-badge {
    @apply px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full;
}
.status-draft { @apply bg-gray-100 text-gray-800; }
.status-submitted { @apply bg-blue-100 text-blue-800; }
.status-under-review { @apply bg-orange-100 text-orange-800; }
.status-approved { @apply bg-green-100 text-green-800; }
.status-in-progress { @apply bg-purple-100 text-purple-800; }
.status-on-hold { @apply bg-yellow-100 text-yellow-800; }
.status-completed { @apply bg-teal-100 text-teal-800; }
.status-cancelled { @apply bg-red-100 text-red-800; }

.priority-low { @apply bg-gray-100 text-gray-800; }
.priority-medium { @apply bg-blue-100 text-blue-800; }
.priority-high { @apply bg-orange-100 text-orange-800; }
.priority-critical { @apply bg-red-100 text-red-800; }
</style>
@endpush

@push('scripts')
<script>
let currentPage = 1;
let searchTimeout;

// Load initial data
document.addEventListener('DOMContentLoaded', function() {
    loadProjects();
});

// Search input
document.getElementById('searchInput').addEventListener('input', function() {
    clearTimeout(searchTimeout);
    searchTimeout = setTimeout(() => {
        currentPage = 1;
        loadProjects();
    }, 500);
});

// Filter changes
document.getElementById('statusFilter').addEventListener('change', function() {
    currentPage = 1;
    loadProjects();
});

document.getElementById('priorityFilter').addEventListener('change', function() {
    currentPage = 1;
    loadProjects();
});

function loadProjects() {
    const search = document.getElementById('searchInput').value;
    const status = document.getElementById('statusFilter').value;
    const priority = document.getElementById('priorityFilter').value;
    
    const params = new URLSearchParams({
        page: currentPage,
        search: search,
        status: status,
        priority: priority
    });
    
    fetch(`/api/project-planning?${params}`)
        .then(response => response.json())
        .then(data => {
            renderTable(data.data);
            renderPagination(data);
            updateShowingInfo(data);
        })
        .catch(error => {
            console.error('Error loading projects:', error);
            document.getElementById('projectTableBody').innerHTML = '<tr><td colspan="9" class="px-6 py-4 text-center text-sm text-gray-500">Error loading data</td></tr>';
        });
}

function renderTable(projects) {
    const tbody = document.getElementById('projectTableBody');
    
    if (projects.length === 0) {
        tbody.innerHTML = '<tr><td colspan="9" class="px-6 py-4 text-center text-sm text-gray-500">No projects found</td></tr>';
        return;
    }
    
    tbody.innerHTML = projects.map(project => `
        <tr class="hover:bg-gray-50">
            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">${project.project_number || '#' + project.id}</td>
            <td class="px-6 py-4 text-sm text-gray-900 max-w-xs truncate" title="${project.project_name || 'N/A'}">
                ${project.project_name || 'N/A'}
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${project.project_type || 'N/A'}</td>
            <td class="px-6 py-4 whitespace-nowrap">
                <span class="status-badge priority-${(project.priority || '').toLowerCase()}">
                    ${project.priority || 'N/A'}
                </span>
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">${formatDate(project.start_date)}</td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">${formatDate(project.end_date)}</td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${project.requested_by || 'N/A'}</td>
            <td class="px-6 py-4 whitespace-nowrap">
                <span class="status-badge status-${(project.status || '').toLowerCase().replace(' ', '-')}" style="text-transform: capitalize;">
                    ${project.status || 'N/A'}
                </span>
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                <div class="flex space-x-2">
                    <a href="/project-planning/${project.id}" class="text-blue-600 hover:text-blue-900" title="View">
                        <i class='bx bx-show text-lg'></i>
                    </a>
                    <a href="/project-planning/${project.id}/edit" class="text-green-600 hover:text-green-900" title="Edit">
                        <i class='bx bx-edit text-lg'></i>
                    </a>
                    <button onclick="deleteProject(${project.id})" class="text-red-600 hover:text-red-900" title="Delete">
                        <i class='bx bx-trash text-lg'></i>
                    </button>
                </div>
            </td>
        </tr>
    `).join('');
}

function renderPagination(data) {
    const pagination = document.getElementById('paginationLinks');
    const { current_page, last_page, prev_page_url, next_page_url } = data;
    
    let html = '';
    
    // Previous button
    if (prev_page_url) {
        html += `<button onclick="changePage(${current_page - 1})" class="px-3 py-1 border border-gray-300 rounded-md hover:bg-gray-50 text-sm">Previous</button>`;
    }
    
    // Page numbers
    for (let i = 1; i <= last_page; i++) {
        if (i === current_page) {
            html += `<button class="px-3 py-1 bg-blue-600 text-white rounded-md text-sm">${i}</button>`;
        } else {
            html += `<button onclick="changePage(${i})" class="px-3 py-1 border border-gray-300 rounded-md hover:bg-gray-50 text-sm">${i}</button>`;
        }
    }
    
    // Next button
    if (next_page_url) {
        html += `<button onclick="changePage(${current_page + 1})" class="px-3 py-1 border border-gray-300 rounded-md hover:bg-gray-50 text-sm">Next</button>`;
    }
    
    pagination.innerHTML = html;
}

function changePage(page) {
    currentPage = page;
    loadProjects();
}

function updateShowingInfo(data) {
    const from = (data.current_page - 1) * data.per_page + 1;
    const to = Math.min(data.current_page * data.per_page, data.total);
    
    document.getElementById('showingFrom').textContent = from;
    document.getElementById('showingTo').textContent = to;
    document.getElementById('totalRecords').textContent = data.total;
}

function refreshTable() {
    document.getElementById('searchInput').value = '';
    document.getElementById('statusFilter').value = '';
    document.getElementById('priorityFilter').value = '';
    currentPage = 1;
    loadProjects();
}

function deleteProject(id) {
    if (confirm('Are you sure you want to delete this project?')) {
        fetch(`/project-planning/${id}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                loadProjects();
            } else {
                alert('Error deleting project');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error deleting project');
        });
    }
}

function formatDate(dateString) {
    if (!dateString) return 'N/A';
    const date = new Date(dateString);
    return date.toLocaleDateString('en-US', { 
        year: 'numeric', 
        month: 'short', 
        day: 'numeric' 
    });
}
</script>
@endpush
