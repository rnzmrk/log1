<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OutboundLogistic extends Model
{
    protected $fillable = [
        'shipment_id',
        'sku',
        'po_number',
        'department',
        'supplier',
        'item_name',
        'total_units',
        'shipped_units',
        'destination',
        'customer_name',
        'address',
        'contact',
        'priority',
        'status',
        'shipping_date',
        'delivery_date',
        'notes',
        'total_value',
        'carrier',
        'tracking_number',
    ];

    protected $casts = [
        'total_units' => 'integer',
        'shipped_units' => 'integer',
        'total_value' => 'decimal:2',
        'shipping_date' => 'date',
        'delivery_date' => 'date',
    ];

    protected static function boot()
    {
        parent::boot();

        static::saving(function ($outbound) {
            // Auto-update shipped_units if not set
            if ($outbound->shipped_units === null && $outbound->status === 'Shipped') {
                $outbound->shipped_units = $outbound->total_units;
            }

            // Auto-update dates based on status
            if ($outbound->status === 'Shipped' && !$outbound->shipping_date) {
                $outbound->shipping_date = now();
            }
            
            if ($outbound->status === 'Delivered' && !$outbound->delivery_date) {
                $outbound->delivery_date = now();
            }
        });
    }
}
