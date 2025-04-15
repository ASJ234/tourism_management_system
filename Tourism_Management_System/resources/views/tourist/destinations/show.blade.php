@extends('layouts.app')

@section('styles')
<style>
    .destination-container {
        background-color: #f8fafc;
        min-height: 100vh;
        padding: 2rem 0;
    }

    .destination-header {
        position: relative;
        height: 500px;
        overflow: hidden;
        border-radius: 1rem;
        margin-bottom: 2rem;
    }

    .destination-header img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.3s ease;
    }

    .destination-header:hover img {
        transform: scale(1.05);
    }

    .destination-overlay {
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        padding: 2rem;
        background: linear-gradient(to top, rgba(0,0,0,0.8), transparent);
        color: white;
    }

    .destination-title {
        font-size: 3rem;
        font-weight: 700;
        margin-bottom: 0.5rem;
    }

    .destination-location {
        display: flex;
        align-items: center;
        font-size: 1.1rem;
        opacity: 0.9;
    }

    .destination-content {
        background: white;
        border-radius: 1rem;
        padding: 2rem;
        box-shadow: 0 4px 6px rgba(0,0,0,0.1);
    }

    .section-title {
        font-size: 1.5rem;
        font-weight: 600;
        color: #1f2937;
        margin-bottom: 1rem;
        padding-bottom: 0.5rem;
        border-bottom: 2px solid #e5e7eb;
    }

    .description-text {
        color: #4b5563;
        line-height: 1.7;
    }

    .meta-info {
        display: flex;
        gap: 2rem;
        margin: 1.5rem 0;
        padding: 1rem;
        background: #f3f4f6;
        border-radius: 0.5rem;
    }

    .meta-item {
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .meta-item i {
        color: #6b7280;
    }

    .gallery-section {
        margin-top: 3rem;
    }

    .gallery-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
        gap: 1rem;
        margin-top: 1rem;
    }

    .gallery-item {
        position: relative;
        height: 200px;
        border-radius: 0.5rem;
        overflow: hidden;
        cursor: pointer;
        transition: transform 0.3s ease;
    }

    .gallery-item:hover {
        transform: scale(1.05);
    }

    .gallery-item img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .gallery-overlay {
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        padding: 1rem;
        background: linear-gradient(to top, rgba(0,0,0,0.8), transparent);
        color: white;
        opacity: 0;
        transition: opacity 0.3s ease;
    }

    .gallery-item:hover .gallery-overlay {
        opacity: 1;
    }

    .btn-back {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.75rem 1.5rem;
        background: #4b5563;
        color: white;
        border-radius: 0.5rem;
        text-decoration: none;
        transition: background-color 0.3s ease;
    }

    .btn-back:hover {
        background: #374151;
    }

    /* Modal styles */
    .modal {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0,0,0,0.9);
        z-index: 1000;
        padding: 2rem;
    }

    .modal.active {
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .modal-content {
        max-width: 90%;
        max-height: 90vh;
        position: relative;
    }

    .modal-image {
        max-width: 100%;
        max-height: 90vh;
        object-fit: contain;
    }

    .modal-close {
        position: absolute;
        top: -2rem;
        right: -2rem;
        color: white;
        font-size: 2rem;
        cursor: pointer;
    }

    .modal-nav {
        position: absolute;
        top: 50%;
        transform: translateY(-50%);
        color: white;
        font-size: 2rem;
        cursor: pointer;
        padding: 1rem;
    }

    .modal-prev {
        left: 2rem;
    }

    .modal-next {
        right: 2rem;
    }
</style>
@endsection

@section('content')
<div class="destination-container">
    <div class="container mx-auto px-4">
        <!-- Destination Header -->
        <div class="destination-header">
            @if($destination->image_url)
                <img src="{{ asset($destination->image_url) }}" alt="{{ $destination->name }}">
            @else
                <div class="w-full h-full bg-gray-200 flex items-center justify-center">
                    <span class="text-gray-400">No image available</span>
                </div>
            @endif
            <div class="destination-overlay">
                <h1 class="destination-title">{{ $destination->name }}</h1>
                <div class="destination-location">
                    <i class="fas fa-map-marker-alt mr-2"></i>
                    <span>{{ $destination->location }}</span>
                </div>
            </div>
        </div>

        <!-- Destination Content -->
        <div class="destination-content">
            <div class="meta-info">
                <div class="meta-item">
                    <i class="fas fa-globe"></i>
                    <span>{{ ucfirst($destination->region) }}</span>
                </div>
                <div class="meta-item">
                    <i class="fas fa-user"></i>
                    <span>Added by {{ $destination->creator->full_name }}</span>
                </div>
                <div class="meta-item">
                    <i class="fas fa-calendar"></i>
                    <span>Added on {{ $destination->created_at->format('M d, Y') }}</span>
                </div>
            </div>

            <div class="mb-6">
                <h2 class="section-title">About This Destination</h2>
                <p class="description-text">{{ $destination->description }}</p>
            </div>

            @if($destination->images->count() > 0)
                <div class="gallery-section">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h2 class="section-title mb-0">Photo Gallery</h2>
                        <button class="btn btn-primary" onclick="openGallery(0)">
                            View All Images
                        </button>
                    </div>
                    <div class="gallery-grid">
                        @foreach($destination->images as $index => $image)
                            <div class="gallery-item" onclick="openGallery({{ $index }})">
                                <img src="{{ asset($image->image_path) }}" alt="Destination Image">
                                <div class="gallery-overlay">
                                    <div class="text-sm">Click to view</div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <div class="mt-6">
                <a href="{{ route('tourist.destinations') }}" class="btn-back">
                    <i class="fas fa-arrow-left"></i>
                    Back to Destinations
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Image Gallery Modal -->
<div class="modal" id="galleryModal">
    <div class="modal-content">
        <span class="modal-close" onclick="closeGallery()">&times;</span>
        <img src="" alt="Gallery Image" class="modal-image" id="modalImage">
        <div class="modal-nav modal-prev" onclick="prevImage()">
            <i class="fas fa-chevron-left"></i>
        </div>
        <div class="modal-nav modal-next" onclick="nextImage()">
            <i class="fas fa-chevron-right"></i>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    let currentImageIndex = 0;
    const images = @json($destination->images->pluck('image_path'));

    function openGallery(index) {
        currentImageIndex = index;
        const modal = document.getElementById('galleryModal');
        const modalImage = document.getElementById('modalImage');
        modalImage.src = "{{ asset('') }}" + images[currentImageIndex];
        modal.classList.add('active');
    }

    function closeGallery() {
        const modal = document.getElementById('galleryModal');
        modal.classList.remove('active');
    }

    function nextImage() {
        currentImageIndex = (currentImageIndex + 1) % images.length;
        const modalImage = document.getElementById('modalImage');
        modalImage.src = "{{ asset('') }}" + images[currentImageIndex];
    }

    function prevImage() {
        currentImageIndex = (currentImageIndex - 1 + images.length) % images.length;
        const modalImage = document.getElementById('modalImage');
        modalImage.src = "{{ asset('') }}" + images[currentImageIndex];
    }

    // Close modal when clicking outside
    document.getElementById('galleryModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeGallery();
        }
    });

    // Keyboard navigation
    document.addEventListener('keydown', function(e) {
        if (!document.getElementById('galleryModal').classList.contains('active')) return;
        
        if (e.key === 'ArrowRight') nextImage();
        if (e.key === 'ArrowLeft') prevImage();
        if (e.key === 'Escape') closeGallery();
    });
</script>
@endpush 