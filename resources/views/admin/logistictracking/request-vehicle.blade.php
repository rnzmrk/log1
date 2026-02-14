@extends('layouts.app')

@section('content')
<div class="container-fluid px-6 py-8">
    <!-- Breadcrumbs -->
    <nav class="flex mb-6" aria-label="Breadcrumb">
        <ol class="inline-flex items-center space-x-1 md:space-x-3">
            <li class="inline-flex items-center">
                <a href="#" class="text-gray-700 hover:text-blue-600 inline-flex items-center">
                    <i class='bx bx-home text-xl mr-2'></i>
                    Home
                </a>
            </li>
            <li>
                <div class="flex items-center">
                    <i class='bx bx-chevron-right text-gray-400'></i>
                    <a href="#" class="text-gray-700 hover:text-blue-600 ml-1 md:ml-2">Logistic Tracking</a>
                </div>
            </li>
            <li>
                <div class="flex items-center">
                    <i class='bx bx-chevron-right text-gray-400'></i>
                    <a href="#" class="text-gray-700 hover:text-blue-600 ml-1 md:ml-2">Request Vehicle</a>
                </div>
            </li>
            <li aria-current="page">
                <div class="flex items-center">
                    <i class='bx bx-chevron-right text-gray-400'></i>
                    <span class="ml-1 text-gray-500 md:ml-2">Vehicle Requests</span>
                </div>
            </li>
        </ol>
    </nav>

    <!-- Page Header -->
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-900">Vehicle Requests</h1>
        <div class="flex gap-3">
            <button class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-medium py-2 px-4 rounded-lg flex items-center gap-2 transition-colors">
                <i class='bx bx-refresh text-xl'></i>
                Refresh
            </button>
            <button class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg flex items-center gap-2 transition-colors" onclick="openNewRequestModal()">
                <i class='bx bx-plus text-xl'></i>
                New Request
            </button>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-6 mb-6">
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Total Requests</p>
                    <p class="text-2xl font-bold text-blue-600" id="totalRequests">0</p>
                    <p class="text-xs text-gray-600 mt-1">All time</p>
                </div>
                <div class="bg-blue-100 rounded-full p-3">
                    <i class='bx bx-car text-blue-600 text-2xl'></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Pending</p>
                    <p class="text-2xl font-bold text-orange-600" id="pendingRequests">0</p>
                    <p class="text-xs text-gray-600 mt-1">Awaiting approval</p>
                </div>
                <div class="bg-orange-100 rounded-full p-3">
                    <i class='bx bx-time text-orange-600 text-2xl'></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">In-Transit</p>
                    <p class="text-2xl font-bold text-purple-600" id="inTransitRequests">0</p>
                    <p class="text-xs text-gray-600 mt-1">Currently in transit</p>
                </div>
                <div class="bg-purple-100 rounded-full p-3">
                    <i class='bx bx-truck text-purple-600 text-2xl'></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Completed</p>
                    <p class="text-2xl font-bold text-green-600" id="completedRequests">0</p>
                    <p class="text-xs text-gray-600 mt-1">Successfully delivered</p>
                </div>
                <div class="bg-green-100 rounded-full p-3">
                    <i class='bx bx-check-circle text-green-600 text-2xl'></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Reserved</p>
                    <p class="text-2xl font-bold text-yellow-600" id="reservedRequests">0</p>
                    <p class="text-xs text-gray-600 mt-1">Vehicle reserved</p>
                </div>
                <div class="bg-yellow-100 rounded-full p-3">
                    <i class='bx bx-bookmark text-yellow-600 text-2xl'></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter Section -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            <!-- Search -->
            <div class="lg:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-2">Search Requests</label>
                <div class="relative">
                    <input type="text" 
                           id="searchInput"
                           placeholder="Search by request ID, driver, vehicle type, or purpose..." 
                           class="w-full pl-10 pr-10 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <i class='bx bx-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400 text-xl'></i>
                    <div id="searchIndicator" class="absolute right-3 top-1/2 transform -translate-y-1/2 hidden">
                        <i class='bx bx-loader-alt animate-spin text-blue-500 text-xl'></i>
                    </div>
                </div>
                <p class="text-xs text-gray-500 mt-1">Type to search automatically</p>
            </div>

            <!-- Status Filter -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                <select id="statusFilter" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <option value="">All Status</option>
                    <option value="pending">Pending</option>
                    <option value="accepted">Accepted</option>
                    <option value="completed">Completed</option>
                    <option value="reserved">Reserved</option>
                    <option value="cancelled">Cancelled</option>
                </select>
            </div>

            <!-- Date Range -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Date Range</label>
                <select id="dateFilter" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <option value="">All Time</option>
                    <option value="today">Today</option>
                    <option value="week">This Week</option>
                    <option value="month">This Month</option>
                    <option value="last7days">Last 7 Days</option>
                    <option value="last30days">Last 30 Days</option>
                </select>
            </div>
        </div>

        <!-- Filter Buttons -->
        <div class="flex gap-3 mt-6">
            <button onclick="applyFilters()" class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-6 rounded-lg transition-colors">
                <i class='bx bx-filter-alt mr-2'></i>
                Apply Filters
            </button>
            <button onclick="clearFilters()" class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-medium py-2 px-6 rounded-lg transition-colors">
                <i class='bx bx-x mr-2'></i>
                Clear
            </button>
        </div>
    </div>

    <!-- Vehicle Requests Table -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
            <h2 class="text-lg font-semibold text-gray-900">Vehicle Requests List</h2>
            <div class="flex gap-2">
                <button onclick="refreshData()" class="text-gray-600 hover:text-gray-900 p-2 rounded-lg hover:bg-gray-100">
                    <i class='bx bx-refresh text-xl'></i>
                </button>
                <button onclick="exportData()" class="text-gray-600 hover:text-gray-900 p-2 rounded-lg hover:bg-gray-100" title="Export to CSV">
                    <i class='bx bx-download text-xl'></i>
                </button>
            </div>
        </div>
        
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Request ID</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Driver</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Vehicle Type</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Purpose</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Start Location</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">End Location</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date/Time</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody id="vehicleRequestsTableBody" class="bg-white divide-y divide-gray-200">
                    <!-- Data will be populated by API -->
                    <tr id="loadingRow">
                        <td colspan="9" class="px-6 py-12 text-center text-gray-500">
                            <i class='bx bx-loader-alt bx-spin text-4xl mb-2'></i>
                            <p>Loading vehicle requests...</p>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="bg-white px-4 py-3 flex items-center justify-between border-t border-gray-200 sm:px-6">
            <div class="flex-1 flex justify-between sm:hidden">
                <button onclick="previousPage()" class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                    Previous
                </button>
                <button onclick="nextPage()" class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                    Next
                </button>
            </div>
            <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                <div>
                    <p class="text-sm text-gray-700">
                        Showing <span class="font-medium" id="showingFrom">0</span> to <span class="font-medium" id="showingTo">0</span> of{' '}
                        <span class="font-medium" id="totalRecords">0</span> results
                    </p>
                </div>
                <div>
                    <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px" aria-label="Pagination">
                        <button onclick="previousPage()" class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
                            <i class='bx bx-chevron-left'></i>
                        </button>
                        <div id="paginationNumbers"></div>
                        <button onclick="nextPage()" class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
                            <i class='bx bx-chevron-right'></i>
                        </button>
                    </nav>
                </div>
            </div>
        </div>
    </div>

    <!-- Vehicle Fleet Status Table -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden mt-6">
        <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
            <h2 class="text-lg font-semibold text-gray-900">Vehicle Fleet Status</h2>
            <div class="flex gap-2">
                <button onclick="refreshVehicleData()" class="text-gray-600 hover:text-gray-900 p-2 rounded-lg hover:bg-gray-100">
                    <i class='bx bx-refresh text-xl'></i>
                </button>
                <button onclick="exportVehicleData()" class="text-gray-600 hover:text-gray-900 p-2 rounded-lg hover:bg-gray-100" title="Export Vehicles to CSV">
                    <i class='bx bx-download text-xl'></i>
                </button>
            </div>
        </div>
        
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Vehicle ID</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">License Plate</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Vehicle Type</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Model</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Engine No</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Chassis No</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Color</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Fuel Type</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody id="vehicleFleetTableBody" class="bg-white divide-y divide-gray-200">
                    <!-- Data will be populated by API -->
                    <tr id="vehicleLoadingRow">
                        <td colspan="10" class="px-6 py-12 text-center text-gray-500">
                            <i class='bx bx-loader-alt bx-spin text-4xl mb-2'></i>
                            <p>Loading vehicle fleet data...</p>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Vehicle Fleet Pagination -->
        <div class="bg-white px-4 py-3 flex items-center justify-between border-t border-gray-200 sm:px-6">
            <div class="flex-1 flex justify-between sm:hidden">
                <button onclick="previousVehiclePage()" class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                    Previous
                </button>
                <button onclick="nextVehiclePage()" class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                    Next
                </button>
            </div>
            <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                <div>
                    <p class="text-sm text-gray-700">
                        Showing <span class="font-medium" id="vehicleShowingFrom">0</span> to <span class="font-medium" id="vehicleShowingTo">0</span> of{' '}
                        <span class="font-medium" id="vehicleTotalRecords">0</span> vehicles
                    </p>
                </div>
                <div>
                    <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px" aria-label="Pagination">
                        <button onclick="previousVehiclePage()" class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
                            <i class='bx bx-chevron-left'></i>
                        </button>
                        <div id="vehiclePaginationNumbers"></div>
                        <button onclick="nextVehiclePage()" class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
                            <i class='bx bx-chevron-right'></i>
                        </button>
                    </nav>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="requestDetailsModal" tabindex="-1" aria-labelledby="requestDetailsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-white border-0 pb-0">
                <div class="w-100">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <h5 class="modal-title fw-bold text-gray-900" id="requestDetailsModalLabel">Request Details</h5>
                            <div class="text-muted small" id="modalRequestId"></div>
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                </div>
            </div>
            <div class="modal-body pt-4">
                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="p-3 rounded-3 border bg-light">
                            <div class="text-uppercase small text-muted fw-semibold">Driver</div>
                            <div class="fw-semibold text-gray-900" id="modalDriver"></div>
                            <div class="small text-muted" id="modalDriverEmail"></div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="p-3 rounded-3 border bg-light">
                            <div class="text-uppercase small text-muted fw-semibold">Vehicle Type</div>
                            <div class="fw-semibold text-gray-900" id="modalVehicleType"></div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="p-3 rounded-3 border bg-light">
                            <div class="text-uppercase small text-muted fw-semibold">Date/Time</div>
                            <div class="fw-semibold text-gray-900" id="modalDateTime"></div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="p-3 rounded-3 border bg-light">
                            <div class="text-uppercase small text-muted fw-semibold">Status</div>
                            <div id="modalStatus"></div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="p-3 rounded-3 border bg-light">
                            <div class="text-uppercase small text-muted fw-semibold">Start Location</div>
                            <div class="fw-semibold text-gray-900" id="modalStartLocation"></div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="p-3 rounded-3 border bg-light">
                            <div class="text-uppercase small text-muted fw-semibold">End Location</div>
                            <div class="fw-semibold text-gray-900" id="modalEndLocation"></div>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="p-3 rounded-3 border bg-white">
                            <div class="text-uppercase small text-muted fw-semibold mb-2">Purpose</div>
                            <div class="text-gray-900" id="modalPurpose"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer border-0 pt-0">
                <button type="button" class="btn btn-primary px-4" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- New Request Modal -->
