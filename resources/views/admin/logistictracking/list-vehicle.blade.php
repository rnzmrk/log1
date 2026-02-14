@extends('layouts.app')

@section('title', 'List of Vehicle - IMARKET Admin')

@section('content')
<div class="space-y-6">
    <!-- Page Header -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">List of Vehicle</h1>
                <p class="text-gray-600 mt-1">Manage and view all vehicles in the fleet</p>
            </div>
            <div class="flex items-center space-x-2 text-sm text-gray-500">
                <i class='bx bx-car text-lg'></i>
                <span>Vehicle Fleet Management</span>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Total Vehicles</p>
                    <p class="text-2xl font-bold text-blue-600" id="totalVehicles">0</p>
                    <p class="text-xs text-gray-600 mt-1">All fleet vehicles</p>
                </div>
                <div class="bg-blue-100 rounded-full p-3">
                    <i class='bx bx-car text-blue-600 text-2xl'></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Active</p>
                    <p class="text-2xl font-bold text-green-600" id="activeVehicles">0</p>
                    <p class="text-xs text-gray-600 mt-1">Currently in use</p>
                </div>
                <div class="bg-green-100 rounded-full p-3">
                    <i class='bx bx-check-circle text-green-600 text-2xl'></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Maintenance</p>
                    <p class="text-2xl font-bold text-yellow-600" id="maintenanceVehicles">0</p>
                    <p class="text-xs text-gray-600 mt-1">Under maintenance</p>
                </div>
                <div class="bg-yellow-100 rounded-full p-3">
                    <i class='bx bx-wrench text-yellow-600 text-2xl'></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">In-Use</p>
                    <p class="text-2xl font-bold text-purple-600" id="inUseVehicles">0</p>
                    <p class="text-xs text-gray-600 mt-1">Currently deployed</p>
                </div>
                <div class="bg-purple-100 rounded-full p-3">
                    <i class='bx bx-time-five text-purple-600 text-2xl'></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Search and Filters -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <div class="flex flex-col md:flex-row gap-4">
            <!-- Search Bar -->
            <div class="flex-1">
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class='bx bx-search text-gray-400'></i>
                    </div>
                    <input type="text" id="vehicleSearchInput" placeholder="Search by vehicle ID, plate no, model..." 
                           class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md leading-5 bg-white placeholder-gray-500 focus:outline-none focus:placeholder-gray-400 focus:ring-1 focus:ring-blue-500 focus:border-blue-500">
                </div>
            </div>

            <!-- Status Filter -->
            <div class="w-full md:w-48">
                <select id="vehicleStatusFilter" class="block w-full px-3 py-2 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                    <option value="">All Status</option>
                    <option value="Active">Active</option>
                    <option value="In-Use">In-Use</option>
                    <option value="Maintenance">Maintenance</option>
                    <option value="Out-of-Service">Out-of-Service</option>
                    <option value="Reserved">Reserved</option>
                </select>
            </div>

            <!-- Vehicle Type Filter -->
            <div class="w-full md:w-48">
                <select id="vehicleTypeFilter" class="block w-full px-3 py-2 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                    <option value="">All Types</option>
                    <option value="Van">Van</option>
                    <option value="Sedan">Sedan</option>
                    <option value="SUV">SUV</option>
                    <option value="Truck">Truck</option>
                </select>
            </div>

            <!-- Action Buttons -->
            <div class="flex gap-2">
                <button onclick="applyVehicleFilters()" class="px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-md hover:bg-blue-700 transition-colors flex items-center gap-2">
                    <i class='bx bx-filter text-sm'></i>
                    Apply Filters
                </button>
                <button onclick="clearVehicleFilters()" class="px-4 py-2 bg-gray-600 text-white text-sm font-medium rounded-md hover:bg-gray-700 transition-colors flex items-center gap-2">
                    <i class='bx bx-x text-sm'></i>
                    Clear
                </button>
            </div>
        </div>
    </div>

    <!-- Vehicle Fleet Status Table -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
            <h2 class="text-lg font-semibold text-gray-900">Vehicle List</h2>
            <div class="flex gap-2">
                <button onclick="refreshVehicleFleet()" class="px-3 py-1.5 bg-blue-600 text-white text-sm font-medium rounded-md hover:bg-blue-700 transition-colors flex items-center gap-1">
                    <i class='bx bx-refresh text-sm'></i>
                    Refresh
                </button>
                <button onclick="exportVehicleData()" class="px-3 py-1.5 bg-green-600 text-white text-sm font-medium rounded-md hover:bg-green-700 transition-colors flex items-center gap-1">
                    <i class='bx bx-download text-sm'></i>
                    Export
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

