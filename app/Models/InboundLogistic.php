<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InboundLogistic extends Model
{
    protected $fillable = [
        'shipment_id',
        'po_number',
        'sku',
        'item_name',
        'category',
        'supplier',
        'expected_units',
        'received_units',
        'quantity',
        'department',
        'received_by',
        'quality',
        'status',
        'expected_date',
        'received_date',
        'notes',
    ];

    protected $casts = [
        'expected_date' => 'date',
        'received_date' => 'date',
        'expected_units' => 'integer',
        'received_units' => 'integer',
    ];
}
