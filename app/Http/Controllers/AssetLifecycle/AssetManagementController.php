<?php

namespace App\Http\Controllers\AssetLifecycle;

use App\Http\Controllers\Controller;
use App\Models\Asset;
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

        // Search functionality
        if ($request->filled('search')) {
            $searchTerm = $request->search;
            $query->where(function($q) use ($searchTerm) {
                $q->where('item_number', 'like', '%' . $searchTerm . '%')
                  ->orWhere('item_code', 'like', '%' . $searchTerm . '%')
                  ->orWhere('item_name', 'like', '%' . $searchTerm . '%')
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

        // Filter by asset type
        if ($request->filled('asset_type')) {
            $query->where('asset_type', $request->asset_type);
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
        $request->validate([
            'item_number' => 'required|string|unique:assets,item_number',
            'item_code' => 'required|string|unique:assets,item_code',
            'item_name' => 'required|string',
            'asset_type' => 'required|string',
            'category' => 'required|string',
            'brand' => 'nullable|string',
            'model' => 'nullable|string',
            'serial_number' => 'nullable|string',
            'status' => 'required|string',
            'condition' => 'required|string',
            'date' => 'required|date',
            'purchase_cost' => 'nullable|numeric|min:0',
            'purchase_date' => 'nullable|date',
            'warranty_expiry' => 'nullable|date',
            'assigned_to' => 'nullable|string',
            'department' => 'nullable|string',
            'location' => 'nullable|string',
            'notes' => 'nullable|string',
            'specifications' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $data = $request->except('image');
        $data['created_by'] = 'System';

        // Handle image upload
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $image->storeAs('assets', $imageName, 'public');
            $data['image'] = $imageName;
        }

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
            'item_number' => 'required|string|unique:assets,item_number,' . $asset->id,
            'item_code' => 'required|string|unique:assets,item_code,' . $asset->id,
            'item_name' => 'required|string',
            'asset_type' => 'required|string',
            'category' => 'required|string',
            'brand' => 'nullable|string',
            'model' => 'nullable|string',
            'serial_number' => 'nullable|string',
            'status' => 'required|string',
            'condition' => 'required|string',
            'date' => 'required|date',
            'purchase_cost' => 'nullable|numeric|min:0',
            'purchase_date' => 'nullable|date',
            'warranty_expiry' => 'nullable|date',
            'assigned_to' => 'nullable|string',
            'department' => 'nullable|string',
            'location' => 'nullable|string',
            'notes' => 'nullable|string',
            'specifications' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $data = $request->except('image');

        // Handle image upload
        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($asset->image) {
                Storage::disk('public')->delete('assets/' . $asset->image);
            }

            $image = $request->file('image');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $image->storeAs('assets', $imageName, 'public');
            $data['image'] = $imageName;
        }

        $asset->update($data);

        return redirect()->route('admin.assetlifecycle.asset-management')
            ->with('success', 'Asset updated successfully.');
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

        if ($asset->status === 'Disposed') {
            return redirect()->back()
                ->with('error', 'Asset is already disposed.');
        }

        if (!$asset->canBeDisposed()) {
            return redirect()->back()
                ->with('error', 'Asset cannot be disposed. It must be in Poor/Damaged condition or have expired warranty.');
        }

        $asset->status = 'Disposed';
        $asset->disposal_reason = $request->disposal_reason;
        $asset->disposal_date = now();
        $asset->disposed_by = 'System';
        $asset->save();

        return redirect()->back()
            ->with('success', 'Asset disposed successfully.');
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
}
