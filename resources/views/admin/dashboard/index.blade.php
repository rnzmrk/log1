@extends('layouts.app')

@section('content')
<div class="space-y-6">
    {{-- Breadcrumb --}}
    <nav class="flex items-center space-x-2 text-sm text-gray-600">
        <a href="#" class="hover:text-blue-600 transition-colors">Home</a>
        <i class='bx bx-chevron-right text-xs'></i>
        <a href="#" class="hover:text-blue-600 transition-colors">Logistics Dashboard</a>
        <i class='bx bx-chevron-right text-xs'></i>
        <span class="text-gray-900 font-medium">Warehouse Analytics</span>
    </nav>

    {{-- Page Title --}}
    <div>
        <h1 class="text-3xl font-bold text-gray-900">Warehouse Analytics</h1>
        <p class="text-gray-600 mt-1">Monitor your warehouse performance and metrics</p>
    </div>

    {{-- Stats Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        {{-- Total Stock Items --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Total Stock Items</p>
                    <p class="text-3xl font-bold text-gray-900 mt-2">141</p>
                    <p class="text-sm text-gray-500 mt-1">+12% from last month</p>
                </div>
                <div class="bg-blue-100 rounded-lg p-3">
                    <i class='bx bx-package text-blue-600 text-2xl'></i>
                </div>
            </div>
        </div>

        {{-- Low Stock Alerts --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Low Stock Alerts</p>
                    <p class="text-3xl font-bold text-orange-600 mt-2">1</p>
                    <p class="text-sm text-gray-500 mt-1">Needs attention</p>
                </div>
                <div class="bg-orange-100 rounded-lg p-3">
                    <i class='bx bx-error text-orange-600 text-2xl'></i>
                </div>
            </div>
        </div>

        {{-- Incoming Shipments --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Incoming Shipments</p>
                    <p class="text-3xl font-bold text-green-600 mt-2">4</p>
                    <p class="text-sm text-gray-500 mt-1">Expected today</p>
                </div>
                <div class="bg-green-100 rounded-lg p-3">
                    <i class='bx bx-down-arrow-alt text-green-600 text-2xl'></i>
                </div>
            </div>
        </div>

        {{-- Outgoing Shipments --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Outgoing Shipments</p>
                    <p class="text-3xl font-bold text-purple-600 mt-2">34</p>
                    <p class="text-sm text-gray-500 mt-1">In progress</p>
                </div>
                <div class="bg-purple-100 rounded-lg p-3">
                    <i class='bx bx-up-arrow-alt text-purple-600 text-2xl'></i>
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
                <div class="flex items-center space-x-3">
                    <div class="bg-green-100 rounded-full p-2">
                        <i class='bx bx-check text-green-600 text-sm'></i>
                    </div>
                    <div class="flex-1">
                        <p class="text-sm text-gray-900">Shipment #1234 received</p>
                        <p class="text-xs text-gray-500">10 minutes ago</p>
                    </div>
                </div>
                <div class="flex items-center space-x-3">
                    <div class="bg-blue-100 rounded-full p-2">
                        <i class='bx bx-package text-blue-600 text-sm'></i>
                    </div>
                    <div class="flex-1">
                        <p class="text-sm text-gray-900">New inventory added</p>
                        <p class="text-xs text-gray-500">1 hour ago</p>
                    </div>
                </div>
                <div class="flex items-center space-x-3">
                    <div class="bg-orange-100 rounded-full p-2">
                        <i class='bx bx-truck text-orange-600 text-sm'></i>
                    </div>
                    <div class="flex-1">
                        <p class="text-sm text-gray-900">Order #5678 shipped</p>
                        <p class="text-xs text-gray-500">2 hours ago</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Quick Actions --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Quick Actions</h3>
            <div class="grid grid-cols-2 gap-3">
                <button class="p-3 text-left border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
                    <i class='bx bx-plus-circle text-blue-600 text-xl mb-2'></i>
                    <p class="text-sm font-medium text-gray-900">New Shipment</p>
                </button>
                <button class="p-3 text-left border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
                    <i class='bx bx-search text-green-600 text-xl mb-2'></i>
                    <p class="text-sm font-medium text-gray-900">Track Order</p>
                </button>
                <button class="p-3 text-left border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
                    <i class='bx bx-file text-orange-600 text-xl mb-2'></i>
                    <p class="text-sm font-medium text-gray-900">Generate Report</p>
                </button>
                <button class="p-3 text-left border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
                    <i class='bx bx-cog text-purple-600 text-xl mb-2'></i>
                    <p class="text-sm font-medium text-gray-900">Settings</p>
                </button>
            </div>
        </div>
    </div>
</div>
@endsection