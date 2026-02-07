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
                    <a href="{{ route('admin.warehousing.outbound-logistics') }}" class="text-gray-700 hover:text-blue-600 ml-1 md:ml-2">Outbound Logistics</a>
                </div>
            </li>
            <li aria-current="page">
                <div class="flex items-center">
                    <i class='bx bx-chevron-right text-gray-400'></i>
                    <span class="ml-1 text-gray-500 md:ml-2">View Shipment</span>
                </div>
            </li>
        </ol>
    </nav>

    <!-- Page Header -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">{{ $outboundLogistic->shipment_id }}</h1>
            <p class="text-gray-600 mt-1">Order: {{ $outboundLogistic->order_number }}</p>
        </div>
        <div class="flex gap-3">
            <a href="{{ route('outbound-logistics.edit', $outboundLogistic->id) }}" class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg flex items-center gap-2 transition-colors">
                <i class='bx bx-edit text-xl'></i>
                Edit
            </a>
            <a href="{{ route('admin.warehousing.outbound-logistics') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-medium py-2 px-4 rounded-lg flex items-center gap-2 transition-colors">
                <i class='bx bx-arrow-back text-xl'></i>
                Back to Shipments
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
            <!-- Shipment Details Card -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-900">Shipment Details</h2>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">Shipment ID</label>
                            <p class="text-gray-900 font-medium">{{ $outboundLogistic->shipment_id }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">Order Number</label>
                            <p class="text-gray-900 font-medium">{{ $outboundLogistic->order_number }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">Customer Name</label>
                            <p class="text-gray-900">{{ $outboundLogistic->customer_name }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">Destination</label>
                            <p class="text-gray-900">{{ $outboundLogistic->destination }}</p>
                        </div>
                        @if ($outboundLogistic->carrier)
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">Carrier</label>
                            <p class="text-gray-900">{{ $outboundLogistic->carrier }}</p>
                        </div>
                        @endif
                        @if ($outboundLogistic->tracking_number)
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">Tracking Number</label>
                            <p class="text-gray-900 font-mono">{{ $outboundLogistic->tracking_number }}</p>
                        </div>
                        @endif
                        @if ($outboundLogistic->total_value)
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">Total Value</label>
                            <p class="text-gray-900">₱{{ number_format($outboundLogistic->total_value, 2) }}</p>
                        </div>
                        @endif
                    </div>
                    
                    @if ($outboundLogistic->notes)
                    <div class="mt-6">
                        <label class="block text-sm font-medium text-gray-500 mb-1">Notes</label>
                        <p class="text-gray-900">{{ $outboundLogistic->notes }}</p>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Status Information Card -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-900">Status Information</h2>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div class="text-center">
                            <div class="text-3xl font-bold text-gray-900">{{ $outboundLogistic->total_units }}</div>
                            <div class="text-sm text-gray-500 mt-1">Total Units</div>
                        </div>
                        <div class="text-center">
                            <div class="text-3xl font-bold text-gray-900">{{ $outboundLogistic->shipped_units ?? 0 }}</div>
                            <div class="text-sm text-gray-500 mt-1">Shipped Units</div>
                        </div>
                        <div class="text-center">
                            @if ($outboundLogistic->status === 'Pending')
                                <span class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full bg-orange-100 text-orange-800">
                                    Pending
                                </span>
                            @elseif ($outboundLogistic->status === 'Processing')
                                <span class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                    Processing
                                </span>
                            @elseif ($outboundLogistic->status === 'Shipped')
                                <span class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                    Shipped
                                </span>
                            @elseif ($outboundLogistic->status === 'Delivered')
                                <span class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                    Delivered
                                </span>
                            @else
                                <span class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                    Cancelled
                                </span>
                            @endif
                            <div class="text-sm text-gray-500 mt-1">Status</div>
                        </div>
                    </div>
                    
                    <!-- Priority Indicator -->
                    <div class="mt-6">
                        <div class="flex justify-between text-sm text-gray-600 mb-2">
                            <span>Priority Level</span>
                            <span>{{ $outboundLogistic->priority }}</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-3">
                            @if ($outboundLogistic->priority === 'Urgent')
                                <div class="bg-red-500 h-3 rounded-full" style="width: 100%"></div>
                            @elseif ($outboundLogistic->priority === 'High')
                                <div class="bg-orange-500 h-3 rounded-full" style="width: 75%"></div>
                            @elseif ($outboundLogistic->priority === 'Medium')
                                <div class="bg-yellow-500 h-3 rounded-full" style="width: 50%"></div>
                            @else
                                <div class="bg-gray-500 h-3 rounded-full" style="width: 25%"></div>
                            @endif
                        </div>
                        <div class="mt-2 text-xs text-gray-500">
                            @if ($outboundLogistic->priority === 'Urgent')
                                Urgent - Immediate attention required
                            @elseif ($outboundLogistic->priority === 'High')
                                High Priority - Process quickly
                            @elseif ($outboundLogistic->priority === 'Medium')
                                Medium Priority - Normal processing
                            @else
                                Low Priority - Standard processing
                            @endif
                        </div>
                    </div>

                    <!-- Progress Indicator -->
                    @if ($outboundLogistic->total_units > 0)
                    <div class="mt-6">
                        <div class="flex justify-between text-sm text-gray-600 mb-2">
                            <span>Shipment Progress</span>
                            <span>{{ $outboundLogistic->shipped_units ?? 0 }} / {{ $outboundLogistic->total_units }} units</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-3">
                            <div class="bg-blue-500 h-3 rounded-full" style="width: {{ round((($outboundLogistic->shipped_units ?? 0) / $outboundLogistic->total_units) * 100) }}%"></div>
                        </div>
                        <div class="mt-2 text-xs text-gray-500">
                            {{ round((($outboundLogistic->shipped_units ?? 0) / $outboundLogistic->total_units) * 100) }}% Complete
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Timeline Card -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-900">Timeline</h2>
                </div>
                <div class="p-6">
                    <div class="space-y-4">
                        <div class="flex items-start gap-3">
                            <div class="flex-shrink-0 w-2 h-2 bg-green-500 rounded-full mt-2"></div>
                            <div>
                                <p class="text-sm font-medium text-gray-900">Created</p>
                                <p class="text-sm text-gray-600">{{ $outboundLogistic->created_at->format('M d, Y H:i') }}</p>
                            </div>
                        </div>
                        
                        @if ($outboundLogistic->shipping_date)
                        <div class="flex items-start gap-3">
                            <div class="flex-shrink-0 w-2 h-2 bg-blue-500 rounded-full mt-2"></div>
                            <div>
                                <p class="text-sm font-medium text-gray-900">Shipping Date</p>
                                <p class="text-sm text-gray-600">{{ $outboundLogistic->shipping_date->format('M d, Y') }}</p>
                            </div>
                        </div>
                        @endif
                        
                        @if ($outboundLogistic->delivery_date)
                        <div class="flex items-start gap-3">
                            <div class="flex-shrink-0 w-2 h-2 bg-green-500 rounded-full mt-2"></div>
                            <div>
                                <p class="text-sm font-medium text-gray-900">Delivery Date</p>
                                <p class="text-sm text-gray-600">{{ $outboundLogistic->delivery_date->format('M d, Y') }}</p>
                            </div>
                        </div>
                        @endif
                        
                        <div class="flex items-start gap-3">
                            <div class="flex-shrink-0 w-2 h-2 bg-gray-500 rounded-full mt-2"></div>
                            <div>
                                <p class="text-sm font-medium text-gray-900">Last Updated</p>
                                <p class="text-sm text-gray-600">{{ $outboundLogistic->updated_at->format('M d, Y H:i') }}</p>
                            </div>
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
                    <a href="{{ route('outbound-logistics.edit', $outboundLogistic->id) }}" class="w-full text-left px-4 py-3 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors flex items-center gap-3">
                        <i class='bx bx-edit text-blue-600 text-xl'></i>
                        <div>
                            <p class="font-medium text-gray-900">Edit Shipment</p>
                            <p class="text-sm text-gray-600">Update shipment details</p>
                        </div>
                    </a>
                    
                    @if ($outboundLogistic->tracking_number)
                    <a href="https://www.google.com/search?q={{ $outboundLogistic->tracking_number }}" target="_blank" class="w-full text-left px-4 py-3 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors flex items-center gap-3">
                        <i class='bx bx-map-pin text-green-600 text-xl'></i>
                        <div>
                            <p class="font-medium text-gray-900">Track Shipment</p>
                            <p class="text-sm text-gray-600">Track with carrier</p>
                        </div>
                    </a>
                    @endif
                    
                    <form action="{{ route('outbound-logistics.destroy', $outboundLogistic->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this shipment? This action cannot be undone.')" class="w-full">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="w-full text-left px-4 py-3 border border-red-200 rounded-lg hover:bg-red-50 transition-colors flex items-center gap-3">
                            <i class='bx bx-trash text-red-600 text-xl'></i>
                            <div>
                                <p class="font-medium text-red-900">Delete Shipment</p>
                                <p class="text-sm text-red-600">Remove from system</p>
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
                        <p class="text-gray-900">{{ $outboundLogistic->created_at->format('M d, Y H:i') }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500 mb-1">Last Updated</label>
                        <p class="text-gray-900">{{ $outboundLogistic->updated_at->format('M d, Y H:i') }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500 mb-1">ID</label>
                        <p class="text-gray-900 font-mono text-sm">#{{ $outboundLogistic->id }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
