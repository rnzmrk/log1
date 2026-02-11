<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReturnRefund extends Model
{
    protected $fillable = [
        'return_id',
        'order_number',
        'customer_name',
        'product_name',
        'sku',
        'quantity',
        'refund_amount',
        'return_reason',
        'status',
        'return_date',
        'refund_date',
        'notes',
        'refund_method',
        'tracking_number',
        'po_number',
        'department',
        'item_name',
        'stock',
        'supplier',
    ];

    protected $casts = [
        'quantity' => 'integer',
        'refund_amount' => 'decimal:2',
        'return_date' => 'date',
        'refund_date' => 'date',
    ];

    protected static function boot()
    {
        parent::boot();

        static::saving(function ($return) {
            // Auto-update refund_date when status changes to 'Refunded'
            if ($return->status === 'Refunded' && !$return->refund_date) {
                $return->refund_date = now();
            }
        });
    }
}
