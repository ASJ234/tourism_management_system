<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Destination;
use App\Models\TourPackage;
use App\Models\Review;
use Illuminate\Http\Request;

class TouristController extends Controller
{
    public function dashboard()
    {
        $user = auth()->user();
        
        // Get recent bookings
        $recentBookings = Booking::where('user_id', $user->user_id)
            ->with('package')
            ->latest()
            ->take(5)
            ->get();

        // Get user's recent reviews
        $recentReviews = Review::where('user_id', $user->user_id)
            ->with('package')
            ->latest()
            ->take(5)
            ->get();

        return view('tourist.dashboard', compact('recentBookings', 'recentReviews'));
    }

    public function destinations()
    {
        $destinations = Destination::where('is_active', true)
            ->latest()
            ->paginate(12);

        return view('tourist.destinations', compact('destinations'));
    }

    public function showDestination(Destination $destination)
    {
        $destination->load(['createdBy']);
        return view('tourist.destinations.show', compact('destination'));
    }

    public function packages()
    {
        $packages = TourPackage::with(['destination', 'images'])
            ->where('is_active', true)
            ->latest()
            ->paginate(12);

        return view('tourist.packages', compact('packages'));
    }

    public function showPackage(TourPackage $package)
    {
        $package->load(['destination', 'images', 'reviews.user']);
        return view('tourist.packages.show', compact('package'));
    }

    public function bookings()
    {
        $bookings = Booking::where('user_id', auth()->user()->user_id)
            ->with(['package', 'package.destination', 'package.images'])
            ->latest()
            ->paginate(10);

        return view('tourist.bookings', compact('bookings'));
    }

    public function reviews()
    {
        $reviews = Review::where('user_id', auth()->id())
            ->with(['destination', 'package'])
            ->latest()
            ->paginate(10);

        return view('tourist.reviews', compact('reviews'));
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
        $request->validate([
            'package_id' => 'required|exists:tour_packages,id',
            'start_date' => 'required|date|after:today',
            'number_of_people' => 'required|integer|min:1',
            'special_requests' => 'nullable|string|max:500'
        ]);

        $booking = Booking::create([
            'user_id' => auth()->id(),
            'package_id' => $request->package_id,
            'start_date' => $request->start_date,
            'number_of_people' => $request->number_of_people,
            'special_requests' => $request->special_requests,
            'status' => 'pending'
        ]);

        return redirect()->route('tourist.bookings.show', $booking)
            ->with('success', 'Booking created successfully!');
    }

    public function showBooking(Booking $booking)
    {
        // Check if the authenticated user owns this booking
        if ($booking->user_id !== auth()->id()) {
            abort(403, 'This action is unauthorized.');
        }
    
        $booking->load(['package', 'package.destination', 'package.images']);
        return view('tourist.bookings.show', compact('booking'));
    }

    public function cancelBooking(Booking $booking)
    {
        // Check if the authenticated user owns this booking
        if ($booking->user_id != auth()->user()->user_id) {
            abort(403, 'This action is unauthorized.');
        }

        // Only allow cancellation of pending bookings
        if ($booking->status !== 'pending') {
            return back()->with('error', 'Only pending bookings can be cancelled.');
        }

        $booking->update(['status' => 'cancelled']);
        return redirect()->route('tourist.bookings')->with('success', 'Booking cancelled successfully.');
    }

    public function editReview(Review $review)
    {
        $this->authorize('update', $review);
        
        return view('tourist.reviews.edit', compact('review'));
    }

    public function updateReview(Request $request, Review $review)
    {
        $this->authorize('update', $review);
        
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string|min:10|max:500'
        ]);

        $review->update([
            'rating' => $request->rating,
            'comment' => $request->comment
        ]);

        return redirect()->route('tourist.reviews')
            ->with('success', 'Review updated successfully!');
    }

    public function storeReview(Request $request)
    {
        $request->validate([
            'package_id' => 'required|exists:tour_packages,id',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string|min:10|max:500'
        ]);

        $review = Review::create([
            'user_id' => auth()->id(),
            'package_id' => $request->package_id,
            'rating' => $request->rating,
            'comment' => $request->comment
        ]);

        return redirect()->route('tourist.reviews')
            ->with('success', 'Review submitted successfully!');
    }
} 