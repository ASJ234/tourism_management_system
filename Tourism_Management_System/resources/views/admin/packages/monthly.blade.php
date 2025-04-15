@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Monthly Bookings and Revenue</h3>
                    <div class="card-tools">
                        <a href="{{ route('admin.destinations.analysis') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Back to Analysis
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Overall Monthly Performance -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title">Monthly Bookings</h3>
                                </div>
                                <div class="card-body">
                                    <canvas id="monthlyBookingsChart"></canvas>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title">Monthly Revenue</h3>
                                </div>
                                <div class="card-body">
                                    <canvas id="monthlyRevenueChart"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Package-wise Monthly Performance -->
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Package-wise Monthly Performance</h3>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover">
                                    <thead>
                                        <tr>
                                            <th>Package</th>
                                            <th>Month</th>
                                            <th>Bookings</th>
                                            <th>Revenue</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($packageMonthlyData as $data)
                                        <tr>
                                            <td>{{ $data->package_name }}</td>
                                            <td>{{ $data->month }}</td>
                                            <td>{{ $data->bookings_count }}</td>
                                            <td>${{ number_format($data->revenue, 2) }}</td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="4" class="text-center">No data available</td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Monthly Bookings Chart
    const monthlyBookingsCtx = document.getElementById('monthlyBookingsChart').getContext('2d');
    new Chart(monthlyBookingsCtx, {
        type: 'bar',
        data: {
            labels: {!! json_encode($monthlyData->pluck('month')) !!},
            datasets: [{
                label: 'Bookings',
                data: {!! json_encode($monthlyData->pluck('bookings_count')) !!},
                backgroundColor: 'rgba(54, 162, 235, 0.5)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: 'Number of Bookings'
                    }
                }
            }
        }
    });

    // Monthly Revenue Chart
    const monthlyRevenueCtx = document.getElementById('monthlyRevenueChart').getContext('2d');
    new Chart(monthlyRevenueCtx, {
        type: 'bar',
        data: {
            labels: {!! json_encode($monthlyData->pluck('month')) !!},
            datasets: [{
                label: 'Revenue',
                data: {!! json_encode($monthlyData->pluck('revenue')) !!},
                backgroundColor: 'rgba(75, 192, 192, 0.5)',
                borderColor: 'rgba(75, 192, 192, 1)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: 'Revenue ($)'
                    }
                }
            }
        }
    });
});
</script>
@endpush 