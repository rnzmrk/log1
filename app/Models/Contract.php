<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Contract extends Model
{
    protected $fillable = [
        'contract_number',
        'contract_name',
        'vendor',
        'vendor_contact',
        'vendor_email',
        'vendor_phone',
        'supplier_id',
        'contract_type',
        'contract_value',
        'status',
        'priority',
        'start_date',
        'end_date',
        'renewal_date',
        'renewal_count',
        'auto_renewal',
        'renewal_terms',
        'description',
        'terms_conditions',
        'notes',
        'created_by',
        'approved_by',
        'renewed_by',
        'terminated_by',
        'termination_reason',
    ];

    protected $casts = [
        'contract_value' => 'decimal:2',
        'start_date' => 'date',
        'end_date' => 'date',
        'renewal_date' => 'date',
        'auto_renewal' => 'boolean',
    ];

    /**
     * Get the supplier associated with the contract.
     */
    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    /**
     * Get contracts that are expiring soon (within 30 days)
     */
    public static function getExpiringSoon($days = 30)
    {
        return self::where('status', 'Active')
            ->where('end_date', '<=', now()->addDays($days))
            ->where('end_date', '>', now())
            ->orderBy('end_date', 'asc');
    }

    /**
     * Get contracts that need renewal (past end date but not renewed)
     */
    public static function getNeedingRenewal()
    {
        return self::where('status', 'Active')
            ->where('end_date', '<', now())
            ->orderBy('end_date', 'asc');
    }

    /**
     * Get expired contracts
     */
    public static function getExpired()
    {
        return self::where('status', 'Expired')
            ->orWhere(function($query) {
                $query->where('status', 'Active')
                    ->where('end_date', '<', now());
            })
            ->orderBy('end_date', 'desc');
    }

    /**
     * Check if contract is expiring soon
     */
    public function isExpiringSoon($days = 30)
    {
        return $this->status === 'Active' && 
               $this->end_date->lessThanOrEqualTo(now()->addDays($days)) &&
               $this->end_date->greaterThan(now());
    }

    /**
     * Check if contract needs renewal
     */
    public function needsRenewal()
    {
        return $this->status === 'Active' && $this->end_date->isPast();
    }

    /**
     * Check if contract is expired
     */
    public function isExpired()
    {
        return $this->status === 'Expired' || 
               ($this->status === 'Active' && $this->end_date->isPast());
    }

    /**
     * Get days until expiration
     */
    public function getDaysUntilExpirationAttribute()
    {
        if ($this->status !== 'Active') return null;
        
        return now()->diffInDays($this->end_date, false);
    }

    /**
     * Get renewal status
     */
    public function getRenewalStatusAttribute()
    {
        if ($this->status === 'Renewed') return 'Renewed';
        if ($this->status === 'Expired') return 'Expired';
        if ($this->needsRenewal()) return 'Needs Renewal';
        if ($this->isExpiringSoon()) return 'Expiring Soon';
        return 'Active';
    }

    /**
     * Get contract duration in days
     */
    public function getDurationAttribute()
    {
        return $this->start_date->diffInDays($this->end_date);
    }

    /**
     * Get contract value per day
     */
    public function getValuePerDayAttribute()
    {
        $duration = $this->getDurationAttribute();
        return $duration > 0 ? $this->contract_value / $duration : 0;
    }

    /**
     * Renew the contract
     */
    public function renew($newEndDate = null, $renewalTerms = null)
    {
        $this->status = 'Renewed';
        $this->renewal_count = ($this->renewal_count ?? 0) + 1;
        $this->renewal_date = now();
        $this->renewed_by = auth()->user()->name ?? 'System';
        
        if ($newEndDate) {
            $this->end_date = $newEndDate;
        }
        
        if ($renewalTerms) {
            $this->renewal_terms = $renewalTerms;
        }
        
        $this->save();
        
        // Create new active contract record
        $newContract = $this->replicate();
        $newContract->status = 'Active';
        $newContract->start_date = now();
        $newContract->end_date = $newEndDate ?: $this->end_date->addYear();
        $newContract->renewal_count = 0;
        $newContract->renewal_date = null;
        $newContract->renewed_by = null;
        $newContract->contract_number = 'CTR-' . date('Y') . '-' . str_pad(self::count() + 1, 4, '0', STR_PAD_LEFT);
        $newContract->save();
        
        return $newContract;
    }

    /**
     * Terminate the contract
     */
    public function terminate($reason)
    {
        $this->status = 'Terminated';
        $this->termination_reason = $reason;
        $this->terminated_by = auth()->user()->name ?? 'System';
        $this->save();
    }

    protected static function boot()
    {
        parent::boot();

        static::saving(function ($contract) {
            // Auto-update renewal date when contract is renewed
            if ($contract->status === 'Renewed' && !$contract->renewal_date) {
                $contract->renewal_date = now();
            }
            
            // Auto-expire contracts past their end date
            if ($contract->status === 'Active' && $contract->end_date->isPast()) {
                $contract->status = 'Expired';
            }
        });

        static::updated(function ($contract) {
            // Send notifications for contracts expiring soon
            if ($contract->isExpiringSoon(7)) {
                // TODO: Send email notification
                \Log::info('Contract ' . $contract->contract_number . ' is expiring soon');
            }
        });
    }
}
