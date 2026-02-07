<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DeliveryConfirmation extends Model
{
    protected $fillable = [
        'confirmation_number',
        'order_number',
        'tracking_number',
        'recipient_name',
        'recipient_email',
        'recipient_phone',
        'delivery_address',
        'delivery_city',
        'delivery_state',
        'delivery_zipcode',
        'delivery_country',
        'delivery_type',
        'status',
        'scheduled_delivery_date',
        'actual_delivery_time',
        'delivery_notes',
        'special_instructions',
        'carrier_name',
        'driver_name',
        'driver_phone',
        'signature_image_url',
        'package_value',
        'package_count',
        'priority',
        'created_by',
    ];

    protected $casts = [
        'scheduled_delivery_date' => 'date',
        'actual_delivery_time' => 'datetime',
        'package_value' => 'decimal:2',
        'package_count' => 'integer',
    ];

    protected static function boot()
    {
        parent::boot();

        static::saving(function ($delivery) {
            // Auto-update actual delivery time when status changes to 'Delivered'
            if ($delivery->status === 'Delivered' && !$delivery->actual_delivery_time) {
                $delivery->actual_delivery_time = now();
            }
            
            // Auto-update status when actual delivery time is set
            if ($delivery->actual_delivery_time && $delivery->status !== 'Delivered') {
                $delivery->status = 'Delivered';
            }
        });
    }
}
