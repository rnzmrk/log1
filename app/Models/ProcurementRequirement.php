<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProcurementRequirement extends Model
{
    use HasFactory;

    protected $fillable = [
        'requirement_number',
        'title',
        'description',
        'category',
        'priority',
        'type',
        'estimated_budget',
        'currency',
        'required_date',
        'department',
        'requested_by',
        'approved_by',
        'approved_at',
        'status',
        'rejection_reason',
        'specifications',
        'delivery_terms',
        'payment_terms',
        'assigned_supplier_id',
    ];

    protected $casts = [
        'estimated_budget' => 'decimal:2',
        'required_date' => 'date',
        'approved_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Relationships
    public function requester()
    {
        return $this->belongsTo(User::class, 'requested_by');
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function assignedSupplier()
    {
        return $this->belongsTo(Supplier::class, 'assigned_supplier_id');
    }

    // Helper methods
    public function getStatusColorAttribute()
    {
        return match($this->status) {
            'draft' => 'gray',
            'submitted' => 'blue',
            'approved' => 'green',
            'rejected' => 'red',
            'procured' => 'purple',
            'cancelled' => 'red',
            default => 'gray',
        };
    }

    public function getPriorityColorAttribute()
    {
        return match($this->priority) {
            'low' => 'gray',
            'medium' => 'blue',
            'high' => 'orange',
            'critical' => 'red',
            default => 'gray',
        };
    }

    public function getTypeColorAttribute()
    {
        return match($this->type) {
            'goods' => 'blue',
            'services' => 'green',
            'works' => 'orange',
            'consultancy' => 'purple',
            default => 'gray',
        };
    }

    public function isUrgent()
    {
        return $this->required_date && $this->required_date->diffInDays(now()) <= 7;
    }

    public function isOverdue()
    {
        return $this->required_date && $this->required_date->isPast() && !in_array($this->status, ['procured', 'cancelled']);
    }

    public function getDaysUntilRequiredAttribute()
    {
        if (!$this->required_date) return null;
        return $this->required_date->diffInDays(now(), false);
    }

    public function getFormattedBudgetAttribute()
    {
        if (!$this->estimated_budget) return 'N/A';
        return number_format($this->estimated_budget, 2) . ' ' . $this->currency;
    }

    // Scopes
    public function scopeDraft($query)
    {
        return $query->where('status', 'draft');
    }

    public function scopeSubmitted($query)
    {
        return $query->where('status', 'submitted');
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    public function scopeRejected($query)
    {
        return $query->where('status', 'rejected');
    }

    public function scopeProcured($query)
    {
        return $query->where('status', 'procured');
    }

    public function scopeByDepartment($query, $department)
    {
        return $query->where('department', $department);
    }

    public function scopeByCategory($query, $category)
    {
        return $query->where('category', $category);
    }

    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
    }

    public function scopeByPriority($query, $priority)
    {
        return $query->where('priority', $priority);
    }

    public function scopeUrgent($query)
    {
        return $query->where('required_date', '<=', now()->addDays(7))
                    ->whereNotIn('status', ['procured', 'cancelled']);
    }

    public function scopeOverdue($query)
    {
        return $query->where('required_date', '<', now())
                    ->whereNotIn('status', ['procured', 'cancelled']);
    }

    public function scopePendingApproval($query)
    {
        return $query->where('status', 'submitted');
    }

    // Boot method to auto-generate requirement number
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($requirement) {
            if (!$requirement->requirement_number) {
                $requirement->requirement_number = 'REQ-' . date('Y') . '-' . str_pad(ProcurementRequirement::count() + 1, 4, '0', STR_PAD_LEFT);
            }
            if (!$requirement->status) {
                $requirement->status = 'draft';
            }
        });
    }
}
