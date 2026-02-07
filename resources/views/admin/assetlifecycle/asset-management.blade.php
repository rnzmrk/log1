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
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">
                <!-- Search -->
                <div class="lg:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Search Assets</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class='bx bx-search text-gray-400'></i>
                        </div>
                        <input type="text" 
                               name="search"
                               value="{{ request('search') }}"
                               placeholder="Search by item number, code, name, or type..." 
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
                
                <!-- Asset Type Filter -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Asset Type</label>
                    <select name="asset_type" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="">All Types</option>
                        <option value="Computer" {{ request('asset_type') == 'Computer' ? 'selected' : '' }}>Computer</option>
                        <option value="Vehicle" {{ request('asset_type') == 'Vehicle' ? 'selected' : '' }}>Vehicle</option>
                        <option value="Equipment" {{ request('asset_type') == 'Equipment' ? 'selected' : '' }}>Equipment</option>
                        <option value="Furniture" {{ request('asset_type') == 'Furniture' ? 'selected' : '' }}>Furniture</option>
                    </select>
                </div>
                
                <!-- Department Filter -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Department</label>
                    <select name="department" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="">All Departments</option>
                        <option value="IT" {{ request('department') == 'IT' ? 'selected' : '' }}>IT</option>
                        <option value="HR" {{ request('department') == 'HR' ? 'selected' : '' }}>HR</option>
                        <option value="Finance" {{ request('department') == 'Finance' ? 'selected' : '' }}>Finance</option>
                        <option value="Operations" {{ request('department') == 'Operations' ? 'selected' : '' }}>Operations</option>
                    </select>
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
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Image</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Item Code</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Item Name</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse ($assets as $asset)
                        <tr class="hover:bg-gray-50 transition-colors duration-150">
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($asset->image)
                                    <img src="{{ asset('storage/assets/' . $asset->image) }}" alt="{{ $asset->item_name }}" class="h-14 w-14 rounded-xl object-cover shadow-sm hover:shadow-md transition-shadow duration-200 cursor-pointer" onclick="viewImage('{{ asset('storage/assets/' . $asset->image) }}')">
                                @else
                                    <div class="h-14 w-14 rounded-xl bg-gradient-to-br from-gray-100 to-gray-200 flex items-center justify-center shadow-sm">
                                        <i class='bx bx-image text-gray-400 text-2xl'></i>
                                    </div>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                    {{ $asset->item_code }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm font-medium text-gray-900">{{ $asset->item_name }}</div>
                                <div class="text-xs text-gray-500">{{ $asset->asset_type }}</div>
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
                                <div class="flex items-center space-x-2">
                                    <a href="{{ route('asset-management.show', $asset->id) }}" class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-blue-100 text-blue-600 hover:bg-blue-200 transition-colors duration-200" title="View Details">
                                        <i class='bx bx-show text-sm'></i>
                                    </a>
                                    <a href="{{ route('asset-management.edit', $asset->id) }}" class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-green-100 text-green-600 hover:bg-green-200 transition-colors duration-200" title="Edit Asset">
                                        <i class='bx bx-edit text-sm'></i>
                                    </a>
                                    @if($asset->status == 'Available')
                                        <button onclick="openRequestModal({{ $asset->id }}, '{{ $asset->item_name }}', '{{ $asset->item_code }}')" class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-purple-100 text-purple-600 hover:bg-purple-200 transition-colors duration-200" title="Request Asset">
                                            <i class='bx bx-send text-sm'></i>
                                        </button>
                                    @endif
                                    @if($asset->canBeDisposed())
                                        <button onclick="openDisposalModal({{ $asset->id }}, '{{ $asset->item_name }}')" class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-orange-100 text-orange-600 hover:bg-orange-200 transition-colors duration-200" title="Dispose Asset">
                                            <i class='bx bx-trash text-sm'></i>
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-8 text-center text-gray-500">
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

<!-- Image Viewer Modal -->
<div id="imageViewer" class="fixed inset-0 bg-gray-900 bg-opacity-90 overflow-y-auto h-full w-full hidden z-50" onclick="closeImageViewer()">
    <div class="flex items-center justify-center h-full p-4">
        <div class="relative max-w-4xl max-h-full">
            <button onclick="closeImageViewer()" class="absolute -top-12 right-0 text-white hover:text-gray-300 transition-colors">
                <i class='bx bx-x text-3xl'></i>
            </button>
            <img id="viewerImage" src="" alt="Asset Image" class="max-w-full max-h-full rounded-lg shadow-2xl">
        </div>
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

function viewImage(imageSrc) {
    document.getElementById('viewerImage').src = imageSrc;
    document.getElementById('imageViewer').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function closeImageViewer() {
    document.getElementById('imageViewer').classList.add('hidden');
    document.body.style.overflow = 'auto';
}

// Close modals on ESC key
document.addEventListener('keydown', function(event) {
    if (event.key === 'Escape') {
        closeRequestModal();
        closeDisposalModal();
        closeImageViewer();
    }
});
</script>

@endsection
