@extends('layouts.app')

@section('content')
<div class="container-fluid px-6 py-8">
    <!-- Enhanced Header -->
    <div class="mb-8">
        <div class="flex justify-between items-center mb-4">
            <div>
                <h1 class="text-4xl font-bold text-gray-900 mb-2">Edit Asset</h1>
                <p class="text-gray-600 text-lg">Update asset information</p>
            </div>
            <div class="flex gap-3">
                <a href="{{ route('asset-management.show', $asset->id) }}" class="bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium py-2 px-4 rounded-lg flex items-center gap-2 transition-all duration-200 shadow-sm">
                    <i class='bx bx-show text-xl'></i>
                    View Asset
                </a>
                <a href="{{ route('admin.assetlifecycle.asset-management') }}" class="bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium py-2 px-4 rounded-lg flex items-center gap-2 transition-all duration-200 shadow-sm">
                    <i class='bx bx-arrow-back text-xl'></i>
                    Back to Assets
                </a>
            </div>
        </div>
    </div>

    <!-- Form Container -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-lg font-semibold text-gray-900">Edit Asset Information</h2>
        </div>
        
        <form method="POST" action="{{ route('asset-management.update', $asset->id) }}" class="p-6">
            @csrf
            @method('PUT')
            
            <!-- Error Messages -->
            @if ($errors->any())
                <div class="mb-4 bg-red-50 border border-red-200 rounded-md p-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <i class='bx bx-error text-red-400 text-xl'></i>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-red-800">There were errors with your submission:</h3>
                            <div class="mt-2 text-sm text-red-700">
                                <ul class="list-disc list-inside space-y-1">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Asset Name -->
                <div>
                    <label for="item_name" class="block text-sm font-medium text-gray-700 mb-2">Asset Name *</label>
                    <input type="text" 
                           id="item_name" 
                           name="item_name" 
                           value="{{ old('item_name', $asset->item_name) }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                           placeholder="e.g., Dell XPS 15 Laptop"
                           required>
                    <p class="mt-1 text-sm text-gray-500">Descriptive name of the asset</p>
                </div>

                <!-- Quantity -->
                <div>
                    <label for="quantity" class="block text-sm font-medium text-gray-700 mb-2">Quantity *</label>
                    <input type="number" 
                           id="quantity" 
                           name="quantity" 
                           value="{{ old('quantity', $asset->quantity ?? 1) }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                           placeholder="1"
                           min="1"
                           required>
                    <p class="mt-1 text-sm text-gray-500">Number of assets</p>
                </div>

                <!-- Department -->
                <div>
                    <label for="department" class="block text-sm font-medium text-gray-700 mb-2">Department *</label>
                    <input type="text" 
                           id="department" 
                           name="department" 
                           value="{{ old('department', $asset->department) }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                           placeholder="e.g., IT, HR, Finance"
                           required>
                    <p class="mt-1 text-sm text-gray-500">Department responsible for the asset</p>
                </div>

                <!-- Status -->
                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Status *</label>
                    <select id="status" 
                            name="status" 
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            required>
                        <option value="">Select status</option>
                        <option value="Available" {{ old('status', $asset->status) === 'Available' ? 'selected' : '' }}>Available</option>
                        <option value="In Use" {{ old('status', $asset->status) === 'In Use' ? 'selected' : '' }}>In Use</option>
                        <option value="Under Maintenance" {{ old('status', $asset->status) === 'Under Maintenance' ? 'selected' : '' }}>Under Maintenance</option>
                        <option value="Disposed" {{ old('status', $asset->status) === 'Disposed' ? 'selected' : '' }}>Disposed</option>
                        <option value="Requested" {{ old('status', $asset->status) === 'Requested' ? 'selected' : '' }}>Requested</option>
                    </select>
                    <p class="mt-1 text-sm text-gray-500">Current status of the asset</p>
                </div>

                <!-- Date -->
                <div>
                    <label for="date" class="block text-sm font-medium text-gray-700 mb-2">Date *</label>
                    <input type="date" 
                           id="date" 
                           name="date" 
                           value="{{ old('date', $asset->date?->format('Y-m-d')) }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                           required>
                    <p class="mt-1 text-sm text-gray-500">Asset acquisition or creation date</p>
                </div>
            </div>

            <!-- Details -->
            <div class="mt-6">
                <label for="details" class="block text-sm font-medium text-gray-700 mb-2">Details</label>
                <textarea id="details" 
                          name="details" 
                          rows="4"
                          class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent resize-none"
                          placeholder="Enter additional details about the asset...">{{ old('details', $asset->details) }}</textarea>
                <p class="mt-1 text-sm text-gray-500">Additional information about the asset (optional)</p>
            </div>

            <!-- Form Actions -->
            <div class="flex justify-end space-x-3 mt-8 pt-6 border-t border-gray-200">
                <a href="{{ route('admin.assetlifecycle.asset-management') }}" class="px-6 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors duration-200 font-medium">
                    <i class='bx bx-x mr-2'></i>Cancel
                </a>
                <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors duration-200 font-medium">
                    <i class='bx bx-save mr-2'></i>Update Asset
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
                                           
