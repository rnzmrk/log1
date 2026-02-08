@extends('layouts.app')

@section('title', 'Create Vehicle Request')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h4 class="page-title mb-1">Create Vehicle Request</h4>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('vehicle-requests.index') }}">Vehicle Requests</a></li>
                            <li class="breadcrumb-item active">Create</li>
                        </ol>
                    </nav>
                </div>
                <a href="{{ route('vehicle-requests.index') }}" class="btn btn-outline-secondary">
                    <i class='bx bx-arrow-back mr-2'></i>
                    Back to Requests
                </a>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Vehicle Request Details</h5>
                    <p class="text-muted text-sm mt-1 mb-0">Search for a reservation ID to create your vehicle request</p>
                </div>
                <div class="card-body">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <strong>Error:</strong>
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('vehicle-requests.store') }}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-4">
                                    <label for="reservation_search" class="form-label">Search Reservation</label>
                                    <div class="position-relative">
                                        <input type="text" 
                                               id="reservation_search" 
                                               name="reservation_id" 
                                               class="form-control" 
                                               placeholder="Enter reservation ID or name..."
                                               required>
                                        <div id="search_results" class="position-absolute w-full bg-white border border-gray-300 rounded-lg shadow-lg max-h-60 overflow-y-auto z-10 hidden" style="top: 100%; margin-top: 4px;"></div>
                                    </div>
                                    <div class="form-text">Start typing to search for reservations by ID or name</div>
                                </div>
                            </div>
                        </div>

                        <!-- Reservation Details (Hidden initially) -->
                        <div id="reservation_details" class="hidden">
                            <div class="border-top pt-4 mt-4">
                                <h6 class="mb-3">Reservation Details</h6>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label text-muted">Reservation ID</label>
                                            <p id="detail_reservation_id" class="form-control-plaintext fw-medium">-</p>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label text-muted">Reserved By</label>
                                            <p id="detail_reserved_by" class="form-control-plaintext">-</p>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label text-muted">Vehicle ID</label>
                                            <p id="detail_vehicle_id" class="form-control-plaintext">-</p>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label text-muted">Start Time</label>
                                            <p id="detail_start_time" class="form-control-plaintext">-</p>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label text-muted">End Time</label>
                                            <p id="detail_end_time" class="form-control-plaintext">-</p>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label text-muted">Status</label>
                                            <p id="detail_status" class="form-control-plaintext">-</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label text-muted">Purpose</label>
                                    <p id="detail_purpose" class="form-control-plaintext">-</p>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label text-muted">Department</label>
                                    <p id="detail_department" class="form-control-plaintext">-</p>
                                </div>
                            </div>

                            <div class="d-flex justify-content-end gap-2 mt-4">
                                <button type="button" id="clear_selection" class="btn btn-outline-secondary">
                                    <i class='bx bx-x mr-2'></i>
                                    Clear Selection
                                </button>
                                <button type="submit" class="btn btn-primary">
                                    <i class='bx bx-send mr-2'></i>
                                    Submit Request
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
let searchTimeout;
let selectedReservation = null;

// Search reservations
document.getElementById('reservation_search').addEventListener('input', function(e) {
    const query = e.target.value.trim();
    const resultsDiv = document.getElementById('search_results');
    
    clearTimeout(searchTimeout);
    
    if (query.length < 2) {
        resultsDiv.classList.add('hidden');
        hideReservationDetails();
        return;
    }
    
    searchTimeout = setTimeout(() => {
        fetch(`/vehicle-requests/search-reservation?q=${encodeURIComponent(query)}`)
            .then(response => response.json())
            .then(data => {
                resultsDiv.innerHTML = '';
                if (data.error) {
                    resultsDiv.innerHTML = '<div class="p-3 text-red-500 text-sm">Search unavailable</div>';
                } else if (data.length === 0) {
                    resultsDiv.innerHTML = '<div class="p-3 text-gray-500 text-sm">No reservations found</div>';
                } else {
                    data.forEach(reservation => {
                        const div = document.createElement('div');
                        div.className = 'px-3 py-2 hover:bg-gray-50 cursor-pointer border-b border-gray-100 last:border-b-0';
                        div.innerHTML = `
                            <div class="font-medium text-sm">ID: ${reservation.id}</div>
                            <div class="text-xs text-gray-500">${reservation.reserved_by} | ${reservation.department}</div>
                            <div class="text-xs text-blue-600">Status: ${reservation.status}</div>
                        `;
                        div.addEventListener('click', () => selectReservation(reservation));
                        resultsDiv.appendChild(div);
                    });
                }
                resultsDiv.classList.remove('hidden');
            })
            .catch(error => {
                console.error('Search error:', error);
                resultsDiv.innerHTML = '<div class="p-3 text-red-500 text-sm">Search failed</div>';
                resultsDiv.classList.remove('hidden');
            });
    }, 300);
});

// Select reservation and show details
function selectReservation(reservation) {
    selectedReservation = reservation;
    document.getElementById('reservation_search').value = `ID: ${reservation.id} - ${reservation.reserved_by}`;
    document.getElementById('search_results').classList.add('hidden');
    
    // Show reservation details
    showReservationDetails(reservation);
}

// Show reservation details
function showReservationDetails(reservation) {
    document.getElementById('detail_reservation_id').textContent = reservation.id;
    document.getElementById('detail_reserved_by').textContent = reservation.reserved_by;
    document.getElementById('detail_vehicle_id').textContent = reservation.vehicle_id;
    document.getElementById('detail_start_time').textContent = new Date(reservation.start_time).toLocaleString();
    document.getElementById('detail_end_time').textContent = new Date(reservation.end_time).toLocaleString();
    document.getElementById('detail_status').innerHTML = getStatusBadge(reservation.status);
    document.getElementById('detail_purpose').textContent = reservation.purpose;
    document.getElementById('detail_department').textContent = reservation.department;
    
    document.getElementById('reservation_details').classList.remove('hidden');
}

// Hide reservation details
function hideReservationDetails() {
    document.getElementById('reservation_details').classList.add('hidden');
    selectedReservation = null;
}

// Get status badge HTML
function getStatusBadge(status) {
    const badges = {
        'Pending': '<span class="px-2 py-1 text-xs font-medium rounded-full bg-yellow-100 text-yellow-800">Pending</span>',
        'Approved': '<span class="px-2 py-1 text-xs font-medium rounded-full bg-green-100 text-green-800">Approved</span>',
        'Rejected': '<span class="px-2 py-1 text-xs font-medium rounded-full bg-red-100 text-red-800">Rejected</span>',
        'Reserved': '<span class="px-2 py-1 text-xs font-medium rounded-full bg-blue-100 text-blue-800">Reserved</span>',
    };
    return badges[status] || '<span class="px-2 py-1 text-xs font-medium rounded-full bg-gray-100 text-gray-800">Unknown</span>';
}

// Clear selection
document.getElementById('clear_selection').addEventListener('click', function() {
    document.getElementById('reservation_search').value = '';
    hideReservationDetails();
});

// Hide results when clicking outside
document.addEventListener('click', function(e) {
    if (!e.target.closest('#reservation_search') && !e.target.closest('#search_results')) {
        document.getElementById('search_results').classList.add('hidden');
    }
});

// Handle form submission
document.querySelector('form').addEventListener('submit', function(e) {
    if (!selectedReservation) {
        e.preventDefault();
        alert('Please select a reservation from the search results.');
        return;
    }
    
    // Set the reservation ID value
    document.getElementById('reservation_search').value = selectedReservation.id;
});
</script>

<style>
.hidden {
    display: none !important;
}
</style>
@endsection
