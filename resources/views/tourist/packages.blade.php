@extends('layouts.app')

@section('styles')
<style>
    .form-container {
        max-width: 800px;
        margin: 2rem auto;
        padding: 2rem;
        background: white;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }

    .form-header {
        margin-bottom: 2rem;
    }

    .form-header h1 {
        font-size: 1.5rem;
        color: #333;
    }

    .form-group {
        margin-bottom: 1.5rem;
    }

    .form-group label {
        display: block;
        margin-bottom: 0.5rem;
        font-weight: 500;
        color: #4b5563;
    }

    .form-control {
        width: 100%;
        padding: 0.75rem;
        border: 1px solid #d1d5db;
        border-radius: 4px;
        font-size: 1rem;
    }

    .form-control:focus {
        outline: none;
        border-color: #2563eb;
        box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
    }

    .btn {
        padding: 0.75rem 1.5rem;
        border-radius: 4px;
        font-size: 1rem;
        font-weight: 500;
        text-decoration: none;
        cursor: pointer;
        transition: all 0.2s;
    }

    .btn-primary {
        background: #2563eb;
        color: white;
        border: none;
    }

    .btn-primary:hover {
        background: #1d4ed8;
    }

    .error-message {
        color: #dc2626;
        font-size: 0.875rem;
        margin-top: 0.25rem;
    }

    .package-info {
        margin-top: 0.5rem;
        font-size: 0.875rem;
        color: #6b7280;
    }

    .card {
        transition: transform 0.2s;
    }
    .card:hover {
        transform: translateY(-5px);
    }
    .card-img-top {
        border-top-left-radius: calc(0.375rem - 1px);
        border-top-right-radius: calc(0.375rem - 1px);
    }
</style>
@endsection

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card shadow">
                <div class="card-header bg-primary text-white py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h4 class="mb-0">Available Tour Packages</h4>
                        <a href="{{ route('tourist.dashboard') }}" class="btn btn-light btn-sm">
                            <i class="fas fa-arrow-left me-2"></i>Back to Dashboard
                        </a>
                    </div>
                </div>

                <div class="card-body">
                    @if($packages->isEmpty())
                        <div class="text-center py-5">
                            <i class="fas fa-exclamation-circle text-warning fs-1 mb-3"></i>
                            <h5>No Available Packages</h5>
                            <p class="text-muted">There are currently no available tour packages.</p>
                            <a href="{{ route('tourist.packages') }}" class="btn btn-primary mt-3">
                                Browse All Packages
                            </a>
                        </div>
                    @else
                        <div class="row g-4">
                            @foreach($packages as $tourPackage)
                                <div class="col-md-6">
                                    <div class="card h-100 shadow-sm">
                                        @if($tourPackage->image)
                                            <img src="{{ asset('uploads/destinations/' . $tourPackage->image) }}" 
                                                 class="card-img-top" 
                                                 alt="{{ $tourPackage->name }}"
                                                 style="height: 200px; object-fit: cover;">
                                        @else
                                            <div class="bg-light text-center py-5">
                                                <i class="fas fa-image text-muted fs-1"></i>
                                            </div>
                                        @endif
                                        <div class="card-body">
                                            <h5 class="card-title">{{ $tourPackage->name }}</h5>
                                            <p class="text-muted mb-2">
                                                <i class="fas fa-map-marker-alt me-2"></i>
                                                {{ $tourPackage->destination->name }}
                                            </p>
                                            <p class="text-muted mb-2">
                                                <i class="fas fa-clock me-2"></i>
                                                Duration: {{ $tourPackage->duration_days }} days
                                            </p>
                                            <p class="text-muted mb-2">
                                                <i class="fas fa-users me-2"></i>
                                                Available Slots: {{ $tourPackage->total_available_slots }}
                                            </p>
                                            <p class="text-muted mb-2">
                                                <i class="fas fa-calendar me-2"></i>
                                                {{ $tourPackage->start_date->format('M d, Y') }} - {{ $tourPackage->end_date->format('M d, Y') }}
                                            </p>
                                            <hr>
                                            <div class="d-flex justify-content-between align-items-center">
                                                <div>
                                                    <span class="text-muted">Price per person</span>
                                                    <div class="text-primary fw-bold fs-5">
                                                        ${{ number_format($tourPackage->price, 2) }}
                                                    </div>
                                                </div>
                                                <a href="{{ route('tourist.bookings.create', ['package' => $tourPackage]) }}" 
                                                   class="btn btn-primary">
                                                    <i class="fas fa-bookmark me-2"></i>Book Now
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <!-- Pagination if needed -->
                        @if($packages->hasPages())
                            <div class="d-flex justify-content-center mt-4">
                                {{ $packages->links() }}
                            </div>
                        @endif
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 