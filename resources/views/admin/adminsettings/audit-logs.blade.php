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
                    <a href="#" class="text-gray-700 hover:text-blue-600 ml-1 md:ml-2">Admin Settings</a>
                </div>
            </li>
            <li>
                <div class="flex items-center">
                    <i class='bx bx-chevron-right text-gray-400'></i>
                    <a href="{{ route('admin.adminsettings.audit-logs') }}" class="text-gray-700 hover:text-blue-600 ml-1 md:ml-2">Audit Logs</a>
                </div>
            </li>
            <li aria-current="page">
                <div class="flex items-center">
                    <i class='bx bx-chevron-right text-gray-400'></i>
                    <span class="ml-1 text-gray-500 md:ml-2">System Activity</span>
                </div>
            </li>
        </ol>
    </nav>

    <!-- Page Header -->
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-900">Audit Logs</h1>
        <div class="flex gap-3">
            <a href="{{ route('audit-logs.export') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-medium py-2 px-4 rounded-lg flex items-center gap-2 transition-colors">
                <i class='bx bx-download text-xl'></i>
                Export Logs
            </a>
            <form action="{{ route('audit-logs.clear') }}" method="POST" onsubmit="return confirm('Are you sure you want to clear old logs? This action cannot be undone.')" class="inline">
                @csrf
                @method('DELETE')
                <button type="submit" class="bg-red-600 hover:bg-red-700 text-white font-medium py-2 px-4 rounded-lg flex items-center gap-2 transition-colors">
                    <i class='bx bx-trash text-xl'></i>
                    Clear Old Logs
                </button>
            </form>
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

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Total Logs</p>
                    <p class="text-2xl font-bold text-blue-600">{{ number_format($stats['total_logs']) }}</p>
                    <p class="text-xs text-green-600 mt-1">
                        <i class='bx bx-up-arrow-alt'></i> All time records
                    </p>
                </div>
                <div class="bg-blue-100 rounded-full p-3">
                    <i class='bx bx-file-text text-blue-600 text-2xl'></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Today's Activity</p>
                    <p class="text-2xl font-bold text-green-600">{{ number_format($stats['today_activity']) }}</p>
                    <p class="text-xs text-gray-600 mt-1">Last 24 hours</p>
                </div>
                <div class="bg-green-100 rounded-full p-3">
                    <i class='bx bx-time text-green-600 text-2xl'></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Failed Logins</p>
                    <p class="text-2xl font-bold text-red-600">{{ number_format($stats['failed_logins']) }}</p>
                    <p class="text-xs text-red-600 mt-1">
                        @if($stats['failed_logins'] > 0)
                            <i class='bx bx-up-arrow-alt'></i> Security alerts
                        @else
                            <i class='bx bx-check'></i> No issues
                        @endif
                    </p>
                </div>
                <div class="bg-red-100 rounded-full p-3">
                    <i class='bx bx-shield-x text-red-600 text-2xl'></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Active Users</p>
                    <p class="text-2xl font-bold text-purple-600">{{ number_format($stats['active_users_today']) }}</p>
                    <p class="text-xs text-gray-600 mt-1">Currently logged in</p>
                </div>
                <div class="bg-purple-100 rounded-full p-3">
                    <i class='bx bx-user-check text-purple-600 text-2xl'></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter Section -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">
        <form method="GET" action="{{ route('admin.adminsettings.audit-logs') }}">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <!-- Search -->
                <div class="lg:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Search Logs</label>
                    <div class="relative">
                        <input type="text" 
                               name="search"
                               value="{{ request('search') }}"
                               placeholder="Search by user, action, or IP..." 
                               class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <i class='bx bx-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400 text-xl'></i>
                    </div>
                </div>

                <!-- Action Type -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Action Type</label>
                    <select name="action" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="">All Actions</option>
                        @foreach($filterOptions['actions'] as $action)
                            <option value="{{ $action }}" {{ request('action') === $action ? 'selected' : '' }}>{{ $action }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Date Range -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Date Range</label>
                    <select name="date_range" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="">All Time</option>
                        <option value="24" {{ request('date_range') === '24' ? 'selected' : '' }}>Last 24 Hours</option>
                        <option value="7" {{ request('date_range') === '7' ? 'selected' : '' }}>Last 7 Days</option>
                        <option value="30" {{ request('date_range') === '30' ? 'selected' : '' }}>Last 30 Days</option>
                        <option value="90" {{ request('date_range') === '90' ? 'selected' : '' }}>Last 3 Months</option>
                    </select>
                </div>
            </div>

            <!-- Additional Filters -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">User</label>
                    <select name="user" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="">All Users</option>
                        @foreach($filterOptions['users'] as $user)
                            <option value="{{ $user }}" {{ request('user') === $user ? 'selected' : '' }}>{{ $user }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Module</label>
                    <select name="module" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="">All Modules</option>
                        @foreach($filterOptions['modules'] as $module)
                            <option value="{{ $module }}" {{ request('module') === $module ? 'selected' : '' }}>{{ $module }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                    <select name="status" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="">All Status</option>
                        <option value="Success" {{ request('status') === 'Success' ? 'selected' : '' }}>Success</option>
                        <option value="Failed" {{ request('status') === 'Failed' ? 'selected' : '' }}>Failed</option>
                        <option value="Warning" {{ request('status') === 'Warning' ? 'selected' : '' }}>Warning</option>
                        <option value="Error" {{ request('status') === 'Error' ? 'selected' : '' }}>Error</option>
                    </select>
                </div>
            </div>

            <!-- Filter Buttons -->
            <div class="flex gap-3 mt-6">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-6 rounded-lg transition-colors">
                    <i class='bx bx-filter-alt mr-2'></i>
                    Apply Filters
                </button>
                <a href="{{ route('admin.adminsettings.audit-logs') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-medium py-2 px-6 rounded-lg transition-colors">
                    <i class='bx bx-x mr-2'></i>
                    Clear
                </a>
            </div>
        </form>
    </div>

    <!-- Audit Logs Table -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
            <h2 class="text-lg font-semibold text-gray-900">System Activity Logs</h2>
            <div class="flex gap-2">
                <a href="{{ route('admin.adminsettings.audit-logs') }}" class="text-gray-600 hover:text-gray-900 p-2 rounded-lg hover:bg-gray-100" title="Refresh">
                    <i class='bx bx-refresh text-xl'></i>
                </a>
                <a href="{{ route('audit-logs.export') }}" class="text-gray-600 hover:text-gray-900 p-2 rounded-lg hover:bg-gray-100" title="Export">
                    <i class='bx bx-download text-xl'></i>
                </a>
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Timestamp</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Action</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Module</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">IP Address</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Details</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse ($auditLogs as $log)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $log->created_at->format('M d, Y H:i') }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="h-8 w-8 rounded-full bg-{{ $log->avatar_color }}-600 flex items-center justify-center text-white text-xs font-bold">
                                        {{ $log->initials }}
                                    </div>
                                    <div class="ml-3">
                                        <div class="text-sm font-medium text-gray-900">{{ $log->user_name }}</div>
                                        <div class="text-xs text-gray-500">{{ $log->user_email }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $log->action }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-{{ $log->module_color }}-100 text-{{ $log->module_color }}-800">
                                    {{ $log->module }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $log->ip_address }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-{{ $log->status_color }}-100 text-{{ $log->status_color }}-800">
                                    {{ $log->status }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                <a href="{{ route('audit-logs.show', $log->id) }}" class="text-blue-600 hover:text-blue-900" title="View Details">
                                    <i class='bx bx-show text-lg'></i>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-8 text-center text-gray-500">
                                <div class="flex flex-col items-center">
                                    <i class='bx bx-file-text text-4xl text-gray-300 mb-3'></i>
                                    <p class="text-lg font-medium">No audit logs found</p>
                                    <p class="text-sm mt-1">No system activity has been recorded yet.</p>
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
                {{ $auditLogs->links() }}
            </div>
            <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                <div>
                    <p class="text-sm text-gray-700">
                        Showing <span class="font-medium">{{ $auditLogs->firstItem() ?? 0 }}</span> to <span class="font-medium">{{ $auditLogs->lastItem() ?? 0 }}</span> of{' '}
                        <span class="font-medium">{{ $auditLogs->total() }}</span> results
                    </p>
                </div>
                <div>
                    {{ $auditLogs->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
