@extends('layouts.app')

@section('content')
<div class="container py-5">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h2 mb-0">My Bookings</h1>
        <a href="{{ route('tourist.packages') }}" class="btn btn-primary">
            <i class="fas fa-plus me-2"></i>Book New Package
        </a>
    </div>

    <!-- Success Message -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
            <i class="fas fa-check-circle me-2"></i>
            {!! session('success') !!}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Error Message -->
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
            <i class="fas fa-exclamation-circle me-2"></i>
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Empty State -->
    @if($bookings->isEmpty())
        <div class="card text-center p-5">
            <div class="card-body">
                <i class="fas fa-calendar-times fs-1 text-muted mb-3"></i>
                <h2 class="h4 mb-3">No Bookings Yet</h2>
                <p class="text-muted mb-4">Start exploring our amazing tour packages and make your first booking!</p>
                <a href="{{ route('tourist.packages') }}" class="btn btn-primary">
                    Browse Packages
                </a>
            </div>
        </div>
    @else
        <!-- Bookings Table -->
        <div class="card">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Package</th>
                            <th>Start Date</th>
                            <th>People</th>
                            <th>Total Price</th>
                            <th>Payment Status</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($bookings as $booking)
                            <tr>
                                <td>
                                    <div class="ms-3">
                                        <div class="fw-bold">{{ $booking->package->name ?? 'N/A' }}</div>
                                        <div class="text-muted small">{{ $booking->package->destination->name ?? 'N/A' }}</div>
                                    </div>
                                </td>
                                <td class="align-middle">
                                    {{ $booking->start_date ? $booking->start_date->format('M d, Y') : 'N/A' }}
                                </td>
                                <td class="align-middle">
                                    {{ $booking->number_of_travelers ?? 'N/A' }}
                                </td>
                                <td class="align-middle">
                                    ${{ number_format($booking->total_price ?? 0, 2) }}
                                </td>
                                <td class="align-middle">
                                    @if($booking->payment_status === 'Paid')
                                        <span class="badge bg-success">
                                            <i class="fas fa-check-circle me-1"></i> Paid
                                        </span>
                                    @else
                                        <span class="badge bg-warning">
                                            <i class="fas fa-clock me-1"></i> Pending
                                        </span>
                                    @endif
                                </td>
                                <td class="align-middle">
                                    <span class="badge rounded-pill 
                                        @if($booking->status === 'Confirmed') bg-success
                                        @elseif($booking->status === 'Pending') bg-warning
                                        @elseif($booking->status === 'Cancelled') bg-danger
                                        @else bg-secondary @endif">
                                        {{ ucfirst($booking->status ?? 'N/A') }}
                                    </span>
                                </td>
                                <td class="align-middle">
                                    <div class="d-flex flex-column gap-2">
                                        <a href="{{ route('tourist.bookings.show', $booking) }}" class="btn btn-info btn-sm">
                                            <i class="fas fa-eye me-1"></i>View Details
                                        </a>
                                        @if($booking->payment_status !== 'Paid' && $booking->status !== 'Cancelled')
                                            <button type="button" 
                                                    class="btn btn-primary btn-sm"
                                                    data-bs-toggle="modal" 
                                                    data-bs-target="#paymentModal{{ $booking->booking_id }}">
                                                <i class="fas fa-credit-card me-1"></i>Proceed to Payment
                                            </button>
                                        @endif
                                        @if($booking->status !== 'Cancelled')
                                            <form action="{{ route('tourist.bookings.cancel', ['booking_id' => $booking->booking_id]) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to cancel this booking?');">
                                                    <i class="fas fa-times me-2"></i>Cancel Booking
                                                </button>
                                            </form>
                                        @endif
                                        @if($booking->status === 'Cancelled')
                                            <span class="text-muted"><i class="fas fa-ban me-1"></i>Booking Cancelled</span>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Pagination -->
        <div class="d-flex justify-content-center mt-4">
            {{ $bookings->links() }}
        </div>
    @endif
</div>

