<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AssetMaintenance extends Model
{
    protected $fillable = [
        'maintenance_number',
        'asset_id',
        'asset_tag',
        'asset_name',
        'maintenance_type',
        'status',
        'priority',
        'scheduled_date',
        'start_time',
        'end_time',
        'problem_description',
        'work_performed',
        'notes',
        'parts_cost',
        'labor_cost',
        'total_cost',
        'technician_name',
        'technician_email',
        'technician_phone',
        'next_maintenance_notes',
        'next_maintenance_date',
        'performed_by',
        'approved_by',
    ];

    protected $casts = [
        'scheduled_date' => 'date',
        'start_time' => 'datetime',
        'end_time' => 'datetime',
        'parts_cost' => 'decimal:2',
        'labor_cost' => 'decimal:2',
        'total_cost' => 'decimal:2',
        'next_maintenance_date' => 'date',
    ];

    protected static function boot()
    {
        parent::boot();

        static::saving(function ($maintenance) {
            // Auto-calculate total cost
            $maintenance->total_cost = $maintenance->parts_cost + $maintenance->labor_cost;
            
            // Auto-set end time when status changes to 'Completed'
            if ($maintenance->status === 'Completed' && !$maintenance->end_time) {
                $maintenance->end_time = now();
            }
            
            // Auto-set start time when status changes to 'In Progress'
            if ($maintenance->status === 'In Progress' && !$maintenance->start_time) {
                $maintenance->start_time = now();
            }
        });

        static::updated(function ($maintenance) {
            // Update asset's last maintenance date when maintenance is completed
            if ($maintenance->status === 'Completed' && $maintenance->asset) {
                $maintenance->asset->update([
                    'last_maintenance_date' => $maintenance->end_time,
                    'next_maintenance_date' => $maintenance->next_maintenance_date,
                ]);
            }
        });
    }

    public function asset()
    {
        return $this->belongsTo(Asset::class);
    }
}
