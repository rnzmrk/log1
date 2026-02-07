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
                    <a href="{{ route('admin.warehousing.inbound-logistics') }}" class="ml-1 text-gray-700 hover:text-blue-600 md:ml-2">Inbound Logistics</a>
                </div>
            </li>
            <li aria-current="page">
                <div class="flex items-center">
                    <i class='bx bx-chevron-right text-gray-400'></i>
                    <span class="ml-1 text-gray-500 md:ml-2">Edit Shipment</span>
                </div>
            </li>
        </ol>
    </nav>

    <!-- Page Header -->
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-900">Edit Shipment</h1>
        <div class="flex gap-3">
            <a href="{{ route('inbound-logistics.show', $inboundLogistic->id) }}" class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg flex items-center gap-2 transition-colors">
                <i class='bx bx-show text-xl'></i>
                View
            </a>
            <a href="{{ route('admin.warehousing.inbound-logistics') }}" class="bg-gray-600 hover:bg-gray-700 text-white font-medium py-2 px-4 rounded-lg flex items-center gap-2 transition-colors">
                <i class='bx bx-arrow-back text-xl'></i>
                Back to List
            </a>
        </div>
    </div>

    <!-- Form -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <form action="{{ route('inbound-logistics.update', $inboundLogistic->id) }}" method="POST">
            @csrf
            @method('PUT')
            
            <!-- Error Messages -->
            @if ($errors->any())
                <div class="bg-red-50 border border-red-200 rounded-md p-4 mb-6">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <i class='bx bx-error-circle text-red-400 text-xl'></i>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-red-800">There were some errors with your submission:</h3>
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
                <!-- Shipment ID -->
                <div>
                    <label for="shipment_id" class="block text-sm font-medium text-gray-700 mb-2">Shipment ID *</label>
                    <input type="text" 
                           id="shipment_id" 
                           name="shipment_id" 
                           value="{{ old('shipment_id', $inboundLogistic->shipment_id) }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                           placeholder="e.g., SHP-2024-001"
                           required>
                </div>

                <!-- PO Number -->
                <div>
                    <label for="po_number" class="block text-sm font-medium text-gray-700 mb-2">PO Number *</label>
                    <input type="text" 
                           id="po_number" 
                           name="po_number" 
                           value="{{ old('po_number', $inboundLogistic->po_number) }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                           placeholder="e.g., PO-2024-001"
                           required>
                </div>

                <!-- Supplier -->
                <div>
                    <label for="supplier" class="block text-sm font-medium text-gray-700 mb-2">Supplier *</label>
                    <input type="text" 
                           id="supplier" 
                           name="supplier" 
                           value="{{ old('supplier', $inboundLogistic->supplier) }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                           placeholder="e.g., ABC Supplies"
                           required>
                </div>

                <!-- Expected Units -->
                <div>
                    <label for="expected_units" class="block text-sm font-medium text-gray-700 mb-2">Expected Units *</label>
                    <input type="number" 
                           id="expected_units" 
                           name="expected_units" 
                           value="{{ old('expected_units', $inboundLogistic->expected_units) }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                           placeholder="e.g., 100"
                           min="1"
                           required>
                </div>

                <!-- Received Units -->
                <div>
                    <label for="received_units" class="block text-sm font-medium text-gray-700 mb-2">Received Units</label>
                    <input type="number" 
                           id="received_units" 
                           name="received_units" 
                           value="{{ old('received_units', $inboundLogistic->received_units) }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                           placeholder="e.g., 95"
                           min="0">
                </div>

                <!-- Quality -->
                <div>
                    <label for="quality" class="block text-sm font-medium text-gray-700 mb-2">Quality *</label>
                    <select id="quality" 
                            name="quality" 
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            required>
                        <option value="Good" {{ old('quality', $inboundLogistic->quality) === 'Good' ? 'selected' : '' }}>Good</option>
                        <option value="Pending" {{ old('quality', $inboundLogistic->quality) === 'Pending' ? 'selected' : '' }}>Pending</option>
                    </select>
                </div>

                <!-- Status -->
                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Status *</label>
                    <select id="status" 
                            name="status" 
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            required>
                        <option value="In Progress" {{ old('status', $inboundLogistic->status) === 'In Progress' ? 'selected' : '' }}>In Progress</option>
                        <option value="Verified" {{ old('status', $inboundLogistic->status) === 'Verified' ? 'selected' : '' }}>Verified</option>
                        <option value="Putaway Complete" {{ old('status', $inboundLogistic->status) === 'Putaway Complete' ? 'selected' : '' }}>Putaway Complete</option>
                    </select>
                </div>

                <!-- Expected Date -->
                <div>
                    <label for="expected_date" class="block text-sm font-medium text-gray-700 mb-2">Expected Date *</label>
                    <div class="relative">
                        <input type="date" 
                               id="expected_date" 
                               name="expected_date" 
                               value="{{ old('expected_date', $inboundLogistic->expected_date->format('Y-m-d')) }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                               required>
                        <i class='bx bx-calendar absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 text-xl pointer-events-none'></i>
                    </div>
                </div>

                <!-- Received Date -->
                <div>
                    <label for="received_date" class="block text-sm font-medium text-gray-700 mb-2">Received Date</label>
                    <div class="relative">
                        <input type="date" 
                               id="received_date" 
                               name="received_date" 
                               value="{{ old('received_date', $inboundLogistic->received_date? $inboundLogistic->received_date->format('Y-m-d') : '') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <i class='bx bx-calendar absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 text-xl pointer-events-none'></i>
                    </div>
                </div>

                <!-- Notes -->
                <div class="md:col-span-2">
                    <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">Notes</label>
                    <textarea id="notes" 
                              name="notes" 
                              rows="4"
                              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                              placeholder="Additional notes about this shipment...">{{ old('notes', $inboundLogistic->notes) }}</textarea>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="flex justify-end gap-3 mt-8 pt-6 border-t border-gray-200">
                <a href="{{ route('admin.warehousing.inbound-logistics') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-medium py-2 px-6 rounded-lg transition-colors">
                    Cancel
                </a>
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-6 rounded-lg transition-colors">
                    <i class='bx bx-save mr-2'></i>
                    Update Shipment
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
