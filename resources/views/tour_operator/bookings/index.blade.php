@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header bg-white border-bottom-0 py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h3 class="card-title mb-0">
                            <i class="fas fa-calendar-check text-primary me-2"></i>
                            Paid Bookings
                        </h3>
                        <div class="badge bg-gradient-success rounded-pill px-3 py-2">
                            <i class="fas fa-check-circle me-1"></i>
                            Total Paid Bookings: {{ $paidBookingsCount }}
                        </div>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th class="border-0 py-3 px-4">Booking ID</th>
                                    <th class="border-0 py-3 px-4">Tourist</th>
                                    <th class="border-0 py-3 px-4">Package</th>
                                    <th class="border-0 py-3 px-4">Date</th>
                                    <th class="border-0 py-3 px-4">Travelers</th>
                                    <th class="border-0 py-3 px-4">Amount</th>
                                    <th class="border-0 py-3 px-4">Status</th>
                                    <th class="border-0 py-3 px-4 text-end">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($bookings as $booking)
                                    <tr class="border-bottom">
                                        <td class="py-3 px-4">
                                            <span class="text-primary fw-bold">#{{ $booking->booking_id }}</span>
                                        </td>
                                        <td class="py-3 px-4">
                                            <div class="d-flex align-items-center">
                                                <div class="avatar-sm bg-primary bg-opacity-10 rounded-circle me-2 d-flex align-items-center justify-content-center">
                                                    <i class="fas fa-user text-primary"></i>
                                                </div>
                                                <div>
                                                    <h6 class="mb-0">{{ $booking->user->full_name }}</h6>
                                                    <small class="text-muted">{{ $booking->user->email }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="py-3 px-4">
                                            <div>
                                                <h6 class="mb-0">{{ $booking->package->name }}</h6>
                                                <small class="text-muted">{{ $booking->package->destination->name }}</small>
                                            </div>
                                        </td>
                                        <td class="py-3 px-4">
                                            <div>
                                                <h6 class="mb-0">{{ \Carbon\Carbon::parse($booking->booking_date)->format('M d, Y') }}</h6>
                                                <small class="text-muted">{{ \Carbon\Carbon::parse($booking->booking_date)->format('h:i A') }}</small>
                                            </div>
                                        </td>
                                        <td class="py-3 px-4">
                                            <span class="badge bg-info bg-opacity-10 text-info px-3 py-2">
                                                <i class="fas fa-users me-1"></i>
                                                {{ $booking->number_of_travelers }}
                                            </span>
                                        </td>
                                        <td class="py-3 px-4">
                                            <span class="fw-bold text-success">
                                                ${{ number_format($booking->total_price, 2) }}
                                            </span>
                                        </td>
                                        <td class="py-3 px-4">
                                            <span class="badge rounded-pill bg-{{ $booking->status === 'Confirmed' ? 'success' : ($booking->status === 'Pending' ? 'warning' : 'danger') }} px-3 py-2">
                                                <i class="fas fa-circle me-1 small"></i>
                                                {{ $booking->status }}
                                            </span>
                                        </td>
                                        <td class="py-3 px-4 text-end">
                                            <a href="{{ route('tour_operator.bookings.show', $booking) }}" 
                                               class="btn btn-sm btn-light border px-3 py-2 rounded-pill">
                                                <i class="fas fa-eye text-primary me-1"></i>
                                                View Details
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center py-5">
                                            <div class="empty-state">
                                                <div class="empty-state-icon bg-light rounded-circle mb-3">
                                                    <i class="fas fa-calendar-times fa-3x text-muted"></i>
                                                </div>
                                                <h5 class="mb-2">No Paid Bookings Found</h5>
                                                <p class="text-muted">There are no paid bookings at the moment.</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer bg-white border-top-0 py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="text-muted">
                            Showing {{ $bookings->firstItem() ?? 0 }} to {{ $bookings->lastItem() ?? 0 }} of {{ $bookings->total() }} entries
                        </div>
                        <div>
                            {{ $bookings->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    /* Table Container */
    .table-responsive {
        border-radius: 8px;
        overflow: hidden;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
        background: white;
    }

    /* Table Styles */
    .table {
        margin-bottom: 0;
        background: white;
        border-collapse: separate;
        border-spacing: 0;
    }

    .table thead th {
        background: #f8f9fa;
        color: #495057;
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.85rem;
        letter-spacing: 0.5px;
        padding: 1rem;
        border-bottom: 2px solid #dee2e6;
        position: sticky;
        top: 0;
        z-index: 1;
    }

    .table tbody td {
        padding: 1rem;
        vertical-align: middle;
        border-bottom: 1px solid #f0f0f0;
        color: #495057;
        transition: all 0.2s ease;
    }

    .table tbody tr:hover {
        background-color: #f8f9fa;
    }

    .table tbody tr:hover td {
        background-color: #f8f9fa;
    }

    /* Status Badges */
    .badge {
        padding: 0.5rem 0.75rem;
        font-weight: 500;
        border-radius: 6px;
        font-size: 0.85rem;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }

    .badge.bg-success {
        background-color: #d4edda !important;
        color: #155724 !important;
    }

    .badge.bg-warning {
        background-color: #fff3cd !important;
        color: #856404 !important;
    }

    .badge.bg-danger {
        background-color: #f8d7da !important;
        color: #721c24 !important;
    }

    /* Action Buttons */
    .btn-light {
        background-color: #f8f9fa;
        border: 1px solid #dee2e6;
        color: #495057;
        padding: 0.5rem 1rem;
        border-radius: 6px;
        transition: all 0.2s ease;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }

    .btn-light:hover {
        background-color: #e9ecef;
        border-color: #ced4da;
        transform: translateY(-1px);
    }

    /* Card Styles */
    .card {
        border: none;
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
        border-radius: 8px;
    }

    .card-header {
        background: #f8f9fa;
        border-bottom: 1px solid #dee2e6;
        padding: 1.25rem;
        border-radius: 8px 8px 0 0 !important;
    }

    .card-title {
        margin: 0;
        color: #495057;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    /* Empty State */
    .empty-state {
        padding: 3rem 1rem;
        text-align: center;
        background: #f8f9fa;
        border-radius: 8px;
    }

    .empty-state-icon {
        width: 80px;
        height: 80px;
        margin: 0 auto 1.5rem;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #e9ecef;
        border-radius: 50%;
    }

    .empty-state-icon i {
        font-size: 2.5rem;
        color: #6c757d;
    }

    /* Pagination */
    .pagination {
        margin: 1rem 0 0;
        display: flex;
        justify-content: center;
    }

    .pagination .page-link {
        color: #495057;
        border: 1px solid #dee2e6;
        padding: 0.5rem 0.75rem;
        margin: 0 0.25rem;
        border-radius: 4px;
        transition: all 0.2s ease;
    }

    .pagination .page-item.active .page-link {
        background-color: #007bff;
        border-color: #007bff;
        color: white;
    }

    /* Responsive Adjustments */
    @media (max-width: 768px) {
        .table-responsive {
            margin: 0 -1rem;
        }
        
        .table th, .table td {
            padding: 0.75rem;
            white-space: nowrap;
        }
    }

    /* Custom Scrollbar */
    .table-responsive::-webkit-scrollbar {
        height: 6px;
    }

    .table-responsive::-webkit-scrollbar-track {
        background: #f1f1f1;
    }

    .table-responsive::-webkit-scrollbar-thumb {
        background: #888;
        border-radius: 3px;
    }

    .table-responsive::-webkit-scrollbar-thumb:hover {
        background: #555;
    }

    /* Avatar Styles */
    .avatar-sm {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        background: #e9ecef;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #495057;
    }
</style>
@endsection