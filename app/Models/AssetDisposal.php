<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AssetDisposal extends Model
{
    protected $fillable = [
        'disposal_number',
        'asset_id',
        'asset_name',
        'details',
        'date',
        'duration',
        'department',
        'quantity',
        'status',
        'created_by',
    ];

    protected $casts = [
        'date' => 'date',
    ];

    public function asset()
    {
        return $this->belongsTo(Asset::class);
    }
}
