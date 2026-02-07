<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DocumentReport extends Model
{
    protected $fillable = [
        'report_number',
        'report_name',
        'report_type',
        'status',
        'generated_by',
        'department',
        'report_date',
        'start_date',
        'end_date',
        'description',
        'parameters',
        'file_path',
        'file_name',
        'file_size',
        'summary',
        'data',
    ];

    protected $casts = [
        'report_date' => 'date',
        'start_date' => 'date',
        'end_date' => 'date',
        'data' => 'array',
        'file_size' => 'integer',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($report) {
            // Auto-generate report number
            if (!$report->report_number) {
                $report->report_number = 'RPT-DOC-' . str_pad(DocumentReport::count() + 1, 3, '0', STR_PAD_LEFT);
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
            'Summary' => 'blue',
            'Storage' => 'purple',
            'Access' => 'orange',
            'Compliance' => 'red',
            'Upload' => 'green',
            'Document Requests' => 'indigo',
            default => 'gray',
        };
    }

    public function getFileSizeInMBAttribute()
    {
        return $this->file_size ? number_format($this->file_size / 1024 / 1024, 2) . ' MB' : 'N/A';
    }
}
