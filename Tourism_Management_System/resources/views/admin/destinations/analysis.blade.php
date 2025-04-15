@extends('layouts.admin')

@section('title', 'Destination Analytics')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <!-- Tourist Statistics Section -->
            <div class="card mb-4">
                <div class="card-header">
                    <h3 class="card-title">Tourist Statistics</h3>
                    <div class="card-tools">
                        <a href="{{ route('admin.destinations.tourist-statistics') }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-chart-bar"></i> View Detailed Statistics
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Year</th>
                                    <th>Tourists</th>
                                    <th>Total Revenue</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $paymentStats = $paymentStatistics->keyBy('year');
                                @endphp
                                @foreach($touristStatistics as $stat)
                                <tr>
                                    <td>{{ $stat->year }}</td>
                                    <td>{{ $stat->total_tourists }}</td>
                                    <td>
                                        @if(isset($paymentStats[$stat->year]))
                                            ${{ number_format($paymentStats[$stat->year]->total_amount, 2) }}
                                        @else
                                            $0.00
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Destination Analysis Section -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Destination Analysis</h3>
                </div>
                <div class="card-body">
                    <!-- Summary Cards -->
                    <div class="row">
                        <div class="col-md-3">
                            <div class="info-box">
                                <span class="info-box-icon bg-info"><i class="fas fa-map-marker-alt"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Total Destinations</span>
                                    <span class="info-box-number">{{ $totalDestinations }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="info-box">
                                <span class="info-box-icon bg-success"><i class="fas fa-check-circle"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Active Destinations</span>
                                    <span class="info-box-number">{{ $activeDestinations }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="info-box">
                                <span class="info-box-icon bg-warning"><i class="fas fa-clock"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Inactive Destinations</span>
                                    <span class="info-box-number">{{ $inactiveDestinations }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="info-box">
                                <span class="info-box-icon bg-primary"><i class="fas fa-calendar-plus"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Recent (6 months)</span>
                                    <span class="info-box-number">{{ $recentDestinations }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Destinations by Region -->
                    <div class="row mt-4">
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title">Destinations by Region</h3>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <th>Region</th>
                                                    <th>Total Destinations</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($destinationsByRegion as $region)
                                                <tr>
                                                    <td>{{ $region->region }}</td>
                                                    <td>{{ $region->total }}</td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Top Destinations with Most Images -->
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title">Top Destinations with Most Images</h3>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <th>Destination</th>
                                                    <th>Total Images</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($destinationsWithMostImages as $destination)
                                                <tr>
                                                    <td>{{ $destination->name }}</td>
                                                    <td>{{ $destination->images_count }}</td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Charts Section -->
                    <div class="row mt-4">
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title">Destinations Status Distribution</h3>
                                </div>
                                <div class="card-body">
                                    <canvas id="statusChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title">Destinations by Region</h3>
                                </div>
                                <div class="card-body">
                                    <canvas id="regionChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Package Analysis Section -->
            <div class="card mt-4">
                <div class="card-header">
                    <h3 class="card-title">Package Analysis</h3>
                </div>
                <div class="card-body">
                    <!-- Package Summary Cards -->
                    <div class="row">
                        <div class="col-md-4">
                            <div class="info-box">
                                <span class="info-box-icon bg-info"><i class="fas fa-suitcase"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Total Packages</span>
                                    <span class="info-box-number">{{ $packageStatistics['total_packages'] ?? 0 }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="info-box">
                                <span class="info-box-icon bg-success"><i class="fas fa-check-circle"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Active Packages</span>
                                    <span class="info-box-number">{{ $packageStatistics['active_packages'] ?? 0 }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="info-box">
                                <span class="info-box-icon bg-primary"><i class="fas fa-dollar-sign"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Total Revenue</span>
                                    <span class="info-box-number">${{ number_format($packageStatistics['total_revenue'] ?? 0, 2) }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Package Performance Cards -->
                    <div class="row">
                        @forelse($packageStatistics['top_packages'] ?? [] as $package)
                        <div class="col-md-4 mb-4">
                            <div class="card h-100 package-card" style="cursor: pointer;" onclick="window.location.href='{{ route('admin.packages.performance', ['package' => $package->package_id]) }}'">
                                <div class="card-body">
                                    <h5 class="card-title">{{ $package->name }}</h5>
                                    <p class="text-muted">{{ $package->destination->name }}</p>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <h6 class="mb-0">Bookings</h6>
                                            <p class="text-primary mb-0">{{ $package->bookings_count }}</p>
                                        </div>
                                        <div>
                                            <h6 class="mb-0">Revenue</h6>
                                            <p class="text-success mb-0">${{ number_format($package->total_revenue, 2) }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @empty
                        <div class="col-12">
                            <div class="alert alert-info">
                                No package data available
                            </div>
                        </div>
                        @endforelse
                    </div>

                    <!-- Monthly Bookings and Revenue Button -->
                    <div class="row mb-4">
                        <div class="col-12 text-center">
                            <a href="{{ route('admin.packages.monthly') }}" class="btn btn-primary btn-lg">
                                <i class="fas fa-chart-line me-2"></i>View Monthly Bookings and Revenue
                            </a>
                        </div>
                    </div>

                    <!-- Package Performance Trend -->
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Package Performance Trend</h3>
                        </div>
                        <div class="card-body">
                            <canvas id="packagePerformanceChart"></canvas>
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
    // Status Distribution Chart
    const statusCtx = document.getElementById('statusChart').getContext('2d');
    new Chart(statusCtx, {
        type: 'doughnut',
        data: {
            labels: ['Active', 'Inactive'],
            datasets: [{
                data: [{{ $activeDestinations }}, {{ $inactiveDestinations }}],
                backgroundColor: ['#28a745', '#ffc107']
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false
        }
    });

    // Region Distribution Chart
    const regionCtx = document.getElementById('regionChart').getContext('2d');
    new Chart(regionCtx, {
        type: 'bar',
        data: {
            labels: {!! json_encode($destinationsByRegion->pluck('region')) !!},
            datasets: [{
                label: 'Number of Destinations',
                data: {!! json_encode($destinationsByRegion->pluck('total')) !!},
                backgroundColor: '#007bff'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1
                    }
                }
            }
        }
    });

    // Package Performance Chart
    const packageCtx = document.getElementById('packagePerformanceChart').getContext('2d');
    new Chart(packageCtx, {
        type: 'line',
        data: {
            labels: {!! json_encode($packageStatistics['performance_data']->pluck('year')) !!},
            datasets: [{
                label: 'Total Packages',
                data: {!! json_encode($packageStatistics['performance_data']->pluck('total_packages')) !!},
                borderColor: 'rgba(255, 99, 132, 1)',
                backgroundColor: 'rgba(255, 99, 132, 0.2)',
                borderWidth: 2,
                tension: 0.1,
                fill: true
            }, {
                label: 'Total Tourists',
                data: {!! json_encode($packageStatistics['performance_data']->pluck('total_bookings')) !!},
                borderColor: 'rgba(153, 102, 255, 1)',
                backgroundColor: 'rgba(153, 102, 255, 0.2)',
                borderWidth: 2,
                tension: 0.1,
                fill: true
            }, {
                label: 'Total Revenue',
                data: {!! json_encode($packageStatistics['performance_data']->pluck('total_revenue')) !!},
                borderColor: 'rgba(255, 206, 86, 1)',
                backgroundColor: 'rgba(255, 206, 86, 0.2)',
                borderWidth: 2,
                tension: 0.1,
                fill: true,
                yAxisID: 'y1'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            interaction: {
                mode: 'index',
                intersect: false
            },
            scales: {
                y: {
                    type: 'linear',
                    display: true,
                    position: 'left',
                    title: {
                        display: true,
                        text: 'Count'
                    }
                },
                y1: {
                    type: 'linear',
                    display: true,
                    position: 'right',
                    title: {
                        display: true,
                        text: 'Revenue ($)'
                    },
                    grid: {
                        drawOnChartArea: false
                    }
                }
            }
        }
    });
</script>
@endpush

@push('styles')
<style>
    .info-box {
        box-shadow: 0 0 1px rgba(0,0,0,.125), 0 1px 3px rgba(0,0,0,.2);
        border-radius: .25rem;
        background-color: #fff;
        display: flex;
        margin-bottom: 1rem;
        min-height: 80px;
        padding: .5rem;
        position: relative;
    }
    .info-box-icon {
        border-radius: .25rem;
        display: flex;
        font-size: 1.875rem;
        justify-content: center;
        text-align: center;
        width: 70px;
        align-items: center;
    }
    .info-box-content {
        display: flex;
        flex-direction: column;
        justify-content: center;
        line-height: 1.8;
        padding: 0 10px;
        flex: 1;
    }
    .info-box-text {
        display: block;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }
    .info-box-number {
        display: block;
        font-weight: bold;
        font-size: 1.5rem;
    }
</style>
@endpush
