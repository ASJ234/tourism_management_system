@extends('layouts.app')

@section('styles')
<style>
    .booking-details-container {
        max-width: 1000px;
        margin: 2rem auto;
        padding: 0 1rem;
    }

    .booking-header {
        background: linear-gradient(120deg, #2563eb, #4f46e5);
        border-radius: 1rem;
        padding: 2rem;
        margin-bottom: 2rem;
        color: white;
        position: relative;
        overflow: hidden;
    }

    .booking-status {
        position: absolute;
        top: 1rem;
        right: 1rem;
        padding: 0.5rem 1rem;
        border-radius: 9999px;
        font-weight: 500;
        font-size: 0.875rem;
    }

    .status-pending {
        background-color: #fef3c7;
        color: #92400e;
    }

    .status-confirmed {
        background-color: #d1fae5;
        color: #065f46;
    }

    .status-completed {
        background-color: #dbeafe;
        color: #1e40af;
    }

    .status-cancelled {
        background-color: #fee2e2;
        color: #991b1b;
    }

    .booking-id {
        font-size: 0.875rem;
        opacity: 0.9;
        margin-bottom: 0.5rem;
    }

    .booking-title {
        font-size: 1.875rem;
        font-weight: 700;
        margin-bottom: 1rem;
    }

    .booking-meta {
        display: flex;
        gap: 2rem;
        font-size: 0.875rem;
    }

    .meta-item {
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .details-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2rem;
    }

    .detail-card {
        background: white;
        border-radius: 1rem;
        padding: 1.5rem;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        border: 1px solid #e5e7eb;
    }

    .card-title {
        font-size: 1rem;
        font-weight: 600;
        color: #374151;
        margin-bottom: 1rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .card-title i {
        color: #2563eb;
    }

    .info-grid {
        display: grid;
        grid-template-columns: auto 1fr;
        gap: 0.75rem;
        align-items: baseline;
    }

    .info-label {
        color: #6b7280;
        font-size: 0.875rem;
    }

    .info-value {
        color: #111827;
        font-size: 0.875rem;
    }

    .price-breakdown {
        background: #f8fafc;
        border-radius: 0.5rem;
        padding: 1rem;
        margin-top: 1rem;
    }

    .price-item {
        display: flex;
        justify-content: space-between;
        margin-bottom: 0.5rem;
    }

    .price-total {
        border-top: 1px solid #e5e7eb;
        margin-top: 0.5rem;
        padding-top: 0.5rem;
        font-weight: 600;
    }

    .action-buttons {
        display: flex;
        gap: 1rem;
        margin-top: 2rem;
    }

    .btn {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.75rem 1.5rem;
        border-radius: 0.5rem;
        font-size: 0.875rem;
        font-weight: 500;
        text-decoration: none;
        transition: all 0.2s;
    }

    .btn-primary {
        background-color: #2563eb;
        color: white;
        border: none;
    }

    .btn-primary:hover {
        background-color: #1d4ed8;
    }

    .btn-secondary {
        background-color: #f3f4f6;
        color: #374151;
        border: 1px solid #e5e7eb;
    }

    .btn-secondary:hover {
        background-color: #e5e7eb;
    }

    .btn-danger {
        background-color: #fee2e2;
        color: #dc2626;
        border: 1px solid #fecaca;
    }

    .btn-danger:hover {
        background-color: #fecaca;
    }

    .package-inclusions {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1rem;
        margin-top: 1rem;
    }

    .inclusion-item {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 0.875rem;
        color: #374151;
    }

    .inclusion-item i {
        color: #059669;
    }

    @media (max-width: 768px) {
        .booking-meta {
            flex-direction: column;
            gap: 1rem;
        }

        .action-buttons {
            flex-direction: column;
        }

        .btn {
            width: 100%;
            justify-content: center;
        }
    }
</style>
@endsection

@section('content')
<div class="booking-details-container">
    <div class="booking-header">
        <span class="booking-status status-{{ strtolower($booking->status) }}">
            {{ $booking->status }}
        </span>
        <div class="booking-id">Booking ID: #{{ $booking->id }}</div>
        <h1 class="booking-title">{{ $booking->package->name }}</h1>
        <div class="booking-meta">
            <div class="meta-item">
                <i class="fas fa-calendar"></i>
                <span>Booked on {{ $booking->created_at->format('M d, Y') }}</span>
            </div>
            <div class="meta-item">
                <i class="fas fa-clock"></i>
                <span>{{ $booking->package->duration_days }} Days</span>
            </div>
            <div class="meta-item">
                <i class="fas fa-users"></i>
                <span>{{ $booking->number_of_travelers }} Travelers</span>
            </div>
        </div>
    </div>

    <div class="details-grid">
        <div class="detail-card">
            <h2 class="card-title">
                <i class="fas fa-suitcase"></i>
                Package Details
            </h2>
            <div class="info-grid">
                <div class="info-label">Destination:</div>
                <div class="info-value">{{ $booking->package->destination->name }}</div>
                
                <div class="info-label">Start Date:</div>
                <div class="info-value">{{ $booking->start_date->format('M d, Y') }}</div>
                
                <div class="info-label">End Date:</div>
                <div class="info-value">{{ $booking->start_date->addDays($booking->package->duration_days - 1)->format('M d, Y') }}</div>
                
                <div class="info-label">Duration:</div>
                <div class="info-value">{{ $booking->package->duration_days }} Days</div>
            </div>
        </div>

        <div class="detail-card">
            <h2 class="card-title">
                <i class="fas fa-dollar-sign"></i>
                Payment Information
            </h2>
            <div class="info-grid">
                <div class="info-label">Payment Status:</div>
                <div class="info-value">{{ $booking->payment_status }}</div>
            </div>
            <div class="price-breakdown">
                <div class="price-item">
                    <span>Package Price (per person)</span>
                    <span>${{ number_format($booking->package->price, 2) }}</span>
                </div>
                <div class="price-item">
                    <span>Number of Travelers</span>
                    <span>× {{ $booking->number_of_travelers }}</span>
                </div>
                <div class="price-item price-total">
                    <span>Total Amount</span>
                    <span>${{ number_format($booking->total_price, 2) }}</span>
                </div>
            </div>
        </div>
    </div>

    <div class="detail-card">
        <h2 class="card-title">
            <i class="fas fa-list-check"></i>
            Package Inclusions
        </h2>
        <div class="package-inclusions">
            @foreach($booking->package->inclusions as $inclusion)
                <div class="inclusion-item">
                    <i class="fas fa-check-circle"></i>
                    <span>{{ $inclusion->name }}</span>
                </div>
            @endforeach
        </div>
    </div>

    @if($booking->special_requests)
        <div class="detail-card">
            <h2 class="card-title">
                <i class="fas fa-comment-alt"></i>
                Special Requests
            </h2>
            <p class="info-value">{{ $booking->special_requests }}</p>
        </div>
    @endif

    <div class="action-buttons">
        <a href="{{ route('tourist.bookings') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i>
            Back to Bookings
        </a>
        
        @if($booking->status === 'Pending' || $booking->status === 'Confirmed')
            <form action="{{ route('tourist.bookings.cancel', $booking) }}" method="POST" class="d-inline">
                @csrf
                @method('PATCH')
                <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to cancel this booking?')">
                    <i class="fas fa-times"></i>
                    Cancel Booking
                </button>
            </form>
        @endif
    </div>
</div>
@endsection 