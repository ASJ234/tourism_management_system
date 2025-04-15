@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <!-- Header Section -->
    <div class="header-section mb-4">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h1 class="display-4 fw-bold text-gradient mb-2">Analytics Dashboard</h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Analytics</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>

    <!-- Tourists Analytics Section -->
    <div class="analytics-section mb-5">
        <h2 class="section-title mb-4">
            <i class="fas fa-users me-2"></i>Tourists Analytics
        </h2>
        <div class="card glass-card">
            <div class="card-body">
                <div class="row g-4">
                    <div class="col-md-3">
                        <div class="stat-card bg-primary-gradient">
                            <div class="stat-icon">
                                <i class="fas fa-user-friends"></i>
                            </div>
                            <div class="stat-info">
                                <h6>Total Tourists</h6>
                                <h3>{{ $totalTourists ?? 0 }}</h3>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="stat-card bg-success-gradient">
                            <div class="stat-icon">
                                <i class="fas fa-calendar-check"></i>
                            </div>
                            <div class="stat-info">
                                <h6>Active Bookings</h6>
                                <h3>{{ $activeBookings ?? 0 }}</h3>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="stat-card bg-info-gradient">
                            <div class="stat-icon">
                                <i class="fas fa-flag-checkered"></i>
                            </div>
                            <div class="stat-info">
                                <h6>Completed Tours</h6>
                                <h3>{{ $completedTours ?? 0 }}</h3>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="stat-card bg-warning-gradient">
                            <div class="stat-icon">
                                <i class="fas fa-star"></i>
                            </div>
                            <div class="stat-info">
                                <h6>Average Rating</h6>
                                <h3>{{ number_format($averageRating ?? 0, 1) }}/5</h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Package Analysis Section -->
    <div class="analytics-section mb-5">
        <h2 class="section-title mb-4">
            <i class="fas fa-suitcase me-2"></i>Package Analysis
        </h2>
        <div class="card glass-card mb-4">
            <div class="card-header glass-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-map-marked-alt me-2"></i>Destination Overview
                </h5>
            </div>
            <div class="card-body">
                <div class="row g-4">
                    @foreach($destinations as $destination)
                        <div class="col-md-4">
                            <div class="destination-card">
                                <div class="destination-image">
                                    <img src="{{ $destination->image_url }}" alt="{{ $destination->name }}" class="img-fluid">
                                </div>
                                <div class="destination-info">
                                    <h4>{{ $destination->name }}</h4>
                                    <p class="text-muted">{{ Str::limit($destination->description, 100) }}</p>
                                    <div class="destination-stats">
                                        <span class="badge bg-primary">
                                            <i class="fas fa-suitcase me-1"></i>
                                            {{ $destination->tourPackages->count() }} Packages
                                        </span>
                                        <span class="badge bg-success">
                                            <i class="fas fa-users me-1"></i>
                                            {{ $destination->totalVisitors() }} Visitors
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="card glass-card">
            <div class="card-header glass-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-star me-2"></i>Popular Tour Packages
                </h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Package Name</th>
                                <th>Destination</th>
                                <th>Bookings</th>
                                <th>Revenue</th>
                                <th>Rating</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($popularPackages as $package)
                                <tr>
                                    <td>{{ $package->name }}</td>
                                    <td>{{ $package->destination->name }}</td>
                                    <td>{{ $package->bookings_count }}</td>
                                    <td>${{ number_format($package->totalRevenue(), 2) }}</td>
                                    <td>
                                        <div class="rating">
                                            @for($i = 1; $i <= 5; $i++)
                                                <i class="fas fa-star {{ $i <= $package->averageRating() ? 'text-warning' : 'text-muted' }}"></i>
                                            @endfor
                                            <span class="ms-1">({{ number_format($package->averageRating(), 1) }})</span>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center">No popular packages found</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    .analytics-section {
        background: rgba(255, 255, 255, 0.1);
        border-radius: 10px;
        padding: 20px;
    }
    .section-title {
        color: #2c3e50;
        font-size: 1.5rem;
        font-weight: 600;
        padding-bottom: 10px;
        border-bottom: 2px solid #e9ecef;
    }
    .glass-card {
        background: rgba(255, 255, 255, 0.8);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.2);
    }
    .glass-header {
        background: rgba(255, 255, 255, 0.5);
        border-bottom: 1px solid rgba(255, 255, 255, 0.2);
    }
    .stat-card {
        border-radius: 10px;
        padding: 20px;
        color: white;
        height: 100%;
    }
    .stat-icon {
        font-size: 2rem;
        margin-bottom: 10px;
    }
    .stat-info h6 {
        margin-bottom: 5px;
        font-size: 0.9rem;
    }
    .stat-info h3 {
        margin: 0;
        font-size: 1.8rem;
    }
    .destination-card {
        border-radius: 10px;
        overflow: hidden;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
    .destination-image {
        height: 200px;
        overflow: hidden;
    }
    .destination-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    .destination-info {
        padding: 15px;
    }
    .destination-stats {
        margin-top: 10px;
    }
    .rating {
        display: inline-flex;
        align-items: center;
    }
</style>
@endpush
@endsection 