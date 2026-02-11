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
                    <span class="ml-1 text-gray-500 md:ml-2">View Shipment</span>
                </div>
            </li>
        </ol>
    </nav>

    <!-- Page Header -->
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-900">Shipment Details</h1>
        <div class="flex gap-3">
            <a href="{{ route('inbound-logistics.edit', $inboundLogistic->id) }}" class="bg-green-600 hover:bg-green-700 text-white font-medium py-2 px-4 rounded-lg flex items-center gap-2 transition-colors">
                <i class='bx bx-edit text-xl'></i>
                Edit
            </a>
            <a href="{{ route('admin.warehousing.inbound-logistics') }}" class="bg-gray-600 hover:bg-gray-700 text-white font-medium py-2 px-4 rounded-lg flex items-center gap-2 transition-colors">
                <i class='bx bx-arrow-back text-xl'></i>
                Back to List
            </a>
        </div>
    </div>

    <!-- Shipment Details - Main Information -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Inbound ID -->
                <div>
                    <label class="block text-sm font-medium text-gray-500 mb-1">Inbound ID</label>
                    <p class="text-lg font-semibold text-gray-900">#{{ $inboundLogistic->id }}</p>
                </div>

                <!-- PO Number -->
                <div>
                    <label class="block text-sm font-medium text-gray-500 mb-1">PO</label>
                    <p class="text-lg font-semibold text-gray-900">{{ $inboundLogistic->po_number ?? '-' }}</p>
                </div>

                <!-- Department -->
                <div>
                    <label class="block text-sm font-medium text-gray-500 mb-1">Department</label>
                    <p class="text-lg font-semibold text-gray-900">{{ $inboundLogistic->department ?? '-' }}</p>
                </div>

                <!-- Supplier -->
                <div>
                    <label class="block text-sm font-medium text-gray-500 mb-1">Supplier</label>
                    <p class="text-lg font-semibold text-gray-900">{{ $inboundLogistic->supplier ?? '-' }}</p>
                </div>

                <!-- Item Name -->
                <div>
                    <label class="block text-sm font-medium text-gray-500 mb-1">Item Name</label>
                    <p class="text-lg font-semibold text-gray-900">{{ $inboundLogistic->item_name ?? '-' }}</p>
                </div>

                <!-- Stock (Quantity) -->
                <div>
                    <label class="block text-sm font-medium text-gray-500 mb-1">Stock</label>
                    <p class="text-lg font-semibold text-gray-900">
                        {{ $inboundLogistic->quantity ?? $inboundLogistic->received_units ?? 0 }} units
                    </p>
                </div>

                <!-- Status -->
                <div>
                    <label class="block text-sm font-medium text-gray-500 mb-1">Status</label>
                    @if ($inboundLogistic->status === 'Pending')
                        <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                            Pending
                        </span>
                    @elseif ($inboundLogistic->status === 'In Transit')
                        <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                            In Transit
                        </span>
                    @elseif ($inboundLogistic->status === 'Delivered')
                        <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                            Delivered
                        </span>
                    @elseif ($inboundLogistic->status === 'Storage')
                        <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-purple-100 text-purple-800">
                            Storage
                        </span>
                    @else
                        <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                            {{ $inboundLogistic->status }}
                        </span>
                    @endif
                </div>

                <!-- Quality Check -->
                <div>
                    <label class="block text-sm font-medium text-gray-500 mb-1">Quality Check</label>
                    @if ($inboundLogistic->quality === 'Pass')
                        <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                            Pass
                        </span>
                    @elseif ($inboundLogistic->quality === 'Fail')
                        <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                            Fail
                        </span>
                    @else
                        <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                            Pending
                        </span>
                    @endif
                </div>

                <!-- Expected Date -->
                <div>
                    <label class="block text-sm font-medium text-gray-500 mb-1">Expected Date</label>
                    <p class="text-lg font-semibold text-gray-900">{{ $inboundLogistic->expected_date->format('M d, Y') }}</p>
                </div>

                <!-- Received Date -->
                <div>
                    <label class="block text-sm font-medium text-gray-500 mb-1">Received Date</label>
                    <p class="text-lg font-semibold text-gray-900">
                        {{ $inboundLogistic->received_date ? $inboundLogistic->received_date->format('M d, Y') : 'Not received yet' }}
                    </p>
                </div>

                <!-- Received By -->
                <div>
                    <label class="block text-sm font-medium text-gray-500 mb-1">Received By</label>
                    <p class="text-lg font-semibold text-gray-900">{{ $inboundLogistic->received_by ?? '-' }}</p>
                </div>

                <!-- Notes -->
                @if ($inboundLogistic->notes)
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-500 mb-1">Notes</label>
                        <p class="text-gray-900 bg-gray-50 p-3 rounded-lg">{{ $inboundLogistic->notes }}</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Additional Information Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">

        <!-- Timeline -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-gray-900">Timeline</h2>
            </div>
            <div class="p-6">
                <div class="space-y-4">
                    <div class="flex items-start gap-3">
                        <div class="flex-shrink-0 w-2 h-2 bg-blue-500 rounded-full mt-2"></div>
                        <div>
                            <p class="text-sm font-medium text-gray-900">Expected Date</p>
                            <p class="text-sm text-gray-500">{{ $inboundLogistic->expected_date->format('M d, Y H:i') }}</p>
                        </div>
                    </div>
                    @if ($inboundLogistic->received_date)
                        <div class="flex items-start gap-3">
                            <div class="flex-shrink-0 w-2 h-2 bg-green-500 rounded-full mt-2"></div>
                            <div>
                                <p class="text-sm font-medium text-gray-900">Received</p>
                                <p class="text-sm text-gray-500">{{ $inboundLogistic->received_date->format('M d, Y H:i') }}</p>
                                @if ($inboundLogistic->received_by)
                                    <p class="text-xs text-gray-400">by {{ $inboundLogistic->received_by }}</p>
                                @endif
                            </div>
                        </div>
                    @endif
                    <div class="flex items-start gap-3">
                        <div class="flex-shrink-0 w-2 h-2 bg-gray-400 rounded-full mt-2"></div>
                        <div>
                            <p class="text-sm font-medium text-gray-900">Last Updated</p>
                            <p class="text-sm text-gray-500">{{ $inboundLogistic->updated_at->format('M d, Y H:i') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
