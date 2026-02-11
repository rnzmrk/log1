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

    <!-- Form Container -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
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

            <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- PO Number -->
                <div>
                    <label class="block text-sm font-medium text-gray-500 mb-1">PO</label>
                    <input type="text" 
                           id="po_number" 
                           name="po_number" 
                           value="{{ old('po_number', $inboundLogistic->po_number) }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                           placeholder="e.g., PO-2024-001">
                </div>

                <!-- Department -->
                <div>
                    <label class="block text-sm font-medium text-gray-500 mb-1">Department</label>
                    <input type="text" 
                           id="department" 
                           name="department" 
                           value="{{ old('department', $inboundLogistic->department) }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                           placeholder="e.g., IT Department">
                </div>

                <!-- Supplier -->
                <div>
                    <label class="block text-sm font-medium text-gray-500 mb-1">Supplier</label>
                    <input type="text" 
                           id="supplier" 
                           name="supplier" 
                           value="{{ old('supplier', $inboundLogistic->supplier) }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                           placeholder="Supplier name">
                </div>

                <!-- Item Name -->
                <div>
                    <label class="block text-sm font-medium text-gray-500 mb-1">Item Name</label>
                    <input type="text" 
                           id="item_name" 
                           name="item_name" 
                           value="{{ old('item_name', $inboundLogistic->item_name) }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                           placeholder="Item description">
                </div>

                <!-- Stock -->
                <div>
                    <label class="block text-sm font-medium text-gray-500 mb-1">Stock</label>
                    <input type="number" 
                           id="stock" 
                           name="stock" 
                           value="{{ old('stock', $inboundLogistic->stock) }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                           placeholder="0"
                           min="0"
                           step="1">
                </div>

                <!-- Status -->
                <div>
                    <label class="block text-sm font-medium text-gray-500 mb-1">Status</label>
                    <select id="status" 
                            name="status" 
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="Pending" {{ old('status', $inboundLogistic->status) === 'Pending' ? 'selected' : '' }}>Pending</option>
                        <option value="In Transit" {{ old('status', $inboundLogistic->status) === 'In Transit' ? 'selected' : '' }}>In Transit</option>
                        <option value="Delivered" {{ old('status', $inboundLogistic->status) === 'Delivered' ? 'selected' : '' }}>Delivered</option>
                        <option value="Storage" {{ old('status', $inboundLogistic->status) === 'Storage' ? 'selected' : '' }}>Storage</option>
                    </select>
                </div>

                <!-- Quality Check -->
                <div>
                    <label class="block text-sm font-medium text-gray-500 mb-1">Quality Check</label>
                    <select id="quality" 
                            name="quality" 
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="Pending" {{ old('quality', $inboundLogistic->quality) === 'Pending' ? 'selected' : '' }}>Pending</option>
                        <option value="Pass" {{ old('quality', $inboundLogistic->quality) === 'Pass' ? 'selected' : '' }}>Pass</option>
                        <option value="Fail" {{ old('quality', $inboundLogistic->quality) === 'Fail' ? 'selected' : '' }}>Fail</option>
                    </select>
                </div>

                <!-- Expected Date -->
                <div>
                    <label class="block text-sm font-medium text-gray-500 mb-1">Expected Date</label>
                    <div class="relative">
                        <input type="date" 
                               id="expected_date" 
                               name="expected_date" 
                               value="{{ old('expected_date', $inboundLogistic->expected_date ? $inboundLogistic->expected_date->format('Y-m-d') : '') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <i class='bx bx-calendar absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 text-xl pointer-events-none'></i>
                    </div>
                </div>

                <!-- Received Date -->
                <div>
                    <label class="block text-sm font-medium text-gray-500 mb-1">Received Date</label>
                    <div class="relative">
                        <input type="date" 
                               id="received_date" 
                               name="received_date" 
                               value="{{ old('received_date', $inboundLogistic->received_date ? $inboundLogistic->received_date->format('Y-m-d') : '') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <i class='bx bx-calendar absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 text-xl pointer-events-none'></i>
                    </div>
                </div>

                <!-- Received By -->
                <div>
                    <label class="block text-sm font-medium text-gray-500 mb-1">Received By</label>
                    <input type="text" 
                           id="received_by" 
                           name="received_by" 
                           value="{{ old('received_by', $inboundLogistic->received_by) }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                           placeholder="Person who received the shipment">
                </div>

                <!-- Notes -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-500 mb-1">Notes</label>
                    <textarea id="notes" 
                              name="notes" 
                              rows="4"
                              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                              placeholder="Additional notes about this shipment...">{{ old('notes', $inboundLogistic->notes) }}</textarea>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="mt-8 flex justify-end gap-3">
                <a href="{{ route('admin.warehousing.inbound-logistics') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-medium py-2 px-6 rounded-lg transition-colors">
                    Cancel
                </a>
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-6 rounded-lg transition-colors">
                    <i class='bx bx-save mr-2'></i>
                    Update Shipment
                </button>
            </div>
            </div>
        </form>
    </div>
@endsection
