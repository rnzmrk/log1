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
            <li aria-current="page">
                <div class="flex items-center">
                    <i class='bx bx-chevron-right text-gray-400'></i>
                    <span class="ml-1 text-gray-500 md:ml-2">Project Planning & Request</span>
                </div>
            </li>
        </ol>
    </nav>

    <!-- Page Header -->
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-900">Project Planning & Request</h1>
        <div class="flex gap-3">
            <button onclick="exportToCSV()" class="bg-green-600 hover:bg-green-700 text-white font-medium py-2 px-4 rounded-lg flex items-center gap-2 transition-colors">
                <i class='bx bx-download text-xl'></i>
                Export CSV
            </button>
            <a href="{{ route('project-planning.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg flex items-center gap-2 transition-colors">
                <i class='bx bx-plus text-xl'></i>
                New Project
            </a>
        </div>
    </div>

    <!-- Search and Filters -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 mb-6">
        <div class="p-6">
            <form method="GET" action="{{ route('project-planning.index') }}" class="space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                    <!-- Search -->
                    <div class="lg:col-span-2">
                        <label for="search" class="block text-sm font-medium text-gray-700 mb-2">Search</label>
                        <div class="relative">
                            <input type="text" 
                                   id="search"
                                   name="search" 
                                   value="{{ request('search') }}"
                                   placeholder="Search by project name, ID, description, requester..." 
                                   class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <i class='bx bx-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400'></i>
                        </div>
                    </div>
                    
                    <!-- Status Filter -->
                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                        <select id="status" name="status" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="">All Status</option>
                            <option value="Draft" {{ request('status') == 'Draft' ? 'selected' : '' }}>Draft</option>
                            <option value="Submitted" {{ request('status') == 'Submitted' ? 'selected' : '' }}>Submitted</option>
                            <option value="Under Review" {{ request('status') == 'Under Review' ? 'selected' : '' }}>Under Review</option>
                            <option value="Approved" {{ request('status') == 'Approved' ? 'selected' : '' }}>Approved</option>
                            <option value="In Progress" {{ request('status') == 'In Progress' ? 'selected' : '' }}>In Progress</option>
                            <option value="On Hold" {{ request('status') == 'On Hold' ? 'selected' : '' }}>On Hold</option>
                            <option value="Completed" {{ request('status') == 'Completed' ? 'selected' : '' }}>Completed</option>
                            <option value="Cancelled" {{ request('status') == 'Cancelled' ? 'selected' : '' }}>Cancelled</option>
                        </select>
                    </div>
                    
                    <!-- Priority Filter -->
                    <div>
                        <label for="priority" class="block text-sm font-medium text-gray-700 mb-2">Priority</label>
                        <select id="priority" name="priority" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="">All Priority</option>
                            <option value="Low" {{ request('priority') == 'Low' ? 'selected' : '' }}>Low</option>
                            <option value="Medium" {{ request('priority') == 'Medium' ? 'selected' : '' }}>Medium</option>
                            <option value="High" {{ request('priority') == 'High' ? 'selected' : '' }}>High</option>
                            <option value="Critical" {{ request('priority') == 'Critical' ? 'selected' : '' }}>Critical</option>
                        </select>
                    </div>
                </div>
                
                <!-- Date Range Filters -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                    <div>
                        <label for="project_type" class="block text-sm font-medium text-gray-700 mb-2">Project Type</label>
                        <select id="project_type" name="project_type" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="">All Types</option>
                            @foreach(config('categories.project_types', []) as $type)
                                <option value="{{ $type }}" {{ request('project_type') == $type ? 'selected' : '' }}>{{ $type }}</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div>
                        <label for="date_from" class="block text-sm font-medium text-gray-700 mb-2">Start Date From</label>
                        <input type="date" 
                               id="date_from"
                               name="date_from"
                               value="{{ request('date_from') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>
                    
                    <div>
                        <label for="date_to" class="block text-sm font-medium text-gray-700 mb-2">Start Date To</label>
                        <input type="date" 
                               id="date_to"
                               name="date_to"
                               value="{{ request('date_to') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>
                    
                    <div class="flex items-end">
                        <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg transition-colors">
                            <i class='bx bx-filter-alt mr-2'></i>
                            Apply Filters
                        </button>
                    </div>
                </div>
                
                @if(request()->hasAny(['search', 'status', 'priority', 'project_type', 'date_from', 'date_to']))
                    <div class="flex items-center justify-between">
                        <a href="{{ route('project-planning.index') }}" class="text-sm text-blue-600 hover:text-blue-800">
                            <i class='bx bx-x mr-1'></i>
                            Clear all filters
                        </a>
                        <span class="text-sm text-gray-600">
                            Showing {{ $projects->count() }} of {{ $projects->total() }} results
                        </span>
                    </div>
                @endif
            </form>
        </div>
    </div>

    <!-- Success Message -->
    @if(session('success'))
        <div class="mb-6 bg-green-50 border border-green-200 rounded-md p-4">
            <div class="flex">
                <div class="flex-shrink-0">
                    <i class='bx bx-check-circle text-green-400 text-xl'></i>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-green-800">{{ session('success') }}</p>
                </div>
            </div>
        </div>
    @endif

    <!-- Projects Table -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
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
                <tbody class="bg-white divide-y divide-gray-200">
                    @if($projects->count() > 0)
                        @foreach($projects as $project)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                    {{ $project->project_number ?: '#' . $project->id }}
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-900 max-w-xs truncate" title="{{ $project->project_name }}">
                                    {{ $project->project_name }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $project->project_type }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full 
                                        @if($project->priority === 'Low') bg-gray-100 text-gray-800
                                        @elseif($project->priority === 'Medium') bg-blue-100 text-blue-800
                                        @elseif($project->priority === 'High') bg-orange-100 text-orange-800
                                        @elseif($project->priority === 'Critical') bg-red-100 text-red-800
                                        @endif">
                                        {{ $project->priority }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                    {{ $project->start_date ? \Carbon\Carbon::parse($project->start_date)->format('M j, Y') : 'N/A' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                    {{ $project->end_date ? \Carbon\Carbon::parse($project->end_date)->format('M j, Y') : 'N/A' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $project->requested_by }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
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
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex space-x-2">
                                        <a href="{{ route('project-planning.show', $project->id) }}" class="text-blue-600 hover:text-blue-900" title="View">
                                            <i class='bx bx-show text-lg'></i>
                                        </a>
                                        <a href="{{ route('project-planning.edit', $project->id) }}" class="text-green-600 hover:text-green-900" title="Edit">
                                            <i class='bx bx-edit text-lg'></i>
                                        </a>
                                        
                                        {{-- Approve Button - Show for Draft, Submitted, Under Review, In Progress --}}
                                        @if(in_array($project->status, ['Draft', 'Submitted', 'Under Review', 'In Progress']))
                                            <form action="{{ route('project-planning.approve', $project->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to approve this project?')">
                                                @csrf
                                                @method('PUT')
                                                <button type="submit" class="text-emerald-600 hover:text-emerald-900" title="Approve">
                                                    <i class='bx bx-check-circle text-lg'></i>
                                                </button>
                                            </form>
                                        @endif
                                        
                                        {{-- Reject Button - Show for Draft, Submitted, Under Review, In Progress, Approved --}}
                                        @if(in_array($project->status, ['Draft', 'Submitted', 'Under Review', 'In Progress', 'Approved']))
                                            <form action="{{ route('project-planning.reject', $project->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to reject this project?')">
                                                @csrf
                                                @method('PUT')
                                                <button type="submit" class="text-red-600 hover:text-red-900" title="Reject">
                                                    <i class='bx bx-x-circle text-lg'></i>
                                                </button>
                                            </form>
                                        @endif
                                        
                                        <form action="{{ route('project-planning.destroy', $project->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this project?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900" title="Delete">
                                                <i class='bx bx-trash text-lg'></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="9" class="px-6 py-12 text-center">
                                <i class='bx bx-inbox text-gray-400 text-4xl mb-4'></i>
                                <p class="text-gray-500 text-lg">No projects found</p>
                                <p class="text-gray-400 text-sm mt-2">
                                    @if(request()->hasAny(['search', 'status', 'priority', 'project_type', 'date_from', 'date_to']))
                                        Try adjusting your filters or 
                                        <a href="{{ route('project-planning.index') }}" class="text-blue-600 hover:text-blue-800">clear all filters</a>
                                    @else
                                        <a href="{{ route('project-planning.create') }}" class="text-blue-600 hover:text-blue-800">Create your first project</a>
                                    @endif
                                </p>
                            </td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        @if($projects->hasPages())
            <div class="px-6 py-4 border-t border-gray-200 flex justify-between items-center">
                <div class="text-sm text-gray-700">
                    Showing {{ $projects->firstItem() }} to {{ $projects->lastItem() }} of {{ $projects->total() }} results
                </div>
                <div>
                    {{ $projects->links() }}
                </div>
            </div>
        @endif
    </div>
</div>

<script>
function exportToCSV() {
    // Get current filters from URL
    const urlParams = new URLSearchParams(window.location.search);
    
    // Build export URL with same filters
    let exportUrl = '/api/project-planning/export/csv';
    if (urlParams.toString()) {
        exportUrl += '?' + urlParams.toString();
    }
    
    // Create and trigger download
    const link = document.createElement('a');
    link.href = exportUrl;
    link.download = 'project-planning-' + new Date().toISOString().split('T')[0] + '.csv';
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
}
</script>

@endsection
