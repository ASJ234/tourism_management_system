@extends('layouts.app')

@section('styles')
<style>
    .destinations-container {
        padding: 2rem;
        background-color: #f8fafc;
        min-height: 100vh;
    }

    .page-header {
        margin-bottom: 2rem;
    }

    .page-title {
        font-size: 2rem;
        font-weight: 700;
        color: #1f2937;
        margin-bottom: 0.5rem;
    }

    .page-description {
        color: #6b7280;
        max-width: 600px;
    }

    .destinations-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
        gap: 2rem;
        padding: 1rem 0;
    }

    .destination-card {
        background: white;
        border-radius: 8px;
        overflow: hidden;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        transition: transform 0.2s, box-shadow 0.2s;
    }

    .destination-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 4px 6px rgba(0,0,0,0.1);
    }

    .destination-image {
        width: 100%;
        height: 200px;
        object-fit: cover;
    }

    .destination-content {
        padding: 1.5rem;
    }

    .destination-name {
        font-size: 1.25rem;
        font-weight: 600;
        color: #1f2937;
        margin-bottom: 0.5rem;
    }

    .destination-description {
        color: #6b7280;
        font-size: 0.875rem;
        margin-bottom: 1rem;
        display: -webkit-box;
        -webkit-line-clamp: 3;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .destination-meta {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding-top: 1rem;
        border-top: 1px solid #e5e7eb;
    }

    .destination-price {
        color: #059669;
        font-weight: 600;
    }

    .destination-rating {
        display: flex;
        align-items: center;
        color: #fbbf24;
    }

    .btn-view {
        display: inline-block;
        padding: 0.5rem 1rem;
        background-color: #2563eb;
        color: white;
        border-radius: 4px;
        text-decoration: none;
        font-weight: 500;
        transition: background-color 0.2s;
    }

    .btn-view:hover {
        background-color: #1d4ed8;
    }

    .filters {
        display: flex;
        gap: 1rem;
        margin-bottom: 2rem;
    }

    .filter-select {
        padding: 0.5rem;
        border: 1px solid #d1d5db;
        border-radius: 4px;
        min-width: 150px;
    }

    .empty-state {
        text-align: center;
        padding: 3rem;
        background: white;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }

    .empty-state i {
        font-size: 3rem;
        color: #d1d5db;
        margin-bottom: 1rem;
    }

    .empty-state p {
        color: #6b7280;
        margin-bottom: 1rem;
    }
</style>
@endsection

@section('content')
<div class="destinations-container">
    <div class="page-header">
        <h1 class="page-title">Explore Destinations</h1>
        <p class="page-description">Discover amazing places around the world and start planning your next adventure.</p>
    </div>

    <div class="filters">
        <select class="filter-select" id="region-filter">
            <option value="">All Regions</option>
            <option value="asia">Asia</option>
            <option value="europe">Europe</option>
            <option value="africa">Africa</option>
            <option value="north-america">North America</option>
            <option value="south-america">South America</option>
            <option value="oceania">Oceania</option>
        </select>

        <select class="filter-select" id="price-filter">
            <option value="">All Prices</option>
            <option value="budget">Budget Friendly</option>
            <option value="mid-range">Mid Range</option>
            <option value="luxury">Luxury</option>
        </select>
    </div>

    <div class="destinations-grid">
        @forelse($destinations as $destination)
            <div class="destination-card">
                <img src="{{ $destination->image_url ?? asset('images/placeholder.jpg') }}" 
                     alt="{{ $destination->name }}" 
                     class="destination-image">
                <div class="destination-content">
                    <h2 class="destination-name">{{ $destination->name }}</h2>
                    <p class="destination-description">{{ $destination->description }}</p>
                    <div class="destination-meta">
                        <div class="destination-price">
                            From ${{ number_format($destination->packages->min('price'), 2) }}
                        </div>
                        @if($destination->reviews_count > 0)
                            <div class="destination-rating">
                                <i class="fas fa-star"></i>
                                <span>{{ number_format($destination->reviews_avg_rating, 1) }}</span>
                            </div>
                        @endif
                    </div>
                    <div class="mt-4">
                        <a href="{{ route('tourist.destinations.show', $destination) }}" class="btn-view">
                            View Details
                        </a>
                    </div>
                </div>
            </div>
        @empty
            <div class="empty-state">
                <i class="fas fa-map-marked-alt"></i>
                <p>No destinations found. Please try adjusting your filters.</p>
            </div>
        @endforelse
    </div>

    <div class="mt-8">
        {{ $destinations->links() }}
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const regionFilter = document.getElementById('region-filter');
    const priceFilter = document.getElementById('price-filter');

    function applyFilters() {
        const region = regionFilter.value;
        const price = priceFilter.value;
        
        // You can implement the filtering logic here
        // For now, we'll just reload the page with the filter parameters
        const params = new URLSearchParams(window.location.search);
        
        if (region) {
            params.set('region', region);
        } else {
            params.delete('region');
        }
        
        if (price) {
            params.set('price', price);
        } else {
            params.delete('price');
        }

        window.location.search = params.toString();
    }

    regionFilter.addEventListener('change', applyFilters);
    priceFilter.addEventListener('change', applyFilters);
});
</script>
@endsection 