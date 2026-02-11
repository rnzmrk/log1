<?php

namespace App\Http\Controllers\SmartWarehousing;

use App\Http\Controllers\Controller;
use App\Models\ReturnRefund;
use Illuminate\Http\Request;

class ReturnRefundController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = ReturnRefund::query();

        // Search functionality
        if ($request->filled('search')) {
            $searchTerm = $request->search;
            $query->where(function($q) use ($searchTerm) {
                $q->where('return_id', 'like', '%' . $searchTerm . '%')
                  ->orWhere('order_number', 'like', '%' . $searchTerm . '%')
                  ->orWhere('customer_name', 'like', '%' . $searchTerm . '%')
                  ->orWhere('product_name', 'like', '%' . $searchTerm . '%')
                  ->orWhere('sku', 'like', '%' . $searchTerm . '%');
            });
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by return reason
        if ($request->filled('return_reason')) {
            $query->where('return_reason', $request->return_reason);
        }

        $returnRefunds = $query->orderBy('created_at', 'desc')->paginate(10);
        
        return view('admin.warehousing.returns-management', compact('returnRefunds'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Get returned items that are NOT already in return_refunds table
        $returnedItems = \App\Models\Inventory::where('status', 'Returned')
            ->whereNotIn('sku', function($query) {
                $query->select('sku')->from('return_refunds');
            })
            ->get();
        
        return view('admin.warehousing.returns-management-create', compact('returnedItems'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'sku' => 'required|string|max:255',
            'po_number' => 'nullable|string|max:255',
            'department' => 'nullable|string|max:255',
            'item_name' => 'nullable|string|max:255',
            'stock' => 'nullable|integer|min:0',
            'supplier' => 'nullable|string|max:255',
            'status' => 'required|in:Pending,Approved,Rejected,Processed,Refunded',
            'return_date' => 'required|date',
        ]);

        // Auto-generate return ID
        $validated['return_id'] = 'RET-' . date('Y') . '-' . str_pad(ReturnRefund::count() + 1, 4, '0', STR_PAD_LEFT);

        ReturnRefund::create($validated);

        return redirect()->route('admin.warehousing.returns-management')
            ->with('success', 'Return/refund created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $returnRefund = ReturnRefund::findOrFail($id);
        return view('admin.warehousing.returns-management-show', compact('returnRefund'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $returnRefund = ReturnRefund::findOrFail($id);
        return view('admin.warehousing.returns-management-edit', compact('returnRefund'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $returnRefund = ReturnRefund::findOrFail($id);

        $validated = $request->validate([
            'return_id' => 'required|string|unique:return_refunds,return_id,'.$id,
            'sku' => 'required|string|max:255',
            'po_number' => 'nullable|string|max:255',
            'department' => 'nullable|string|max:255',
            'item_name' => 'nullable|string|max:255',
            'stock' => 'nullable|integer|min:0',
            'supplier' => 'nullable|string|max:255',
            'status' => 'required|in:Pending,Approved,Rejected,Processed,Refunded',
            'return_date' => 'required|date',
            'notes' => 'nullable|string',
        ]);

        $returnRefund->update($validated);

        return redirect()->route('returns-management.index')
            ->with('success', 'Return/refund updated successfully.');
    }

    /**
     * Display returns management history with all items and search/filter functionality
     */
    public function history(Request $request)
    {
        $query = ReturnRefund::query();

        // Search functionality
        if ($request->filled('search')) {
            $searchTerm = $request->search;
            $query->where(function($q) use ($searchTerm) {
                $q->where('return_id', 'like', '%' . $searchTerm . '%')
                  ->orWhere('sku', 'like', '%' . $searchTerm . '%')
                  ->orWhere('po_number', 'like', '%' . $searchTerm . '%')
                  ->orWhere('item_name', 'like', '%' . $searchTerm . '%')
                  ->orWhere('product_name', 'like', '%' . $searchTerm . '%')
                  ->orWhere('supplier', 'like', '%' . $searchTerm . '%')
                  ->orWhere('return_reason', 'like', '%' . $searchTerm . '%')
                  ->orWhere('notes', 'like', '%' . $searchTerm . '%');
            });
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Order by latest first
        $returnRefunds = $query->orderBy('updated_at', 'desc')->paginate(50);

        // Get unique statuses for filter
        $statuses = ReturnRefund::distinct()->pluck('status')->filter();

        return view('admin.warehousing.returns-management-history', compact('returnRefunds', 'statuses'));
    }

    /**
     * Remove the specified resource from storage.
     */
    // public function destroy(string $id)
    // {
    //     // Delete functionality removed
    // }
}
