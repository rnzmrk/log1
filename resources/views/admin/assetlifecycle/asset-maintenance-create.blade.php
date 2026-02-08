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
                    <a href="{{ route('asset-maintenance.index') }}" class="text-gray-700 hover:text-blue-600 ml-1 md:ml-2">Asset Maintenance</a>
                </div>
            </li>
            <li aria-current="page">
                <div class="flex items-center">
                    <i class='bx bx-chevron-right text-gray-400'></i>
                    <span class="ml-1 text-gray-500 md:ml-2">New Maintenance Record</span>
                </div>
            </li>
        </ol>
    </nav>

    <!-- Page Header -->
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-900">New Maintenance Record</h1>
        <a href="{{ route('asset-maintenance.index') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-medium py-2 px-4 rounded-lg flex items-center gap-2 transition-colors">
            <i class='bx bx-arrow-back text-xl'></i>
            Back to Maintenance
        </a>
    </div>

    <!-- Assets Needing Maintenance Alert -->
    @if($assetsNeedingMaintenance->count() > 0)
        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-6">
            <div class="flex items-start">
                <i class='bx bx-error text-yellow-600 text-xl mr-3 mt-0.5'></i>
                <div class="flex-1">
                    <h3 class="text-sm font-medium text-yellow-800">Assets Needing Maintenance</h3>
                    <div class="mt-2 text-sm text-yellow-700">
                        <p>{{ $assetsNeedingMaintenance->count() }} asset(s) are due for maintenance. Consider selecting one of these assets.</p>
                        <div class="mt-2 space-y-1">
                            @foreach($assetsNeedingMaintenance->take(3) as $asset)
                                <p class="font-medium">{{ $asset->asset_tag }} - {{ $asset->item_name }}</p>
                            @endforeach
                            @if($assetsNeedingMaintenance->count() > 3)
                                <p class="text-xs">... and {{ $assetsNeedingMaintenance->count() - 3 }} more</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Form Container -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-lg font-semibold text-gray-900">Maintenance Information</h2>
        </div>
        
        <form method="POST" action="{{ route('asset-maintenance.store') }}" class="p-6">
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
                <!-- Asset Selection -->
                <div>
                    <label for="asset_id" class="block text-sm font-medium text-gray-700 mb-2">Asset *</label>
                    <select id="asset_id" 
                            name="asset_id" 
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            required onchange="loadAssetDetails(this.value)">
                        <option value="">Select an asset</option>
                        @foreach ($assets as $asset)
                            <option value="{{ $asset->id }}" {{ old('asset_id') == $asset->id ? 'selected' : '' }}>
                                {{ $asset->asset_tag ?? $asset->item_code }} - {{ $asset->item_name }}
                            </option>
                        @endforeach
                    </select>
                    <p class="mt-1 text-sm text-gray-500">Select the asset requiring maintenance</p>
                </div>

                <!-- Asset Details (shown after selection) -->
                <div id="assetDetails" class="hidden">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Asset Details</label>
                    <div class="bg-gray-50 border border-gray-200 rounded-lg p-3">
                        <div class="space-y-1 text-sm">
                            <p><strong>Tag:</strong> <span id="detailAssetTag">-</span></p>
                            <p><strong>Name:</strong> <span id="detailAssetName">-</span></p>
                            <p><strong>Condition:</strong> <span id="detailCondition">-</span></p>
                            <p><strong>Location:</strong> <span id="detailLocation">-</span></p>
                            <p><strong>Last Maintenance:</strong> <span id="detailLastMaintenance">-</span></p>
                            <p><strong>Next Maintenance:</strong> <span id="detailNextMaintenance">-</span></p>
                        </div>
                    </div>
                </div>

                <!-- Maintenance Type -->
                <div>
                    <label for="maintenance_type" class="block text-sm font-medium text-gray-700 mb-2">Maintenance Type *</label>
                    <select id="maintenance_type" 
                            name="maintenance_type" 
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            required>
                        <option value="">Select maintenance type</option>
                        <option value="Preventive" {{ old('maintenance_type') === 'Preventive' ? 'selected' : '' }}>Preventive</option>
                        <option value="Corrective" {{ old('maintenance_type') === 'Corrective' ? 'selected' : '' }}>Corrective</option>
                        <option value="Emergency" {{ old('maintenance_type') === 'Emergency' ? 'selected' : '' }}>Emergency</option>
                        <option value="Predictive" {{ old('maintenance_type') === 'Predictive' ? 'selected' : '' }}>Predictive</option>
                        <option value="Calibration" {{ old('maintenance_type') === 'Calibration' ? 'selected' : '' }}>Calibration</option>
                    </select>
                </div>

                <!-- Status -->
                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Status *</label>
                    <select id="status" 
                            name="status" 
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            required>
                        <option value="">Select status</option>
                        <option value="Scheduled" {{ old('status') === 'Scheduled' ? 'selected' : '' }}>Scheduled</option>
                        <option value="In Progress" {{ old('status') === 'In Progress' ? 'selected' : '' }}>In Progress</option>
                        <option value="Completed" {{ old('status') === 'Completed' ? 'selected' : '' }}>Completed</option>
                        <option value="On Hold" {{ old('status') === 'On Hold' ? 'selected' : '' }}>On Hold</option>
                        <option value="Cancelled" {{ old('status') === 'Cancelled' ? 'selected' : '' }}>Cancelled</option>
                    </select>
                </div>

                <!-- Priority -->
                <div>
                    <label for="priority" class="block text-sm font-medium text-gray-700 mb-2">Priority *</label>
                    <select id="priority" 
                            name="priority" 
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            required>
                        <option value="">Select priority</option>
                        <option value="Urgent" {{ old('priority') === 'Urgent' ? 'selected' : '' }}>Urgent</option>
                        <option value="High" {{ old('priority') === 'High' ? 'selected' : '' }}>High</option>
                        <option value="Medium" {{ old('priority') === 'Medium' ? 'selected' : '' }}>Medium</option>
                        <option value="Low" {{ old('priority') === 'Low' ? 'selected' : '' }}>Low</option>
                    </select>
                </div>

                <!-- Scheduled Date -->
                <div>
                    <label for="scheduled_date" class="block text-sm font-medium text-gray-700 mb-2">Scheduled Date *</label>
                    <input type="date" 
                           id="scheduled_date" 
                           name="scheduled_date" 
                           value="{{ old('scheduled_date') }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                           required>
                </div>

                <!-- Start Time -->
                <div>
                    <label for="start_time" class="block text-sm font-medium text-gray-700 mb-2">Start Time</label>
                    <input type="datetime-local" 
                           id="start_time" 
                           name="start_time" 
                           value="{{ old('start_time') }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <p class="mt-1 text-sm text-gray-500">Optional: When maintenance started</p>
                </div>

                <!-- End Time -->
                <div>
                    <label for="end_time" class="block text-sm font-medium text-gray-700 mb-2">End Time</label>
                    <input type="datetime-local" 
                           id="end_time" 
                           name="end_time" 
                           value="{{ old('end_time') }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <p class="mt-1 text-sm text-gray-500">Optional: When maintenance completed</p>
                </div>

                <!-- Next Maintenance Date -->
                <div>
                    <label for="next_maintenance_date" class="block text-sm font-medium text-gray-700 mb-2">Next Maintenance Date</label>
                    <input type="date" 
                           id="next_maintenance_date" 
                           name="next_maintenance_date" 
                           value="{{ old('next_maintenance_date') }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <p class="mt-1 text-sm text-gray-500">Optional: When next maintenance is due</p>
                </div>
            </div>

            <!-- Technician Information -->
            <div class="mt-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Technician Information</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="technician_name" class="block text-sm font-medium text-gray-700 mb-2">Technician Name</label>
                        <input type="text" 
                               id="technician_name" 
                               name="technician_name" 
                               value="{{ old('technician_name') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                               placeholder="e.g., John Smith">
                        <p class="mt-1 text-sm text-gray-500">Optional: Assigned technician</p>
                    </div>

                    <div>
                        <label for="technician_email" class="block text-sm font-medium text-gray-700 mb-2">Technician Email</label>
                        <input type="email" 
                               id="technician_email" 
                               name="technician_email" 
                               value="{{ old('technician_email') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                               placeholder="e.g., john@example.com">
                        <p class="mt-1 text-sm text-gray-500">Optional: Technician email</p>
                    </div>

                    <div>
                        <label for="technician_phone" class="block text-sm font-medium text-gray-700 mb-2">Technician Phone</label>
                        <input type="tel" 
                               id="technician_phone" 
                               name="technician_phone" 
                               value="{{ old('technician_phone') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                               placeholder="e.g., (555) 123-4567">
                        <p class="mt-1 text-sm text-gray-500">Optional: Technician phone</p>
                    </div>

                    <div>
                        <label for="performed_by" class="block text-sm font-medium text-gray-700 mb-2">Performed By</label>
                        <input type="text" 
                               id="performed_by" 
                               name="performed_by" 
                               value="{{ old('performed_by') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                               placeholder="e.g., Maintenance Team">
                        <p class="mt-1 text-sm text-gray-500">Optional: Who performed the maintenance</p>
                    </div>

                    <div>
                        <label for="approved_by" class="block text-sm font-medium text-gray-700 mb-2">Approved By</label>
                        <input type="text" 
                               id="approved_by" 
                               name="approved_by" 
                               value="{{ old('approved_by') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                               placeholder="e.g., Manager Name">
                        <p class="mt-1 text-sm text-gray-500">Optional: Who approved the maintenance</p>
                    </div>
                </div>
            </div>

            <!-- Cost Information -->
            <div class="mt-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Cost Information</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <label for="parts_cost" class="block text-sm font-medium text-gray-700 mb-2">Parts Cost</label>
                        <input type="number" 
                               id="parts_cost" 
                               name="parts_cost" 
                               value="{{ old('parts_cost', 0) }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                               placeholder="0.00"
                               step="0.01"
                               min="0">
                        <p class="mt-1 text-sm text-gray-500">Cost of replacement parts</p>
                    </div>

                    <div>
                        <label for="labor_cost" class="block text-sm font-medium text-gray-700 mb-2">Labor Cost</label>
                        <input type="number" 
                               id="labor_cost" 
                               name="labor_cost" 
                               value="{{ old('labor_cost', 0) }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                               placeholder="0.00"
                               step="0.01"
                               min="0">
                        <p class="mt-1 text-sm text-gray-500">Labor service cost</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Total Cost</label>
                        <div class="w-full px-3 py-2 bg-gray-100 border border-gray-300 rounded-lg">
                            <span id="total_cost">₱0.00</span>
                        </div>
                        <p class="mt-1 text-sm text-gray-500">Automatically calculated</p>
                    </div>
                </div>
            </div>

            <!-- Maintenance Details -->
            <div class="mt-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Maintenance Details</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="md:col-span-2">
                        <label for="problem_description" class="block text-sm font-medium text-gray-700 mb-2">Problem Description</label>
                        <textarea id="problem_description" 
                                  name="problem_description" 
                                  rows="4"
                                  class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                  placeholder="Describe the problem or issue that required maintenance...">{{ old('problem_description') }}</textarea>
                        <p class="mt-1 text-sm text-gray-500">Optional: What was the issue?</p>
                    </div>

                    <div class="md:col-span-2">
                        <label for="work_performed" class="block text-sm font-medium text-gray-700 mb-2">Work Performed</label>
                        <textarea id="work_performed" 
                                  name="work_performed" 
                                  rows="4"
                                  class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                  placeholder="Describe the work that was performed...">{{ old('work_performed') }}</textarea>
                        <p class="mt-1 text-sm text-gray-500">Optional: What maintenance work was done?</p>
                    </div>

                    <div class="md:col-span-2">
                        <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">Additional Notes</label>
                        <textarea id="notes" 
                                  name="notes" 
                                  rows="3"
                                  class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                  placeholder="Any additional notes or observations...">{{ old('notes') }}</textarea>
                        <p class="mt-1 text-sm text-gray-500">Optional: Additional information</p>
                    </div>

                    <div class="md:col-span-2">
                        <label for="next_maintenance_notes" class="block text-sm font-medium text-gray-700 mb-2">Next Maintenance Notes</label>
                        <textarea id="next_maintenance_notes" 
                                  name="next_maintenance_notes" 
                                  rows="3"
                                  class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                  placeholder="Notes for next maintenance...">{{ old('next_maintenance_notes') }}</textarea>
                        <p class="mt-1 text-sm text-gray-500">Optional: Notes for next maintenance</p>
                    </div>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="flex justify-end gap-3 mt-8">
                <a href="{{ route('asset-maintenance.index') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-medium py-2 px-6 rounded-lg transition-colors">
                    Cancel
                </a>
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-6 rounded-lg transition-colors">
                    <i class='bx bx-save mr-2'></i>
                    Create Maintenance Record
                </button>
            </div>
        </form>
    </div>
