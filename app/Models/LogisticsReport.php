<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LogisticsReport extends Model
{
    protected $fillable = [
        'report_number',
        'report_name',
        'report_type',
        'status',
        'priority',
        'generated_by',
        'department',
        'report_date',
        'start_date',
        'end_date',
        'description',
        'data_summary',
        'file_path',
        'total_records',
        'success_rate',
        'notes',
    ];

    protected $casts = [
        'report_date' => 'date',
        'start_date' => 'date',
        'end_date' => 'date',
        'data_summary' => 'array',
        'success_rate' => 'decimal:2',
        'total_records' => 'integer',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($report) {
            // Auto-generate report number
            if (!$report->report_number) {
                $report->report_number = 'RPT-' . date('Y') . '-' . str_pad(LogisticsReport::count() + 1, 4, '0', STR_PAD_LEFT);
            }
        });
    }

    public function getStatusColorAttribute()
    {
        return match($this->status) {
            'Completed' => 'green',
            'Processing' => 'blue',
            'Scheduled' => 'orange',
            'Failed' => 'red',
            'Cancelled' => 'gray',
            default => 'gray',
        };
    }

    public function getTypeColorAttribute()
    {
        return match($this->report_type) {
            'Delivery' => 'blue',
            'Vehicle' => 'green',
            'Project' => 'orange',
            'Performance' => 'purple',
            'Financial' => 'red',
            'Inventory' => 'yellow',
            'Maintenance' => 'indigo',
            default => 'gray',
        };
    }

    public function getPriorityColorAttribute()
    {
        return match($this->priority) {
            'Urgent' => 'red',
            'High' => 'orange',
            'Medium' => 'yellow',
            'Low' => 'green',
            default => 'gray',
        };
    }
}
