<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\TourPackage;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'admin']);
    }

    public function index(Request $request)
    {
        try {
            // Get all tour packages for the dropdown
            $tourPackages = TourPackage::all();
            
            // Base query
            $baseQuery = Booking::with(['user', 'package.destination']);

            // Filter by tour package
            if ($request->filled('package_id')) {
                $baseQuery->where('package_id', $request->package_id);
            }

            // Filter by status
            if ($request->filled('status')) {
                $baseQuery->where('status', $request->status);
            }

            // Filter by date
            if ($request->filled('date')) {
                $baseQuery->whereDate('booking_date', $request->date);
            }

            // Search by booking ID or tourist name
            if ($request->filled('search')) {
                $search = $request->search;
                $baseQuery->where(function($q) use ($search) {
                    $q->where('booking_id', 'like', "%{$search}%")
                      ->orWhereHas('user', function($userQuery) use ($search) {
                          $userQuery->where('full_name', 'like', "%{$search}%");
                      });
                });
            }

            // Get the paginated results
            $bookings = $baseQuery->latest()->paginate(10)->withQueryString();

            // Initialize stats array with default values
            $stats = [
                'total' => 0,
                'paid' => 0,
                'pending' => 0,
                'cancelled' => 0
            ];

            // Calculate statistics using separate queries to avoid query modification issues
            $stats['total'] = (clone $baseQuery)->count();
            $stats['paid'] = (clone $baseQuery)->where('status', 'Paid')->count();
            $stats['pending'] = (clone $baseQuery)->where('status', 'Pending')->count();
            $stats['cancelled'] = (clone $baseQuery)->where('status', 'Cancelled')->count();

            return view('admin.bookings.index', compact('bookings', 'tourPackages', 'stats'));
        } catch (\Exception $e) {
            // Log the error
            \Log::error('Error in BookingController@index: ' . $e->getMessage());
            
            // Return with empty stats if there's an error
            return view('admin.bookings.index', [
                'bookings' => collect([]),
                'tourPackages' => collect([]),
                'stats' => [
                    'total' => 0,
                    'paid' => 0,
                    'pending' => 0,
                    'cancelled' => 0
                ]
            ])->with('error', 'An error occurred while loading the bookings.');
        }
    }

    public function show(Booking $booking)
    {
        $booking->load(['user', 'package.destination']);
        return view('admin.bookings.show', compact('booking'));
    }

    public function approve($id)
    {
        $booking = Booking::findOrFail($id);
        
        if ($booking->status === 'Paid' || $booking->status === 'paid') {
            $booking->update(['status' => 'Approved']);
            return redirect()->back()->with('success', 'Booking has been approved successfully.');
        }
        
        return redirect()->back()->with('error', 'Only paid bookings can be approved.');
    }

    public function updateStatus(Request $request, $id)
    {
        $booking = Booking::findOrFail($id);
        
        $request->validate([
            'status' => 'required|in:Pending,Paid,Approved,Cancelled'
        ]);

        $booking->update(['status' => $request->status]);
        
        return redirect()->back()->with('success', 'Booking status updated successfully.');
    }
} 