<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VehicleRequest extends Model
{
    protected $fillable = [
        'reservation_id',
        'vehicle_id',
        'reserved_by',
        'start_time',
        'end_time',
        'purpose',
        'status',
        'department',
        'api_data',
        'request_status',
        'user_id',
    ];

    protected $casts = [
        'start_time' => 'datetime',
        'end_time' => 'datetime',
        'api_data' => 'json',
    ];

    // Status constants
    const STATUS_PENDING = 'Pending';
    const STATUS_APPROVED = 'Approved';
    const STATUS_REJECTED = 'Rejected';
    const STATUS_RESERVED = 'Reserved';

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getFormattedStartTimeAttribute()
    {
        return $this->start_time ? $this->start_time->format('M d, Y h:i A') : 'N/A';
    }

    public function getFormattedEndTimeAttribute()
    {
        return $this->end_time ? $this->end_time->format('M d, Y h:i A') : 'N/A';
    }

    public function getStatusBadgeAttribute()
    {
        $badges = [
            self::STATUS_PENDING => '<span class="px-2 py-1 text-xs font-medium rounded-full bg-yellow-100 text-yellow-800">Pending</span>',
            self::STATUS_APPROVED => '<span class="px-2 py-1 text-xs font-medium rounded-full bg-green-100 text-green-800">Approved</span>',
            self::STATUS_REJECTED => '<span class="px-2 py-1 text-xs font-medium rounded-full bg-red-100 text-red-800">Rejected</span>',
            self::STATUS_RESERVED => '<span class="px-2 py-1 text-xs font-medium rounded-full bg-blue-100 text-blue-800">Reserved</span>',
        ];

        return $badges[$this->status] ?? '<span class="px-2 py-1 text-xs font-medium rounded-full bg-gray-100 text-gray-800">Unknown</span>';
    }
}
