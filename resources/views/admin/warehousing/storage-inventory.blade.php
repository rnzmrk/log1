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
                    <a href="#" class="text-gray-700 hover:text-blue-600 ml-1 md:ml-2">Storage & Inventory</a>
                </div>
            </li>
            <li aria-current="page">
                <div class="flex items-center">
                    <i class='bx bx-chevron-right text-gray-400'></i>
                    <span class="ml-1 text-gray-500 md:ml-2">Inventory Management</span>
                </div>
            </li>
        </ol>
    </nav>

    <!-- Page Header -->
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-900">Storage & Inventory Management</h1>
        <div class="flex gap-3">
            <a href="{{ route('inventory.history') }}" class="bg-purple-600 hover:bg-purple-700 text-white font-medium py-2 px-4 rounded-lg flex items-center gap-2 transition-colors">
                <i class='bx bx-history text-xl'></i>
                History
            </a>
            <button onclick="openRequestModal()" class="bg-orange-600 hover:bg-orange-700 text-white font-medium py-2 px-4 rounded-lg flex items-center gap-2 transition-colors">
                <i class='bx bx-package text-xl'></i>
                Request Item
            </button>
            <a href="{{ route('inventory.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg flex items-center gap-2 transition-colors">
                <i class='bx bx-plus text-xl'></i>
                Add Item
            </a>
        </div>
    </div>

    <!-- Filter Section -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">
        <form method="GET" action="{{ route('admin.warehousing.storage-inventory') }}">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <!-- Search -->
                <div class="lg:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Search Items</label>
                    <div class="relative">
                        <input type="text" 
                               name="search"
                               value="{{ request('search') }}"
                               placeholder="Search by SKU, name, or location..." 
                               class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <i class='bx bx-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400 text-xl'></i>
                    </div>
                </div>

                <!-- Status -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Stock Status</label>
                    <select name="status" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="">All Status</option>
                        <option value="On Stock" {{ request('status') === 'On Stock' ? 'selected' : '' }}>On Stock</option>
                        <option value="Low Stock" {{ request('status') === 'Low Stock' ? 'selected' : '' }}>Low Stock</option>
                        <option value="Out of Stock" {{ request('status') === 'Out of Stock' ? 'selected' : '' }}>Out of Stock</option>
                    </select>
                </div>
            </div>

            <!-- Filter Buttons -->
            <div class="flex gap-3 mt-6">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-6 rounded-lg transition-colors">
                    <i class='bx bx-filter-alt mr-2'></i>
                    Apply Filters
                </button>
                <a href="{{ route('admin.warehousing.storage-inventory') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-medium py-2 px-6 rounded-lg transition-colors">
                    <i class='bx bx-x mr-2'></i>
                    Clear
                </a>
            </div>
        </form>
    </div>

    <!-- Inventory Table -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
            <h2 class="text-lg font-semibold text-gray-900">Inventory Items</h2>
            <div class="flex gap-2">
                <button onclick="window.location.reload()" class="text-gray-600 hover:text-gray-900 p-2 rounded-lg hover:bg-gray-100">
                    <i class='bx bx-refresh text-xl'></i>
                </button>
            </div>
        </div>
        <div class="overflow-x-auto">
            <table id="inventoryTable" class="w-full" data-export="excel">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">SKU</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">PO</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">DEPARTMENT</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">SUPPLIER</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ITEM NAME</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">STOCK</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">STATUS</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse ($inventories as $inventory)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $inventory->sku }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $inventory->po_number ?? '-' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $inventory->department ?? '-' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $inventory->supplier ?? '-' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $inventory->item_name }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <span class="text-sm font-medium text-gray-900">{{ $inventory->stock }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if ($inventory->status === 'On Stock')
                                    <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                        On Stock
                                    </span>
                                @elseif ($inventory->status === 'Low Stock')
                                    <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                        Low Stock
                                    </span>
                                @elseif ($inventory->status === 'Moved')
                                    <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                        Moved
                                    </span>
                                @elseif ($inventory->status === 'Returned')
                                    <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-purple-100 text-purple-800">
                                        Returned
                                    </span>
                                @else
                                    <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                        Out of Stock
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex space-x-2">
                                    <a href="{{ route('inventory.show', $inventory->id) }}" class="text-blue-600 hover:text-blue-900" title="View">
                                        <i class='bx bx-show text-lg'></i>
                                    </a>
                                    <a href="{{ route('inventory.edit', $inventory->id) }}" class="text-green-600 hover:text-green-900" title="Edit">
                                        <i class='bx bx-edit text-lg'></i>
                                    </a>
                                    @if ($inventory->stock > 0)
                                        <button onclick="openMoveModal({{ $inventory->id }}, '{{ $inventory->item_name }}', {{ $inventory->stock }})" class="text-orange-600 hover:text-orange-900" title="Move">
                                            <i class='bx bx-transfer text-lg'></i>
                                        </button>
                                        <button onclick="openReturnModal({{ $inventory->id }}, '{{ $inventory->item_name }}', {{ $inventory->stock }})" class="text-purple-600 hover:text-purple-900" title="Return">
                                            <i class='bx bx-undo text-lg'></i>
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-6 py-8 text-center text-gray-500">
                                <div class="flex flex-col items-center">
                                    <i class='bx bx-package text-4xl text-gray-300 mb-3'></i>
                                    <p class="text-lg font-medium">No inventory items found</p>
                                    <p class="text-sm mt-1">Get started by adding your first inventory item.</p>
                                    <a href="{{ route('inventory.create') }}" class="mt-4 bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg inline-flex items-center gap-2 transition-colors">
                                        <i class='bx bx-plus'></i>
                                        Add First Item
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
                {{ $inventories->links() }}
            </div>
            <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                <div>
                    <p class="text-sm text-gray-700">
                        Showing <span class="font-medium">{{ $inventories->firstItem() ?? 0 }}</span> to <span class="font-medium">{{ $inventories->lastItem() ?? 0 }}</span> of{' '}
                        <span class="font-medium">{{ $inventories->total() }}</span> results
                    </p>
                </div>
                <div>
                    {{ $inventories->links() }}
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
                <input type="hidden" name="_method" value="POST">
                
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Item</label>
                    <input type="text" id="moveItemName" class="w-full px-3 py-2 border border-gray-300 rounded-lg bg-gray-50" readonly>
                </div>
                
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Current Stock</label>
                    <input type="text" id="moveAvailableStock" class="w-full px-3 py-2 border border-gray-300 rounded-lg bg-gray-50" readonly>
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
                
                <div class="mb-4">
                    <label for="new_department" class="block text-sm font-medium text-gray-700 mb-2">New Department *</label>
                    <input type="text" 
                           id="new_department" 
                           name="new_department" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                           placeholder="e.g., IT Department"
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

<!-- Return Modal -->
<div id="returnModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Return Inventory Item</h3>
            <form id="returnForm" method="POST">
                @csrf
                <input type="hidden" id="returnItemId" name="item_id">
                <input type="hidden" name="_method" value="POST">
                
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Item</label>
                    <input type="text" id="returnItemName" class="w-full px-3 py-2 border border-gray-300 rounded-lg bg-gray-50" readonly>
                </div>
                
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Current Stock</label>
                    <input type="text" id="returnAvailableStock" class="w-full px-3 py-2 border border-gray-300 rounded-lg bg-gray-50" readonly>
                </div>
                
                <div class="mb-4">
                    <label for="return_quantity" class="block text-sm font-medium text-gray-700 mb-2">Quantity to Return *</label>
                    <input type="number" 
                           id="return_quantity" 
                           name="return_quantity" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                           placeholder="Enter quantity"
                           min="1"
                           required>
                </div>
                
                <div class="mb-4">
                    <label for="return_reason" class="block text-sm font-medium text-gray-700 mb-2">Return Reason *</label>
                    <textarea id="return_reason" 
                              name="return_reason" 
                              rows="3"
                              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                              placeholder="Reason for return..."
                              required></textarea>
                </div>
                
                <div class="flex justify-end gap-3">
                    <button type="button" onclick="closeReturnModal()" class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-medium py-2 px-4 rounded-lg transition-colors">
                        Cancel
                    </button>
                    <button type="submit" class="bg-purple-600 hover:bg-purple-700 text-white font-medium py-2 px-4 rounded-lg transition-colors">
                        Return Item
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

function openReturnModal(itemId, itemName, availableStock) {
    document.getElementById('returnItemId').value = itemId;
    document.getElementById('returnItemName').value = itemName;
    document.getElementById('returnAvailableStock').value = availableStock;
    document.getElementById('return_quantity').max = availableStock;
    document.getElementById('returnModal').classList.remove('hidden');
}

function closeReturnModal() {
    document.getElementById('returnModal').classList.add('hidden');
    document.getElementById('returnForm').reset();
}

// Handle move form submission
document.getElementById('moveForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const itemId = document.getElementById('moveItemId').value;
    const formData = new FormData(this);
    
    fetch(`{{ route('inventory.move', ':id') }}`.replace(':id', itemId), {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Accept': 'application/json',
        },
        body: formData
    })
    .then(response => {
        if (!response.ok) {
            return response.json().then(err => {
                throw err;
            });
        }
        return response.json();
    })
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
        if (error.errors) {
            let errorMessage = 'Validation errors:\n';
            for (const [field, messages] of Object.entries(error.errors)) {
                errorMessage += `${field}: ${messages.join(', ')}\n`;
            }
            alert(errorMessage);
        } else {
            alert('An error occurred while moving the item: ' + (error.message || 'Unknown error'));
        }
    });
});

