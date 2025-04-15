@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <!-- Header Section -->
    <div class="header-section mb-4">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h1 class="display-4 fw-bold text-gradient mb-2">Manage Destinations</h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}" class="text-decoration-none">Dashboard</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Destinations</li>
                    </ol>
                </nav>
            </div>
            <a href="{{ route('admin.destinations.create') }}" class="btn btn-primary">
                <i class="fas fa-plus me-2"></i>Add New Destination
            </a>
        </div>
    </div>

    <!-- Notifications -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show glass-alert" role="alert">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Destinations Table -->
    <div class="card glass-card">
        <div class="card-header glass-header">
            <h5 class="card-title mb-0">
                <i class="fas fa-map-marked-alt me-2"></i>All Destinations
            </h5>
        </div>
        <div class="card-body">
            @if($destinations->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Image</th>
                                <th>Name</th>
                                <th>Location</th>
                                <th>Region</th>
                                <th>Status</th>
                                <th>Created At</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($destinations as $destination)
                                <tr>
                                    <td>
                                        <img src="{{ asset($destination->image_url) }}" 
                                             alt="{{ $destination->name }}" 
                                             class="rounded"
                                             style="width: 80px; height: 60px; object-fit: cover;">
                                    </td>
                                    <td>{{ $destination->name }}</td>
                                    <td>{{ $destination->location }}</td>
                                    <td>
                                        <span class="badge bg-info text-capitalize">
                                            {{ str_replace('_', ' ', $destination->region) }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge bg-{{ $destination->is_active ? 'success' : 'danger' }}">
                                            {{ $destination->is_active ? 'Active' : 'Inactive' }}
                                        </span>
                                    </td>
                                    <td>{{ $destination->created_at->format('M d, Y') }}</td>
                                    <td>
                                        <div class="btn-group">
                                            <a href="{{ route('admin.destinations.edit', $destination) }}" 
                                               class="btn btn-sm btn-primary"
                                               data-bs-toggle="tooltip"
                                               title="Edit Destination">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('admin.destinations.delete', $destination) }}" 
                                                  method="POST" 
                                                  class="d-inline"
                                                  onsubmit="return confirm('Are you sure you want to delete this destination?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" 
                                                        class="btn btn-sm btn-danger"
                                                        data-bs-toggle="tooltip"
                                                        title="Delete Destination">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-5">
                    <div class="avatar-circle mx-auto mb-3" style="width: 80px; height: 80px;">
                        <i class="fas fa-map-marked-alt fa-2x"></i>
                    </div>
                    <h4 class="text-muted mb-3">No Destinations Found</h4>
                    <p class="text-muted mb-4">Start by adding a new destination to your collection!</p>
                    <a href="{{ route('admin.destinations.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus me-2"></i>Add New Destination
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Initialize tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl)
    });
</script>
@endpush 