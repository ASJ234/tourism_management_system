@extends('layouts.admin')

@section('content')
<div class="container-fluid px-4">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0">Manage Destination Images</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.destinations') }}">Destinations</a></li>
                    <li class="breadcrumb-item active">{{ $destination->name }}</li>
                </ol>
            </nav>
        </div>
        <a href="{{ route('admin.destinations') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Back to Destinations
        </a>
    </div>

    <!-- Upload Section -->
    <div class="card mb-4">
        <div class="card-header">
            <h5 class="card-title mb-0">Upload Images</h5>
        </div>
        <div class="card-body">
            <form id="uploadForm" action="{{ route('admin.destinations.upload-images', $destination) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="upload-area" id="dropZone">
                    <div class="upload-content text-center">
                        <i class="fas fa-cloud-upload-alt fa-3x mb-3 text-primary"></i>
                        <h4>Drag & Drop Images Here</h4>
                        <p class="text-muted">or</p>
                        <label class="btn btn-primary">
                            Select Files
                            <input type="file" name="images[]" multiple accept="image/jpeg,image/jpg,image/png" style="display: none;">
                        </label>
                        <div class="mt-3">
                            <small class="text-muted">Accepted formats: JPEG, JPG, PNG (Max size: 10MB)</small>
                        </div>
                    </div>
                </div>
                <div id="fileList" class="mt-3"></div>
            </form>
        </div>
    </div>

    <!-- Image Gallery -->
    <div class="card">
        <div class="card-header">
            <h5 class="card-title mb-0">Current Images ({{ $images->count() }})</h5>
        </div>
        <div class="card-body">
            <div class="row g-4">
                @forelse($images as $image)
                    <div class="col-md-4 col-lg-3">
                        <div class="image-card">
                            <img src="{{ asset($image->image_path) }}" alt="Destination Image" class="img-fluid rounded">
                            <div class="image-actions">
                                <button class="btn btn-sm btn-light" onclick="viewImage('{{ asset($image->image_path) }}')">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <form action="{{ route('admin.destinations.delete-image', ['destination' => $destination->destination_id, 'image' => $image->image_id]) }}" 
                                      method="POST" 
                                      class="d-inline"
                                      onsubmit="return confirm('Are you sure you want to delete this image?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12 text-center py-5">
                        <i class="fas fa-image fa-3x text-muted mb-3"></i>
                        <h4>No Images Uploaded</h4>
                        <p class="text-muted">Start by uploading images for this destination</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>

<!-- Image Preview Modal -->
<div class="modal fade" id="imageModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Image Preview</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center">
                <img id="previewImage" src="" alt="Preview" class="img-fluid">
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    .upload-area {
        border: 2px dashed #dee2e6;
        border-radius: 8px;
        padding: 40px;
        text-align: center;
        background: #f8f9fa;
        transition: all 0.3s ease;
    }

    .upload-area.dragover {
        border-color: #0d6efd;
        background: #e9ecef;
    }

    .image-card {
        position: relative;
        border-radius: 8px;
        overflow: hidden;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }

    .image-card img {
        width: 100%;
        height: 200px;
        object-fit: cover;
    }

    .image-actions {
        position: absolute;
        top: 10px;
        right: 10px;
        display: none;
    }

    .image-card:hover .image-actions {
        display: block;
    }

    .image-actions .btn {
        padding: 0.25rem 0.5rem;
        margin-left: 5px;
    }

    #fileList {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
        gap: 1rem;
    }

    .file-item {
        background: #f8f9fa;
        padding: 10px;
        border-radius: 4px;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .file-item .file-name {
        margin-right: 10px;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    .file-item .remove-file {
        color: #dc3545;
        cursor: pointer;
    }
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const dropZone = document.getElementById('dropZone');
    const fileInput = document.querySelector('input[type="file"]');
    const fileList = document.getElementById('fileList');
    const uploadForm = document.getElementById('uploadForm');

    // Drag and drop handlers
    dropZone.addEventListener('dragover', (e) => {
        e.preventDefault();
        dropZone.classList.add('dragover');
    });

    dropZone.addEventListener('dragleave', () => {
        dropZone.classList.remove('dragover');
    });

    dropZone.addEventListener('drop', (e) => {
        e.preventDefault();
        dropZone.classList.remove('dragover');
        const files = e.dataTransfer.files;
        handleFiles(files);
    });

    // File input change handler
    fileInput.addEventListener('change', (e) => {
        handleFiles(e.target.files);
    });

    // Handle selected files
    function handleFiles(files) {
        fileList.innerHTML = '';
        
        Array.from(files).forEach(file => {
            if (file.type.startsWith('image/')) {
                const fileItem = document.createElement('div');
                fileItem.className = 'file-item';
                fileItem.innerHTML = `
                    <span class="file-name">${file.name}</span>
                    <span class="remove-file" onclick="this.parentElement.remove()">
                        <i class="fas fa-times"></i>
                    </span>
                `;
                fileList.appendChild(fileItem);
            }
        });

        // Auto submit form when files are selected
        if (fileList.children.length > 0) {
            uploadForm.submit();
        }
    }

    // Form submit handler
    uploadForm.addEventListener('submit', async (e) => {
        e.preventDefault();
        
        const formData = new FormData(uploadForm);
        
        try {
            const response = await fetch(uploadForm.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            });

            // Create a success alert
            const alertDiv = document.createElement('div');
            alertDiv.className = 'alert alert-success alert-dismissible fade show';
            alertDiv.innerHTML = `
                Images uploaded successfully!
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            `;
            
            // Insert alert at the top of the form
            const cardBody = document.querySelector('.card-body');
            cardBody.insertBefore(alertDiv, cardBody.firstChild);

            // Clear the file list
            fileList.innerHTML = '';
            
            // Reload the page after 2 seconds
            setTimeout(() => {
                window.location.reload();
            }, 2000);

        } catch (error) {
            console.error('Upload error:', error);
            alert('Upload failed. Please try again.');
        }
    });
});

// Image preview function
function viewImage(src) {
    const modal = new bootstrap.Modal(document.getElementById('imageModal'));
    document.getElementById('previewImage').src = src;
    modal.show();
}
</script>
@endpush
@endsection