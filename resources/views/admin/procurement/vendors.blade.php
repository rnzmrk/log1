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
                    <a href="{{ route('procurement.vendors') }}" class="text-gray-700 hover:text-blue-600 ml-1 md:ml-2">Vendors</a>
                </div>
            </li>
            <li aria-current="page">
                <div class="flex items-center">
                    <i class='bx bx-chevron-right text-gray-400'></i>
                    <span class="ml-1 text-gray-500 md:ml-2">Vendor Management</span>
                </div>
            </li>
        </ol>
    </nav>

    <!-- Page Header -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Supplier Management</h1>
            <p class="text-gray-600 mt-1">Manage suppliers including website registrations</p>
        </div>
        <div class="flex gap-3">
            <a href="{{ route('vendors.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg flex items-center gap-2 transition-colors">
                <i class='bx bx-plus text-xl'></i>
                Add Supplier
            </a>
            <a href="{{ route('vendors.export') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-medium py-2 px-4 rounded-lg flex items-center gap-2 transition-colors">
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
                    <p class="text-sm font-medium text-gray-600">Total Suppliers</p>
                    <p class="text-2xl font-bold text-blue-600">{{ number_format($stats['total_suppliers']) }}</p>
                    <p class="text-xs text-gray-600 mt-1">All registered suppliers</p>
                </div>
                <div class="bg-blue-100 rounded-full p-3">
                    <i class='bx bx-store text-blue-600 text-2xl'></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Active Suppliers</p>
                    <p class="text-2xl font-bold text-green-600">{{ number_format($stats['active_suppliers']) }}</p>
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
                    <p class="text-sm font-medium text-gray-600">Pending Approval</p>
                    <p class="text-2xl font-bold text-orange-600">{{ number_format($stats['pending_suppliers']) }}</p>
                    <p class="text-xs text-gray-600 mt-1">Website registrations awaiting review</p>
                </div>
                <div class="bg-orange-100 rounded-full p-3">
                    <i class='bx bx-time text-orange-600 text-2xl'></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">High Rated</p>
                    <p class="text-2xl font-bold text-purple-600">{{ number_format($stats['high_rated_suppliers']) }}</p>
                    <p class="text-xs text-gray-600 mt-1">4.0+ rating</p>
                </div>
                <div class="bg-purple-100 rounded-full p-3">
                    <i class='bx bx-star text-purple-600 text-2xl'></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter Section -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">
        <div class="flex items-center mb-4">
            <i class='bx bx-filter-alt text-xl text-blue-600 mr-2'></i>
            <h2 class="text-lg font-semibold text-gray-900">Filter Suppliers</h2>
            <p class="text-sm text-gray-600 ml-2">Search and filter suppliers by various criteria</p>
        </div>
        <form method="GET" action="{{ route('procurement.vendors') }}">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <!-- Search -->
                <div class="lg:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Search Vendors</label>
                    <div class="relative">
                        <input type="text" 
                               name="search"
                               value="{{ request('search') }}"
                               placeholder="Search by name, code, contact, or email..." 
                               class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <i class='bx bx-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400 text-xl'></i>
                    </div>
                </div>

                <!-- Status -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                    <select name="status" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="">All Status</option>
                        <option value="Active" {{ request('status') === 'Active' ? 'selected' : '' }}>Active</option>
                        <option value="Inactive" {{ request('status') === 'Inactive' ? 'selected' : '' }}>Inactive</option>
                        <option value="Pending" {{ request('status') === 'Pending' ? 'selected' : '' }}>Pending</option>
                        <option value="Suspended" {{ request('status') === 'Suspended' ? 'selected' : '' }}>Suspended</option>
                        <option value="Under Review" {{ request('status') === 'Under Review' ? 'selected' : '' }}>Under Review</option>
                    </select>
                </div>

                <!-- Category -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Category</label>
                    <select name="category" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="">All Categories</option>
                        @foreach($filterOptions['categories'] as $category)
                            <option value="{{ $category }}" {{ request('category') === $category ? 'selected' : '' }}>{{ $category }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <!-- Additional Filters -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Rating</label>
                    <select name="rating" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="">All Ratings</option>
                        <option value="5" {{ request('rating') === '5' ? 'selected' : '' }}>5 Stars (4.5+)</option>
                        <option value="4" {{ request('rating') === '4' ? 'selected' : '' }}>4 Stars (3.5-4.4)</option>
                        <option value="3" {{ request('rating') === '3' ? 'selected' : '' }}>3 Stars (2.5-3.4)</option>
                        <option value="2" {{ request('rating') === '2' ? 'selected' : '' }}>Below 3 Stars</option>
                    </select>
                </div>
            </div>

            <!-- Filter Buttons -->
            <div class="flex gap-3 mt-6">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-6 rounded-lg transition-colors">
                    <i class='bx bx-filter-alt mr-2'></i>
                    Apply Filters
                </button>
                <a href="{{ route('procurement.vendors') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-medium py-2 px-6 rounded-lg transition-colors">
                    <i class='bx bx-x mr-2'></i>
                    Clear
                </a>
            </div>
        </form>
    </div>

    <!-- Suppliers Table -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-gray-50 to-gray-100">
            <div class="flex justify-between items-center">
                <div class="flex items-center">
                    <i class='bx bx-list-ul text-xl text-blue-600 mr-3'></i>
                    <div>
                        <h2 class="text-lg font-semibold text-gray-900">Supplier List</h2>
                        <p class="text-sm text-gray-600">Manage all registered suppliers and their information</p>
                    </div>
                </div>
                <div class="flex gap-2">
                    <a href="{{ route('procurement.vendors') }}" class="text-gray-600 hover:text-gray-900 p-2 rounded-lg hover:bg-gray-200 transition-colors" title="Refresh">
                        <i class='bx bx-refresh text-xl'></i>
                    </a>
                    <a href="{{ route('vendors.export') }}" class="text-gray-600 hover:text-gray-900 p-2 rounded-lg hover:bg-gray-200 transition-colors" title="Export">
                        <i class='bx bx-download text-xl'></i>
                    </a>
                </div>
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Supplier</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Contact</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Category</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Rating</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Compliance</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse ($suppliers as $supplier)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="h-10 w-10 rounded-full bg-{{ $supplier->avatar_color }}-600 flex items-center justify-center text-white text-xs font-bold">
                                        {{ $supplier->initials }}
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900">{{ $supplier->name }}</div>
                                        <div class="text-sm text-gray-500">{{ $supplier->vendor_code }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $supplier->contact_person ?? 'N/A' }}</div>
                                <div class="text-sm text-gray-500">{{ $supplier->email ?? 'No email' }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $supplier->category ?? 'N/A' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($supplier->rating)
                                    <div class="flex items-center">
                                        <span class="text-sm font-medium text-{{ $supplier->rating_color }}-600">{{ $supplier->rating }}</span>
                                        <i class='bx bx-star text-yellow-400 text-sm ml-1'></i>
                                    </div>
                                @else
                                    <span class="text-sm text-gray-500">Not rated</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-{{ $supplier->status_color }}-100 text-{{ $supplier->status_color }}-800">
                                    {{ $supplier->status }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @php
                                    $complianceStatus = $supplier->compliance_status;
                                    $complianceColor = match($complianceStatus) {
                                        'compliant' => 'green',
                                        'pending' => 'yellow',
                                        'non-compliant' => 'red',
                                        default => 'gray',
                                    };
                                @endphp
                                <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-{{ $complianceColor }}-100 text-{{ $complianceColor }}-800" title="Validation: {{ $supplier->validation_status ?? 'none' }}, Verification: {{ $supplier->verification_status ?? 'none' }}">
                                    {{ ucfirst($complianceStatus) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex space-x-2">
                                    <a href="{{ route('vendors.show', $supplier->id) }}" class="text-blue-600 hover:text-blue-900" title="View">
                                        <i class='bx bx-show text-lg'></i>
                                    </a>
                                    <a href="{{ route('vendors.edit', $supplier->id) }}" class="text-green-600 hover:text-green-900" title="Edit">
                                        <i class='bx bx-edit text-lg'></i>
                                    </a>
                                    
                                    <!-- Validation Button -->
                                    <a href="{{ route('vendors.validations.index', $supplier->id) }}" class="text-indigo-600 hover:text-indigo-900" title="Manage Validations">
                                        <i class='bx bx-certification text-lg'></i>
                                    </a>
                                    
                                    <!-- Verification Button -->
                                    <a href="{{ route('vendors.verifications.index', $supplier->id) }}" class="text-teal-600 hover:text-teal-900" title="Manage Verifications">
                                        <i class='bx bx-check-shield text-lg'></i>
                                    </a>
                                    
                                    @if($supplier->status !== 'Active')
                                        <form action="{{ route('vendors.approve', $supplier->id) }}" method="POST" class="inline" onsubmit="return confirm('Approve this supplier?')">
                                            @csrf
                                            <button type="submit" class="text-purple-600 hover:text-purple-900" title="Approve">
                                                <i class='bx bx-check-circle text-lg'></i>
                                            </button>
                                        </form>
                                    @endif
                                    
                                    <form action="{{ route('vendors.destroy', $supplier->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this supplier?')" class="inline">
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
                            <td colspan="7" class="px-6 py-8 text-center text-gray-500">
                                <div class="flex flex-col items-center">
                                    <i class='bx bx-store text-4xl text-gray-300 mb-3'></i>
                                    <p class="text-lg font-medium">No suppliers found</p>
                                    <p class="text-sm mt-1">Suppliers will appear here after they register on the website.</p>
                                    <a href="{{ route('vendors.create') }}" class="mt-4 bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg transition-colors">
                                        <i class='bx bx-plus mr-2'></i>
                                        Add Supplier
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
                {{ $suppliers->links() }}
            </div>
            <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                <div>
                    <p class="text-sm text-gray-700">
                        Showing <span class="font-medium">{{ $suppliers->firstItem() ?? 0 }}</span> to <span class="font-medium">{{ $suppliers->lastItem() ?? 0 }}</span> of{' '}
                        <span class="font-medium">{{ $suppliers->total() }}</span> results
                    </p>
                </div>
                <div>
                    {{ $suppliers->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
