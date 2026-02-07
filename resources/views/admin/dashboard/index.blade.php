@extends('layouts.app')

@section('content')
{{-- Success Messages --}}
@if(session('success'))
    <div class="mb-6 bg-green-50 border border-green-200 rounded-lg p-4 flex items-center">
        <i class='bx bx-check-circle text-green-500 text-xl mr-3'></i>
        <div>
            <p class="text-green-800 font-medium">Success!</p>
            <p class="text-green-600 text-sm">{{ session('success') }}</p>
        </div>
    </div>
@endif

<div class="space-y-6">
    {{-- Header with User Info and Logout --}}
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Admin Dashboard</h1>
            <p class="text-gray-600 mt-1">Welcome back, {{ $user->name }}!</p>
        </div>
        <div class="flex items-center space-x-4">
            <div class="text-right">
                <p class="text-sm font-medium text-gray-900">{{ $user->name }}</p>
                <p class="text-sm text-gray-500">{{ $user->email }}</p>
            </div>
            <form method="POST" action="{{ route('logout') }}" onsubmit="return confirm('Are you sure you want to logout?')">
                @csrf
                <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg flex items-center space-x-2 transition-colors">
                    <i class='bx bx-log-out'></i>
                    <span>Logout</span>
                </button>
            </form>
        </div>
    </div>

    {{-- Stats Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        {{-- Total Inventory --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Total Inventory</p>
                    <p class="text-3xl font-bold text-gray-900 mt-2">{{ $stats['total_inventory'] }}</p>
                    <p class="text-sm text-gray-500 mt-1">Items in stock</p>
                </div>
                <div class="bg-blue-100 rounded-lg p-3">
                    <i class='bx bx-package text-blue-600 text-2xl'></i>
                </div>
            </div>
        </div>

        {{-- Low Stock Alerts --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Low Stock Alerts</p>
                    <p class="text-3xl font-bold text-red-600 mt-2">{{ $stats['low_stock_items'] }}</p>
                    <p class="text-sm text-gray-500 mt-1">Need restocking</p>
                </div>
                <div class="bg-red-100 rounded-lg p-3">
                    <i class='bx bx-error-circle text-red-600 text-2xl'></i>
                </div>
            </div>
        </div>

        {{-- Inbound Shipments --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Inbound Shipments</p>
                    <p class="text-3xl font-bold text-gray-900 mt-2">{{ $stats['inbound_shipments'] }}</p>
                    <p class="text-sm text-gray-500 mt-1">{{ $stats['pending_inbound'] }} pending</p>
                </div>
                <div class="bg-green-100 rounded-lg p-3">
                    <i class='bx bx-down-arrow-circle text-green-600 text-2xl'></i>
                </div>
            </div>
        </div>

        {{-- Outbound Shipments --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Outbound Shipments</p>
                    <p class="text-3xl font-bold text-gray-900 mt-2">{{ $stats['outbound_shipments'] }}</p>
                    <p class="text-sm text-gray-500 mt-1">{{ $stats['pending_outbound'] }} pending</p>
                </div>
                <div class="bg-purple-100 rounded-lg p-3">
                    <i class='bx bx-up-arrow-circle text-purple-600 text-2xl'></i>
                </div>
            </div>
        </div>
    </div>

    {{-- Additional Stats Row --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        {{-- Active Suppliers --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Active Suppliers</p>
                    <p class="text-3xl font-bold text-gray-900 mt-2">{{ $stats['active_suppliers'] }}</p>
                    <p class="text-sm text-gray-500 mt-1">of {{ $stats['total_suppliers'] }} total</p>
                </div>
                <div class="bg-indigo-100 rounded-lg p-3">
                    <i class='bx bx-building text-indigo-600 text-2xl'></i>
                </div>
            </div>
        </div>

        {{-- Pending Posts --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Pending Posts</p>
                    <p class="text-3xl font-bold text-orange-600 mt-2">{{ $stats['pending_posts'] }}</p>
                    <p class="text-sm text-gray-500 mt-1">Need review</p>
                </div>
                <div class="bg-orange-100 rounded-lg p-3">
                    <i class='bx bx-file text-orange-600 text-2xl'></i>
                </div>
            </div>
        </div>

        {{-- Asset Requests --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Asset Requests</p>
                    <p class="text-3xl font-bold text-gray-900 mt-2">{{ $stats['asset_requests'] }}</p>
                    <p class="text-sm text-gray-500 mt-1">{{ $stats['pending_asset_requests'] }} pending</p>
                </div>
                <div class="bg-teal-100 rounded-lg p-3">
                    <i class='bx bx-cube text-teal-600 text-2xl'></i>
                </div>
            </div>
        </div>

        {{-- Total Users --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Total Users</p>
                    <p class="text-3xl font-bold text-gray-900 mt-2">{{ $stats['total_users'] }}</p>
                    <p class="text-sm text-gray-500 mt-1">Registered users</p>
                </div>
                <div class="bg-pink-100 rounded-lg p-3">
                    <i class='bx bx-user text-pink-600 text-2xl'></i>
                </div>
            </div>
        </div>
    </div>

    {{-- Stock Movement Trends Chart --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-xl font-semibold text-gray-900">Stock Movement Trends</h2>
            <div class="flex items-center space-x-2">
                <button class="px-3 py-1 text-sm text-gray-600 hover:text-gray-900 border border-gray-300 rounded-lg hover:bg-gray-50">Week</button>
                <button class="px-3 py-1 text-sm text-white bg-blue-600 border border-blue-600 rounded-lg">Month</button>
                <button class="px-3 py-1 text-sm text-gray-600 hover:text-gray-900 border border-gray-300 rounded-lg hover:bg-gray-50">Year</button>
            </div>
        </div>
        
        {{-- Chart Placeholder --}}
        <div class="h-80 flex items-center justify-center bg-gray-50 rounded-lg border-2 border-dashed border-gray-300">
            <div class="text-center">
                <i class='bx bx-bar-chart text-4xl text-gray-400 mb-3'></i>
                <p class="text-gray-500 font-medium">Chart Placeholder</p>
                <p class="text-sm text-gray-400 mt-1">Stock movement trends will be displayed here</p>
                <p class="text-xs text-gray-400 mt-2">Integration with chart library needed</p>
            </div>
        </div>
    </div>

    {{-- Additional Info Cards --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        {{-- Recent Activities --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Recent Activities</h3>
            <div class="space-y-4">
                @forelse($recentActivities as $activity)
                    <div class="flex items-center space-x-3">
                        <div class="bg-{{ $activity['color'] }}-100 rounded-full p-2">
                            <i class='bx {{ $activity['icon'] }} text-{{ $activity['color'] }}-600 text-sm'></i>
                        </div>
                        <div class="flex-1">
                            <p class="text-sm text-gray-900">{{ $activity['message'] }}</p>
                            <p class="text-xs text-gray-500">{{ $activity['time'] }}</p>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-8">
                        <i class='bx bx-time text-4xl text-gray-300 mb-3'></i>
                        <p class="text-gray-500">No recent activities</p>
                    </div>
                @endforelse
            </div>
        </div>

        {{-- Top Suppliers --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Top Suppliers</h3>
            <div class="space-y-3">
                @forelse($topSuppliers as $supplier)
                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                        <div class="flex items-center space-x-3">
                            <div class="bg-indigo-100 rounded-full p-2">
                                <i class='bx bx-building text-indigo-600 text-sm'></i>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-900">{{ $supplier->name }}</p>
                                <p class="text-xs text-gray-500">{{ $supplier->posts_count }} posts</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="text-sm font-medium text-gray-900">{{ $supplier->posts_count }}</p>
                            <p class="text-xs text-gray-500">Posts</p>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-8">
                        <i class='bx bx-building text-4xl text-gray-300 mb-3'></i>
                        <p class="text-gray-500">No suppliers found</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection