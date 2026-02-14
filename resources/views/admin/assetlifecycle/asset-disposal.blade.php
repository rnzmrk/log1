@extends('layouts.app')

@section('title', 'Asset Disposal')

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
                    <a href="#" class="text-gray-700 hover:text-blue-600 ml-1 md:ml-2">Asset Lifecycle</a>
                </div>
            </li>
            <li>
                <div class="flex items-center">
                    <i class='bx bx-chevron-right text-gray-400'></i>
                    <a href="#" class="text-gray-700 hover:text-blue-600 ml-1 md:ml-2">Asset Disposal</a>
                </div>
            </li>
            <li aria-current="page">
                <div class="flex items-center">
                    <i class='bx bx-chevron-right text-gray-400'></i>
                    <span class="ml-1 text-gray-500 md:ml-2">Disposal List</span>
                </div>
            </li>
        </ol>
    </nav>

    <!-- Page Header -->
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-900">Asset Disposal</h1>
        <div class="flex gap-3">
            <button class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-medium py-2 px-4 rounded-lg flex items-center gap-2 transition-colors">
                <i class='bx bx-refresh text-xl'></i>
                Refresh
            </button>

        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-6">
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Total Disposals</p>
                    <p class="text-2xl font-bold text-blue-600" id="totalDisposals">0</p>
                    <p class="text-xs text-gray-600 mt-1">All time</p>
                </div>
                <div class="bg-blue-100 rounded-full p-3">
                    <i class='bx bx-trash text-blue-600 text-2xl'></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Pending</p>
                    <p class="text-2xl font-bold text-orange-600" id="pendingDisposals">0</p>
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
                    <p class="text-2xl font-bold text-green-600" id="approvedDisposals">0</p>
                    <p class="text-xs text-gray-600 mt-1">Approved for disposal</p>
                </div>
                <div class="bg-green-100 rounded-full p-3">
                    <i class='bx bx-check-circle text-green-600 text-2xl'></i>
                </div>
            </div>
        </div>

 
    </div>

    <!-- Filter Section -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            <!-- Search -->
            <div class="lg:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-2">Search Disposals</label>
                <div class="relative">
                    <input type="text" 
                           id="searchInput"
                           placeholder="Search by disposal ID, asset name, department..." 
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
                    <option value="approved">Approved</option>
                    <option value="completed">Completed</option>
                    <option value="rejected">Rejected</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Date From</label>
                <input type="date" id="dateFromFilter" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Date To</label>
                <input type="date" id="dateToFilter" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
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

    <!-- Asset Disposal Table -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
            <h2 class="text-lg font-semibold text-gray-900">Asset Disposal List</h2>
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
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Disposal ID</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Asset Name</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Details</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Department</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Quantity</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody id="disposalTableBody" class="bg-white divide-y divide-gray-200">
                    <!-- Data will be populated by API -->
                    <tr id="loadingRow">
                        <td colspan="8" class="px-6 py-12 text-center text-gray-500">
                            <i class='bx bx-loader-alt bx-spin text-4xl mb-2'></i>
                            <p>Loading disposal data...</p>
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
</div>

<!-- Disposal Details Modal -->
<div class="modal fade" id="disposalDetailsModal" tabindex="-1" aria-labelledby="disposalDetailsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-white border-0 pb-0">
                <div class="w-100">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <h5 class="modal-title fw-bold text-gray-900" id="disposalDetailsModalLabel">Disposal Details</h5>
                            <div class="text-muted small" id="modalDisposalId"></div>
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                </div>
            </div>
            <div class="modal-body pt-4">
                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="p-3 rounded-3 border bg-light">
                            <div class="text-uppercase small text-muted fw-semibold">Asset Name</div>
                            <div class="fw-semibold text-gray-900" id="modalAssetName"></div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="p-3 rounded-3 border bg-light">
                            <div class="text-uppercase small text-muted fw-semibold">Department</div>
                            <div class="fw-semibold text-gray-900" id="modalDepartment"></div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="p-3 rounded-3 border bg-light">
                            <div class="text-uppercase small text-muted fw-semibold">Date</div>
                            <div class="fw-semibold text-gray-900" id="modalDate"></div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="p-3 rounded-3 border bg-light">
                            <div class="text-uppercase small text-muted fw-semibold">Quantity</div>
                            <div class="fw-semibold text-gray-900" id="modalQuantity"></div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="p-3 rounded-3 border bg-light">
                            <div class="text-uppercase small text-muted fw-semibold">Status</div>
                            <div id="modalStatus"></div>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="p-3 rounded-3 border bg-white">
                            <div class="text-uppercase small text-muted fw-semibold mb-2">Details</div>
                            <div class="text-gray-900" id="modalDetails"></div>
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

