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
        'item_name',
        'item_category',
        'item_quantity',
        'unit_price',
        'total_cost',
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

        static::creating(function ($po) {
            // Auto-generate PO number if not provided
            if (!$po->po_number) {
                $po->po_number = self::generatePONumber();
            }
        });

        static::saving(function ($po) {
            // Auto-calculate total amount
            $po->total_amount = $po->subtotal + $po->tax_amount + $po->shipping_cost;
            
            // Auto-update actual_delivery_date when status changes to 'Received'
            if ($po->status === 'Received' && !$po->actual_delivery_date) {
                $po->actual_delivery_date = now();
            }
        });
    }

    /**
     * Generate automatic PO number
     */
    public static function generatePONumber()
    {
        $prefix = 'PO';
        $year = date('Y');
        $month = date('m');
        
        // Get the last PO number for this month
        $lastPO = self::where('po_number', 'like', "{$prefix}{$year}{$month}%")
            ->orderBy('po_number', 'desc')
            ->first();
        
        if ($lastPO) {
            // Extract the sequence number from the last PO
            $lastSequence = intval(substr($lastPO->po_number, -4));
            $newSequence = $lastSequence + 1;
        } else {
            $newSequence = 1;
        }
        
        // Format: PO2026020001 (PO + Year + Month + 4-digit sequence)
        return $prefix . $year . $month . str_pad($newSequence, 4, '0', STR_PAD_LEFT);
    }

    /**
     * Generate PO number from supply request if connected
     */
    public static function generateFromSupplyRequest($supplyRequestId)
    {
        $prefix = 'PO';
        $year = date('Y');
        $month = date('m');
        $requestSuffix = 'SR' . str_pad($supplyRequestId, 3, '0', STR_PAD_LEFT);
        
        // Get the last PO number for this month
        $lastPO = self::where('po_number', 'like', "{$prefix}{$year}{$month}%")
            ->orderBy('po_number', 'desc')
            ->first();
        
        if ($lastPO) {
            $lastSequence = intval(substr($lastPO->po_number, -4));
            $newSequence = $lastSequence + 1;
        } else {
            $newSequence = 1;
        }
        
        // Format: PO2026020001-SR001 (connected to supply request)
        return $prefix . $year . $month . str_pad($newSequence, 4, '0', STR_PAD_LEFT) . '-' . $requestSuffix;
    }
}
