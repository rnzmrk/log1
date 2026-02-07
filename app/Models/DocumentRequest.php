<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DocumentRequest extends Model
{
    protected $fillable = [
        'request_number',
        'request_title',
        'description',
        'priority',
        'urgency',
        'document_type',
        'document_category',
        'format_required',
        'number_of_copies',
        'date_range',
        'reference_number',
        'notarization_required',
        'apostille_needed',
        'translation_required',
        'certified_true_copy',
        'delivery_method',
        'delivery_address',
        'contact_person',
        'contact_number',
        'purpose',
        'project_name',
        'cost_center',
        'status',
        'requested_by',
        'department',
        'request_date',
        'notes',
    ];

    protected $casts = [
        'notarization_required' => 'boolean',
        'apostille_needed' => 'boolean',
        'translation_required' => 'boolean',
        'certified_true_copy' => 'boolean',
        'number_of_copies' => 'integer',
        'request_date' => 'date',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($request) {
            // Auto-generate request number
            if (!$request->request_number) {
                $request->request_number = 'DOC-REQ-' . date('Y') . '-' . str_pad(DocumentRequest::count() + 1, 4, '0', STR_PAD_LEFT);
            }
            
            // Set default request date
            if (!$request->request_date) {
                $request->request_date = now();
            }
        });
    }

    public function getStatusColorAttribute()
    {
        return match($this->status) {
            'Draft' => 'gray',
            'Submitted' => 'blue',
            'Under Review' => 'orange',
            'Approved' => 'green',
            'Processing' => 'purple',
            'Completed' => 'green',
            'Rejected' => 'red',
            'Cancelled' => 'gray',
            default => 'gray',
        };
    }

    public function getPriorityColorAttribute()
    {
        return match($this->priority) {
            'Critical' => 'red',
            'High' => 'orange',
            'Medium' => 'yellow',
            'Low' => 'green',
            default => 'gray',
        };
    }

    public function getUrgencyColorAttribute()
    {
        return match($this->urgency) {
            'Critical (Same day)' => 'red',
            'Urgent (1-2 days)' => 'orange',
            'Normal (3-5 days)' => 'green',
            default => 'gray',
        };
    }
}
