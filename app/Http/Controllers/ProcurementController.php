<?php

namespace App\Http\Controllers;

use App\Models\SupplyRequest;
use App\Models\Inventory;
use App\Models\AuditLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProcurementController extends Controller
{
    /**
     * Display all procurement requests
     */
    public function index()
    {
        $requests = SupplyRequest::orderBy('created_at', 'desc')->paginate(20);
        return view('procurement.index', compact('requests'));
    }

    /**
     * Show procurement request details
     */
    public function show(SupplyRequest $supplyRequest)
    {
        return view('procurement.show', compact('supplyRequest'));
    }

    /**
     * Approve procurement request
     */
    public function approve(SupplyRequest $supplyRequest)
    {
        if ($supplyRequest->status !== 'Pending') {
            return redirect()->back()
                ->with('error', 'Only pending requests can be approved.');
        }

        $supplyRequest->update([
            'status' => 'Approved',
            'approval_date' => now(),
            'approved_by' => Auth::user()->name,
        ]);

        // Create audit log
        AuditLog::create([
            'action' => 'approve_procurement',
            'model_type' => 'SupplyRequest',
            'model_id' => $supplyRequest->id,
            'user_id' => Auth::id(),
            'notes' => 'Procurement request approved',
        ]);

        return redirect()->back()
            ->with('success', 'Procurement request approved successfully.');
    }

    /**
     * Mark procurement request as ordered
     */
    public function markAsOrdered(SupplyRequest $supplyRequest)
    {
        if ($supplyRequest->status !== 'Approved') {
            return redirect()->back()
                ->with('error', 'Only approved requests can be marked as ordered.');
        }

        $supplyRequest->update([
            'status' => 'Ordered',
            'order_date' => now(),
        ]);

        // Create audit log
        AuditLog::create([
            'action' => 'order_procurement',
            'model_type' => 'SupplyRequest',
            'model_id' => $supplyRequest->id,
            'user_id' => Auth::id(),
            'notes' => 'Procurement request marked as ordered',
        ]);

        return redirect()->back()
            ->with('success', 'Procurement request marked as ordered.');
    }

    /**
     * Show form for creating manual procurement request
     */
    public function create()
    {
        $inventoryItems = Inventory::all();
        return view('procurement.create', compact('inventoryItems'));
    }

    /**
     * Store manual procurement request
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'item_name' => 'required|string|max:255',
            'category' => 'required|string|max:255',
            'supplier' => 'nullable|string|max:255',
            'quantity_requested' => 'required|integer|min:1',
            'unit_price' => 'nullable|numeric|min:0',
            'priority' => 'required|in:Low,Medium,High',
            'notes' => 'nullable|string',
            'needed_by_date' => 'nullable|date|after:today',
        ]);

        $validated['quantity_approved'] = $validated['quantity_requested'];
        $validated['status'] = 'Pending';
        $validated['request_date'] = now();
        $validated['needed_by_date'] = $validated['needed_by_date'] ?? now()->addDays(7);
        $validated['requested_by'] = Auth::user()->name;

        $supplyRequest = SupplyRequest::create($validated);

        return redirect()->route('procurement.index')
            ->with('success', 'Procurement request created successfully.');
    }

    /**
     * Delete procurement request
     */
    public function destroy(SupplyRequest $supplyRequest)
    {
        if (in_array($supplyRequest->status, ['Approved', 'Ordered'])) {
            return redirect()->back()
                ->with('error', 'Cannot delete approved or ordered requests.');
        }

        $oldValues = $supplyRequest->toArray();
        $supplyRequest->delete();

        // Create audit log
        AuditLog::create([
            'action' => 'delete_procurement',
            'model_type' => 'SupplyRequest',
            'model_id' => $supplyRequest->id,
            'old_values' => json_encode($oldValues),
            'user_id' => Auth::id(),
            'notes' => 'Procurement request deleted',
        ]);

        return redirect()->route('procurement.index')
            ->with('success', 'Procurement request deleted successfully.');
    }
}
