<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TourPackageImage extends Model
{
    protected $fillable = [
        'package_id',
        'image_path',
        'caption',
        'is_primary'
    ];

    protected $casts = [
        'is_primary' => 'boolean'
    ];

    public function package(): BelongsTo
    {
        return $this->belongsTo(TourPackage::class, 'package_id');
    }
} 