<?php

namespace App\Http\Controllers\AssetLifecycle;

use App\Http\Controllers\Controller;
use App\Models\Asset;
use App\Models\AssetDisposal;
use App\Models\AssetMaintenance;
use App\Models\AssetRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AssetManagementController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Asset::query();

        // Hide assets tagged for disposal from inventory list
        $query->where('status', '!=', 'Disposal');

        // Search functionality - simplified to match our fields
        if ($request->filled('search')) {
            $searchTerm = $request->search;
            $query->where(function($q) use ($searchTerm) {
                $q->where('item_name', 'like', '%' . $searchTerm . '%')
                  ->orWhere('department', 'like', '%' . $searchTerm . '%');
            });
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by date range
        if ($request->filled('date_from')) {
            $query->whereDate('date', '>=', $request->date_from);
        }
        
        if ($request->filled('date_to')) {
            $query->whereDate('date', '<=', $request->date_to);
        }

        $assets = $query->orderBy('created_at', 'desc')->paginate(5);
        
        return view('admin.assetlifecycle.asset-management', compact('assets'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.assetlifecycle.asset-management-create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'item_name' => 'required|string|max:255',
            'quantity' => 'required|integer|min:1',
            'department' => 'required|string|max:255',
            'date' => 'required|date',
            'details' => 'nullable|string|max:1000',
        ]);

        $data = $request->only(['item_name', 'quantity', 'department', 'date', 'details']);
        
        // Add default values for required database fields
        $data['item_number'] = 'ITEM-' . strtoupper(uniqid());
        $data['item_code'] = 'CODE-' . strtoupper(uniqid());
        $data['asset_type'] = 'General';
        $data['category'] = 'General';
        $data['status'] = 'Available';
        $data['created_by'] = 'System';

        Asset::create($data);

        return redirect()->route('admin.assetlifecycle.asset-management')
            ->with('success', 'Asset created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Asset $asset)
    {
        return view('admin.assetlifecycle.asset-management-show', compact('asset'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Asset $asset)
    {
        return view('admin.assetlifecycle.asset-management-edit', compact('asset'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Asset $asset)
    {
        $request->validate([
            'item_name' => 'required|string|max:255',
            'quantity' => 'required|integer|min:1',
            'department' => 'required|string|max:255',
            'status' => 'required|string',
            'date' => 'required|date',
            'details' => 'nullable|string|max:1000',
        ]);

        $data = $request->only(['item_name', 'quantity', 'department', 'status', 'date', 'details']);
        
        // Keep existing values for fields not in the form
        $data['item_number'] = $asset->item_number;
        $data['item_code'] = $asset->item_code;
        $data['asset_type'] = $asset->asset_type;
        $data['category'] = $asset->category;
        $data['condition'] = $asset->condition;

        $asset->update($data);

        return redirect()->route('admin.assetlifecycle.asset-management')
            ->with('success', 'Asset updated successfully.');
    }

    /**
     * Set asset status to under maintenance
     */
    public function setMaintenance(Request $request, Asset $asset)
    {
        $request->validate([
            'maintenance_description' => 'required|string|max:1000',
            'maintenance_date' => 'required|date|after_or_equal:today',
        ]);

        // Update asset status to under maintenance
        $asset->status = 'Under Maintenance';
        $asset->save();

        // Generate maintenance number
        $maintenanceNumber = 'MAINT-' . date('Y') . '-' . str_pad(AssetMaintenance::count() + 1, 4, '0', STR_PAD_LEFT);

        // Create maintenance record
        AssetMaintenance::create([
            'maintenance_number' => $maintenanceNumber,
            'asset_id' => $asset->id,
            'asset_tag' => $asset->item_code,
            'asset_name' => $asset->item_name,
            'maintenance_type' => 'Corrective',
            'status' => 'Scheduled',
            'priority' => 'Medium',
            'scheduled_date' => $request->maintenance_date,
            'start_time' => now(),
            'problem_description' => $request->maintenance_description,
            'work_performed' => '',
            'notes' => '',
            'parts_cost' => 0.00,
            'labor_cost' => 0.00,
            'total_cost' => 0.00,
            'technician_name' => '',
            'technician_email' => '',
            'technician_phone' => '',
            'next_maintenance_notes' => '',
            'next_maintenance_date' => null,
            'performed_by' => auth()->user()->name ?? 'System',
            'approved_by' => '',
        ]);

        return redirect()->route('admin.assetlifecycle.asset-management')
            ->with('success', 'Asset set to maintenance successfully.');
    }

    /**
     * Display maintenance records list
     */
    public function maintenanceList()
    {
        $maintenances = AssetMaintenance::with('asset')->orderBy('created_at', 'desc')->get();
        
        return view('admin.assetlifecycle.asset-maintenance-list', compact('maintenances'));
    }

    /**
     * Remove the specified resource from storage.
     */
    // public function destroy(Asset $asset)
    // {
    //     // Delete functionality removed
    // }

    /**
     * Dispose asset
     */
    public function disposeAsset(Request $request, Asset $asset)
    {
        $request->validate([
            'disposal_reason' => 'required|string',
        ]);

        if (in_array($asset->status, ['Disposal', 'Disposed'], true)) {
            return redirect()->back()
                ->with('error', 'Asset is already tagged for disposal.');
        }

        $asset->status = 'Disposal';
        $asset->save();

        $disposalNumber = 'DISP-' . date('Y') . '-' . str_pad(AssetDisposal::count() + 1, 4, '0', STR_PAD_LEFT);

        AssetDisposal::create([
            'disposal_number' => $disposalNumber,
            'asset_id' => $asset->id,
            'asset_name' => $asset->item_name,
            'details' => $request->disposal_reason,
            'date' => now()->toDateString(),
            'duration' => null,
            'department' => $asset->department,
            'quantity' => $asset->quantity ?? 1,
            'status' => 'pending',
            'created_by' => auth()->user()->name ?? 'System',
        ]);

        return redirect()->back()
            ->with('success', 'Asset marked for disposal successfully.');
    }

    /**
     * Get assets that need disposal
     */
    public function getAssetsForDisposal()
    {
        $assets = Asset::getAssetsForDisposal();
        return response()->json($assets);
    }

    /**
     * Request asset from warehouse
     */
    public function requestAsset(Request $request)
    {
        $request->validate([
            'asset_id' => 'required|exists:assets,id',
            'request_reason' => 'required|string',
            'department' => 'required|string',
            'urgency' => 'required|in:Low,Medium,High',
        ]);

        $asset = Asset::findOrFail($request->asset_id);

        if ($asset->status !== 'Available') {
            return redirect()->back()
                ->with('error', 'Only available assets can be requested.');
        }

        // Create asset request
        AssetRequest::create([
            'asset_id' => $asset->id,
            'asset_name' => $asset->item_name,
            'asset_code' => $asset->item_code,
            'request_reason' => $request->request_reason,
            'department' => $request->department,
            'urgency' => $request->urgency,
            'status' => 'Pending',
            'requested_by' => 'System',
            'request_date' => now(),
        ]);

        // Update asset status
        $asset->status = 'Requested';
        $asset->save();

        return redirect()->back()
            ->with('success', 'Asset request sent to warehouse successfully.');
    }

    /**
     * Get available assets for request
     */
    public function getAvailableAssets()
    {
        $assets = Asset::where('status', 'Available')
            ->orderBy('item_name', 'asc')
            ->get();

        return response()->json($assets);
    }

    /**
     * Search assets for autocomplete
     */
    public function search(Request $request)
    {
        try {
            $query = $request->get('q', '');
            
            // Always return JSON, even for empty queries
            if (strlen($query) < 2) {
                return response()->json(['status' => 'error', 'message' => 'Query too short']);
            }

            // Simple query first to test
            $assets = Asset::limit(5)->get(['id', 'item_name', 'asset_tag', 'category', 'status']);
            
            return response()->json([
                'status' => 'success', 
                'query' => $query,
                'count' => $assets->count(),
                'data' => $assets
            ]);
        } catch (\Exception $e) {
            \Log::error('Asset search error: ' . $e->getMessage());
            return response()->json([
                'status' => 'error', 
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Export assets to CSV
     */
    public function export(Request $request)
    {
        $query = Asset::query();

        // Apply filters if present - same as index method
        if ($request->filled('search')) {
            $searchTerm = $request->search;
            $query->where(function($q) use ($searchTerm) {
                $q->where('item_name', 'like', '%' . $searchTerm . '%')
                  ->orWhere('department', 'like', '%' . $searchTerm . '%');
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by date range
        if ($request->filled('date_from')) {
            $query->whereDate('date', '>=', $request->date_from);
        }
        
        if ($request->filled('date_to')) {
            $query->whereDate('date', '<=', $request->date_to);
        }

        $assets = $query->orderBy('item_name')->get();

        $filename = 'assets_export_' . date('Y-m-d_H-i-s') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($assets) {
            $file = fopen('php://output', 'w');
            
            // CSV header - only simplified fields
            fputcsv($file, [
                'Asset Name',
                'Quantity',
                'Department',
                'Status',
                'Date',
                'Details'
            ]);

            // CSV rows - only simplified fields
            foreach ($assets as $asset) {
                fputcsv($file, [
                    $asset->item_name,
                    $asset->quantity ?? 1,
                    $asset->department ?? '',
                    $asset->status,
                    $asset->date ? $asset->date->format('Y-m-d') : '',
                    $asset->details ?? ''
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Get available assets for assignment
     */
    public function getAvailableAssetsForAssignment()
    {
        $availableAssets = Asset::where('status', 'Available')
            ->orderBy('asset_name')
            ->get();

        return response()->json($availableAssets);
    }
}