<!-- New Disposal Modal -->
<div id="newDisposalModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-lg bg-white">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-bold text-gray-900">New Asset Disposal</h3>
            <button onclick="closeNewDisposalModal()" class="text-gray-400 hover:text-gray-900">
                <i class='bx bx-x text-xl'></i>
            </button>
        </div>
        <form id="newDisposalForm">
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Asset Name</label>
                <input type="text" name="asset_name" required placeholder="Enter asset name" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Department</label>
                <select name="department" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">Select department...</option>
                    <option value="IT">IT</option>
                    <option value="HR">HR</option>
                    <option value="Finance">Finance</option>
                    <option value="Operations">Operations</option>
                    <option value="Logistics">Logistics</option>
                </select>
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Quantity</label>
                <input type="number" name="quantity" required min="1" placeholder="Enter quantity" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Duration</label>
                <input type="text" name="duration" required placeholder="e.g., 1 week, 2 days" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Details</label>
                <textarea name="details" required placeholder="Enter disposal details" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
            </div>
            <div class="flex gap-3">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg transition-colors">
                    Submit Disposal
                </button>
                <button type="button" onclick="closeNewDisposalModal()" class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-medium py-2 px-4 rounded-lg transition-colors">
                    Cancel
                </button>
            </div>
        </form>
    </div>
</div>

<script>
// API Configuration
const API_BASE_URL = '/api/assetlifecycle/asset-disposal';
let currentPage = 1;
let itemsPerPage = 10;
let totalItems = 0;
let allDisposals = [];
let filteredDisposals = [];

// Initialize page
document.addEventListener('DOMContentLoaded', function() {
    loadDisposalData();
    updateStats();
    
    // Add real-time search functionality
    const searchInput = document.getElementById('searchInput');
    const searchIndicator = document.getElementById('searchIndicator');
    
    if (searchInput) {
        searchInput.addEventListener('input', function() {
            if (searchIndicator) {
                searchIndicator.classList.remove('hidden');
            }
            
            clearTimeout(window.searchTimeout);
            window.searchTimeout = setTimeout(() => {
                applyFilters();
                if (searchIndicator) {
                    searchIndicator.classList.add('hidden');
                }
            }, 500);
        });
    }
    
    // Add change listeners for filters
    const statusFilter = document.getElementById('statusFilter');
    const dateFromFilter = document.getElementById('dateFromFilter');
    const dateToFilter = document.getElementById('dateToFilter');
    
    if (statusFilter) {
        statusFilter.addEventListener('change', applyFilters);
    }

    if (dateFromFilter) {
        dateFromFilter.addEventListener('change', applyFilters);
    }

    if (dateToFilter) {
        dateToFilter.addEventListener('change', applyFilters);
    }
});

// Load disposal data from API
async function loadDisposalData() {
    try {
        showLoading();
        const response = await fetch(API_BASE_URL);
        const data = await response.json();
        
        console.log('Disposal API Response:', data);
        
        allDisposals = data.data || [];
        filteredDisposals = [...allDisposals];
        totalItems = allDisposals.length;
        
        renderPaginatedTable();
        updatePagination();
        updateStats();
        hideLoading();
    } catch (error) {
        console.error('Error loading disposal data:', error);
        showError('Failed to load disposal data');
        hideLoading();
    }
}

