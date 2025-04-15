@extends('layouts.app')

@section('styles')
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
<style>
    .packages-container {
        padding: 2rem;
    }

    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 2rem;
    }

    .page-title {
        font-size: 1.875rem;
        font-weight: 600;
        color: #1f2937;
    }

    .btn-create {
        background-color: #2563eb;
        color: white;
        padding: 0.75rem 1.5rem;
        border-radius: 0.5rem;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        transition: all 0.2s;
    }

    .btn-create:hover {
        background-color: #1d4ed8;
        color: white;
    }

    .packages-table {
        width: 100%;
        background: white;
        border-radius: 0.5rem;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        overflow: hidden;
    }

    .packages-table th {
        background-color: #f8fafc;
        padding: 1rem;
        text-align: left;
        font-weight: 600;
        color: #1f2937;
        border-bottom: 1px solid #e5e7eb;
    }

    .packages-table td {
        padding: 1rem;
        border-bottom: 1px solid #e5e7eb;
        color: #4b5563;
    }

    .status-badge {
        display: inline-flex;
        align-items: center;
        padding: 0.375rem 0.75rem;
        border-radius: 9999px;
        font-size: 0.875rem;
        font-weight: 500;
    }

    .status-active {
        background-color: #dcfce7;
        color: #166534;
    }

    .status-inactive {
        background-color: #fee2e2;
        color: #991b1b;
    }

    .action-buttons {
        display: flex;
        gap: 0.5rem;
    }

    .btn-action {
        padding: 0.5rem;
        border-radius: 0.375rem;
        color: white;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s;
    }

    .btn-view {
        background-color: #10b981;
    }

    .btn-view:hover {
        background-color: #059669;
    }

    .btn-edit {
        background-color: #3b82f6;
    }

    .btn-edit:hover {
        background-color: #2563eb;
    }

    .btn-delete {
        background-color: #ef4444;
    }

    .btn-delete:hover {
        background-color: #dc2626;
    }

    .price {
        font-weight: 600;
        color: #059669;
    }

    .empty-state {
        text-align: center;
        padding: 3rem;
    }

    .empty-state i {
        font-size: 3rem;
        color: #d1d5db;
        margin-bottom: 1rem;
    }

    .empty-state p {
        color: #6b7280;
        margin-bottom: 1.5rem;
    }
</style>
@endsection

@section('content')
<div class="packages-container">
    <div class="page-header">
        <h1 class="page-title">Manage Tour Packages</h1>
        <a href="{{ route('tour_operator.packages.create') }}" class="btn-create">
            <i class="fas fa-plus"></i> Add New Package
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if($packages->count() > 0)
        <div class="table-responsive">
            <table class="packages-table">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Destination</th>
                        <th>Price</th>
                        <th>Duration</th>
                        <th>Status</th>
                        <th>Created At</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($packages as $package)
                        <tr>
                            <td>{{ $package->name }}</td>
                            <td>{{ $package->destination->name }}</td>
                            <td class="price">${{ number_format($package->price, 2) }}</td>
                            <td>{{ $package->duration }} days</td>
                            <td>
                                <span class="status-badge status-{{ $package->is_active ? 'active' : 'inactive' }}">
                                    {{ $package->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td>{{ $package->created_at->format('M d, Y') }}</td>
                            <td>
                                <div class="action-buttons">
                                    <a href="{{ route('tour_operator.packages.show', $package) }}" 
                                       class="btn-action btn-view">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('tour_operator.packages.edit', $package) }}" 
                                       class="btn-action btn-edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('tour_operator.packages.destroy', $package) }}" 
                                          method="POST" 
                                          onsubmit="return confirm('Are you sure you want to delete this package?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn-action btn-delete">
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
        <div class="empty-state">
            <i class="fas fa-suitcase"></i>
            <p>No tour packages found. Start by adding a new package!</p>
            <a href="{{ route('tour_operator.packages.create') }}" class="btn-create">
                <i class="fas fa-plus"></i> Add New Package
            </a>
        </div>
    @endif
</div>
@endsection