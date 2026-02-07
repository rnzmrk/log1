<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SupplierValidation extends Model
{
    use HasFactory;

    protected $fillable = [
        'supplier_id',
        'validation_type',
        'document_number',
        'issue_date',
        'expiry_date',
        'status',
        'notes',
        'document_path',
        'validated_by',
        'validated_at',
    ];

    protected $casts = [
        'issue_date' => 'date',
        'expiry_date' => 'date',
        'validated_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Relationships
    public function supplier()
    {
        return $this->belongsTo(Supplier::class, 'supplier_id');
    }

    public function validator()
    {
        return $this->belongsTo(User::class, 'validated_by');
    }

    // Helper methods
    public function getStatusColorAttribute()
    {
        return match($this->status) {
            'valid' => 'green',
            'expired' => 'red',
            'pending' => 'orange',
            'rejected' => 'red',
            default => 'gray',
        };
    }

    public function isExpired()
    {
        return $this->expiry_date && $this->expiry_date->isPast();
    }

    public function isExpiringSoon($days = 30)
    {
        return $this->expiry_date && $this->expiry_date->diffInDays(now()) <= $days;
    }

    public function getDaysUntilExpiryAttribute()
    {
        if (!$this->expiry_date) return null;
        return $this->expiry_date->diffInDays(now(), false);
    }

    // Scopes
    public function scopeValid($query)
    {
        return $query->where('status', 'valid')
                    ->where(function($q) {
                        $q->whereNull('expiry_date')
                          ->orWhere('expiry_date', '>', now());
                    });
    }

    public function scopeExpired($query)
    {
        return $query->where('status', 'expired')
                    ->orWhere(function($q) {
                        $q->whereNotNull('expiry_date')
                          ->where('expiry_date', '<=', now());
                    });
    }

    public function scopeExpiringSoon($query, $days = 30)
    {
        return $query->whereNotNull('expiry_date')
                    ->where('expiry_date', '<=', now()->addDays($days))
                    ->where('expiry_date', '>', now());
    }

    public function scopeByType($query, $type)
    {
        return $query->where('validation_type', $type);
    }
}
