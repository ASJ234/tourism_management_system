@extends('layouts.admin')

@section('content')
<div class="destination-images-container">
    <!-- Header Section -->
    <div class="header">
        <h1>Manage Destination Images</h1>
        <a href="{{ route('admin.dashboard') }}" class="back-button">Back to Dashboard</a>
    </div>

    <!-- Success Message -->
    @if(session('success'))
        <div class="success-message">
            {{ session('success') }}
        </div>
    @endif

    <!-- Destinations Grid -->
    <div class="destinations-grid">
        @foreach($destinations as $destination)
            <div class="destination-card">
                <div class="destination-info">
                    <h2>{{ $destination->name }}</h2>
                    <p>{{ $destination->location }}</p>
                </div>

                <!-- Images Preview -->
                <div class="images-preview">
                    @if($destination->images->count() > 0)
                        <div class="image-grid">
                            @foreach($destination->images->take(4) as $image)
                                <div class="image-item">
                                    <img src="{{ asset($image->image_path) }}" alt="Destination Image">
                                    @if($image->is_primary)
                                        <span class="primary-badge">Primary</span>
                                    @endif
                                </div>
                            @endforeach
                            @if($destination->images->count() > 4)
                                <div class="more-images">
                                    +{{ $destination->images->count() - 4 }} more
                                </div>
                            @endif
                        </div>
                    @else
                        <div class="no-images">
                            No images uploaded yet
                        </div>
                    @endif
                </div>

                <!-- Action Buttons -->
                <div class="card-actions">
                    <a href="{{ route('admin.destinations.images', $destination) }}" class="manage-button">
                        Manage Images
                    </a>
                    <span class="image-count">
                        {{ $destination->images->count() }} images
                    </span>
                </div>
            </div>
        @endforeach
    </div>
</div>

<style>
    /* Main Container */
    .destination-images-container {
        padding: 20px;
        max-width: 1200px;
        margin: 0 auto;
    }

    /* Header Styles */
    .header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 30px;
    }

    .header h1 {
        font-size: 24px;
        color: #333;
        margin: 0;
    }

    .back-button {
        background-color: #666;
        color: white;
        padding: 8px 16px;
        border-radius: 4px;
        text-decoration: none;
        transition: background-color 0.3s;
    }

    .back-button:hover {
        background-color: #555;
    }

    /* Success Message */
    .success-message {
        background-color: #d4edda;
        color: #155724;
        padding: 15px;
        border-radius: 4px;
        margin-bottom: 20px;
    }

    /* Destinations Grid */
    .destinations-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
        gap: 20px;
    }

    /* Destination Card */
    .destination-card {
        background: white;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        overflow: hidden;
        transition: transform 0.3s;
    }

    .destination-card:hover {
        transform: translateY(-5px);
    }

    .destination-info {
        padding: 15px;
        border-bottom: 1px solid #eee;
    }

    .destination-info h2 {
        margin: 0 0 5px 0;
        font-size: 18px;
        color: #333;
    }

    .destination-info p {
        margin: 0;
        color: #666;
        font-size: 14px;
    }

    /* Images Preview */
    .images-preview {
        padding: 15px;
    }

    .image-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 10px;
    }

    .image-item {
        position: relative;
        aspect-ratio: 1;
    }

    .image-item img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        border-radius: 4px;
    }

    .primary-badge {
        position: absolute;
        top: 5px;
        right: 5px;
        background: #ffd700;
        color: #000;
        padding: 2px 6px;
        border-radius: 3px;
        font-size: 12px;
    }

    .more-images {
        background: #f5f5f5;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 4px;
        color: #666;
        font-size: 14px;
    }

    .no-images {
        background: #f5f5f5;
        padding: 20px;
        text-align: center;
        color: #666;
        border-radius: 4px;
    }

    /* Card Actions */
    .card-actions {
        padding: 15px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        border-top: 1px solid #eee;
    }

    .manage-button {
        background-color: #007bff;
        color: white;
        padding: 8px 16px;
        border-radius: 4px;
        text-decoration: none;
        transition: background-color 0.3s;
    }

    .manage-button:hover {
        background-color: #0056b3;
    }

    .image-count {
        color: #666;
        font-size: 14px;
    }
</style>

<script>
    // Add hover effect to destination cards
    document.addEventListener('DOMContentLoaded', function() {
        const cards = document.querySelectorAll('.destination-card');
        
        cards.forEach(card => {
            card.addEventListener('mouseenter', function() {
                this.style.transform = 'translateY(-5px)';
            });
            
            card.addEventListener('mouseleave', function() {
                this.style.transform = 'translateY(0)';
            });
        });

        // Add click effect to manage buttons
        const manageButtons = document.querySelectorAll('.manage-button');
        
        manageButtons.forEach(button => {
            button.addEventListener('click', function(e) {
                this.style.transform = 'scale(0.95)';
                setTimeout(() => {
                    this.style.transform = 'scale(1)';
                }, 100);
            });
        });
    });
</script>
@endsection 