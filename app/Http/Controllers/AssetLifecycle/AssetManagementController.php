<?php

namespace App\Http\Controllers\AssetLifecycle;

use App\Http\Controllers\Controller;
use App\Models\Asset;
use Illuminate\Http\Request;

class AssetManagementController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Asset::query();

        // Search functionality
        if ($request->filled('search')) {
            $searchTerm = $request->search;
            $query->where(function($q) use ($searchTerm) {
                $q->where('asset_tag', 'like', '%' . $searchTerm . '%')
                  ->orWhere('asset_name', 'like', '%' . $searchTerm . '%')
                  ->orWhere('asset_type', 'like', '%' . $searchTerm . '%')
                  ->orWhere('category', 'like', '%' . $searchTerm . '%')
                  ->orWhere('brand', 'like', '%' . $searchTerm . '%')
                  ->orWhere('model', 'like', '%' . $searchTerm . '%')
                  ->orWhere('serial_number', 'like', '%' . $searchTerm . '%')
                  ->orWhere('assigned_to', 'like', '%' . $searchTerm . '%');
            });
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by condition
        if ($request->filled('condition')) {
            $query->where('condition', $request->condition);
        }

        // Filter by category
        if ($request->filled('category')) {
            $query->where('category', 'like', '%' . $request->category . '%');
        }

        // Filter by department
        if ($request->filled('department')) {
            $query->where('department', 'like', '%' . $request->department . '%');
        }

        $assets = $query->orderBy('created_at', 'desc')->paginate(10);
        
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
        $validated = $request->validate([
            'asset_name' => 'required|string|max:255',
            'asset_type' => 'required|string|max:255',
            'category' => 'required|string|max:255',
            'brand' => 'nullable|string|max:255',
            'model' => 'nullable|string|max:255',
            'serial_number' => 'nullable|string|max:255',
            'status' => 'required|in:Available,In Use,Under Maintenance,Retired,Lost,Damaged',
            'condition' => 'required|in:Excellent,Good,Fair,Poor',
            'purchase_cost' => 'nullable|numeric|min:0|max:999999999999.99',
            'purchase_date' => 'nullable|date',
            'warranty_expiry' => 'nullable|date|after:purchase_date',
            'last_maintenance_date' => 'nullable|date',
            'next_maintenance_date' => 'nullable|date',
            'assigned_to' => 'nullable|string|max:255',
            'department' => 'nullable|string|max:255',
            'location' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
            'specifications' => 'nullable|string',
            'created_by' => 'required|string|max:255',
        ]);

        // Auto-generate asset tag
        $validated['asset_tag'] = 'AST-' . date('Y') . '-' . str_pad(Asset::count() + 1, 4, '0', STR_PAD_LEFT);

        Asset::create($validated);

        return redirect()->route('asset-management.index')
            ->with('success', 'Asset created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $asset = Asset::findOrFail($id);
        return view('admin.assetlifecycle.asset-management-show', compact('asset'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $asset = Asset::findOrFail($id);
        return view('admin.assetlifecycle.asset-management-edit', compact('asset'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $asset = Asset::findOrFail($id);

        $validated = $request->validate([
            'asset_tag' => 'required|string|unique:assets,asset_tag,'.$id,
            'asset_name' => 'required|string|max:255',
            'asset_type' => 'required|string|max:255',
            'category' => 'required|string|max:255',
            'brand' => 'nullable|string|max:255',
            'model' => 'nullable|string|max:255',
            'serial_number' => 'nullable|string|max:255',
            'status' => 'required|in:Available,In Use,Under Maintenance,Retired,Lost,Damaged',
            'condition' => 'required|in:Excellent,Good,Fair,Poor',
            'purchase_cost' => 'nullable|numeric|min:0|max:999999999999.99',
            'purchase_date' => 'nullable|date',
            'warranty_expiry' => 'nullable|date|after:purchase_date',
            'last_maintenance_date' => 'nullable|date',
            'next_maintenance_date' => 'nullable|date',
            'assigned_to' => 'nullable|string|max:255',
            'department' => 'nullable|string|max:255',
            'location' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
            'specifications' => 'nullable|string',
            'created_by' => 'required|string|max:255',
        ]);

        $asset->update($validated);

        return redirect()->route('asset-management.index')
            ->with('success', 'Asset updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $asset = Asset::findOrFail($id);
        $asset->delete();

        return redirect()->route('asset-management.index')
            ->with('success', 'Asset deleted successfully.');
    }
}
