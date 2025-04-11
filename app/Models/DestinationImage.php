<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DestinationImage extends Model
{
    use HasFactory;

    protected $table = 'destination_images';
    protected $primaryKey = 'image_id';

    protected $fillable = [
        'destination_id',
        'image_path',
        'is_primary'
    ];

    protected $casts = [
        'is_primary' => 'boolean'
    ];

    public function destination()
    {
        return $this->belongsTo(Destination::class);
    }
} 