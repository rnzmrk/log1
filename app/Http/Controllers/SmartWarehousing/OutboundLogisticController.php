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
                  ->orWhere('destination', 'like', '%' . $searchTerm . '%');
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
        $validated = $request->validate([
            'order_number' => 'required|string',
            'customer_name' => 'required|string|max:255',
            'destination' => 'required|string|max:255',
            'total_units' => 'required|integer|min:1',
            'priority' => 'required|in:Low,Medium,High,Urgent',
            'status' => 'required|in:Pending,Processing,Shipped,Delivered,Cancelled',
            'shipping_date' => 'nullable|date',
            'delivery_date' => 'nullable|date',
            'notes' => 'nullable|string',
            'total_value' => 'nullable|numeric|min:0|max:999999999999.99',
            'carrier' => 'nullable|string|max:255',
            'tracking_number' => 'nullable|string|max:255',
        ]);

        // Auto-generate shipment ID
        $validated['shipment_id'] = 'SHP-' . date('Y') . '-' . str_pad(OutboundLogistic::count() + 1, 4, '0', STR_PAD_LEFT);

        OutboundLogistic::create($validated);

        return redirect()->route('outbound-logistics.index')
            ->with('success', 'Outbound logistic created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $outboundLogistic = OutboundLogistic::findOrFail($id);
        return view('admin.warehousing.outbound-logistics-show', compact('outboundLogistic'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $outboundLogistic = OutboundLogistic::findOrFail($id);
        return view('admin.warehousing.outbound-logistics-edit', compact('outboundLogistic'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $outboundLogistic = OutboundLogistic::findOrFail($id);

        $validated = $request->validate([
            'shipment_id' => 'required|string|unique:outbound_logistics,shipment_id,'.$id,
            'order_number' => 'required|string',
            'customer_name' => 'required|string|max:255',
            'destination' => 'required|string|max:255',
            'total_units' => 'required|integer|min:1',
            'shipped_units' => 'nullable|integer|min:0|max:' . $request->total_units,
            'priority' => 'required|in:Low,Medium,High,Urgent',
            'status' => 'required|in:Pending,Processing,Shipped,Delivered,Cancelled',
            'shipping_date' => 'nullable|date',
            'delivery_date' => 'nullable|date',
            'notes' => 'nullable|string',
            'total_value' => 'nullable|numeric|min:0|max:999999999999.99',
            'carrier' => 'nullable|string|max:255',
            'tracking_number' => 'nullable|string|max:255',
        ]);

        $outboundLogistic->update($validated);

        return redirect()->route('outbound-logistics.index')
            ->with('success', 'Outbound logistic updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $outboundLogistic = OutboundLogistic::findOrFail($id);
        $outboundLogistic->delete();

        return redirect()->route('outbound-logistics.index')
            ->with('success', 'Outbound logistic deleted successfully.');
    }
}
