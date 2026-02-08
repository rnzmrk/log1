@extends('layouts.app')

@section('title', 'Vehicle Requests')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h4 class="page-title mb-1">Vehicle Requests</h4>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item active">Vehicle Requests</li>
                        </ol>
                    </nav>
                </div>
                <div class="d-flex gap-2">
                    <button type="button" id="refresh_status" class="btn btn-outline-info">
                        <i class='bx bx-refresh mr-2'></i>
                        Refresh Status
                    </button>
                    <a href="{{ route('vehicle-requests.create') }}" class="btn btn-primary">
                        <i class='bx bx-plus mr-2'></i>
                        New Request
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">My Vehicle Requests</h5>
                    <p class="text-muted text-sm mt-1 mb-0">Track your vehicle reservation requests and their status</p>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @forelse ($vehicleRequests as $request)
                        <div class="border rounded-lg mb-3 hover:shadow-md transition-shadow">
                            <div class="p-4">
                                <div class="row align-items-center">
                                    <div class="col-md-8">
                                        <div class="d-flex align-items-center mb-2">
                                            <h6 class="mb-0 me-3">Reservation #{{ $request->reservation_id }}</h6>
                                            {!! $request->status_badge !!}
                                        </div>
                                        <div class="row text-sm">
                                            <div class="col-md-6">
                                                <p class="mb-1"><strong>Reserved By:</strong> {{ $request->reserved_by }}</p>
                                                <p class="mb-1"><strong>Vehicle ID:</strong> {{ $request->vehicle_id }}</p>
                                                <p class="mb-1"><strong>Department:</strong> {{ $request->department }}</p>
                                            </div>
                                            <div class="col-md-6">
                                                <p class="mb-1"><strong>Start Time:</strong> {{ $request->formatted_start_time }}</p>
                                                <p class="mb-1"><strong>End Time:</strong> {{ $request->formatted_end_time }}</p>
                                                <p class="mb-1"><strong>Purpose:</strong> {{ $request->purpose }}</p>
                                            </div>
                                        </div>
                                        <div class="mt-2">
                                            <p class="mb-0 text-sm text-muted">
                                                <strong>Request Status:</strong> 
                                                <span class="badge bg-secondary">{{ $request->request_status }}</span>
                                                <span class="ms-2">Requested on {{ $request->created_at->format('M d, Y h:i A') }}</span>
                                            </p>
                                        </div>
                                    </div>
                                    <div class="col-md-4 text-end">
                                        <div class="d-flex flex-column gap-2">
                                            <a href="{{ route('vehicle-requests.show', $request->id) }}" class="btn btn-outline-primary btn-sm">
                                                <i class='bx bx-eye mr-1'></i>
                                                View Details
                                            </a>
                                            @if($request->request_status === 'Pending')
                                                <span class="text-sm text-muted">
                                                    <i class='bx bx-time-five'></i>
                                                    Awaiting approval...
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-5">
                            <i class='bx bx-car text-4xl text-gray-300 mb-3'></i>
                            <h5 class="text-gray-600 mb-2">No Vehicle Requests Yet</h5>
                            <p class="text-gray-500 mb-4">Create your first vehicle request to get started.</p>
                            <a href="{{ route('vehicle-requests.create') }}" class="btn btn-primary">
                                <i class='bx bx-plus mr-2'></i>
                                Create Request
                            </a>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Refresh status from API
document.getElementById('refresh_status').addEventListener('click', function() {
    const btn = this;
    const originalHtml = btn.innerHTML;
    
    btn.disabled = true;
    btn.innerHTML = '<i class="bx bx-loader-alt bx-spin mr-2"></i>Refreshing...';
    
    fetch('/vehicle-requests/update-status')
        .then(response => response.json())
        .then(data => {
            if (data.error) {
                showNotification('Error updating status: ' + data.error, 'error');
            } else {
                showNotification(data.message, 'success');
                // Reload page after 2 seconds to show updated status
                setTimeout(() => {
                    window.location.reload();
                }, 2000);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showNotification('Failed to update status', 'error');
        })
        .finally(() => {
            btn.disabled = false;
            btn.innerHTML = originalHtml;
        });
});

// Show notification
function showNotification(message, type = 'info') {
    const alertDiv = document.createElement('div');
    alertDiv.className = `alert alert-${type} alert-dismissible fade show`;
    alertDiv.innerHTML = `
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
    
    const cardBody = document.querySelector('.card-body');
    cardBody.insertBefore(alertDiv, cardBody.firstChild);
    
    // Auto dismiss after 5 seconds
    setTimeout(() => {
        alertDiv.remove();
    }, 5000);
}
</script>
@endsection
