<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AssetRequest extends Model
{
    protected $fillable = [
        'request_number',
        'asset_name',
        'asset_type',
        'category',
        'brand',
        'model',
        'serial_number',
        'priority',
        'status',
        'request_type',
        'estimated_cost',
        'request_date',
        'required_date',
        'approved_date',
        'completed_date',
        'justification',
        'specifications',
        'notes',
        'requested_by',
        'department',
        'approved_by',
    ];

    protected $casts = [
        'estimated_cost' => 'decimal:2',
        'request_date' => 'date',
        'required_date' => 'date',
        'approved_date' => 'date',
        'completed_date' => 'date',
    ];

    protected static function boot()
    {
        parent::boot();

        static::saving(function ($assetRequest) {
            // Auto-update approved date when status changes to 'Approved'
            if ($assetRequest->status === 'Approved' && !$assetRequest->approved_date) {
                $assetRequest->approved_date = now();
            }
            
            // Auto-update completed date when status changes to 'Completed'
            if ($assetRequest->status === 'Completed' && !$assetRequest->completed_date) {
                $assetRequest->completed_date = now();
            }
        });
    }
}
