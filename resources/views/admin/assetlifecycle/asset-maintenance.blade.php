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
                    <a href="#" class="text-gray-700 hover:text-blue-600 ml-1 md:ml-2">Asset Lifecycle & Maintenance</a>
                </div>
            </li>
            <li>
                <div class="flex items-center">
                    <i class='bx bx-chevron-right text-gray-400'></i>
                    <a href="#" class="text-gray-700 hover:text-blue-600 ml-1 md:ml-2">Asset Maintenance</a>
                </div>
            </li>
            <li aria-current="page">
                <div class="flex items-center">
                    <i class='bx bx-chevron-right text-gray-400'></i>
                    <span class="ml-1 text-gray-500 md:ml-2">Maintenance Schedule</span>
                </div>
            </li>
        </ol>
    </nav>

    <!-- Page Header -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Asset Maintenance</h1>
            <p class="text-gray-600 mt-1">Manage maintenance schedules and track asset repairs</p>
        </div>
        <div class="flex gap-3">
            <form method="GET" action="{{ route('asset-maintenance.export') }}" class="inline">
                @csrf
                @if(request('search'))
                    <input type="hidden" name="search" value="{{ request('search') }}">
                @endif
                @if(request('status'))
                    <input type="hidden" name="status" value="{{ request('status') }}">
                @endif
                @if(request('month'))
                    <input type="hidden" name="month" value="{{ request('month') }}">
                @endif
                @if(request('year'))
                    <input type="hidden" name="year" value="{{ request('year') }}">
                @endif
                <button type="submit" class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-medium py-2 px-4 rounded-lg flex items-center gap-2 transition-colors">
                    <i class='bx bx-download text-xl'></i>
                    Export CSV
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
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-6 mb-6">
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Total Maintenance</p>
                    <p class="text-2xl font-bold text-blue-600">{{ number_format($stats['total']) }}</p>
                    <p class="text-xs text-gray-600 mt-1">All records</p>
                </div>
                <div class="bg-blue-100 rounded-full p-3">
                    <i class='bx bx-wrench text-blue-600 text-2xl'></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Scheduled</p>
                    <p class="text-2xl font-bold text-orange-600">{{ number_format($stats['scheduled']) }}</p>
                    <p class="text-xs text-gray-600 mt-1">Pending maintenance</p>
                </div>
                <div class="bg-orange-100 rounded-full p-3">
                    <i class='bx bx-calendar text-orange-600 text-2xl'></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">In Progress</p>
                    <p class="text-2xl font-bold text-blue-600">{{ number_format($stats['in_progress']) }}</p>
                    <p class="text-xs text-gray-600 mt-1">Currently being worked on</p>
                </div>
                <div class="bg-blue-100 rounded-full p-3">
                    <i class='bx bx-time text-blue-600 text-2xl'></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Completed</p>
                    <p class="text-2xl font-bold text-green-600">{{ number_format($stats['completed']) }}</p>
                    <p class="text-xs text-gray-600 mt-1">Finished maintenance</p>
                </div>
                <div class="bg-green-100 rounded-full p-3">
                    <i class='bx bx-check-circle text-green-600 text-2xl'></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Overdue</p>
                    <p class="text-2xl font-bold text-red-600">{{ number_format($stats['overdue']) }}</p>
                    <p class="text-xs text-gray-600 mt-1">Past due date</p>
                </div>
                <div class="bg-red-100 rounded-full p-3">
                    <i class='bx bx-error text-red-600 text-2xl'></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter Section -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">
        <form method="GET" action="{{ route('admin.assetlifecycle.asset-maintenance') }}">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <!-- Search -->
                <div class="lg:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Search Maintenance</label>
                    <div class="relative">
                        <input type="text" 
                               name="search"
                               value="{{ request('search') }}"
                               placeholder="Maintenance ID, Asset Name, or Description..." 
                               class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <i class='bx bx-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400 text-xl'></i>
                    </div>
                </div>

                <!-- Status -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                    <select name="status" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="">All Status</option>
                        <option value="Scheduled" {{ request('status') === 'Scheduled' ? 'selected' : '' }}>pending</option>
                        <option value="In Progress" {{ request('status') === 'In Progress' ? 'selected' : '' }}>ongoing</option>
                        <option value="Completed" {{ request('status') === 'Completed' ? 'selected' : '' }}>done</option>
                        <option value="On Hold" {{ request('status') === 'On Hold' ? 'selected' : '' }}>reject</option>
                        <option value="Cancelled" {{ request('status') === 'Cancelled' ? 'selected' : '' }}>Cancelled</option>
                    </select>
                </div>

                <!-- Month -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Month</label>
                    <select name="month" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="">All Months</option>
                        <option value="1" {{ request('month') == '1' ? 'selected' : '' }}>January</option>
                        <option value="2" {{ request('month') == '2' ? 'selected' : '' }}>February</option>
                        <option value="3" {{ request('month') == '3' ? 'selected' : '' }}>March</option>
                        <option value="4" {{ request('month') == '4' ? 'selected' : '' }}>April</option>
                        <option value="5" {{ request('month') == '5' ? 'selected' : '' }}>May</option>
                        <option value="6" {{ request('month') == '6' ? 'selected' : '' }}>June</option>
                        <option value="7" {{ request('month') == '7' ? 'selected' : '' }}>July</option>
                        <option value="8" {{ request('month') == '8' ? 'selected' : '' }}>August</option>
                        <option value="9" {{ request('month') == '9' ? 'selected' : '' }}>September</option>
                        <option value="10" {{ request('month') == '10' ? 'selected' : '' }}>October</option>
                        <option value="11" {{ request('month') == '11' ? 'selected' : '' }}>November</option>
                        <option value="12" {{ request('month') == '12' ? 'selected' : '' }}>December</option>
                    </select>
                </div>

                <!-- Year -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Year</label>
                    <select name="year" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="">All Years</option>
                        @for($year = date('Y'); $year >= date('Y') - 5; $year--)
                            <option value="{{ $year }}" {{ request('year') == $year ? 'selected' : '' }}>{{ $year }}</option>
                        @endfor
                    </select>
                </div>
            </div>

            <!-- Filter Buttons -->
            <div class="flex gap-3 mt-6">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-6 rounded-lg transition-colors">
                    <i class='bx bx-filter-alt mr-2'></i>
                    Apply Filters
                </button>
                <a href="{{ route('admin.assetlifecycle.asset-maintenance') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-medium py-2 px-6 rounded-lg transition-colors">
                    <i class='bx bx-x mr-2'></i>
                    Clear
                </a>
            </div>
        </form>
    </div>

    <!-- Maintenance Schedule -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
            <div>
                <h2 class="text-lg font-semibold text-gray-900">Maintenance Schedule</h2>
                <p class="text-sm text-gray-600 mt-1">Manage all maintenance records and schedules</p>
            </div>
            <div class="flex gap-2">
                <button onclick="toggleBulkActions()" class="text-gray-600 hover:text-gray-900 p-2 rounded-lg hover:bg-gray-100" title="Bulk Actions">
                    <i class='bx bx-checkbox text-xl'></i>
                </button>
                <button onclick="window.location.reload()" class="text-gray-600 hover:text-gray-900 p-2 rounded-lg hover:bg-gray-100" title="Refresh">
                    <i class='bx bx-refresh text-xl'></i>
                </button>
                <a href="{{ route('asset-maintenance.export') }}" class="text-gray-600 hover:text-gray-900 p-2 rounded-lg hover:bg-gray-100" title="Export">
                    <i class='bx bx-download text-xl'></i>
                </a>
            </div>
        </div>

        <!-- Bulk Actions (Hidden by default) -->
        <div id="bulkActions" class="hidden px-6 py-3 bg-gray-50 border-b border-gray-200">
            <form id="bulkActionForm" method="POST" action="{{ route('asset-maintenance.bulk-action') }}">
                @csrf
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-4">
                        <span class="text-sm text-gray-700">Bulk Actions:</span>
                        <select name="action" class="px-3 py-1 border border-gray-300 rounded text-sm">
                            <option value="">Select Action</option>
                            <option value="update_status">Update Status</option>
                            <option value="delete">Delete</option>
                        </select>
                        <select name="status" class="px-3 py-1 border border-gray-300 rounded text-sm" id="bulkStatusSelect" style="display:none;">
                            <option value="Scheduled">Scheduled</option>
                            <option value="In Progress">In Progress</option>
                            <option value="Completed">Completed</option>
                            <option value="On Hold">On Hold</option>
                            <option value="Cancelled">Cancelled</option>
                        </select>
                        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium py-1 px-3 rounded">
                            Apply
                        </button>
                    </div>
                    <button type="button" onclick="toggleBulkActions()" class="text-gray-600 hover:text-gray-900">
                        <i class='bx bx-x text-xl'></i>
                    </button>
                </div>
            </form>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="bulk-select-header hidden px-6 py-3 text-left">
                            <input type="checkbox" id="selectAll" onchange="toggleAllCheckboxes()" class="rounded border-gray-300">
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Maintenance ID</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Asset Name</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Maintenance Description</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Schedule Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse ($maintenances as $maintenance)
                        <tr class="hover:bg-gray-50">
                            <td class="bulk-select-cell hidden px-6 py-4">
                                <input type="checkbox" name="maintenance_ids[]" value="{{ $maintenance->id }}" class="maintenance-checkbox rounded border-gray-300">
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $maintenance->maintenance_number }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">{{ $maintenance->asset_name }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm text-gray-900 max-w-xs" title="{{ $maintenance->problem_description }}">
                                    {{ Str::limit($maintenance->problem_description, 100) }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                @if ($maintenance->scheduled_date)
                                    {{ $maintenance->scheduled_date->format('M d, Y') }}
                                @else
                                    -
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if ($maintenance->status === 'Scheduled')
                                    <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                        pending
                                    </span>
                                @elseif ($maintenance->status === 'In Progress')
                                    <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                        ongoing
                                    </span>
                                @elseif ($maintenance->status === 'Completed')
                                    <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                        done
                                    </span>
                                @elseif ($maintenance->status === 'On Hold')
                                    <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                        reject
                                    </span>
                                @else
                                    <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                        {{ $maintenance->status }}
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex space-x-2">
                                    @if($maintenance->status === 'Scheduled')
                                        <form action="{{ route('asset-maintenance.update-status', $maintenance->id) }}" method="POST" class="inline" onsubmit="return confirm('Approve this maintenance request?')">
                                            @csrf
                                            @method('PATCH')
                                            <input type="hidden" name="status" value="In Progress">
                                            <button type="submit" class="text-green-600 hover:text-green-900" title="Approve">
                                                <i class='bx bx-check-circle text-lg'></i>
                                            </button>
                                        </form>
                                        <form action="{{ route('asset-maintenance.update-status', $maintenance->id) }}" method="POST" class="inline" onsubmit="return confirm('Reject this maintenance request?')">
                                            @csrf
                                            @method('PATCH')
                                            <input type="hidden" name="status" value="On Hold">
                                            <button type="submit" class="text-red-600 hover:text-red-900" title="Reject">
                                                <i class='bx bx-x-circle text-lg'></i>
                                            </button>
                                        </form>
                                    @endif
                                    <a href="{{ route('asset-maintenance.show', $maintenance->id) }}" class="text-blue-600 hover:text-blue-900" title="View">
                                        <i class='bx bx-show text-lg'></i>
                                    </a>
                                    <a href="{{ route('asset-maintenance.edit', $maintenance->id) }}" class="text-green-600 hover:text-green-900" title="Edit">
                                        <i class='bx bx-edit text-lg'></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-8 text-center text-gray-500">
                                <div class="flex flex-col items-center">
                                    <i class='bx bx-wrench text-4xl text-gray-300 mb-3'></i>
                                    <p class="text-lg font-medium">No maintenance records found</p>
                                    <p class="text-sm mt-1">Get started by creating your first maintenance record.</p>
                                    <a href="{{ route('asset-maintenance.create') }}" class="mt-4 bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg inline-flex items-center gap-2 transition-colors">
                                        <i class='bx bx-plus'></i>
                                        Create First Maintenance
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
                {{ $maintenances->links() }}
            </div>
            <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                <div>
                    <p class="text-sm text-gray-700">
                        Showing <span class="font-medium">{{ $maintenances->firstItem() ?? 0 }}</span> to <span class="font-medium">{{ $maintenances->lastItem() ?? 0 }}</span> of{' '}
                        <span class="font-medium">{{ $maintenances->total() }}</span> results
                    </p>
                </div>
                <div>
                    {{ $maintenances->links() }}
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function toggleBulkActions() {
    const bulkActions = document.getElementById('bulkActions');
    const bulkSelectHeader = document.querySelector('.bulk-select-header');
    const bulkSelectCells = document.querySelectorAll('.bulk-select-cell');
    
    if (bulkActions.classList.contains('hidden')) {
        bulkActions.classList.remove('hidden');
        bulkSelectHeader.classList.remove('hidden');
        bulkSelectCells.forEach(cell => cell.classList.remove('hidden'));
    } else {
        bulkActions.classList.add('hidden');
        bulkSelectHeader.classList.add('hidden');
        bulkSelectCells.forEach(cell => cell.classList.add('hidden'));
    }
}

function toggleAllCheckboxes() {
    const selectAll = document.getElementById('selectAll');
    const checkboxes = document.querySelectorAll('.maintenance-checkbox');
    checkboxes.forEach(checkbox => checkbox.checked = selectAll.checked);
}

// Handle bulk action form
document.getElementById('bulkActionForm').addEventListener('submit', function(e) {
    const action = this.querySelector('select[name="action"]').value;
    if (!action) {
        e.preventDefault();
        alert('Please select an action');
        return;
    }
    
    const checkboxes = document.querySelectorAll('.maintenance-checkbox:checked');
    if (checkboxes.length === 0) {
        e.preventDefault();
        alert('Please select at least one maintenance record');
        return;
    }
});

// Show/hide bulk status select
document.querySelector('select[name="action"]').addEventListener('change', function() {
    const statusSelect = document.getElementById('bulkStatusSelect');
    if (this.value === 'update_status') {
        statusSelect.style.display = 'block';
    } else {
        statusSelect.style.display = 'none';
    }
});
</script>
@endsection
