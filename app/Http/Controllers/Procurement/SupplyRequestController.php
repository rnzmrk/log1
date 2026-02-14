<?php

namespace App\Http\Controllers\Procurement;

use App\Http\Controllers\Controller;
use App\Models\SupplyRequest;
use Illuminate\Http\Request;

class SupplyRequestController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = SupplyRequest::query();

        // Search functionality
        if ($request->filled('search')) {
            $searchTerm = $request->search;
            $query->where(function($q) use ($searchTerm) {
                $q->where('request_id', 'like', '%' . $searchTerm . '%')
                  ->orWhere('item_name', 'like', '%' . $searchTerm . '%')
                  ->orWhere('category', 'like', '%' . $searchTerm . '%')
                  ->orWhere('supplier', 'like', '%' . $searchTerm . '%');
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

        // Filter by category
        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        $supplyRequests = $query->orderBy('created_at', 'desc')->paginate(10);
        
        return view('admin.procurement.request-supplies', compact('supplyRequests'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $suppliers = \App\Models\Supplier::whereIn('status', ['Accepted', 'Active'])
            ->orderBy('name')
            ->get();
        
        return view('admin.procurement.request-supplies-create', compact('suppliers'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'item_name' => 'required|string|max:255',
            'category' => 'required|string|max:255',
            'supplier' => 'required|string|max:255',
            'quantity_requested' => 'required|integer|min:1',
            'quantity_approved' => 'nullable|integer|min:1',
            'unit_price' => 'nullable|numeric|min:0',
            'priority' => 'required|in:Low,Medium,High,Urgent',
            'status' => 'required|in:Pending,Approved,Rejected,Ordered,Received',
            'request_date' => 'required|date',
            'needed_by_date' => 'required|date',
            'approval_date' => 'nullable|date',
            'order_date' => 'nullable|date',
            'expected_delivery' => 'nullable|date',
            'notes' => 'nullable|string',
            'requested_by' => 'required|string|max:255',
            'approved_by' => 'nullable|string|max:255',
        ]);

        SupplyRequest::create($validated);

        return redirect()->route('supply-requests.index')
            ->with('success', 'Supply request created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $supplyRequest = SupplyRequest::findOrFail($id);
        return view('admin.procurement.request-supplies-show', compact('supplyRequest'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $supplyRequest = SupplyRequest::findOrFail($id);
        $suppliers = \App\Models\Supplier::whereIn('status', ['Accepted', 'Active'])
            ->orderBy('name')
            ->get();
        
        return view('admin.procurement.request-supplies-edit', compact('supplyRequest', 'suppliers'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $supplyRequest = SupplyRequest::findOrFail($id);

        $validated = $request->validate([
            'request_id' => 'required|string|unique:supply_requests,request_id,'.$id,
            'item_name' => 'required|string|max:255',
            'category' => 'required|string|max:255',
            'supplier' => 'required|string|max:255',
            'quantity_requested' => 'required|integer|min:1',
            'quantity_approved' => 'nullable|integer|min:1',
            'unit_price' => 'nullable|numeric|min:0',
            'priority' => 'required|in:Low,Medium,High,Urgent',
            'status' => 'required|in:Pending,Approved,Rejected,Ordered,Received',
            'request_date' => 'required|date',
            'needed_by_date' => 'required|date',
            'approval_date' => 'nullable|date',
            'order_date' => 'nullable|date',
            'expected_delivery' => 'nullable|date',
            'notes' => 'nullable|string',
            'requested_by' => 'required|string|max:255',
            'approved_by' => 'nullable|string|max:255',
        ]);

        $supplyRequest->update($validated);

        return redirect()->route('supply-requests.index')
            ->with('success', 'Supply request updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $supplyRequest = SupplyRequest::findOrFail($id);
        $supplyRequest->delete();

        return redirect()->route('supply-requests.index')
            ->with('success', 'Supply request deleted successfully.');
    }

    /**
     * Approve the supply request.
     */
    public function approve(string $id)
    {
        $supplyRequest = SupplyRequest::findOrFail($id);
        $supplyRequest->status = 'Approved';
        $supplyRequest->approved_by = auth()->user()->name;
        $supplyRequest->approval_date = now();
        $supplyRequest->save();

        return redirect()->route('supply-requests.index')
            ->with('success', 'Supply request approved successfully.');
    }

    /**
     * Reject the supply request.
     */
    public function reject(Request $request, string $id)
    {
        $supplyRequest = SupplyRequest::findOrFail($id);
        $supplyRequest->status = 'Rejected';
        $supplyRequest->save();

        return redirect()->route('supply-requests.index')
            ->with('success', 'Supply request rejected successfully.');
    }

    /**
     * Display supply requests history with all items and search/filter functionality
     */
    public function history(Request $request)
    {
        $query = SupplyRequest::query();

        // Search functionality
        if ($request->filled('search')) {
            $searchTerm = $request->search;
            $query->where(function($q) use ($searchTerm) {
                $q->where('request_id', 'like', '%' . $searchTerm . '%')
                  ->orWhere('item_name', 'like', '%' . $searchTerm . '%')
                  ->orWhere('supplier', 'like', '%' . $searchTerm . '%');
            });
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Order by latest first
        $supplyRequests = $query->orderBy('updated_at', 'desc')->paginate(50);

        // Get unique statuses for filter
        $statuses = SupplyRequest::distinct()->pluck('status')->filter();

        return view('admin.procurement.supply-requests-history', compact('supplyRequests', 'statuses'));
    }

    /**
     * Show the form for creating a supply request from inventory
     */
    public function createFromInventory()
    {
        // Get inventory items for selection
        $inventoryItems = \App\Models\Inventory::all();
        
        return view('admin.procurement.supply-requests-create-from-inventory', compact('inventoryItems'));
    }
}
