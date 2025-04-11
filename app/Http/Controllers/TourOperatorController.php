<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\TourPackage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Models\Review;

class TourOperatorController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:tour_operator');
    }

    public function dashboard()
    {
        $user = auth()->user();
        
        // Get statistics
        $totalPackages = TourPackage::where('created_by', $user->user_id)->count();
        $totalBookings = Booking::whereHas('package', function($query) use ($user) {
            $query->where('created_by', $user->user_id);
        })->count();
        
        $averageRating = Review::whereHas('package', function($query) use ($user) {
            $query->where('created_by', $user->user_id);
        })->avg('rating') ?? 0;
        
        // Get recent bookings
        $recentBookings = Booking::whereHas('package', function($query) use ($user) {
            $query->where('created_by', $user->user_id);
        })
        ->with(['package', 'user'])
        ->latest()
        ->take(5)
        ->get();
        
        // Get recent reviews
        $recentReviews = Review::whereHas('package', function($query) use ($user) {
            $query->where('created_by', $user->user_id);
        })
        ->with(['package', 'user'])
        ->latest()
        ->take(5)
        ->get();
        
        return view('tour_operator.dashboard', compact(
            'totalPackages',
            'totalBookings',
            'averageRating',
            'recentBookings',
            'recentReviews'
        ));
    }

    public function packages()
    {
        $packages = TourPackage::where('created_by', Auth::id())
            ->with(['destination', 'bookings'])
            ->latest()
            ->paginate(10);

        return view('tour_operator.packages.index', ['packages' => $packages]);
    }

    public function createPackage()
    {
        $destinations = \App\Models\Destination::all();
        return view('tour_operator.packages.create', compact('destinations'));
    }

    public function storePackage(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'destination_id' => 'required|exists:destinations,destination_id',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'duration_days' => 'required|integer|min:1',
            'max_capacity' => 'required|integer|min:1',
            'total_available_slots' => 'required|integer|min:1',
            'difficulty_level' => 'required|in:Easy,Moderate,Challenging',
            'start_date' => 'required|date|after:today',
            'end_date' => 'required|date|after:start_date',
        ]);

        $package = new TourPackage();
        $package->fill($validated);
        $package->created_by = auth()->id();
        $package->is_active = true;
        $package->save();

        return redirect()->route('tour_operator.packages.index')
            ->with('success', 'Package created successfully.');
    }

    public function editPackage(TourPackage $package)
    {
        $this->authorize('update', $package);
        $destinations = \App\Models\Destination::all();
        return view('tour_operator.packages.edit', compact('package', 'destinations'));
    }

    public function updatePackage(Request $request, TourPackage $package)
    {
        $this->authorize('update', $package);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'destination_id' => 'required|exists:destinations,destination_id',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'duration_days' => 'required|integer|min:1',
            'max_capacity' => 'required|integer|min:1',
            'total_available_slots' => 'required|integer|min:1',
            'difficulty_level' => 'required|in:Easy,Moderate,Challenging',
            'start_date' => 'required|date|after:today',
            'end_date' => 'required|date|after:start_date',
            'is_active' => 'boolean',
        ]);

        $package->update($validated);

        return redirect()->route('tour_operator.packages.index')
            ->with('success', 'Package updated successfully.');
    }

    public function deletePackage(TourPackage $package)
    {
        // Check if the user is the creator of the package
        if ($package->created_by !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

        if ($package->bookings()->exists()) {
            return back()->with('error', 'Cannot delete package with existing bookings.');
        }

        $package->delete();

        return redirect()->route('tour_operator.packages.index')
            ->with('success', 'Package deleted successfully.');
    }

    public function showPackage(TourPackage $package)
    {
        // Check if the user is the creator of the package
        if ($package->created_by !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }
        
        // Eager load all necessary relationships with specific fields
        $package->load([
            'destination:id,name',
            'bookings' => function($query) {
                $query->with([
                    'user:id,user_id,full_name,email'
                ])->latest('booking_date');
            }
        ]);
        
        return view('tour_operator.packages.show', compact('package'));
    }

    public function bookings()
    {
        $bookings = Booking::whereHas('package', function($query) {
            $query->where('created_by', auth()->id());
        })
        ->with(['package', 'user' => function($query) {
            $query->select('user_id', 'full_name', 'email');
        }])
        ->latest('booking_date')
        ->paginate(10);

        return view('tour_operator.bookings.index', compact('bookings'));
    }

    public function showBooking(Booking $booking)
    {
        $this->authorize('view', $booking);
        return view('tour_operator.bookings.show', compact('booking'));
    }

    public function updateBookingStatus(Request $request, Booking $booking)
    {
        $this->authorize('update', $booking);

        $validated = $request->validate([
            'status' => 'required|in:pending,confirmed,cancelled,completed'
        ]);

        $booking->update($validated);

        return back()->with('success', 'Booking status updated successfully.');
    }
} 