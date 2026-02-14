@extends('layouts.app')

@section('content')
<div class="container-fluid px-6 py-8">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex justify-between items-center mb-4">
            <div>
                <h1 class="text-4xl font-bold text-gray-900 mb-2">Maintenance Records</h1>
                <p class="text-gray-600 text-lg">Track and manage asset maintenance activities</p>
            </div>
            <div class="flex gap-3">
                <button onclick="location.reload()" class="bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium py-2 px-4 rounded-lg flex items-center gap-2 transition-all duration-200 shadow-sm">
                    <i class='bx bx-refresh text-xl'></i>
                    Refresh
                </button>
                <a href="{{ route('admin.assetlifecycle.asset-management') }}" class="bg-blue-100 hover:bg-blue-200 text-blue-700 font-medium py-2 px-4 rounded-lg flex items-center gap-2 transition-all duration-200 shadow-sm">
                    <i class='bx bx-arrow-back text-xl'></i>
                    Back to Assets
                </a>
            </div>
        </div>
        
        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
            <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl p-6 text-white shadow-lg">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-blue-100 text-sm font-medium">Total Maintenance</p>
                        <p class="text-3xl font-bold mt-1">{{ $maintenances->count() }}</p>
                    </div>
                    <div class="bg-blue-400 bg-opacity-30 rounded-lg p-3">
                        <i class='bx bx-wrench text-2xl'></i>
                    </div>
                </div>
            </div>
            <div class="bg-gradient-to-br from-yellow-500 to-yellow-600 rounded-xl p-6 text-white shadow-lg">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-yellow-100 text-sm font-medium">Pending</p>
                        <p class="text-3xl font-bold mt-1">{{ $maintenances->where('status', 'Scheduled')->count() }}</p>
                    </div>
                    <div class="bg-yellow-400 bg-opacity-30 rounded-lg p-3">
                        <i class='bx bx-clock text-2xl'></i>
                    </div>
                </div>
            </div>
            <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl p-6 text-white shadow-lg">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-blue-100 text-sm font-medium">Ongoing</p>
                        <p class="text-3xl font-bold mt-1">{{ $maintenances->where('status', 'In Progress')->count() }}</p>
                    </div>
                    <div class="bg-blue-400 bg-opacity-30 rounded-lg p-3">
                        <i class='bx bx-play-circle text-2xl'></i>
                    </div>
                </div>
            </div>
            <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-xl p-6 text-white shadow-lg">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-green-100 text-sm font-medium">Done</p>
                        <p class="text-3xl font-bold mt-1">{{ $maintenances->where('status', 'Completed')->count() }}</p>
                    </div>
                    <div class="bg-green-400 bg-opacity-30 rounded-lg p-3">
                        <i class='bx bx-check-circle text-2xl'></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Maintenance Table -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
            <div class="flex items-center justify-between">
                <h2 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                    <i class='bx bx-list-ul text-xl text-blue-600'></i>
                    Maintenance Records
                </h2>
                <div class="text-sm text-gray-500">
                    Showing {{ $maintenances->count() }} maintenance records
                </div>
            </div>
        </div>
        
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Maintenance ID
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Asset Name
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Maintenance Description
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Schedule Date
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Status
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Actions
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($maintenances as $maintenance)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="text-sm font-medium text-gray-900">{{ $maintenance->maintenance_number }}</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">{{ $maintenance->asset_name }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm text-gray-900 max-w-xs" title="{{ $maintenance->problem_description }}">
                                    {{ Str::limit($maintenance->problem_description, 100) }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">
                                    {{ $maintenance->scheduled_date ? $maintenance->scheduled_date->format('M d, Y') : 'N/A' }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
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
                                    @else
                                        {{ $maintenance->status }}
                                    @endif
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex items-center space-x-2">
                                    <a href="{{ route('asset-management.show', $maintenance->asset_id) }}" class="inline-flex items-center px-3 py-1.5 bg-blue-100 text-blue-600 hover:bg-blue-200 text-sm font-medium rounded-lg transition-colors duration-200" title="View Asset">
                                        <i class='bx bx-show mr-1'></i>
                                        View Asset
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-8 text-center text-gray-500">
                                <div class="flex flex-col items-center">
                                    <i class='bx bx-wrench text-4xl text-gray-300 mb-3'></i>
                                    <p class="text-lg font-medium">No maintenance records found</p>
                                    <p class="text-sm">Maintenance records will appear here when assets are set to maintenance.</p>
                                    <a href="{{ route('admin.assetlifecycle.asset-management') }}" class="mt-4 bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg inline-flex items-center gap-2 transition-colors">
                                        <i class='bx bx-plus'></i>
                                        Set Asset Maintenance
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