<div id="newRequestModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-lg bg-white">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-bold text-gray-900">New Vehicle Request</h3>
            <button onclick="closeNewRequestModal()" class="text-gray-400 hover:text-gray-900">
                <i class='bx bx-x text-xl'></i>
            </button>
        </div>
        <form id="newRequestForm">
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Start Location</label>
                <input type="text" name="start_location" required placeholder="Enter start location" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">End Location</label>
                <input type="text" name="end_location" required placeholder="Enter end location" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Reserved By</label>
                <input type="text" name="reserved_by" required placeholder="Enter your name" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Select Vehicle</label>
                <select name="vehicle_id" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">Loading vehicles...</option>
                </select>
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Purpose</label>
                <input type="text" name="purpose" required placeholder="Enter purpose of request" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div class="flex gap-3">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg transition-colors">
                    Submit Request
                </button>
                <button type="button" onclick="closeNewRequestModal()" class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-medium py-2 px-4 rounded-lg transition-colors">
                    Cancel
                </button>
            </div>
        </form>
    </div>
</div>

<script>
// API Configuration
const API_BASE_URL = 'https://log2.imarketph.com/api/dispatch';
const VEHICLE_API_BASE_URL = '/api/logistictracking/vehicle-fleet';
let currentPage = 1;
let itemsPerPage = 5;
let totalItems = 0;
let allRequests = [];
let filteredRequests = [];

