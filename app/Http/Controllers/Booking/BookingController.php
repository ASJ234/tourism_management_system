<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    public function show(Booking $booking)
    {
        // Add authorization if needed
        // $this->authorize('view', $booking);
        
        return view('tourist.bookings.show', compact('booking'));
    }

    public function cancel(Booking $booking)
    {
        // Add authorization if needed
        // $this->authorize('cancel', $booking);
        
        if ($booking->status === 'pending') {
            $booking->update(['status' => 'cancelled']);
            return redirect()->route('tourist.bookings')
                            ->with('success', 'Booking has been cancelled successfully.');
        }
        
        return back()->with('error', 'This booking cannot be cancelled.');
    }
} 