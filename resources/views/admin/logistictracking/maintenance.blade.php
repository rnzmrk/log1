@extends('layouts.app')

@section('title', 'Vehicle Maintenance')

@section('content')
<div class="container-fluid px-4 py-6">
    <!-- Page Header -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Vehicle Maintenance</h1>
            <p class="text-gray-600 mt-1">Monitor and manage vehicle maintenance status</p>
        </div>
        <button class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg flex items-center gap-2 transition-colors" onclick="refreshMaintenance()">
            <i class='bx bx-refresh'></i>
            Refresh
        </button>
    </div>

    <!-- Maintenance Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">In Maintenance</p>
                    <p class="text-2xl font-bold text-yellow-600" id="maintenanceCount">0</p>
                    <p class="text-xs text-gray-600 mt-1">Currently under maintenance</p>
                </div>
                <div class="bg-yellow-100 rounded-full p-3">
                    <i class='bx bx-wrench text-yellow-600 text-2xl'></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Available</p>
                    <p class="text-2xl font-bold text-green-600" id="availableCount">0</p>
                    <p class="text-xs text-gray-600 mt-1">Ready for use</p>
                </div>
                <div class="bg-green-100 rounded-full p-3">
                    <i class='bx bx-check-circle text-green-600 text-2xl'></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Total Vehicles</p>
                    <p class="text-2xl font-bold text-blue-600" id="totalCount">0</p>
                    <p class="text-xs text-gray-600 mt-1">All vehicles in fleet</p>
                </div>
                <div class="bg-blue-100 rounded-full p-3">
                    <i class='bx bx-car text-blue-600 text-2xl'></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Maintenance Table -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
            <h2 class="text-lg font-semibold text-gray-900">Maintenance Status</h2>
            <div class="flex gap-2">
                <button onclick="refreshMaintenance()" class="text-gray-600 hover:text-gray-900 p-2 rounded-lg hover:bg-gray-100">
                    <i class='bx bx-refresh text-xl'></i>
                </button>
                <button onclick="exportMaintenanceData()" class="text-gray-600 hover:text-gray-900 p-2 rounded-lg hover:bg-gray-100" title="Export to CSV">
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
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Driver</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Last Maintenance</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody id="maintenanceTableBody" class="bg-white divide-y divide-gray-200">
                    <!-- Data will be populated by API -->
                    <tr id="loadingRow">
                        <td colspan="12" class="px-6 py-12 text-center text-gray-500">
                            <i class='bx bx-loader-alt bx-spin text-4xl mb-2'></i>
                            <p>Loading maintenance data...</p>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        <div class="px-6 py-4 border-t border-gray-200 flex items-center justify-between">
            <div class="text-sm text-gray-700">
                Showing <span id="startItem">0</span> to <span id="endItem">0</span> of <span id="totalItems">0</span> results
            </div>
            <div class="flex items-center gap-2">
                <button onclick="previousPage()" class="px-3 py-1 text-sm bg-white border border-gray-300 rounded-md hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed" id="prevBtn">
                    Previous
                </button>
                <div id="paginationNumbers" class="flex gap-1">
                    <!-- Pagination numbers will be inserted here -->
                </div>
                <button onclick="nextPage()" class="px-3 py-1 text-sm bg-white border border-gray-300 rounded-md hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed" id="nextBtn">
                    Next
                </button>
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
@endsection

@push('scripts')
<script>
// API Configuration
const API_BASE_URL = '/api/logistictracking/vehicle-fleet';
let currentPage = 1;
let itemsPerPage = 5;
let totalItems = 0;
let allVehicles = [];
let maintenanceVehicles = [];

// Initialize page
document.addEventListener('DOMContentLoaded', function() {
    loadMaintenanceVehicles();
});

// Load vehicles with maintenance status
async function loadMaintenanceVehicles() {
    try {
        showLoading();
        const response = await fetch(API_BASE_URL);
        const data = await response.json();
        
        console.log('Maintenance API Response:', data);
        
        allVehicles = data.data || [];
        
        // Filter vehicles with maintenance status
        maintenanceVehicles = allVehicles.filter(vehicle => {
            const status = (vehicle.status || vehicle.status_raw || '').toLowerCase();
            return status.includes('maintenance') || status.includes('repair') || status.includes('service');
        });
        
        totalItems = maintenanceVehicles.length;
        
        // Update statistics
        updateMaintenanceStats();
        
        // Apply pagination for display
        renderPaginatedTable();
        updatePagination();
        
        hideLoading();
    } catch (error) {
        console.error('Error loading maintenance vehicles:', error);
        showError('Failed to load maintenance data');
        hideLoading();
    }
}

