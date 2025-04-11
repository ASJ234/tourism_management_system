<?php

namespace App\Http\Controllers\TourOperator;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\TourPackage;
use App\Models\User;
use App\Models\Destination;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TourOperatorController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:tour_operator']);
    }

    public function dashboard()
    {
        $tourOperator = auth()->user();
        
        // Get active packages count
        $activePackages = TourPackage::where('created_by', $tourOperator->user_id)
            ->where('is_active', true)
            ->count();
        
        // Get total bookings count
        $recentBookings = Booking::whereHas('package', function($query) use ($tourOperator) {
            $query->where('created_by', $tourOperator->user_id);
        })->count();
        
        // Get recent bookings for the table
        $recentBookingsList = Booking::with(['package', 'user'])
            ->whereHas('package', function($query) use ($tourOperator) {
                $query->where('created_by', $tourOperator->user_id);
            })
            ->latest()
            ->take(5)
            ->get();
        
        // Get recent packages for the table
        $recentPackages = TourPackage::where('created_by', $tourOperator->user_id)
            ->latest()
            ->take(5)
            ->get();
        
        return view('tour_operator.dashboard', compact(
            'activePackages',
            'recentBookings',
            'recentBookingsList',
            'recentPackages'
        ));
    }

    public function packages()
    {
        $packages = TourPackage::where('created_by', auth()->id())
            ->with(['destination', 'bookings'])
            ->latest()
            ->paginate(10);

        return view('tour_operator.packages.index', compact('packages'));
    }

    public function createPackage()
    {
        $destinations = Destination::where('is_active', true)->get();
        return view('tour_operator.packages.create', compact('destinations'));
    }

    public function storePackage(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'duration_days' => 'required|integer|min:1',
            'max_capacity' => 'required|integer|min:1',
            'difficulty_level' => 'required|in:Easy,Moderate,Challenging',
            'start_date' => 'required|date|after:today',
            'end_date' => 'required|date|after:start_date',
            'total_available_slots' => 'required|integer|min:1',
            'destination_id' => 'required|exists:destinations,destination_id'
        ]);

        $package = new TourPackage();
        $package->fill($validated);
        $package->created_by = auth()->id();
        $package->is_active = true;
        $package->save();


        return redirect()->route('tour_operator.packages.show', $package)
            ->with('success', 'Package created successfully!');
    }

    public function showPackage(TourPackage $package)
    {
        // Check if the user is the creator of the package
        if ($package->created_by !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }
        
        // Eager load the user relationship for bookings with specific fields
        $package->load(['bookings' => function($query) {
            $query->with(['user' => function($q) {
                $q->select('user_id', 'full_name', 'email');
            }]);
        }]);
        
        return view('tour_operator.packages.show', compact('package'));
    }


    public function editPackage(TourPackage $package)
    {
        if ($package->created_by !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }
        
        $destinations = Destination::where('is_active', true)->get();
        return view('tour_operator.packages.edit', compact('package', 'destinations'));
    }

    public function updatePackage(Request $request, TourPackage $package)
    {
        if ($package->created_by !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'duration_days' => 'required|integer|min:1',
            'max_capacity' => 'required|integer|min:1',
            'difficulty_level' => 'required|in:Easy,Moderate,Challenging',
            'start_date' => 'required|date|after:today',
            'end_date' => 'required|date|after:start_date',
            'total_available_slots' => 'required|integer|min:1',
            'destination_id' => 'required|exists:destinations,destination_id',
            'is_active' => 'boolean'
        ]);

        $package->update($validated);

        return redirect()->route('tour_operator.packages.show', $package)
            ->with('success', 'Package updated successfully!');
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
        if ($package->created_by !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }
        
        $booking->load(['package', 'user']);
        return view('tour_operator.bookings.show', compact('booking'));
    }

    public function updateBookingStatus(Request $request, Booking $booking)
    {
        $this->authorize('update', $booking);
        
        $validated = $request->validate([
            'status' => 'required|in:Pending,Confirmed,Cancelled,Completed'
        ]);

        $booking->update($validated);

        return redirect()->route('tour_operator.bookings.show', $booking)
            ->with('success', 'Booking status updated successfully!');
    }

    public function promotions()
    {
        $packages = TourPackage::where('created_by', auth()->id())
            ->where('is_active', true)
            ->with(['destination'])
            ->get();

        return view('tour_operator.promotions.index', compact('packages'));
    }

    public function createPromotion()
    {
        $packages = TourPackage::where('created_by', auth()->id())
            ->where('is_active', true)
            ->with(['destination'])
            ->get();

        return view('tour_operator.promotions.create', compact('packages'));
    }

    public function storePromotion(Request $request)
    {
        $validated = $request->validate([
            'package_id' => 'required|exists:tour_packages,package_id',
            'discount_percentage' => 'required|integer|min:1|max:100',
            'start_date' => 'required|date|after:today',
            'end_date' => 'required|date|after:start_date'
        ]);

        $package = TourPackage::findOrFail($validated['package_id']);
        if ($package->created_by !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

        $package->update([
            'discount_percentage' => $validated['discount_percentage'],
            'promotion_start_date' => $validated['start_date'],
            'promotion_end_date' => $validated['end_date']
        ]);

        return redirect()->route('tour_operator.promotions.index')
            ->with('success', 'Promotion created successfully!');
    }

    public function updatePromotion(Request $request, TourPackage $package)
    {
        $this->authorize('update', $package);
        
        $validated = $request->validate([
            'discount_percentage' => 'required|integer|min:1|max:100',
            'start_date' => 'required|date|after:today',
            'end_date' => 'required|date|after:start_date'
        ]);

        $package->update([
            'discount_percentage' => $validated['discount_percentage'],
            'promotion_start_date' => $validated['start_date'],
            'promotion_end_date' => $validated['end_date']
        ]);

        return redirect()->route('tour_operator.promotions.index')
            ->with('success', 'Promotion updated successfully!');
    }

    public function removePromotion(TourPackage $package)
    {
        $this->authorize('update', $package);
        
        $package->update([
            'discount_percentage' => null,
            'promotion_start_date' => null,
            'promotion_end_date' => null
        ]);

        return redirect()->route('tour_operator.promotions.index')
            ->with('success', 'Promotion removed successfully!');
    }

    public function destroyPackage(TourPackage $package)
    {
        if ($package->created_by !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

        // Check if there are any active bookings
        if ($package->bookings()->where('status', '!=', 'Cancelled')->exists()) {
            return back()->with('error', 'Cannot delete package with active bookings.');
        }

        $package->delete();

        return redirect()->route('tour_operator.packages.index')
            ->with('success', 'Package deleted successfully!');
    }
}