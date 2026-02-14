<?php

namespace App\Http\Controllers\AssetLifecycle;

use App\Http\Controllers\Controller;
use App\Models\AssetRequest;
use Illuminate\Http\Request;

class AssetRequestController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = AssetRequest::query();

        // Search functionality
        if ($request->filled('search')) {
            $searchTerm = $request->search;
            $query->where(function($q) use ($searchTerm) {
                $q->where('request_number', 'like', '%' . $searchTerm . '%')
                  ->orWhere('asset_name', 'like', '%' . $searchTerm . '%')
                  ->orWhere('asset_type', 'like', '%' . $searchTerm . '%')
                  ->orWhere('category', 'like', '%' . $searchTerm . '%')
                  ->orWhere('requested_by', 'like', '%' . $searchTerm . '%');
            });
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by priority
        if ($request->filled('priority')) {
            $query->where('priority', $request->priority);
        }

        // Filter by request type
        if ($request->filled('request_type')) {
            $query->where('request_type', $request->request_type);
        }

        // Filter by department
        if ($request->filled('department')) {
            $query->where('department', 'like', '%' . $request->department . '%');
        }

        $assetRequests = $query->orderBy('created_at', 'desc')->paginate(10);
        
        return view('admin.assetlifecycle.request-asset', compact('assetRequests'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.assetlifecycle.request-asset-create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'asset_name' => 'required|string|max:255',
            'priority' => 'required|in:Low,Medium,High,Urgent',
            'status' => 'required|in:Pending,Approved,Rejected,Processing,Completed,Cancelled',
            'request_type' => 'required|in:New,Replacement,Upgrade,Repair,Other',
            'request_date' => 'required|date',
            'requested_by' => 'required|string|max:255',
            'department' => 'required|string|max:255',
        ]);

        // Auto-generate request number
        $validated['request_number'] = 'AR-' . date('Y') . '-' . str_pad(AssetRequest::count() + 1, 4, '0', STR_PAD_LEFT);
        
        // Add default values for required fields that were removed from the form
        $validated['asset_type'] = 'General';
        $validated['category'] = 'Other';

        AssetRequest::create($validated);

        return redirect()->route('asset-requests.index')
            ->with('success', 'Asset request created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $assetRequest = AssetRequest::findOrFail($id);
        return view('admin.assetlifecycle.request-asset-show', compact('assetRequest'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $assetRequest = AssetRequest::findOrFail($id);
        return view('admin.assetlifecycle.request-asset-edit', compact('assetRequest'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $assetRequest = AssetRequest::findOrFail($id);

        $validated = $request->validate([
            'request_number' => 'required|string|unique:asset_requests,request_number,'.$id,
            'asset_name' => 'required|string|max:255',
            'asset_type' => 'required|string|max:255',
            'category' => 'required|string|max:255',
            'brand' => 'nullable|string|max:255',
            'model' => 'nullable|string|max:255',
            'serial_number' => 'nullable|string|max:255',
            'priority' => 'required|in:Low,Medium,High,Urgent',
            'status' => 'required|in:Pending,Approved,Rejected,Processing,Completed,Cancelled',
            'request_type' => 'required|in:New,Replacement,Upgrade,Repair,Other',
            'estimated_cost' => 'nullable|numeric|min:0|max:999999999999.99',
            'request_date' => 'required|date',
            'required_date' => 'nullable|date|after:request_date',
            'approved_date' => 'nullable|date',
            'completed_date' => 'nullable|date',
            'justification' => 'nullable|string',
            'specifications' => 'nullable|string',
            'notes' => 'nullable|string',
            'requested_by' => 'required|string|max:255',
            'department' => 'required|string|max:255',
            'approved_by' => 'nullable|string|max:255',
        ]);

        $assetRequest->update($validated);

        return redirect()->route('asset-requests.index')
            ->with('success', 'Asset request updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $assetRequest = AssetRequest::findOrFail($id);
        $assetRequest->delete();

        return redirect()->route('asset-requests.index')
            ->with('success', 'Asset request deleted successfully.');
    }

    /**
     * Approve the asset request.
     */
    public function approve(string $id)
    {
        $assetRequest = AssetRequest::findOrFail($id);
        $assetRequest->status = 'Approved';
        $assetRequest->save();

        return redirect()->route('asset-requests.index')
            ->with('success', 'Asset request approved successfully.');
    }

    /**
     * Reject the asset request.
     */
    public function reject(string $id)
    {
        $assetRequest = AssetRequest::findOrFail($id);
        $assetRequest->status = 'Rejected';
        $assetRequest->save();

        return redirect()->route('asset-requests.index')
            ->with('success', 'Asset request rejected successfully.');
    }
}