// Vehicle Fleet Pagination
let currentVehiclePage = 1;
let vehicleItemsPerPage = 5;
let totalVehicleItems = 0;
let allVehicles = [];

// Initialize page
document.addEventListener('DOMContentLoaded', function() {
    loadVehicleRequests();
    loadVehicleFleet();
    updateStats();
    
    // Add real-time search functionality
    const searchInput = document.getElementById('searchInput');
    const searchIndicator = document.getElementById('searchIndicator');
    
    if (searchInput) {
        searchInput.addEventListener('input', function() {
            // Show loading indicator
            if (searchIndicator) {
                searchIndicator.classList.remove('hidden');
            }
            
            // Debounce search - wait 500ms after typing stops
            clearTimeout(window.searchTimeout);
            window.searchTimeout = setTimeout(() => {
                applyFilters();
                // Hide loading indicator
                if (searchIndicator) {
                    searchIndicator.classList.add('hidden');
                }
            }, 500);
        });
        
        // Also search on Enter key
        searchInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                clearTimeout(window.searchTimeout);
                // Show loading indicator immediately
                if (searchIndicator) {
                    searchIndicator.classList.remove('hidden');
                }
                applyFilters();
                // Hide loading indicator after a short delay
                setTimeout(() => {
                    if (searchIndicator) {
                        searchIndicator.classList.add('hidden');
                    }
                }, 300);
            }
        });
    }
    
    // Add change listeners for other filters
    const statusFilter = document.getElementById('statusFilter');
    const dateFilter = document.getElementById('dateFilter');
    
    if (statusFilter) {
        statusFilter.addEventListener('change', applyFilters);
    }
    
    if (dateFilter) {
        dateFilter.addEventListener('change', applyFilters);
    }
});

// Load vehicle requests from API
async function loadVehicleRequests() {
    try {
        showLoading();
        // Fetch ALL requests without pagination for accurate statistics
        const response = await fetch(API_BASE_URL);
        const data = await response.json();
        
        console.log('Dispatch API Response:', data);
        
        // Transform dispatch data to match expected structure
        allRequests = data.active_dispatches ? data.active_dispatches.map(dispatch => ({
            id: dispatch.dispatch.id,
            driver: dispatch.driver.name,
            driverEmail: dispatch.driver.email,
            vehicleType: dispatch.vehicle.type,
            purpose: dispatch.reservation.purpose,
            requestDate: dispatch.dispatch.dispatch_date + ' ' + dispatch.dispatch.dispatch_time,
            status: dispatch.dispatch.status,
            start_location: dispatch.dispatch.start_location,
            end_location: dispatch.dispatch.end_location,
            reservation_id: dispatch.dispatch.reservation_id,
            vehicle_id: dispatch.dispatch.vehicle_id,
            driver_id: dispatch.dispatch.driver_id
        })) : [];
        
        filteredRequests = [...allRequests]; // Initialize filtered requests
        totalItems = allRequests.length;
        
        // Apply pagination for display but keep all data for statistics
        renderPaginatedTable();
        updatePagination();
        updateStats(); // Update statistics with ALL data
        hideLoading();
    } catch (error) {
        console.error('Error loading vehicle requests:', error);
        showError('Failed to load vehicle requests');
        hideLoading();
    }
}

// Render table with data
function renderTable(requests) {
    const tbody = document.getElementById('vehicleRequestsTableBody');
    
    if (requests.length === 0) {
        tbody.innerHTML = `
            <tr>
                <td colspan="9" class="px-6 py-12 text-center text-gray-500">
                    <i class='bx bx-inbox text-4xl mb-2'></i>
                    <p>No vehicle requests found</p>
                </td>
            </tr>
        `;
        return;
    }
    
    tbody.innerHTML = requests.map(request => `
        <tr class="hover:bg-gray-50">
            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">#${request.id}</td>
            <td class="px-6 py-4 whitespace-nowrap">
                <div class="flex items-center">
                    <div class="h-8 w-8 rounded-full bg-blue-600 flex items-center justify-center text-white text-xs font-bold">
                        ${request.driver.charAt(0)}
                    </div>
                    <div class="ml-3">
                        <div class="text-sm font-medium text-gray-900">${request.driver}</div>
                        <div class="text-xs text-gray-500">${request.driverEmail || 'N/A'}</div>
                    </div>
                </div>
            </td>
            <td class="px-6 py-4 whitespace-nowrap">
                <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                    ${request.vehicleType}
                </span>
            </td>
            <td class="px-6 py-4 text-sm text-gray-900 max-w-xs truncate" title="${request.purpose}">
                ${request.purpose}
            </td>
            <td class="px-6 py-4 text-sm text-gray-900 max-w-xs truncate" title="${request.start_location || 'N/A'}">
                ${request.start_location || 'N/A'}
            </td>
            <td class="px-6 py-4 text-sm text-gray-900 max-w-xs truncate" title="${request.end_location || 'N/A'}">
                ${request.end_location || 'N/A'}
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                ${formatDate(request.requestDate)}
            </td>
            <td class="px-6 py-4 whitespace-nowrap">
                <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full ${getStatusClass(request.status)}">
                    ${request.status}
                </span>
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                <div class="flex space-x-2">
                    <button onclick="viewRequest('${request.id}')" class="text-blue-600 hover:text-blue-900" title="View">
                        <i class='bx bx-show text-lg'></i>
                    </button>
                </div>
            </td>
        </tr>
    `).join('');
}

