<?php

namespace App\Http\Controllers;

use App\Models\Inventory;
use App\Models\SupplyRequest;
use App\Models\AuditLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InventoryController extends Controller
{
    /**
     * Display inventory dashboard
     */
    public function dashboard()
    {
        $lowStockItems = Inventory::getLowStockItems();
        $outOfStockItems = Inventory::getOutOfStockItems();
        $pendingProcurements = SupplyRequest::whereIn('status', ['Pending', 'Approved', 'Ordered'])->get();
        
        return view('inventory.dashboard', compact('lowStockItems', 'outOfStockItems', 'pendingProcurements'));
    }

    /**
     * Display all inventory items
     */
    public function index()
    {
        $inventories = Inventory::orderBy('category')->orderBy('item_name')->paginate(20);
        return view('inventory.index', compact('inventories'));
    }

    /**
     * Show form for creating new inventory item
     */
    public function create()
    {
        return view('inventory.create');
    }

    /**
     * Store new inventory item
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'sku' => 'required|unique:inventories',
            'item_name' => 'required|string|max:255',
            'category' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'stock' => 'required|integer|min:0',
            'description' => 'nullable|string',
            'price' => 'nullable|numeric|min:0',
            'supplier' => 'nullable|string|max:255',
        ]);

        $inventory = Inventory::create($validated);

        // Create audit log
        AuditLog::create([
            'action' => 'create',
            'model_type' => 'Inventory',
            'model_id' => $inventory->id,
            'new_values' => json_encode($validated),
            'user_id' => Auth::id(),
            'notes' => 'New inventory item created',
        ]);

        return redirect()->route('inventory.index')
            ->with('success', 'Inventory item created successfully.');
    }

    /**
     * Show inventory item details
     */
    public function show(Inventory $inventory)
    {
        $procurementRequests = SupplyRequest::where('item_name', $inventory->item_name)
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        return view('inventory.show', compact('inventory', 'procurementRequests'));
    }

    /**
     * Show form for editing inventory item
     */
    public function edit(Inventory $inventory)
    {
        return view('inventory.edit', compact('inventory'));
    }

    /**
     * Update inventory item
     */
    public function update(Request $request, Inventory $inventory)
    {
        $validated = $request->validate([
            'sku' => 'required|unique:inventories,sku,' . $inventory->id,
            'item_name' => 'required|string|max:255',
            'category' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'stock' => 'required|integer|min:0',
            'description' => 'nullable|string',
            'price' => 'nullable|numeric|min:0',
            'supplier' => 'nullable|string|max:255',
        ]);

        $oldValues = $inventory->toArray();
        $inventory->update($validated);

        // Create audit log
        AuditLog::create([
            'action' => 'update',
            'model_type' => 'Inventory',
            'model_id' => $inventory->id,
            'old_values' => json_encode($oldValues),
            'new_values' => json_encode($validated),
            'user_id' => Auth::id(),
            'notes' => 'Inventory item updated',
        ]);

        return redirect()->route('inventory.index')
            ->with('success', 'Inventory item updated successfully.');
    }

    /**
     * Delete inventory item
     */
    public function destroy(Inventory $inventory)
    {
        $oldValues = $inventory->toArray();
        $inventory->delete();

        // Create audit log
        AuditLog::create([
            'action' => 'delete',
            'model_type' => 'Inventory',
            'model_id' => $inventory->id,
            'old_values' => json_encode($oldValues),
            'user_id' => Auth::id(),
            'notes' => 'Inventory item deleted',
        ]);

        return redirect()->route('inventory.index')
            ->with('success', 'Inventory item deleted successfully.');
    }

    /**
     * Show low stock items
     */
    public function lowStock()
    {
        $lowStockItems = Inventory::getLowStockItems();
        return view('inventory.low-stock', compact('lowStockItems'));
    }

    /**
     * Show out of stock items
     */
    public function outOfStock()
    {
        $outOfStockItems = Inventory::getOutOfStockItems();
        return view('inventory.out-of-stock', compact('outOfStockItems'));
    }

    /**
     * Create procurement request for low stock item
     */
    public function createProcurementRequest(Inventory $inventory)
    {
        if ($inventory->stock > 10) {
            return redirect()->back()
                ->with('error', 'This item is not low stock.');
        }

        // Check if there's already a pending request
        $existingRequest = SupplyRequest::where('item_name', $inventory->item_name)
            ->whereIn('status', ['Pending', 'Approved', 'Ordered'])
            ->first();

        if ($existingRequest) {
            return redirect()->back()
                ->with('error', 'There is already a pending procurement request for this item.');
        }

        $suggestedQuantity = max($inventory->stock * 2, 20);

        return view('inventory.procurement-request', compact('inventory', 'suggestedQuantity'));
    }

    /**
     * Store procurement request
     */
    public function storeProcurementRequest(Request $request, Inventory $inventory)
    {
        $validated = $request->validate([
            'quantity_requested' => 'required|integer|min:1',
            'unit_price' => 'nullable|numeric|min:0',
            'priority' => 'required|in:Low,Medium,High',
            'notes' => 'nullable|string',
        ]);

        $validated['item_name'] = $inventory->item_name;
        $validated['category'] = $inventory->category;
        $validated['supplier'] = $inventory->supplier;
        $validated['quantity_approved'] = $validated['quantity_requested'];
        $validated['unit_price'] = $validated['unit_price'] ?? $inventory->price;
        $validated['status'] = 'Pending';
        $validated['request_date'] = now();
        $validated['needed_by_date'] = now()->addDays(7);
        $validated['requested_by'] = Auth::user()->name;

        $supplyRequest = SupplyRequest::create($validated);

        return redirect()->route('inventory.show', $inventory)
            ->with('success', 'Procurement request created successfully.');
    }
}
