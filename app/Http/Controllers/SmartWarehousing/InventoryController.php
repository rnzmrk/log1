<?php

namespace App\Http\Controllers\SmartWarehousing;

use App\Http\Controllers\Controller;
use App\Models\Inventory;
use App\Models\OutboundLogistic;
use App\Models\Supplier;
use Illuminate\Http\Request;

class InventoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Inventory::query();

        // Filter out items with "Returned" and "Moved" status
        $query->whereNotIn('status', ['Returned', 'Moved']);

        // Search functionality
        if ($request->filled('search')) {
            $searchTerm = $request->search;
            $query->where(function($q) use ($searchTerm) {
                $q->where('sku', 'like', '%' . $searchTerm . '%')
                  ->orWhere('item_name', 'like', '%' . $searchTerm . '%')
                  ->orWhere('category', 'like', '%' . $searchTerm . '%')
                  ->orWhere('supplier', 'like', '%' . $searchTerm . '%')
                  ->orWhere('department', 'like', '%' . $searchTerm . '%');
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

        // Filter by department
        if ($request->filled('department')) {
            $query->where('department', $request->department);
        }

        // Filter by supplier
        if ($request->filled('supplier')) {
            $query->where('supplier', $request->supplier);
        }

        $inventories = $query->orderBy('created_at', 'desc')->paginate(10);
        
        // Get returned items for the return modal dropdown
        $returnedItems = Inventory::where('status', 'Returned')->get();

        // Get statistics
        $stats = [
            'total_items' => $query->count(),
            'total_stock' => $query->sum('stock'),
            'low_stock' => $query->where('stock', '<=', 10)->where('stock', '>', 0)->count(),
            'out_of_stock' => $query->where('stock', 0)->count(),
        ];
        
        // Get categories for filter
        $categories = Inventory::whereNotIn('status', ['Returned', 'Moved'])
            ->whereNotNull('category')
            ->distinct()
            ->pluck('category');
        
        $departments = Inventory::whereNotIn('status', ['Returned', 'Moved'])
            ->whereNotNull('department')
            ->distinct()
            ->pluck('department');
        
        $suppliers = Inventory::whereNotIn('status', ['Returned', 'Moved'])
            ->whereNotNull('supplier')
            ->distinct()
            ->pluck('supplier');

        // Get active vendors for the request modal
        $vendors = Supplier::where('status', 'Active')->get();

        return view('admin.warehousing.storage-inventory', compact('inventories', 'stats', 'categories', 'departments', 'suppliers', 'vendors'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $suppliers = \App\Models\Supplier::whereIn('status', ['Accepted', 'Active'])
            ->orderBy('name')
            ->get();
        
        // Get inbound logistics with Storage status for selection
        $inboundLogistics = \App\Models\InboundLogistic::where('status', 'Storage')
            ->orderBy('created_at', 'desc')
            ->get();
        
        return view('admin.warehousing.storage-inventory-create', compact('suppliers', 'inboundLogistics'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'inbound_logistic_id' => 'required|exists:inbound_logistics,id',
            'item_name' => 'required|string',
            'department' => 'nullable|string',
            'stock' => 'required|integer|min:0',
            'description' => 'nullable|string',
            'sku' => 'nullable|string|unique:inventories,sku',
            'supplier' => 'nullable|string',
        ]);

        $data = $request->only([
            'sku',
            'item_name',
            'department',
            'stock',
            'description',
            'supplier',
        ]);

        // Auto-set status to "On Stock" for all new inventory items
        $data['status'] = 'On Stock';

        // Auto-fill data from inbound logistics if selected
        if ($request->filled('inbound_logistic_id')) {
            $inboundLogistic = \App\Models\InboundLogistic::find($request->inbound_logistic_id);
            if ($inboundLogistic) {
                // Update inbound status to "Stored"
                $inboundLogistic->status = 'Stored';
                $inboundLogistic->save();
                
                // Auto-fill inventory data from inbound
                $data['po_number'] = $inboundLogistic->po_number;
                $data['supplier'] = $inboundLogistic->supplier;
                $data['stock'] = $inboundLogistic->quantity; // Use quantity from inbound
                $data['department'] = $inboundLogistic->department; // Auto-fill department
                $data['category'] = $inboundLogistic->category; // Auto-fill category (can be null)
                $data['location'] = 'Storage'; // Set default location
                
                // If no SKU provided, generate one based on item name
                if (empty($data['sku'])) {
                    $data['sku'] = $this->generateSku();
                }
            }
        } else {
            // Manual entry is no longer allowed - require inbound selection
            return redirect()->back()
                ->with('error', 'Please select an inbound shipment to create inventory.')
                ->withInput();
        }

        Inventory::create($data);

        return redirect()->route('admin.warehousing.storage-inventory')
            ->with('success', 'Item added successfully to inventory.');
    }

    private function generateSku(): string
    {
        do {
            $sku = 'SKU-' . strtoupper(bin2hex(random_bytes(4)));
        } while (Inventory::where('sku', $sku)->exists());

        return $sku;
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
        $suppliers = \App\Models\Supplier::whereIn('status', ['Accepted', 'Active'])
            ->orderBy('name')
            ->get();
        
        return view('admin.warehousing.storage-inventory-edit', compact('inventory', 'suppliers'));
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
     * Return item - update inventory status to Returned
     */
    public function returnItem(Request $request, Inventory $inventory)
    {
        $request->validate([
            'return_quantity' => 'required|integer|min:1|max:' . $inventory->stock,
            'return_reason' => 'required|string',
        ]);

        $returnQuantity = $request->integer('return_quantity');
        $returnReason = $request->input('return_reason');

        // If returning all stock, update status to Returned
        if ($returnQuantity >= $inventory->stock) {
            $inventory->stock = 0;
            $inventory->status = 'Returned';
            $inventory->notes = $returnReason; // Save return reason to notes field
            $inventory->save();

            return response()->json([
                'success' => true,
                'message' => "Returned all {$returnQuantity} units - Status set to Returned",
            ]);
        } else {
            // Partial return: create new record for returned items
            $returnedInventory = $inventory->replicate();
            $returnedInventory->stock = $returnQuantity;
            $returnedInventory->sku = $this->generateSku();
            $returnedInventory->status = 'Returned';
            $returnedInventory->notes = $returnReason; // Save return reason to notes field
            $returnedInventory->save();

            // Reduce original stock
            $inventory->stock -= $returnQuantity;
            $inventory->save();

            return response()->json([
                'success' => true,
                'message' => "Returned {$returnQuantity} units - Status set to Returned",
            ]);
        }
    }

    /**
     * Search inventory items for autocomplete
     */
    public function search(Request $request)
    {
        $query = $request->get('q', '');
        if (strlen($query) < 2) {
            return response()->json([]);
        }

        $items = Inventory::where('sku', 'like', '%' . $query . '%')
            ->orWhere('item_name', 'like', '%' . $query . '%')
            ->select(['id', 'sku', 'item_name', 'stock', 'location'])
            ->limit(10)
            ->get();

        return response()->json($items);
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
     * Move inventory item to a new department (with partial quantity support)
     */
    public function move(Request $request, $id)
    {
        $inventory = Inventory::findOrFail($id);

        $request->validate([
            'move_quantity' => 'required|integer|min:1|max:' . $inventory->stock,
            'new_department' => 'required|string',
        ]);

        $moveQuantity = $request->integer('move_quantity');
        $newDepartment = $request->input('new_department');

        // If moving the full stock, just update department and status
        if ($moveQuantity >= $inventory->stock) {
            $inventory->department = $newDepartment;
            $inventory->status = 'Moved';
            $inventory->save();

            return response()->json([
                'success' => true,
                'message' => "Moved all {$moveQuantity} units to {$newDepartment}",
            ]);
        }

        // Partial move: create a new inventory record for the moved quantity
        $newInventory = $inventory->replicate();
        $newInventory->stock = $moveQuantity;
        $newInventory->sku = $this->generateSku(); // ensure unique SKU for the split item
        $newInventory->status = 'Moved';
        $newInventory->department = $newDepartment;
        $newInventory->save();

        // Reduce original stock
        $inventory->stock -= $moveQuantity;
        $inventory->save();

        return response()->json([
            'success' => true,
            'message' => "Moved {$moveQuantity} units to {$newDepartment}",
        ]);
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

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Inventory $inventory)
    {
        $itemName = $inventory->item_name;
        $inventory->delete();

        return redirect()->route('admin.warehousing.storage-inventory')
            ->with('success', "Item '{$itemName}' has been deleted successfully.");
    }

    /**
     * Export inventory to CSV
     */
    public function export(Request $request)
    {
        $query = Inventory::query();

        // Apply same filters as index
        if ($request->filled('search')) {
            $searchTerm = $request->search;
            $query->where(function($q) use ($searchTerm) {
                $q->where('sku', 'like', '%' . $searchTerm . '%')
                  ->orWhere('item_name', 'like', '%' . $searchTerm . '%')
                  ->orWhere('category', 'like', '%' . $searchTerm . '%');
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        $inventories = $query->orderBy('created_at', 'desc')->get();

        // Create CSV export
        $filename = 'inventory-' . date('Y-m-d') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($inventories) {
            $file = fopen('php://output', 'w');
            
            // CSV Header
            fputcsv($file, [
                'SKU',
                'Item Name',
                'Category',
                'Location',
                'Stock',
                'Status',
                'Price',
                'Total Value',
                'Supplier',
                'Description',
                'Last Updated'
            ]);

            // CSV Data
            foreach ($inventories as $inventory) {
                fputcsv($file, [
                    $inventory->sku,
                    $inventory->item_name,
                    $inventory->category,
                    $inventory->location,
                    $inventory->stock,
                    $inventory->status,
                    $inventory->price,
                    $inventory->stock * $inventory->price,
                    $inventory->supplier,
                    $inventory->description,
                    $inventory->updated_at->format('Y-m-d H:i:s')
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Bulk actions on inventory
     */
    public function bulkAction(Request $request)
    {
        $request->validate([
            'action' => 'required|in:update_status,update_category,delete',
            'inventory_ids' => 'required|array',
            'inventory_ids.*' => 'exists:inventories,id',
            'status' => 'required_if:action,update_status|in:Available,Low Stock,Out of Stock',
            'category' => 'required_if:action,update_category|string',
        ]);

        $inventoryIds = $request->inventory_ids;

        if ($request->action === 'update_status') {
            Inventory::whereIn('id', $inventoryIds)->update(['status' => $request->status]);
            return redirect()->back()->with('success', 'Inventory status updated successfully.');
        }

        if ($request->action === 'update_category') {
            Inventory::whereIn('id', $inventoryIds)->update(['category' => $request->category]);
            return redirect()->back()->with('success', 'Inventory category updated successfully.');
        }

        if ($request->action === 'delete') {
            Inventory::whereIn('id', $inventoryIds)->delete();
            return redirect()->back()->with('success', 'Inventory items deleted successfully.');
        }

        return redirect()->back()->with('error', 'Invalid action.');
    }

    /**
     * Get inventory statistics for dashboard
     */
    public function getStats()
    {
        $stats = [
            'total_items' => Inventory::count(),
            'total_stock' => Inventory::sum('stock'),
            'low_stock' => Inventory::where('status', 'Low Stock')->count(),
            'out_of_stock' => Inventory::where('stock', 0)->count(),
            'total_value' => Inventory::selectRaw('SUM(stock * price) as total_value')->value('total_value'),
            'categories' => Inventory::selectRaw('category, COUNT(*) as count')
                ->groupBy('category')
                ->orderBy('count', 'desc')
                ->get(),
        ];

        return response()->json($stats);
    }

    /**
     * Display inventory history with all items and search/filter functionality
     */
    public function history(Request $request)
    {
        $query = Inventory::query();

        // Search functionality
        if ($request->filled('search')) {
            $searchTerm = $request->search;
            $query->where(function($q) use ($searchTerm) {
                $q->where('sku', 'like', '%' . $searchTerm . '%')
                  ->orWhere('po_number', 'like', '%' . $searchTerm . '%')
                  ->orWhere('department', 'like', '%' . $searchTerm . '%')
                  ->orWhere('supplier', 'like', '%' . $searchTerm . '%')
                  ->orWhere('item_name', 'like', '%' . $searchTerm . '%');
            });
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Order by latest first
        $inventories = $query->orderBy('updated_at', 'desc')->paginate(50);

        // Get unique statuses for filter
        $statuses = Inventory::distinct()->pluck('status')->filter();

        return view('admin.warehousing.inventory-history', compact('inventories', 'statuses'));
    }
}
