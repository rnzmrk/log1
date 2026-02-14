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
                    <a href="{{ route('admin.assetlifecycle.asset-maintenance') }}" class="text-gray-700 hover:text-blue-600 ml-1 md:ml-2">Asset Maintenance</a>
                </div>
            </li>
            <li aria-current="page">
                <div class="flex items-center">
                    <i class='bx bx-chevron-right text-gray-400'></i>
                    <span class="ml-1 text-gray-500 md:ml-2">View Maintenance</span>
                </div>
            </li>
        </ol>
    </nav>

    <!-- Page Header -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Maintenance Details</h1>
            <p class="text-gray-600 mt-1">View maintenance record information</p>
        </div>
        <div class="flex gap-3">
            <a href="{{ route('admin.assetlifecycle.asset-maintenance') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-medium py-2 px-4 rounded-lg flex items-center gap-2 transition-colors">
                <i class='bx bx-arrow-back text-xl'></i>
                Back to Maintenance
            </a>
            <a href="{{ route('asset-maintenance.edit', $maintenance->id) }}" class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg flex items-center gap-2 transition-colors">
                <i class='bx bx-edit text-xl'></i>
                Edit
            </a>
        </div>
    </div>

    <!-- Maintenance Details -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-lg font-semibold text-gray-900">Maintenance Information</h2>
            <p class="text-sm text-gray-600 mt-1">Complete maintenance record details</p>
        </div>

        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Maintenance ID -->
                <div>
                    <label class="block text-sm font-medium text-gray-500 mb-1">Maintenance ID</label>
                    <div class="text-sm text-gray-900 font-medium">{{ $maintenance->maintenance_number }}</div>
                </div>

                <!-- Asset Name -->
                <div>
                    <label class="block text-sm font-medium text-gray-500 mb-1">Asset Name</label>
                    <div class="text-sm text-gray-900 font-medium">{{ $maintenance->asset_name }}</div>
                </div>

                <!-- Status -->
                <div>
                    <label class="block text-sm font-medium text-gray-500 mb-1">Status</label>
                    <div>
                        <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full shadow-sm
                            @if($maintenance->status == 'Scheduled') bg-yellow-100 text-yellow-800 border border-yellow-300
                            @elseif($maintenance->status == 'In Progress') bg-blue-100 text-blue-800 border border-blue-300
                            @elseif($maintenance->status == 'Completed') bg-green-100 text-green-800 border border-green-300
                            @elseif($maintenance->status == 'On Hold') bg-red-100 text-red-800 border border-red-300
                            @elseif($maintenance->status == 'Cancelled') bg-gray-100 text-gray-800 border border-gray-300
                            @else bg-gray-100 text-gray-800 border border-gray-300
                            @endif">
                            @if($maintenance->status == 'Scheduled')
                                pending
                            @elseif($maintenance->status == 'In Progress')
                                ongoing
                            @elseif($maintenance->status == 'Completed')
                                done
                            @elseif($maintenance->status == 'On Hold')
                                reject
                            @else
                                {{ $maintenance->status }}
                            @endif
                        </span>
                    </div>
                </div>

                <!-- Schedule Date -->
                <div>
                    <label class="block text-sm font-medium text-gray-500 mb-1">Schedule Date</label>
                    <div class="text-sm text-gray-900 font-medium">
                        {{ $maintenance->scheduled_date ? $maintenance->scheduled_date->format('M d, Y') : 'N/A' }}
                    </div>
                </div>
            </div>

            <!-- Maintenance Description -->
            <div class="mt-6 pt-6 border-t border-gray-200">
                <label class="block text-sm font-medium text-gray-500 mb-2">Maintenance Description</label>
                <div class="text-sm text-gray-900 bg-gray-50 rounded-lg p-4">
                    {{ $maintenance->problem_description ?? 'No description provided' }}
                </div>
            </div>

            <!-- Additional Information -->
            <div class="mt-6 pt-6 border-t border-gray-200">
                <h3 class="text-sm font-medium text-gray-900 mb-4">Additional Information</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Created Date -->
                    <div>
                        <label class="block text-sm font-medium text-gray-500 mb-1">Created Date</label>
                        <div class="text-sm text-gray-900">
                            {{ $maintenance->created_at ? $maintenance->created_at->format('M d, Y h:i A') : 'N/A' }}
                        </div>
                    </div>

                    <!-- Last Updated -->
                    <div>
                        <label class="block text-sm font-medium text-gray-500 mb-1">Last Updated</label>
                        <div class="text-sm text-gray-900">
                            {{ $maintenance->updated_at ? $maintenance->updated_at->format('M d, Y h:i A') : 'N/A' }}
                        </div>
                    </div>

                    <!-- Performed By -->
                    <div>
                        <label class="block text-sm font-medium text-gray-500 mb-1">Performed By</label>
                        <div class="text-sm text-gray-900">
                            {{ $maintenance->performed_by ?? 'Not assigned' }}
                        </div>
                    </div>

                    <!-- Asset Tag -->
                    @if($maintenance->asset_tag)
                    <div>
                        <label class="block text-sm font-medium text-gray-500 mb-1">Asset Tag</label>
                        <div class="text-sm text-gray-900">
                            {{ $maintenance->asset_tag }}
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex justify-end space-x-3 mt-8 pt-6 border-t border-gray-200">
                <a href="{{ route('admin.assetlifecycle.asset-maintenance') }}" class="px-6 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors duration-200 font-medium">
                    <i class='bx bx-arrow-back mr-2'></i>
                    Back to List
                </a>
                <a href="{{ route('asset-maintenance.edit', $maintenance->id) }}" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors duration-200 font-medium">
                    <i class='bx bx-edit mr-2'></i>
                    Edit Maintenance
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
