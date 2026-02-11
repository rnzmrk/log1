<?php

namespace App\Http\Controllers\Procurement;

use App\Http\Controllers\Controller;
use App\Models\PurchaseOrder;
use App\Models\SupplyRequest;
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
        
        // Load item data from supply requests for POs that don't have item data
        foreach ($purchaseOrders as $po) {
            if (empty($po->item_name) && $po->supply_request_id) {
                $supplyRequest = \App\Models\SupplyRequest::find($po->supply_request_id);
                if ($supplyRequest) {
                    $po->item_name = $supplyRequest->item_name;
                    $po->item_category = $supplyRequest->category;
                    $po->item_quantity = $supplyRequest->quantity_requested;
                    $po->unit_price = $supplyRequest->unit_price;
                    $po->total_cost = $supplyRequest->total_cost;
                }
            }
            
            // Ensure total amount is calculated and available
            if (empty($po->total_amount) || $po->total_amount == 0) {
                // Try multiple calculation methods
                if (!empty($po->item_quantity) && !empty($po->unit_price)) {
                    $po->total_amount = $po->item_quantity * $po->unit_price;
                } elseif (!empty($po->total_cost)) {
                    $po->total_amount = $po->total_cost;
                } elseif ($po->supply_request_id) {
                    // Fallback to supply request calculation
                    $supplyRequest = \App\Models\SupplyRequest::find($po->supply_request_id);
                    if ($supplyRequest) {
                        $po->total_amount = $supplyRequest->quantity_requested * $supplyRequest->unit_price;
                    }
                }
            }
        }
        
        return view('admin.procurement.create-purchase-order', compact('purchaseOrders'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $supplyRequest = null;
        
        // Check if creating from supply request
        if ($request->filled('request_id')) {
            $supplyRequest = SupplyRequest::find($request->request_id);
        }
        
        // Get approved and active suppliers
        $suppliers = \App\Models\Supplier::whereIn('status', ['Active'])
            ->orderBy('name')
            ->get();
        
        // Get approved supply requests for selection
        $approvedSupplyRequests = \App\Models\SupplyRequest::where('status', 'Approved')
            ->orderBy('created_at', 'desc')
            ->get();
        
        return view('admin.procurement.create-purchase-order-create', compact('supplyRequest', 'suppliers', 'approvedSupplyRequests'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'supply_request' => 'required|integer|exists:supply_requests,id',
            'supplier' => 'required|string|max:255',
            'supplier_contact' => 'nullable|string|max:255',
            'supplier_email' => 'nullable|email|max:255',
            'supplier_phone' => 'nullable|string|max:255',
            'billing_address' => 'nullable|string',
            'shipping_address' => 'nullable|string',
            'item_name' => 'required|string|max:255',
            'item_category' => 'required|string|max:255',
            'item_quantity' => 'required|integer|min:1',
            'unit_price' => 'required|numeric|min:0|max:999999999999.99',
            'total_cost' => 'required|numeric|min:0|max:999999999999.99',
            'subtotal' => 'nullable|numeric|min:0|max:999999999999.99',
            'tax_amount' => 'nullable|numeric|min:0|max:999999999999.99',
            'shipping_cost' => 'nullable|numeric|min:0|max:999999999999.99',
            'priority' => 'required|in:Low,Medium,High,Urgent',
            'status' => 'required|string|max:255',
            'order_date' => 'required|date',
            'expected_delivery_date' => 'required|date',
            'actual_delivery_date' => 'nullable|date',
            'notes' => 'nullable|string',
            'created_by' => 'required|string|max:255',
            'approved_by' => 'nullable|string|max:255',
        ]);

        // Calculate total amount from item details
        $validated['total_amount'] = $validated['item_quantity'] * $validated['unit_price'];
        
        // Map supply_request to supply_request_id for model
        $validated['supply_request_id'] = $validated['supply_request'];
        unset($validated['supply_request']);

        // Auto-generate PO number based on supply request connection
        if (!empty($validated['supply_request_id'])) {
            $validated['po_number'] = PurchaseOrder::generateFromSupplyRequest($validated['supply_request_id']);
        } else {
            $validated['po_number'] = PurchaseOrder::generatePONumber();
        }

        $purchaseOrder = PurchaseOrder::create($validated);

        // Update supply request status if linked
        if (!empty($validated['supply_request_id'])) {
            $supplyRequest = SupplyRequest::find($validated['supply_request_id']);
            if ($supplyRequest) {
                $supplyRequest->status = 'Ordered';
                $supplyRequest->order_date = now();
                $supplyRequest->save();
            }
        }

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
        $suppliers = \App\Models\Supplier::whereIn('status', ['Accepted', 'Active'])
            ->orderBy('name')
            ->get();
        
        return view('admin.procurement.create-purchase-order-edit', compact('purchaseOrder', 'suppliers'));
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

    /**
     * Search purchase orders for inbound logistics.
     */
    public function search(Request $request)
    {
        $query = $request->get('q');
        
        $purchaseOrders = PurchaseOrder::where('status', 'To Received')
            ->where(function($q) use ($query) {
                $q->where('po_number', 'LIKE', "%{$query}%")
                  ->orWhere('item_name', 'LIKE', "%{$query}%")
                  ->orWhere('supplier', 'LIKE', "%{$query}%");
            })
            ->select(['id', 'po_number', 'item_name', 'supplier', 'item_quantity'])
            ->limit(10)
            ->get();
        
        return response()->json($purchaseOrders);
    }

    /**
     * Approve the purchase order.
     */
    public function approve(string $id)
    {
        $purchaseOrder = PurchaseOrder::findOrFail($id);
        $purchaseOrder->status = 'Approved';
        $purchaseOrder->approved_by = auth()->user()->name;
        $purchaseOrder->approved_at = now();
        $purchaseOrder->save();

        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Purchase order approved successfully.'
            ]);
        }

        return redirect()->route('purchase-orders.index')
            ->with('success', 'Purchase order approved successfully.');
    }

    /**
     * Receive the purchase order.
     */
    public function receive(string $id)
    {
        $purchaseOrder = PurchaseOrder::findOrFail($id);
        $purchaseOrder->status = 'To Received';
        $purchaseOrder->received_by = auth()->user()->name;
        $purchaseOrder->received_at = now();
        $purchaseOrder->actual_delivery_date = now(); // Set actual delivery date
        $purchaseOrder->save();

        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Purchase order marked as to be received successfully.'
            ]);
        }

        return redirect()->route('purchase-orders.index')
            ->with('success', 'Purchase order marked as to be received successfully.');
    }

    /**
     * Reject the purchase order.
     */
    public function reject(Request $request, string $id)
    {
        $purchaseOrder = PurchaseOrder::findOrFail($id);
        $purchaseOrder->status = 'Rejected';
        $purchaseOrder->rejected_by = auth()->user()->name;
        $purchaseOrder->rejected_at = now();
        
        if ($request->has('rejection_reason')) {
            $purchaseOrder->rejection_reason = $request->rejection_reason;
        }
        
        $purchaseOrder->save();

        return redirect()->route('purchase-orders.index')
            ->with('success', 'Purchase order rejected successfully.');
    }

    /**
     * Display purchase orders history with all items and search/filter functionality
     */
    public function history(Request $request)
    {
        $query = PurchaseOrder::query();

        // Search functionality
        if ($request->filled('search')) {
            $searchTerm = $request->search;
            $query->where(function($q) use ($searchTerm) {
                $q->where('po_number', 'like', '%' . $searchTerm . '%')
                  ->orWhere('item_name', 'like', '%' . $searchTerm . '%')
                  ->orWhere('supplier', 'like', '%' . $searchTerm . '%');
            });
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Order by latest first
        $purchaseOrders = $query->orderBy('updated_at', 'desc')->paginate(50);

        // Get unique statuses for filter
        $statuses = PurchaseOrder::distinct()->pluck('status')->filter();

        return view('admin.procurement.purchase-orders-history', compact('purchaseOrders', 'statuses'));
    }
}
