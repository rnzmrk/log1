<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PurchaseOrder extends Model
{
    protected $fillable = [
        'po_number',
        'supplier',
        'supplier_contact',
        'supplier_email',
        'supplier_phone',
        'billing_address',
        'shipping_address',
        'subtotal',
        'tax_amount',
        'shipping_cost',
        'total_amount',
        'status',
        'priority',
        'order_date',
        'expected_delivery_date',
        'actual_delivery_date',
        'notes',
        'created_by',
        'approved_by',
        'supply_request_id',
    ];

    protected $casts = [
        'subtotal' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'shipping_cost' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'order_date' => 'date',
        'expected_delivery_date' => 'date',
        'actual_delivery_date' => 'date',
    ];

    protected static function boot()
    {
        parent::boot();

        static::saving(function ($po) {
            // Auto-calculate total amount
            $po->total_amount = $po->subtotal + $po->tax_amount + $po->shipping_cost;
            
            // Auto-update actual_delivery_date when status changes to 'Received'
            if ($po->status === 'Received' && !$po->actual_delivery_date) {
                $po->actual_delivery_date = now();
            }
        });
    }
}
