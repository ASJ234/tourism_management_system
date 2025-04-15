<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\DB;

class Destination extends Model
{
    use HasFactory;

    protected $primaryKey = 'destination_id';

    protected $fillable = [
        'name',
        'description',
        'location',
        'region',
        'image_url',
        'created_by',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean'
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by', 'user_id');
    }

    public function packages(): HasMany
    {
        return $this->hasMany(TourPackage::class, 'destination_id', 'destination_id');
    }

    public function services()
    {
        return $this->hasMany(Service::class, 'destination_id');
    }

    public function reviews()
    {
        return $this->morphMany(Review::class, 'reviewable');
    }

    public function images()
    {
        return $this->hasMany(DestinationImage::class, 'destination_id', 'destination_id');
    }

    /**
     * Get destinations grouped by region with counts
     */
    public static function getDestinationsByRegion()
    {
        return self::select('region', DB::raw('count(*) as total'))
            ->groupBy('region')
            ->orderBy('total', 'desc')
            ->get();
    }

    /**
     * Get destinations with most images
     */
    public static function getDestinationsWithMostImages($limit = 5)
    {
        return self::withCount('images')
            ->orderBy('images_count', 'desc')
            ->take($limit)
            ->get();
    }

    /**
     * Get count of active destinations
     */
    public static function getActiveDestinationsCount()
    {
        return self::where('is_active', true)->count();
    }

    /**
     * Get count of inactive destinations
     */
    public static function getInactiveDestinationsCount()
    {
        return self::where('is_active', false)->count();
    }

    /**
     * Get count of recent destinations (last 6 months)
     */
    public static function getRecentDestinationsCount()
    {
        return self::where('created_at', '>=', now()->subMonths(6))->count();
    }

    /**
     * Get total destinations count
     */
    public static function getTotalDestinationsCount()
    {
        return self::count();
    }

    /**
     * Get tourist statistics over the years
     */
    public static function getTouristStatistics()
    {
        return \DB::table('bookings')
            ->select(
                \DB::raw('YEAR(created_at) as year'),
                \DB::raw('SUM(number_of_travelers) as total_tourists')
            )
            ->groupBy('year')
            ->orderBy('year', 'asc')
            ->get();
    }

    /**
     * Get payment statistics over the years
     */
    public static function getPaymentStatistics()
    {
        return \DB::table('payments')
            ->select(
                \DB::raw('YEAR(created_at) as year'),
                \DB::raw('SUM(amount) as total_amount')
            )
            ->groupBy('year')
            ->orderBy('year', 'asc')
            ->get();
    }

    /**
     * Get package performance statistics over the years
     */
    public static function getPackagePerformanceStatistics()
    {
        // Get total packages and active packages
        $totalPackages = TourPackage::count();
        $activePackages = TourPackage::where('is_active', true)->count();
        
        // Get total revenue from bookings
        $totalRevenue = \DB::table('bookings')
            ->join('tour_packages', 'bookings.package_id', '=', 'tour_packages.package_id')
            ->sum('bookings.total_price');

        // Get top performing packages
        $topPackages = TourPackage::with(['destination', 'bookings'])
            ->get()
            ->map(function($package) {
                return (object)[
                    'package_id' => $package->package_id,
                    'name' => $package->name,
                    'destination' => $package->destination,
                    'bookings_count' => $package->bookings->count(),
                    'total_revenue' => $package->bookings->sum('total_price')
                ];
            })
            ->sortByDesc('bookings_count')
            ->take(5);

        // Get performance trend data
        $performanceData = \DB::table('bookings')
            ->join('tour_packages', 'bookings.package_id', '=', 'tour_packages.package_id')
            ->select(
                \DB::raw('YEAR(bookings.created_at) as year'),
                \DB::raw('COUNT(DISTINCT tour_packages.package_id) as total_packages'),
                \DB::raw('SUM(number_of_travelers) as total_bookings'),
                \DB::raw('SUM(bookings.total_price) as total_revenue')
            )
            ->groupBy('year')
            ->orderBy('year', 'asc')
            ->get();

        return collect([
            'total_packages' => $totalPackages,
            'active_packages' => $activePackages,
            'total_revenue' => $totalRevenue,
            'top_packages' => $topPackages,
            'performance_data' => $performanceData
        ]);
    }
} 