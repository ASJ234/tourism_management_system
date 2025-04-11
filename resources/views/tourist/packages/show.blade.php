@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <!-- Package Header -->
            <div class="card shadow mb-5">
                <!-- Image Section -->
                <div class="position-relative">
                    <img src="{{ asset('uploads/destinations/' . $package->image) }}" 
                         alt="{{ $package->name }}"
                         class="card-img-top"
                         style="height: 400px; object-fit: cover;">
                    <div class="position-absolute bottom-0 start-0 w-100 p-4" 
                         style="background: linear-gradient(to top, rgba(0,0,0,0.7), transparent);">
                        <h1 class="text-white display-5 fw-bold mb-2">{{ $package->name }}</h1>
                        <div class="text-white d-flex align-items-center">
                            <i class="fas fa-map-marker-alt me-2"></i>
                            <span>{{ $package->destination->name }}</span>
                        </div>
                    </div>
                </div>
                
                <div class="card-body p-4">
                    <!-- Quick Info Cards -->
                    <div class="row mb-5">
                        <div class="col-md-3 col-6 mb-3">
                            <div class="card bg-light">
                                <div class="card-body text-center">
                                    <i class="fas fa-clock text-primary fs-3 mb-2"></i>
                                    <h6 class="text-muted mb-1">Duration</h6>
                                    <div class="fw-bold">{{ $package->duration_days }} days</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 col-6 mb-3">
                            <div class="card bg-light">
                                <div class="card-body text-center">
                                    <i class="fas fa-tag text-success fs-3 mb-2"></i>
                                    <h6 class="text-muted mb-1">Price</h6>
                                    <div class="fw-bold">${{ number_format($package->price, 2) }}</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 col-6 mb-3">
                            <div class="card bg-light">
                                <div class="card-body text-center">
                                    <i class="fas fa-mountain text-warning fs-3 mb-2"></i>
                                    <h6 class="text-muted mb-1">Difficulty</h6>
                                    <div class="fw-bold">{{ ucfirst($package->difficulty_level) }}</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 col-6 mb-3">
                            <div class="card bg-light">
                                <div class="card-body text-center">
                                    <i class="fas fa-users text-info fs-3 mb-2"></i>
                                    <h6 class="text-muted mb-1">Available Slots</h6>
                                    <div class="fw-bold">{{ $package->total_available_slots }}</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Description Section -->
                    <div class="mb-5">
                        <h2 class="h4 mb-4">
                            <i class="fas fa-info-circle text-primary me-2"></i>
                            Description
                        </h2>
                        <p class="text-muted">{{ $package->description }}</p>
                    </div>

                    <!-- Dates Section -->
                    <div class="mb-5">
                        <h2 class="h4 mb-4">
                            <i class="fas fa-calendar text-primary me-2"></i>
                            Tour Dates
                        </h2>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <div class="card bg-light">
                                    <div class="card-body text-center">
                                        <h6 class="text-muted mb-1">Start Date</h6>
                                        <div class="fw-bold">{{ $package->start_date->format('M d, Y') }}</div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="card bg-light">
                                    <div class="card-body text-center">
                                        <h6 class="text-muted mb-1">End Date</h6>
                                        <div class="fw-bold">{{ $package->end_date->format('M d, Y') }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Reviews Section -->
                    @if($package->reviews->isNotEmpty())
                    <div class="mb-5">
                        <h2 class="h4 mb-4">
                            <i class="fas fa-star text-warning me-2"></i>
                            Reviews
                        </h2>
                        <div class="review-list">
                            @foreach($package->reviews as $review)
                            <div class="card bg-light mb-3">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <div>
                                            <h6 class="mb-1">{{ $review->user->full_name }}</h6>
                                            <small class="text-muted">{{ $review->created_at->format('M d, Y') }}</small>
                                        </div>
                                        <div>
                                            @for($i = 1; $i <= 5; $i++)
                                                <i class="fas fa-star {{ $i <= $review->rating ? 'text-warning' : 'text-muted' }}"></i>
                                            @endfor
                                        </div>
                                    </div>
                                    <p class="mb-0">{{ $review->comment }}</p>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    <!-- Action Buttons -->
                    <div class="d-flex justify-content-between align-items-center">
                        <a href="{{ route('tourist.packages') }}" 
                           class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-2"></i>
                            Back to Packages
                        </a>
                        <a href="{{ route('tourist.bookings.create', ['package' => $package->id, 'start_date' => $package->start_date]) }}" class="btn btn-primary">
                            <i class="fas fa-bookmark me-2"></i>
                            Book Now
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    .card {
        border: none;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
    
    .bg-light {
        background-color: #f8f9fa !important;
    }
    
    .card-img-top {
        border-top-left-radius: 0.5rem;
        border-top-right-radius: 0.5rem;
    }
    
    .btn {
        padding: 0.5rem 1.5rem;
        border-radius: 0.5rem;
    }
    
    .review-list {
        max-height: 500px;
        overflow-y: auto;
    }
</style>
@endpush
@endsection 