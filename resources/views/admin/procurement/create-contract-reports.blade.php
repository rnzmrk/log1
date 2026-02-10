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
                    <a href="#" class="text-gray-700 hover:text-blue-600 ml-1 md:ml-2">Procurement</a>
                </div>
            </li>
            <li>
                <div class="flex items-center">
                    <i class='bx bx-chevron-right text-gray-400'></i>
                    <a href="#" class="text-gray-700 hover:text-blue-600 ml-1 md:ml-2">Contracts & Reports</a>
                </div>
            </li>
            <li aria-current="page">
                <div class="flex items-center">
                    <i class='bx bx-chevron-right text-gray-400'></i>
                    <span class="ml-1 text-gray-500 md:ml-2">Contracts</span>
                </div>
            </li>
        </ol>
    </nav>

    <!-- Page Header -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Contracts & Reports</h1>
            <p class="text-gray-600 mt-1">Manage contracts and track renewal dates</p>
        </div>
        <div class="flex gap-3">
            <a href="{{ route('contracts.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg flex items-center gap-2 transition-colors">
                <i class='bx bx-plus text-xl'></i>
                New Contract
            </a>
            <a href="{{ route('contracts.export') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-medium py-2 px-4 rounded-lg flex items-center gap-2 transition-colors">
                <i class='bx bx-download text-xl'></i>
                Export
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

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Total Contracts</p>
                    <p class="text-2xl font-bold text-blue-600">{{ number_format($stats['total']) }}</p>
                    <p class="text-xs text-gray-600 mt-1">All contracts</p>
                </div>
                <div class="bg-blue-100 rounded-full p-3">
                    <i class='bx bx-file text-blue-600 text-2xl'></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Active Contracts</p>
                    <p class="text-2xl font-bold text-green-600">{{ number_format($stats['active']) }}</p>
                    <p class="text-xs text-gray-600 mt-1">Currently active</p>
                </div>
                <div class="bg-green-100 rounded-full p-3">
                    <i class='bx bx-check-circle text-green-600 text-2xl'></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Expiring Soon</p>
                    <p class="text-2xl font-bold text-orange-600">{{ number_format($stats['expiring_soon']) }}</p>
                    <p class="text-xs text-gray-600 mt-1">Within 30 days</p>
                </div>
                <div class="bg-orange-100 rounded-full p-3">
                    <i class='bx bx-time text-orange-600 text-2xl'></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Total Value</p>
                    <p class="text-2xl font-bold text-purple-600">₱{{ number_format($stats['total_value'], 0) }}</p>
                    <p class="text-xs text-gray-600 mt-1">Active: ₱{{ number_format($stats['active_value'], 0) }}</p>
                </div>
                <div class="bg-purple-100 rounded-full p-3">
                    <i class='bx bx-dollar text-purple-600 text-2xl'></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Alert for contracts needing attention -->
    @if($expiringSoon->count() > 0 || $needingRenewal->count() > 0)
        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-6">
            <div class="flex items-start">
                <i class='bx bx-error text-yellow-600 text-xl mr-3 mt-0.5'></i>
                <div class="flex-1">
                    <h3 class="text-sm font-medium text-yellow-800">Contracts Needing Attention</h3>
                    <div class="mt-2 text-sm text-yellow-700">
                        @if($expiringSoon->count() > 0)
                            <p>• {{ $expiringSoon->count() }} contract(s) expiring within 30 days</p>
                        @endif
                        @if($needingRenewal->count() > 0)
                            <p>• {{ $needingRenewal->count() }} contract(s) need renewal</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Filter Section -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">
        <form method="GET" action="{{ route('admin.procurement.create-contract-reports') }}">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">
                <!-- Search -->
                <div class="lg:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Search Contracts</label>
                    <div class="relative">
                        <input type="text" 
                               name="search"
                               value="{{ request('search') }}"
                               placeholder="Contract Number, Name, Vendor, or Created By..." 
                               class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <i class='bx bx-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400 text-xl'></i>
                    </div>
                </div>

                <!-- Status -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                    <select name="status" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="">All Status</option>
                        <option value="Draft" {{ request('status') === 'Draft' ? 'selected' : '' }}>Draft</option>
                        <option value="Under Review" {{ request('status') === 'Under Review' ? 'selected' : '' }}>Under Review</option>
                        <option value="Active" {{ request('status') === 'Active' ? 'selected' : '' }}>Active</option>
                        <option value="Expired" {{ request('status') === 'Expired' ? 'selected' : '' }}>Expired</option>
                        <option value="Terminated" {{ request('status') === 'Terminated' ? 'selected' : '' }}>Terminated</option>
                        <option value="Renewed" {{ request('status') === 'Renewed' ? 'selected' : '' }}>Renewed</option>
                    </select>
                </div>

                <!-- Renewal Status -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Renewal Status</label>
                    <select name="renewal_status" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="">All Contracts</option>
                        <option value="expiring_soon" {{ request('renewal_status') === 'expiring_soon' ? 'selected' : '' }}>Expiring Soon</option>
                        <option value="needs_renewal" {{ request('renewal_status') === 'needs_renewal' ? 'selected' : '' }}>Needs Renewal</option>
                        <option value="expired" {{ request('renewal_status') === 'expired' ? 'selected' : '' }}>Expired</option>
                    </select>
                </div>

                <!-- Contract Type -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Contract Type</label>
                    <select name="contract_type" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="">All Types</option>
                        <option value="Service" {{ request('contract_type') === 'Service' ? 'selected' : '' }}>Service</option>
                        <option value="Supply" {{ request('contract_type') === 'Supply' ? 'selected' : '' }}>Supply</option>
                        <option value="Maintenance" {{ request('contract_type') === 'Maintenance' ? 'selected' : '' }}>Maintenance</option>
                        <option value="Consulting" {{ request('contract_type') === 'Consulting' ? 'selected' : '' }}>Consulting</option>
                        <option value="Software License" {{ request('contract_type') === 'Software License' ? 'selected' : '' }}>Software License</option>
                        <option value="Hardware Lease" {{ request('contract_type') === 'Hardware Lease' ? 'selected' : '' }}>Hardware Lease</option>
                        <option value="Other" {{ request('contract_type') === 'Other' ? 'selected' : '' }}>Other</option>
                    </select>
                </div>

                <!-- Vendor -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Vendor</label>
                    <input type="text" 
                           name="vendor"
                           value="{{ request('vendor') }}"
                           placeholder="Filter by vendor..." 
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>

                <!-- Priority -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Priority</label>
                    <select name="priority" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="">All Priority</option>
                        <option value="Low" {{ request('priority') === 'Low' ? 'selected' : '' }}>Low</option>
                        <option value="Medium" {{ request('priority') === 'Medium' ? 'selected' : '' }}>Medium</option>
                        <option value="High" {{ request('priority') === 'High' ? 'selected' : '' }}>High</option>
                        <option value="Urgent" {{ request('priority') === 'Urgent' ? 'selected' : '' }}>Urgent</option>
                    </select>
                </div>
            </div>

            <!-- Filter Buttons -->
            <div class="flex gap-3 mt-6">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-6 rounded-lg transition-colors">
                    <i class='bx bx-filter-alt mr-2'></i>
                    Apply Filters
                </button>
                <a href="{{ route('admin.procurement.create-contract-reports') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-medium py-2 px-6 rounded-lg transition-colors">
                    <i class='bx bx-x mr-2'></i>
                    Clear
                </a>
            </div>
        </form>
    </div>

    <!-- Contracts Table -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
            <div>
                <h2 class="text-lg font-semibold text-gray-900">Contracts & Reports</h2>
                <p class="text-sm text-gray-600 mt-1">Manage all contracts and track renewals</p>
            </div>
            <div class="flex gap-2">
                <button onclick="toggleBulkActions()" class="text-gray-600 hover:text-gray-900 p-2 rounded-lg hover:bg-gray-100" title="Bulk Actions">
                    <i class='bx bx-checkbox text-xl'></i>
                </button>
                <button onclick="window.location.reload()" class="text-gray-600 hover:text-gray-900 p-2 rounded-lg hover:bg-gray-100" title="Refresh">
                    <i class='bx bx-refresh text-xl'></i>
                </button>
                <a href="{{ route('contracts.export') }}" class="text-gray-600 hover:text-gray-900 p-2 rounded-lg hover:bg-gray-100" title="Export">
                    <i class='bx bx-download text-xl'></i>
                </a>
            </div>
        </div>

        <!-- Bulk Actions (Hidden by default) -->
        <div id="bulkActions" class="hidden px-6 py-3 bg-gray-50 border-b border-gray-200">
            <form id="bulkActionForm" method="POST" action="{{ route('contracts.bulk-action') }}">
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
                            <option value="Draft">Draft</option>
                            <option value="Under Review">Under Review</option>
                            <option value="Active">Active</option>
                            <option value="Expired">Expired</option>
                            <option value="Terminated">Terminated</option>
                            <option value="Renewed">Renewed</option>
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
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Contract Number</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Contract Name</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Vendor</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Value</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Start Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">End Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Days Left</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Priority</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse ($contracts as $contract)
                        <tr class="hover:bg-gray-50">
                            <td class="bulk-select-cell hidden px-6 py-4">
                                <input type="checkbox" name="contract_ids[]" value="{{ $contract->id }}" class="contract-checkbox rounded border-gray-300">
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $contract->contract_number }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $contract->contract_name }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div>
                                    <p class="text-sm font-medium text-gray-900">{{ $contract->vendor }}</p>
                                    @if ($contract->vendor_contact)
                                        <p class="text-xs text-gray-500">{{ $contract->vendor_contact }}</p>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                    {{ $contract->contract_type }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                ₱{{ number_format($contract->contract_value, 2) }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $contract->start_date->format('M d, Y') }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $contract->end_date->format('M d, Y') }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <x-contract-days-left :contract="$contract" />
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if ($contract->status === 'Draft')
                                    <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                        Draft
                                    </span>
                                @elseif ($contract->status === 'Under Review')
                                    <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-orange-100 text-orange-800">
                                        Under Review
                                    </span>
                                @elseif ($contract->status === 'Active')
                                    <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                        Active
                                    </span>
                                @elseif ($contract->status === 'Expired')
                                    <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                        Expired
                                    </span>
                                @elseif ($contract->status === 'Terminated')
                                    <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                        Terminated
                                    </span>
                                @else
                                    <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-purple-100 text-purple-800">
                                        Renewed
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if ($contract->priority === 'Urgent')
                                    <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                        Urgent
                                    </span>
                                @elseif ($contract->priority === 'High')
                                    <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-orange-100 text-orange-800">
                                        High
                                    </span>
                                @elseif ($contract->priority === 'Medium')
                                    <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                        Medium
                                    </span>
                                @else
                                    <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                        Low
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex space-x-2">
                                    <a href="{{ route('contracts.show', $contract->id) }}" class="text-blue-600 hover:text-blue-900" title="View">
                                        <i class='bx bx-show text-lg'></i>
                                    </a>
                                    <a href="{{ route('contracts.edit', $contract->id) }}" class="text-green-600 hover:text-green-900" title="Edit">
                                        <i class='bx bx-edit text-lg'></i>
                                    </a>
                                    
                                    <!-- Renew Button -->
                                    @if($contract->status === 'Active' && $contract->end_date->isPast())
                                        <button onclick="openRenewModal({{ $contract->id }})" class="text-purple-600 hover:text-purple-900" title="Renew">
                                            <i class='bx bx-refresh text-lg'></i>
                                        </button>
                                    @endif
                                    
                                    <!-- Terminate Button -->
                                    @if(in_array($contract->status, ['Active', 'Under Review']))
                                        <button onclick="openTerminateModal({{ $contract->id }})" class="text-orange-600 hover:text-orange-900" title="Terminate">
                                            <i class='bx bx-block text-lg'></i>
                                        </button>
                                    @endif
                                    
                                    <form action="{{ route('contracts.destroy', $contract->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this contract?')" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900" title="Delete">
                                            <i class='bx bx-trash text-lg'></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="12" class="px-6 py-8 text-center text-gray-500">
                                <div class="flex flex-col items-center">
                                    <i class='bx bx-file text-4xl text-gray-300 mb-3'></i>
                                    <p class="text-lg font-medium">No contracts found</p>
                                    <p class="text-sm mt-1">Get started by creating your first contract.</p>
                                    <a href="{{ route('contracts.create') }}" class="mt-4 bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg inline-flex items-center gap-2 transition-colors">
                                        <i class='bx bx-plus'></i>
                                        Create First Contract
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
                {{ $contracts->links() }}
            </div>
            <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                <div>
                    <p class="text-sm text-gray-700">
                        Showing <span class="font-medium">{{ $contracts->firstItem() ?? 0 }}</span> to <span class="font-medium">{{ $contracts->lastItem() ?? 0 }}</span> of{' '}
                        <span class="font-medium">{{ $contracts->total() }}</span> results
                    </p>
                </div>
                <div>
                    {{ $contracts->links() }}
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Renew Modal -->
<div id="renewModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-lg bg-white">
        <div class="mt-3">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Renew Contract</h3>
            <form id="renewForm" method="POST">
                @csrf
                <input type="hidden" name="contract_id" id="renewContractId">
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">New End Date</label>
                    <input type="date" name="new_end_date" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Renewal Terms (Optional)</label>
                    <textarea name="renewal_terms" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
                </div>
                <div class="flex justify-end gap-3">
                    <button type="button" onclick="closeRenewModal()" class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-medium py-2 px-4 rounded-lg">
                        Cancel
                    </button>
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg">
                        Renew Contract
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Terminate Modal -->
<div id="terminateModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-lg bg-white">
        <div class="mt-3">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Terminate Contract</h3>
            <form id="terminateForm" method="POST">
                @csrf
                <input type="hidden" name="contract_id" id="terminateContractId">
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Termination Reason</label>
                    <textarea name="termination_reason" required rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
                </div>
                <div class="flex justify-end gap-3">
                    <button type="button" onclick="closeTerminateModal()" class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-medium py-2 px-4 rounded-lg">
                        Cancel
                    </button>
                    <button type="submit" class="bg-red-600 hover:bg-red-700 text-white font-medium py-2 px-4 rounded-lg">
                        Terminate Contract
                    </button>
                </div>
            </form>
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
    const checkboxes = document.querySelectorAll('.contract-checkbox');
    checkboxes.forEach(checkbox => checkbox.checked = selectAll.checked);
}

