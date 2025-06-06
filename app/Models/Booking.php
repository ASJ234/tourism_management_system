<?php 
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Booking extends Model 
{
    protected $primaryKey = 'booking_id';
    
    protected $fillable = [
        'user_id',
        'package_id',
        'number_of_travelers',
        'total_price',
        'booking_date',
        'start_date',
        'status',
        'payment_status', 
        'special_requests'
    ];


    protected $dates = [
        'start_date',
        'booking_date',
        'created_at',
        'updated_at'
    ];

    // Add this relationship
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function package()
    {
        return $this->belongsTo(TourPackage::class, 'package_id', 'package_id');
    }
}