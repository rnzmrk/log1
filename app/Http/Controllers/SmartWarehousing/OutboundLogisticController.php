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
        
        return view('admin.warehousing.outbound-logistics', compact('outboundLogistics'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.warehousing.outbound-logistics-create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'shipment_id' => 'required|string|unique:outbound_logistics,shipment_id',
            'order_number' => 'required|string',
            'customer_name' => 'required|string',
            'item_name' => 'required|string',
            'quantity' => 'required|integer|min:1',
            'destination' => 'required|string',
            'priority' => 'required|in:Low,Medium,High',
            'expected_date' => 'required|date',
        ]);

        OutboundLogistic::create($request->all());

        return redirect()->route('admin.warehousing.outbound-logistics')
            ->with('success', 'Outbound shipment created successfully.');
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
            'order_number' => 'required|string',
            'customer_name' => 'required|string',
            'item_name' => 'required|string',
            'quantity' => 'required|integer|min:1',
            'destination' => 'required|string',
            'priority' => 'required|in:Low,Medium,High',
            'expected_date' => 'required|date',
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
     * Get pending supply requests
     */
    public function getPendingSupplyRequests()
    {
        $pendingRequests = OutboundLogistic::where('request_type', 'Supply Request')
            ->where('status', 'Pending Supply')
            ->orderBy('created_at', 'desc')
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
}