<!-- Vehicle Details Modal -->
<div class="modal fade" id="vehicleDetailsModal" tabindex="-1" aria-labelledby="vehicleDetailsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-white border-0 pb-0">
                <div class="w-100">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <h5 class="modal-title fw-bold text-gray-900" id="vehicleDetailsModalLabel">Vehicle Details</h5>
                            <div class="text-muted small" id="modalVehicleId"></div>
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                </div>
            </div>
            <div class="modal-body pt-4">
                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="p-3 rounded-3 border bg-light">
                            <div class="text-uppercase small text-muted fw-semibold">License Plate</div>
                            <div class="fw-semibold text-gray-900" id="modalLicensePlate"></div>
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
                            <div class="text-uppercase small text-muted fw-semibold">Model</div>
                            <div class="fw-semibold text-gray-900" id="modalModel"></div>
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
                            <div class="text-uppercase small text-muted fw-semibold">Engine No</div>
                            <div class="fw-semibold text-gray-900" id="modalEngineNo"></div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="p-3 rounded-3 border bg-light">
                            <div class="text-uppercase small text-muted fw-semibold">Chassis No</div>
                            <div class="fw-semibold text-gray-900" id="modalChassisNo"></div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="p-3 rounded-3 border bg-light">
                            <div class="text-uppercase small text-muted fw-semibold">Color</div>
                            <div class="fw-semibold text-gray-900" id="modalColor"></div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="p-3 rounded-3 border bg-light">
                            <div class="text-uppercase small text-muted fw-semibold">Fuel Type</div>
                            <div class="fw-semibold text-gray-900" id="modalFuelType"></div>
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
@endsection

@push('styles')
<style>
    .modal-content {
        border-radius: 0.75rem;
    }
    .modal-header {
        border-bottom: 1px solid #f3f4f6;
    }
    .modal-footer {
        border-top: 1px solid #f3f4f6;
    }
</style>
@endpush

@push('scripts')
<script>
// API Configuration
const VEHICLE_API_BASE_URL = '/api/logistictracking/vehicle-fleet';
let currentVehiclePage = 1;
let vehicleItemsPerPage = 5;
let totalVehicleItems = 0;
let allVehicles = [];
let filteredVehicles = [];

// Initialize page
document.addEventListener('DOMContentLoaded', function() {
    loadVehicleFleet();
    
    // Add enter key support for search
    document.getElementById('vehicleSearchInput').addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            applyVehicleFilters();
        }
    });
});

// Load vehicle fleet from API
async function loadVehicleFleet() {
    try {
        showVehicleLoading();
        console.log('Loading vehicle fleet...');
        
        // Fetch ALL vehicles without limit
        const response = await fetch(`${VEHICLE_API_BASE_URL}`);
        const data = await response.json();
        
        console.log('API Response:', data);
        
        allVehicles = data.data || [];
        totalVehicleItems = data.total || 0;
        filteredVehicles = [...allVehicles];
        
        console.log('Vehicles loaded:', {
            allVehicles: allVehicles.length,
            totalVehicleItems,
            filteredVehicles: filteredVehicles.length
        });
        
        renderVehicleTable(filteredVehicles);
        updateVehiclePagination();
        updateVehicleStatistics();
        hideVehicleLoading();
    } catch (error) {
        console.error('Error loading vehicle fleet:', error);
        showVehicleError('Failed to load vehicle fleet data');
        hideVehicleLoading();
    }
}

// Update vehicle statistics cards
function updateVehicleStatistics() {
    const total = allVehicles.length;
    const active = allVehicles.filter(v => v.status_raw === 'Active').length;
    const maintenance = allVehicles.filter(v => v.status_raw === 'Maintenance').length;
    const inUse = allVehicles.filter(v => v.status_raw === 'In-Use').length;
    
    document.getElementById('totalVehicles').textContent = total;
    document.getElementById('activeVehicles').textContent = active;
    document.getElementById('maintenanceVehicles').textContent = maintenance;
    document.getElementById('inUseVehicles').textContent = inUse;
}

// Apply filters to vehicle list
function applyVehicleFilters() {
    const searchTerm = document.getElementById('vehicleSearchInput').value.toLowerCase();
    const statusFilter = document.getElementById('vehicleStatusFilter').value;
    const typeFilter = document.getElementById('vehicleTypeFilter').value;
    
    filteredVehicles = allVehicles.filter(vehicle => {
        // Search filter
        const matchesSearch = !searchTerm || 
            vehicle.id.toString().includes(searchTerm) ||
            (vehicle.licensePlate && vehicle.licensePlate.toLowerCase().includes(searchTerm)) ||
            (vehicle.model && vehicle.model.toLowerCase().includes(searchTerm)) ||
            (vehicle.engine_no && vehicle.engine_no.toLowerCase().includes(searchTerm)) ||
            (vehicle.chassis_no && vehicle.chassis_no.toLowerCase().includes(searchTerm));
        
        // Status filter
        const matchesStatus = !statusFilter || vehicle.status_raw === statusFilter;
        
        // Type filter
        const matchesType = !typeFilter || vehicle.vehicleType === typeFilter;
        
        return matchesSearch && matchesStatus && matchesType;
    });
    
    currentVehiclePage = 1;
    renderVehicleTable(filteredVehicles);
    updateVehiclePagination();
}

