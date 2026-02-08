<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Inventory extends Model
{
    protected $fillable = [
        'sku',
        'item_name',
        'category',
        'location',
        'stock',
        'status',
        'description',
        'price',
        'supplier',
        'last_updated',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'stock' => 'integer',
        'last_updated' => 'date',
    ];

    protected static function boot()
    {
        parent::boot();

        static::saving(function ($inventory) {
            // Auto-update status based on stock level
            if ($inventory->stock == 0) {
                $inventory->status = 'Out of Stock';
            } elseif ($inventory->stock <= 10) {
                $inventory->status = 'Low Stock';
            } else {
                $inventory->status = 'In Stock';
            }

            // Auto-update last_updated date
            $inventory->last_updated = now();
        });

        static::updated(function ($inventory) {
            // Check if stock became low and create procurement request if needed
            if ($inventory->wasChanged('stock') && $inventory->stock <= 10 && $inventory->stock > 0) {
                $inventory->createProcurementRequestIfNeeded();
            }
        });
    }

    /**
     * Get items that are low stock
     */
    public static function getLowStockItems()
    {
        return self::where('stock', '<=', 10)
            ->where('stock', '>', 0)
            ->orderBy('stock', 'asc')
            ->get();
    }

    /**
     * Get items that are out of stock
     */
    public static function getOutOfStockItems()
    {
        return self::where('stock', '=', 0)
            ->orderBy('last_updated', 'desc')
            ->get();
    }

    /**
     * Create procurement request if needed for low stock items
     */
    public function createProcurementRequestIfNeeded()
    {
        // Check if there's already a pending procurement request for this item
        $existingRequest = SupplyRequest::where('item_name', $this->item_name)
            ->whereIn('status', ['Pending', 'Approved', 'Ordered'])
            ->first();

        if (!$existingRequest) {
            // Calculate suggested order quantity (typically 2x current stock or minimum 20)
            $suggestedQuantity = max($this->stock * 2, 20);

            SupplyRequest::create([
                'item_name' => $this->item_name,
                'category' => $this->category,
                'supplier' => $this->supplier,
                'quantity_requested' => $suggestedQuantity,
                'quantity_approved' => $suggestedQuantity,
                'unit_price' => $this->price,
                'priority' => $this->stock <= 5 ? 'High' : 'Medium',
                'status' => 'Pending',
                'request_date' => now(),
                'needed_by_date' => now()->addDays(7),
                'notes' => "Auto-generated request due to low stock (Current: {$this->stock})",
                'requested_by' => auth()->user()->name ?? 'System',
            ]);

            return true;
        }

        return false;
    }

    /**
     * Update stock quantity and create audit trail
     */
    public function updateStock($quantity, $type = 'manual', $reference = null)
    {
        $oldStock = $this->stock;
        $this->stock = $quantity;
        $this->save();

        // Create audit log
        AuditLog::create([
            'action' => 'stock_update',
            'model_type' => 'Inventory',
            'model_id' => $this->id,
            'old_values' => json_encode(['stock' => $oldStock]),
            'new_values' => json_encode(['stock' => $quantity]),
            'user_id' => auth()->id(),
            'notes' => "Stock updated via {$type}" . ($reference ? " (Ref: {$reference})" : ''),
        ]);

        return $this;
    }

    /**
     * Add stock (for inbound deliveries)
     */
    public function addStock($quantity, $reference = null)
    {
        return $this->updateStock($this->stock + $quantity, 'inbound', $reference);
    }

    /**
     * Remove stock (for outbound deliveries)
     */
    public function removeStock($quantity, $reference = null)
    {
        if ($this->stock >= $quantity) {
            return $this->updateStock($this->stock - $quantity, 'outbound', $reference);
        }
        
        throw new \Exception("Insufficient stock. Current: {$this->stock}, Requested: {$quantity}");
    }
}
