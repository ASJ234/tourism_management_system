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

    /* Updated Modal Styles */
    .modal {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.9);
        z-index: 9999;
        overflow: hidden;
    }

    .modal.active {
        display: flex !important;
        justify-content: center;
        align-items: center;
    }

    .modal-content {
        position: relative;
        display: flex;
        justify-content: center;
        align-items: center;
        width: 90%;
        height: 90%;
        max-width: 1200px;
    }

    .modal-image {
        max-width: 100%;
        max-height: 90vh;
        object-fit: contain;
        border-radius: 4px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }

    .modal-close {
        position: absolute;
        top: -40px;
        right: 0;
        color: #fff;
        font-size: 35px;
        font-weight: bold;
        cursor: pointer;
        z-index: 10000;
        width: 40px;
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: rgba(0, 0, 0, 0.5);
        border-radius: 50%;
        transition: all 0.3s ease;
    }

    .modal-close:hover {
        background: rgba(0, 0, 0, 0.8);
        transform: scale(1.1);
    }

    .gallery-item {
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .gallery-item:hover {
        transform: scale(1.03);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    }

    .gallery-overlay {
        display: flex;
        align-items: center;
        justify-content: center;
        background: rgba(0, 0, 0, 0.5);
        opacity: 0;
        transition: opacity 0.3s ease;
    }

    .gallery-overlay i {
        font-size: 24px;
        color: white;
    }

    .gallery-item:hover .gallery-overlay {
        opacity: 1;
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
                        <button class="btn btn-primary" onclick="openModal('{{ asset($destination->images->first()->image_path) }}')">
                            View All Images
                        </button>
                    </div>
                    <div class="gallery-grid">
                        @foreach($destination->images as $image)
                            <div class="gallery-item" onclick="openModal('{{ asset($image->image_path) }}')">
                                <img src="{{ asset($image->image_path) }}" alt="Gallery image of {{ $destination->name }}">
                                <div class="gallery-overlay">
                                    <i class="fas fa-expand"></i>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <div class="mt-6">
                <a href="{{ route('tourist.destinations.index') }}" class="btn-back">
                    <i class="fas fa-arrow-left"></i>
                    Back to Destinations
                </a>
            </div>
        </div>
    </div>

    <!-- Image Modal -->
    <div id="imageModal" class="modal" onclick="closeModal()">
        <div class="modal-content" onclick="event.stopPropagation()">
            <span class="modal-close" onclick="closeModal()">&times;</span>
            <img id="modalImage" class="modal-image" src="" alt="Enlarged destination image">
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
function openModal(imageSrc) {
    const modal = document.getElementById('imageModal');
    const modalImg = document.getElementById('modalImage');
    
    modalImg.src = imageSrc;
    modal.classList.add('active');
    document.body.style.overflow = 'hidden'; // Prevent scrolling
}

function closeModal() {
    const modal = document.getElementById('imageModal');
    modal.classList.remove('active');
    document.body.style.overflow = 'auto'; // Re-enable scrolling
}

// Close modal with Escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeModal();
    }
});

// Prevent modal from closing when clicking modal content
document.querySelector('.modal-content').addEventListener('click', function(e) {
    e.stopPropagation();
});
</script>
@endsection 