@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1>Inventory Dashboard</h1>
                <a href="{{ route('inventory.index') }}" class="btn btn-primary">Manage Inventory</a>
            </div>

            <!-- Summary Cards -->
            <div class="row mb-4">
                <div class="col-md-3">
                    <div class="card bg-danger text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h4>{{ $outOfStockItems->count() }}</h4>
                                    <p>Out of Stock</p>
                                </div>
                                <i class="fas fa-exclamation-triangle fa-2x"></i>
                            </div>
                        </div>
                        <div class="card-footer">
                            <a href="{{ route('inventory.out-of-stock') }}" class="text-white">View Details <i class="fas fa-arrow-right"></i></a>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-warning text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h4>{{ $lowStockItems->count() }}</h4>
                                    <p>Low Stock</p>
                                </div>
                                <i class="fas fa-exclamation-circle fa-2x"></i>
                            </div>
                        </div>
                        <div class="card-footer">
                            <a href="{{ route('inventory.low-stock') }}" class="text-white">View Details <i class="fas fa-arrow-right"></i></a>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-success text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h4>{{ $pendingProcurements->count() }}</h4>
                                    <p>Pending Procurements</p>
                                </div>
                                <i class="fas fa-shopping-cart fa-2x"></i>
                            </div>
                        </div>
                        <div class="card-footer">
                            <a href="{{ route('procurement.index') }}" class="text-white">View Details <i class="fas fa-arrow-right"></i></a>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-info text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h4>{{ \App\Models\Inventory::count() }}</h4>
                                    <p>Total Items</p>
                                </div>
                                <i class="fas fa-boxes fa-2x"></i>
                            </div>
                        </div>
                        <div class="card-footer">
                            <a href="{{ route('inventory.index') }}" class="text-white">View All <i class="fas fa-arrow-right"></i></a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Low Stock Items -->
            @if($lowStockItems->count() > 0)
            <div class="card mb-4">
                <div class="card-header bg-warning text-dark">
                    <h5 class="mb-0"><i class="fas fa-exclamation-circle"></i> Low Stock Items - Action Required</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Item Name</th>
                                    <th>Category</th>
                                    <th>Current Stock</th>
                                    <th>Status</th>
                                    <th>Location</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($lowStockItems as $item)
                                <tr>
                                    <td>{{ $item->item_name }}</td>
                                    <td>{{ $item->category }}</td>
                                    <td>
                                        <span class="badge bg-warning">{{ $item->stock }}</span>
                                    </td>
                                    <td>
                                        <span class="badge bg-warning">{{ $item->status }}</span>
                                    </td>
                                    <td>{{ $item->location }}</td>
                                    <td>
                                        <a href="{{ route('inventory.procurement-request', $item) }}" class="btn btn-sm btn-primary">
                                            <i class="fas fa-plus"></i> Request Procurement
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

            <!-- Out of Stock Items -->
            @if($outOfStockItems->count() > 0)
            <div class="card mb-4">
                <div class="card-header bg-danger text-white">
                    <h5 class="mb-0"><i class="fas fa-exclamation-triangle"></i> Out of Stock Items - Urgent Action Required</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Item Name</th>
                                    <th>Category</th>
                                    <th>Last Updated</th>
                                    <th>Location</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($outOfStockItems as $item)
                                <tr>
                                    <td>{{ $item->item_name }}</td>
                                    <td>{{ $item->category }}</td>
                                    <td>{{ $item->last_updated ? $item->last_updated->format('M d, Y') : 'N/A' }}</td>
                                    <td>{{ $item->location }}</td>
                                    <td>
                                        <a href="{{ route('inventory.procurement-request', $item) }}" class="btn btn-sm btn-danger">
                                            <i class="fas fa-exclamation-triangle"></i> Urgent Procurement
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

            <!-- Pending Procurements -->
            @if($pendingProcurements->count() > 0)
            <div class="card">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0"><i class="fas fa-shopping-cart"></i> Pending Procurement Requests</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Request ID</th>
                                    <th>Item Name</th>
                                    <th>Quantity</th>
                                    <th>Priority</th>
                                    <th>Status</th>
                                    <th>Request Date</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($pendingProcurements as $request)
                                <tr>
                                    <td>{{ $request->request_id }}</td>
                                    <td>{{ $request->item_name }}</td>
                                    <td>{{ $request->quantity_requested }}</td>
                                    <td>
                                        <span class="badge bg-{{ $request->priority === 'High' ? 'danger' : ($request->priority === 'Medium' ? 'warning' : 'info') }}">
                                            {{ $request->priority }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge bg-{{ $request->status === 'Pending' ? 'warning' : ($request->status === 'Approved' ? 'info' : 'primary') }}">
                                            {{ $request->status }}
                                        </span>
                                    </td>
                                    <td>{{ $request->request_date->format('M d, Y') }}</td>
                                    <td>
                                        <a href="{{ route('procurement.show', $request) }}" class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-eye"></i> View
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

            @if($lowStockItems->count() == 0 && $outOfStockItems->count() == 0 && $pendingProcurements->count() == 0)
            <div class="card">
                <div class="card-body text-center">
                    <i class="fas fa-check-circle fa-3x text-success mb-3"></i>
                    <h4>All Systems Operational</h4>
                    <p class="text-muted">No low stock items or pending procurement requests. Your inventory is in good condition!</p>
                    <a href="{{ route('inventory.index') }}" class="btn btn-primary">Manage Inventory</a>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
