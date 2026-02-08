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
                  ->orWhere('asset_tag', 'like', '%' . $searchTerm . '%')
                  ->orWhere('asset_name', 'like', '%' . $searchTerm . '%')
                  ->orWhere('technician_name', 'like', '%' . $searchTerm . '%')
                  ->orWhere('problem_description', 'like', '%' . $searchTerm . '%');
            });
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by maintenance type
        if ($request->filled('maintenance_type')) {
            $query->where('maintenance_type', $request->maintenance_type);
        }

        // Filter by priority
        if ($request->filled('priority')) {
            $query->where('priority', $request->priority);
        }

        // Filter by date range
        if ($request->filled('date_from')) {
            $query->whereDate('scheduled_date', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('scheduled_date', '<=', $request->date_to);
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

        $maintenance->update($validated);

        return redirect()->route('asset-maintenance.index')
            ->with('success', 'Maintenance record updated successfully.');
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
     * Export maintenance records to Excel
     */
    public function export(Request $request)
    {
        $query = AssetMaintenance::with('asset');

        // Apply same filters as index
        if ($request->filled('search')) {
            $searchTerm = $request->search;
            $query->where(function($q) use ($searchTerm) {
                $q->where('maintenance_number', 'like', '%' . $searchTerm . '%')
                  ->orWhere('asset_tag', 'like', '%' . $searchTerm . '%')
                  ->orWhere('asset_name', 'like', '%' . $searchTerm . '%')
                  ->orWhere('technician_name', 'like', '%' . $searchTerm . '%')
                  ->orWhere('problem_description', 'like', '%' . $searchTerm . '%');
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('maintenance_type')) {
            $query->where('maintenance_type', $request->maintenance_type);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('scheduled_date', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('scheduled_date', '<=', $request->date_to);
        }

        $maintenances = $query->orderBy('created_at', 'desc')->get();

        // Create CSV export
        $filename = 'maintenance-records-' . date('Y-m-d') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($maintenances) {
            $file = fopen('php://output', 'w');
            
            // CSV Header
            fputcsv($file, [
                'Maintenance Number',
                'Asset Tag',
                'Asset Name',
                'Maintenance Type',
                'Status',
                'Priority',
                'Scheduled Date',
                'Start Time',
                'End Time',
                'Problem Description',
                'Work Performed',
                'Technician Name',
                'Parts Cost',
                'Labor Cost',
                'Total Cost',
                'Notes'
            ]);

            // CSV Data
            foreach ($maintenances as $maintenance) {
                fputcsv($file, [
                    $maintenance->maintenance_number,
                    $maintenance->asset_tag,
                    $maintenance->asset_name,
                    $maintenance->maintenance_type,
                    $maintenance->status,
                    $maintenance->priority,
                    $maintenance->scheduled_date->format('Y-m-d'),
                    $maintenance->start_time ? $maintenance->start_time->format('Y-m-d H:i:s') : '',
                    $maintenance->end_time ? $maintenance->end_time->format('Y-m-d H:i:s') : '',
                    $maintenance->problem_description,
                    $maintenance->work_performed,
                    $maintenance->technician_name,
                    $maintenance->parts_cost,
                    $maintenance->labor_cost,
                    $maintenance->total_cost,
                    $maintenance->notes
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
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
