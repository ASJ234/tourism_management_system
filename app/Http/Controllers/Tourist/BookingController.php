<?php

namespace App\Http\Controllers\Tourist;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\TourPackage;
use App\Services\BookingPdfService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BookingController extends Controller
{
    protected $pdfService;

    public function __construct(BookingPdfService $pdfService)
    {
        $this->pdfService = $pdfService;
    }

    public function index()
    {
        $bookings = Booking::with(['package'])
            ->where('user_id', auth()->id())
            ->latest()
            ->paginate(10);

        return view('tourist.bookings', compact('bookings'));
    }

    public function show(Booking $booking)
    {
        // Check if the booking belongs to the authenticated user
        if ($booking->user_id !== auth()->id()) {
            return redirect()->route('tourist.bookings')
                           ->with('error', 'Unauthorized access to booking.');
        }

        return view('tourist.bookings.show', compact('booking'));
    }

    public function store(Request $request)
    {
        // Validate request
        $validated = $request->validate([
            'package_id' => 'required|exists:tour_packages,package_id',
            'number_of_travelers' => 'required|integer|min:1',
            'start_date' => 'required|date',
            'special_requests' => 'nullable|string'
        ]);

        try {
            DB::beginTransaction();
            
            // Get package details
            $package = TourPackage::where('package_id', $request->package_id)->first();
            if (!$package) {
                return back()->with('error', 'Package not found.');
            }

            // Calculate total price
            $totalPrice = $package->price * $request->number_of_travelers;

            // Create booking using Eloquent model
            $booking = Booking::create([
                'user_id' => auth()->id(),
                'package_id' => $request->package_id,
                'number_of_travelers' => $request->number_of_travelers,
                'total_price' => $totalPrice,
                'booking_date' => now(),
                'start_date' => $request->start_date,
                'status' => 'Pending',
                'payment_status' => 'Unpaid',
                'special_requests' => $request->special_requests
            ]);

            // Update available slots
            $package->decrement('total_available_slots', $request->number_of_travelers);
            
            DB::commit();

            return redirect()->route('tourist.bookings')
                ->with('success', 'Booking created successfully! Please proceed with payment.');

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Booking Error: ' . $e->getMessage());
            // Return the actual error message for debugging
            return back()->with('error', 'Error: ' . $e->getMessage())
                        ->withInput();
        }
    }

    public function create(Request $request)
    {
        $packageId = $request->query('package');
        $package = TourPackage::find($packageId); // Fetch the package by ID

        if (!$package) {
            return redirect()->route('tourist.dashboard')->with('error', 'Package not found.');
        }

        return view('tourist.bookings.create', compact('package'));
    }

    public function cancel($booking_id)
    {
        try {
            // Use booking_id instead of id and get the complete model
            $booking = Booking::where('booking_id', $booking_id)->first();
            
            if (!$booking) {
                return redirect()->route('tourist.bookings')->with('error', 'Booking not found');
            }
            
            // Verify that the current user owns this booking
            if ($booking->user_id !== auth()->id()) {
                return redirect()->route('tourist.bookings')->with('error', 'Unauthorized action');
            }
            
            // Begin transaction
            DB::beginTransaction();
            
            // Update the model first
            $booking->status = 'Cancelled';
            $booking->save();
            
            // Return available slots to the package (using the model we retrieved)
            if ($booking->package) {
                $booking->package->increment('total_available_slots', $booking->number_of_travelers);
            }
            
            DB::commit();
            
            return redirect()->route('tourist.bookings')->with('success', 'Booking cancelled successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Booking Cancellation Error: ' . $e->getMessage());
            return redirect()->route('tourist.bookings')->with('error', 'Error: ' . $e->getMessage());
        }
    }

    public function showBookingForm(TourPackage $package)
    {
        if (!$package->is_active || $package->total_available_slots <= 0) {
            return redirect()->route('tourist.bookings.create')
                           ->with('error', 'This package is not available for booking.');
        }

        return view('tourist.bookings.create', compact('package'));
    }

    public function downloadPdf(Booking $booking)
    {
        // Verify the booking belongs to the authenticated user
        if ($booking->user_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

        try {
            // Generate PDF
            $pdfContent = $this->pdfService->generateBookingPdf($booking);

            // Return the PDF as a download response
            return response($pdfContent)
                ->header('Content-Type', 'application/pdf')
                ->header('Content-Disposition', 'attachment; filename="booking_confirmation_' . $booking->id . '.pdf"');
        } catch (\Exception $e) {
            \Log::error('PDF Generation Error: ' . $e->getMessage());
            return redirect()->route('tourist.bookings.show', $booking)
                           ->with('error', 'Failed to generate PDF: ' . $e->getMessage());
        }
    }
}