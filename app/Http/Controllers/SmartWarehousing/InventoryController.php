<?php

namespace App\Http\Controllers\SmartWarehousing;

use App\Http\Controllers\Controller;
use App\Models\Inventory;
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
        $validated = $request->validate([
            'item_name' => 'required|string|max:255',
            'category' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'stock' => 'required|integer|min:0',
            'description' => 'nullable|string',
            'price' => 'nullable|numeric|min:0',
            'supplier' => 'nullable|string|max:255',
        ]);

        // Auto-generate SKU
        $validated['sku'] = 'SKU-' . date('Y') . '-' . str_pad(Inventory::count() + 1, 4, '0', STR_PAD_LEFT);

        Inventory::create($validated);

        return redirect()->route('inventory.index')
            ->with('success', 'Inventory item created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $inventory = Inventory::findOrFail($id);
        return view('admin.warehousing.storage-inventory-show', compact('inventory'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $inventory = Inventory::findOrFail($id);
        return view('admin.warehousing.storage-inventory-edit', compact('inventory'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $inventory = Inventory::findOrFail($id);

        $validated = $request->validate([
            'sku' => 'required|string|unique:inventories,sku,'.$id,
            'item_name' => 'required|string|max:255',
            'category' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'stock' => 'required|integer|min:0',
            'description' => 'nullable|string',
            'price' => 'nullable|numeric|min:0',
            'supplier' => 'nullable|string|max:255',
        ]);

        $inventory->update($validated);

        return redirect()->route('inventory.index')
            ->with('success', 'Inventory item updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $inventory = Inventory::findOrFail($id);
        $inventory->delete();

        return redirect()->route('inventory.index')
            ->with('success', 'Inventory item deleted successfully.');
    }

    /**
     * Move inventory item to new location
     */
    public function move(Request $request, string $id)
    {
        $inventory = Inventory::findOrFail($id);

        $validated = $request->validate([
            'new_location' => 'required|string|max:255',
            'move_quantity' => 'required|integer|min:1|max:' . $inventory->stock,
        ]);

        // Update the location and reduce stock
        $inventory->location = $validated['new_location'];
        $inventory->stock -= $validated['move_quantity'];
        $inventory->save();

        // Create a new inventory record for the moved quantity
        Inventory::create([
            'sku' => $inventory->sku . '-MOVED-' . time(),
            'item_name' => $inventory->item_name,
            'category' => $inventory->category,
            'location' => $validated['new_location'],
            'stock' => $validated['move_quantity'],
            'description' => $inventory->description,
            'price' => $inventory->price,
            'supplier' => $inventory->supplier,
        ]);

        return redirect()->route('inventory.index')
            ->with('success', 'Inventory item moved successfully.');
    }
}
