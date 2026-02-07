<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Vendor extends Model
{
    use HasFactory;

    protected $fillable = [
        'vendor_code',
        'name',
        'contact_person',
        'email',
        'phone',
        'address',
        'city',
        'state',
        'postal_code',
        'country',
        'category',
        'services',
        'tax_id',
        'payment_terms',
        'bank_name',
        'bank_account',
        'status',
        'rating',
        'notes',
        'created_by',
    ];

    protected $casts = [
        'services' => 'array',
        'rating' => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Helper methods for status colors
    public function getStatusColorAttribute()
    {
        return match($this->status) {
            'Active' => 'green',
            'Inactive' => 'gray',
            'Pending' => 'orange',
            'Suspended' => 'red',
            'Under Review' => 'blue',
            default => 'gray',
        };
    }

    public function getRatingColorAttribute()
    {
        if ($this->rating >= 4.5) return 'green';
        if ($this->rating >= 3.5) return 'blue';
        if ($this->rating >= 2.5) return 'orange';
        return 'red';
    }

    public function getInitialsAttribute()
    {
        $words = explode(' ', $this->name);
        return strtoupper(substr($words[0] ?? '', 0, 1) . substr($words[1] ?? '', 0, 1));
    }

    public function getAvatarColorAttribute()
    {
        $hash = md5($this->name);
        $colors = ['blue', 'green', 'purple', 'orange', 'red', 'indigo', 'pink', 'yellow'];
        return $colors[hexdec(substr($hash, 0, 2)) % count($colors)];
    }

    // Relationship to User (who created the vendor)
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // Scope for filtering
    public function scopeFilter($query, array $filters)
    {
        // Search functionality
        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('vendor_code', 'like', "%{$search}%")
                  ->orWhere('contact_person', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        // Status filter
        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        // Category filter
        if (!empty($filters['category'])) {
            $query->where('category', $filters['category']);
        }

        // Rating filter
        if (!empty($filters['rating'])) {
            $rating = (float) $filters['rating'];
            if ($rating === 5) {
                $query->where('rating', '>=', 4.5);
            } elseif ($rating === 4) {
                $query->where('rating', '>=', 3.5)->where('rating', '<', 4.5);
            } elseif ($rating === 3) {
                $query->where('rating', '>=', 2.5)->where('rating', '<', 3.5);
            } elseif ($rating === 2) {
                $query->where('rating', '<', 2.5);
            }
        }
    }

    // Boot method to auto-generate vendor code
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($vendor) {
            if (!$vendor->vendor_code) {
                $vendor->vendor_code = 'V-' . str_pad(Vendor::count() + 1, 4, '0', STR_PAD_LEFT);
            }
            if (!$vendor->status) {
                $vendor->status = 'Pending';
            }
        });
    }
}
