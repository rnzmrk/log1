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
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
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
                    <p class="text-sm font-medium text-gray-600">Approved</p>
                    <p class="text-2xl font-bold text-green-600" id="approvedRequests">0</p>
                    <p class="text-xs text-gray-600 mt-1">Ready for dispatch</p>
                </div>
                <div class="bg-green-100 rounded-full p-3">
                    <i class='bx bx-check-circle text-green-600 text-2xl'></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Active</p>
                    <p class="text-2xl font-bold text-purple-600" id="activeRequests">0</p>
                    <p class="text-xs text-gray-600 mt-1">Currently in use</p>
                </div>
                <div class="bg-purple-100 rounded-full p-3">
                    <i class='bx bx-play-circle text-purple-600 text-2xl'></i>
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
                           placeholder="Search by request ID, driver, or vehicle..." 
                           class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <i class='bx bx-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400 text-xl'></i>
                </div>
            </div>

            <!-- Status Filter -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                <select id="statusFilter" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <option value="">All Status</option>
                    <option value="pending">Pending</option>
                    <option value="approved">Approved</option>
                    <option value="rejected">Rejected</option>
                    <option value="active">Active</option>
                    <option value="completed">Completed</option>
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
                    <option value="year">This Year</option>
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
                <button onclick="exportData()" class="text-gray-600 hover:text-gray-900 p-2 rounded-lg hover:bg-gray-100">
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
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date/Time</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Duration</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody id="vehicleRequestsTableBody" class="bg-white divide-y divide-gray-200">
                    <!-- Data will be populated by API -->
                    <tr id="loadingRow">
                        <td colspan="8" class="px-6 py-12 text-center text-gray-500">
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
                <button onclick="exportVehicleData()" class="text-gray-600 hover:text-gray-900 p-2 rounded-lg hover:bg-gray-100">
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
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Driver</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Last Maintenance</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody id="vehicleFleetTableBody" class="bg-white divide-y divide-gray-200">
                    <!-- Data will be populated by API -->
                    <tr id="vehicleLoadingRow">
                        <td colspan="8" class="px-6 py-12 text-center text-gray-500">
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
                <label class="block text-sm font-medium text-gray-700 mb-2">Driver Name</label>
                <input type="text" name="driver" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Vehicle Type</label>
                <select name="vehicleType" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">Select Vehicle Type</option>
                    <option value="sedan">Sedan</option>
                    <option value="suv">SUV</option>
                    <option value="van">Van</option>
                    <option value="truck">Truck</option>
                    <option value="motorcycle">Motorcycle</option>
                </select>
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Purpose</label>
                <textarea name="purpose" required rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
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
const API_BASE_URL = '/api/logistictracking/vehicle-requests';
const VEHICLE_API_BASE_URL = '/api/logistictracking/vehicle-fleet';
let currentPage = 1;
let itemsPerPage = 10;
let totalItems = 0;
let allRequests = [];

// Vehicle Fleet Pagination
let currentVehiclePage = 1;
let vehicleItemsPerPage = 10;
let totalVehicleItems = 0;
let allVehicles = [];

// Initialize page
document.addEventListener('DOMContentLoaded', function() {
    loadVehicleRequests();
    loadVehicleFleet();
    updateStats();
});