// Handle return form submission
document.getElementById('returnForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const itemId = document.getElementById('returnItemId').value;
    const formData = new FormData(this);
    
    fetch(`{{ route('inventory.return-item', ':id') }}`.replace(':id', itemId), {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Accept': 'application/json',
        },
        body: formData
    })
    .then(response => {
        if (!response.ok) {
            return response.json().then(err => {
                throw err;
            });
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            closeReturnModal();
            window.location.reload();
        } else {
            alert('Error: ' + (data.message || 'Failed to return item'));
        }
    })
    .catch(error => {
        console.error('Error:', error);
        if (error.errors) {
            let errorMessage = 'Validation errors:\n';
            for (const [field, messages] of Object.entries(error.errors)) {
                errorMessage += `${field}: ${messages.join(', ')}\n`;
            }
            alert(errorMessage);
        } else {
            alert('An error occurred while returning the item: ' + (error.message || 'Unknown error'));
        }
    });
});
</script>

<!-- Request Item Modal -->
<div id="requestModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full z-50">
    <div class="relative top-10 mx-auto p-5 border max-w-4xl shadow-lg rounded-md bg-white max-h-[90vh] overflow-y-auto">
        <div class="flex justify-between items-center mb-6">
            <h3 class="text-xl font-medium text-gray-900">Request Item from Inventory</h3>
            <button onclick="closeRequestModal()" class="text-gray-400 hover:text-gray-600">
                <i class='bx bx-x text-2xl'></i>
            </button>
        </div>

        <form action="{{ route('supply-requests.store') }}" method="POST">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Item Name (Input) -->
                <div>
                    <label class="block text-sm font-medium text-gray-500 mb-1">Item Name *</label>
                    <input type="text" 
                           id="modal_item_name" 
                           name="item_name" 
                           value="{{ old('item_name') }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                           placeholder="Enter item name"
                           required>
                </div>

                <!-- Supplier Dropdown -->
                <div>
                    <label class="block text-sm font-medium text-gray-500 mb-1">Supplier *</label>
                    <select id="modal_supplier" 
                            name="supplier" 
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            required>
                        <option value="">Select a supplier</option>
                        @if(isset($vendors))
                            @foreach ($vendors as $vendor)
                                @if ($vendor->status === 'Active')
                                    <option value="{{ $vendor->name }}" data-category="{{ $vendor->category ?? 'General' }}">
                                        {{ $vendor->name }} - {{ $vendor->category ?? 'General' }}
                                    </option>
                                @endif
                            @endforeach
                        @endif
                    </select>
                </div>

                <!-- Category (Auto-filled) -->
                <div>
                    <label class="block text-sm font-medium text-gray-500 mb-1">Category</label>
                    <input type="text" 
                           id="modal_category" 
                           name="category" 
                           value="{{ old('category') }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-gray-50"
                           placeholder="Auto-filled based on supplier"
                           readonly>
                </div>

                <!-- Quantity -->
                <div>
                    <label class="block text-sm font-medium text-gray-500 mb-1">Quantity *</label>
                    <input type="number" 
                           id="modal_quantity" 
                           name="quantity_requested" 
                           value="{{ old('quantity_requested') }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                           placeholder="0"
                           min="1"
                           required>
                </div>

                <!-- Unit Price -->
                <div>
                    <label class="block text-sm font-medium text-gray-500 mb-1">Unit Price *</label>
                    <input type="number" 
                           id="modal_unit_price" 
                           name="unit_price" 
                           value="{{ old('unit_price') }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                           placeholder="0.00"
                           min="0"
                           step="0.01"
                           required>
                </div>

                <!-- Total Cost (Real-time) -->
                <div>
                    <label class="block text-sm font-medium text-gray-500 mb-1">Total Cost</label>
                    <input type="text" 
                           id="modal_total_cost" 
                           name="total_cost" 
                           value="{{ old('total_cost', '0.00') }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-gray-50"
                           placeholder="0.00"
                           readonly>
                </div>

                <!-- Priority -->
                <div>
                    <label class="block text-sm font-medium text-gray-500 mb-1">Priority</label>
                    <select id="modal_priority" 
                            name="priority" 
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="">Select priority</option>
                        <option value="Low" {{ old('priority') === 'Low' ? 'selected' : '' }}>Low</option>
                        <option value="Medium" {{ old('priority') === 'Medium' ? 'selected' : '' }}>Medium</option>
                        <option value="High" {{ old('priority') === 'High' ? 'selected' : '' }}>High</option>
                    </select>
                </div>

                <!-- Expected Delivery Date -->
                <div>
                    <label class="block text-sm font-medium text-gray-500 mb-1">Needed By Date *</label>
                    <div class="relative">
                        <input type="date" 
                               id="modal_expected_date" 
                               name="needed_by_date" 
                               value="{{ old('needed_by_date') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                               required>
                        <i class='bx bx-calendar absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 text-xl pointer-events-none'></i>
                    </div>
                </div>

                <!-- Request Date -->
                <div>
                    <label class="block text-sm font-medium text-gray-500 mb-1">Request Date *</label>
                    <div class="relative">
                        <input type="date" 
                               id="request_date" 
                               name="request_date" 
                               value="{{ old('request_date', now()->format('Y-m-d')) }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                               required>
                        <i class='bx bx-calendar absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 text-xl pointer-events-none'></i>
                    </div>
                </div>

                <!-- Status -->
                <div>
                    <label class="block text-sm font-medium text-gray-500 mb-1">Status *</label>
                    <select id="status" 
                            name="status" 
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            required>
                        <option value="">Select status</option>
                        <option value="Pending" {{ old('status') === 'Pending' ? 'selected' : '' }}>Pending</option>
                        <option value="Approved" {{ old('status') === 'Approved' ? 'selected' : '' }}>Approved</option>
                        <option value="Rejected" {{ old('status') === 'Rejected' ? 'selected' : '' }}>Rejected</option>
                        <option value="Processing" {{ old('status') === 'Processing' ? 'selected' : '' }}>Processing</option>
                        <option value="Completed" {{ old('status') === 'Completed' ? 'selected' : '' }}>Completed</option>
                    </select>
                </div>

                <!-- Priority -->
                <div>
                    <label class="block text-sm font-medium text-gray-500 mb-1">Priority</label>
                    <select id="modal_priority" 
                            name="priority" 
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="">Select priority</option>
                        <option value="Low" {{ old('priority') === 'Low' ? 'selected' : '' }}>Low</option>
                        <option value="Medium" {{ old('priority') === 'Medium' ? 'selected' : '' }}>Medium</option>
                        <option value="High" {{ old('priority') === 'High' ? 'selected' : '' }}>High</option>
                    </select>
                </div>

                <!-- Requested By -->
                <div>
                    <label class="block text-sm font-medium text-gray-500 mb-1">Requested By</label>
                    <input type="text" 
                           id="modal_requested_by" 
                           name="requested_by" 
                           value="{{ old('requested_by', auth()->user()->name) }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                           placeholder="Your name">
                </div>

                <!-- Department -->
                <div>
                    <label class="block text-sm font-medium text-gray-500 mb-1">Department</label>
                    <input type="text" 
                           id="modal_department" 
                           name="department" 
                           value="{{ old('department', auth()->user()->department ?? '') }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                           placeholder="e.g., IT Department">
                </div>

                <!-- Reason/Notes -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-500 mb-1">Reason for Request *</label>
                    <textarea id="modal_reason" 
                              name="reason" 
                              rows="4"
                              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                              placeholder="Explain why this item is needed..."
                              required>{{ old('reason') }}</textarea>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="mt-8 flex justify-end gap-3">
                <button type="button" onclick="closeRequestModal()" class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-medium py-2 px-6 rounded-lg transition-colors">
                    Cancel
                </button>
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-6 rounded-lg transition-colors">
                    <i class='bx bx-send mr-2'></i>
                    Submit Request
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function openRequestModal() {
    document.getElementById('requestModal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function closeRequestModal() {
    document.getElementById('requestModal').classList.add('hidden');
    document.body.style.overflow = 'auto';
    // Reset form
    document.querySelector('#requestModal form').reset();
    // Reset auto-filled fields
    document.getElementById('modal_supplier').value = '';
    document.getElementById('modal_category').value = '';
    document.getElementById('modal_total_cost').value = '0.00';
}

// Auto-fill functionality for modal
document.addEventListener('DOMContentLoaded', function() {
    const supplierSelect = document.getElementById('modal_supplier');
    const categoryInput = document.getElementById('modal_category');
    
    // Supplier dropdown change event
    supplierSelect.addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        
        if (selectedOption.value) {
            // Auto-fill category from data-category attribute
            categoryInput.value = selectedOption.dataset.category || '';
        } else {
            categoryInput.value = '';
        }
    });

    // Real-time total cost calculation
    const quantityInput = document.getElementById('modal_quantity');
    const unitPriceInput = document.getElementById('modal_unit_price');
    const totalCostInput = document.getElementById('modal_total_cost');

    function calculateTotal() {
        const quantity = parseFloat(quantityInput.value) || 0;
        const unitPrice = parseFloat(unitPriceInput.value) || 0;
        const total = quantity * unitPrice;
        totalCostInput.value = total.toFixed(2);
    }

    quantityInput.addEventListener('input', calculateTotal);
    unitPriceInput.addEventListener('input', calculateTotal);

    // Calculate initial total if values exist
    calculateTotal();
});

// Close modal when clicking outside
document.getElementById('requestModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeRequestModal();
    }
});
</script>

<script src="{{ asset('js/excel-export.js') }}"></script>
@endsection