// Render table with data
function renderTable(disposals) {
    const tbody = document.getElementById('disposalTableBody');
    
    if (disposals.length === 0) {
        tbody.innerHTML = `
            <tr>
                <td colspan="8" class="px-6 py-12 text-center text-gray-500">
                    <i class='bx bx-inbox text-4xl mb-2'></i>
                    <p>No disposal records found</p>
                </td>
            </tr>
        `;
        return;
    }
    
    tbody.innerHTML = disposals.map(disposal => `
        <tr class="hover:bg-gray-50">
            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">${disposal.disposal_number ? disposal.disposal_number : `#${disposal.id}`}</td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${disposal.asset_name || 'N/A'}</td>
            <td class="px-6 py-4 text-sm text-gray-900 max-w-xs truncate" title="${disposal.details || 'N/A'}">
                ${disposal.details || 'N/A'}
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                ${formatDate(disposal.created_at)}
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${disposal.department || 'N/A'}</td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${disposal.quantity || 'N/A'}</td>
            <td class="px-6 py-4 whitespace-nowrap">
                <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full ${getStatusClass(disposal.status)}">
                    ${disposal.status || 'N/A'}
                </span>
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                <div class="flex space-x-2">
                    <button onclick="viewDisposal('${disposal.id}')" class="text-blue-600 hover:text-blue-900" title="View">
                        <i class='bx bx-show text-lg'></i>
                    </button>
                    ${disposal.status === 'pending' ? `
                        <button onclick="approveDisposal('${disposal.id}')" class="text-green-600 hover:text-green-900" title="Approve">
                            <i class='bx bx-check text-lg'></i>
                        </button>
                    ` : ''}
                </div>
            </td>
        </tr>
    `).join('');
}

// Update statistics
function updateStats() {
    if (!allDisposals || allDisposals.length === 0) {
        document.getElementById('totalDisposals').textContent = '0';
        document.getElementById('pendingDisposals').textContent = '0';
        document.getElementById('approvedDisposals').textContent = '0';
        return;
    }
    
    const total = allDisposals.length;
    const pending = allDisposals.filter(d => d.status === 'pending').length;
    const approved = allDisposals.filter(d => d.status === 'approved').length;
    
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
    
    updateElement('totalDisposals', total);
    updateElement('pendingDisposals', pending);
    updateElement('approvedDisposals', approved);
}

// Filter functions
function applyFilters() {
    const search = document.getElementById('searchInput').value.toLowerCase();
    const status = document.getElementById('statusFilter').value;
    const dateFrom = document.getElementById('dateFromFilter').value;
    const dateTo = document.getElementById('dateToFilter').value;
    
    filteredDisposals = allDisposals.filter(disposal => {
        const matchesSearch = !search || 
            disposal.id.toString().includes(search) ||
            (disposal.disposal_number && disposal.disposal_number.toLowerCase().includes(search)) ||
            (disposal.asset_name && disposal.asset_name.toLowerCase().includes(search)) ||
            (disposal.details && disposal.details.toLowerCase().includes(search));
        
        const matchesStatus = !status || disposal.status === status;

        let matchesDate = true;
        if (dateFrom && disposal.date) {
            matchesDate = matchesDate && (new Date(disposal.date) >= new Date(dateFrom));
        }
        if (dateTo && disposal.date) {
            const end = new Date(dateTo);
            end.setHours(23, 59, 59, 999);
            matchesDate = matchesDate && (new Date(disposal.date) <= end);
        }
        
        return matchesSearch && matchesStatus && matchesDate;
    });
    
    currentPage = 1;
    renderPaginatedTable();
    updatePagination();
}

function clearFilters() {
    document.getElementById('searchInput').value = '';
    document.getElementById('statusFilter').value = '';
    document.getElementById('dateFromFilter').value = '';
    document.getElementById('dateToFilter').value = '';
    
    filteredDisposals = [...allDisposals];
    currentPage = 1;
    renderPaginatedTable();
    updatePagination();
}

async function approveDisposal(id) {
    try {
        const response = await fetch(`${API_BASE_URL}/${id}/approve`, {
            method: 'POST',
            headers: {
                'Accept': 'application/json'
            }
        });
        const data = await response.json();

        if (!response.ok) {
            alert(data?.message || 'Failed to approve');
            return;
        }

        const idx = allDisposals.findIndex(d => d.id == id);
        if (idx !== -1) {
            allDisposals[idx].status = 'approved';
        }

        applyFilters();
        updateStats();
    } catch (error) {
        console.error('Approve error:', error);
        alert('Failed to approve');
    }
}

// Pagination functions
function renderPaginatedTable() {
    const startIndex = (currentPage - 1) * itemsPerPage;
    const endIndex = startIndex + itemsPerPage;
    const paginatedDisposals = filteredDisposals.slice(startIndex, endIndex);
    
    renderTable(paginatedDisposals);
    updatePagination();
}

function updatePagination() {
    const totalPages = Math.ceil(filteredDisposals.length / itemsPerPage);
    const paginationNumbers = document.getElementById('paginationNumbers');
    
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
    
    // Update showing info
    const start = (currentPage - 1) * itemsPerPage + 1;
    const end = Math.min(currentPage * itemsPerPage, filteredDisposals.length);
    
    document.getElementById('showingFrom').textContent = filteredDisposals.length > 0 ? start : 0;
    document.getElementById('showingTo').textContent = end;
    document.getElementById('totalRecords').textContent = filteredDisposals.length;
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
    const totalPages = Math.ceil(filteredDisposals.length / itemsPerPage);
    if (currentPage < totalPages) {
        currentPage++;
        renderPaginatedTable();
    }
}

// CRUD operations
function viewDisposal(id) {
    const disposal = allDisposals.find(d => d.id == id);
    if (!disposal) {
        alert('Disposal record not found');
        return;
    }

    document.getElementById('modalDisposalId').textContent = disposal.disposal_number ? disposal.disposal_number : `#${disposal.id}`;
    document.getElementById('modalAssetName').textContent = disposal.asset_name || 'N/A';
    document.getElementById('modalDepartment').textContent = disposal.department || 'N/A';
    document.getElementById('modalDate').textContent = formatDate(disposal.created_at);
    document.getElementById('modalQuantity').textContent = disposal.quantity || 'N/A';
    document.getElementById('modalDetails').textContent = disposal.details || 'N/A';

    const statusEl = document.getElementById('modalStatus');
    statusEl.innerHTML = `
        <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full ${getStatusClass(disposal.status)}">
            ${disposal.status || 'N/A'}
        </span>
    `;

    const modalEl = document.getElementById('disposalDetailsModal');
    if (window.bootstrap && window.bootstrap.Modal) {
        const modal = window.bootstrap.Modal.getOrCreateInstance(modalEl);
        modal.show();
    } else {
        alert('Bootstrap is not loaded.');
    }
}

// Modal functions
function openNewDisposalModal() {
    document.getElementById('newDisposalModal').classList.remove('hidden');
}

function closeNewDisposalModal() {
    document.getElementById('newDisposalModal').classList.add('hidden');
    document.getElementById('newDisposalForm').reset();
}

// Form submission
document.getElementById('newDisposalForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    const data = Object.fromEntries(formData);
    
    // Add current date to the data
    data.date = new Date().toISOString().split('T')[0];
    data.status = 'pending';
    
    // Here you would normally send this to your API
    console.log('New disposal data:', data);
    
    // For now, just add to the local array and refresh
    const newDisposal = {
        id: allDisposals.length + 1,
        ...data
    };
    
    allDisposals.unshift(newDisposal);
    filteredDisposals = [...allDisposals];
    
    renderPaginatedTable();
    updatePagination();
    updateStats();
    closeNewDisposalModal();
    
    alert('Disposal request submitted successfully!');
});

