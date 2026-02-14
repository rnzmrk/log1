<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SupplyRequest;

class RequestSupplyController extends Controller
{
    /**
     * Display a listing of supply requests.
     */
    public function index()
    {
        $supplyRequests = SupplyRequest::orderBy('created_at', 'desc')->get();
        
        return response()->json([
            'success' => true,
            'data' => $supplyRequests,
            'message' => 'Supply requests retrieved successfully'
        ]);
    }

    /**
     * Store a newly created supply request in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'item_name' => 'required|string|max:255',
            'category' => 'required|string|max:255',
            'supplier' => 'required|string|max:255',
            'quantity_requested' => 'required|integer|min:1',
            'quantity_approved' => 'nullable|integer|min:1',
            'unit_price' => 'nullable|numeric|min:0',
            'priority' => 'required|in:Low,Medium,High,Urgent',
            'status' => 'required|in:Pending,Approved,Rejected,Ordered,Received',
            'request_date' => 'required|date',
            'needed_by_date' => 'required|date',
            'approval_date' => 'nullable|date',
            'order_date' => 'nullable|date',
            'expected_delivery' => 'nullable|date',
            'notes' => 'nullable|string',
            'requested_by' => 'required|string|max:255',
            'approved_by' => 'nullable|string|max:255',
        ]);

        // Auto-generate request number
        $validated['request_number'] = 'SR-' . date('Y') . '-' . str_pad(SupplyRequest::count() + 1, 4, '0', STR_PAD_LEFT);
        
        // Set defaults for optional fields if not provided
        $validated['quantity_approved'] = $validated['quantity_approved'] ?? $validated['quantity_requested'];
        $validated['status'] = $validated['status'] ?? 'Pending';
        $validated['request_date'] = $validated['request_date'] ?? now()->format('Y-m-d');
        $validated['requested_by'] = $validated['requested_by'] ?? 'API User';

        $supplyRequest = SupplyRequest::create($validated);

        return response()->json([
            'success' => true,
            'data' => $supplyRequest,
            'message' => 'Supply request created successfully'
        ], 201);
    }

    /**
     * Display the specified supply request.
     */
    public function show($id)
    {
        $supplyRequest = SupplyRequest::find($id);
        
        if (!$supplyRequest) {
            return response()->json([
                'success' => false,
                'message' => 'Supply request not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $supplyRequest,
            'message' => 'Supply request retrieved successfully'
        ]);
    }
}
