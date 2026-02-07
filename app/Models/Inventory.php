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
    }
}