// Clear all filters
function clearVehicleFilters() {
    document.getElementById('vehicleSearchInput').value = '';
    document.getElementById('vehicleStatusFilter').value = '';
    document.getElementById('vehicleTypeFilter').value = '';
    
    filteredVehicles = [...allVehicles];
    currentVehiclePage = 1;
    renderVehicleTable(allVehicles);
    updateVehiclePagination();
}

// Export vehicle data to CSV
function exportVehicleData() {
    try {
        const dataToExport = filteredVehicles.length > 0 ? filteredVehicles : allVehicles;
        
        if (dataToExport.length === 0) {
            alert('No data to export');
            return;
        }
        
        console.log('Exporting data:', dataToExport.length, 'records');
        
        // Create CSV content with proper formatting
        const headers = ['Vehicle ID', 'License Plate', 'Vehicle Type', 'Model', 'Engine No', 'Chassis No', 'Color', 'Fuel Type', 'Status'];
        const csvRows = [headers];
        
        dataToExport.forEach(vehicle => {
            const row = [
                vehicle.id || '',
                `"${(vehicle.licensePlate || '').replace(/"/g, '""')}"`, // Escape quotes
                `"${(vehicle.vehicleType || '').replace(/"/g, '""')}"`,
                `"${(vehicle.model || '').replace(/"/g, '""')}"`,
                `"${(vehicle.engine_no || '').replace(/"/g, '""')}"`,
                `"${(vehicle.chassis_no || '').replace(/"/g, '""')}"`,
                `"${(vehicle.color || '').replace(/"/g, '""')}"`,
                `"${(vehicle.fuel_type || '').replace(/"/g, '""')}"`,
                `"${(vehicle.status_raw || vehicle.status || '').replace(/"/g, '""')}"`
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
            exportButton.innerHTML = '<i class="bx bx-check text-sm"></i> Downloaded!';
            exportButton.classList.remove('bg-green-600', 'hover:bg-green-700');
            exportButton.classList.add('bg-green-700');
            
            setTimeout(() => {
                exportButton.innerHTML = originalHTML;
                exportButton.classList.remove('bg-green-700');
                exportButton.classList.add('bg-green-600', 'hover:bg-green-700');
            }, 2000);
        }
        
        console.log('CSV exported successfully:', dataToExport.length, 'records');
        
    } catch (error) {
        console.error('Export error:', error);
        alert('Failed to export data. Please try again.');
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
    
    // Apply pagination
    const startIndex = (currentVehiclePage - 1) * vehicleItemsPerPage;
    const endIndex = startIndex + vehicleItemsPerPage;
    const paginatedVehicles = vehicles.slice(startIndex, endIndex);
    
    tbody.innerHTML = paginatedVehicles.map(vehicle => `
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

// Get vehicle status class for badge styling
function getVehicleStatusClass(status) {
    const classes = {
        'in-use': 'bg-blue-100 text-blue-800',
        available: 'bg-green-100 text-green-800',
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
    const dataToShow = filteredVehicles.length > 0 ? filteredVehicles : allVehicles;
    const totalPages = Math.ceil(dataToShow.length / vehicleItemsPerPage);
    const paginationNumbers = document.getElementById('vehiclePaginationNumbers');
    
    console.log('Updating pagination:', {
        dataToShow: dataToShow.length,
        totalPages,
        currentPage: currentVehiclePage,
        itemsPerPage: vehicleItemsPerPage
    });
    
    // Update showing info
    const start = (currentVehiclePage - 1) * vehicleItemsPerPage + 1;
    const end = Math.min(currentVehiclePage * vehicleItemsPerPage, dataToShow.length);
    
    document.getElementById('vehicleShowingFrom').textContent = dataToShow.length > 0 ? start : 0;
    document.getElementById('vehicleShowingTo').textContent = end;
    document.getElementById('vehicleTotalRecords').textContent = dataToShow.length;
    
    // Generate pagination numbers - always show at least page 1
    let paginationHTML = '';
    const pagesToShow = Math.max(1, totalPages);
    
    for (let i = 1; i <= pagesToShow; i++) {
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
    
    // Ensure pagination container is visible
    const paginationContainer = document.querySelector('.bg-white.px-4.py-3.flex.items-center.justify-between');
    if (paginationContainer) {
        paginationContainer.style.display = 'flex';
    }
}

function goToVehiclePage(page) {
    currentVehiclePage = page;
    const dataToShow = filteredVehicles.length > 0 ? filteredVehicles : allVehicles;
    renderVehicleTable(dataToShow);
}

function previousVehiclePage() {
    const dataToShow = filteredVehicles.length > 0 ? filteredVehicles : allVehicles;
    const totalPages = Math.ceil(dataToShow.length / vehicleItemsPerPage);
    if (currentVehiclePage > 1) {
        currentVehiclePage--;
        renderVehicleTable(dataToShow);
        updateVehiclePagination();
    }
}

function nextVehiclePage() {
    const dataToShow = filteredVehicles.length > 0 ? filteredVehicles : allVehicles;
    const totalPages = Math.ceil(dataToShow.length / vehicleItemsPerPage);
    if (currentVehiclePage < totalPages) {
        currentVehiclePage++;
        renderVehicleTable(dataToShow);
        updateVehiclePagination();
    }
}

// Vehicle actions
function viewVehicle(id) {
    const vehicle = allVehicles.find(v => v.id == id);
    if (!vehicle) {
        alert('Vehicle not found');
        return;
    }

    document.getElementById('modalVehicleId').textContent = `#${vehicle.id}`;
    document.getElementById('modalLicensePlate').textContent = vehicle.licensePlate || 'N/A';
    document.getElementById('modalVehicleType').textContent = vehicle.vehicleType || 'N/A';
    document.getElementById('modalModel').textContent = vehicle.model || 'N/A';
    document.getElementById('modalEngineNo').textContent = vehicle.engine_no || 'N/A';
    document.getElementById('modalChassisNo').textContent = vehicle.chassis_no || 'N/A';
    document.getElementById('modalColor').textContent = vehicle.color || 'N/A';
    document.getElementById('modalFuelType').textContent = vehicle.fuel_type || 'N/A';

    const statusEl = document.getElementById('modalStatus');
    statusEl.innerHTML = `
        <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full ${getVehicleStatusClass(vehicle.status)}">
            ${vehicle.status_raw || vehicle.status}
        </span>
    `;

    const modalEl = document.getElementById('vehicleDetailsModal');
    if (window.bootstrap && window.bootstrap.Modal) {
        const modal = window.bootstrap.Modal.getOrCreateInstance(modalEl);
        modal.show();
    } else {
        alert('Bootstrap is not loaded.');
    }
}

function editVehicle(id) {
    console.log('Edit vehicle:', id);
    // TODO: Implement edit functionality
}

function maintainVehicle(id) {
    console.log('Maintenance for vehicle:', id);
    // TODO: Implement maintenance functionality
}

function refreshVehicleFleet() {
    currentVehiclePage = 1;
    loadVehicleFleet();
}

function exportVehicleData() {
    try {
        const dataToExport = filteredVehicles.length > 0 ? filteredVehicles : allVehicles;
        
        if (dataToExport.length === 0) {
            alert('No data to export');
            return;
        }
        
        console.log('Exporting data:', dataToExport.length, 'records');
        
        // Create CSV content with proper formatting
        const headers = ['Vehicle ID', 'License Plate', 'Vehicle Type', 'Model', 'Engine No', 'Chassis No', 'Color', 'Fuel Type', 'Status'];
        const csvRows = [headers];
        
        dataToExport.forEach(vehicle => {
            const row = [
                vehicle.id || '',
                `"${(vehicle.licensePlate || '').replace(/"/g, '""')}"`, // Escape quotes
                `"${(vehicle.vehicleType || '').replace(/"/g, '""')}"`,
                `"${(vehicle.model || '').replace(/"/g, '""')}"`,
                `"${(vehicle.engine_no || '').replace(/"/g, '""')}"`,
                `"${(vehicle.chassis_no || '').replace(/"/g, '""')}"`,
                `"${(vehicle.color || '').replace(/"/g, '""')}"`,
                `"${(vehicle.fuel_type || '').replace(/"/g, '""')}"`,
                `"${(vehicle.status_raw || vehicle.status || '').replace(/"/g, '""')}"`
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
            exportButton.innerHTML = '<i class="bx bx-check text-sm"></i> Downloaded!';
            exportButton.classList.remove('bg-green-600', 'hover:bg-green-700');
            exportButton.classList.add('bg-green-700');
            
            setTimeout(() => {
                exportButton.innerHTML = originalHTML;
                exportButton.classList.remove('bg-green-700');
                exportButton.classList.add('bg-green-600', 'hover:bg-green-700');
            }, 2000);
        }
        
        console.log('CSV exported successfully:', dataToExport.length, 'records');
        
    } catch (error) {
        console.error('Export error:', error);
        alert('Failed to export data. Please try again.');
    }
}
</script>
@endpush
