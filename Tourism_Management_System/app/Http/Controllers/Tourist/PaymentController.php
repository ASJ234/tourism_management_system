<?php
namespace App\Http\Controllers\Tourist;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PaymentController extends Controller
{
    public function store(Request $request)
    {
        try {
            $request->validate([
                'booking_id' => 'required|exists:bookings,booking_id',
                'amount' => 'required|numeric|min:0',
                'payment_method' => 'required|string'
            ]);
            
            // Log incoming request data for debugging
            \Log::info('Payment request received:', $request->all());
            
            // Map payment method values to match database enum values
            $paymentMethodMap = [
                'credit_card' => 'Credit Card',
                'paypal' => 'PayPal',
                'bank_transfer' => 'Bank Transfer'
            ];
            
            // Get the correct payment method value
            $paymentMethod = $paymentMethodMap[$request->payment_method] ?? $request->payment_method;
            
            // Check if booking belongs to authenticated user
            $booking = Booking::where('booking_id', $request->booking_id)->first();
            
            if (!$booking) {
                \Log::error('Booking not found: ' . $request->booking_id);
                return redirect()->route('tourist.bookings')
                    ->with('error', 'Booking not found. Please try again.');
            }
            
            if ($booking->user_id !== auth()->id()) {
                \Log::error('Unauthorized booking access attempt: User ' . auth()->id() . ' tried to access booking ' . $booking->booking_id);
                abort(403, 'You do not have permission to process payment for this booking.');
            }
            
            // Begin transaction to ensure all updates happen together
            \DB::beginTransaction();
            
            try {
                // For development/testing, we'll simulate a successful payment
                // In production, you would integrate with a real payment gateway
                $isSuccessful = true;
                $transactionId = 'TXN_' . Str::random(12); // Generate a mock transaction ID
                
                if ($isSuccessful) {
                    // Create payment record
                    $payment = Payment::create([
                        'booking_id' => $request->booking_id,
                        'amount' => $request->amount,
                        'payment_method' => $paymentMethod,
                        'transaction_id' => $transactionId,
                        'status' => 'Successful',
                        'payment_date' => now(),
                    ]);
                    
                    \Log::info('Payment created:', ['id' => $payment->id, 'transaction_id' => $transactionId]);
                    
                    // Update booking status to confirmed
                    $booking->update([
                        'status' => 'Confirmed',
                        'payment_status' => 'Paid'
                    ]);
                    
                    \Log::info('Booking updated:', [
                        'booking_id' => $booking->booking_id,
                        'status' => 'Confirmed',
                        'payment_status' => 'Paid',
                        'total_price' => $booking->total_price
                    ]);
                    
                    // Commit the transaction
                    \DB::commit();
                    
                    return redirect()->route('tourist.bookings')
                        ->with('success', 'Payment processed successfully! Your booking has been confirmed.');
                } else {
                    throw new \Exception('Payment processing failed.');
                }
                
            } catch (\Exception $e) {
                \DB::rollback();
                \Log::error('Payment processing error: ' . $e->getMessage());
                return redirect()->route('tourist.bookings')
                    ->with('error', 'Payment processing failed: ' . $e->getMessage());
            }
        } catch (\Exception $e) {
            \Log::error('Payment validation error: ' . $e->getMessage());
            return redirect()->route('tourist.bookings')
                ->with('error', 'Payment validation failed: ' . $e->getMessage());
        }
    }
}