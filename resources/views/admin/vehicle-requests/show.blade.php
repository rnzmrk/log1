@extends('layouts.app')

@section('title', 'Vehicle Request Details')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h4 class="page-title mb-1">Vehicle Request Details</h4>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('vehicle-requests.index') }}">Vehicle Requests</a></li>
                            <li class="breadcrumb-item active">Details</li>
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
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Reservation Information</h5>
                </div>
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h6 class="text-muted text-sm mb-2">Reservation Details</h6>
                            <table class="table table-borderless table-sm">
                                <tr>
                                    <td class="text-muted" style="width: 140px;">Reservation ID:</td>
                                    <td class="fw-medium">{{ $vehicleRequest->reservation_id }}</td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Vehicle ID:</td>
                                    <td>{{ $vehicleRequest->vehicle_id }}</td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Reserved By:</td>
                                    <td>{{ $vehicleRequest->reserved_by }}</td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Department:</td>
                                    <td>{{ $vehicleRequest->department }}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-muted text-sm mb-2">Schedule</h6>
                            <table class="table table-borderless table-sm">
                                <tr>
                                    <td class="text-muted" style="width: 140px;">Start Time:</td>
                                    <td>{{ $vehicleRequest->formatted_start_time }}</td>
                                </tr>
                                <tr>
                                    <td class="text-muted">End Time:</td>
                                    <td>{{ $vehicleRequest->formatted_end_time }}</td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Status:</td>
                                    <td>{!! $vehicleRequest->status_badge !!}</td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Request Status:</td>
                                    <td><span class="badge bg-secondary">{{ $vehicleRequest->request_status }}</span></td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <div class="border-top pt-3">
                        <h6 class="text-muted text-sm mb-2">Purpose</h6>
                        <p class="mb-0">{{ $vehicleRequest->purpose }}</p>
                    </div>

                    <div class="border-top pt-3 mt-3">
                        <h6 class="text-muted text-sm mb-2">Request Information</h6>
                        <table class="table table-borderless table-sm">
                            <tr>
                                <td class="text-muted" style="width: 140px;">Requested On:</td>
                                <td>{{ $vehicleRequest->created_at->format('M d, Y h:i A') }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted">Last Updated:</td>
                                <td>{{ $vehicleRequest->updated_at->format('M d, Y h:i A') }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Quick Actions</h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <button type="button" id="refresh_single_status" class="btn btn-outline-info">
                            <i class='bx bx-refresh mr-2'></i>
                            Refresh Status
                        </button>
                        
                        @if($vehicleRequest->request_status === 'Pending')
                            <div class="alert alert-warning">
                                <i class='bx bx-info-circle me-2'></i>
                                Your request is currently pending approval.
                            </div>
                        @endif

                        @if($vehicleRequest->status === 'Approved')
                            <div class="alert alert-success">
                                <i class='bx bx-check-circle me-2'></i>
                                Your vehicle reservation has been approved!
                            </div>
                        @endif

                        @if($vehicleRequest->status === 'Rejected')
                            <div class="alert alert-danger">
                                <i class='bx bx-x-circle me-2'></i>
                                Your vehicle reservation has been rejected.
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            @if($vehicleRequest->api_data)
                <div class="card mt-3">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Raw API Data</h5>
                    </div>
                    <div class="card-body">
                        <pre class="bg-light p-3 rounded" style="font-size: 12px; max-height: 300px; overflow-y-auto;">{{ json_encode($vehicleRequest->api_data, JSON_PRETTY_PRINT) }}</pre>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

<script>
// Refresh single request status
document.getElementById('refresh_single_status').addEventListener('click', function() {
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
    
    const container = document.querySelector('.container-fluid');
    container.insertBefore(alertDiv, container.firstChild);
    
    // Auto dismiss after 5 seconds
    setTimeout(() => {
        alertDiv.remove();
    }, 5000);
}
</script>
@endsection
