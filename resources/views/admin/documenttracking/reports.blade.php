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
                    <span class="ml-1 text-gray-500 md:ml-2">Reports</span>
                </div>
            </li>
        </ol>
    </nav>

    <!-- Page Header -->
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-900">Document Tracking Reports</h1>
        <div class="flex gap-3">
            <button class="bg-green-600 hover:bg-green-700 text-white font-medium py-2 px-4 rounded-lg flex items-center gap-2 transition-colors">
                <i class='bx bx-download text-xl'></i>
                Export All
            </button>
            <a href="{{ route('document-reports.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg flex items-center gap-2 transition-colors">
                <i class='bx bx-plus text-xl'></i>
                Generate Report
            </a>
        </div>
    </div>

    <!-- Success Messages -->
    @if(session('success'))
        <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg mb-6">
            <div class="flex items-center">
                <i class='bx bx-check-circle text-xl mr-2"></i>
                <span>{{ session('success') }}</span>
            </div>
        </div>
    @endif

    <!-- Filter Section -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">
        <form method="GET" action="{{ route('admin.documenttracking.reports') }}">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <!-- Report Type -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Report Type</label>
                    <select name="report_type" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="">All Reports</option>
                        <option value="Summary" {{ request('report_type') === 'Summary' ? 'selected' : '' }}>Summary Reports</option>
                        <option value="Storage" {{ request('report_type') === 'Storage' ? 'selected' : '' }}>Storage Reports</option>
                        <option value="Access" {{ request('report_type') === 'Access' ? 'selected' : '' }}>Access Logs</option>
                        <option value="Compliance" {{ request('report_type') === 'Compliance' ? 'selected' : '' }}>Compliance Reports</option>
                        <option value="Upload" {{ request('report_type') === 'Upload' ? 'selected' : '' }}>Upload Activity</option>
                        <option value="Document Requests" {{ request('report_type') === 'Document Requests' ? 'selected' : '' }}>Document Requests</option>
                    </select>
                </div>

                <!-- Date Range -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Date Range</label>
                    <select name="date_range" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="">All Time</option>
                        <option value="7" {{ request('date_range') === '7' ? 'selected' : '' }}>Last 7 Days</option>
                        <option value="30" {{ request('date_range') === '30' ? 'selected' : '' }}>Last 30 Days</option>
                        <option value="90" {{ request('date_range') === '90' ? 'selected' : '' }}>Last Quarter</option>
                        <option value="365" {{ request('date_range') === '365' ? 'selected' : '' }}>Last Year</option>
                    </select>
                </div>

                <!-- Status -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                    <select name="status" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="">All Status</option>
                        <option value="Completed" {{ request('status') === 'Completed' ? 'selected' : '' }}>Completed</option>
                        <option value="Processing" {{ request('status') === 'Processing' ? 'selected' : '' }}>Processing</option>
                        <option value="Scheduled" {{ request('status') === 'Scheduled' ? 'selected' : '' }}>Scheduled</option>
                        <option value="Failed" {{ request('status') === 'Failed' ? 'selected' : '' }}>Failed</option>
                        <option value="Cancelled" {{ request('status') === 'Cancelled' ? 'selected' : '' }}>Cancelled</option>
                    </select>
                </div>

                <!-- Department -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Department</label>
                    <select name="department" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="">All Departments</option>
                        <option value="Operations" {{ request('department') === 'Operations' ? 'selected' : '' }}>Operations</option>
                        <option value="Finance" {{ request('department') === 'Finance' ? 'selected' : '' }}>Finance</option>
                        <option value="HR" {{ request('department') === 'HR' ? 'selected' : '' }}>HR</option>
                        <option value="IT" {{ request('department') === 'IT' ? 'selected' : '' }}>IT</option>
                        <option value="Legal" {{ request('department') === 'Legal' ? 'selected' : '' }}>Legal</option>
                        <option value="Security" {{ request('department') === 'Security' ? 'selected' : '' }}>Security</option>
                    </select>
                </div>
            </div>

            <!-- Search and Filter Buttons -->
            <div class="flex gap-3 mt-6">
                <div class="flex-1">
                    <div class="relative">
                        <input type="text" 
                               name="search"
                               value="{{ request('search') }}"
                               placeholder="Search reports by ID, name, or generated by..." 
                               class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <i class='bx bx-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400 text-xl'></i>
                    </div>
                </div>
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-6 rounded-lg transition-colors">
                    <i class='bx bx-filter-alt mr-2'></i>
                    Apply Filters
                </button>
                <a href="{{ route('admin.documenttracking.reports') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-medium py-2 px-6 rounded-lg transition-colors">
                    <i class='bx bx-x mr-2'></i>
                    Clear
                </a>
            </div>
        </form>
    </div>


    <!-- Recent Reports Table -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
            <h2 class="text-lg font-semibold text-gray-900">Generated Reports</h2>
            <div class="flex gap-2">
                <a href="{{ route('document-reports.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg flex items-center gap-2 transition-colors">
                    <i class='bx bx-plus text-lg'></i>
                    New Report
                </a>
                <button class="bg-green-600 hover:bg-green-700 text-white font-medium py-2 px-4 rounded-lg flex items-center gap-2 transition-colors">
                    <i class='bx bx-download text-lg'></i>
                    Export
                </button>
                <button class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-medium py-2 px-4 rounded-lg flex items-center gap-2 transition-colors">
                    <i class='bx bx-refresh text-lg'></i>
                    Refresh
                </button>
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Report ID</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Report Name</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Generated By</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse ($reports as $report)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $report->report_number }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $report->report_name }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-{{ $report->type_color }}-100 text-{{ $report->type_color }}-800">
                                    {{ $report->report_type }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $report->generated_by }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $report->report_date->format('M d, Y') }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-{{ $report->status_color }}-100 text-{{ $report->status_color }}-800">
                                    {{ $report->status }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex space-x-2">
                                    <a href="{{ route('document-reports.show', $report->id) }}" class="text-blue-600 hover:text-blue-900" title="View">
                                        <i class='bx bx-show text-lg'></i>
                                    </a>
                                    @if(in_array($report->status, ['Processing', 'Scheduled']))
                                        <a href="{{ route('document-reports.edit', $report->id) }}" class="text-orange-600 hover:text-orange-900" title="Edit">
                                            <i class='bx bx-edit text-lg'></i>
                                        </a>
                                    @endif
                                    @if(in_array($report->status, ['Processing', 'Scheduled', 'Failed']))
                                        <form action="{{ route('document-reports.destroy', $report->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this report?')" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900" title="Delete">
                                                <i class='bx bx-trash text-lg'></i>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-8 text-center text-gray-500">
                                <div class="flex flex-col items-center">
                                    <i class='bx bx-file text-4xl text-gray-300 mb-3'></i>
                                    <p class="text-lg font-medium">No reports found</p>
                                    <p class="text-sm mt-1">Get started by generating your first report.</p>
                                    <a href="{{ route('document-reports.create') }}" class="mt-4 bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg inline-flex items-center gap-2 transition-colors">
                                        <i class='bx bx-plus'></i>
                                        Generate First Report
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
                {{ $reports->links() }}
            </div>
            <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                <div>
                    <p class="text-sm text-gray-700">
                        Showing <span class="font-medium">{{ $reports->firstItem() ?? 0 }}</span> to <span class="font-medium">{{ $reports->lastItem() ?? 0 }}</span> of{' '}
                        <span class="font-medium">{{ $reports->total() }}</span> results
                    </p>
                </div>
                <div>
                    {{ $reports->links() }}
                </div>
            </div>
        </div>
    </div>

</div>
@endsection
