<?php

namespace App\Http\Controllers\SmartWarehousing;

use App\Http\Controllers\Controller;
use App\Models\OutboundLogistic;
use Illuminate\Http\Request;

class OutboundLogisticController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = OutboundLogistic::query();

        // Search functionality
        if ($request->filled('search')) {
            $searchTerm = $request->search;
            $query->where(function($q) use ($searchTerm) {
                $q->where('shipment_id', 'like', '%' . $searchTerm . '%')
                  ->orWhere('order_number', 'like', '%' . $searchTerm . '%')
                  ->orWhere('customer_name', 'like', '%' . $searchTerm . '%')
                  ->orWhere('destination', 'like', '%' . $searchTerm . '%')
                  ->orWhere('item_name', 'like', '%' . $searchTerm . '%');
            });
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by request type
        if ($request->filled('request_type')) {
            $query->where('request_type', $request->request_type);
        }

        $outboundLogistics = $query->orderBy('created_at', 'desc')->paginate(10);
        
        // Get statistics
        $stats = [
            'total' => OutboundLogistic::count(),
            'pending' => OutboundLogistic::where('status', 'Pending')->count(),
            'ready_to_ship' => OutboundLogistic::where('status', 'Ready to Ship')->count(),
            'shipped' => OutboundLogistic::where('status', 'Shipped')->count(),
            'delivered' => OutboundLogistic::where('status', 'Delivered')->count(),
            'cancelled' => OutboundLogistic::where('status', 'Cancelled')->count(),
            'supply_requests' => OutboundLogistic::where('request_type', 'Supply Request')->count(),
        ];
        
        return view('admin.warehousing.outbound-logistics', compact('outboundLogistics', 'stats'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Get SKUs that are already in outbound logistics
        $usedSkus = OutboundLogistic::pluck('sku')->filter()->unique();
        
        // Get inventory items with "Moved" status that are NOT already in outbound logistics
        $movedInventory = \App\Models\Inventory::where('status', 'Moved')
            ->whereNotIn('sku', $usedSkus)
            ->orderBy('item_name')
            ->get();
            
        return view('admin.warehousing.outbound-logistics-create', compact('movedInventory'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'sku' => 'required|string',
            'po_number' => 'required|string',
            'department' => 'required|string',
            'supplier' => 'required|string',
            'item_name' => 'required|string',
            'stock' => 'required|integer|min:1',
            'address' => 'required|string',
            'contact' => 'required|string',
        ]);

        // Create outbound shipment with Pending status
        OutboundLogistic::create([
            'shipment_id' => 'SHIP-' . date('Y-m-d-His'),
            'sku' => $request->sku,
            'po_number' => $request->po_number,
            'department' => $request->department,
            'supplier' => $request->supplier,
            'item_name' => $request->item_name,
            'total_units' => $request->stock,
            'shipped_units' => 0,
            'destination' => $request->address,
            'address' => $request->address,
            'contact' => $request->contact,
            'customer_name' => $request->contact,
            'status' => 'Pending',
            'priority' => 'Medium',
            'shipping_date' => now(),
        ]);

        return redirect()->route('admin.warehousing.outbound-logistics')
            ->with('success', 'Shipment created successfully and set to Pending status.');
    }

    /**
     * Display the specified resource.
     */
    public function show(OutboundLogistic $outboundLogistic)
    {
        return view('admin.warehousing.outbound-logistics-show', compact('outboundLogistic'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(OutboundLogistic $outboundLogistic)
    {
        return view('admin.warehousing.outbound-logistics-edit', compact('outboundLogistic'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, OutboundLogistic $outboundLogistic)
    {
        $request->validate([
            'shipment_id' => 'required|string|unique:outbound_logistics,shipment_id,' . $outboundLogistic->id,
            'sku' => 'nullable|string',
            'po_number' => 'nullable|string',
            'department' => 'nullable|string',
            'item_name' => 'nullable|string',
            'total_units' => 'nullable|integer|min:1',
            'supplier' => 'nullable|string',
            'address' => 'nullable|string',
            'contact' => 'nullable|string',
            'status' => 'nullable|string',
        ]);

        $outboundLogistic->update($request->all());

        return redirect()->route('admin.warehousing.outbound-logistics')
            ->with('success', 'Outbound shipment updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    // public function destroy(OutboundLogistic $outboundLogistic)
    // {
    //     // Delete functionality removed
    // }

    /**
     * Ship item - change status to shipped
     */
    public function shipItem(OutboundLogistic $outboundLogistic)
    {
        if ($outboundLogistic->status === 'Shipped') {
            return redirect()->back()
                ->with('error', 'Item is already shipped.');
        }

        if ($outboundLogistic->status === 'Delivered') {
            return redirect()->back()
                ->with('error', 'Item is already delivered.');
        }

        $outboundLogistic->status = 'Shipped';
        $outboundLogistic->shipped_date = now();
        $outboundLogistic->save();

        return redirect()->back()
            ->with('success', 'Item shipped successfully.');
    }

    /**
     * Mark item as delivered
     */
    public function deliverItem(OutboundLogistic $outboundLogistic)
    {
        if ($outboundLogistic->status !== 'Shipped') {
            return redirect()->back()
                ->with('error', 'Item must be shipped before it can be delivered.');
        }

        $outboundLogistic->status = 'Delivered';
        $outboundLogistic->delivered_date = now();
        $outboundLogistic->save();

        return redirect()->back()
            ->with('success', 'Item delivered successfully.');
    }

    /**
     * Cancel outbound shipment
     */
    public function cancelShipment(Request $request, OutboundLogistic $outboundLogistic)
    {
        $request->validate([
            'cancellation_reason' => 'required|string',
        ]);

        if (in_array($outboundLogistic->status, ['Shipped', 'Delivered'])) {
            return redirect()->back()
                ->with('error', 'Cannot cancel shipped or delivered items.');
        }

        $outboundLogistic->status = 'Cancelled';
        $outboundLogistic->cancellation_reason = $request->cancellation_reason;
        $outboundLogistic->cancelled_date = now();
        $outboundLogistic->save();

        return redirect()->back()
            ->with('success', 'Shipment cancelled successfully.');
    }

    /**
     * Search outbound logistics by order number for autocomplete
     */
    public function search(Request $request)
    {
        $query = $request->get('q', '');
        if (strlen($query) < 2) {
            return response()->json([]);
        }

        $orders = OutboundLogistic::where('order_number', 'like', '%' . $query . '%')
            ->select(['id', 'order_number', 'customer_name', 'item_name', 'sku', 'total_units'])
            ->limit(10)
            ->get();

        return response()->json($orders);
    }

    /**
     * Get pending supply requests
     */
    public function getPendingSupplyRequests()
    {
        $pendingRequests = OutboundLogistic::where('request_type', 'Supply Request')
            ->where('status', 'Pending Supply')
            ->orderBy('created_at', 'asc')
            ->get();

        return response()->json($pendingRequests);
    }

    /**
     * Process supply request - update status and handle inventory
     */
    public function processSupplyRequest(OutboundLogistic $outboundLogistic)
    {
        if ($outboundLogistic->request_type !== 'Supply Request' || $outboundLogistic->status !== 'Pending Supply') {
            return redirect()->back()
                ->with('error', 'Invalid supply request.');
        }

        // Update status
        $outboundLogistic->status = 'Supply Approved';
        $outboundLogistic->approved_date = now();
        $outboundLogistic->save();

        return redirect()->back()
            ->with('success', 'Supply request processed and approved.');
    }

    /**
     * Export outbound logistics to CSV
     */
    public function export(Request $request)
    {
        $query = OutboundLogistic::query();

        // Apply same filters as index
        if ($request->filled('search')) {
            $searchTerm = $request->search;
            $query->where(function($q) use ($searchTerm) {
                $q->where('shipment_id', 'like', '%' . $searchTerm . '%')
                  ->orWhere('order_number', 'like', '%' . $searchTerm . '%')
                  ->orWhere('customer_name', 'like', '%' . $searchTerm . '%')
                  ->orWhere('destination', 'like', '%' . $searchTerm . '%')
                  ->orWhere('item_name', 'like', '%' . $searchTerm . '%');
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('request_type')) {
            $query->where('request_type', $request->request_type);
        }

        $outboundLogistics = $query->orderBy('created_at', 'desc')->get();

        // Create CSV export
        $filename = 'outbound-logistics-' . date('Y-m-d') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($outboundLogistics) {
            $file = fopen('php://output', 'w');
            
            // CSV Header
            fputcsv($file, [
                'Shipment ID',
                'Order Number',
                'Customer Name',
                'Item Name',
                'SKU',
                'Quantity',
                'Destination',
                'Priority',
                'Expected Date',
                'Status',
                'Request Type',
                'Department',
                'Requested By',
                'Created At',
                'Shipped Date',
                'Delivered Date',
                'Cancellation Reason'
            ]);

            // CSV Data
            foreach ($outboundLogistics as $logistic) {
                fputcsv($file, [
                    $logistic->shipment_id,
                    $logistic->order_number,
                    $logistic->customer_name,
                    $logistic->item_name,
                    $logistic->sku,
                    $logistic->quantity,
                    $logistic->destination,
                    $logistic->priority,
                    $logistic->expected_date->format('Y-m-d'),
                    $logistic->status,
                    $logistic->request_type,
                    $logistic->department,
                    $logistic->requested_by,
                    $logistic->created_at->format('Y-m-d H:i:s'),
                    $logistic->shipped_date ? $logistic->shipped_date->format('Y-m-d H:i:s') : '',
                    $logistic->delivered_date ? $logistic->delivered_date->format('Y-m-d H:i:s') : '',
                    $logistic->cancellation_reason ?? ''
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Bulk actions on outbound logistics
     */
    public function bulkAction(Request $request)
    {
        $request->validate([
            'action' => 'required|in:ship,deliver,cancel,delete',
            'outbound_ids' => 'required|array',
            'outbound_ids.*' => 'exists:outbound_logistics,id',
            'cancellation_reason' => 'required_if:action,cancel|string',
        ]);

        $outboundIds = $request->outbound_ids;

        if ($request->action === 'ship') {
            $count = 0;
            foreach ($outboundIds as $id) {
                $outbound = OutboundLogistic::find($id);
                if ($outbound && !in_array($outbound->status, ['Shipped', 'Delivered', 'Cancelled'])) {
                    $outbound->status = 'Shipped';
                    $outbound->shipped_date = now();
                    $outbound->save();
                    $count++;
                }
            }
            return redirect()->back()->with('success', $count . ' shipments marked as shipped.');
        }

        if ($request->action === 'deliver') {
            $count = 0;
            foreach ($outboundIds as $id) {
                $outbound = OutboundLogistic::find($id);
                if ($outbound && $outbound->status === 'Shipped') {
                    $outbound->status = 'Delivered';
                    $outbound->delivered_date = now();
                    $outbound->save();
                    $count++;
                }
            }
            return redirect()->back()->with('success', $count . ' shipments marked as delivered.');
        }

        if ($request->action === 'cancel') {
            $count = 0;
            foreach ($outboundIds as $id) {
                $outbound = OutboundLogistic::find($id);
                if ($outbound && !in_array($outbound->status, ['Shipped', 'Delivered'])) {
                    $outbound->status = 'Cancelled';
                    $outbound->cancellation_reason = $request->cancellation_reason;
                    $outbound->cancelled_date = now();
                    $outbound->save();
                    $count++;
                }
            }
            return redirect()->back()->with('success', $count . ' shipments cancelled.');
        }

        return redirect()->back()->with('error', 'Invalid action.');
    }

    /**
     * Get outbound logistics statistics for dashboard
     */
    public function getStats()
    {
        $stats = [
            'total' => OutboundLogistic::count(),
            'pending' => OutboundLogistic::where('status', 'Pending')->count(),
            'ready_to_ship' => OutboundLogistic::where('status', 'Ready to Ship')->count(),
            'shipped' => OutboundLogistic::where('status', 'Shipped')->count(),
            'delivered' => OutboundLogistic::where('status', 'Delivered')->count(),
            'cancelled' => OutboundLogistic::where('status', 'Cancelled')->count(),
            'overdue' => OutboundLogistic::where('status', 'Pending')
                ->where('expected_date', '<', now())
                ->count(),
        ];

        return response()->json($stats);
    }

    /**
     * Display outbound logistics history with all items and search/filter functionality
     */
    public function history(Request $request)
    {
        $query = OutboundLogistic::query();

        // Search functionality
        if ($request->filled('search')) {
            $searchTerm = $request->search;
            $query->where(function($q) use ($searchTerm) {
                $q->where('shipment_id', 'like', '%' . $searchTerm . '%')
                  ->orWhere('sku', 'like', '%' . $searchTerm . '%')
                  ->orWhere('po_number', 'like', '%' . $searchTerm . '%')
                  ->orWhere('department', 'like', '%' . $searchTerm . '%')
                  ->orWhere('item_name', 'like', '%' . $searchTerm . '%');
            });
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Order by latest first
        $outboundLogistics = $query->orderBy('updated_at', 'desc')->paginate(50);

        // Get unique statuses for filter
        $statuses = OutboundLogistic::distinct()->pluck('status')->filter();

        return view('admin.warehousing.outbound-logistics-history', compact('outboundLogistics', 'statuses'));
    }
}
