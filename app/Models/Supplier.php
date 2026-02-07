<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;

class Supplier extends Model
{
    use HasFactory;

    protected $table = 'vendors';

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
        'website',
        'description',
        'logo',
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

    // Relationship to User (who created the supplier)
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // Relationship to Supplier Posts
    public function supplierPosts()
    {
        return $this->hasMany(SupplierPost::class, 'supplier_id');
    }

    // Relationship to Supplier Validations
    public function validations()
    {
        return $this->hasMany(SupplierValidation::class, 'supplier_id');
    }

    // Relationship to Supplier Verifications
    public function verifications()
    {
        return $this->hasMany(SupplierVerification::class, 'supplier_id');
    }

    // Relationship to Procurement Requirements
    public function procurementRequirements()
    {
        return $this->hasMany(ProcurementRequirement::class, 'assigned_supplier_id');
    }

    // Public posts relationship
    public function publicPosts()
    {
        return $this->supplierPosts()->published();
    }

    // For backward compatibility with existing views
    public function products()
    {
        return $this->supplierPosts();
    }

    // Supplier-specific scopes
    public function scopeActive($query)
    {
        return $query->where('status', 'Active');
    }
    
    public function scopeFeatured($query)
    {
        return $query->active()->where('rating', '>=', 4.0);
    }
    
    public function scopeByCategory($query, $category)
    {
        return $query->where('category', $category);
    }

    // Scope for filtering
    public function scopeFilter($query, array $filters)
    {
        // Search functionality
        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('supplier_code', 'like', "%{$search}%")
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
    
    // Helper methods for supplier display
    public function getDisplayNameAttribute()
    {
        return $this->name;
    }
    
    public function getFullAddressAttribute()
    {
        $address = $this->address;
        if ($this->city) $address .= ', ' . $this->city;
        if ($this->state) $address .= ', ' . $this->state;
        if ($this->postal_code) $address .= ' ' . $this->postal_code;
        if ($this->country) $address .= ', ' . $this->country;
        return $address;
    }
    
    public function getWebsiteUrlAttribute()
    {
        if (!$this->website) return null;
        return strpos($this->website, 'http') === 0 ? $this->website : 'https://' . $this->website;
    }
    
    public function getLogoUrlAttribute()
    {
        if (!$this->logo) return null;
        return asset($this->logo);
    }
    
    public function getSlugAttribute()
    {
        return Str::slug($this->name) . '-' . $this->id;
    }
    
    // Check if supplier has complete profile
    public function hasCompleteProfile()
    {
        return !empty($this->name) && 
               !empty($this->contact_person) && 
               !empty($this->email) && 
               !empty($this->phone) && 
               !empty($this->category);
    }

    // Check if supplier has valid validations
    public function hasValidValidations()
    {
        return $this->validations()->valid()->count() > 0;
    }

    // Check if supplier has expired validations
    public function hasExpiredValidations()
    {
        return $this->validations()->expired()->count() > 0;
    }

    // Check if supplier has validations expiring soon
    public function hasExpiringValidations($days = 30)
    {
        return $this->validations()->expiringSoon($days)->count() > 0;
    }

    // Get validation status summary
    public function getValidationStatusAttribute()
    {
        $validCount = $this->validations()->valid()->count();
        $expiredCount = $this->validations()->expired()->count();
        $pendingCount = $this->validations()->where('status', 'pending')->count();

        if ($expiredCount > 0) return 'expired';
        if ($pendingCount > 0) return 'pending';
        if ($validCount > 0) return 'valid';
        return 'none';
    }

    // Get verification status summary
    public function getVerificationStatusAttribute()
    {
        $passedCount = $this->verifications()->passed()->count();
        $failedCount = $this->verifications()->failed()->count();
        $pendingCount = $this->verifications()->pending()->count();
        $scheduledCount = $this->verifications()->scheduled()->count();

        if ($failedCount > 0) return 'failed';
        if ($pendingCount > 0) return 'pending';
        if ($scheduledCount > 0) return 'scheduled';
        if ($passedCount > 0) return 'passed';
        return 'none';
    }

    // Get overall compliance status
    public function getComplianceStatusAttribute()
    {
        $validationStatus = $this->validation_status;
        $verificationStatus = $this->verification_status;

        if ($validationStatus === 'expired' || $verificationStatus === 'failed') return 'non-compliant';
        if ($validationStatus === 'pending' || $verificationStatus === 'pending' || $verificationStatus === 'scheduled') return 'pending';
        if ($validationStatus === 'valid' && $verificationStatus === 'passed') return 'compliant';
        return 'unknown';
    }
    
    // Get supplier's rating display
    public function getRatingStarsAttribute()
    {
        $rating = $this->rating ?? 5.0;
        $fullStars = floor($rating);
        $hasHalfStar = ($rating - $fullStars) >= 0.5;
        $emptyStars = 5 - $fullStars - ($hasHalfStar ? 1 : 0);
        
        return [
            'full' => $fullStars,
            'half' => $hasHalfStar,
            'empty' => $emptyStars,
            'rating' => $rating
        ];
    }

    // Boot method to auto-generate vendor code
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($supplier) {
            if (!$supplier->vendor_code) {
                $supplier->vendor_code = 'V-' . str_pad(Supplier::count() + 1, 4, '0', STR_PAD_LEFT);
            }
            if (!$supplier->status) {
                $supplier->status = 'Pending';
            }
        });
    }
}