</div>

<script>
// Load asset details via AJAX
function loadAssetDetails(assetId) {
    const assetDetails = document.getElementById('assetDetails');
    
    if (!assetId) {
        assetDetails.classList.add('hidden');
        return;
    }
    
    fetch(`/asset-maintenance/get-asset-details/${assetId}`)
        .then(response => response.json())
        .then(data => {
            document.getElementById('detailAssetTag').textContent = data.asset_tag || '-';
            document.getElementById('detailAssetName').textContent = data.asset_name || '-';
            document.getElementById('detailCondition').textContent = data.condition || '-';
            document.getElementById('detailLocation').textContent = data.location || '-';
            document.getElementById('detailLastMaintenance').textContent = data.last_maintenance_date || 'Never';
            document.getElementById('detailNextMaintenance').textContent = data.next_maintenance_date || 'Not scheduled';
            
            assetDetails.classList.remove('hidden');
        })
        .catch(error => {
            console.error('Error loading asset details:', error);
            assetDetails.classList.add('hidden');
        });
}

// Calculate total cost
document.addEventListener('DOMContentLoaded', function() {
    const partsCost = document.getElementById('parts_cost');
    const laborCost = document.getElementById('labor_cost');
    const totalCost = document.getElementById('total_cost');
    const statusSelect = document.getElementById('status');
    const startTime = document.getElementById('start_time');
    const endTime = document.getElementById('end_time');

    function calculateTotal() {
        const parts = parseFloat(partsCost.value) || 0;
        const labor = parseFloat(laborCost.value) || 0;
        const total = parts + labor;
        totalCost.textContent = '₱' + total.toFixed(2);
    }

    partsCost.addEventListener('input', calculateTotal);
    laborCost.addEventListener('input', calculateTotal);

    // Auto-set start time when status changes to 'In Progress'
    statusSelect.addEventListener('change', function() {
        if (this.value === 'In Progress' && !startTime.value) {
            const now = new Date();
            const offset = now.getTimezoneOffset() * 60000;
            const localISOTime = new Date(now - offset).toISOString().slice(0, 16);
            startTime.value = localISOTime;
        }
        
        // Auto-set end time when status changes to 'Completed'
        if (this.value === 'Completed' && !endTime.value) {
            const now = new Date();
            const offset = now.getTimezoneOffset() * 60000;
            const localISOTime = new Date(now - offset).toISOString().slice(0, 16);
            endTime.value = localISOTime;
        }
    });

    // Initial calculation
    calculateTotal();
    
    // Load asset details if already selected
    const assetSelect = document.getElementById('asset_id');
    if (assetSelect.value) {
        loadAssetDetails(assetSelect.value);
    }
});
</script>
@endsection