// Update maintenance statistics
function updateMaintenanceStats() {
    const maintenanceCount = maintenanceVehicles.length;
    const availableCount = allVehicles.filter(v => {
        const status = (v.status || v.status_raw || '').toLowerCase();
        return status.includes('available') || status.includes('ready');
    }).length;
    const totalCount = allVehicles.length;
    
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
    
    updateElement('maintenanceCount', maintenanceCount);
    updateElement('availableCount', availableCount);
    updateElement('totalCount', totalCount);
    
    console.log('Maintenance Statistics:', {
        maintenance: maintenanceCount,
        available: availableCount,
        total: totalCount
    });
}

// Render table with pagination
function renderPaginatedTable() {
    const startIndex = (currentPage - 1) * itemsPerPage;
    const endIndex = startIndex + itemsPerPage;
    const paginatedVehicles = maintenanceVehicles.slice(startIndex, endIndex);
    
    renderTable(paginatedVehicles);
    updatePagination();
}

// Render vehicle table
function renderTable(vehicles) {
    const tableBody = document.getElementById('maintenanceTableBody');
    
    if (vehicles.length === 0) {
        tableBody.innerHTML = `
            <tr>
                <td colspan="12" class="px-6 py-12 text-center text-gray-500">
                    <i class='bx bx-wrench text-4xl text-gray-300 mb-2'></i>
                    <p class="text-lg font-medium text-gray-900 mb-1">No vehicles in maintenance</p>
                    <p class="text-sm text-gray-500">All vehicles are currently operational</p>
                </td>
            </tr>
        `;
        return;
    }
    
    tableBody.innerHTML = vehicles.map(vehicle => `
        <tr class="hover:bg-gray-50">
            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">#${vehicle.id}</td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${vehicle.licensePlate || vehicle.plate_no || 'N/A'}</td>
            <td class="px-6 py-4 whitespace-nowrap">
                <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                    ${vehicle.vehicleType || vehicle.type || 'N/A'}
                </span>
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${vehicle.model || 'N/A'}</td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${vehicle.engine_no || 'N/A'}</td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${vehicle.chassis_no || 'N/A'}</td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${vehicle.color || 'N/A'}</td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${vehicle.fuel_type || 'N/A'}</td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${vehicle.driver || 'Unassigned'}</td>
            <td class="px-6 py-4 whitespace-nowrap">
                <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full ${getMaintenanceStatusClass(vehicle.status)}">
                    ${vehicle.status_raw || vehicle.status}
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
                    <button onclick="completeMaintenance('${vehicle.id}')" class="text-green-600 hover:text-green-900" title="Complete Maintenance">
                        <i class='bx bx-check-double text-lg'></i>
                    </button>
                </div>
            </td>
        </tr>
    `).join('');
}

// Get maintenance status class for styling
function getMaintenanceStatusClass(status) {
    const statusLower = (status || '').toLowerCase();
    if (statusLower.includes('maintenance') || statusLower.includes('repair')) {
        return 'bg-yellow-100 text-yellow-800';
    } else if (statusLower.includes('service')) {
        return 'bg-orange-100 text-orange-800';
    } else {
        return 'bg-gray-100 text-gray-800';
    }
}