// Utility functions
function getStatusClass(status) {
    const classes = {
        'pending': 'bg-orange-100 text-orange-800',
        'approved': 'bg-green-100 text-green-800',
        'completed': 'bg-gray-100 text-gray-800',
        'rejected': 'bg-red-100 text-red-800'
    };
    
    return classes[status] || 'bg-gray-100 text-gray-800';
}

function formatDate(dateString) {
    if (!dateString) return 'N/A';
    const date = new Date(dateString);
    return date.toLocaleDateString();
}

function showLoading() {
    const el = document.getElementById('loadingRow');
    if (el) {
        el.style.display = 'table-row';
    }
}

function hideLoading() {
    const el = document.getElementById('loadingRow');
    if (el) {
        el.style.display = 'none';
    }
}

function showError(message) {
    const tbody = document.getElementById('disposalTableBody');
    tbody.innerHTML = `
        <tr>
            <td colspan="8" class="px-6 py-12 text-center text-red-500">
                <i class='bx bx-error text-4xl mb-2'></i>
                <p>${message}</p>
            </td>
        </tr>
    `;
}

function refreshData() {
    currentPage = 1;
    loadDisposalData();
}

function exportData() {
    try {
        if (filteredDisposals.length === 0) {
            alert('No data to export');
            return;
        }
        
        const headers = ['Disposal ID', 'Asset Name', 'Details', 'Date', 'Department', 'Quantity', 'Status'];
        const csvRows = [headers];
        
        filteredDisposals.forEach(disposal => {
            const row = [
                disposal.disposal_number || disposal.id || '',
                `"${(disposal.asset_name || '').replace(/"/g, '""')}"`,
                `"${(disposal.details || '').replace(/"/g, '""')}"`,
                `"${formatDate(disposal.created_at)}"`,
                `"${(disposal.department || '').replace(/"/g, '""')}"`,
                disposal.quantity || '',
                `"${(disposal.status || '').replace(/"/g, '""')}"`
            ];
            csvRows.push(row.join(','));
        });
        
        const csvContent = csvRows.join('\n');
        const BOM = '\uFEFF';
        const csvData = BOM + csvContent;
        
        const blob = new Blob([csvData], { type: 'text/csv;charset=utf-8;' });
        const link = document.createElement('a');
        const url = URL.createObjectURL(blob);
        
        link.setAttribute('href', url);
        link.setAttribute('download', `asset_disposal_${new Date().toISOString().split('T')[0]}.csv`);
        link.style.display = 'none';
        
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
        URL.revokeObjectURL(url);
        
    } catch (error) {
        console.error('Export error:', error);
        alert('Failed to export data');
    }
}
</script>
@endsection
