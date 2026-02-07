<?php

namespace App\Http\Controllers\SmartWarehousing;

use App\Http\Controllers\Controller;
use App\Models\InboundLogistic;
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
        $validated = $request->validate([
            'shipment_id' => 'required|string|unique:inbound_logistics,shipment_id',
            'po_number' => 'required|string',
            'supplier' => 'required|string',
            'expected_units' => 'required|integer|min:1',
            'expected_date' => 'required|date',
            'notes' => 'nullable|string',
        ]);

        $validated['status'] = 'In Progress';
        $validated['quality'] = 'Pending';

        InboundLogistic::create($validated);

        return redirect()->route('inbound-logistics.index')
            ->with('success', 'Inbound logistic created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $inboundLogistic = InboundLogistic::findOrFail($id);
        return view('admin.warehousing.inbound-logistics-show', compact('inboundLogistic'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $inboundLogistic = InboundLogistic::findOrFail($id);
        return view('admin.warehousing.inbound-logistics-edit', compact('inboundLogistic'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $inboundLogistic = InboundLogistic::findOrFail($id);

        $validated = $request->validate([
            'shipment_id' => 'required|string|unique:inbound_logistics,shipment_id,'.$id,
            'po_number' => 'required|string',
            'supplier' => 'required|string',
            'expected_units' => 'required|integer|min:1',
            'received_units' => 'nullable|integer|min:0',
            'quality' => 'required|in:Good,Pending',
            'status' => 'required|in:In Progress,Verified,Putaway Complete',
            'expected_date' => 'required|date',
            'received_date' => 'nullable|date',
            'notes' => 'nullable|string',
        ]);

        $inboundLogistic->update($validated);

        return redirect()->route('inbound-logistics.index')
            ->with('success', 'Inbound logistic updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $inboundLogistic = InboundLogistic::findOrFail($id);
        $inboundLogistic->delete();

        return redirect()->route('inbound-logistics.index')
            ->with('success', 'Inbound logistic deleted successfully.');
    }
}
