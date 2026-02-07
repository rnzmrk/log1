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

    <!-- Enhanced Form Container -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="bg-gradient-to-r from-blue-600 to-blue-700 px-6 py-4 text-white">
            <h2 class="text-xl font-semibold flex items-center gap-3">
                <div class="bg-blue-400 bg-opacity-30 rounded-lg p-2">
                    <i class='bx bx-edit text-xl'></i>
                </div>
                Edit Asset Information
            </h2>
        </div>
        
        <form method="POST" action="{{ route('asset-management.update', $asset->id) }}" enctype="multipart/form-data" class="p-6">
            @csrf
            @method('PUT')
    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
        <div class="flex items-center">
            <i class='bx bx-package text-blue-600 text-xl mr-3'></i>
            <div>
                <h3 class="text-sm font-medium text-blue-900">Asset Summary</h3>
                <p class="text-sm text-blue-700">Asset Tag: <span class="font-semibold">{{ $asset->asset_tag }}</span> | Created: {{ $asset->created_at->format('M d, Y') }}</p>
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
                <!-- Item Code -->
                <div>
                    <label for="item_code" class="block text-sm font-medium text-gray-700 mb-2">Item Code *</label>
                    <input type="text" 
                           id="item_code" 
                           name="item_code" 
                           value="{{ old('item_code', $asset->item_code) }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                           placeholder="e.g., LAP-DELL-001"
                           required>
                    <p class="mt-1 text-sm text-gray-500">Asset code for tracking</p>
                </div>

                <!-- Item Name -->
                <div>
                    <label for="item_name" class="block text-sm font-medium text-gray-700 mb-2">Item Name *</label>
                    <input type="text" 
                           id="item_name" 
                           name="item_name" 
                           value="{{ old('item_name', $asset->item_name) }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                           placeholder="e.g., Dell XPS 15 Laptop"
                           required>
                    <p class="mt-1 text-sm text-gray-500">Descriptive name of the asset</p>
                </div>

                <!-- Asset Type -->
                <div>
                    <label for="asset_type" class="block text-sm font-medium text-gray-700 mb-2">Asset Type *</label>
                    <select id="asset_type" 
                            name="asset_type" 
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            required>
                        <option value="">Select type</option>
                        <option value="Computer" {{ old('asset_type', $asset->asset_type) === 'Computer' ? 'selected' : '' }}>Computer</option>
                        <option value="Laptop" {{ old('asset_type', $asset->asset_type) === 'Laptop' ? 'selected' : '' }}>Laptop</option>
                        <option value="Desktop" {{ old('asset_type', $asset->asset_type) === 'Desktop' ? 'selected' : '' }}>Desktop</option>
                        <option value="Monitor" {{ old('asset_type', $asset->asset_type) === 'Monitor' ? 'selected' : '' }}>Monitor</option>
                        <option value="Phone" {{ old('asset_type', $asset->asset_type) === 'Phone' ? 'selected' : '' }}>Phone</option>
                        <option value="Tablet" {{ old('asset_type', $asset->asset_type) === 'Tablet' ? 'selected' : '' }}>Tablet</option>
                        <option value="Printer" {{ old('asset_type', $asset->asset_type) === 'Printer' ? 'selected' : '' }}>Printer</option>
                        <option value="Server" {{ old('asset_type', $asset->asset_type) === 'Server' ? 'selected' : '' }}>Server</option>
                        <option value="Vehicle" {{ old('asset_type', $asset->asset_type) === 'Vehicle' ? 'selected' : '' }}>Vehicle</option>
                        <option value="Equipment" {{ old('asset_type', $asset->asset_type) === 'Equipment' ? 'selected' : '' }}>Equipment</option>
                        <option value="Furniture" {{ old('asset_type', $asset->asset_type) === 'Furniture' ? 'selected' : '' }}>Furniture</option>
                        <option value="Other" {{ old('asset_type', $asset->asset_type) === 'Other' ? 'selected' : '' }}>Other</option>
                    </select>
                    <p class="mt-1 text-sm text-gray-500">Category of the asset</p>
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

                <!-- Image Upload -->
                <div class="md:col-span-2">
                    <label for="image" class="block text-sm font-medium text-gray-700 mb-2">Asset Image</label>
                    <div class="flex items-center space-x-4">
                        <div class="flex-shrink-0">
                            @if($asset->image)
                                <img id="imagePreview" src="{{ asset('storage/assets/' . $asset->image) }}" alt="Asset preview" class="h-20 w-20 object-cover rounded-lg border border-gray-300">
                            @else
                                <img id="imagePreview" src="{{ asset('images/no-image.png') }}" alt="Asset preview" class="h-20 w-20 object-cover rounded-lg border border-gray-300">
                            @endif
                        </div>
                        <div class="flex-1">
                            <input type="file" 
                                   id="image" 
                                   name="image" 
                                   accept="image/*"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                   onchange="previewImage(event)">
                            <p class="mt-1 text-sm text-gray-500">Optional: Upload new asset image (JPG, PNG, GIF - Max 2MB)</p>
                        </div>
                    </div>
                </div>

            <!-- Form Actions -->
            <div class="flex justify-end space-x-3 mt-8 pt-6 border-t border-gray-200">
                <a href="{{ route('admin.assetlifecycle.asset-management') }}" class="px-6 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors duration-200 font-medium">
                    <i class='bx bx-x mr-2'></i>Cancel
                </a>
                <button type="submit" class="px-6 py-2 bg-gradient-to-r from-blue-600 to-blue-700 text-white rounded-lg hover:from-blue-700 hover:to-blue-800 transition-all duration-200 font-medium shadow-lg">
                    <i class='bx bx-save mr-2'></i>Update Asset
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function previewImage(event) {
    const file = event.target.files[0];
    const preview = document.getElementById('imagePreview');
    
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            preview.src = e.target.result;
        }
        reader.readAsDataURL(file);
    }
    // Don't reset to default if no file selected, keep existing image
}
</script>

@endsection
                                           
