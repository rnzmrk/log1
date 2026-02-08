@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1>Logistics Dashboard</h1>
                <div>
                    <a href="{{ route('logistics.outbound.create') }}" class="btn btn-warning">
                        <i class="fas fa-truck"></i> Outbound Delivery
                    </a>
                    <a href="{{ route('logistics.inbound.create') }}" class="btn btn-success">
                        <i class="fas fa-box"></i> Inbound Acceptance
                    </a>
                </div>
            </div>

            <!-- Summary Cards -->
            <div class="row mb-4">
                <div class="col-md-4">
                    <div class="card bg-warning text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h4>{{ $pendingDeliveries->count() }}</h4>
                                    <p>Pending Deliveries</p>
                                </div>
                                <i class="fas fa-shipping-fast fa-2x"></i>
                            </div>
                        </div>
                        <div class="card-footer">
                            <a href="{{ route('logistics.pending') }}" class="text-white">View Details <i class="fas fa-arrow-right"></i></a>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card bg-success text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h4>{{ $recentInbound->count() }}</h4>
                                    <p>Recent Inbound</p>
                                </div>
                                <i class="fas fa-sign-in-alt fa-2x"></i>
                            </div>
                        </div>
                        <div class="card-footer">
                            <a href="{{ route('logistics.history') }}" class="text-white">View History <i class="fas fa-arrow-right"></i></a>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card bg-info text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h4>{{ $recentOutbound->count() }}</h4>
                                    <p>Recent Outbound</p>
                                </div>
                                <i class="fas fa-sign-out-alt fa-2x"></i>
                            </div>
                        </div>
                        <div class="card-footer">
                            <a href="{{ route('logistics.history') }}" class="text-white">View History <i class="fas fa-arrow-right"></i></a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Pending Deliveries -->
            @if($pendingDeliveries->count() > 0)
            <div class="card mb-4">
                <div class="card-header bg-warning text-dark">
                    <h5 class="mb-0"><i class="fas fa-shipping-fast"></i> Pending Deliveries - Ready for Inbound Processing</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Request ID</th>
                                    <th>Item Name</th>
                                    <th>Quantity</th>
                                    <th>Supplier</th>
                                    <th>Order Date</th>
                                    <th>Expected Delivery</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($pendingDeliveries as $delivery)
                                <tr>
                                    <td>{{ $delivery->request_id }}</td>
                                    <td>{{ $delivery->item_name }}</td>
                                    <td>{{ $delivery->quantity_approved }}</td>
                                    <td>{{ $delivery->supplier ?? 'N/A' }}</td>
                                    <td>{{ $delivery->order_date ? $delivery->order_date->format('M d, Y') : 'N/A' }}</td>
                                    <td>{{ $delivery->expected_delivery ? $delivery->expected_delivery->format('M d, Y') : 'N/A' }}</td>
                                    <td>
                                        <a href="{{ route('logistics.inbound.create') }}" class="btn btn-sm btn-success">
                                            <i class="fas fa-check"></i> Accept Delivery
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            @endif

            <div class="row">
                <!-- Recent Inbound -->
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header bg-success text-white">
                            <h5 class="mb-0"><i class="fas fa-sign-in-alt"></i> Recent Inbound Acceptances</h5>
                        </div>
                        <div class="card-body">
                            @if($recentInbound->count() > 0)
                                <div class="table-responsive">
                                    <table class="table table-sm">
                                        <thead>
                                            <tr>
                                                <th>Item</th>
                                                <th>Quantity</th>
                                                <th>Date</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($recentInbound as $inbound)
                                            <tr>
                                                <td>{{ $inbound->notes }}</td>
                                                <td>{{ json_decode($inbound->new_values, true)['quantity_received'] ?? 'N/A' }}</td>
                                                <td>{{ $inbound->created_at->format('M d, H:i') }}</td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <p class="text-muted">No recent inbound deliveries.</p>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Recent Outbound -->
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header bg-info text-white">
                            <h5 class="mb-0"><i class="fas fa-sign-out-alt"></i> Recent Outbound Deliveries</h5>
                        </div>
                        <div class="card-body">
                            @if($recentOutbound->count() > 0)
                                <div class="table-responsive">
                                    <table class="table table-sm">
                                        <thead>
                                            <tr>
                                                <th>Item</th>
                                                <th>Quantity</th>
                                                <th>Destination</th>
                                                <th>Date</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($recentOutbound as $outbound)
                                            <tr>
                                                <td>{{ $outbound->notes }}</td>
                                                <td>{{ json_decode($outbound->new_values, true)['quantity'] ?? 'N/A' }}</td>
                                                <td>{{ json_decode($outbound->new_values, true)['destination'] ?? 'N/A' }}</td>
                                                <td>{{ $outbound->created_at->format('M d, H:i') }}</td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <p class="text-muted">No recent outbound deliveries.</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="card mt-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-bolt"></i> Quick Actions</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="d-grid">
                                <a href="{{ route('logistics.outbound.create') }}" class="btn btn-warning btn-lg">
                                    <i class="fas fa-truck"></i> Create Outbound Delivery
                                </a>
                                <p class="text-muted small mt-2">Process outbound deliveries to departments or locations</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="d-grid">
                                <a href="{{ route('logistics.inbound.create') }}" class="btn btn-success btn-lg">
                                    <i class="fas fa-box"></i> Accept Inbound Delivery
                                </a>
                                <p class="text-muted small mt-2">Accept incoming supplies and update inventory</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