function openRenewModal(contractId) {
    document.getElementById('renewContractId').value = contractId;
    document.getElementById('renewModal').classList.remove('hidden');
}

function closeRenewModal() {
    document.getElementById('renewModal').classList.add('hidden');
}

function openTerminateModal(contractId) {
    document.getElementById('terminateContractId').value = contractId;
    document.getElementById('terminateModal').classList.remove('hidden');
}

function closeTerminateModal() {
    document.getElementById('terminateModal').classList.add('hidden');
}

// Handle bulk action form
document.getElementById('bulkActionForm').addEventListener('submit', function(e) {
    const action = this.querySelector('select[name="action"]').value;
    if (!action) {
        e.preventDefault();
        alert('Please select an action');
        return;
    }
    
    const checkboxes = document.querySelectorAll('.contract-checkbox:checked');
    if (checkboxes.length === 0) {
        e.preventDefault();
        alert('Please select at least one contract');
        return;
    }
});

// Handle renew form
document.getElementById('renewForm').addEventListener('submit', function(e) {
    e.preventDefault();
    const form = this;
    const formData = new FormData(form);
    
    fetch(`/contracts/${formData.get('contract_id')}/renew`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
            new_end_date: formData.get('new_end_date'),
            renewal_terms: formData.get('renewal_terms')
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            window.location.reload();
        } else {
            alert(data.message || 'Error renewing contract');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error renewing contract');
    });
});

// Handle terminate form
document.getElementById('terminateForm').addEventListener('submit', function(e) {
    e.preventDefault();
    const form = this;
    const formData = new FormData(form);
    
    fetch(`/contracts/${formData.get('contract_id')}/terminate`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
            termination_reason: formData.get('termination_reason')
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            window.location.reload();
        } else {
            alert(data.message || 'Error terminating contract');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error terminating contract');
    });
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