// Update statistics
function updateStats() {
    console.log('=== UPDATING VEHICLE REQUEST STATISTICS ===');
    console.log('Total allRequests:', allRequests.length);
    console.log('All requests data:', allRequests);
    
    if (!allRequests || allRequests.length === 0) {
        console.log('No requests data available');
        document.getElementById('totalRequests').textContent = '0';
        document.getElementById('pendingRequests').textContent = '0';
        document.getElementById('inTransitRequests').textContent = '0';
        document.getElementById('completedRequests').textContent = '0';
        document.getElementById('reservedRequests').textContent = '0';
        return;
    }
    
    // Count requests by status based on actual API dispatch.status values
    const total = allRequests.length;
    const pending = allRequests.filter(r => r.status === 'pending').length;
    const inTransit = allRequests.filter(r => r.status === 'accepted' || r.status === 'In-Transit').length;
    const completed = allRequests.filter(r => r.status === 'completed').length;
    const reserved = allRequests.filter(r => r.status === 'Reserved').length;
    
    // Also count other possible statuses for debugging
    const approved = allRequests.filter(r => r.status === 'Approved').length;
    const active = allRequests.filter(r => r.status === 'Active').length;
    const cancelled = allRequests.filter(r => r.status === 'Cancelled').length;
    
    // Log all unique statuses found in the data
    const uniqueStatuses = [...new Set(allRequests.map(r => r.status))];
    console.log('Unique statuses found in API data:', uniqueStatuses);
    
    // Count each status for debugging
    const statusCounts = {};
    allRequests.forEach(r => {
        statusCounts[r.status] = (statusCounts[r.status] || 0) + 1;
    });
    console.log('Status counts breakdown:', statusCounts);
    
    // Update DOM elements with animation
    const updateElement = (id, value) => {
        const element = document.getElementById(id);
        if (element) {
            element.textContent = value;
            element.style.transform = 'scale(1.1)';
            setTimeout(() => {
                element.style.transform = 'scale(1)';
            }, 200);
        }
    };
    
    updateElement('totalRequests', total);
    updateElement('pendingRequests', pending);
    updateElement('inTransitRequests', inTransit);
    updateElement('completedRequests', completed);
    updateElement('reservedRequests', reserved);
    
    console.log('=== FINAL STATISTICS ===');
    console.log('Total Requests:', total);
    console.log('Pending:', pending);
    console.log('In-Transit:', inTransit);
    console.log('Completed:', completed);
    console.log('Reserved:', reserved);
    console.log('Approved:', approved);
    console.log('Active:', active);
    console.log('Cancelled:', cancelled);
    console.log('========================');
}

// Utility functions
function getStatusClass(status) {
    const classes = {
        // Dispatch API status values (lowercase)
        'pending': 'bg-orange-100 text-orange-800',
        'accepted': 'bg-blue-100 text-blue-800',
        'completed': 'bg-green-100 text-green-800',
        'reserved': 'bg-yellow-100 text-yellow-800',
        'cancelled': 'bg-red-100 text-red-800',
        
        // Fallback for capitalized variations
        'Pending': 'bg-orange-100 text-orange-800',
        'Approved': 'bg-green-100 text-green-800',
        'Rejected': 'bg-red-100 text-red-800',
        'Active': 'bg-blue-100 text-blue-800',
        'Completed': 'bg-green-100 text-green-800',
        'In-Transit': 'bg-purple-100 text-purple-800',
        'Reserved': 'bg-yellow-100 text-yellow-800',
        'Cancelled': 'bg-red-100 text-red-800',
        
        // Additional fallbacks
        pending: 'bg-orange-100 text-orange-800',
        approved: 'bg-green-100 text-green-800',
        approve: 'bg-green-100 text-green-800',
        rejected: 'bg-red-100 text-red-800',
        reject: 'bg-red-100 text-red-800',
        active: 'bg-blue-100 text-blue-800',
        completed: 'bg-green-100 text-green-800',
        complete: 'bg-green-100 text-green-800',
        'in-transit': 'bg-purple-100 text-purple-800',
        'in transit': 'bg-purple-100 text-purple-800',
        reserved: 'bg-yellow-100 text-yellow-800',
        cancelled: 'bg-red-100 text-red-800',
        cancel: 'bg-red-100 text-red-800'
    };
    
    return classes[status] || 'bg-gray-100 text-gray-800';
}

function formatDate(dateString) {
    const date = new Date(dateString);
    return date.toLocaleDateString() + ' ' + date.toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'});
}

function showLoading() {
    const el = document.getElementById('loadingRow');
    if (!el) {
        return;
    }
    el.style.display = 'table-row';
}

function hideLoading() {
    const el = document.getElementById('loadingRow');
    if (!el) {
        return;
    }
    el.style.display = 'none';
}

function showError(message) {
    const tbody = document.getElementById('vehicleRequestsTableBody');
    tbody.innerHTML = `
        <tr>
            <td colspan="7" class="px-6 py-12 text-center text-red-500">
                <i class='bx bx-error text-4xl mb-2'></i>
                <p>${message}</p>
            </td>
        </tr>
    `;
}

// Modal functions
function openNewRequestModal() {
    document.getElementById('newRequestModal').classList.remove('hidden');
    loadVehiclesForRequest();
}

function closeNewRequestModal() {
    document.getElementById('newRequestModal').classList.add('hidden');
    document.getElementById('newRequestForm').reset();
}

// Load vehicles for the new request form
async function loadVehiclesForRequest() {
    try {
        const response = await fetch(VEHICLE_API_BASE_URL);
        const data = await response.json();
        
        const vehicleSelect = document.querySelector('select[name="vehicle_id"]');
        if (vehicleSelect) {
            vehicleSelect.innerHTML = '<option value="">Select a vehicle...</option>';
            
            if (data.data && data.data.length > 0) {
                data.data.forEach(vehicle => {
                    const option = document.createElement('option');
                    option.value = vehicle.id;
                    option.textContent = `${vehicle.licensePlate || vehicle.plate_no} - ${vehicle.vehicleType || vehicle.type} (${vehicle.model || 'N/A'})`;
                    vehicleSelect.appendChild(option);
                });
            } else {
                vehicleSelect.innerHTML = '<option value="">No vehicles available</option>';
            }
        }
    } catch (error) {
        console.error('Error loading vehicles:', error);
        const vehicleSelect = document.querySelector('select[name="vehicle_id"]');
        if (vehicleSelect) {
            vehicleSelect.innerHTML = '<option value="">Failed to load vehicles</option>';
        }
    }
}

// Handle new request form submission
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('newRequestForm');
    if (form) {
        form.addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const formData = new FormData(form);
            const requestData = {
                start_location: formData.get('start_location'),
                end_location: formData.get('end_location'),
                reserved_by: formData.get('reserved_by'),
                vehicle_id: formData.get('vehicle_id'),
                purpose: formData.get('purpose'),
                start_time: new Date().toISOString(),
                end_time: new Date().toISOString()
            };
            
            try {
                // Show loading state
                const submitButton = form.querySelector('button[type="submit"]');
                const originalText = submitButton.textContent;
                submitButton.textContent = 'Submitting...';
                submitButton.disabled = true;
                
                // Post to local API endpoint
                const response = await fetch('/api/logistictracking/create-dispatch', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify(requestData)
                });
                
                if (response.ok) {
                    // Success
                    closeNewRequestModal();
                    loadVehicleRequests(); // Refresh the requests list
                    updateStats(); // Update statistics
                    
                    // Show success message
                    alert('Vehicle request submitted successfully!');
                } else {
                    // Error
                    const errorData = await response.json().catch(() => ({}));
                    throw new Error(errorData.message || errorData.error || `HTTP ${response.status}: ${response.statusText}`);
                }
                
            } catch (error) {
                console.error('Error submitting request:', error);
                alert(`Failed to submit request: ${error.message}`);
            } finally {
                // Reset button state
                const submitButton = form.querySelector('button[type="submit"]');
                if (submitButton) {
                    submitButton.textContent = 'Submit Request';
                    submitButton.disabled = false;
                }
            }
        });
    }
});

