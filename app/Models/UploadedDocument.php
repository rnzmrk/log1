<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UploadedDocument extends Model
{
    use HasFactory;

    protected $fillable = [
        'document_number',
        'document_title',
        'description',
        'document_type',
        'category',
        'file_path',
        'file_name',
        'file_size',
        'file_type',
        'reference_number',
        'tags',
        'effective_date',
        'expiration_date',
        'visibility',
        'view_only_access',
        'download_permission',
        'edit_permission',
        'share_permission',
        'authorized_users',
        'uploaded_by',
        'department',
        'status',
        'upload_date',
    ];

    protected $casts = [
        'effective_date' => 'date',
        'expiration_date' => 'date',
        'upload_date' => 'date',
        'view_only_access' => 'boolean',
        'download_permission' => 'boolean',
        'edit_permission' => 'boolean',
        'share_permission' => 'boolean',
        'authorized_users' => 'array',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($document) {
            if (empty($document->document_number)) {
                $document->document_number = 'DOC-' . date('Y') . '-' . str_pad(static::count() + 1, 4, '0', STR_PAD_LEFT);
            }
            if (empty($document->upload_date)) {
                $document->upload_date = now();
            }
        });
    }

    public function getStatusColorAttribute()
    {
        return match($this->status) {
            'Active' => 'green',
            'Processing' => 'blue',
            'Archived' => 'gray',
            'Expired' => 'red',
            'Restricted' => 'orange',
            default => 'gray',
        };
    }

    public function getVisibilityColorAttribute()
    {
        return match($this->visibility) {
            'Public' => 'green',
            'Internal' => 'blue',
            'Restricted' => 'orange',
            'Confidential' => 'red',
            default => 'gray',
        };
    }

    public function getFileSizeInMBAttribute()
    {
        return number_format($this->file_size / 1024 / 1024, 2) . ' MB';
    }

    public function isExpired()
    {
        return $this->expiration_date && $this->expiration_date->isPast();
    }

    public function canBeDownloaded()
    {
        return $this->download_permission && !$this->isExpired();
    }

    public function canBeEdited()
    {
        return $this->edit_permission && !$this->isExpired();
    }

    public function canBeShared()
    {
        return $this->share_permission && !$this->isExpired();
    }
}
