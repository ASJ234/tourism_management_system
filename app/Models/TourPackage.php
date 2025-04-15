<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TourPackage extends Model
{
    use HasFactory;

    protected $table = 'tour_packages';
    protected $primaryKey = 'package_id';

    protected $fillable = [
        'destination_id',
        'name',
        'description',
        'duration_days',
        'price',
        'max_capacity',
        'difficulty_level',
        'start_date',
        'end_date',
        'total_available_slots',
        'is_active',
        'created_by',
        'discount_percentage',
        'promotion_start_date',
        'promotion_end_date',
        'bookings_count'
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'promotion_start_date' => 'date',
        'promotion_end_date' => 'date',
        'is_active' => 'boolean',
        'price' => 'decimal:2',
        'discount_percentage' => 'integer'
    ];

    public function destination(): BelongsTo
    {
        return $this->belongsTo(Destination::class, 'destination_id');
    }

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class, 'package_id', 'package_id');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function images()
    {
        return $this->hasMany(TourPackageImage::class, 'package_id', 'package_id');
    }

    public function reviews()
    {
        return $this->hasMany(Review::class, 'reviewable_id', 'package_id')
            ->where('reviewable_type', TourPackage::class);
    }

    public function inclusions(): HasMany
    {
        return $this->hasMany(PackageInclusion::class, 'package_id');
    }

    public function updateBookingsCount(): void
    {
        $this->update([
            'bookings_count' => $this->bookings()->count()
        ]);
    }
} 