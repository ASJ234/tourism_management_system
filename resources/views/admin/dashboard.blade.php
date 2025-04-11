@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <!-- Header Section -->
    <div class="header-section mb-4">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h1 class="display-4 fw-bold text-gradient mb-2">Dashboard Overview</h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item active" aria-current="page">Dashboard</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row g-4 mb-4">
        <div class="col-md-3">
            <div class="card glass-card h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="avatar-circle me-3">
                            <i class="fas fa-users"></i>
                        </div>
                        <div>
                            <h6 class="card-subtitle mb-2 text-muted">Total Users</h6>
                            <h3 class="card-title mb-0">{{ $totalUsers ?? 0 }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card glass-card h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="avatar-circle me-3">
                            <i class="fas fa-map-marker-alt"></i>
                        </div>
                        <div>
                            <h6 class="card-subtitle mb-2 text-muted">Total Destinations</h6>
                            <h3 class="card-title mb-0">{{ $totalDestinations ?? 0 }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card glass-card h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="avatar-circle me-3">
                            <i class="fas fa-calendar-check"></i>
                        </div>
                        <div>
                            <h6 class="card-subtitle mb-2 text-muted">Total Bookings</h6>
                            <h3 class="card-title mb-0">{{ $totalBookings ?? 0 }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Activity -->
    <div class="card glass-card">
        <div class="card-header glass-header">
            <h5 class="card-title mb-0">
                <i class="fas fa-history me-2"></i>Recent Activity
            </h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Description</th>
                            <th>User</th>
                            <th>Time</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentActivities ?? [] as $activity)
                            <tr>
                                <td>{{ $activity->description }}</td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-circle me-2">
                                            {{ substr($activity->user ? $activity->user->full_name : 'S', 0, 1) }}
                                        </div>
                                        {{ $activity->user ? $activity->user->full_name : 'System' }}
                                    </div>
                                </td>
                                <td>{{ $activity->created_at->diffForHumans() }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="text-center">No recent activity</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection 