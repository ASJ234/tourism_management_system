<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;

    protected $primaryKey = 'review_id';

    protected $fillable = [
        'user_id',
        'rating',
        'comment',
        'reviewable_id',
        'reviewable_type'
    ];

    protected $casts = [
        'rating' => 'decimal:1'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function reviewable()
    {
        return $this->morphTo();
    }
} 