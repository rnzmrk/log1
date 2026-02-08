<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SupplyRequest extends Model
{
    protected $fillable = [
        'request_id',
        'item_name',
        'category',
        'supplier',
        'quantity_requested',
        'quantity_approved',
        'unit_price',
        'total_cost',
        'priority',
        'status',
        'request_date',
        'needed_by_date',
        'approval_date',
        'order_date',
        'expected_delivery',
        'notes',
        'requested_by',
        'approved_by',
    ];

    protected $casts = [
        'quantity_requested' => 'integer',
        'quantity_approved' => 'integer',
        'unit_price' => 'decimal:2',
        'total_cost' => 'decimal:2',
        'request_date' => 'date',
        'needed_by_date' => 'date',
        'approval_date' => 'date',
        'order_date' => 'date',
        'expected_delivery' => 'date',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($request) {
            // Auto-generate request ID if not provided
            if (!$request->request_id) {
                $request->request_id = self::generateRequestID();
            }
        });

        static::saving(function ($request) {
            // Auto-calculate total cost when unit price and quantity are set
            if ($request->unit_price && $request->quantity_approved) {
                $request->total_cost = $request->unit_price * $request->quantity_approved;
            }
            
            // Auto-update approval_date when status changes to 'Approved'
            if ($request->status === 'Approved' && !$request->approval_date) {
                $request->approval_date = now();
            }
            
            // Auto-update order_date when status changes to 'Ordered'
            if ($request->status === 'Ordered' && !$request->order_date) {
                $request->order_date = now();
            }
        });
    }

    /**
     * Generate automatic request ID
     */
    public static function generateRequestID()
    {
        $prefix = 'SR';
        $year = date('Y');
        $month = date('m');
        
        // Get the last request ID for this month
        $lastRequest = self::where('request_id', 'like', "{$prefix}{$year}{$month}%")
            ->orderBy('request_id', 'desc')
            ->first();
        
        if ($lastRequest) {
            // Extract the sequence number from the last request
            $lastSequence = intval(substr($lastRequest->request_id, -4));
            $newSequence = $lastSequence + 1;
        } else {
            $newSequence = 1;
        }
        
        // Format: SR2026020001 (SR + Year + Month + 4-digit sequence)
        return $prefix . $year . $month . str_pad($newSequence, 4, '0', STR_PAD_LEFT);
    }
}
