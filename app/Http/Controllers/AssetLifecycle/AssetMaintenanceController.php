<?php

namespace App\Http\Controllers\AssetLifecycle;

use App\Http\Controllers\Controller;
use App\Models\AssetMaintenance;
use App\Models\Asset;
use Illuminate\Http\Request;

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
        
        return view('admin.assetlifecycle.asset-maintenance', compact('maintenances'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $assets = Asset::where('status', '!=', 'Retired')->get();
        return view('admin.assetlifecycle.asset-maintenance-create', compact('assets'));
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
        $validated['asset_tag'] = $asset->asset_tag;
        $validated['asset_name'] = $asset->asset_name;

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
        $assets = Asset::where('status', '!=', 'Retired')->get();
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
        $validated['asset_tag'] = $asset->asset_tag;
        $validated['asset_name'] = $asset->asset_name;

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
}
