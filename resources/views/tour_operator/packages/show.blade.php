@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Package Details</h5>
                </div>

                <div class="card-body">
                    <!-- Primary Image -->
                    @if($package->primaryImage)
                        <div class="mb-4">
                            <img src="{{ asset('storage/' . $package->primaryImage->image_path) }}" 
                                 class="img-fluid rounded" 
                                 alt="{{ $package->primaryImage->caption ?? $package->name }}">
                        </div>
                    @endif

                    <!-- Package Details -->
                    <div class="mb-4">
                        <h4>{{ $package->name }}</h4>
                        <p class="text-muted">{{ $package->description }}</p>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <p><strong>Price:</strong> ${{ number_format($package->price, 2) }}</p>
                                <p><strong>Duration:</strong> {{ $package->duration_days }} days</p>
                                <p><strong>Difficulty:</strong> {{ $package->difficulty_level }}</p>
                            </div>
                            <div class="col-md-6">
                                <p><strong>Start Date:</strong> {{ $package->start_date->format('M d, Y') }}</p>
                                <p><strong>End Date:</strong> {{ $package->end_date->format('M d, Y') }}</p>
                                <p><strong>Available Slots:</strong> {{ $package->total_available_slots }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Image Gallery -->
                    @if($package->images->count() > 0)
                        <div class="mb-4">
                            <h5>Gallery</h5>
                            <div class="row">
                                @foreach($package->images as $image)
                                    <div class="col-md-4 mb-3">
                                        <div class="card">
                                            <img src="{{ asset('storage/' . $image->image_path) }}" 
                                                 class="card-img-top" 
                                                 alt="{{ $image->caption ?? 'Package Image' }}">
                                            @if($image->caption)
                                                <div class="card-body">
                                                    <p class="card-text">{{ $image->caption }}</p>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <!-- Action Buttons -->
                    <div class="d-flex justify-content-between">
                        <a href="{{ route('tour_operator.packages.index') }}" class="btn btn-secondary">
                            Back to Packages
                        </a>
                        <div>
                            <a href="{{ route('tour_operator.packages.edit', $package) }}" class="btn btn-primary">
                                Edit Package
                            </a>
                            <form action="{{ route('tour_operator.packages.destroy', $package) }}" 
                                  method="POST" 
                                  class="d-inline"
                                  onsubmit="return confirm('Are you sure you want to delete this package?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger">
                                    Delete Package
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 