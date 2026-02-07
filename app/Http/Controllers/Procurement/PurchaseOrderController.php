<?php

namespace App\Http\Controllers\Procurement;

use App\Http\Controllers\Controller;
use App\Models\PurchaseOrder;
use Illuminate\Http\Request;

class PurchaseOrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = PurchaseOrder::query();

        // Search functionality
        if ($request->filled('search')) {
            $searchTerm = $request->search;
            $query->where(function($q) use ($searchTerm) {
                $q->where('po_number', 'like', '%' . $searchTerm . '%')
                  ->orWhere('supplier', 'like', '%' . $searchTerm . '%')
                  ->orWhere('supplier_contact', 'like', '%' . $searchTerm . '%')
                  ->orWhere('created_by', 'like', '%' . $searchTerm . '%');
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

        // Filter by supplier
        if ($request->filled('supplier')) {
            $query->where('supplier', 'like', '%' . $request->supplier . '%');
        }

        $purchaseOrders = $query->orderBy('created_at', 'desc')->paginate(10);
        
        return view('admin.procurement.create-purchase-order', compact('purchaseOrders'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.procurement.create-purchase-order-create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'supplier' => 'required|string|max:255',
            'supplier_contact' => 'nullable|string|max:255',
            'supplier_email' => 'nullable|email|max:255',
            'supplier_phone' => 'nullable|string|max:255',
            'billing_address' => 'nullable|string',
            'shipping_address' => 'nullable|string',
            'subtotal' => 'required|numeric|min:0|max:999999999999.99',
            'tax_amount' => 'required|numeric|min:0|max:999999999999.99',
            'shipping_cost' => 'required|numeric|min:0|max:999999999999.99',
            'priority' => 'required|in:Low,Medium,High,Urgent',
            'status' => 'required|in:Draft,Sent,Approved,Rejected,Partially Received,Received,Cancelled',
            'order_date' => 'required|date',
            'expected_delivery_date' => 'required|date',
            'actual_delivery_date' => 'nullable|date',
            'notes' => 'nullable|string',
            'created_by' => 'required|string|max:255',
            'approved_by' => 'nullable|string|max:255',
        ]);

        // Auto-generate PO number
        $validated['po_number'] = 'PO-' . date('Y') . '-' . str_pad(PurchaseOrder::count() + 1, 4, '0', STR_PAD_LEFT);

        PurchaseOrder::create($validated);

        return redirect()->route('purchase-orders.index')
            ->with('success', 'Purchase order created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $purchaseOrder = PurchaseOrder::findOrFail($id);
        return view('admin.procurement.create-purchase-order-show', compact('purchaseOrder'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $purchaseOrder = PurchaseOrder::findOrFail($id);
        return view('admin.procurement.create-purchase-order-edit', compact('purchaseOrder'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $purchaseOrder = PurchaseOrder::findOrFail($id);

        $validated = $request->validate([
            'po_number' => 'required|string|unique:purchase_orders,po_number,'.$id,
            'supplier' => 'required|string|max:255',
            'supplier_contact' => 'nullable|string|max:255',
            'supplier_email' => 'nullable|email|max:255',
            'supplier_phone' => 'nullable|string|max:255',
            'billing_address' => 'nullable|string',
            'shipping_address' => 'nullable|string',
            'subtotal' => 'required|numeric|min:0|max:999999999999.99',
            'tax_amount' => 'required|numeric|min:0|max:999999999999.99',
            'shipping_cost' => 'required|numeric|min:0|max:999999999999.99',
            'priority' => 'required|in:Low,Medium,High,Urgent',
            'status' => 'required|in:Draft,Sent,Approved,Rejected,Partially Received,Received,Cancelled',
            'order_date' => 'required|date',
            'expected_delivery_date' => 'required|date',
            'actual_delivery_date' => 'nullable|date',
            'notes' => 'nullable|string',
            'created_by' => 'required|string|max:255',
            'approved_by' => 'nullable|string|max:255',
        ]);

        $purchaseOrder->update($validated);

        return redirect()->route('purchase-orders.index')
            ->with('success', 'Purchase order updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $purchaseOrder = PurchaseOrder::findOrFail($id);
        $purchaseOrder->delete();

        return redirect()->route('purchase-orders.index')
            ->with('success', 'Purchase order deleted successfully.');
    }
}
