<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Facades\Hash;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $primaryKey = 'user_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'username',
        'email',
        'password',
        'full_name',
        'role',
        'contact_number',
        'profile_image',
        'is_active'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'last_login' => 'datetime',
        'is_active' => 'boolean',
    ];

    /**
     * Hash the password before saving it to the database.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($user) {
            if (Hash::needsRehash($user->password)) {
                $user->password = Hash::make($user->password);
            }
        });

        static::updating(function ($user) {
            if ($user->isDirty('password') && Hash::needsRehash($user->password)) {
                $user->password = Hash::make($user->password);
            }
        });
    }

    // Relationships
    public function bookings()
    {
        return $this->hasMany(Booking::class, 'user_id');
    }

    public function reviews()
    {
        return $this->hasMany(Review::class, 'user_id');
    }

    public function createdDestinations()
    {
        return $this->hasMany(Destination::class, 'created_by');
    }

    public function createdTourPackages()
    {
        return $this->hasMany(TourPackage::class, 'created_by');
    }

    public function activityLogs()
    {
        return $this->hasMany(ActivityLog::class, 'user_id');
    }

    // Helper methods
    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    public function isTourOperator()
    {
        return $this->role === 'tour_operator';
    }

    public function isDestinationManager()
    {
        return $this->role === 'destination_manager';
    }

    public function isTourist()
    {
        return $this->role === 'tourist';
    }
}
