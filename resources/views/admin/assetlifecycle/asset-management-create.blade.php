@extends('layouts.app')

@section('content')
<div class="container-fluid px-6 py-8">
    <!-- Enhanced Header -->
    <div class="mb-8">
        <div class="flex justify-between items-center mb-4">
            <div>
                <h1 class="text-4xl font-bold text-gray-900 mb-2">Add New Asset</h1>
                <p class="text-gray-600 text-lg">Register a new asset in the system</p>
            </div>
            <a href="{{ route('admin.assetlifecycle.asset-management') }}" class="bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium py-2 px-4 rounded-lg flex items-center gap-2 transition-all duration-200 shadow-sm">
                <i class='bx bx-arrow-back text-xl'></i>
                Back to Assets
            </a>
        </div>
    </div>

    <!-- Enhanced Form Container -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="bg-gradient-to-r from-blue-600 to-blue-700 px-6 py-4 text-white">
            <h2 class="text-xl font-semibold flex items-center gap-3">
                <div class="bg-blue-400 bg-opacity-30 rounded-lg p-2">
                    <i class='bx bx-plus text-xl'></i>
                </div>
                Asset Information
            </h2>
        </div>
        
        <form method="POST" action="{{ route('asset-management.store') }}" enctype="multipart/form-data" class="p-6">
            @csrf
            
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
                <!-- Item Name -->
                <div>
                    <label for="item_name" class="block text-sm font-medium text-gray-700 mb-2">Asset Name *</label>
                    <input type="text" 
                           id="item_name" 
                           name="item_name" 
                           value="{{ old('item_name') }}"
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
                           value="{{ old('quantity') ?? 1 }}"
                           min="1"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                           placeholder="e.g., 1"
                           required>
                    <p class="mt-1 text-sm text-gray-500">Number of assets</p>
                </div>

                <!-- Department -->
                <div>
                    <label for="department" class="block text-sm font-medium text-gray-700 mb-2">Department *</label>
                    <input type="text" 
                           id="department" 
                           name="department" 
                           value="{{ old('department') }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                           placeholder="e.g., IT, HR, Finance"
                           required>
                    <p class="mt-1 text-sm text-gray-500">Department responsible for the asset</p>
                </div>

                <!-- Date -->
                <div>
                    <label for="date" class="block text-sm font-medium text-gray-700 mb-2">Date *</label>
                    <input type="date" 
                           id="date" 
                           name="date" 
                           value="{{ old('date') ?? now()->format('Y-m-d') }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                           required>
                    <p class="mt-1 text-sm text-gray-500">Asset acquisition or creation date</p>
                </div>

                <!-- Details -->
                <div class="md:col-span-2">
                    <label for="details" class="block text-sm font-medium text-gray-700 mb-2">Details</label>
                    <textarea id="details" 
                              name="details" 
                              rows="3"
                              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent resize-none"
                              placeholder="Enter additional details about the asset...">{{ old('details') }}</textarea>
                    <p class="mt-1 text-sm text-gray-500">Additional information about the asset (optional)</p>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="flex justify-end space-x-3 mt-8 pt-6 border-t border-gray-200">
                <a href="{{ route('admin.assetlifecycle.asset-management') }}" class="px-6 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors duration-200 font-medium">
                    <i class='bx bx-x mr-2'></i>Cancel
                </a>
                <button type="submit" class="px-6 py-2 bg-gradient-to-r from-blue-600 to-blue-700 text-white rounded-lg hover:from-blue-700 hover:to-blue-800 transition-all duration-200 font-medium shadow-lg">
                    <i class='bx bx-save mr-2'></i>Save Asset
                </button>
            </div>
        </form>
    </div>
</div>

<script>
// Simple form validation
function validateForm() {
    const itemName = document.getElementById('item_name').value.trim();
    const quantity = document.getElementById('quantity').value;
    const department = document.getElementById('department').value;
    const date = document.getElementById('date').value;
    
    if (!itemName) {
        alert('Please enter an asset name');
        return false;
    }
    
    if (!quantity || quantity < 1) {
        alert('Please enter a valid quantity');
        return false;
    }
    
    if (!department) {
        alert('Please select a department');
        return false;
    }
    
    if (!date) {
        alert('Please select a date');
        return false;
    }
    
    return true;
}

// Add form validation on submit
document.querySelector('form').addEventListener('submit', function(e) {
    if (!validateForm()) {
        e.preventDefault();
    }
});
</script>

@endsection
