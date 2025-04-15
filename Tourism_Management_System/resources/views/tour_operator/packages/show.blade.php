@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Package Details</h5>
                    <div>
                        <a href="{{ route('tour_operator.packages.edit', $package) }}" class="btn btn-warning">
                            <i class="fas fa-edit"></i> Edit Package
                        </a>
                        <a href="{{ route('tour_operator.packages.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Back to Packages
                        </a>
                    </div>
                </div>

                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    <div class="row">
                        <div class="col-md-8">
                            <h3>{{ $package->name }}</h3>
                            <p class="text-muted">Destination: {{ $package->destination->name }}</p>
                            
                            <div class="mb-4">
                                <h5>Description</h5>
                                <p>{{ $package->description }}</p>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <h5>Package Details</h5>
                                    <ul class="list-unstyled">
                                        <li><strong>Price:</strong> ${{ number_format($package->price, 2) }}</li>
                                        <li><strong>Duration:</strong> {{ $package->duration_days }} days</li>
                                        <li><strong>Difficulty Level:</strong> {{ $package->difficulty_level }}</li>
                                        <li><strong>Maximum Capacity:</strong> {{ $package->max_capacity }} people</li>
                                        <li><strong>Available Slots:</strong> {{ $package->total_available_slots }}</li>
                                        <li><strong>Status:</strong> 
                                            <span class="badge bg-{{ $package->is_active ? 'success' : 'danger' }}">
                                                {{ $package->is_active ? 'Active' : 'Inactive' }}
                                            </span>
                                        </li>
                                    </ul>
                                </div>
                                <div class="col-md-6">
                                    <h5>Schedule</h5>
                                    <ul class="list-unstyled">
                                        <li><strong>Start Date:</strong> {{ $package->start_date->format('M d, Y') }}</li>
                                        <li><strong>End Date:</strong> {{ $package->end_date->format('M d, Y') }}</li>
                                    </ul>

                                    @if($package->discount_percentage)
                                        <h5 class="mt-4">Promotion</h5>
                                        <ul class="list-unstyled">
                                            <li><strong>Discount:</strong> {{ $package->discount_percentage }}%</li>
                                            <li><strong>Promotion Start:</strong> {{ $package->promotion_start_date->format('M d, Y') }}</li>
                                            <li><strong>Promotion End:</strong> {{ $package->promotion_end_date->format('M d, Y') }}</li>
                                        </ul>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-4">
                        <h4>Recent Bookings</h4>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Booking ID</th>
                                        <th>Customer</th>
                                        <th>Date</th>
                                        <th>Travelers</th>
                                        <th>Total Price</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($package->bookings as $booking)
                                        <tr>
                                            <td>#{{ $booking->booking_id }}</td>
                                            <td>{{ $booking->user ? $booking->user->full_name : 'N/A' }}</td>
                                            <td>{{ \Carbon\Carbon::parse($booking->booking_date)->format('M d, Y') }}</td>
                                            <td>{{ $booking->number_of_travelers }}</td>
                                            <td>${{ number_format($booking->total_price, 2) }}</td>
                                            <td>
                                                <span class="badge bg-{{ $booking->status === 'Completed' ? 'success' : ($booking->status === 'Pending' ? 'warning' : 'danger') }}">
                                                    {{ $booking->status }}
                                                </span>
                                            </td>
                                            <td>
                                                <a href="{{ route('tour_operator.bookings.show', $booking) }}" 
                                                   class="btn btn-sm btn-info">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="text-center">No bookings found.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 