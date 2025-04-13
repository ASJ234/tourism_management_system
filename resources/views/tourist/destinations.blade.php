@extends('layouts.app')

@section('styles')
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
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
        cursor: pointer;
        transition: opacity 0.3s;
    }

    .destination-image:hover {
        opacity: 0.9;
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

    /* Updated Modal Styles */
    .image-modal {
        display: none;
        position: fixed;
        z-index: 9999;
        padding-top: 50px;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        overflow: auto;
        background-color: rgba(0, 0, 0, 0.95);
    }

    .modal-content {
        margin: auto;
        display: block;
        position: relative;
        max-width: 90%;
        max-height: 90vh;
    }

    .modal-image {
        width: auto;
        height: auto;
        max-width: 100%;
        max-height: 90vh;
        margin: auto;
        display: block;
    }

    .close-modal {
        position: absolute;
        right: 35px;
        top: 15px;
        color: #f1f1f1;
        font-size: 40px;
        font-weight: bold;
        cursor: pointer;
        z-index: 10000;
    }

    .close-modal:hover,
    .close-modal:focus {
        color: #bbb;
        text-decoration: none;
        cursor: pointer;
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
        <select class="filter-select" id="region-filter" name="region">
            <option value="">All Regions</option>
            <option value="asia" {{ request('region') == 'asia' ? 'selected' : '' }}>Asia</option>
            <option value="europe" {{ request('region') == 'europe' ? 'selected' : '' }}>Europe</option>
            <option value="africa" {{ request('region') == 'africa' ? 'selected' : '' }}>Africa</option>
            <option value="north-america" {{ request('region') == 'north-america' ? 'selected' : '' }}>North America</option>
            <option value="south-america" {{ request('region') == 'south-america' ? 'selected' : '' }}>South America</option>
            <option value="oceania" {{ request('region') == 'oceania' ? 'selected' : '' }}>Oceania</option>
        </select>

        <!-- <select class="filter-select" id="price-filter">
            <option value="">All Prices</option>
            <option value="budget">Budget Friendly</option>
            <option value="mid-range">Mid Range</option>
            <option value="luxury">Luxury</option>
        </select> -->
    </div>

    <div class="destinations-grid">
        @forelse($destinations as $destination)
            <div class="destination-card">
                <img src="{{ url($destination->image_url) }}" 
                     alt="{{ $destination->name }}" 
                     class="destination-image"
                     onerror="this.src='{{ asset('images/placeholder.jpg') }}'">
                <div class="destination-content">
                    <h2 class="destination-name">{{ $destination->name }}</h2>
                    <p class="destination-description">{{ $destination->description }}</p>
                    <div class="destination-meta">
                        <div class="destination-price">
                            From ${{ number_format($destination->packages->min('price') ?? 0, 2) }}
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
                <p>No destinations found in this region. Please try selecting a different region.</p>
            </div>
        @endforelse
    </div>

    <div class="mt-8">
        {{ $destinations->appends(request()->query())->links() }}
    </div>
</div>

<!-- Image Modal -->
<div id="imageModal" class="image-modal" onclick="closeImageModal()">
    <span class="close-modal">&times;</span>
    <div class="modal-content" onclick="event.stopPropagation()">
        <img id="modalImage" class="modal-image" src="" alt="">
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Region filter functionality
    document.getElementById('region-filter').addEventListener('change', function() {
        const selectedRegion = this.value;
        const currentUrl = new URL(window.location.href);
        
        if (selectedRegion) {
            currentUrl.searchParams.set('region', selectedRegion);
        } else {
            currentUrl.searchParams.delete('region');
        }
        
        window.location.href = currentUrl.toString();
    });

    // Image modal functionality
    function openImageModal(imageSrc, imageAlt) {
        const modal = document.getElementById('imageModal');
        const modalImg = document.getElementById('modalImage');
        
        modal.style.display = "block";
        modalImg.src = imageSrc;
        modalImg.alt = imageAlt;
        document.body.style.overflow = 'hidden';
    }

    function closeImageModal() {
        const modal = document.getElementById('imageModal');
        modal.style.display = "none";
        document.body.style.overflow = 'auto';
    }

    document.addEventListener('DOMContentLoaded', function() {
        // Close modal when clicking the × symbol
        const closeBtn = document.querySelector('.close-modal');
        if (closeBtn) {
            closeBtn.onclick = closeImageModal;
        }

        // Close modal with Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeImageModal();
            }
        });

        // Add click listener to all destination images
        const images = document.querySelectorAll('.destination-image');
        images.forEach(img => {
            img.addEventListener('click', function() {
                openImageModal(this.src, this.alt);
            });
        });
    });
</script>
@endsection 