<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AuditLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'user_name',
        'user_email',
        'action',
        'module',
        'ip_address',
        'status',
        'details',
        'user_agent',
        'created_at',
    ];

    protected $casts = [
        'details' => 'array',
        'created_at' => 'datetime',
    ];

    public $timestamps = false;

    // Helper methods for status colors
    public function getStatusColorAttribute()
    {
        return match($this->status) {
            'Success' => 'green',
            'Failed' => 'red',
            'Warning' => 'orange',
            'Error' => 'red',
            default => 'gray',
        };
    }

    public function getModuleColorAttribute()
    {
        return match($this->module) {
            'Authentication' => 'gray',
            'Document Tracking' => 'blue',
            'Logistic Tracking' => 'purple',
            'Warehousing' => 'green',
            'Procurement' => 'orange',
            'Asset Lifecycle' => 'red',
            'Admin Settings' => 'purple',
            default => 'gray',
        };
    }

    public function getInitialsAttribute()
    {
        if ($this->user_name) {
            $words = explode(' ', $this->user_name);
            return strtoupper(substr($words[0] ?? '', 0, 1) . substr($words[1] ?? '', 0, 1));
        }
        return '?';
    }

    public function getAvatarColorAttribute()
    {
        if (!$this->user_name || $this->user_name === 'Unknown User') {
            return 'red';
        }
        
        $hash = md5($this->user_name);
        $colors = ['blue', 'green', 'purple', 'orange', 'red', 'indigo', 'pink', 'yellow'];
        return $colors[hexdec(substr($hash, 0, 2)) % count($colors)];
    }

    // Relationship to User (if available)
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Scope for filtering
    public function scopeFilter($query, array $filters)
    {
        // Search functionality
        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function($q) use ($search) {
                $q->where('user_name', 'like', "%{$search}%")
                  ->orWhere('user_email', 'like', "%{$search}%")
                  ->orWhere('action', 'like', "%{$search}%")
                  ->orWhere('ip_address', 'like', "%{$search}%");
            });
        }

        // Action type filter
        if (!empty($filters['action'])) {
            $query->where('action', $filters['action']);
        }

        // Module filter
        if (!empty($filters['module'])) {
            $query->where('module', $filters['module']);
        }

        // User filter
        if (!empty($filters['user'])) {
            $query->where('user_email', $filters['user']);
        }

        // Status filter
        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        // Date range filter
        if (!empty($filters['date_range'])) {
            $now = now();
            switch ($filters['date_range']) {
                case '24':
                    $query->where('created_at', '>=', $now->subHours(24));
                    break;
                case '7':
                    $query->where('created_at', '>=', $now->subDays(7));
                    break;
                case '30':
                    $query->where('created_at', '>=', $now->subDays(30));
                    break;
                case '90':
                    $query->where('created_at', '>=', $now->subDays(90));
                    break;
            }
        }
    }
}
