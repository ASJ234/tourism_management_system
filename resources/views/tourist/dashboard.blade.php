@extends('layouts.app')

@section('styles')
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
<style>
    :root {
        --primary-color: #2563eb;
        --primary-dark: #1e40af;
        --secondary-color: #4f46e5;
        --success-color: #059669;
        --warning-color: #d97706;
        --danger-color: #dc2626;
        --background-color: #f8fafc;
        --card-bg: #ffffff;
        --text-primary: #1f2937;
        --text-secondary: #6b7280;
        --border-color: #e5e7eb;
    }

    .dashboard-container {
        background-color: var(--background-color);
        min-height: 100vh;
        padding: 2rem;
    }

    .welcome-banner {
        background: linear-gradient(120deg, #2563eb, #4f46e5);
        border-radius: 1.5rem;
        padding: 3rem;
        margin-bottom: 2.5rem;
        position: relative;
        overflow: hidden;
        box-shadow: 0 10px 20px rgba(37, 99, 235, 0.1);
    }

    .welcome-banner::before {
        content: '';
        position: absolute;
        top: 0;
        right: 0;
        width: 300px;
        height: 100%;
        background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><circle cx="50" cy="50" r="40" fill="rgba(255,255,255,0.1)"/></svg>') no-repeat center center;
        opacity: 0.6;
    }

    .welcome-banner h1 {
        font-size: 2.5rem;
        font-weight: 800;
        color: white;
        margin-bottom: 1rem;
        text-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }

    .welcome-banner p {
        font-size: 1.25rem;
        color: rgba(255,255,255,0.9);
        max-width: 600px;
        line-height: 1.6;
    }

    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2.5rem;
    }

    .stat-card {
        background: var(--card-bg);
        border-radius: 1rem;
        padding: 1.5rem;
        display: flex;
        align-items: center;
        transition: all 0.3s ease;
        border: 1px solid var(--border-color);
    }

    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.05);
        border-color: var(--primary-color);
    }

    .stat-icon {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 1rem;
        font-size: 1.5rem;
    }

    .stat-icon.blue {
        background: rgba(37, 99, 235, 0.1);
        color: var(--primary-color);
    }

    .stat-icon.purple {
        background: rgba(79, 70, 229, 0.1);
        color: var(--secondary-color);
    }

    .stat-icon.green {
        background: rgba(5, 150, 105, 0.1);
        color: var(--success-color);
    }

    .stat-content {
        flex: 1;
    }

    .stat-number {
        font-size: 1.875rem;
        font-weight: 700;
        color: var(--text-primary);
        line-height: 1.2;
    }

    .stat-label {
        font-size: 0.875rem;
        color: var(--text-secondary);
        margin-top: 0.25rem;
    }

    .quick-actions {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2.5rem;
    }

    .action-card {
        background: var(--card-bg);
        border-radius: 1rem;
        padding: 2rem;
        text-align: left;
        transition: all 0.3s ease;
        border: 1px solid var(--border-color);
        display: flex;
        align-items: center;
        text-decoration: none;
    }

    .action-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.05);
        border-color: var(--primary-color);
    }

    .action-icon {
        width: 56px;
        height: 56px;
        border-radius: 16px;
        background: rgba(37, 99, 235, 0.1);
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 1.5rem;
    }

    .action-icon i {
        font-size: 1.75rem;
        color: var(--primary-color);
    }

    .action-content h3 {
        font-size: 1.25rem;
        font-weight: 600;
        color: var(--text-primary);
        margin-bottom: 0.5rem;
    }

    .action-content p {
        font-size: 0.875rem;
        color: var(--text-secondary);
        line-height: 1.5;
    }

    .main-content {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
        gap: 1.5rem;
    }

    .content-card {
        background: var(--card-bg);
        border-radius: 1rem;
        border: 1px solid var(--border-color);
        overflow: hidden;
    }

    .content-card-header {
        padding: 1.5rem;
        border-bottom: 1px solid var(--border-color);
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .content-card-header h2 {
        font-size: 1.25rem;
        font-weight: 600;
        color: var(--text-primary);
        margin: 0;
    }

    .content-card-body {
        padding: 1.5rem;
    }

    .booking-list, .review-list {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .booking-item, .review-item {
        padding: 1.25rem;
        border-bottom: 1px solid var(--border-color);
        display: flex;
        align-items: flex-start;
        gap: 1rem;
    }

    .booking-item:last-child, .review-item:last-child {
        border-bottom: none;
    }

    .item-icon {
        width: 40px;
        height: 40px;
        border-radius: 10px;
        background: rgba(37, 99, 235, 0.1);
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .item-icon i {
        font-size: 1.25rem;
        color: var(--primary-color);
    }

    .item-details {
        flex: 1;
    }

    .item-details h4 {
        font-size: 1rem;
        font-weight: 600;
        color: var(--text-primary);
        margin-bottom: 0.5rem;
    }

    .item-details p {
        font-size: 0.875rem;
        color: var(--text-secondary);
        margin-bottom: 0.5rem;
    }

    .status-badge {
        display: inline-flex;
        align-items: center;
        padding: 0.375rem 0.75rem;
        border-radius: 9999px;
        font-size: 0.75rem;
        font-weight: 500;
        gap: 0.375rem;
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

    .star-rating {
        color: #f59e0b;
        font-size: 1rem;
        margin-bottom: 0.5rem;
    }

    .btn {
        display: inline-flex;
        align-items: center;
        padding: 0.625rem 1.25rem;
        border-radius: 0.5rem;
        font-size: 0.875rem;
        font-weight: 500;
        text-decoration: none;
        transition: all 0.2s;
        gap: 0.5rem;
    }

    .btn-primary {
        background-color: var(--primary-color);
        color: white;
    }

    .btn-primary:hover {
        background-color: var(--primary-dark);
    }

    .btn-secondary {
        background-color: #f3f4f6;
        color: var(--text-primary);
    }

    .btn-secondary:hover {
        background-color: #e5e7eb;
    }

    .empty-state {
        text-align: center;
        padding: 3rem 2rem;
    }

    .empty-state i {
        font-size: 3rem;
        color: #d1d5db;
        margin-bottom: 1.5rem;
    }

    .empty-state p {
        font-size: 1rem;
        color: var(--text-secondary);
        margin-bottom: 1.5rem;
    }

    @media (max-width: 768px) {
        .dashboard-container {
            padding: 1rem;
        }

        .welcome-banner {
            padding: 2rem;
        }

        .welcome-banner h1 {
            font-size: 2rem;
        }

        .stat-card {
            padding: 1.25rem;
        }

        .action-card {
            padding: 1.5rem;
        }
    }
</style>
@endsection

@section('content')
<div class="dashboard-container">
    <div class="welcome-banner">
        <h1>Welcome back, {{ auth()->user()->full_name }}! 👋</h1>
        <p>Ready to explore new destinations? Browse our curated collection of amazing travel experiences and plan your next unforgettable journey.</p>
    </div>

    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon blue">
                <i class="fas fa-suitcase"></i>
            </div>
            <div class="stat-content">
                <div class="stat-number">{{ $totalBookings }}</div>
                <div class="stat-label">Total Bookings</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon purple">
                <i class="fas fa-star"></i>
            </div>
            <div class="stat-content">
                <div class="stat-number">{{ $recentReviews->count() }}</div>
                <div class="stat-label">Reviews Given</div>
            </div>
        </div>
    </div>

    <div class="quick-actions">
        <a href="{{ route('tourist.destinations.index') }}" class="action-card">
            <div class="action-icon">
                <i class="fas fa-map-marked-alt"></i>
            </div>
            <div class="action-content">
                <h3>Browse Destinations</h3>
                <p>Discover amazing places around the world</p>
            </div>
        </a>
        <a href="{{ route('tourist.packages') }}" class="action-card">
            <div class="action-icon">
                <i class="fas fa-suitcase-rolling"></i>
            </div>
            <div class="action-content">
                <h3>Tour Packages</h3>
                <p>Find the perfect package for your trip</p>
            </div>
        </a>
        <a href="{{ route('tourist.packages') }}" class="action-card">
            <div class="action-icon">
                <i class="fas fa-calendar-plus"></i>
            </div>
            <div class="action-content">
                <h3>New Booking</h3>
                <p>Start planning your next adventure</p>
            </div>
        </a>
    </div>

    <div class="main-content">
        <div class="content-card">
            <div class="content-card-header">
                <h2>Recent Bookings</h2>
                @if($recentBookings->count() > 0)
                    <a href="{{ route('tourist.bookings.index') }}" class="btn btn-secondary">View All</a>
                @endif
            </div>
            <div class="content-card-body">
                @if($recentBookings->count() > 0)
                    <ul class="booking-list">
                        @foreach($recentBookings as $booking)
                            <li class="booking-item">
                                <div class="item-icon">
                                    <i class="fas fa-suitcase"></i>
                                </div>
                                <div class="item-details">
                                    <h4>{{ $booking->package->name }}</h4>
                                    <p>{{ $booking->start_date->format('M d, Y') }}</p>
                                    <span class="status-badge status-{{ strtolower($booking->status) }}">
                                        <i class="fas fa-circle text-xs"></i>
                                        {{ $booking->status }}
                                    </span>
                                </div>
                                <a href="{{ route('tourist.bookings.show', $booking) }}" class="btn btn-primary">
                                    <i class="fas fa-eye"></i> View
                                </a>
                            </li>
                        @endforeach
                    </ul>
                @else
                    <div class="empty-state">
                        <i class="fas fa-calendar-times"></i>
                        <p>No bookings yet. Start by browsing our amazing tour packages!</p>
                        <a href="{{ route('tourist.packages') }}" class="btn btn-primary">
                            <i class="fas fa-plus me-2"></i>Browse Packages
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const cards = document.querySelectorAll('.stat-card, .action-card');
        
        cards.forEach(card => {
            card.addEventListener('mouseenter', function() {
                this.style.transform = 'translateY(-5px)';
                this.style.boxShadow = '0 10px 20px rgba(0,0,0,0.05)';
            });
            
            card.addEventListener('mouseleave', function() {
                this.style.transform = 'translateY(0)';
                this.style.boxShadow = 'none';
            });
        });
    });
</script>
@endsection