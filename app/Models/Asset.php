<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Asset extends Model
{
    protected $fillable = [
        'asset_tag',
        'asset_name',
        'asset_type',
        'category',
        'brand',
        'model',
        'serial_number',
        'status',
        'condition',
        'purchase_cost',
        'purchase_date',
        'warranty_expiry',
        'last_maintenance_date',
        'next_maintenance_date',
        'assigned_to',
        'department',
        'location',
        'notes',
        'specifications',
        'created_by',
    ];

    protected $casts = [
        'purchase_cost' => 'decimal:2',
        'purchase_date' => 'date',
        'warranty_expiry' => 'date',
        'last_maintenance_date' => 'date',
        'next_maintenance_date' => 'date',
    ];

    protected static function boot()
    {
        parent::boot();

        static::saving(function ($asset) {
            // Auto-update last maintenance date when status changes to 'Under Maintenance'
            if ($asset->status === 'Under Maintenance' && !$asset->last_maintenance_date) {
                $asset->last_maintenance_date = now();
            }
            
            // Auto-update next maintenance date when maintenance is completed
            if ($asset->status === 'Available' && $asset->last_maintenance_date) {
                $asset->next_maintenance_date = now()->addMonths(6); // Next maintenance in 6 months
            }
        });
    }
}
