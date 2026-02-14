<?php

namespace App\Http\Controllers\AssetLifecycle;

use App\Http\Controllers\Controller;
use App\Models\AssetMaintenance;
use App\Models\Asset;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\AssetMaintenanceExport;

class AssetMaintenanceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = AssetMaintenance::with('asset');

        // Search functionality
        if ($request->filled('search')) {
            $searchTerm = $request->search;
            $query->where(function($q) use ($searchTerm) {
                $q->where('maintenance_number', 'like', '%' . $searchTerm . '%')
                  ->orWhere('asset_name', 'like', '%' . $searchTerm . '%')
                  ->orWhere('problem_description', 'like', '%' . $searchTerm . '%');
            });
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by month
        if ($request->filled('month')) {
            $query->whereMonth('scheduled_date', $request->month);
        }

        // Filter by year
        if ($request->filled('year')) {
            $query->whereYear('scheduled_date', $request->year);
        }

        $maintenances = $query->orderBy('created_at', 'desc')->paginate(10);
        
        // Get statistics
        $stats = [
            'total' => AssetMaintenance::count(),
            'scheduled' => AssetMaintenance::where('status', 'Scheduled')->count(),
            'in_progress' => AssetMaintenance::where('status', 'In Progress')->count(),
            'completed' => AssetMaintenance::where('status', 'Completed')->count(),
            'overdue' => AssetMaintenance::where('status', 'Scheduled')
                ->where('scheduled_date', '<', now())
                ->count(),
        ];
        
        return view('admin.assetlifecycle.asset-maintenance', compact('maintenances', 'stats'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $assets = Asset::where('status', '!=', 'Retired')
            ->where('status', '!=', 'Disposed')
            ->get();
            
        // Get assets needing maintenance
        $assetsNeedingMaintenance = Asset::getAssetsNeedingMaintenance();
        
        return view('admin.assetlifecycle.asset-maintenance-create', compact('assets', 'assetsNeedingMaintenance'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'asset_id' => 'required|exists:assets,id',
            'maintenance_type' => 'required|in:Preventive,Corrective,Emergency,Predictive,Calibration',
            'status' => 'required|in:Scheduled,In Progress,Completed,On Hold,Cancelled',
            'priority' => 'required|in:Low,Medium,High,Urgent',
            'scheduled_date' => 'required|date',
            'start_time' => 'nullable|datetime',
            'end_time' => 'nullable|datetime',
            'problem_description' => 'nullable|string',
            'work_performed' => 'nullable|string',
            'notes' => 'nullable|string',
            'parts_cost' => 'nullable|numeric|min:0|max:999999999999.99',
            'labor_cost' => 'nullable|numeric|min:0|max:999999999999.99',
            'technician_name' => 'nullable|string|max:255',
            'technician_email' => 'nullable|email|max:255',
            'technician_phone' => 'nullable|string|max:255',
            'next_maintenance_notes' => 'nullable|string',
            'next_maintenance_date' => 'nullable|date',
            'performed_by' => 'nullable|string|max:255',
            'approved_by' => 'nullable|string|max:255',
        ]);

        // Get asset information
        $asset = Asset::findOrFail($validated['asset_id']);
        $validated['asset_tag'] = $asset->asset_tag ?? $asset->item_code;
        $validated['asset_name'] = $asset->item_name;

        // Auto-generate maintenance number
        $validated['maintenance_number'] = 'MTN-' . date('Y') . '-' . str_pad(AssetMaintenance::count() + 1, 4, '0', STR_PAD_LEFT);

        AssetMaintenance::create($validated);

        return redirect()->route('asset-maintenance.index')
            ->with('success', 'Maintenance record created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $maintenance = AssetMaintenance::with('asset')->findOrFail($id);
        return view('admin.assetlifecycle.asset-maintenance-show', compact('maintenance'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $maintenance = AssetMaintenance::findOrFail($id);
        $assets = Asset::where('status', '!=', 'Retired')
            ->where('status', '!=', 'Disposed')
            ->get();
        return view('admin.assetlifecycle.asset-maintenance-edit', compact('maintenance', 'assets'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $maintenance = AssetMaintenance::findOrFail($id);

        $validated = $request->validate([
            'status' => 'required|in:Scheduled,In Progress,Completed,On Hold,Cancelled',
            'scheduled_date' => 'required|date',
            'problem_description' => 'nullable|string',
            'asset_id' => 'nullable|exists:assets,id',
            'maintenance_type' => 'nullable|in:Preventive,Corrective,Emergency,Predictive,Calibration',
            'priority' => 'nullable|in:Low,Medium,High,Urgent',
            'start_time' => 'nullable|datetime',
            'end_time' => 'nullable|datetime',
            'work_performed' => 'nullable|string',
            'notes' => 'nullable|string',
            'parts_cost' => 'nullable|numeric|min:0|max:999999999999.99',
            'labor_cost' => 'nullable|numeric|min:0|max:999999999999.99',
            'technician_name' => 'nullable|string|max:255',
            'technician_email' => 'nullable|email|max:255',
            'technician_phone' => 'nullable|string|max:255',
            'next_maintenance_notes' => 'nullable|string',
            'next_maintenance_date' => 'nullable|date',
            'performed_by' => 'nullable|string|max:255',
            'approved_by' => 'nullable|string|max:255',
        ]);

        // Only update asset information if asset_id is provided
        if (isset($validated['asset_id'])) {
            $asset = Asset::findOrFail($validated['asset_id']);
            $validated['asset_tag'] = $asset->asset_tag ?? $asset->item_code;
            $validated['asset_name'] = $asset->item_name;
        } else {
            // Remove asset_id from validation if not provided
            unset($validated['asset_id']);
        }

        $maintenance->update($validated);

        return redirect()->route('asset-maintenance.index')
            ->with('success', 'Maintenance record updated successfully.');
    }

    /**
     * Update maintenance status (approve/reject)
     */
    public function updateStatus(Request $request, string $id)
    {
        $maintenance = AssetMaintenance::findOrFail($id);

        $validated = $request->validate([
            'status' => 'required|in:Scheduled,In Progress,Completed,On Hold,Cancelled',
        ]);

        $maintenance->update($validated);

        $statusMessage = match($validated['status']) {
            'In Progress' => 'approved',
            'On Hold' => 'rejected',
            'Completed' => 'marked as done',
            'Scheduled' => 'reset to pending',
            'Cancelled' => 'cancelled',
            default => 'updated'
        };

        return redirect()->route('admin.assetlifecycle.asset-maintenance')
            ->with('success', "Maintenance request {$statusMessage} successfully.");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $maintenance = AssetMaintenance::findOrFail($id);
        $maintenance->delete();

        return redirect()->route('asset-maintenance.index')
            ->with('success', 'Maintenance record deleted successfully.');
    }

    /**
     * Export maintenance records to CSV
     */
    public function export(Request $request)
    {
        $query = AssetMaintenance::query();

        // Apply same filters as index
        if ($request->filled('search')) {
            $searchTerm = $request->search;
            $query->where(function($q) use ($searchTerm) {
                $q->where('maintenance_number', 'like', '%' . $searchTerm . '%')
                  ->orWhere('asset_name', 'like', '%' . $searchTerm . '%')
                  ->orWhere('problem_description', 'like', '%' . $searchTerm . '%');
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('month')) {
            $query->whereMonth('scheduled_date', $request->month);
        }

        if ($request->filled('year')) {
            $query->whereYear('scheduled_date', $request->year);
        }

        $maintenances = $query->orderBy('created_at', 'desc')->get();

        // Create CSV content
        $csvContent = '';
        
        // CSV Header
        $csvContent .= "Maintenance ID,Asset Name,Maintenance Description,Schedule Date,Status\n";

        // CSV Data
        foreach ($maintenances as $maintenance) {
            // Convert status to custom labels
            $statusLabel = match($maintenance->status) {
                'Scheduled' => 'pending',
                'In Progress' => 'ongoing',
                'Completed' => 'done',
                'On Hold' => 'reject',
                default => $maintenance->status
            };

            $csvContent .= '"' . $maintenance->maintenance_number . '",';
            $csvContent .= '"' . $maintenance->asset_name . '",';
            $csvContent .= '"' . ($maintenance->problem_description ?? '') . '",';
            $csvContent .= '"' . ($maintenance->scheduled_date ? $maintenance->scheduled_date->format('M d, Y') : '') . '",';
            $csvContent .= '"' . $statusLabel . '"' . "\n";
        }

        // Create filename
        $filename = 'maintenance-records-' . date('Y-m-d') . '.csv';

        // Return download response
        return response($csvContent)
            ->header('Content-Type', 'text/csv')
            ->header('Content-Disposition', 'attachment; filename="' . $filename . '"')
            ->header('Cache-Control', 'no-cache, must-revalidate')
            ->header('Pragma', 'no-cache')
            ->header('Expires', '0');
    }

    /**
     * Get asset details for AJAX requests
     */
    public function getAssetDetails($assetId)
    {
        $asset = Asset::findOrFail($assetId);
        
        return response()->json([
            'asset_tag' => $asset->asset_tag ?? $asset->item_code,
            'asset_name' => $asset->item_name,
            'last_maintenance_date' => $asset->last_maintenance_date ? $asset->last_maintenance_date->format('Y-m-d') : null,
            'next_maintenance_date' => $asset->next_maintenance_date ? $asset->next_maintenance_date->format('Y-m-d') : null,
            'condition' => $asset->condition,
            'location' => $asset->location,
        ]);
    }

    /**
     * Bulk actions on maintenance records
     */
    public function bulkAction(Request $request)
    {
        $request->validate([
            'action' => 'required|in:delete,update_status',
            'maintenance_ids' => 'required|array',
            'maintenance_ids.*' => 'exists:asset_maintenances,id',
            'status' => 'required_if:action,update_status|in:Scheduled,In Progress,Completed,On Hold,Cancelled',
        ]);

        $maintenanceIds = $request->maintenance_ids;

        if ($request->action === 'delete') {
            AssetMaintenance::whereIn('id', $maintenanceIds)->delete();
            return redirect()->back()->with('success', 'Maintenance records deleted successfully.');
        }

        if ($request->action === 'update_status') {
            AssetMaintenance::whereIn('id', $maintenanceIds)->update(['status' => $request->status]);
            return redirect()->back()->with('success', 'Maintenance records updated successfully.');
        }

        return redirect()->back()->with('error', 'Invalid action.');
    }
}
