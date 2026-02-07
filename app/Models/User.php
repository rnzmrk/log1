<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Schema;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'user_number',
        'role',
        'department',
        'status',
        'phone',
        'last_login_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'last_login_at' => 'datetime',
        ];
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($user) {
            // Auto-generate user number if column exists
            if (Schema::hasColumn('users', 'user_number') && !$user->user_number) {
                $user->user_number = 'USR-' . str_pad(User::count() + 1, 3, '0', STR_PAD_LEFT);
            }
            
            // Set default status if column exists
            if (Schema::hasColumn('users', 'status') && !$user->status) {
                $user->status = 'Active';
            }
        });
    }

    public function getStatusColorAttribute()
    {
        if (!Schema::hasColumn('users', 'status')) return 'gray';
        return match($this->status) {
            'Active' => 'green',
            'Inactive' => 'gray',
            'Suspended' => 'red',
            'Pending' => 'orange',
            default => 'gray',
        };
    }

    public function getRoleColorAttribute()
    {
        if (!Schema::hasColumn('users', 'role')) return 'gray';
        return match($this->role) {
            'Administrator' => 'purple',
            'Manager' => 'blue',
            'User' => 'green',
            'Viewer' => 'orange',
            default => 'gray',
        };
    }

    public function getInitialsAttribute()
    {
        $words = explode(' ', $this->name);
        return strtoupper(substr($words[0] ?? '', 0, 1) . substr($words[1] ?? '', 0, 1));
    }

    public function getAvatarColorAttribute()
    {
        if (!Schema::hasColumn('users', 'role')) return 'gray';
        return match($this->role) {
            'Administrator' => 'purple',
            'Manager' => 'blue',
            'User' => 'green',
            'Viewer' => 'orange',
            default => 'gray',
        };
    }
}
