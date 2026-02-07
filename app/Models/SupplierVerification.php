<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SupplierVerification extends Model
{
    use HasFactory;

    protected $fillable = [
        'supplier_id',
        'verification_type',
        'verification_date',
        'status',
        'score',
        'findings',
        'recommendations',
        'report_path',
        'verified_by',
        'scheduled_by',
        'scheduled_at',
    ];

    protected $casts = [
        'verification_date' => 'date',
        'score' => 'integer',
        'scheduled_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Relationships
    public function supplier()
    {
        return $this->belongsTo(Supplier::class, 'supplier_id');
    }

    public function verifier()
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    public function scheduler()
    {
        return $this->belongsTo(User::class, 'scheduled_by');
    }

    // Helper methods
    public function getStatusColorAttribute()
    {
        return match($this->status) {
            'passed' => 'green',
            'failed' => 'red',
            'pending' => 'orange',
            'scheduled' => 'blue',
            default => 'gray',
        };
    }

    public function getScoreColorAttribute()
    {
        if (!$this->score) return 'gray';
        if ($this->score >= 80) return 'green';
        if ($this->score >= 60) return 'orange';
        return 'red';
    }

    public function getGradeAttribute()
    {
        if (!$this->score) return 'N/A';
        if ($this->score >= 90) return 'A';
        if ($this->score >= 80) return 'B';
        if ($this->score >= 70) return 'C';
        if ($this->score >= 60) return 'D';
        return 'F';
    }

    public function isScheduled()
    {
        return $this->status === 'scheduled' && $this->scheduled_at && $this->scheduled_at->isFuture();
    }

    public function isOverdue()
    {
        return $this->status === 'scheduled' && $this->scheduled_at && $this->scheduled_at->isPast();
    }

    // Scopes
    public function scopePassed($query)
    {
        return $query->where('status', 'passed');
    }

    public function scopeFailed($query)
    {
        return $query->where('status', 'failed');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeScheduled($query)
    {
        return $query->where('status', 'scheduled');
    }

    public function scopeByType($query, $type)
    {
        return $query->where('verification_type', $type);
    }

    public function scopeWithScore($query, $minScore = null, $maxScore = null)
    {
        if ($minScore !== null) {
            $query->where('score', '>=', $minScore);
        }
        if ($maxScore !== null) {
            $query->where('score', '<=', $maxScore);
        }
        return $query;
    }

    public function scopeUpcoming($query, $days = 7)
    {
        return $query->where('status', 'scheduled')
                    ->where('scheduled_at', '<=', now()->addDays($days))
                    ->where('scheduled_at', '>', now());
    }

    public function scopeOverdue($query)
    {
        return $query->where('status', 'scheduled')
                    ->where('scheduled_at', '<', now());
    }
}
