<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Asset extends Model
{
    protected $fillable = [
        'item_number',
        'image',
        'asset_type',
        'item_code',
        'item_name',
        'status',
        'date',
        'category',
        'brand',
        'model',
        'serial_number',
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
        'disposal_reason',
        'disposal_date',
        'disposed_by',
    ];

    protected $casts = [
        'purchase_cost' => 'decimal:2',
        'purchase_date' => 'date',
        'warranty_expiry' => 'date',
        'last_maintenance_date' => 'date',
        'next_maintenance_date' => 'date',
        'date' => 'date',
        'disposal_date' => 'date',
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

            // Set disposal info when status changes to 'Disposed'
            if ($asset->status === 'Disposed' && !$asset->disposal_date) {
                $asset->disposal_date = now();
                $asset->disposed_by = auth()->user()->name ?? 'System';
            }
        });
    }

    /**
     * Get assets that need disposal
     */
    public static function getAssetsForDisposal()
    {
        return self::where('status', '!=', 'Disposed')
            ->where(function($query) {
                $query->where('condition', 'Poor')
                    ->orWhere('condition', 'Damaged')
                    ->orWhereRaw('DATEDIFF(warranty_expiry, CURDATE()) < 0'); // Expired warranty
            })
            ->get();
    }

    /**
     * Check if asset can be disposed
     */
    public function canBeDisposed()
    {
        return $this->status !== 'Disposed' && 
               in_array($this->condition, ['Poor', 'Damaged']) ||
               $this->warranty_expiry && $this->warranty_expiry->isPast();
    }
}
