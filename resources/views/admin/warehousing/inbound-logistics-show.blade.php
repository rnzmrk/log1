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

    <!-- Shipment Details -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Shipment ID -->
                <div>
                    <h3 class="text-sm font-medium text-gray-500 mb-1">Shipment ID</h3>
                    <p class="text-lg font-semibold text-gray-900">{{ $inboundLogistic->shipment_id }}</p>
                </div>

                <!-- PO Number -->
                <div>
                    <h3 class="text-sm font-medium text-gray-500 mb-1">PO Number</h3>
                    <p class="text-lg font-semibold text-gray-900">{{ $inboundLogistic->po_number }}</p>
                </div>

                <!-- Supplier -->
                <div>
                    <h3 class="text-sm font-medium text-gray-500 mb-1">Supplier</h3>
                    <p class="text-lg font-semibold text-gray-900">{{ $inboundLogistic->supplier }}</p>
                </div>

                <!-- Expected Units -->
                <div>
                    <h3 class="text-sm font-medium text-gray-500 mb-1">Expected Units</h3>
                    <p class="text-lg font-semibold text-gray-900">{{ $inboundLogistic->expected_units }} units</p>
                </div>

                <!-- Received Units -->
                <div>
                    <h3 class="text-sm font-medium text-gray-500 mb-1">Received Units</h3>
                    <div class="flex items-center gap-3">
                        <p class="text-lg font-semibold text-gray-900">
                            {{ $inboundLogistic->received_units ?? 0 }} units
                        </p>
                        @if ($inboundLogistic->received_units)
                            <div class="flex items-center">
                                <div class="w-20 bg-gray-200 rounded-full h-2 mr-2">
                                    <div class="bg-blue-600 h-2 rounded-full" style="width: {{ min(100, ($inboundLogistic->received_units / $inboundLogistic->expected_units) * 100) }}%"></div>
                                </div>
                                <span class="text-sm text-gray-600">{{ round(($inboundLogistic->received_units / $inboundLogistic->expected_units) * 100) }}%</span>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Quality -->
                <div>
                    <h3 class="text-sm font-medium text-gray-500 mb-1">Quality</h3>
                    @if ($inboundLogistic->quality === 'Good')
                        <span class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                            Good
                        </span>
                    @else
                        <span class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                            Pending
                        </span>
                    @endif
                </div>

                <!-- Status -->
                <div>
                    <h3 class="text-sm font-medium text-gray-500 mb-1">Status</h3>
                    @if ($inboundLogistic->status === 'Putaway Complete')
                        <span class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                            Putaway Complete
                        </span>
                    @elseif ($inboundLogistic->status === 'Verified')
                        <span class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                            Verified
                        </span>
                    @else
                        <span class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                            In Progress
                        </span>
                    @endif
                </div>

                <!-- Expected Date -->
                <div>
                    <h3 class="text-sm font-medium text-gray-500 mb-1">Expected Date</h3>
                    <p class="text-lg font-semibold text-gray-900">{{ $inboundLogistic->expected_date->format('M d, Y') }}</p>
                </div>

                <!-- Received Date -->
                <div>
                    <h3 class="text-sm font-medium text-gray-500 mb-1">Received Date</h3>
                    <p class="text-lg font-semibold text-gray-900">
                        {{ $inboundLogistic->received_date ? $inboundLogistic->received_date->format('M d, Y') : 'Not received yet' }}
                    </p>
                </div>

                <!-- Notes -->
                @if ($inboundLogistic->notes)
                    <div class="md:col-span-2">
                        <h3 class="text-sm font-medium text-gray-500 mb-1">Notes</h3>
                        <p class="text-gray-900">{{ $inboundLogistic->notes }}</p>
                    </div>
                @endif
            </div>

            <!-- Timestamps -->
            <div class="mt-8 pt-6 border-t border-gray-200">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm text-gray-500">
                    <div>
                        <span class="font-medium">Created:</span> {{ $inboundLogistic->created_at->format('M d, Y H:i') }}
                    </div>
                    <div>
                        <span class="font-medium">Last Updated:</span> {{ $inboundLogistic->updated_at->format('M d, Y H:i') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
