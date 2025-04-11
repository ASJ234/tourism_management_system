@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">Book Package: {{ $package->name }}</h4>
                </div>
                <div class="card-body">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('tourist.bookings.store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="package_id" value="{{ $package->package_id }}">

                        @if(session('error'))
                            <div class="alert alert-danger">
                                {{ session('error') }}
                            </div>
                        @endif

                        <div class="mb-4">
                            <label for="number_of_travelers" class="form-label">Number of Travelers</label>
                            <input type="number" 
                                   name="number_of_travelers" 
                                   id="number_of_travelers" 
                                   class="form-control @error('number_of_travelers') is-invalid @enderror"
                                   value="{{ old('number_of_travelers', 1) }}"
                                   min="1"
                                   max="{{ $package->total_available_slots }}"
                                   required>
                            @error('number_of_travelers')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="start_date" class="form-label">Start Date</label>
                            <input type="date" 
                                   name="start_date" 
                                   id="start_date" 
                                   class="form-control @error('start_date') is-invalid @enderror"
                                   min="{{ $package->start_date->format('Y-m-d') }}"
                                   max="{{ $package->end_date->format('Y-m-d') }}"
                                   required>
                            @error('start_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="special_requests" class="form-label">Special Requests (Optional)</label>
                            <textarea name="special_requests" 
                                      id="special_requests" 
                                      class="form-control @error('special_requests') is-invalid @enderror"
                                      rows="4">{{ old('special_requests') }}</textarea>
                            @error('special_requests')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="card bg-light mb-4">
                            <div class="card-body">
                                <h5 class="card-title">Total Price</h5>
                                <p class="display-6 text-primary mb-0">$<span id="totalPrice">{{ number_format($package->price, 2) }}</span></p>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('tourist.bookings.create') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left me-2"></i>Back
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-check me-2"></i>Confirm Booking
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const numberOfTravelersInput = document.getElementById('number_of_travelers');
    const totalPriceSpan = document.getElementById('totalPrice');
    const pricePerPerson = {{ $package->price }};

    function updateTotalPrice() {
        const numberOfTravelers = parseInt(numberOfTravelersInput.value) || 0;
        const totalPrice = (numberOfTravelers * pricePerPerson).toFixed(2);
        totalPriceSpan.textContent = totalPrice;
    }

    numberOfTravelersInput.addEventListener('input', updateTotalPrice);

    // Form submission handling
    const form = document.querySelector('form');
    form.addEventListener('submit', function(e) {
        // Disable the submit button
        const submitButton = this.querySelector('button[type="submit"]');
        submitButton.disabled = true;
        submitButton.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Processing...';
    });
});
</script>
@endpush
@endsection 