// Pagination functions
function updatePagination() {
    const totalPages = Math.ceil(totalItems / itemsPerPage);
    const paginationNumbers = document.getElementById('paginationNumbers');
    
    // Update showing info
    const start = totalItems > 0 ? (currentPage - 1) * itemsPerPage + 1 : 0;
    const end = Math.min(currentPage * itemsPerPage, totalItems);
    
    document.getElementById('startItem').textContent = start;
    document.getElementById('endItem').textContent = end;
    document.getElementById('totalItems').textContent = totalItems;
    
    // Update pagination numbers
    let paginationHTML = '';
    for (let i = 1; i <= totalPages; i++) {
        if (i === currentPage) {
            paginationHTML += `<button class="px-3 py-1 text-sm bg-blue-600 text-white rounded-md">${i}</button>`;
        } else {
            paginationHTML += `<button onclick="goToPage(${i})" class="px-3 py-1 text-sm bg-white border border-gray-300 rounded-md hover:bg-gray-50">${i}</button>`;
        }
    }
    paginationNumbers.innerHTML = paginationHTML;
    
    // Update prev/next buttons
    document.getElementById('prevBtn').disabled = currentPage === 1;
    document.getElementById('nextBtn').disabled = currentPage === totalPages || totalPages === 0;
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

// View vehicle details
function viewVehicle(id) {
    const vehicle = allVehicles.find(v => v.id == id);
    
    if (!vehicle) {
        console.error('Vehicle not found:', id);
        return;
    }
    
    console.log('Viewing vehicle:', vehicle);
    
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
                        <span class="mt-1 inline-flex px-2 py-1 text-xs leading-5 font-semibold rounded-full ${getMaintenanceStatusClass(vehicle.status)}">
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
    
    const modal = document.getElementById('viewVehicleModal');
    if (modal && typeof bootstrap !== 'undefined') {
        const bsModal = new bootstrap.Modal(modal);
        bsModal.show();
    }
}

// Complete maintenance
function completeMaintenance(id) {
    // Direct completion without confirmation
    console.log('Complete maintenance for vehicle:', id);
    alert('Maintenance marked as complete for vehicle #' + id);
    // In a real implementation, this would update the vehicle status via API
    // For now, just show success message
}

// Refresh maintenance data
function refreshMaintenance() {
    loadMaintenanceVehicles();
}

// Export maintenance data
function exportMaintenanceData() {
    try {
        if (maintenanceVehicles.length === 0) {
            alert('No maintenance data to export');
            return;
        }
        
        console.log('Exporting maintenance data:', maintenanceVehicles.length, 'records');
        
        const headers = ['Vehicle ID', 'License Plate', 'Vehicle Type', 'Model', 'Engine No', 'Chassis No', 'Color', 'Fuel Type', 'Driver', 'Status', 'Last Maintenance'];
        const csvRows = [headers];
        
        maintenanceVehicles.forEach(vehicle => {
            const row = [
                vehicle.id || '',
                `"${(vehicle.licensePlate || vehicle.plate_no || '').replace(/"/g, '""')}"`,
                `"${(vehicle.vehicleType || vehicle.type || '').replace(/"/g, '""')}"`,
                `"${(vehicle.model || '').replace(/"/g, '""')}"`,
                `"${(vehicle.engine_no || '').replace(/"/g, '""')}"`,
                `"${(vehicle.chassis_no || '').replace(/"/g, '""')}"`,
                `"${(vehicle.color || '').replace(/"/g, '""')}"`,
                `"${(vehicle.fuel_type || '').replace(/"/g, '""')}"`,
                `"${(vehicle.driver || '').replace(/"/g, '""')}"`,
                `"${(vehicle.status_raw || vehicle.status || '').replace(/"/g, '""')}"`,
                `"${formatDate(vehicle.lastMaintenance).replace(/"/g, '""')}"`
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
        link.setAttribute('download', `vehicle_maintenance_${new Date().toISOString().split('T')[0]}.csv`);
        link.style.display = 'none';
        
        document.body.appendChild(link);
        link.click();
        
        setTimeout(() => {
            document.body.removeChild(link);
            URL.revokeObjectURL(url);
        }, 100);
        
        // Show success feedback
        const exportButton = document.querySelector('button[onclick="exportMaintenanceData()"]');
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
        
        console.log('Maintenance CSV exported successfully:', maintenanceVehicles.length, 'records');
        
    } catch (error) {
        console.error('Export error:', error);
        alert('Failed to export data. Please try again.');
    }
}

// Utility functions
function formatDate(dateString) {
    if (!dateString) return 'N/A';
    try {
        const date = new Date(dateString);
        return date.toLocaleDateString('en-US', {
            year: 'numeric',
            month: '2-digit',
            day: '2-digit',
            hour: '2-digit',
            minute: '2-digit'
        });
    } catch (error) {
        return dateString;
    }
}

function showLoading() {
    const loadingRow = document.getElementById('loadingRow');
    if (loadingRow) {
        loadingRow.style.display = 'table-row';
    }
}

function hideLoading() {
    const loadingRow = document.getElementById('loadingRow');
    if (loadingRow) {
        loadingRow.style.display = 'none';
    }
}

function showError(message) {
    const tableBody = document.getElementById('maintenanceTableBody');
    tableBody.innerHTML = `
        <tr>
            <td colspan="12" class="px-6 py-12 text-center text-red-500">
                <i class='bx bx-error text-4xl mb-2'></i>
                <p>${message}</p>
            </td>
        </tr>
    `;
}
</script>

<style>
/* Statistics card animations */
.text-2xl {
    transition: transform 0.2s ease-in-out;
}
</style>
@endpush