// CRUD operations
function viewRequest(id) {
    const request = allRequests.find(r => r.id == id);
    if (!request) {
        alert('Request not found');
        return;
    }

    document.getElementById('modalRequestId').textContent = `#${request.id}`;
    document.getElementById('modalDriver').textContent = request.driver || 'N/A';
    document.getElementById('modalDriverEmail').textContent = request.driverEmail || 'N/A';
    document.getElementById('modalVehicleType').textContent = request.vehicleType || 'N/A';
    document.getElementById('modalPurpose').textContent = request.purpose || 'N/A';
    document.getElementById('modalDateTime').textContent = formatDate(request.requestDate);
    document.getElementById('modalStartLocation').textContent = request.start_location || 'N/A';
    document.getElementById('modalEndLocation').textContent = request.end_location || 'N/A';

    const statusEl = document.getElementById('modalStatus');
    statusEl.innerHTML = `
        <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full ${getStatusClass(request.status)}">
            ${request.status || 'N/A'}
        </span>
    `;

    const modalEl = document.getElementById('requestDetailsModal');
    if (window.bootstrap && window.bootstrap.Modal) {
        const modal = window.bootstrap.Modal.getOrCreateInstance(modalEl);
        modal.show();
    } else {
        alert('Bootstrap is not loaded.');
    }
}

// Filter functions
function applyFilters() {
    const search = document.getElementById('searchInput').value.toLowerCase();
    const status = document.getElementById('statusFilter').value;
    const dateRange = document.getElementById('dateFilter').value;
    
    console.log('Applying filters:', { search, status, dateRange });
    
    filteredRequests = allRequests.filter(request => {
        // Search filter - check ID, driver, vehicle type, purpose, and locations
        const matchesSearch = !search || 
            request.id.toString().includes(search) ||
            (request.driver && request.driver.toLowerCase().includes(search)) ||
            (request.vehicleType && request.vehicleType.toLowerCase().includes(search)) ||
            (request.purpose && request.purpose.toLowerCase().includes(search)) ||
            (request.start_location && request.start_location.toLowerCase().includes(search)) ||
            (request.end_location && request.end_location.toLowerCase().includes(search));
        
        // Status filter - exact match with API status values
        const matchesStatus = !status || request.status === status;
        
        // Enhanced date range filter
        let matchesDate = true;
        if (dateRange && request.requestDate) {
            const requestDate = new Date(request.requestDate);
            const today = new Date();
            today.setHours(0, 0, 0, 0); // Set to start of day for accurate comparison
            
            switch(dateRange) {
                case 'today':
                    const startOfDay = new Date(today);
                    const endOfDay = new Date(today);
                    endOfDay.setHours(23, 59, 59, 999);
                    matchesDate = requestDate >= startOfDay && requestDate <= endOfDay;
                    break;
                    
                case 'week':
                    const startOfWeek = new Date(today);
                    startOfWeek.setDate(today.getDate() - today.getDay()); // Start of week (Sunday)
                    startOfWeek.setHours(0, 0, 0, 0);
                    
                    const endOfWeek = new Date(startOfWeek);
                    endOfWeek.setDate(startOfWeek.getDate() + 6); // End of week (Saturday)
                    endOfWeek.setHours(23, 59, 59, 999);
                    
                    matchesDate = requestDate >= startOfWeek && requestDate <= endOfWeek;
                    break;
                    
                case 'month':
                    const startOfMonth = new Date(today.getFullYear(), today.getMonth(), 1);
                    startOfMonth.setHours(0, 0, 0, 0);
                    
                    const endOfMonth = new Date(today.getFullYear(), today.getMonth() + 1, 0);
                    endOfMonth.setHours(23, 59, 59, 999);
                    
                    matchesDate = requestDate >= startOfMonth && requestDate <= endOfMonth;
                    break;
                    
                case 'last7days':
                    const sevenDaysAgo = new Date(today);
                    sevenDaysAgo.setDate(today.getDate() - 7);
                    sevenDaysAgo.setHours(0, 0, 0, 0);
                    
                    matchesDate = requestDate >= sevenDaysAgo && requestDate <= endOfDay;
                    break;
                    
                case 'last30days':
                    const thirtyDaysAgo = new Date(today);
                    thirtyDaysAgo.setDate(today.getDate() - 30);
                    thirtyDaysAgo.setHours(0, 0, 0, 0);
                    
                    matchesDate = requestDate >= thirtyDaysAgo && requestDate <= endOfDay;
                    break;
            }
        }
        
        return matchesSearch && matchesStatus && matchesDate;
    });
    
    console.log('Filtered results:', filteredRequests.length, 'out of', allRequests.length);
    
    // Reset to first page when filtering
    currentPage = 1;
    totalItems = filteredRequests.length;
    
    // Apply pagination to filtered results
    renderPaginatedTable();
    
    // Show filter feedback
    if (filteredRequests.length === 0) {
        const tableBody = document.querySelector('#requestTable tbody');
        if (tableBody) {
            tableBody.innerHTML = `
                <tr>
                    <td colspan="7" class="text-center py-8 text-gray-500">
                        <i class='bx bx-search text-4xl text-gray-300 mb-2'></i>
                        <p>No requests found matching your filters</p>
                        <button onclick="clearFilters()" class="mt-2 text-blue-600 hover:text-blue-800 text-sm underline">
                            Clear filters
                        </button>
                    </td>
                </tr>
            `;
        }
    }
}

function clearFilters() {
    console.log('Clearing all filters');
    
    document.getElementById('searchInput').value = '';
    document.getElementById('statusFilter').value = '';
    document.getElementById('dateFilter').value = '';
    
    // Reset filtered requests to all requests
    filteredRequests = [...allRequests];
    currentPage = 1;
    totalItems = allRequests.length;
    
    renderPaginatedTable();
}