// Load vehicle requests from API
async function loadVehicleRequests() {
    try {
        showLoading();
        const response = await fetch(`${API_BASE_URL}?page=${currentPage}&limit=${itemsPerPage}`);
        const data = await response.json();
        
        allRequests = data.data || [];
        totalItems = data.total || 0;
        
        renderTable(allRequests);
        updatePagination();
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
                <td colspan="8" class="px-6 py-12 text-center text-gray-500">
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
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                ${formatDate(request.requestDate)}
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                ${request.duration || 'N/A'}
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
                    <button onclick="editRequest('${request.id}')" class="text-green-600 hover:text-green-900" title="Edit">
                        <i class='bx bx-edit text-lg'></i>
                    </button>
                    <button onclick="deleteRequest('${request.id}')" class="text-red-600 hover:text-red-900" title="Delete">
                        <i class='bx bx-trash text-lg'></i>
                    </button>
                </div>
            </td>
        </tr>
    `).join('');
}

// Update statistics
function updateStats() {
    // This would typically come from API
    document.getElementById('totalRequests').textContent = allRequests.length;
    document.getElementById('pendingRequests').textContent = allRequests.filter(r => r.status === 'pending').length;
    document.getElementById('approvedRequests').textContent = allRequests.filter(r => r.status === 'approved').length;
    document.getElementById('activeRequests').textContent = allRequests.filter(r => r.status === 'active').length;
}

// Utility functions
function getStatusClass(status) {
    const classes = {
        pending: 'bg-orange-100 text-orange-800',
        approved: 'bg-green-100 text-green-800',
        rejected: 'bg-red-100 text-red-800',
        active: 'bg-blue-100 text-blue-800',
        completed: 'bg-gray-100 text-gray-800'
    };
    return classes[status] || 'bg-gray-100 text-gray-800';
}

function formatDate(dateString) {
    const date = new Date(dateString);
    return date.toLocaleDateString() + ' ' + date.toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'});
}

function showLoading() {
    document.getElementById('loadingRow').style.display = 'table-row';
}

function hideLoading() {
    document.getElementById('loadingRow').style.display = 'none';
}

function showError(message) {
    const tbody = document.getElementById('vehicleRequestsTableBody');
    tbody.innerHTML = `
        <tr>
            <td colspan="8" class="px-6 py-12 text-center text-red-500">
                <i class='bx bx-error text-4xl mb-2'></i>
                <p>${message}</p>
            </td>
        </tr>
    `;
}

// Modal functions
function openNewRequestModal() {
    document.getElementById('newRequestModal').classList.remove('hidden');
}

function closeNewRequestModal() {
    document.getElementById('newRequestModal').classList.add('hidden');
    document.getElementById('newRequestForm').reset();
}

// Form submission
document.getElementById('newRequestForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    const formData = new FormData(e.target);
    const data = Object.fromEntries(formData);
    
    try {
        const response = await fetch(API_BASE_URL, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(data)
        });
        
        if (response.ok) {
            closeNewRequestModal();
            loadVehicleRequests();
            updateStats();
            alert('Vehicle request submitted successfully!');
        } else {
            throw new Error('Failed to submit request');
        }
    } catch (error) {
        console.error('Error submitting request:', error);
        alert('Failed to submit vehicle request');
    }
});

// CRUD operations
function viewRequest(id) {
    // Implementation for viewing request details
    console.log('View request:', id);
}

function editRequest(id) {
    // Implementation for editing request
    console.log('Edit request:', id);
}

async function deleteRequest(id) {
    if (confirm('Are you sure you want to delete this request?')) {
        try {
            await fetch(`${API_BASE_URL}/${id}`, {
                method: 'DELETE'
            });
            loadVehicleRequests();
            updateStats();
        } catch (error) {
            console.error('Error deleting request:', error);
            alert('Failed to delete request');
        }
    }
}

// Filter functions
function applyFilters() {
    const search = document.getElementById('searchInput').value.toLowerCase();
    const status = document.getElementById('statusFilter').value;
    const dateRange = document.getElementById('dateFilter').value;
    
    let filtered = allRequests.filter(request => {
        const matchesSearch = !search || 
            request.id.toString().includes(search) ||
            request.driver.toLowerCase().includes(search) ||
            request.vehicleType.toLowerCase().includes(search);
        
        const matchesStatus = !status || request.status === status;
        
        // Add date range filtering logic here
        const matchesDate = true; // Simplified for now
        
        return matchesSearch && matchesStatus && matchesDate;
    });
    
    renderTable(filtered);
}

function clearFilters() {
    document.getElementById('searchInput').value = '';
    document.getElementById('statusFilter').value = '';
    document.getElementById('dateFilter').value = '';
    renderTable(allRequests);
}

// Utility functions
function refreshData() {
    loadVehicleRequests();
    updateStats();
}

function exportData() {
    // Implementation for exporting data
    console.log('Export data');
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
    loadVehicleRequests();
}

function previousPage() {
    if (currentPage > 1) {
        currentPage--;
        loadVehicleRequests();
    }
}

function nextPage() {
    const totalPages = Math.ceil(totalItems / itemsPerPage);
    if (currentPage < totalPages) {
        currentPage++;
        loadVehicleRequests();
    }
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
                <td colspan="8" class="px-6 py-12 text-center text-gray-500">
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
            <td class="px-6 py-4 whitespace-nowrap">
                <div class="flex items-center">
                    <div class="h-8 w-8 rounded-full bg-green-600 flex items-center justify-center text-white text-xs font-bold">
                        ${vehicle.driver ? vehicle.driver.charAt(0) : 'N'}
                    </div>
                    <div class="ml-3">
                        <div class="text-sm font-medium text-gray-900">${vehicle.driver || 'Unassigned'}</div>
                        <div class="text-xs text-gray-500">${vehicle.driverPhone || 'N/A'}</div>
                    </div>
                </div>
            </td>
            <td class="px-6 py-4 whitespace-nowrap">
                <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full ${getVehicleStatusClass(vehicle.status)}">
                    ${vehicle.status}
                </span>
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                ${formatDate(vehicle.lastMaintenance)}
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                <div class="flex space-x-2">
                    <button onclick="viewVehicle('${vehicle.id}')" class="text-blue-600 hover:text-blue-900" title="View">
                        <i class='bx bx-show text-lg'></i>
                    </button>
                    <button onclick="editVehicle('${vehicle.id}')" class="text-green-600 hover:text-green-900" title="Edit">
                        <i class='bx bx-edit text-lg'></i>
                    </button>
                    <button onclick="maintainVehicle('${vehicle.id}')" class="text-yellow-600 hover:text-yellow-900" title="Maintenance">
                        <i class='bx bx-wrench text-lg'></i>
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
    document.getElementById('vehicleLoadingRow').style.display = 'table-row';
}

function hideVehicleLoading() {
    document.getElementById('vehicleLoadingRow').style.display = 'none';
}

function showVehicleError(message) {
    const tbody = document.getElementById('vehicleFleetTableBody');
    tbody.innerHTML = `
        <tr>
            <td colspan="8" class="px-6 py-12 text-center text-red-500">
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
function viewVehicle(id) {
    console.log('View vehicle:', id);
}

function editVehicle(id) {
    console.log('Edit vehicle:', id);
}

function maintainVehicle(id) {
    console.log('Schedule maintenance for vehicle:', id);
}

// Vehicle utility functions
function refreshVehicleData() {
    loadVehicleFleet();
}

function exportVehicleData() {
    console.log('Export vehicle fleet data');
}
</script>
@endsection
