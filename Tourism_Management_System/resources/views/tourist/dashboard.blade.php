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
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        padding: 1rem;
        border-radius: 8px;
        margin-bottom: 1.5rem;
        border: 1px solid #dee2e6;
    }

    .welcome-banner h1 {
        color: #495057;
        font-weight: 600;
        margin: 0;
    }

    .welcome-banner p {
        font-size: 0.9rem;
        margin: 0;
    }

    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1rem;
        margin-bottom: 1.5rem;
    }

    .stat-card {
        background: var(--card-bg);
        border-radius: 8px;
        padding: 1rem;
        display: flex;
        align-items: center;
        transition: all 0.2s ease;
        border: 1px solid var(--border-color);
        box-shadow: 0 2px 4px rgba(0,0,0,0.02);
    }

    .stat-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 6px rgba(0,0,0,0.05);
        border-color: var(--primary-color);
    }

    .stat-icon {
        width: 40px;
        height: 40px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 0.75rem;
        font-size: 1.25rem;
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
        font-size: 1.25rem;
        font-weight: 600;
        color: var(--text-primary);
        line-height: 1.2;
    }

    .stat-label {
        font-size: 0.75rem;
        color: var(--text-secondary);
        margin-top: 0.125rem;
    }

    .quick-actions {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1rem;
        margin-bottom: 1.5rem;
    }

    .action-card {
        background: var(--card-bg);
        border-radius: 8px;
        padding: 1rem;
        text-align: left;
        transition: all 0.2s ease;
        border: 1px solid var(--border-color);
        display: flex;
        align-items: center;
        text-decoration: none;
        box-shadow: 0 2px 4px rgba(0,0,0,0.02);
    }

    .action-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 6px rgba(0,0,0,0.05);
        border-color: var(--primary-color);
    }

    .action-icon {
        width: 40px;
        height: 40px;
        border-radius: 8px;
        background: rgba(37, 99, 235, 0.1);
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 0.75rem;
        flex-shrink: 0;
    }

    .action-icon i {
        font-size: 1.25rem;
        color: var(--primary-color);
    }

    .action-content h3 {
        font-size: 0.875rem;
        font-weight: 600;
        color: var(--text-primary);
        margin-bottom: 0.25rem;
    }

    .action-content p {
        font-size: 0.75rem;
        color: var(--text-secondary);
        line-height: 1.4;
        margin: 0;
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

        .stats-grid,
        .quick-actions {
            grid-template-columns: 1fr;
        }

        .stat-card,
        .action-card {
            padding: 0.875rem;
        }
    }

    /* Recent Bookings Section */
    .recent-bookings {
        background: var(--card-bg);
        border-radius: 8px;
        border: 1px solid var(--border-color);
        overflow: hidden;
        margin-bottom: 1.5rem;
    }

    .bookings-header {
        padding: 0.75rem 1rem;
        border-bottom: 1px solid var(--border-color);
        display: flex;
        justify-content: space-between;
        align-items: center;
        background: #f8f9fa;
    }

    .bookings-header h3 {
        font-size: 0.875rem;
        font-weight: 600;
        color: var(--text-primary);
        margin: 0;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .bookings-header h3 i {
        color: var(--primary-color);
    }

    .bookings-list {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .booking-item {
        padding: 0.75rem 1rem;
        border-bottom: 1px solid var(--border-color);
        display: flex;
        align-items: center;
        gap: 0.75rem;
        transition: background-color 0.2s ease;
    }

    .booking-item:last-child {
        border-bottom: none;
    }

    .booking-item:hover {
        background-color: #f8f9fa;
    }

    .booking-icon {
        width: 32px;
        height: 32px;
        border-radius: 6px;
        background: rgba(37, 99, 235, 0.1);
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .booking-icon i {
        font-size: 1rem;
        color: var(--primary-color);
    }

    .booking-details {
        flex: 1;
        min-width: 0;
    }

    .booking-title {
        font-size: 0.875rem;
        font-weight: 500;
        color: var(--text-primary);
        margin-bottom: 0.25rem;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .booking-meta {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        font-size: 0.75rem;
        color: var(--text-secondary);
    }

    .booking-date {
        display: flex;
        align-items: center;
        gap: 0.25rem;
    }

    .booking-date i {
        font-size: 0.75rem;
    }

    .booking-status {
        display: inline-flex;
        align-items: center;
        padding: 0.25rem 0.5rem;
        border-radius: 4px;
        font-size: 0.75rem;
        font-weight: 500;
        gap: 0.25rem;
    }

    .booking-actions {
        display: flex;
        gap: 0.5rem;
    }

    .btn-sm {
        padding: 0.375rem 0.75rem;
        font-size: 0.75rem;
        border-radius: 4px;
        display: inline-flex;
        align-items: center;
        gap: 0.375rem;
    }

    .btn-view {
        background-color: var(--primary-color);
        color: white;
        border: none;
    }

    .btn-view:hover {
        background-color: var(--primary-dark);
    }

    .empty-bookings {
        padding: 2rem 1rem;
        text-align: center;
    }

    .empty-bookings i {
        font-size: 2rem;
        color: #d1d5db;
        margin-bottom: 1rem;
    }

    .empty-bookings p {
        font-size: 0.875rem;
        color: var(--text-secondary);
        margin-bottom: 1rem;
    }

    @media (max-width: 768px) {
        .booking-item {
            flex-wrap: wrap;
        }

        .booking-actions {
            width: 100%;
            justify-content: flex-end;
            margin-top: 0.5rem;
        }
    }
</style>
@endsection

@section('content')
<div class="dashboard-container">
    <div class="welcome-banner">
        <div class="d-flex align-items-center justify-content-between">
            <div>
                <h1 class="h4 mb-1">Welcome back, {{ auth()->user()->full_name }}! 👋</h1>
                <p class="text-muted mb-0">Ready to explore new destinations? Browse our curated collection of amazing travel experiences.</p>
            </div>
        </div>
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
        <a href="{{ route('tourist.destinations') }}" class="action-card">
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
                    <a href="{{ route('tourist.bookings') }}" class="btn btn-secondary">View All</a>
                @endif
            </div>
            <div class="content-card-body">
                @if($recentBookings->count() > 0)
                    <div class="recent-bookings">
                        <div class="bookings-header">
                            <h3><i class="fas fa-calendar-check"></i>Recent Bookings</h3>
                        
                        </div>
                        <div class="bookings-body">
                            <ul class="bookings-list">
                                @foreach($recentBookings as $booking)
                                    <li class="booking-item">
                                        <div class="booking-icon">
                                            <i class="fas fa-suitcase"></i>
                                        </div>
                                        <div class="booking-details">
                                            <div class="booking-title">{{ $booking->package->name }}</div>
                                            <div class="booking-meta">
                                                <div class="booking-date">
                                                    <i class="fas fa-calendar"></i>
                                                    {{ $booking->start_date->format('M d, Y') }}
                                                </div>
                                                <span class="booking-status status-{{ strtolower($booking->status) }}">
                                                    <i class="fas fa-circle text-xs"></i>
                                                    {{ $booking->status }}
                                                </span>
                                            </div>
                                        </div>
                                        <div class="booking-actions">
                                            <a href="{{ route('tourist.bookings.show', $booking) }}" class="btn-sm btn-view">
                                                <i class="fas fa-eye"></i>View
                                            </a>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                @else
                    <div class="empty-bookings">
                        <i class="fas fa-calendar-times"></i>
                        <p>No bookings yet. Start by browsing our amazing tour packages!</p>
                        <a href="{{ route('tourist.packages') }}" class="btn-sm btn-view">
                            <i class="fas fa-plus"></i>Browse Packages
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