// Utility functions
function refreshData() {
    loadVehicleRequests();
    updateStats();
}

function exportData() {
    try {
        // Export filtered data if filters are applied, otherwise export all data
        const dataToExport = filteredRequests.length > 0 ? filteredRequests : allRequests;
        
        if (dataToExport.length === 0) {
            alert('No data to export');
            return;
        }
        
        console.log('Exporting vehicle request data:', dataToExport.length, 'records');
        
        // Create CSV content with proper formatting
        const headers = ['Request ID', 'Driver', 'Email', 'Vehicle Type', 'Purpose', 'Request Date', 'Status'];
        const csvRows = [headers];
        
        dataToExport.forEach(request => {
            const row = [
                request.id || '',
                `"${(request.driver || '').replace(/"/g, '""')}"`, // Escape quotes
                `"${(request.driverEmail || '').replace(/"/g, '""')}"`,
                `"${(request.vehicleType || '').replace(/"/g, '""')}"`,
                `"${(request.purpose || '').replace(/"/g, '""')}"`,
                `"${formatDate(request.requestDate || '').replace(/"/g, '""')}"`,
                `"${(request.status || '').replace(/"/g, '""')}"`
            ];
            csvRows.push(row.join(','));
        });
        
        const csvContent = csvRows.join('\n');
        
        // Add UTF-8 BOM for proper Excel display
        const BOM = '\uFEFF';
        const csvData = BOM + csvContent;
        
        // Create blob with correct MIME type
        const blob = new Blob([csvData], { type: 'text/csv;charset=utf-8;' });
        
        // Create download link
        const link = document.createElement('a');
        const url = URL.createObjectURL(blob);
        
        link.setAttribute('href', url);
        link.setAttribute('download', `vehicle_requests_${new Date().toISOString().split('T')[0]}.csv`);
        link.style.display = 'none';
        
        // Add to document and trigger download
        document.body.appendChild(link);
        
        // Trigger click event
        if (link.click) {
            link.click();
        } else {
            // Fallback for older browsers
            const event = new MouseEvent('click', {
                view: window,
                bubbles: true,
                cancelable: true
            });
            link.dispatchEvent(event);
        }
        
        // Cleanup
        setTimeout(() => {
            document.body.removeChild(link);
            URL.revokeObjectURL(url);
        }, 100);
        
        // Show success message
        const exportButton = document.querySelector('button[onclick="exportData()"]');
        if (exportButton) {
            const originalHTML = exportButton.innerHTML;
            exportButton.innerHTML = '<i class="bx bx-check text-xl"></i>';
            exportButton.classList.remove('text-gray-600');
            exportButton.classList.add('text-green-600');
            
            setTimeout(() => {
                exportButton.innerHTML = originalHTML;
                exportButton.classList.remove('text-green-600');
                exportButton.classList.add('text-gray-600');
            }, 2000);
        }
        
        console.log('Vehicle requests CSV exported successfully:', dataToExport.length, 'records');
        
    } catch (error) {
        console.error('Export error:', error);
        alert('Failed to export data. Please try again.');
    }
}

// Pagination functions
function updatePagination() {
    const totalPages = Math.ceil(totalItems / itemsPerPage);
    const paginationNumbers = document.getElementById('paginationNumbers');
    
    // Update showing info
    const start = (currentPage - 1) * itemsPerPage + 1;
    const end = Math.min(currentPage * itemsPerPage, totalItems);
    
    document.getElementById('showingFrom').textContent = allRequests.length > 0 ? start : 0;
    document.getElementById('showingTo').textContent = end;
    document.getElementById('totalRecords').textContent = totalItems;
    
    // Generate pagination numbers
    let paginationHTML = '';
    for (let i = 1; i <= totalPages; i++) {
        if (i === currentPage) {
            paginationHTML += `
                <button class="relative inline-flex items-center px-4 py-2 border border-blue-500 bg-blue-50 text-sm font-medium text-blue-600">
                    ${i}
                </button>
            `;
        } else {
            paginationHTML += `
                <button onclick="goToPage(${i})" class="relative inline-flex items-center px-4 py-2 border border-gray-300 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50">
                    ${i}
                </button>
            `;
        }
    }
    paginationNumbers.innerHTML = paginationHTML;
}

function goToPage(page) {
    currentPage = page;
    renderPaginatedTable();
}

function previousPage() {
    if (currentPage > 1) {
        currentPage--;
        renderPaginatedTable();
    }
}

function nextPage() {
    const totalPages = Math.ceil(totalItems / itemsPerPage);
    if (currentPage < totalPages) {
        currentPage++;
        renderPaginatedTable();
    }
}

function renderPaginatedTable() {
    // Apply pagination for display using filtered requests
    const dataToPaginate = filteredRequests.length > 0 ? filteredRequests : allRequests;
    const startIndex = (currentPage - 1) * itemsPerPage;
    const endIndex = startIndex + itemsPerPage;
    const paginatedRequests = dataToPaginate.slice(startIndex, endIndex);
    
    renderTable(paginatedRequests);
    updatePagination();
}

// Vehicle Fleet Functions
// Load vehicle fleet from API
async function loadVehicleFleet() {
    try {
        showVehicleLoading();
        const response = await fetch(`${VEHICLE_API_BASE_URL}?page=${currentVehiclePage}&limit=${vehicleItemsPerPage}`);
        const data = await response.json();
        
        allVehicles = data.data || [];
        totalVehicleItems = data.total || 0;
        
        renderVehicleTable(allVehicles);
        updateVehiclePagination();
        hideVehicleLoading();
    } catch (error) {
        console.error('Error loading vehicle fleet:', error);
        showVehicleError('Failed to load vehicle fleet data');
        hideVehicleLoading();
    }
}

