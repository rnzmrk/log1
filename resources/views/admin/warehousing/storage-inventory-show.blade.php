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
                    <a href="#" class="text-gray-700 hover:text-blue-600 ml-1 md:ml-2">Smart Warehousing</a>
                </div>
            </li>
            <li>
                <div class="flex items-center">
                    <i class='bx bx-chevron-right text-gray-400'></i>
                    <a href="{{ route('admin.warehousing.storage-inventory') }}" class="text-gray-700 hover:text-blue-600 ml-1 md:ml-2">Storage & Inventory</a>
                </div>
            </li>
            <li aria-current="page">
                <div class="flex items-center">
                    <i class='bx bx-chevron-right text-gray-400'></i>
                    <span class="ml-1 text-gray-500 md:ml-2">View Inventory Item</span>
                </div>
            </li>
        </ol>
    </nav>

    <!-- Page Header -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">{{ $inventory->item_name }}</h1>
            <p class="text-gray-600 mt-1">SKU: {{ $inventory->sku }}</p>
        </div>
        <div class="flex gap-3">
            <a href="{{ route('inventory.edit', $inventory->id) }}" class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg flex items-center gap-2 transition-colors">
                <i class='bx bx-edit text-xl'></i>
                Edit
            </a>
            <a href="{{ route('admin.warehousing.storage-inventory') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-medium py-2 px-4 rounded-lg flex items-center gap-2 transition-colors">
                <i class='bx bx-arrow-back text-xl'></i>
                Back to Inventory
            </a>
        </div>
    </div>

    <!-- Success Message -->
    @if (session('success'))
        <div class="mb-6 bg-green-50 border border-green-200 rounded-md p-4">
            <div class="flex">
                <div class="flex-shrink-0">
                    <i class='bx bx-check text-green-400 text-xl'></i>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-green-800">{{ session('success') }}</p>
                </div>
            </div>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Information -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Item Details Card -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-900">Item Details</h2>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">SKU</label>
                            <p class="text-gray-900 font-medium">{{ $inventory->sku }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">Item Name</label>
                            <p class="text-gray-900 font-medium">{{ $inventory->item_name }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">Category</label>
                            <p class="text-gray-900">{{ $inventory->category }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">Location</label>
                            <p class="text-gray-900">{{ $inventory->location }}</p>
                        </div>
                        @if ($inventory->supplier)
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">Supplier</label>
                            <p class="text-gray-900">{{ $inventory->supplier }}</p>
                        </div>
                        @endif
                        @if ($inventory->price)
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">Unit Price</label>
                            <p class="text-gray-900">${{ number_format($inventory->price, 2) }}</p>
                        </div>
                        @endif
                    </div>
                    
                    @if ($inventory->description)
                    <div class="mt-6">
                        <label class="block text-sm font-medium text-gray-500 mb-1">Description</label>
                        <p class="text-gray-900">{{ $inventory->description }}</p>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Stock Information Card -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-900">Stock Information</h2>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div class="text-center">
                            <div class="text-3xl font-bold text-gray-900">{{ $inventory->stock }}</div>
                            <div class="text-sm text-gray-500 mt-1">Current Stock</div>
                        </div>
                        <div class="text-center">
                            @if ($inventory->status === 'In Stock')
                                <span class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                    In Stock
                                </span>
                            @elseif ($inventory->status === 'Low Stock')
                                <span class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                    Low Stock
                                </span>
                            @else
                                <span class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                    Out of Stock
                                </span>
                            @endif
                            <div class="text-sm text-gray-500 mt-1">Status</div>
                        </div>
                        <div class="text-center">
                            <div class="text-lg font-medium text-gray-900">
                                {{ $inventory->last_updated ? $inventory->last_updated->format('M d, Y') : 'Never' }}
                            </div>
                            <div class="text-sm text-gray-500 mt-1">Last Updated</div>
                        </div>
                    </div>
                    
                    <!-- Stock Status Indicator -->
                    <div class="mt-6">
                        <div class="flex justify-between text-sm text-gray-600 mb-2">
                            <span>Stock Level</span>
                            <span>{{ $inventory->stock }} units</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-3">
                            @if ($inventory->stock == 0)
                                <div class="bg-red-500 h-3 rounded-full" style="width: 0%"></div>
                            @elseif ($inventory->stock <= 10)
                                <div class="bg-yellow-500 h-3 rounded-full" style="width: 25%"></div>
                            @else
                                <div class="bg-green-500 h-3 rounded-full" style="width: 100%"></div>
                            @endif
                        </div>
                        <div class="mt-2 text-xs text-gray-500">
                            @if ($inventory->stock == 0)
                                Out of Stock - Order needed
                            @elseif ($inventory->stock <= 10)
                                Low Stock - Consider reordering
                            @else
                                Good Stock Level
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Actions Sidebar -->
        <div class="space-y-6">
            <!-- Quick Actions -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-900">Quick Actions</h2>
                </div>
                <div class="p-6 space-y-3">
                    <a href="{{ route('inventory.edit', $inventory->id) }}" class="w-full text-left px-4 py-3 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors flex items-center gap-3">
                        <i class='bx bx-edit text-blue-600 text-xl'></i>
                        <div>
                            <p class="font-medium text-gray-900">Edit Item</p>
                            <p class="text-sm text-gray-600">Update item details</p>
                        </div>
                    </a>
                    
                    @if ($inventory->stock > 0)
                    <button onclick="openMoveModal({{ $inventory->id }}, '{{ $inventory->item_name }}', {{ $inventory->stock }})" class="w-full text-left px-4 py-3 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors flex items-center gap-3">
                        <i class='bx bx-transfer text-orange-600 text-xl'></i>
                        <div>
                            <p class="font-medium text-gray-900">Move Stock</p>
                            <p class="text-sm text-gray-600">Transfer to new location</p>
                        </div>
                    </button>
                    @endif
                    
                    <form action="{{ route('inventory.destroy', $inventory->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this item? This action cannot be undone.')" class="w-full">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="w-full text-left px-4 py-3 border border-red-200 rounded-lg hover:bg-red-50 transition-colors flex items-center gap-3">
                            <i class='bx bx-trash text-red-600 text-xl'></i>
                            <div>
                                <p class="font-medium text-red-900">Delete Item</p>
                                <p class="text-sm text-red-600">Remove from inventory</p>
                            </div>
                        </button>
                    </form>
                </div>
            </div>

            <!-- System Information -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-900">System Information</h2>
                </div>
                <div class="p-6 space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-500 mb-1">Created At</label>
                        <p class="text-gray-900">{{ $inventory->created_at->format('M d, Y H:i') }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500 mb-1">Last Updated</label>
                        <p class="text-gray-900">{{ $inventory->updated_at->format('M d, Y H:i') }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500 mb-1">ID</label>
                        <p class="text-gray-900 font-mono text-sm">#{{ $inventory->id }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Move Modal -->
<div id="moveModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Move Inventory Item</h3>
            <form id="moveForm" method="POST">
                @csrf
                <input type="hidden" id="moveItemId" name="item_id">
                
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Item</label>
                    <input type="text" id="moveItemName" class="w-full px-3 py-2 border border-gray-300 rounded-lg bg-gray-50" readonly>
                </div>
                
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Available Stock</label>
                    <input type="text" id="moveAvailableStock" class="w-full px-3 py-2 border border-gray-300 rounded-lg bg-gray-50" readonly>
                </div>
                
                <div class="mb-4">
                    <label for="new_location" class="block text-sm font-medium text-gray-700 mb-2">New Location *</label>
                    <input type="text" 
                           id="new_location" 
                           name="new_location" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                           placeholder="e.g., Warehouse A - A2-15"
                           required>
                </div>
                
                <div class="mb-4">
                    <label for="move_quantity" class="block text-sm font-medium text-gray-700 mb-2">Quantity to Move *</label>
                    <input type="number" 
                           id="move_quantity" 
                           name="move_quantity" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                           placeholder="Enter quantity"
                           min="1"
                           required>
                </div>
                
                <div class="flex justify-end gap-3">
                    <button type="button" onclick="closeMoveModal()" class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-medium py-2 px-4 rounded-lg transition-colors">
                        Cancel
                    </button>
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg transition-colors">
                        Move Item
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function openMoveModal(itemId, itemName, availableStock) {
    document.getElementById('moveItemId').value = itemId;
    document.getElementById('moveItemName').value = itemName;
    document.getElementById('moveAvailableStock').value = availableStock;
    document.getElementById('move_quantity').max = availableStock;
    document.getElementById('moveModal').classList.remove('hidden');
}

function closeMoveModal() {
    document.getElementById('moveModal').classList.add('hidden');
    document.getElementById('moveForm').reset();
}

// Handle move form submission
document.getElementById('moveForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const itemId = document.getElementById('moveItemId').value;
    const formData = new FormData(this);
    
    fetch(`/inventory/${itemId}/move`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Accept': 'application/json',
        },
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            closeMoveModal();
            window.location.reload();
        } else {
            alert('Error: ' + (data.message || 'Failed to move item'));
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred while moving the item');
    });
});
</script>
@endsection
