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
                    <a href="{{ route('admin.assetlifecycle.asset-maintenance') }}" class="text-gray-700 hover:text-blue-600 ml-1 md:ml-2">Asset Maintenance</a>
                </div>
            </li>
            <li aria-current="page">
                <div class="flex items-center">
                    <i class='bx bx-chevron-right text-gray-400'></i>
                    <span class="ml-1 text-gray-500 md:ml-2">Edit Maintenance</span>
                </div>
            </li>
        </ol>
    </nav>

    <!-- Page Header -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Edit Maintenance</h1>
            <p class="text-gray-600 mt-1">Update maintenance record details</p>
        </div>
        <div class="flex gap-3">
            <a href="{{ route('admin.assetlifecycle.asset-maintenance') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-medium py-2 px-4 rounded-lg flex items-center gap-2 transition-colors">
                <i class='bx bx-arrow-back text-xl'></i>
                Back to Maintenance
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

    <!-- Error Messages -->
    @if($errors->any())
        <div class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg mb-6">
            <div class="flex items-center mb-2">
                <i class='bx bx-error-circle text-xl mr-2'></i>
                <span class="font-medium">Please fix the following errors:</span>
            </div>
            <ul class="list-disc list-inside text-sm">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Edit Form -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-lg font-semibold text-gray-900">Maintenance Details</h2>
            <p class="text-sm text-gray-600 mt-1">Update the maintenance record information</p>
        </div>

        <form method="POST" action="{{ route('asset-maintenance.update', $maintenance->id) }}" class="p-6">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Maintenance Number -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Maintenance ID</label>
                    <input type="text" value="{{ $maintenance->maintenance_number }}" readonly class="w-full px-3 py-2 bg-gray-100 border border-gray-300 rounded-lg text-gray-700">
                </div>

                <!-- Asset Name -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Asset Name</label>
                    <input type="text" value="{{ $maintenance->asset_name }}" readonly class="w-full px-3 py-2 bg-gray-100 border border-gray-300 rounded-lg text-gray-700">
                </div>

                <!-- Status -->
                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                    <select id="status" name="status" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="Scheduled" {{ $maintenance->status === 'Scheduled' ? 'selected' : '' }}>pending</option>
                        <option value="In Progress" {{ $maintenance->status === 'In Progress' ? 'selected' : '' }}>ongoing</option>
                        <option value="Completed" {{ $maintenance->status === 'Completed' ? 'selected' : '' }}>done</option>
                        <option value="On Hold" {{ $maintenance->status === 'On Hold' ? 'selected' : '' }}>reject</option>
                        <option value="Cancelled" {{ $maintenance->status === 'Cancelled' ? 'selected' : '' }}>Cancelled</option>
                    </select>
                </div>

                <!-- Schedule Date -->
                <div>
                    <label for="scheduled_date" class="block text-sm font-medium text-gray-700 mb-2">Schedule Date</label>
                    <input type="date" id="scheduled_date" name="scheduled_date" value="{{ $maintenance->scheduled_date ? $maintenance->scheduled_date->format('Y-m-d') : '' }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>
            </div>

            <!-- Maintenance Description -->
            <div class="mt-6">
                <label for="problem_description" class="block text-sm font-medium text-gray-700 mb-2">Maintenance Description</label>
                <textarea id="problem_description" name="problem_description" rows="4" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent resize-none">{{ $maintenance->problem_description ?? '' }}</textarea>
            </div>

            <!-- Form Actions -->
            <div class="flex justify-end space-x-3 mt-8 pt-6 border-t border-gray-200">
                <a href="{{ route('admin.assetlifecycle.asset-maintenance') }}" class="px-6 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors duration-200 font-medium">
                    Cancel
                </a>
                <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors duration-200 font-medium">
                    <i class='bx bx-save mr-2'></i>
                    Update Maintenance
                </button>
            </div>
        </form>
    </div>
</div>

<script>
// Calculate total cost automatically
document.getElementById('parts_cost').addEventListener('input', calculateTotal);
document.getElementById('labor_cost').addEventListener('input', calculateTotal);

function calculateTotal() {
    const partsCost = parseFloat(document.getElementById('parts_cost').value) || 0;
    const laborCost = parseFloat(document.getElementById('labor_cost').value) || 0;
    const totalCost = partsCost + laborCost;
    document.getElementById('total_cost').value = totalCost.toFixed(2);
}
</script>
@endsection
