@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <!-- Header Section -->
    <div class="header-section mb-4">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h1 class="display-4 fw-bold text-gradient mb-2">Edit Destination</h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}" class="text-decoration-none">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.destinations') }}" class="text-decoration-none">Destinations</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Edit {{ $destination->name }}</li>
                    </ol>
                </nav>
            </div>
            <a href="{{ route('admin.destinations') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-2"></i>Back to Destinations
            </a>
        </div>
    </div>

    <!-- Form Card -->
    <div class="card glass-card">
        <div class="card-header glass-header">
            <h5 class="card-title mb-0">
                <i class="fas fa-map-marked-alt me-2"></i>Destination Details
            </h5>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.destinations.update', $destination) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="row">
                    <!-- Left Column -->
                    <div class="col-md-8">
                        <div class="mb-3">
                            <label for="name" class="form-label">Destination Name</label>
                            <input type="text" 
                                   id="name" 
                                   name="name" 
                                   class="form-control @error('name') is-invalid @enderror" 
                                   value="{{ old('name', $destination->name) }}" 
                                   required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea id="description" 
                                      name="description" 
                                      class="form-control @error('description') is-invalid @enderror" 
                                      rows="5" 
                                      required>{{ old('description', $destination->description) }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="location" class="form-label">Location</label>
                            <input type="text" 
                                   id="location" 
                                   name="location" 
                                   class="form-control @error('location') is-invalid @enderror" 
                                   value="{{ old('location', $destination->location) }}" 
                                   required>
                            @error('location')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Right Column -->
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="region" class="form-label">Region</label>
                            <select id="region" 
                                    name="region" 
                                    class="form-select @error('region') is-invalid @enderror" 
                                    required>
                                <option value="">Select a region</option>
                                <option value="asia" {{ old('region', $destination->region) == 'asia' ? 'selected' : '' }}>Asia</option>
                                <option value="europe" {{ old('region', $destination->region) == 'europe' ? 'selected' : '' }}>Europe</option>
                                <option value="africa" {{ old('region', $destination->region) == 'africa' ? 'selected' : '' }}>Africa</option>
                                <option value="north_america" {{ old('region', $destination->region) == 'north_america' ? 'selected' : '' }}>North America</option>
                                <option value="south_america" {{ old('region', $destination->region) == 'south_america' ? 'selected' : '' }}>South America</option>
                                <option value="oceania" {{ old('region', $destination->region) == 'oceania' ? 'selected' : '' }}>Oceania</option>
                            </select>
                            @error('region')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="image" class="form-label">Destination Image</label>
                            <input type="file" 
                                   id="image" 
                                   name="image" 
                                   class="form-control @error('image') is-invalid @enderror" 
                                   accept="image/*">
                            @error('image')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            
                            <!-- Current Image -->
                            <div class="mt-3">
                                <label class="form-label text-muted">Current Image</label>
                                <div class="position-relative">
                                    <img src="{{ asset('storage/' . $destination->image_url) }}" 
                                         alt="Current image" 
                                         class="img-fluid rounded">
                                    <div class="position-absolute top-0 end-0 p-2">
                                        <span class="badge bg-dark">Current</span>
                                    </div>
                                </div>
                            </div>

                            <!-- New Image Preview -->
                            <div id="imagePreview" class="mt-3">
                                <label class="form-label text-muted">New Image Preview</label>
                                <img src="" alt="Preview" class="img-fluid rounded" style="display: none;">
                            </div>
                        </div>

                        <div class="mb-3">
                            <div class="form-check form-switch">
                                <input type="checkbox" 
                                       id="is_active" 
                                       name="is_active" 
                                       class="form-check-input" 
                                       value="1" 
                                       {{ old('is_active', $destination->is_active) ? 'checked' : '' }}>
                                <label for="is_active" class="form-check-label">Active</label>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-end gap-2 mt-4">
                    <a href="{{ route('admin.destinations') }}" class="btn btn-secondary">
                        <i class="fas fa-times me-2"></i>Cancel
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-2"></i>Update Destination
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Image preview functionality
    document.getElementById('image').addEventListener('change', function(e) {
        const file = e.target.files[0];
        const preview = document.querySelector('#imagePreview img');
        
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                preview.src = e.target.result;
                preview.style.display = 'block';
            }
            reader.readAsDataURL(file);
        } else {
            preview.style.display = 'none';
        }
    });
</script>
@endpush 