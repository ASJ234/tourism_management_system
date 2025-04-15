<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PackageInclusion extends Model
{
    protected $fillable = [
        'package_id',
        'inclusion',
        // add other fields as needed
    ];

    public function tourPackage(): BelongsTo
    {
        return $this->belongsTo(TourPackage::class, 'package_id');
    }
} 