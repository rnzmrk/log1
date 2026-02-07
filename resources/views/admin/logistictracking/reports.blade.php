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
            <li aria-current="page">
                <div class="flex items-center">
                    <i class='bx bx-chevron-right text-gray-400'></i>
                    <span class="ml-1 text-gray-500 md:ml-2">Analytics Dashboard</span>
                </div>
            </li>
        </ol>
    </nav>

    <!-- Page Header -->
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-900">Logistics Reports</h1>
        <div class="flex gap-3">
            <a href="{{ route('logistics-reports.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg flex items-center gap-2 transition-colors">
                <i class='bx bx-plus text-xl'></i>
                Generate Report
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

    <!-- Stats Overview -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Total Deliveries</p>
                    <p class="text-2xl font-bold text-blue-600">{{ $stats['total_deliveries'] }}</p>
                    <p class="text-xs text-green-600 mt-1">
                        @if($stats['delivery_trend'][0] > $stats['delivery_trend'][1])
                            <i class='bx bx-up-arrow-alt'></i> {{ number_format(($stats['delivery_trend'][0] - $stats['delivery_trend'][1]) / max($stats['delivery_trend'][1], 1) * 100, 1) }}% from last month
                        @else
                            <i class='bx bx-down-arrow-alt'></i> {{ number_format(abs($stats['delivery_trend'][0] - $stats['delivery_trend'][1]) / max($stats['delivery_trend'][1], 1) * 100, 1) }}% from last month
                        @endif
                    </p>
                </div>
                <div class="bg-blue-100 rounded-full p-3">
                    <i class='bx bx-package text-blue-600 text-2xl'></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Vehicle Requests</p>
                    <p class="text-2xl font-bold text-green-600">{{ $stats['vehicle_requests'] }}</p>
                    <p class="text-xs text-green-600 mt-1">
                        @if(isset($stats['vehicle_requests_trend']))
                            <i class='bx bx-{{ $stats['vehicle_requests_trend'] > 0 ? 'up' : 'down' }}-arrow-alt'></i> {{ abs($stats['vehicle_requests_trend']) }}% from last month
                        @else
                            <i class='bx bx-up-arrow-alt'></i> 5% from last month
                        @endif
                    </p>
                </div>
                <div class="bg-green-100 rounded-full p-3">
                    <i class='bx bx-car text-green-600 text-2xl'></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Active Projects</p>
                    <p class="text-2xl font-bold text-orange-600">{{ $stats['active_projects'] }}</p>
                    <p class="text-xs {{ $stats['project_trend'][0] > $stats['project_trend'][1] ? 'text-green-600' : 'text-red-600' }} mt-1">
                        <i class='bx bx-{{ $stats['project_trend'][0] > $stats['project_trend'][1] ? 'up' : 'down' }}-arrow-alt'></i> {{ abs($stats['project_trend'][0] - $stats['project_trend'][1]) }}% from last month
                    </p>
                </div>
                <div class="bg-orange-100 rounded-full p-3">
                    <i class='bx bx-briefcase text-orange-600 text-2xl'></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Success Rate</p>
                    <p class="text-2xl font-bold text-purple-600">{{ $stats['success_rate'] }}%</p>
                    <p class="text-xs text-green-600 mt-1">
                        @if(isset($stats['success_rate_improvement']))
                            <i class='bx bx-up-arrow-alt'></i> {{ $stats['success_rate_improvement'] }}% improvement
                        @else
                            <i class='bx bx-up-arrow-alt'></i> 1.5% improvement
                        @endif
                    </p>
                </div>
                <div class="bg-purple-100 rounded-full p-3">
                    <i class='bx bx-check-circle text-purple-600 text-2xl'></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter Section -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">
        <form method="GET" action="{{ route('admin.logistictracking.reports') }}">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <!-- Report Type -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Report Type</label>
                    <select name="report_type" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="">All Reports</option>
                        <option value="Delivery" {{ request('report_type') === 'Delivery' ? 'selected' : '' }}>Delivery Reports</option>
                        <option value="Vehicle" {{ request('report_type') === 'Vehicle' ? 'selected' : '' }}>Vehicle Reports</option>
                        <option value="Project" {{ request('report_type') === 'Project' ? 'selected' : '' }}>Project Reports</option>
                        <option value="Performance" {{ request('report_type') === 'Performance' ? 'selected' : '' }}>Performance Reports</option>
                        <option value="Financial" {{ request('report_type') === 'Financial' ? 'selected' : '' }}>Financial Reports</option>
                        <option value="Inventory" {{ request('report_type') === 'Inventory' ? 'selected' : '' }}>Inventory Reports</option>
                        <option value="Maintenance" {{ request('report_type') === 'Maintenance' ? 'selected' : '' }}>Maintenance Reports</option>
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
                        <option value="Logistics" {{ request('department') === 'Logistics' ? 'selected' : '' }}>Logistics</option>
                        <option value="IT" {{ request('department') === 'IT' ? 'selected' : '' }}>IT</option>
                        <option value="Finance" {{ request('department') === 'Finance' ? 'selected' : '' }}>Finance</option>
                        <option value="HR" {{ request('department') === 'HR' ? 'selected' : '' }}>HR</option>
                    </select>
                </div>

                <!-- Search -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Search</label>
                    <div class="relative">
                        <input type="text" 
                               name="search"
                               value="{{ request('search') }}"
                               placeholder="Report ID, Name, or Generated By..." 
                               class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <i class='bx bx-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400 text-xl'></i>
                    </div>
                </div>
            </div>

            <!-- Filter Buttons -->
            <div class="flex gap-3 mt-6">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-6 rounded-lg transition-colors">
                    <i class='bx bx-filter-alt mr-2'></i>
                    Apply Filters
                </button>
                <a href="{{ route('admin.logistictracking.reports') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-medium py-2 px-6 rounded-lg transition-colors">
                    <i class='bx bx-x mr-2'></i>
                    Clear
                </a>
            </div>
        </form>
    </div>

    <!-- Charts Section -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
        <!-- Delivery Performance Chart -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-gray-900">Delivery Performance</h2>
            </div>
            <div class="p-6">
                <div class="h-64 flex items-center justify-center bg-gray-50 rounded-lg">
                    <div class="text-center">
                        <i class='bx bx-line-chart text-4xl text-gray-400 mb-2'></i>
                        <p class="text-gray-600">Delivery performance chart</p>
                        <p class="text-sm text-gray-500">Shows delivery trends over time</p>
                    </div>
                </div>
                <div class="mt-4 grid grid-cols-3 gap-4 text-center">
                    <div>
                        <p class="text-2xl font-bold text-green-600">{{ $stats['delivery_performance']['on_time'] }}</p>
                        <p class="text-xs text-gray-600">On-Time</p>
                    </div>
                    <div>
                        <p class="text-2xl font-bold text-orange-600">{{ $stats['delivery_performance']['delayed'] }}</p>
                        <p class="text-xs text-gray-600">Delayed</p>
                    </div>
                    <div>
                        <p class="text-2xl font-bold text-red-600">{{ $stats['delivery_performance']['failed'] }}</p>
                        <p class="text-xs text-gray-600">Failed</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Vehicle Utilization Chart -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-gray-900">Vehicle Utilization</h2>
            </div>
            <div class="p-6">
                <div class="h-64 flex items-center justify-center bg-gray-50 rounded-lg">
                    <div class="text-center">
                        <i class='bx bx-pie-chart text-4xl text-gray-400 mb-2'></i>
                        <p class="text-gray-600">Vehicle utilization chart</p>
                        <p class="text-sm text-gray-500">Shows vehicle usage distribution</p>
                    </div>
                </div>
                <div class="mt-4 grid grid-cols-3 gap-4 text-center">
                    <div>
                        <p class="text-2xl font-bold text-blue-600">{{ $stats['vehicle_utilization']['in_use'] }}%</p>
                        <p class="text-xs text-gray-600">In Use</p>
                    </div>
                    <div>
                        <p class="text-2xl font-bold text-green-600">{{ $stats['vehicle_utilization']['available'] }}%</p>
                        <p class="text-xs text-gray-600">Available</p>
                    </div>
                    <div>
                        <p class="text-2xl font-bold text-orange-600">{{ $stats['vehicle_utilization']['maintenance'] }}%</p>
                        <p class="text-xs text-gray-600">Maintenance</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Reports Table -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
            <h2 class="text-lg font-semibold text-gray-900">Recent Reports</h2>
            <div class="flex gap-2">
                <button class="text-gray-600 hover:text-gray-900 p-2 rounded-lg hover:bg-gray-100">
                    <i class='bx bx-refresh text-xl'></i>
                </button>
                <button class="text-gray-600 hover:text-gray-900 p-2 rounded-lg hover:bg-gray-100">
                    <i class='bx bx-cog text-xl'></i>
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
                                    <a href="{{ route('logistics-reports.show', $report->id) }}" class="text-blue-600 hover:text-blue-900" title="View">
                                        <i class='bx bx-show text-lg'></i>
                                    </a>
                                    @if($report->status === 'Completed')
                                        <a href="#" class="text-green-600 hover:text-green-900" title="Download">
                                            <i class='bx bx-download text-lg'></i>
                                        </a>
                                    @endif
                                    <a href="#" class="text-purple-600 hover:text-purple-900" title="Share">
                                        <i class='bx bx-share text-lg'></i>
                                    </a>
                                    @if(in_array($report->status, ['Processing', 'Scheduled']))
                                        <a href="{{ route('logistics-reports.edit', $report->id) }}" class="text-orange-600 hover:text-orange-900" title="Edit">
                                            <i class='bx bx-edit text-lg'></i>
                                        </a>
                                    @endif
                                    @if(in_array($report->status, ['Processing', 'Scheduled', 'Failed']))
                                        <form action="{{ route('logistics-reports.destroy', $report->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this report?')" class="inline">
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
                                    <a href="{{ route('logistics-reports.create') }}" class="mt-4 bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg inline-flex items-center gap-2 transition-colors">
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