// Render vehicle fleet table
function renderVehicleTable(vehicles) {
    const tbody = document.getElementById('vehicleFleetTableBody');
    
    if (vehicles.length === 0) {
        tbody.innerHTML = `
            <tr>
                <td colspan="10" class="px-6 py-12 text-center text-gray-500">
                    <i class='bx bx-inbox text-4xl mb-2'></i>
                    <p>No vehicles found</p>
                </td>
            </tr>
        `;
        return;
    }
    
    tbody.innerHTML = vehicles.map(vehicle => `
        <tr class="hover:bg-gray-50">
            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">#${vehicle.id}</td>
            <td class="px-6 py-4 whitespace-nowrap">
                <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                    ${vehicle.licensePlate}
                </span>
            </td>
            <td class="px-6 py-4 whitespace-nowrap">
                <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                    ${vehicle.vehicleType}
                </span>
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                ${vehicle.model || 'N/A'}
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${vehicle.engine_no || 'N/A'}</td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${vehicle.chassis_no || 'N/A'}</td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${vehicle.color || 'N/A'}</td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${vehicle.fuel_type || 'N/A'}</td>
            <td class="px-6 py-4 whitespace-nowrap">
                <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full ${getVehicleStatusClass(vehicle.status)}">
                    ${vehicle.status_raw || vehicle.status}
                </span>
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                <div class="flex space-x-2">
                    <button onclick="viewVehicle('${vehicle.id}')" class="text-blue-600 hover:text-blue-900" title="View">
                        <i class='bx bx-show text-lg'></i>
                    </button>
                </div>
            </td>
        </tr>
    `).join('');
}

// Get vehicle status class for styling
function getVehicleStatusClass(status) {
    const classes = {
        available: 'bg-green-100 text-green-800',
        'in-use': 'bg-blue-100 text-blue-800',
        maintenance: 'bg-yellow-100 text-yellow-800',
        'out-of-service': 'bg-red-100 text-red-800',
        reserved: 'bg-purple-100 text-purple-800'
    };
    return classes[status] || 'bg-gray-100 text-gray-800';
}

// Vehicle loading states
function showVehicleLoading() {
    const el = document.getElementById('vehicleLoadingRow');
    if (!el) {
        return;
    }
    el.style.display = 'table-row';
}

function hideVehicleLoading() {
    const el = document.getElementById('vehicleLoadingRow');
    if (!el) {
        return;
    }
    el.style.display = 'none';
}

function showVehicleError(message) {
    const tbody = document.getElementById('vehicleFleetTableBody');
    tbody.innerHTML = `
        <tr>
            <td colspan="10" class="px-6 py-12 text-center text-red-500">
                <i class='bx bx-error text-4xl mb-2'></i>
                <p>${message}</p>
            </td>
        </tr>
    `;
}

// Vehicle pagination functions
function updateVehiclePagination() {
    const totalPages = Math.ceil(totalVehicleItems / vehicleItemsPerPage);
    const paginationNumbers = document.getElementById('vehiclePaginationNumbers');
    
    // Update showing info
    const start = (currentVehiclePage - 1) * vehicleItemsPerPage + 1;
    const end = Math.min(currentVehiclePage * vehicleItemsPerPage, totalVehicleItems);
    
    document.getElementById('vehicleShowingFrom').textContent = allVehicles.length > 0 ? start : 0;
    document.getElementById('vehicleShowingTo').textContent = end;
    document.getElementById('vehicleTotalRecords').textContent = totalVehicleItems;
    
    // Generate pagination numbers
    let paginationHTML = '';
    for (let i = 1; i <= totalPages; i++) {
        if (i === currentVehiclePage) {
            paginationHTML += `
                <button class="relative inline-flex items-center px-4 py-2 border border-blue-500 bg-blue-50 text-sm font-medium text-blue-600">
                    ${i}
                </button>
            `;
        } else {
            paginationHTML += `
                <button onclick="goToVehiclePage(${i})" class="relative inline-flex items-center px-4 py-2 border border-gray-300 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50">
                    ${i}
                </button>
            `;
        }
    }
    paginationNumbers.innerHTML = paginationHTML;
}

function goToVehiclePage(page) {
    currentVehiclePage = page;
    loadVehicleFleet();
}

function previousVehiclePage() {
    if (currentVehiclePage > 1) {
        currentVehiclePage--;
        loadVehicleFleet();
    }
}

function nextVehiclePage() {
    const totalPages = Math.ceil(totalVehicleItems / vehicleItemsPerPage);
    if (currentVehiclePage < totalPages) {
        currentVehiclePage++;
        loadVehicleFleet();
    }
}

// Vehicle CRUD operations
function viewRequest(id) {
    // Find the request by ID
    const request = allRequests.find(r => r.id == id);
    
    if (!request) {
        console.error('Request not found:', id);
        return;
    }
    
    console.log('Viewing request:', request);
    
    // Update modal content with request details
    const modalBody = document.getElementById('viewRequestModalBody');
    if (modalBody) {
        modalBody.innerHTML = `
            <div class="space-y-4">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Request ID</label>
                        <p class="mt-1 text-sm text-gray-900">#${request.id}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Status</label>
                        <span class="mt-1 inline-flex px-2 py-1 text-xs leading-5 font-semibold rounded-full ${getStatusClass(request.status)}">
                            ${request.status}
                        </span>
                    </div>
                </div>
                
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Driver Name</label>
                        <p class="mt-1 text-sm text-gray-900">${request.driver || 'N/A'}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Driver Email</label>
                        <p class="mt-1 text-sm text-gray-900">${request.driverEmail || 'N/A'}</p>
                    </div>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700">Vehicle Type</label>
                    <p class="mt-1 text-sm text-gray-900">${request.vehicleType || 'N/A'}</p>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700">Purpose</label>
                    <p class="mt-1 text-sm text-gray-900">${request.purpose || 'N/A'}</p>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700">Request Date</label>
                    <p class="mt-1 text-sm text-gray-900">${formatDate(request.requestDate)}</p>
                </div>
                
                ${request.duration ? `
                <div>
                    <label class="block text-sm font-medium text-gray-700">Duration</label>
                    <p class="mt-1 text-sm text-gray-900">${request.duration}</p>
                </div>
                ` : ''}
            </div>
        `;
    }
    
    // Show the modal using Bootstrap
    const modal = document.getElementById('viewRequestModal');
    if (modal && typeof bootstrap !== 'undefined') {
        const bsModal = new bootstrap.Modal(modal);
        bsModal.show();
    } else {
        // Fallback if Bootstrap is not loaded
        if (modal) {
            modal.style.display = 'block';
            modal.classList.add('show');
        }
    }
}

