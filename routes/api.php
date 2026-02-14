<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\InvoiceController;
use App\Http\Controllers\Api\PurchaseOrderController;
use App\Http\Controllers\Api\GrnController;
use App\Http\Controllers\Api\RequestSupplyController;
use App\Http\Controllers\Api\ProjectController;
use App\Http\Controllers\VehicleRequestController;
use App\Models\AssetDisposal;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// API Routes for Procurement
// Invoice API
Route::get('/invoices', [InvoiceController::class, 'index']);

// Purchase Order API
Route::get('/purchase-orders', [PurchaseOrderController::class, 'index']);

// GRN API
Route::get('/grns', [GrnController::class, 'index']);

// Supply Request API
Route::get('/supply-requests', [RequestSupplyController::class, 'index']);
Route::post('/supply-requests', [RequestSupplyController::class, 'store']);
Route::get('/supply-requests/{id}', [RequestSupplyController::class, 'show']);

// Vehicle Fleet API
Route::get('/logistictracking/vehicle-fleet', [VehicleRequestController::class, 'getVehicleFleet']);
Route::get('/logistictracking/vehicle-requests', [VehicleRequestController::class, 'getVehicleRequests']);
Route::post('/logistictracking/create-dispatch', [VehicleRequestController::class, 'createDispatch']);

// Asset Disposal API
Route::get('/assetlifecycle/asset-disposal', function () {
    $records = AssetDisposal::orderBy('created_at', 'desc')->get();

    return response()->json([
        'data' => $records,
        'total' => $records->count(),
    ]);
});

Route::post('/assetlifecycle/asset-disposal/{id}/approve', function ($id) {
    $record = AssetDisposal::find($id);

    if (!$record) {
        return response()->json(['message' => 'Disposal record not found'], 404);
    }

    if ($record->status !== 'pending') {
        return response()->json(['message' => 'Only pending disposals can be approved'], 422);
    }

    $record->status = 'approved';
    $record->save();

    return response()->json(['message' => 'Approved', 'data' => $record]);
});

// Project Planning API
Route::get('/project-planning', function (Request $request) {
    $query = \App\Models\ProjectPlanning::query();

    // Search functionality
    if ($request->filled('search')) {
        $searchTerm = $request->search;
        $query->where(function($q) use ($searchTerm) {
            $q->where('project_number', 'like', '%' . $searchTerm . '%')
              ->orWhere('project_name', 'like', '%' . $searchTerm . '%')
              ->orWhere('project_description', 'like', '%' . $searchTerm . '%')
              ->orWhere('requested_by', 'like', '%' . $searchTerm . '%')
              ->orWhere('project_address', 'like', '%' . $searchTerm . '%');
        });
    }

    // Filter by status
    if ($request->filled('status')) {
        $query->where('status', $request->status);
    }

    // Filter by project type
    if ($request->filled('project_type')) {
        $query->where('project_type', $request->project_type);
    }

    // Filter by priority
    if ($request->filled('priority')) {
        $query->where('priority', $request->priority);
    }

    // Filter by date range
    if ($request->filled('date_from')) {
        $query->whereDate('start_date', '>=', $request->date_from);
    }
    if ($request->filled('date_to')) {
        $query->whereDate('start_date', '<=', $request->date_to);
    }

    $projects = $query->orderBy('created_at', 'desc')->paginate(10);
    
    return response()->json($projects);
});

// Project Planning CSV Export
Route::get('/project-planning/export/csv', function (Request $request) {
    $query = \App\Models\ProjectPlanning::query();

    // Apply same filters as the main API
    if ($request->filled('search')) {
        $searchTerm = $request->search;
        $query->where(function($q) use ($searchTerm) {
            $q->where('project_number', 'like', '%' . $searchTerm . '%')
              ->orWhere('project_name', 'like', '%' . $searchTerm . '%')
              ->orWhere('project_description', 'like', '%' . $searchTerm . '%')
              ->orWhere('requested_by', 'like', '%' . $searchTerm . '%')
              ->orWhere('project_address', 'like', '%' . $searchTerm . '%');
        });
    }

    if ($request->filled('status')) {
        $query->where('status', $request->status);
    }

    if ($request->filled('project_type')) {
        $query->where('project_type', $request->project_type);
    }

    if ($request->filled('priority')) {
        $query->where('priority', $request->priority);
    }

    if ($request->filled('date_from')) {
        $query->whereDate('start_date', '>=', $request->date_from);
    }

    if ($request->filled('date_to')) {
        $query->whereDate('start_date', '<=', $request->date_to);
    }

    $projects = $query->orderBy('created_at', 'desc')->get();

    // Create CSV content
    $headers = [
        'Project ID',
        'Project Name',
        'Description',
        'Type',
        'Priority',
        'Status',
        'Start Date',
        'End Date',
        'Address',
        'City',
        'State',
        'ZIP Code',
        'Requested By',
        'Department',
        'Approved By',
        'Approval Date',
        'Created Date',
        'Notes'
    ];

    $csvRows = [];
    $csvRows[] = implode(',', array_map(function($header) {
        return '"' . str_replace('"', '""', $header) . '"';
    }, $headers));

    foreach ($projects as $project) {
        $row = [
            $project->project_number ?: '#' . $project->id,
            $project->project_name,
            $project->project_description,
            $project->project_type,
            $project->priority,
            $project->status,
            $project->start_date ? \Carbon\Carbon::parse($project->start_date)->format('Y-m-d') : '',
            $project->end_date ? \Carbon\Carbon::parse($project->end_date)->format('Y-m-d') : '',
            $project->project_address,
            $project->project_city,
            $project->project_state,
            $project->project_zipcode,
            $project->requested_by,
            $project->department,
            $project->approved_by,
            $project->approved_date ? \Carbon\Carbon::parse($project->approved_date)->format('Y-m-d') : '',
            $project->created_at->format('Y-m-d H:i:s'),
            $project->notes
        ];

        $csvRows[] = implode(',', array_map(function($field) {
            return '"' . str_replace('"', '""', $field) . '"';
        }, $row));
    }

    $csvContent = implode("\n", $csvRows);

    // Return CSV download
    return response($csvContent)
        ->header('Content-Type', 'text/csv')
        ->header('Content-Disposition', 'attachment; filename="project-planning-' . date('Y-m-d') . '.csv"');
});

// Test route for debugging
Route::post('/logistictracking/test', function() {
    return response()->json(['message' => 'POST route works']);
});

// Project Planning API Routes
Route::apiResource('projects', ProjectController::class);
