<?php

namespace App\Http\Controllers;

use App\Models\Inventory;
use App\Models\SupplyRequest;
use App\Models\AuditLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class LogisticsController extends Controller
{
    /**
     * Display logistics dashboard
     */
    public function dashboard()
    {
        $pendingDeliveries = SupplyRequest::where('status', 'Ordered')->get();
        $recentInbound = AuditLog::where('action', 'stock_update')
            ->where('notes', 'like', '%inbound%')
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();
        $recentOutbound = AuditLog::where('action', 'stock_update')
            ->where('notes', 'like', '%outbound%')
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        return view('logistics.dashboard', compact('pendingDeliveries', 'recentInbound', 'recentOutbound'));
    }

    /**
     * Show outbound delivery form
     */
    public function outboundCreate()
    {
        $inventoryItems = Inventory::where('stock', '>', 0)->get();
        return view('logistics.outbound-create', compact('inventoryItems'));
    }

    /**
     * Process outbound delivery
     */
    public function outboundStore(Request $request)
    {
        $validated = $request->validate([
            'inventory_id' => 'required|exists:inventories,id',
            'quantity' => 'required|integer|min:1',
            'destination' => 'required|string|max:255',
            'recipient' => 'required|string|max:255',
            'notes' => 'nullable|string',
        ]);

        $inventory = Inventory::findOrFail($validated['inventory_id']);

        try {
            DB::beginTransaction();

            // Remove stock from inventory
            $reference = "OUT-" . date('YmdHis');
            $inventory->removeStock($validated['quantity'], $reference);

            // Create delivery record
            $deliveryData = [
                'inventory_id' => $inventory->id,
                'item_name' => $inventory->item_name,
                'quantity' => $validated['quantity'],
                'destination' => $validated['destination'],
                'recipient' => $validated['recipient'],
                'notes' => $validated['notes'],
                'status' => 'In Transit',
                'delivery_date' => now(),
                'reference' => $reference,
                'created_by' => Auth::user()->name,
            ];

            // Create audit log for delivery
            AuditLog::create([
                'action' => 'outbound_delivery',
                'model_type' => 'Inventory',
                'model_id' => $inventory->id,
                'new_values' => json_encode($deliveryData),
                'user_id' => Auth::id(),
                'notes' => "Outbound delivery: {$validated['quantity']} units of {$inventory->item_name} to {$validated['destination']}",
            ]);

            DB::commit();

            return redirect()->route('logistics.dashboard')
                ->with('success', "Outbound delivery processed successfully. Reference: {$reference}");

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Error processing outbound delivery: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Show inbound acceptance form
     */
    public function inboundCreate()
    {
        $orderedItems = SupplyRequest::where('status', 'Ordered')->get();
        return view('logistics.inbound-create', compact('orderedItems'));
    }

    /**
     * Process inbound acceptance
     */
    public function inboundStore(Request $request)
    {
        $validated = $request->validate([
            'supply_request_id' => 'required|exists:supply_requests,id',
            'quantity_received' => 'required|integer|min:1',
            'condition' => 'required|in:Good,Damaged,Defective',
            'notes' => 'nullable|string',
        ]);

        $supplyRequest = SupplyRequest::findOrFail($validated['supply_request_id']);

        try {
            DB::beginTransaction();

            // Find or create inventory item
            $inventory = Inventory::where('item_name', $supplyRequest->item_name)->first();
            
            if (!$inventory) {
                // Create new inventory item if it doesn't exist
                $inventory = Inventory::create([
                    'sku' => 'AUTO-' . date('YmdHis'),
                    'item_name' => $supplyRequest->item_name,
                    'category' => $supplyRequest->category,
                    'location' => 'Warehouse',
                    'stock' => 0,
                    'supplier' => $supplyRequest->supplier,
                    'price' => $supplyRequest->unit_price,
                ]);
            }

            // Add stock to inventory
            $reference = "IN-" . date('YmdHis');
            $inventory->addStock($validated['quantity_received'], $reference);

            // Update supply request status if fully delivered
            if ($validated['quantity_received'] >= $supplyRequest->quantity_approved) {
                $supplyRequest->update([
                    'status' => 'Delivered',
                ]);
            }

            // Create inbound receipt record
            $receiptData = [
                'supply_request_id' => $supplyRequest->id,
                'inventory_id' => $inventory->id,
                'item_name' => $supplyRequest->item_name,
                'quantity_received' => $validated['quantity_received'],
                'condition' => $validated['condition'],
                'notes' => $validated['notes'],
                'status' => 'Accepted',
                'receipt_date' => now(),
                'reference' => $reference,
                'received_by' => Auth::user()->name,
            ];

            // Create audit log for receipt
            AuditLog::create([
                'action' => 'inbound_receipt',
                'model_type' => 'Inventory',
                'model_id' => $inventory->id,
                'new_values' => json_encode($receiptData),
                'user_id' => Auth::id(),
                'notes' => "Inbound receipt: {$validated['quantity_received']} units of {$inventory->item_name} (Condition: {$validated['condition']})",
            ]);

            DB::commit();

            return redirect()->route('logistics.dashboard')
                ->with('success', "Inbound receipt processed successfully. Reference: {$reference}");

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Error processing inbound receipt: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Show delivery history
     */
    public function history()
    {
        $deliveries = AuditLog::where(function($query) {
                $query->where('action', 'outbound_delivery')
                    ->orWhere('action', 'inbound_receipt');
            })
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('logistics.history', compact('deliveries'));
    }

    /**
     * Show pending deliveries
     */
    public function pendingDeliveries()
    {
        $pendingDeliveries = SupplyRequest::where('status', 'Ordered')
            ->orderBy('expected_delivery', 'asc')
            ->get();

        return view('logistics.pending', compact('pendingDeliveries'));
    }
}
