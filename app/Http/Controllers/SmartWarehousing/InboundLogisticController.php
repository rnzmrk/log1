<?php

namespace App\Http\Controllers\SmartWarehousing;

use App\Http\Controllers\Controller;
use App\Models\InboundLogistic;
use App\Models\Inventory;
use Illuminate\Http\Request;

class InboundLogisticController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = InboundLogistic::query();

        // Search by shipment ID or supplier
        if ($request->filled('search')) {
            $searchTerm = $request->search;
            $query->where(function($q) use ($searchTerm) {
                $q->where('shipment_id', 'like', '%' . $searchTerm . '%')
                  ->orWhere('supplier', 'like', '%' . $searchTerm . '%');
            });
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by date range
        if ($request->filled('from_date')) {
            $query->whereDate('expected_date', '>=', $request->from_date);
        }

        if ($request->filled('to_date')) {
            $query->whereDate('expected_date', '<=', $request->to_date);
        }

        $inboundLogistics = $query->orderBy('created_at', 'desc')->paginate(10);
        
        // Get statistics
        $stats = [
            'total' => InboundLogistic::count(),
            'pending' => InboundLogistic::where('status', 'Pending')->count(),
            'accepted' => InboundLogistic::where('status', 'Accepted')->count(),
            'rejected' => InboundLogistic::where('status', 'Rejected')->count(),
            'overdue' => InboundLogistic::where('status', 'Pending')
                ->where('expected_date', '<', now())
                ->count(),
        ];
        
        return view('admin.warehousing.inbound-logistics', compact('inboundLogistics', 'stats'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $suppliers = \App\Models\Supplier::whereIn('status', ['Accepted', 'Active'])
            ->orderBy('name')
            ->get();
        
        return view('admin.warehousing.inbound-logistics-create', compact('suppliers'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'shipment_id' => 'required|string|unique:inbound_logistics,shipment_id',
            'supplier' => 'required|string',
            'item_name' => 'required|string',
            'expected_units' => 'required|integer|min:1',
            'expected_date' => 'required|date',
            'description' => 'nullable|string',
        ]);

        InboundLogistic::create($request->all());

        return redirect()->route('admin.warehousing.inbound-logistics')
            ->with('success', 'Inbound shipment created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(InboundLogistic $inboundLogistic)
    {
        return view('admin.warehousing.inbound-logistics-show', compact('inboundLogistic'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(InboundLogistic $inboundLogistic)
    {
        $suppliers = \App\Models\Supplier::whereIn('status', ['Accepted', 'Active'])
            ->orderBy('name')
            ->get();
        
        return view('admin.warehousing.inbound-logistics-edit', compact('inboundLogistic', 'suppliers'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, InboundLogistic $inboundLogistic)
    {
        $request->validate([
            'shipment_id' => 'required|string|unique:inbound_logistics,shipment_id,' . $inboundLogistic->id,
            'supplier' => 'required|string',
            'item_name' => 'required|string',
            'expected_units' => 'required|integer|min:1',
            'expected_date' => 'required|date',
            'description' => 'nullable|string',
        ]);

        $inboundLogistic->update($request->all());

        return redirect()->route('admin.warehousing.inbound-logistics')
            ->with('success', 'Inbound shipment updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    // public function destroy(InboundLogistic $inboundLogistic)
    // {
    //     // Delete functionality removed
    // }

    /**
     * Accept inbound shipment and move to storage inventory
     */
    public function acceptShipment(InboundLogistic $inboundLogistic)
    {
        if ($inboundLogistic->status !== 'Pending') {
            return redirect()->back()
                ->with('error', 'Only pending shipments can be accepted.');
        }

        // Check if item already exists in inventory
        $existingInventory = Inventory::where('sku', $inboundLogistic->shipment_id)->first();

        if ($existingInventory) {
            // Update existing inventory
            $existingInventory->stock += $inboundLogistic->expected_units;
            $existingInventory->save();
        } else {
            // Create new inventory entry
            Inventory::create([
                'sku' => $inboundLogistic->shipment_id,
                'item_name' => $inboundLogistic->item_name,
                'category' => 'General',
                'location' => 'Main Warehouse',
                'stock' => $inboundLogistic->expected_units,
                'description' => $inboundLogistic->description,
                'price' => 0.00,
                'supplier' => $inboundLogistic->supplier,
            ]);
        }

        // Update inbound shipment status
        $inboundLogistic->status = 'Accepted';
        $inboundLogistic->accepted_date = now();
        $inboundLogistic->save();

        return redirect()->route('admin.warehousing.storage-inventory')
            ->with('success', 'Shipment accepted and items moved to storage inventory.');
    }

    /**
     * Reject inbound shipment
     */
    public function rejectShipment(Request $request, InboundLogistic $inboundLogistic)
    {
        $request->validate([
            'rejection_reason' => 'required|string',
        ]);

        if ($inboundLogistic->status !== 'Pending') {
            return redirect()->back()
                ->with('error', 'Only pending shipments can be rejected.');
        }

        $inboundLogistic->status = 'Rejected';
        $inboundLogistic->rejection_reason = $request->rejection_reason;
        $inboundLogistic->rejected_date = now();
        $inboundLogistic->save();

        return redirect()->back()
            ->with('success', 'Shipment rejected successfully.');
    }

    /**
     * Export inbound logistics to CSV
     */
    public function export(Request $request)
    {
        $query = InboundLogistic::query();

        // Apply same filters as index
        if ($request->filled('search')) {
            $searchTerm = $request->search;
            $query->where(function($q) use ($searchTerm) {
                $q->where('shipment_id', 'like', '%' . $searchTerm . '%')
                  ->orWhere('supplier', 'like', '%' . $searchTerm . '%');
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('from_date')) {
            $query->whereDate('expected_date', '>=', $request->from_date);
        }

        if ($request->filled('to_date')) {
            $query->whereDate('expected_date', '<=', $request->to_date);
        }

        $inboundLogistics = $query->orderBy('created_at', 'desc')->get();

        // Create CSV export
        $filename = 'inbound-logistics-' . date('Y-m-d') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($inboundLogistics) {
            $file = fopen('php://output', 'w');
            
            // CSV Header
            fputcsv($file, [
                'Shipment ID',
                'Supplier',
                'Item Name',
                'Quantity',
                'Expected Date',
                'Status',
                'Description',
                'Created At',
                'Accepted Date',
                'Rejected Date',
                'Rejection Reason'
            ]);

            // CSV Data
            foreach ($inboundLogistics as $logistic) {
                fputcsv($file, [
                    $logistic->shipment_id,
                    $logistic->supplier,
                    $logistic->item_name,
                    $logistic->expected_units,
                    $logistic->expected_date->format('Y-m-d'),
                    $logistic->status,
                    $logistic->description,
                    $logistic->created_at->format('Y-m-d H:i:s'),
                    $logistic->accepted_date ? $logistic->accepted_date->format('Y-m-d H:i:s') : '',
                    $logistic->rejected_date ? $logistic->rejected_date->format('Y-m-d H:i:s') : '',
                    $logistic->rejection_reason ?? ''
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Bulk actions on inbound logistics
     */
    public function bulkAction(Request $request)
    {
        $request->validate([
            'action' => 'required|in:accept,reject,delete',
            'inbound_ids' => 'required|array',
            'inbound_ids.*' => 'exists:inbound_logistics,id',
            'rejection_reason' => 'required_if:action,reject|string',
        ]);

        $inboundIds = $request->inbound_ids;

        if ($request->action === 'accept') {
            $count = 0;
            foreach ($inboundIds as $id) {
                $inbound = InboundLogistic::find($id);
                if ($inbound && $inbound->status === 'Pending') {
                    // Move to inventory logic here
                    $existingInventory = Inventory::where('sku', $inbound->shipment_id)->first();
                    
                    if ($existingInventory) {
                        $existingInventory->stock += $inbound->expected_units;
                        $existingInventory->save();
                    } else {
                        Inventory::create([
                            'sku' => $inbound->shipment_id,
                            'item_name' => $inbound->item_name,
                            'category' => 'General',
                            'location' => 'Main Warehouse',
                            'stock' => $inbound->expected_units,
                            'description' => $inbound->description,
                            'price' => 0.00,
                            'supplier' => $inbound->supplier,
                        ]);
                    }
                    
                    $inbound->status = 'Accepted';
                    $inbound->accepted_date = now();
                    $inbound->save();
                    $count++;
                }
            }
            return redirect()->back()->with('success', $count . ' shipments accepted successfully.');
        }

        if ($request->action === 'reject') {
            $count = 0;
            foreach ($inboundIds as $id) {
                $inbound = InboundLogistic::find($id);
                if ($inbound && $inbound->status === 'Pending') {
                    $inbound->status = 'Rejected';
                    $inbound->rejection_reason = $request->rejection_reason;
                    $inbound->rejected_date = now();
                    $inbound->save();
                    $count++;
                }
            }
            return redirect()->back()->with('success', $count . ' shipments rejected successfully.');
        }

        return redirect()->back()->with('error', 'Invalid action.');
    }
}
