<?php

namespace App\Http\Controllers\Tourist;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Destination;
use App\Models\Review;
use App\Models\TourPackage;
use Illuminate\Http\Request;

class TouristController extends Controller
{
    public function dashboard()
    {
        $user = auth()->user();
        
        // Get total bookings count
        $totalBookings = Booking::where('user_id', $user->user_id)->count();
        
        // Get recent bookings for display
        $recentBookings = Booking::where('user_id', $user->user_id)
            ->with(['package', 'package.destination'])
            ->latest()
            ->take(5)
            ->get();
            
        $recentReviews = Review::where('user_id', $user->user_id)
            ->with(['reviewable'])
            ->latest()
            ->take(5)
            ->get();

        return view('tourist.dashboard', [
            'totalBookings' => $totalBookings,
            'recentBookings' => $recentBookings,
            'recentReviews' => $recentReviews
        ]);
    }

    public function destinations(Request $request)
    {
        $query = Destination::query()
            ->where('is_active', true)
            ->with(['packages' => function($q) {
                $q->where('is_active', true);
            }])
            ->withCount('reviews')
            ->withAvg('reviews', 'rating');

        // Apply region filter
        if ($request->filled('region')) {
            $query->where('region', $request->region);
        }

        // Apply price filter
        if ($request->filled('price')) {
            switch ($request->price) {
                case 'budget':
                    $query->whereHas('packages', function($q) {
                        $q->where('price', '<', 1000);
                    });
                    break;
                case 'mid-range':
                    $query->whereHas('packages', function($q) {
                        $q->whereBetween('price', [1000, 3000]);
                    });
                    break;
                case 'luxury':
                    $query->whereHas('packages', function($q) {
                        $q->where('price', '>', 3000);
                    });
                    break;
            }
        }

        $destinations = $query->latest()->paginate(12);
        return view('tourist.destinations', compact('destinations'));
    }

    public function showDestination(Destination $destination)
    {
        $destination->load(['images', 'packages', 'creator']);
        return view('tourist.destinations.show', compact('destination'));
    }

    public function packages()
    {
        // Get all active destinations for the filter dropdown
        $destinations = Destination::where('is_active', true)->get();

        $packages = TourPackage::where('is_active', true)
            ->with(['destination', 'images'])
            ->latest()
            ->paginate(12);

        return view('tourist.packages', compact('packages', 'destinations'));
    }

    public function showPackage(TourPackage $package)
    {
        $package->load(['destination', 'images', 'inclusions']);
        return view('tourist.packages.show', compact('package'));
    }

    public function bookPackage(TourPackage $package)
    {
        $package->load(['destination', 'inclusions']);
        return view('tourist.packages.book', compact('package'));
    }

    public function bookings()
    {
        $bookings = Booking::where('user_id', auth()->user()->user_id)
            ->with(['package', 'package.destination'])
            ->latest()
            ->paginate(10);

        return view('tourist.bookings', compact('bookings'));
    }

    public function createBooking()
    {
        $packages = TourPackage::where('is_active', true)
            ->with('destination')
            ->get();
            
        return view('tourist.bookings.create', compact('packages'));
    }

    public function storeBooking(Request $request)
    {
        $validated = $request->validate([
            'package_id' => 'required|exists:tour_packages,package_id',
            'start_date' => 'required|date|after:today',
            'number_of_travelers' => 'required|integer|min:1',
            'special_requests' => 'nullable|string|max:500'
        ]);

        $package = TourPackage::findOrFail($validated['package_id']);
        $total_price = $package->price * $validated['number_of_travelers'];

        $booking = Booking::create([
            'user_id' => auth()->user()->user_id,
            'package_id' => $validated['package_id'],
            'number_of_travelers' => $validated['number_of_travelers'],
            'total_price' => $total_price,
            'start_date' => $validated['start_date'],
            'status' => 'Pending',
            'payment_status' => 'Unpaid',
            'special_requests' => $validated['special_requests']
        ]);

        return redirect()->route('tourist.bookings.show', $booking)
            ->with('success', 'Booking created successfully!');
    }

    public function showBooking(Booking $booking)
    {
        $this->authorize('view', $booking);
        
        $booking->load(['package', 'package.destination', 'package.inclusions']);
        return view('tourist.bookings.show', compact('booking'));
    }

    public function cancelBooking(Booking $booking)
    {
        $this->authorize('cancel', $booking);
        
        $booking->update([
            'status' => 'Cancelled',
            'cancelled_at' => now(),
            'cancellation_reason' => request('reason')
        ]);

        return redirect()->route('tourist.bookings')
            ->with('success', 'Booking cancelled successfully.');
    }

    public function reviews()
    {
        $reviews = Review::where('user_id', auth()->user()->user_id)
            ->with(['reviewable'])
            ->latest()
            ->paginate(10);

        return view('tourist.reviews', compact('reviews'));
    }

    public function createReview($type, $id)
    {
        $reviewable = $type === 'destination' 
            ? Destination::findOrFail($id)
            : TourPackage::findOrFail($id);

        return view('tourist.reviews.create', compact('reviewable', 'type'));
    }

    public function storeReview(Request $request, $type, $id)
    {
        $reviewable = $type === 'destination' 
            ? Destination::findOrFail($id)
            : TourPackage::findOrFail($id);

        $validated = $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string|min:10|max:1000'
        ]);

        $review = $reviewable->reviews()->create([
            'user_id' => auth()->user()->user_id,
            'rating' => $validated['rating'],
            'comment' => $validated['comment']
        ]);

        return redirect()->route('tourist.reviews')
            ->with('success', 'Review submitted successfully.');
    }

    public function editReview(Review $review)
    {
        $this->authorize('update', $review);
        
        return view('tourist.reviews.edit', compact('review'));
    }

    public function updateReview(Request $request, Review $review)
    {
        $this->authorize('update', $review);
        
        $validated = $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string|min:10|max:1000'
        ]);

        $review->update($validated);

        return redirect()->route('tourist.reviews')
            ->with('success', 'Review updated successfully.');
    }

    public function deleteReview(Review $review)
    {
        $this->authorize('delete', $review);
        
        $review->delete();

        return redirect()->route('tourist.reviews')
            ->with('success', 'Review deleted successfully.');
    }
} 