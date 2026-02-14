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
                    <span class="text-gray-500 ml-1 md:ml-2">Validation</span>
                </div>
            </li>
        </ol>
    </nav>

    <!-- Page Header -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Supplier Validation</h1>
            <p class="text-gray-600 mt-1">Manage and validate supplier information</p>
        </div>
        <div class="flex gap-3">
            <a href="{{ route('admin.documenttracking.create-document-reports') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-medium py-2 px-4 rounded-lg flex items-center gap-2 transition-colors">
                <i class='bx bx-arrow-back text-xl'></i>
                Back to Contracts
            </a>
            <a href="{{ route('admin.documenttracking.export-validation', request()->query()) }}" class="bg-green-600 hover:bg-green-700 text-white font-medium py-2 px-4 rounded-lg flex items-center gap-2 transition-colors">
                <i class='bx bx-download text-xl'></i>
                Export CSV
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

    <!-- Filter Section -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">
        <form method="GET" action="{{ route('admin.documenttracking.validation') }}">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <!-- Search -->
                <div class="lg:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Search Suppliers</label>
                    <div class="relative">
                        <input type="text" 
                               name="search"
                               value="{{ request('search') }}"
                               placeholder="Supplier Name, Contact Person, or Email..." 
                               class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <i class='bx bx-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400 text-xl'></i>
                    </div>
                </div>

                <!-- Validation Status -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Validation Status</label>
                    <select name="validation_status" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="">All Status</option>
                        <option value="Pending" {{ request('validation_status') === 'Pending' ? 'selected' : '' }}>Pending</option>
                        <option value="Active" {{ request('validation_status') === 'Active' ? 'selected' : '' }}>Active</option>
                        <option value="Suspended" {{ request('validation_status') === 'Suspended' ? 'selected' : '' }}>Suspended</option>
                        <option value="Under Review" {{ request('validation_status') === 'Under Review' ? 'selected' : '' }}>Under Review</option>
                        <option value="Inactive" {{ request('validation_status') === 'Inactive' ? 'selected' : '' }}>Inactive</option>
                    </select>
                </div>

                <!-- Date Range -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Submission Date</label>
                    <select name="date_range" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="">All Time</option>
                        <option value="today" {{ request('date_range') === 'today' ? 'selected' : '' }}>Today</option>
                        <option value="week" {{ request('date_range') === 'week' ? 'selected' : '' }}>This Week</option>
                        <option value="month" {{ request('date_range') === 'month' ? 'selected' : '' }}>This Month</option>
                        <option value="year" {{ request('date_range') === 'year' ? 'selected' : '' }}>This Year</option>
                    </select>
                </div>
            </div>

            <!-- Filter Buttons -->
            <div class="flex gap-3 mt-6">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-6 rounded-lg transition-colors">
                    <i class='bx bx-filter-alt mr-2'></i>
                    Apply Filters
                </button>
                <a href="{{ route('admin.documenttracking.validation') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-medium py-2 px-6 rounded-lg transition-colors">
                    <i class='bx bx-refresh mr-2'></i>
                    Clear Filters
                </a>
            </div>
        </form>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Total Suppliers</p>
                    <p class="text-2xl font-bold text-blue-600">{{ number_format($stats['total_suppliers']) }}</p>
                    <p class="text-xs text-gray-600 mt-1">All suppliers</p>
                </div>
                <div class="bg-blue-100 rounded-full p-3">
                    <i class='bx bx-building text-blue-600 text-2xl'></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Validated</p>
                    <p class="text-2xl font-bold text-green-600">{{ number_format($stats['validated']) }}</p>
                    <p class="text-xs text-gray-600 mt-1">Successfully validated</p>
                </div>
                <div class="bg-green-100 rounded-full p-3">
                    <i class='bx bx-check-circle text-green-600 text-2xl'></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Pending</p>
                    <p class="text-2xl font-bold text-orange-600">{{ number_format($stats['pending']) }}</p>
                    <p class="text-xs text-gray-600 mt-1">Awaiting validation</p>
                </div>
                <div class="bg-orange-100 rounded-full p-3">
                    <i class='bx bx-time text-orange-600 text-2xl'></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Rejected</p>
                    <p class="text-2xl font-bold text-red-600">{{ number_format($stats['rejected']) }}</p>
                    <p class="text-xs text-gray-600 mt-1">Validation failed</p>
                </div>
                <div class="bg-red-100 rounded-full p-3">
                    <i class='bx bx-x-circle text-red-600 text-2xl'></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Suppliers Table -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-lg font-semibold text-gray-900">Supplier Validations</h2>
        </div>
        
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Supplier Name</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Contact Person</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Phone</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Validation Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Submitted Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse ($suppliers as $supplier)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">{{ $supplier->name }}</div>
                                @if ($supplier->category)
                                    <div class="text-xs text-gray-500">{{ $supplier->category }}</div>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $supplier->contact_person }}</div>
                                @if ($supplier->vendor_code)
                                    <div class="text-xs text-gray-500">{{ $supplier->vendor_code }}</div>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $supplier->email }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $supplier->phone }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if ($supplier->status === 'Active')
                                    <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                        Active
                                    </span>
                                @elseif ($supplier->status === 'Pending')
                                    <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-orange-100 text-orange-800">
                                        Pending
                                    </span>
                                @elseif ($supplier->status === 'Suspended')
                                    <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                        Suspended
                                    </span>
                                @elseif ($supplier->status === 'Under Review')
                                    <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                        Under Review
                                    </span>
                                @else
                                    <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                        {{ $supplier->status }}
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $supplier->created_at->format('M d, Y') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex space-x-2">
                                    <a href="{{ route('admin.documenttracking.show-validation', $supplier->id) }}" class="text-blue-600 hover:text-blue-900" title="View Details">
                                        <i class='bx bx-show text-lg'></i>
                                    </a>
                                    @if ($supplier->status === 'Pending' || $supplier->status === 'Under Review')
                                        <a href="{{ route('admin.documenttracking.edit-validation', $supplier->id) }}" class="text-green-600 hover:text-green-900" title="Validate">
                                            <i class='bx bx-check-circle text-lg'></i>
                                        </a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center text-gray-500">
                                <i class='bx bx-inbox text-4xl text-gray-300 mb-3 block'></i>
                                No supplier validations found
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if ($suppliers->hasPages())
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $suppliers->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
