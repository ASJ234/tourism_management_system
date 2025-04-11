@extends('layouts.admin')

@section('content')
<div class="container-fluid px-4">
    <!-- Header Section -->
    <div class="header-section mb-4">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h1 class="display-4 fw-bold text-gradient mb-2">Manage Bookings</h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}" class="text-decoration-none">Dashboard</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Bookings</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>

    <!-- Notifications -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show glass-alert" role="alert">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Stats Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="stat-card">
                <div class="stat-icon bg-primary">
                    <i class="fas fa-calendar-check"></i>
                </div>
                <div class="stat-content">
                    <h3>{{ $stats['total'] }}</h3>
                    <p>Total Bookings</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card">
                <div class="stat-icon bg-success">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div class="stat-content">
                    <h3>{{ $stats['paid'] }}</h3>
                    <p>Paid Bookings</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card">
                <div class="stat-icon bg-warning">
                    <i class="fas fa-clock"></i>
                </div>
                <div class="stat-content">
                    <h3>{{ $stats['pending'] }}</h3>
                    <p>Pending Bookings</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card">
                <div class="stat-icon bg-danger">
                    <i class="fas fa-times-circle"></i>
                </div>
                <div class="stat-content">
                    <h3>{{ $stats['cancelled'] }}</h3>
                    <p>Cancelled Bookings</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters Section -->
    <div class="card glass-card mb-4">
        <div class="card-header glass-header">
            <h5 class="card-title mb-0">
                <i class="fas fa-filter me-2"></i>Filter Bookings
            </h5>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.bookings.index') }}" method="GET" class="row g-3">
                <div class="col-md-3">
                    <label class="form-label">Tour Package</label>
                    <select name="package_id" class="form-select">
                        <option value="">All Packages</option>
                        @foreach($tourPackages as $package)
                            <option value="{{ $package->package_id }}" {{ request('package_id') == $package->package_id ? 'selected' : '' }}>
                                {{ $package->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-select">
                        <option value="">All Status</option>
                        <option value="Pending" {{ request('status') == 'Pending' ? 'selected' : '' }}>Pending</option>
                        <option value="Paid" {{ request('status') == 'Paid' ? 'selected' : '' }}>Paid</option>
                        <option value="Approved" {{ request('status') == 'Approved' ? 'selected' : '' }}>Approved</option>
                        <option value="Cancelled" {{ request('status') == 'Cancelled' ? 'selected' : '' }}>Cancelled</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Date</label>
                    <input type="date" name="date" class="form-control" value="{{ request('date') }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Search</label>
                    <input type="text" name="search" class="form-control" placeholder="Search by ID or tourist name..." value="{{ request('search') }}">
                </div>
                <div class="col-md-3 d-flex align-items-end">
                    <div class="d-flex gap-2 w-100">
                        <button type="submit" class="btn btn-primary flex-grow-1">
                            <i class="fas fa-search me-2"></i>Filter
                        </button>
                        @if(request()->hasAny(['status', 'date', 'search']))
                            <a href="{{ route('admin.bookings.index') }}" class="btn btn-secondary">
                                <i class="fas fa-times me-2"></i>Clear
                            </a>
                        @endif
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Bookings Table -->
    <div class="card glass-card">
        <div class="card-header glass-header d-flex justify-content-between align-items-center">
            <h5 class="card-title mb-0">
                <i class="fas fa-calendar-check me-2"></i>All Bookings
            </h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Booking ID</th>
                            <th>Tourist</th>
                            <th>Package</th>
                            <th>Date</th>
                            <th>Amount</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($bookings as $booking)
                            <tr>
                                <td>
                                    <span class="fw-bold">#{{ $booking->booking_id }}</span>
                                </td>
                                <td>
                                    @if($booking->user)
                                        <div class="fw-bold">{{ $booking->user->full_name ?? 'Unknown User' }}</div>
                                    @else
                                        <div class="fw-bold">Unknown User</div>
                                    @endif
                                </td>
                                <td>
                                    @if($booking->package)
                                        <div class="fw-bold">{{ $booking->package->name ?? 'Unknown Package' }}</div>
                                        <small class="text-muted">{{ $booking->package->destination->name ?? 'Unknown Destination' }}</small>
                                    @else
                                        <div class="fw-bold">Unknown Package</div>
                                        <small class="text-muted">Unknown Destination</small>
                                    @endif
                                </td>
                                <td>
                                    <div>{{ \Carbon\Carbon::parse($booking->booking_date)->format('M d, Y') }}</div>
                                    <small class="text-muted">{{ \Carbon\Carbon::parse($booking->booking_date)->format('h:i A') }}</small>
                                </td>
                                <td>
                                    <div class="fw-bold">${{ number_format($booking->total_price, 2) }}</div>
                                    @if($booking->status === 'Paid' || $booking->status === 'paid')
                                        <small class="text-success"><i class="fas fa-check-circle"></i> Paid</small>
                                    @endif
                                </td>
                                <td>
                                    @php
                                        $statusColors = [
                                            'pending' => 'warning',
                                            'Pending' => 'warning',
                                            'paid' => 'success',
                                            'Paid' => 'success',
                                            'cancelled' => 'danger',
                                            'Cancelled' => 'danger',
                                            'approved' => 'info',
                                            'Approved' => 'info'
                                        ];
                                        $statusColor = $statusColors[strtolower($booking->status)] ?? 'secondary';
                                    @endphp
                                    <span class="badge rounded-pill bg-{{ $statusColor }} text-white">
                                        {{ ucfirst($booking->status) }}
                                    </span>
                                </td>
                                <td>
                                    <div class="btn-group">
                                        <button type="button" class="btn btn-sm btn-light" onclick="showBookingDetails({{ $booking->booking_id }})">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        @if($booking->status === 'paid' || $booking->status === 'Paid')
                                            <form action="{{ route('admin.bookings.approve', $booking->booking_id) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('PUT')
                                                <button type="submit" class="btn btn-sm btn-success" onclick="return confirm('Are you sure you want to approve this booking?')">
                                                    <i class="fas fa-check"></i>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>

                            <!-- Simple Modal -->
                            <div id="bookingDetails{{ $booking->booking_id }}" class="custom-modal" style="display: none;">
                                <div class="custom-modal-content glass-card">
                                    <div class="modal-header">
                                        <h5>Booking Details #{{ $booking->booking_id }}</h5>
                                        <button type="button" class="modal-close" onclick="hideBookingDetails({{ $booking->booking_id }})">×</button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <h6 class="text-muted mb-3">Tourist Information</h6>
                                                <p><strong>Name:</strong> {{ $booking->user->full_name ?? 'N/A' }}</p>
                                                <p><strong>Email:</strong> {{ $booking->user->email ?? 'N/A' }}</p>
                                                <p><strong>Phone:</strong> {{ $booking->user->phone ?? 'N/A' }}</p>
                                            </div>
                                            <div class="col-md-6">
                                                <h6 class="text-muted mb-3">Package Details</h6>
                                                <p><strong>Package:</strong> {{ $booking->package->name ?? 'N/A' }}</p>
                                                <p><strong>Destination:</strong> {{ $booking->package->destination->name ?? 'N/A' }}</p>
                                                <p><strong>Duration:</strong> {{ $booking->package->duration_days ?? 'N/A' }} days</p>
                                            </div>
                                        </div>
                                        <hr>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <h6 class="text-muted mb-3">Booking Information</h6>
                                                <p><strong>Date:</strong> {{ \Carbon\Carbon::parse($booking->booking_date)->format('M d, Y h:i A') }}</p>
                                                <p><strong>Status:</strong> <span class="badge bg-{{ $statusColor }}">{{ ucfirst($booking->status) }}</span></p>
                                            </div>
                                            <div class="col-md-6">
                                                <h6 class="text-muted mb-3">Additional Information</h6>
                                                <p><strong>Number of Travelers:</strong> {{ $booking->number_of_travelers ?? 'N/A' }}</p>
                                                <p><strong>Special Requests:</strong> {{ $booking->special_requests ?? 'None' }}</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" onclick="hideBookingDetails({{ $booking->booking_id }})">Close</button>
                                        @if($booking->status === 'paid' || $booking->status === 'Paid')
                                            <form action="{{ route('admin.bookings.approve', $booking->booking_id) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('PUT')
                                                <button type="submit" class="btn btn-success">
                                                    <i class="fas fa-check me-1"></i> Approve Booking
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-4">
                                    <div class="empty-state">
                                        <i class="fas fa-calendar-times fa-3x text-muted mb-3"></i>
                                        <h5>No Bookings Found</h5>
                                        <p class="text-muted">Try adjusting your filters</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="card-footer glass-header">
                {{ $bookings->links() }}
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    /* Glassmorphism Styles */
    .glass-card {
        background: rgba(255, 255, 255, 0.8);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.3);
        box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.15);
    }

    .glass-header {
        background: rgba(255, 255, 255, 0.9);
        border-bottom: 1px solid rgba(255, 255, 255, 0.3);
    }

    .glass-alert {
        background: rgba(255, 255, 255, 0.9);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.3);
    }

    /* Text Gradient */
    .text-gradient {
        background: linear-gradient(45deg, #2193b0, #6dd5ed);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }

    /* Stat Cards */
    .stat-card {
        background: white;
        border-radius: 15px;
        padding: 20px;
        display: flex;
        align-items: center;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        transition: transform 0.3s ease;
    }

    .stat-card:hover {
        transform: translateY(-5px);
    }

    .stat-icon {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 20px;
        color: white;
        font-size: 24px;
    }

    .stat-content h3 {
        margin: 0;
        font-size: 24px;
        font-weight: bold;
    }

    .stat-content p {
        margin: 0;
        color: #6c757d;
    }

    /* Avatar Circle */
    .avatar-circle {
        width: 40px;
        height: 40px;
        background: linear-gradient(45deg, #2193b0, #6dd5ed);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: bold;
    }

    /* Status Colors */
    .badge {
        padding: 8px 12px;
        font-weight: 500;
        letter-spacing: 0.3px;
    }
    .bg-warning { 
        background-color: #ffc107 !important; 
        color: #000 !important;
    }
    .bg-success { 
        background-color: #28a745 !important; 
        color: #fff !important;
    }
    .bg-info { 
        background-color: #17a2b8 !important; 
        color: #fff !important;
    }
    .bg-danger { 
        background-color: #dc3545 !important; 
        color: #fff !important;
    }

    /* Table Styles */
    .table {
        margin-bottom: 0;
    }

    .table th {
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.85rem;
        letter-spacing: 0.5px;
    }

    .table td {
        vertical-align: middle;
    }

    /* Button Styles */
    .btn-group .btn {
        padding: 0.375rem 0.75rem;
    }

    /* Empty State */
    .empty-state {
        padding: 40px 20px;
    }

    /* Form Styles */
    .form-control, .form-select {
        border-radius: 8px;
        border: 1px solid rgba(0, 0, 0, 0.1);
        padding: 0.5rem 1rem;
    }

    .form-control:focus, .form-select:focus {
        border-color: #2193b0;
        box-shadow: 0 0 0 0.2rem rgba(33, 147, 176, 0.25);
    }

    /* Responsive Adjustments */
    @media (max-width: 768px) {
        .stat-card {
            margin-bottom: 1rem;
        }
        
        .table-responsive {
            margin: 0 -1rem;
        }
    }

    /* Custom Modal Styles */
    .custom-modal {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.5);
        z-index: 1050;
        overflow-y: auto;
        padding: 20px;
    }

    .custom-modal-content {
        position: relative;
        width: 100%;
        max-width: 800px;
        margin: 40px auto;
        background: #fff;
        border-radius: 8px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }

    .modal-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 1rem;
        border-bottom: 1px solid #dee2e6;
    }

    .modal-close {
        background: none;
        border: none;
        font-size: 1.5rem;
        cursor: pointer;
        padding: 0;
        margin: 0;
        width: 30px;
        height: 30px;
        line-height: 30px;
        text-align: center;
        border-radius: 50%;
    }

    .modal-close:hover {
        background-color: #f8f9fa;
    }

    .modal-body {
        padding: 1rem;
    }

    .modal-footer {
        padding: 1rem;
        border-top: 1px solid #dee2e6;
        display: flex;
        justify-content: flex-end;
        gap: 10px;
    }

    body.modal-open {
        overflow: hidden;
    }
</style>
@endpush

@push('scripts')
<script>
function showBookingDetails(bookingId) {
    const modal = document.getElementById('bookingDetails' + bookingId);
    if (modal) {
        modal.style.display = 'block';
        document.body.classList.add('modal-open');
        
        // Close on escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                hideBookingDetails(bookingId);
            }
        });

        // Close on outside click
        modal.addEventListener('click', function(e) {
            if (e.target === modal) {
                hideBookingDetails(bookingId);
            }
        });
    }
}

function hideBookingDetails(bookingId) {
    const modal = document.getElementById('bookingDetails' + bookingId);
    if (modal) {
        modal.style.display = 'none';
        document.body.classList.remove('modal-open');
    }
}
</script>
@endpush
@endsection