@extends('layouts.app')

@php
use App\Models\Asset;
@endphp

@section('content')
<div class="container-fluid px-6 py-8">
    <!-- Enhanced Header -->
    <div class="mb-8">
        <div class="flex justify-between items-center mb-4">
            <div>
                <h1 class="text-4xl font-bold text-gray-900 mb-2">Asset Management</h1>
                <p class="text-gray-600 text-lg">Manage your organization's assets efficiently</p>
            </div>
            <div class="flex gap-3">
                <button onclick="location.reload()" class="bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium py-2 px-4 rounded-lg flex items-center gap-2 transition-all duration-200 shadow-sm">
                    <i class='bx bx-refresh text-xl'></i>
                    Refresh
                </button>
                <a href="{{ route('asset-management.export') }}" class="bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium py-2 px-4 rounded-lg flex items-center gap-2 transition-all duration-200 shadow-sm">
                    <i class='bx bx-download text-xl'></i>
                    Export
                </a>
                <a href="{{ route('asset-management.create') }}" class="bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white font-medium py-2 px-6 rounded-lg flex items-center gap-2 transition-all duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                    <i class='bx bx-plus text-xl'></i>
                    Add New Asset
                </a>
            </div>
        </div>
        
        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
            <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl p-6 text-white shadow-lg">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-blue-100 text-sm font-medium">Total Assets</p>
                        <p class="text-3xl font-bold mt-1">{{ $assets->total() }}</p>
                    </div>
                    <div class="bg-blue-400 bg-opacity-30 rounded-lg p-3">
                        <i class='bx bx-package text-2xl'></i>
                    </div>
                </div>
            </div>
            <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-xl p-6 text-white shadow-lg">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-green-100 text-sm font-medium">Available</p>
                        <p class="text-3xl font-bold mt-1">{{ Asset::where('status', 'Available')->count() }}</p>
                    </div>
                    <div class="bg-green-400 bg-opacity-30 rounded-lg p-3">
                        <i class='bx bx-check-circle text-2xl'></i>
                    </div>
                </div>
            </div>
            <div class="bg-gradient-to-br from-yellow-500 to-yellow-600 rounded-xl p-6 text-white shadow-lg">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-yellow-100 text-sm font-medium">In Use</p>
                        <p class="text-3xl font-bold mt-1">{{ Asset::where('status', 'In Use')->count() }}</p>
                    </div>
                    <div class="bg-yellow-400 bg-opacity-30 rounded-lg p-3">
                        <i class='bx bx-user text-2xl'></i>
                    </div>
                </div>
            </div>
            <div class="bg-gradient-to-br from-red-500 to-red-600 rounded-xl p-6 text-white shadow-lg">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-red-100 text-sm font-medium">Need Disposal</p>
                        <p class="text-3xl font-bold mt-1">{{ Asset::getAssetsForDisposal()->count() }}</p>
                    </div>
                    <div class="bg-red-400 bg-opacity-30 rounded-lg p-3">
                        <i class='bx bx-trash text-2xl'></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Enhanced Filter Section -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                <i class='bx bx-filter text-xl text-blue-600'></i>
                Filter Assets
            </h2>
            <button type="button" onclick="document.getElementById('resetFilters').click()" class="text-sm text-gray-500 hover:text-gray-700">
                <i class='bx bx-reset'></i> Reset Filters
            </button>
        </div>
        <form method="GET" action="{{ route('admin.assetlifecycle.asset-management') }}">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <!-- Search -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Search Assets</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class='bx bx-search text-gray-400'></i>
                        </div>
                        <input type="text" 
                               name="search"
                               value="{{ request('search') }}"
                               placeholder="Search by asset name..." 
                               class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200">
                    </div>
                </div>
                
                <!-- Status Filter -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                    <select name="status" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="">All Status</option>
                        <option value="Available" {{ request('status') == 'Available' ? 'selected' : '' }}>Available</option>
                        <option value="In Use" {{ request('status') == 'In Use' ? 'selected' : '' }}>In Use</option>
                        <option value="Under Maintenance" {{ request('status') == 'Under Maintenance' ? 'selected' : '' }}>Under Maintenance</option>
                        <option value="Disposed" {{ request('status') == 'Disposed' ? 'selected' : '' }}>Disposed</option>
                        <option value="Requested" {{ request('status') == 'Requested' ? 'selected' : '' }}>Requested</option>
                    </select>
                </div>
                
                <!-- Date Filter -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Filter by Date Range</label>
                    <div class="flex gap-2">
                        <input type="date" 
                               name="date_from" 
                               value="{{ request('date_from') }}"
                               class="flex-1 px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                               placeholder="From date">
                        <input type="date" 
                               name="date_to" 
                               value="{{ request('date_to') }}"
                               class="flex-1 px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                               placeholder="To date">
                    </div>
                </div>
            </div>
            
            <div class="flex justify-end mt-4 space-x-3">
                <button type="button" id="resetFilters" name="reset" value="1" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors duration-200">
                    <i class='bx bx-reset'></i> Reset
                </button>
                <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors duration-200">
                    <i class='bx bx-search'></i> Search
                </button>
            </div>
        </form>
    </div>

    <!-- Enhanced Assets Table -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
            <div class="flex items-center justify-between">
                <h2 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                    <i class='bx bx-list-ul text-xl text-blue-600'></i>
                    Asset Inventory
                </h2>
                <div class="text-sm text-gray-500">
                    Showing {{ $assets->firstItem() ?? 0 }} to {{ $assets->lastItem() ?? 0 }} of {{ $assets->total() }} assets
                </div>
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Asset Name</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Details</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Quantity</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Department</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Duration</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse ($assets as $asset)
                        <tr class="hover:bg-gray-50 transition-colors duration-150">
                            <td class="px-6 py-4">
                                <div class="text-sm font-medium text-gray-900">{{ $asset->item_name }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm text-gray-600 max-w-xs truncate" title="{{ $asset->details ?? 'No details' }}">
                                    {{ $asset->details ?? 'No details' }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                    {{ $asset->quantity ?? 1 }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                    {{ $asset->department ?? 'Unassigned' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full shadow-sm
                                    @if($asset->status == 'Available') bg-gradient-to-r from-green-100 to-green-200 text-green-800 border border-green-300
                                    @elseif($asset->status == 'In Use') bg-gradient-to-r from-blue-100 to-blue-200 text-blue-800 border border-blue-300
                                    @elseif($asset->status == 'Under Maintenance') bg-gradient-to-r from-yellow-100 to-yellow-200 text-yellow-800 border border-yellow-300
                                    @elseif($asset->status == 'Disposed') bg-gradient-to-r from-red-100 to-red-200 text-red-800 border border-red-300
                                    @elseif($asset->status == 'Requested') bg-gradient-to-r from-purple-100 to-purple-200 text-purple-800 border border-purple-300
                                    @else bg-gradient-to-r from-gray-100 to-gray-200 text-gray-800 border border-gray-300
                                    @endif">
                                    {{ $asset->status }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">
                                    {{ $asset->date ? $asset->date->format('M d, Y') : 'N/A' }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm">
                                    @if ($asset->date)
                                        @php
                                            $now = \Carbon\Carbon::now();
                                            $assetDate = \Carbon\Carbon::parse($asset->date)->startOfDay();
                                            $nowDate = $now->copy()->startOfDay();
                                            $diffInDays = (int) $assetDate->diffInDays($nowDate);
                                            $diffInMonths = (int) $assetDate->diffInMonths($nowDate);
                                            $diffInYears = (int) $assetDate->diffInYears($nowDate);
                                        @endphp
                                        @if ($diffInYears >= 1)
                                            <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                                {{ $diffInYears }} {{ $diffInYears == 1 ? 'year' : 'years' }}
                                            </span>
                                        @elseif ($diffInMonths >= 6)
                                            <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-orange-100 text-orange-800">
                                                {{ $diffInMonths }} {{ $diffInMonths == 1 ? 'month' : 'months' }}
                                            </span>
                                        @elseif ($diffInMonths >= 1)
                                            <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                                {{ $diffInMonths }} {{ $diffInMonths == 1 ? 'month' : 'months' }}
                                            </span>
                                        @elseif ($diffInDays >= 30)
                                            <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                                {{ $diffInDays }} {{ $diffInDays == 1 ? 'day' : 'days' }}
                                            </span>
                                        @else
                                            <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                {{ $diffInDays }} {{ $diffInDays == 1 ? 'day' : 'days' }}
                                            </span>
                                        @endif
                                    @else
                                        <span class="text-gray-400">-</span>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center space-x-2">
                                    <a href="{{ route('asset-management.show', $asset->id) }}" class="inline-flex items-center px-3 py-1.5 bg-blue-100 text-blue-600 hover:bg-blue-200 text-sm font-medium rounded-lg transition-colors duration-200" title="View Details">
                                        <i class='bx bx-show mr-1'></i>
                                        View
                                    </a>
                                    <a href="{{ route('asset-management.edit', $asset->id) }}" class="inline-flex items-center px-3 py-1.5 bg-green-100 text-green-600 hover:bg-green-200 text-sm font-medium rounded-lg transition-colors duration-200" title="Edit Asset">
                                        <i class='bx bx-edit mr-1'></i>
                                        Edit
                                    </a>
                                    @if($asset->status != 'Under Maintenance')
                                        <button onclick="openMaintenanceModal({{ $asset->id }}, '{{ $asset->item_name }}')" class="inline-flex items-center px-3 py-1.5 bg-yellow-100 text-yellow-600 hover:bg-yellow-200 text-sm font-medium rounded-lg transition-colors duration-200" title="Set Maintenance">
                                            <i class='bx bx-wrench mr-1'></i>
                                            Maintenance
                                        </button>
                                    @endif
                                    <button onclick="openDisposalModal({{ $asset->id }}, '{{ $asset->item_name }}')" class="inline-flex items-center px-3 py-1.5 bg-red-100 text-red-600 hover:bg-red-200 text-sm font-medium rounded-lg transition-colors duration-200" title="Dispose">
                                        <i class='bx bx-trash mr-1'></i>
                                        Disposal
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-6 py-8 text-center text-gray-500">
                                <div class="flex flex-col items-center">
                                    <i class='bx bx-package text-4xl text-gray-300 mb-3'></i>
                                    <p class="text-lg font-medium">No assets found</p>
                                    <p class="text-sm">Get started by adding your first asset.</p>
                                    <a href="{{ route('asset-management.create') }}" class="mt-4 bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg inline-flex items-center gap-2 transition-colors">
                                        <i class='bx bx-plus'></i>
                                        Add First Asset
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
                {{ $assets->links() }}
            </div>
            <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                <div>
                    <p class="text-sm text-gray-700">
                        Showing <span class="font-medium">{{ $assets->firstItem() ?? 0 }}</span> to <span class="font-medium">{{ $assets->lastItem() ?? 0 }}</span> of{' '}
                        <span class="font-medium">{{ $assets->total() }}</span> results
                    </p>
                </div>
                <div>
                    {{ $assets->links() }}
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Enhanced Asset Request Modal -->
<div id="requestModal" class="fixed inset-0 bg-gray-900 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-0 border shadow-2xl rounded-2xl bg-white max-w-md w-full">
        <div class="bg-gradient-to-r from-purple-600 to-purple-700 rounded-t-2xl p-6 text-white">
            <h3 class="text-xl font-bold flex items-center gap-3">
                <div class="bg-purple-400 bg-opacity-30 rounded-lg p-2">
                    <i class='bx bx-send text-xl'></i>
                </div>
                Request Asset
            </h3>
            <p class="text-purple-100 text-sm mt-2">Send asset request to warehouse</p>
        </div>
        <form action="{{ route('asset-management.request-asset') }}" method="POST" class="p-6">
            @csrf
            <input type="hidden" id="requestAssetId" name="asset_id">
            
            <div class="space-y-4">
                <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Asset Details</label>
                    <div class="space-y-2">
                        <div>
                            <span class="text-xs text-gray-500">Asset Name</span>
                            <input type="text" id="requestAssetName" readonly class="w-full px-3 py-2 border border-gray-300 rounded-lg bg-white font-medium text-gray-900">
                        </div>
                        <div>
                            <span class="text-xs text-gray-500">Asset Code</span>
                            <input type="text" id="requestAssetCode" readonly class="w-full px-3 py-2 border border-gray-300 rounded-lg bg-white font-medium text-gray-900">
                        </div>
                    </div>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Request Reason *</label>
                    <textarea name="request_reason" required rows="3" 
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent resize-none"
                        placeholder="Why do you need this asset?"></textarea>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Department *</label>
                    <input type="text" name="department" required 
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                        placeholder="Your department">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Urgency Level *</label>
                    <select name="urgency" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                        <option value="">Select urgency</option>
                        <option value="Low">🟢 Low - Can wait</option>
                        <option value="Medium">🟡 Medium - Soon</option>
                        <option value="High">🔴 High - Urgent</option>
                    </select>
                </div>
            </div>
            
            <div class="flex justify-end space-x-3 mt-6">
                <button type="button" onclick="closeRequestModal()" class="px-6 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors duration-200 font-medium">
                    Cancel
                </button>
                <button type="submit" class="px-6 py-2 bg-gradient-to-r from-purple-600 to-purple-700 text-white rounded-lg hover:from-purple-700 hover:to-purple-800 transition-all duration-200 font-medium shadow-lg">
                    <i class='bx bx-send mr-2'></i>Send Request
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Enhanced Asset Disposal Modal -->
<div id="disposalModal" class="fixed inset-0 bg-gray-900 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-0 border shadow-2xl rounded-2xl bg-white max-w-md w-full">
        <div class="bg-gradient-to-r from-orange-600 to-red-600 rounded-t-2xl p-6 text-white">
            <h3 class="text-xl font-bold flex items-center gap-3">
                <div class="bg-orange-400 bg-opacity-30 rounded-lg p-2">
                    <i class='bx bx-trash text-xl'></i>
                </div>
                Dispose Asset
            </h3>
            <p class="text-orange-100 text-sm mt-2">Permanently dispose this asset</p>
        </div>
        <form method="POST" id="disposalForm" class="p-6">
            @csrf
            <input type="hidden" id="disposalAssetId">
            
            <div class="space-y-4">
                <div class="bg-red-50 border border-red-200 rounded-lg p-4">
                    <div class="flex items-center gap-2 text-red-700 mb-2">
                        <i class='bx bx-error-circle'></i>
                        <span class="font-medium">Warning</span>
                    </div>
                    <p class="text-sm text-red-600">This action cannot be undone. The asset will be marked as disposed and will no longer be available for use.</p>
                </div>
                
                <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Asset to Dispose</label>
                    <input type="text" id="disposalAssetName" readonly class="w-full px-3 py-2 border border-gray-300 rounded-lg bg-white font-medium text-gray-900">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Disposal Reason *</label>
                    <textarea name="disposal_reason" required rows="3" 
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent resize-none"
                        placeholder="Why is this asset being disposed?"></textarea>
                </div>
            </div>
            
            <div class="flex justify-end space-x-3 mt-6">
                <button type="button" onclick="closeDisposalModal()" class="px-6 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors duration-200 font-medium">
                    Cancel
                </button>
                <button type="submit" class="px-6 py-2 bg-gradient-to-r from-orange-600 to-red-600 text-white rounded-lg hover:from-orange-700 hover:to-red-700 transition-all duration-200 font-medium shadow-lg">
                    <i class='bx bx-trash mr-2'></i>Dispose Asset
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Enhanced Asset Maintenance Modal -->
<div id="maintenanceModal" class="fixed inset-0 bg-gray-900 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-lg bg-white">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                <div class="bg-yellow-100 rounded-lg p-2">
                    <i class='bx bx-wrench text-yellow-600 text-xl'></i>
                </div>
                Set Asset Maintenance
            </h3>
            <button type="button" onclick="closeMaintenanceModal()" class="text-gray-400 hover:text-gray-600">
                <i class='bx bx-x text-xl'></i>
            </button>
        </div>
        
        <form id="maintenanceForm" method="POST" action="" onsubmit="return true;">
            @csrf
            @method('PATCH')
            
            <input type="hidden" id="maintenanceAssetId" name="asset_id" value="">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Asset Name</label>
                <input type="text" id="maintenanceAssetName" readonly class="w-full px-3 py-2 bg-gray-100 border border-gray-300 rounded-lg text-gray-700">
            </div>
            
            <div class="mb-4">
                <label for="maintenance_description" class="block text-sm font-medium text-gray-700 mb-2">Maintenance Description *</label>
                <textarea id="maintenance_description" name="maintenance_description" rows="4" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent resize-none" placeholder="Describe the maintenance work needed..." required></textarea>
            </div>
            
            <div class="mb-4">
                <label for="maintenance_date" class="block text-sm font-medium text-gray-700 mb-2">Maintenance Date *</label>
                <input type="date" id="maintenance_date" name="maintenance_date" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" required>
            </div>
            
            <div class="flex justify-end space-x-3 mt-6">
                <button type="button" onclick="closeMaintenanceModal()" class="px-6 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors duration-200 font-medium">
                    Cancel
                </button>
                <button type="submit" class="px-6 py-2 bg-gradient-to-r from-yellow-500 to-orange-500 text-white rounded-lg hover:from-yellow-600 hover:to-orange-600 transition-all duration-200 font-medium shadow-lg">
                    <i class='bx bx-wrench mr-2'></i>Set Maintenance
                </button>
            </div>
        </form>
    </div>
</div>


<script>
function openRequestModal(assetId, assetName, assetCode) {
    document.getElementById('requestAssetId').value = assetId;
    document.getElementById('requestAssetName').value = assetName;
    document.getElementById('requestAssetCode').value = assetCode;
    document.getElementById('requestModal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function closeRequestModal() {
    document.getElementById('requestModal').classList.add('hidden');
    document.body.style.overflow = 'auto';
}

function openDisposalModal(assetId, assetName) {
    document.getElementById('disposalAssetId').value = assetId;
    document.getElementById('disposalAssetName').value = assetName;
    document.getElementById('disposalForm').action = '/asset-management/' + assetId + '/dispose';
    document.getElementById('disposalModal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function closeDisposalModal() {
    document.getElementById('disposalModal').classList.add('hidden');
    document.body.style.overflow = 'auto';
}

function openMaintenanceModal(assetId, assetName) {
    document.getElementById('maintenanceAssetId').value = assetId;
    document.getElementById('maintenanceAssetName').value = assetName;
    document.getElementById('maintenanceForm').action = '/asset-management/' + assetId + '/maintenance';
    document.getElementById('maintenanceModal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function closeMaintenanceModal() {
    document.getElementById('maintenanceModal').classList.add('hidden');
    document.body.style.overflow = 'auto';
    // Reset form
    document.getElementById('maintenanceForm').reset();
}


// Close modals on ESC key
document.addEventListener('keydown', function(event) {
    if (event.key === 'Escape') {
        closeRequestModal();
        closeDisposalModal();
        closeMaintenanceModal();
    }
});
</script>

@endsection
