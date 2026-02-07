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
        return view('admin.warehousing.returns-management-create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'order_number' => 'required|string',
            'customer_name' => 'required|string|max:255',
            'product_name' => 'required|string|max:255',
            'sku' => 'required|string|max:255',
            'quantity' => 'required|integer|min:1',
            'refund_amount' => 'required|numeric|min:0|max:999999.99',
            'return_reason' => 'required|in:Defective,Wrong Item,Damaged,Not Satisfied,Other',
            'status' => 'required|in:Pending,Approved,Rejected,Processed,Refunded',
            'return_date' => 'required|date',
            'refund_date' => 'nullable|date',
            'notes' => 'nullable|string',
            'refund_method' => 'nullable|string|max:255',
            'tracking_number' => 'nullable|string|max:255',
        ]);

        // Auto-generate return ID
        $validated['return_id'] = 'RET-' . date('Y') . '-' . str_pad(ReturnRefund::count() + 1, 4, '0', STR_PAD_LEFT);

        ReturnRefund::create($validated);

        return redirect()->route('returns-management.index')
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
            'order_number' => 'required|string',
            'customer_name' => 'required|string|max:255',
            'product_name' => 'required|string|max:255',
            'sku' => 'required|string|max:255',
            'quantity' => 'required|integer|min:1',
            'refund_amount' => 'required|numeric|min:0|max:999999.99',
            'return_reason' => 'required|in:Defective,Wrong Item,Damaged,Not Satisfied,Other',
            'status' => 'required|in:Pending,Approved,Rejected,Processed,Refunded',
            'return_date' => 'required|date',
            'refund_date' => 'nullable|date',
            'notes' => 'nullable|string',
            'refund_method' => 'nullable|string|max:255',
            'tracking_number' => 'nullable|string|max:255',
        ]);

        $returnRefund->update($validated);

        return redirect()->route('returns-management.index')
            ->with('success', 'Return/refund updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $returnRefund = ReturnRefund::findOrFail($id);
        $returnRefund->delete();

        return redirect()->route('returns-management.index')
            ->with('success', 'Return/refund deleted successfully.');
    }
}
