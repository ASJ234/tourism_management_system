<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Services\BookingPdfService;
use Illuminate\Http\Request;

class BookingPdfController extends Controller
{
    protected $pdfService;

    public function __construct(BookingPdfService $pdfService)
    {
        $this->pdfService = $pdfService;
    }

    public function download(Booking $booking)
    {
        // Check if the booking belongs to the authenticated user
        if (auth()->id() !== $booking->user_id) {
            abort(403, 'Unauthorized access');
        }

        // Check if the booking is paid
        if ($booking->status !== 'paid') {
            return redirect()->back()->with('error', 'PDF is only available for paid bookings');
        }

        $pdf = $this->pdfService->generateBookingPdf($booking);
        
        return response($pdf->Output('booking_confirmation.pdf', 'S'), 200)
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'attachment; filename="booking_confirmation.pdf"');
    }
} 