<!-- Payment Modal -->
@foreach($bookings as $booking)
    <div class="modal fade" id="paymentModal{{ $booking->booking_id }}" data-bs-backdrop="static" tabindex="-1" aria-labelledby="paymentModalLabel{{ $booking->booking_id }}" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="paymentModalLabel{{ $booking->booking_id }}">
                        <i class="fas fa-credit-card me-2"></i>Payment Details
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('tourist.payments.store') }}" method="POST" class="payment-form">
                    @csrf
                    <input type="hidden" name="booking_id" value="{{ $booking->booking_id }}">
                    <input type="hidden" name="amount" value="{{ $booking->total_price }}">

                    <!-- For debugging -->
                    @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif

                    <div class="modal-body">
                        <!-- Booking Summary -->
                        <div class="card bg-light mb-4">
                            <div class="card-body">
                                <h6 class="card-title text-primary mb-3">Booking Summary</h6>
                                <div class="row g-3">
                                    <div class="col-6">
                                        <p class="text-muted mb-1">Package</p>
                                        <p class="fw-bold">{{ $booking->package->name }}</p>
                                    </div>
                                    <div class="col-6">
                                        <p class="text-muted mb-1">Total Amount</p>
                                        <p class="fw-bold text-primary">${{ number_format($booking->total_price, 2) }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Payment Method Selection -->
                        <div class="mb-4">
                            <label class="form-label fw-bold">Select Payment Method</label>
                            <select name="payment_method" id="payment_method_{{ $booking->booking_id }}" class="form-select" required>
                                <option value="">Choose payment method</option>
                                <option value="credit_card">Credit Card</option>
                                <option value="paypal">PayPal</option>
                                <option value="bank_transfer">Bank Transfer</option>
                            </select>
                        </div>

                        <div id="payment_details_{{ $booking->booking_id }}" class="border rounded p-3 mt-3 mb-3" style="display: none;">
                            <div class="payment-method-title mb-3 fw-bold">Payment Details</div>
                            
                            <!-- Credit Card Fields -->
                            <div id="credit_card_fields_{{ $booking->booking_id }}" style="display: none;">
                                <div class="mb-3">
                                    <label class="form-label">Card Number</label>
                                    <input type="text" class="form-control" name="card_number" placeholder="1234 5678 9012 3456">
                                </div>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Expiry Date</label>
                                        <input type="text" class="form-control" name="expiry_date" placeholder="MM/YY">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">CVV</label>
                                        <input type="text" class="form-control" name="cvv" placeholder="123">
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Cardholder Name</label>
                                    <input type="text" class="form-control" name="cardholder_name" placeholder="John Doe">
                                </div>
                            </div>
                            
                            <!-- PayPal Fields -->
                            <div id="paypal_fields_{{ $booking->booking_id }}" style="display: none;">
                                <div class="alert alert-info">
                                    <i class="fab fa-paypal me-2"></i>
                                    You'll be redirected to PayPal to complete your payment securely.
                                </div>
                            </div>
                            
                            <!-- Bank Transfer Fields -->
                            <div id="bank_transfer_fields_{{ $booking->booking_id }}" style="display: none;">
                                <div class="alert alert-info">
                                    <i class="fas fa-university me-2"></i>
                                    Please use the following bank details for your transfer:
                                    <hr>
                                    <strong>Bank Name:</strong> International Bank<br>
                                    <strong>Account Number:</strong> 1234567890<br>
                                    <strong>SWIFT/BIC:</strong> INTLBNK123<br>
                                    <strong>Reference:</strong> Booking #{{ $booking->booking_id }}
                                </div>
                                <div class="form-check mt-3">
                                    <input class="form-check-input" type="checkbox" id="confirmTransfer{{ $booking->booking_id }}" name="confirm_transfer">
                                    <label class="form-check-label" for="confirmTransfer{{ $booking->booking_id }}">
                                        I confirm that I will make this bank transfer within 24 hours
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary confirm-payment">
                            <i class="fas fa-lock me-2"></i>Confirm Payment
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endforeach

@push('styles')
<style>
    .modal-content {
        border: none;
        border-radius: 0.5rem;
    }
    
    .modal-header {
        border-bottom: 1px solid #dee2e6;
    }
    
    .modal-footer {
        border-top: 1px solid #dee2e6;
    }
    
    .form-control, .form-select {
        border-radius: 0.375rem;
        padding: 0.5rem 1rem;
    }
    
    .payment-details {
        transition: all 0.3s ease;
    }

    .table td {
        vertical-align: middle;
    }

    .btn-sm {
        padding: 0.25rem 0.5rem;
        font-size: 0.875rem;
    }
</style>
@endpush

@push('scripts')
<script>
// Simplified payment form handling
document.addEventListener('DOMContentLoaded', function() {
    console.log('Payment scripts loaded! Version 2.0');
    
    // Test function to check if payment elements exist
    function testPaymentElements() {
        document.querySelectorAll('.payment-form').forEach(function(form) {
            const bookingId = form.querySelector('input[name="booking_id"]').value;
            console.log('Testing payment elements for booking ID:', bookingId);
            
            const paymentDetails = document.getElementById('payment_details_' + bookingId);
            const creditCardFields = document.getElementById('credit_card_fields_' + bookingId);
            const paypalFields = document.getElementById('paypal_fields_' + bookingId);
            const bankTransferFields = document.getElementById('bank_transfer_fields_' + bookingId);
            
            console.log('Payment details element exists:', !!paymentDetails);
            console.log('Credit card fields element exists:', !!creditCardFields);
            console.log('PayPal fields element exists:', !!paypalFields);
            console.log('Bank transfer fields element exists:', !!bankTransferFields);
            
            // Try to force show the payment details and credit card fields
            if (paymentDetails) {
                paymentDetails.style.display = 'block';
                console.log('Forced display of payment details');
            }
            
            if (creditCardFields) {
                creditCardFields.style.display = 'block';
                console.log('Forced display of credit card fields');
            }
        });
    }
    
    // Remove this line in production - it's just for debugging
    // setTimeout(testPaymentElements, 2000);
    
    // Add manual trigger to display payment fields (for testing)
    function showPaymentFields() {
        const selects = document.querySelectorAll('select[name="payment_method"]');
        console.log('Found ' + selects.length + ' payment method selectors');
        
        selects.forEach(function(select) {
            select.addEventListener('change', handlePaymentMethodChange);
        });
    }
    
    function handlePaymentMethodChange(event) {
        const select = event.target;
        const selectedMethod = select.value;
        console.log('Payment method changed to: ' + selectedMethod);
        
        // Find the form that contains this select
        const form = select.closest('form');
        console.log('Found form:', form);
        
        if (!form) {
            console.error('Could not find form for payment method select');
            return;
        }
        
        // Get the booking ID from a hidden input
        const bookingId = form.querySelector('input[name="booking_id"]').value;
        console.log('Booking ID from form:', bookingId);
        
        // Find all the payment detail sections directly by ID
        const paymentDetails = document.getElementById('payment_details_' + bookingId);
        const creditCardFields = document.getElementById('credit_card_fields_' + bookingId);
        const paypalFields = document.getElementById('paypal_fields_' + bookingId);
        const bankTransferFields = document.getElementById('bank_transfer_fields_' + bookingId);
        const submitButton = form.querySelector('button[type="submit"]');
        
        console.log('Payment details:', paymentDetails);
        console.log('Credit card fields:', creditCardFields);
        console.log('PayPal fields:', paypalFields);
        console.log('Bank transfer fields:', bankTransferFields);
        
        // Hide all payment fields first
        if (paymentDetails) {
            paymentDetails.style.display = 'none';
        }
        
        if (creditCardFields) {
            creditCardFields.style.display = 'none';
        }
        
        if (paypalFields) {
            paypalFields.style.display = 'none';
        }
        
        if (bankTransferFields) {
            bankTransferFields.style.display = 'none';
        }
        
        // Disable submit button if no payment method selected
        if (submitButton) {
            submitButton.disabled = !selectedMethod;
        }
        
        // If a payment method is selected, show the appropriate fields
        if (selectedMethod) {
            // First show the payment details container
            if (paymentDetails) {
                console.log('Showing payment details');
                paymentDetails.style.display = 'block';
            }
            
            // Then show the specific payment method fields
            if (selectedMethod === 'credit_card' && creditCardFields) {
                console.log('Showing credit card fields');
                creditCardFields.style.display = 'block';
            } else if (selectedMethod === 'paypal' && paypalFields) {
                console.log('Showing PayPal fields');
                paypalFields.style.display = 'block';
            } else if (selectedMethod === 'bank_transfer' && bankTransferFields) {
                console.log('Showing bank transfer fields');
                bankTransferFields.style.display = 'block';
            }
        }
    }
    
    // Initialize the payment form handlers
    showPaymentFields();
    
    // Form submission handling
    document.querySelectorAll('.payment-form').forEach(function(form) {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const paymentMethod = this.querySelector('select[name="payment_method"]').value;
            
            if (!paymentMethod) {
                alert('Please select a payment method');
                return;
            }
            
            // Validate based on payment method
            if (paymentMethod === 'credit_card') {
                const cardNumber = this.querySelector('input[name="card_number"]').value.trim();
                const expiryDate = this.querySelector('input[name="expiry_date"]').value.trim();
                const cvv = this.querySelector('input[name="cvv"]').value.trim();
                const cardholderName = this.querySelector('input[name="cardholder_name"]').value.trim();
                
                if (!cardNumber || !expiryDate || !cvv || !cardholderName) {
                    alert('Please fill in all credit card details');
                    return;
                }
            } else if (paymentMethod === 'bank_transfer') {
                const confirmTransfer = this.querySelector('input[name="confirm_transfer"]');
                if (confirmTransfer && !confirmTransfer.checked) {
                    alert('Please confirm you will make the bank transfer');
                    return;
                }
            }
            
            // Show processing state
            const submitButton = this.querySelector('button[type="submit"]');
            if (submitButton) {
                submitButton.disabled = true;
                submitButton.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Processing Payment...';
            }
            
            // Special handling for PayPal
            if (paymentMethod === 'paypal') {
                alert('You would now be redirected to PayPal to complete your payment securely.');
                setTimeout(() => this.submit(), 1500);
            } else {
                this.submit();
            }
        });
    });
});
</script>
@endpush
@endsection