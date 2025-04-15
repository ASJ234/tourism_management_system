@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <!-- Header Section -->
    <div class="header-section mb-4">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h1 class="display-4 fw-bold text-gradient mb-2">Tour Operator Dashboard</h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item active" aria-current="page">Dashboard</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card glass-card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="avatar-circle bg-primary bg-opacity-10 me-3">
                            <i class="fas fa-suitcase text-primary"></i>
                        </div>
                        <div>
                            <h6 class="card-subtitle mb-1 text-muted">Active Packages</h6>
                            <h3 class="card-title mb-0">{{ $activePackages }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card glass-card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="avatar-circle bg-success bg-opacity-10 me-3">
                            <i class="fas fa-calendar-check text-success"></i>
                        </div>
                        <div>
                            <h6 class="card-subtitle mb-1 text-muted">Total Bookings</h6>
                            <h3 class="card-title mb-0">{{ $recentBookings }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Bookings -->
    <div class="card glass-card mb-4">
        <div class="card-header glass-header">
            <h5 class="card-title mb-0">
                <i class="fas fa-calendar-check me-2"></i>Recent Bookings
            </h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Booking ID</th>
                            <th>Tourist</th>
                            <th>Package</th>
                            <th>Date</th>
                            <th>Amount</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentBookingsList as $booking)
                        <tr>
                            <td>{{ $booking->booking_id }}</td>
                            <td>{{ $booking->user->full_name }}</td>
                            <td>{{ $booking->package->name }}</td>
                            <td>{{ $booking->booking_date->format('M d, Y') }}</td>
                            <td>${{ number_format($booking->total_price, 2) }}</td>
                            <td>
                                <span class="badge bg-{{ $booking->status === 'completed' ? 'success' : ($booking->status === 'pending' ? 'warning' : 'secondary') }}">
                                    {{ ucfirst($booking->status) }}
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-4">
                                <div class="empty-state">
                                    <i class="fas fa-calendar-times fa-3x text-muted mb-3"></i>
                                    <h5>No Recent Bookings</h5>
                                    <p class="text-muted">You don't have any bookings yet</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Recent Packages -->
    <div class="card glass-card">
        <div class="card-header glass-header">
            <h5 class="card-title mb-0">
                <i class="fas fa-suitcase me-2"></i>Recent Packages
            </h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Package Name</th>
                            <th>Destination</th>
                            <th>Price</th>
                            <th>Duration</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentPackages as $package)
                        <tr>
                            <td>{{ $package->name }}</td>
                            <td>{{ $package->destination->name }}</td>
                            <td>${{ number_format($package->price, 2) }}</td>
                            <td>{{ $package->duration_days }} days</td>
                            <td>
                                <span class="badge bg-{{ $package->is_active ? 'success' : 'secondary' }}">
                                    {{ $package->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center py-4">
                                <div class="empty-state">
                                    <i class="fas fa-suitcase fa-3x text-muted mb-3"></i>
                                    <h5>No Tour Packages</h5>
                                    <p class="text-muted">Create your first tour package to get started</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection