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
        
        return view('admin.warehousing.inbound-logistics', compact('inboundLogistics'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.warehousing.inbound-logistics-create');
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
            'quantity' => 'required|integer|min:1',
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
        return view('admin.warehousing.inbound-logistics-edit', compact('inboundLogistic'));
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
            'quantity' => 'required|integer|min:1',
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
            $existingInventory->stock += $inboundLogistic->quantity;
            $existingInventory->save();
        } else {
            // Create new inventory entry
            Inventory::create([
                'sku' => $inboundLogistic->shipment_id,
                'item_name' => $inboundLogistic->item_name,
                'category' => 'General',
                'location' => 'Main Warehouse',
                'stock' => $inboundLogistic->quantity,
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
}
