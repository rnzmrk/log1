<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;

class SupplierPost extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'content',
        'excerpt',
        'image',
        'supplier_id',
        'category',
        'tags',
        'featured',
        'status',
        'views',
        'published_at',
        'created_by',
    ];

    protected $casts = [
        'featured' => 'boolean',
        'published_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Relationships
    public function supplier()
    {
        return $this->belongsTo(Supplier::class, 'supplier_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // Helper methods
    public function getStatusColorAttribute()
    {
        return match($this->status) {
            'published' => 'green',
            'draft' => 'yellow',
            'archived' => 'gray',
            default => 'gray',
        };
    }

    public function getExcerptAttribute()
    {
        return $this->attributes['excerpt'] ?: Str::limit(strip_tags($this->content), 150);
    }

    public function getReadTimeAttribute()
    {
        $words = str_word_count(strip_tags($this->content));
        return ceil($words / 200); // Assuming 200 words per minute
    }

    // Scopes
    public function scopePublished($query)
    {
        return $query->where('status', 'published')
                    ->whereNotNull('published_at')
                    ->where('published_at', '<=', now());
    }

    public function scopeFeatured($query)
    {
        return $query->where('featured', true);
    }

    public function scopeByCategory($query, $category)
    {
        return $query->where('category', $category);
    }

    public function scopeFilter($query, array $filters)
    {
        // Search functionality
        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('content', 'like', "%{$search}%")
                  ->orWhere('excerpt', 'like', "%{$search}%");
            });
        }

        // Category filter
        if (!empty($filters['category'])) {
            $query->where('category', $filters['category']);
        }

        // Status filter
        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        // Featured filter
        if (!empty($filters['featured'])) {
            $query->where('featured', true);
        }
    }

    // Boot method to auto-generate slug
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($post) {
            if (!$post->slug) {
                $post->slug = Str::slug($post->title);
            }
        });

        static::updating(function ($post) {
            if ($post->isDirty('title') && !$post->slug) {
                $post->slug = Str::slug($post->title);
            }
        });

        static::saving(function ($post) {
            if ($post->status === 'published' && !$post->published_at) {
                $post->published_at = now();
            }
        });
    }
}
