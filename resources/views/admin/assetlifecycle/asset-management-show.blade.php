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
                    <a href="{{ route('admin.assetlifecycle.asset-management') }}" class="text-gray-700 hover:text-blue-600 ml-1 md:ml-2">Asset Management</a>
                </div>
            </li>
            <li aria-current="page">
                <div class="flex items-center">
                    <i class='bx bx-chevron-right text-gray-400'></i>
                    <span class="ml-1 text-gray-500 md:ml-2">Asset Details</span>
                </div>
            </li>
        </ol>
    </nav>

    <!-- Page Header -->
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-900">Asset Details</h1>
        <div class="flex gap-3">
            <a href="{{ route('asset-management.edit', $asset->id) }}" class="bg-green-600 hover:bg-green-700 text-white font-medium py-2 px-4 rounded-lg flex items-center gap-2 transition-colors">
                <i class='bx bx-edit text-xl'></i>
                Edit Asset
            </a>
            <a href="{{ route('admin.assetlifecycle.asset-management') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-medium py-2 px-4 rounded-lg flex items-center gap-2 transition-colors">
                <i class='bx bx-arrow-back text-xl'></i>
                Back to Assets
            </a>
        </div>
    </div>

    <!-- Success Message -->
    @if (session('success'))
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

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Asset Information -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-900">Asset Information</h2>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <p class="text-sm text-gray-600">Asset Tag</p>
                            <p class="font-semibold text-gray-900">{{ $asset->asset_tag }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Asset Name</p>
                            <p class="font-semibold text-gray-900">{{ $asset->asset_name }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Asset Type</p>
                            <p class="font-semibold text-gray-900">{{ $asset->asset_type }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Category</p>
                            <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                {{ $asset->category }}
                            </span>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Brand/Model</p>
                            <p class="font-semibold text-gray-900">
                                @if ($asset->brand && $asset->model)
                                    {{ $asset->brand }} {{ $asset->model }}
                                @elseif ($asset->brand)
                                    {{ $asset->brand }}
                                @elseif ($asset->model)
                                    {{ $asset->model }}
                                @else
                                    Not specified
                                @endif
                            </p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Serial Number</p>
                            <p class="font-semibold text-gray-900">{{ $asset->serial_number ?: 'Not specified' }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Status</p>
                            @if ($asset->status === 'Available')
                                <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                    Available
                                </span>
                            @elseif ($asset->status === 'In Use')
                                <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                    In Use
                                </span>
                            @elseif ($asset->status === 'Under Maintenance')
                                <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-orange-100 text-orange-800">
                                    Under Maintenance
                                </span>
                            @elseif ($asset->status === 'Retired')
                                <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                    Retired
                                </span>
                            @elseif ($asset->status === 'Lost')
                                <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                    Lost
                                </span>
                            @else
                                <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                    Damaged
                                </span>
                            @endif
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Condition</p>
                            @if ($asset->condition === 'Excellent')
                                <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                    Excellent
                                </span>
                            @elseif ($asset->condition === 'Good')
                                <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                    Good
                                </span>
                            @elseif ($asset->condition === 'Fair')
                                <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                    Fair
                                </span>
                            @else
                                <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                    Poor
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Financial Information -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-900">Financial Information</h2>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <p class="text-sm text-gray-600">Purchase Cost</p>
                            <p class="font-semibold text-gray-900">
                                @if ($asset->purchase_cost)
                                    ₱{{ number_format($asset->purchase_cost, 2) }}
                                @else
                                    Not specified
                                @endif
                            </p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Purchase Date</p>
                            <p class="font-semibold text-gray-900">
                                @if ($asset->purchase_date)
                                    {{ $asset->purchase_date->format('M d, Y') }}
                                @else
                                    Not specified
                                @endif
                            </p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Warranty Expiry</p>
                            <p class="font-semibold text-gray-900">
                                @if ($asset->warranty_expiry)
                                    {{ $asset->warranty_expiry->format('M d, Y') }}
                                    @if ($asset->warranty_expiry->isPast())
                                        <span class="text-red-600 text-xs">(Expired)</span>
                                    @elseif ($asset->warranty_expiry->diffInDays(now()) <= 30)
                                        <span class="text-orange-600 text-xs">(Expires Soon)</span>
                                    @endif
                                @else
                                    Not specified
                                @endif
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Assignment Information -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-900">Assignment Information</h2>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <p class="text-sm text-gray-600">Assigned To</p>
                            <p class="font-semibold text-gray-900">{{ $asset->assigned_to ?: 'Not assigned' }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Department</p>
                            <p class="font-semibold text-gray-900">{{ $asset->department ?: 'Not specified' }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Location</p>
                            <p class="font-semibold text-gray-900">{{ $asset->location ?: 'Not specified' }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Specifications -->
            @if ($asset->specifications)
                <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h2 class="text-lg font-semibold text-gray-900">Asset Specifications</h2>
                    </div>
                    <div class="p-6">
                        <p class="text-gray-900 whitespace-pre-wrap">{{ $asset->specifications }}</p>
                    </div>
                </div>
            @endif

            <!-- Additional Notes -->
            @if ($asset->notes)
                <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h2 class="text-lg font-semibold text-gray-900">Additional Notes</h2>
                    </div>
                    <div class="p-6">
                        <p class="text-gray-900 whitespace-pre-wrap">{{ $asset->notes }}</p>
                    </div>
                </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Maintenance Information -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-900">Maintenance Information</h2>
                </div>
                <div class="p-6">
                    <div class="space-y-4">
                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                <i class='bx bx-wrench text-orange-600 text-lg mt-1'></i>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-gray-900">Last Maintenance</p>
                                <p class="text-sm text-gray-600">
                                    @if ($asset->last_maintenance_date)
                                        {{ $asset->last_maintenance_date->format('M d, Y') }}
                                    @else
                                        No maintenance recorded
                                    @endif
                                </p>
                            </div>
                        </div>
                        
                        @if ($asset->next_maintenance_date)
                            <div class="flex items-start">
                                <div class="flex-shrink-0">
                                    <i class='bx bx-calendar text-blue-600 text-lg mt-1'></i>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm font-medium text-gray-900">Next Maintenance</p>
                                    <p class="text-sm text-gray-600">{{ $asset->next_maintenance_date->format('M d, Y') }}</p>
                                    @if ($asset->next_maintenance_date->isPast())
                                        <p class="text-xs text-red-600">Overdue</p>
                                    @elseif ($asset->next_maintenance_date->diffInDays(now()) <= 7)
                                        <p class="text-xs text-orange-600">Due Soon</p>
                                    @endif
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Asset History -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-900">Asset History</h2>
                </div>
                <div class="p-6">
                    <div class="space-y-4">
                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                <i class='bx bx-plus-circle text-green-600 text-lg mt-1'></i>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-gray-900">Created</p>
                                <p class="text-sm text-gray-600">{{ $asset->created_at->format('M d, Y') }} by {{ $asset->created_by }}</p>
                            </div>
                        </div>
                        
                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                <i class='bx bx-edit text-blue-600 text-lg mt-1'></i>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-gray-900">Last Updated</p>
                                <p class="text-sm text-gray-600">{{ $asset->updated_at->format('M d, Y') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-900">Quick Actions</h2>
                </div>
                <div class="p-6 space-y-3">
                    <a href="{{ route('asset-management.edit', $asset->id) }}" class="w-full text-left px-4 py-3 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors flex items-center gap-3">
                        <i class='bx bx-edit text-green-600 text-xl'></i>
                        <div>
                            <p class="font-medium text-gray-900">Edit Asset</p>
                            <p class="text-sm text-gray-600">Modify details</p>
                        </div>
                    </a>
                    
                    @if ($asset->status === 'Available')
                        <form action="{{ route('asset-management.update', $asset->id) }}" method="POST" class="w-full">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="status" value="In Use">
                            <button type="submit" class="w-full text-left px-4 py-3 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors flex items-center gap-3">
                                <i class='bx bx-user-plus text-blue-600 text-xl'></i>
                                <div>
                                    <p class="font-medium text-gray-900">Assign Asset</p>
                                    <p class="text-sm text-gray-600">Mark as in use</p>
                                </div>
                            </button>
                        </form>
                    @endif
                    
                    @if ($asset->status === 'In Use')
                        <form action="{{ route('asset-management.update', $asset->id) }}" method="POST" class="w-full">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="status" value="Available">
                            <input type="hidden" name="assigned_to" value="">
                            <button type="submit" class="w-full text-left px-4 py-3 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors flex items-center gap-3">
                                <i class='bx bx-user-x text-orange-600 text-xl'></i>
                                <div>
                                    <p class="font-medium text-gray-900">Unassign Asset</p>
                                    <p class="text-sm text-gray-600">Mark as available</p>
                                </div>
                            </button>
                        </form>
                    @endif
                    
                    @if ($asset->status !== 'Under Maintenance')
                        <form action="{{ route('asset-management.update', $asset->id) }}" method="POST" class="w-full">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="status" value="Under Maintenance">
                            <button type="submit" class="w-full text-left px-4 py-3 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors flex items-center gap-3">
                                <i class='bx bx-wrench text-orange-600 text-xl'></i>
                                <div>
                                    <p class="font-medium text-gray-900">Schedule Maintenance</p>
                                    <p class="text-sm text-gray-600">Mark for service</p>
                                </div>
                            </button>
                        </form>
                    @endif
                    
                    @if ($asset->status === 'Under Maintenance')
                        <form action="{{ route('asset-management.update', $asset->id) }}" method="POST" class="w-full">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="status" value="Available">
                            <button type="submit" class="w-full text-left px-4 py-3 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors flex items-center gap-3">
                                <i class='bx bx-check text-green-600 text-xl'></i>
                                <div>
                                    <p class="font-medium text-gray-900">Complete Maintenance</p>
                                    <p class="text-sm text-gray-600">Mark as available</p>
                                </div>
                            </button>
                        </form>
                    @endif
                    
                    <form action="{{ route('asset-management.destroy', $asset->id) }}" method="POST" class="w-full" onsubmit="return confirm('Are you sure you want to delete this asset?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="w-full text-left px-4 py-3 border border-red-200 rounded-lg hover:bg-red-50 transition-colors flex items-center gap-3">
                            <i class='bx bx-trash text-red-600 text-xl'></i>
                            <div>
                                <p class="font-medium text-red-900">Delete Asset</p>
                                <p class="text-sm text-red-600">Remove permanently</p>
                            </div>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
