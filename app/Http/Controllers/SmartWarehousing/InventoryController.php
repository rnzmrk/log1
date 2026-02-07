<?php

namespace App\Http\Controllers\SmartWarehousing;

use App\Http\Controllers\Controller;
use App\Models\Inventory;
use App\Models\OutboundLogistic;
use App\Models\ReturnRefund;
use Illuminate\Http\Request;

class InventoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Inventory::query();

        // Search functionality
        if ($request->filled('search')) {
            $searchTerm = $request->search;
            $query->where(function($q) use ($searchTerm) {
                $q->where('sku', 'like', '%' . $searchTerm . '%')
                  ->orWhere('item_name', 'like', '%' . $searchTerm . '%')
                  ->orWhere('category', 'like', '%' . $searchTerm . '%');
            });
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by category
        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        $inventories = $query->orderBy('created_at', 'desc')->paginate(10);
        
        return view('admin.warehousing.storage-inventory', compact('inventories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.warehousing.storage-inventory-create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'sku' => 'required|string|unique:inventories,sku',
            'item_name' => 'required|string',
            'category' => 'required|string',
            'location' => 'required|string',
            'stock' => 'required|integer|min:0',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'supplier' => 'required|string',
        ]);

        Inventory::create($request->all());

        return redirect()->route('admin.warehousing.storage-inventory')
            ->with('success', 'Item added successfully to inventory.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Inventory $inventory)
    {
        return view('admin.warehousing.storage-inventory-show', compact('inventory'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Inventory $inventory)
    {
        return view('admin.warehousing.storage-inventory-edit', compact('inventory'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Inventory $inventory)
    {
        $request->validate([
            'sku' => 'required|string|unique:inventories,sku,' . $inventory->id,
            'item_name' => 'required|string',
            'category' => 'required|string',
            'location' => 'required|string',
            'stock' => 'required|integer|min:0',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'supplier' => 'required|string',
        ]);

        $inventory->update($request->all());

        return redirect()->route('admin.warehousing.storage-inventory')
            ->with('success', 'Item updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    // public function destroy(Inventory $inventory)
    // {
    //     // Delete functionality removed
    // }

    /**
     * Request supply for low stock items
     */
    public function requestSupply(Inventory $inventory)
    {
        if ($inventory->status !== 'Low Stock') {
            return redirect()->back()
                ->with('error', 'Supply request can only be made for low stock items.');
        }

        // Create outbound logistic entry for supply request
        OutboundLogistic::create([
            'inventory_id' => $inventory->id,
            'item_name' => $inventory->item_name,
            'sku' => $inventory->sku,
            'quantity' => 50, // Default supply quantity
            'department' => 'Warehouse',
            'status' => 'Pending Supply',
            'request_type' => 'Supply Request',
            'requested_by' => 'System',
            'request_date' => now(),
        ]);

        return redirect()->back()
            ->with('success', 'Supply request created successfully for ' . $inventory->item_name);
    }

    /**
     * Return item to returns management
     */
    public function returnItem(Request $request, Inventory $inventory)
    {
        $request->validate([
            'return_reason' => 'required|string',
            'return_quantity' => 'required|integer|min:1|max:' . $inventory->stock,
        ]);

        // Create return entry
        ReturnRefund::create([
            'inventory_id' => $inventory->id,
            'item_name' => $inventory->item_name,
            'sku' => $inventory->sku,
            'quantity' => $request->return_quantity,
            'reason' => $request->return_reason,
            'status' => 'Return Pending',
            'return_date' => now(),
            'returned_by' => 'Warehouse Staff',
        ]);

        // Update inventory stock
        $inventory->stock -= $request->return_quantity;
        $inventory->save();

        return redirect()->back()
            ->with('success', 'Item return processed successfully.');
    }

    /**
     * Get low stock items for supply requests
     */
    public function getLowStockItems()
    {
        $lowStockItems = Inventory::where('status', 'Low Stock')
            ->orderBy('stock', 'asc')
            ->get();

        return response()->json($lowStockItems);
    }

    /**
     * Move item to outbound logistics for department supply
     */
    public function moveToOutbound(Request $request, Inventory $inventory)
    {
        $request->validate([
            'department' => 'required|string',
            'quantity' => 'required|integer|min:1|max:' . $inventory->stock,
        ]);

        // Create outbound logistic entry
        OutboundLogistic::create([
            'inventory_id' => $inventory->id,
            'item_name' => $inventory->item_name,
            'sku' => $inventory->sku,
            'quantity' => $request->quantity,
            'department' => $request->department,
            'status' => 'Ready to Ship',
            'request_type' => 'Department Supply',
            'requested_by' => 'Warehouse Staff',
            'request_date' => now(),
        ]);

        // Update inventory stock
        $inventory->stock -= $request->quantity;
        $inventory->save();

        return redirect()->route('admin.warehousing.outbound-logistics')
            ->with('success', 'Item moved to outbound logistics for ' . $request->department);
    }
}
