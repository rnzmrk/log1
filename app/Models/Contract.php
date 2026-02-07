<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Contract extends Model
{
    protected $fillable = [
        'contract_number',
        'contract_name',
        'vendor',
        'vendor_contact',
        'vendor_email',
        'vendor_phone',
        'contract_type',
        'contract_value',
        'status',
        'priority',
        'start_date',
        'end_date',
        'renewal_date',
        'description',
        'terms_conditions',
        'notes',
        'created_by',
        'approved_by',
    ];

    protected $casts = [
        'contract_value' => 'decimal:2',
        'start_date' => 'date',
        'end_date' => 'date',
        'renewal_date' => 'date',
    ];

    protected static function boot()
    {
        parent::boot();

        static::saving(function ($contract) {
            // Auto-update renewal date when contract is renewed
            if ($contract->status === 'Renewed' && !$contract->renewal_date) {
                $contract->renewal_date = now();
            }
        });
    }
}