function viewVehicle(id) {
    // Find the vehicle by ID
    const vehicle = allVehicles.find(v => v.id == id);
    
    if (!vehicle) {
        console.error('Vehicle not found:', id);
        return;
    }
    
    console.log('Viewing vehicle:', vehicle);
    
    // Update modal content with vehicle details
    const modalBody = document.getElementById('viewVehicleModalBody');
    if (modalBody) {
        modalBody.innerHTML = `
            <div class="space-y-4">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Vehicle ID</label>
                        <p class="mt-1 text-sm text-gray-900">#${vehicle.id}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Status</label>
                        <span class="mt-1 inline-flex px-2 py-1 text-xs leading-5 font-semibold rounded-full ${getVehicleStatusClass(vehicle.status)}">
                            ${vehicle.status_raw || vehicle.status}
                        </span>
                    </div>
                </div>
                
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">License Plate</label>
                        <p class="mt-1 text-sm text-gray-900">${vehicle.licensePlate || vehicle.plate_no || 'N/A'}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Vehicle Type</label>
                        <p class="mt-1 text-sm text-gray-900">${vehicle.vehicleType || vehicle.type || 'N/A'}</p>
                    </div>
                </div>
                
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Model</label>
                        <p class="mt-1 text-sm text-gray-900">${vehicle.model || 'N/A'}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Color</label>
                        <p class="mt-1 text-sm text-gray-900">${vehicle.color || 'N/A'}</p>
                    </div>
                </div>
                
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Engine Number</label>
                        <p class="mt-1 text-sm text-gray-900">${vehicle.engine_no || 'N/A'}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Chassis Number</label>
                        <p class="mt-1 text-sm text-gray-900">${vehicle.chassis_no || 'N/A'}</p>
                    </div>
                </div>
                
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Fuel Type</label>
                        <p class="mt-1 text-sm text-gray-900">${vehicle.fuel_type || 'N/A'}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Driver</label>
                        <p class="mt-1 text-sm text-gray-900">${vehicle.driver || 'Unassigned'}</p>
                    </div>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700">Last Maintenance</label>
                    <p class="mt-1 text-sm text-gray-900">${formatDate(vehicle.lastMaintenance)}</p>
                </div>
            </div>
        `;
    }
    
    // Show the modal using Bootstrap
    const modal = document.getElementById('viewVehicleModal');
    if (modal && typeof bootstrap !== 'undefined') {
        const bsModal = new bootstrap.Modal(modal);
        bsModal.show();
    } else {
        // Fallback if Bootstrap is not loaded
        if (modal) {
            modal.style.display = 'block';
            modal.classList.add('show');
        }
    }
}

function editVehicle(id) {
    console.log('Edit vehicle:', id);
}

// Vehicle utility functions
function refreshVehicleData() {
    loadVehicleFleet();
}

function exportVehicleData() {
    try {
        // Export all vehicles (vehicle fleet doesn't have filtering in this view)
        const dataToExport = allVehicles || [];
        
        if (dataToExport.length === 0) {
            alert('No vehicle data to export');
            return;
        }
        
        console.log('Exporting vehicle fleet data:', dataToExport.length, 'records');
        
        // Create CSV content with proper formatting
        const headers = ['Vehicle ID', 'License Plate', 'Vehicle Type', 'Model', 'Engine No', 'Chassis No', 'Color', 'Fuel Type', 'Status', 'Driver', 'Last Maintenance'];
        const csvRows = [headers];
        
        dataToExport.forEach(vehicle => {
            const row = [
                vehicle.id || '',
                `"${(vehicle.licensePlate || vehicle.plate_no || '').replace(/"/g, '""')}"`, // Escape quotes
                `"${(vehicle.vehicleType || vehicle.type || '').replace(/"/g, '""')}"`,
                `"${(vehicle.model || '').replace(/"/g, '""')}"`,
                `"${(vehicle.engine_no || '').replace(/"/g, '""')}"`,
                `"${(vehicle.chassis_no || '').replace(/"/g, '""')}"`,
                `"${(vehicle.color || '').replace(/"/g, '""')}"`,
                `"${(vehicle.fuel_type || '').replace(/"/g, '""')}"`,
                `"${(vehicle.status || vehicle.status_raw || '').replace(/"/g, '""')}"`,
                `"${(vehicle.driver || '').replace(/"/g, '""')}"`,
                `"${formatDate(vehicle.lastMaintenance || '').replace(/"/g, '""')}"`
            ];
            csvRows.push(row.join(','));
        });
        
        const csvContent = csvRows.join('\n');
        
        // Add UTF-8 BOM for proper Excel display
        const BOM = '\uFEFF';
        const csvData = BOM + csvContent;
        
        // Create blob with correct MIME type
        const blob = new Blob([csvData], { type: 'text/csv;charset=utf-8;' });
        
        // Create download link
        const link = document.createElement('a');
        const url = URL.createObjectURL(blob);
        
        link.setAttribute('href', url);
        link.setAttribute('download', `vehicle_fleet_${new Date().toISOString().split('T')[0]}.csv`);
        link.style.display = 'none';
        
        // Add to document and trigger download
        document.body.appendChild(link);
        
        // Trigger click event
        if (link.click) {
            link.click();
        } else {
            // Fallback for older browsers
            const event = new MouseEvent('click', {
                view: window,
                bubbles: true,
                cancelable: true
            });
            link.dispatchEvent(event);
        }
        
        // Cleanup
        setTimeout(() => {
            document.body.removeChild(link);
            URL.revokeObjectURL(url);
        }, 100);
        
        // Show success message
        const exportButton = document.querySelector('button[onclick="exportVehicleData()"]');
        if (exportButton) {
            const originalHTML = exportButton.innerHTML;
            exportButton.innerHTML = '<i class="bx bx-check text-xl"></i>';
            exportButton.classList.remove('text-gray-600');
            exportButton.classList.add('text-green-600');
            
            setTimeout(() => {
                exportButton.innerHTML = originalHTML;
                exportButton.classList.remove('text-green-600');
                exportButton.classList.add('text-gray-600');
            }, 2000);
        }
        
        console.log('Vehicle fleet CSV exported successfully:', dataToExport.length, 'records');
        
    } catch (error) {
        console.error('Vehicle export error:', error);
        alert('Failed to export vehicle data. Please try again.');
    }
}
</script>

<!-- View Request Modal -->
<div class="modal fade" id="viewRequestModal" tabindex="-1" aria-labelledby="viewRequestModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="viewRequestModalLabel">Vehicle Request Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="viewRequestModalBody">
                <!-- Request details will be inserted here -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- View Vehicle Modal -->
<div class="modal fade" id="viewVehicleModal" tabindex="-1" aria-labelledby="viewVehicleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="viewVehicleModalLabel">Vehicle Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="viewVehicleModalBody">
                <!-- Vehicle details will be inserted here -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<style>
/* Statistics card animations */
.text-2xl {
    transition: transform 0.2s ease-in-out;
}
</style>
@endsection
