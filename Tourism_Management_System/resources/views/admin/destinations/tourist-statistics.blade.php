@extends('layouts.admin')

@section('title', 'Tourist Statistics')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Tourist Statistics Over the Years</h3>
                    <div class="card-tools">
                        <a href="{{ route('admin.destinations.analysis') }}" class="btn btn-secondary btn-sm">
                            <i class="fas fa-arrow-left"></i> Back to Analysis
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-8">
                            <div class="chart-container" style="position: relative; height:400px;">
                                <canvas id="touristChart"></canvas>
                            </div>
                            <div class="chart-container mt-4" style="position: relative; height:400px;">
                                <canvas id="revenueChart"></canvas>
                            </div>
                            <div class="chart-container mt-4" style="position: relative; height:400px;">
                                <canvas id="packageChart"></canvas>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Year</th>
                                            <th>Tourists</th>
                                            <th>Revenue</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($statistics as $stat)
                                        <tr>
                                            <td>{{ $stat->year }}</td>
                                            <td>{{ $stat->total_tourists }}</td>
                                            <td>
                                                @php
                                                    $payment = $paymentStatistics->where('year', $stat->year)->first();
                                                @endphp
                                                @if($payment)
                                                    ${{ number_format($payment->total_amount, 2) }}
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
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Tourist Chart
    const touristCtx = document.getElementById('touristChart').getContext('2d');
    new Chart(touristCtx, {
        type: 'bar',
        data: {
            labels: {!! json_encode($years) !!},
            datasets: [{
                label: 'Number of Tourists',
                data: {!! json_encode($touristCounts) !!},
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
                    ticks: {
                        stepSize: 1
                    }
                }
            },
            plugins: {
                title: {
                    display: true,
                    text: 'Tourist Growth Over the Years'
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return 'Tourists: ' + context.raw;
                        }
                    }
                }
            }
        }
    });

    // Revenue Chart
    const revenueCtx = document.getElementById('revenueChart').getContext('2d');
    new Chart(revenueCtx, {
        type: 'line',
        data: {
            labels: {!! json_encode($years) !!},
            datasets: [{
                label: 'Total Revenue',
                data: {!! json_encode($paymentAmounts) !!},
                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                borderColor: 'rgba(75, 192, 192, 1)',
                borderWidth: 2,
                tension: 0.1,
                fill: true
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return '$' + value.toLocaleString();
                        }
                    }
                }
            },
            plugins: {
                title: {
                    display: true,
                    text: 'Revenue Growth Over the Years'
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return 'Revenue: $' + context.raw.toLocaleString();
                        }
                    }
                }
            }
        }
    });

    // Package Chart
    const packageCtx = document.getElementById('packageChart').getContext('2d');
    new Chart(packageCtx, {
        type: 'line',
        data: {
            labels: {!! json_encode($years) !!},
            datasets: [{
                label: 'Total Packages',
                data: {!! json_encode($packageCounts) !!},
                borderColor: 'rgba(255, 99, 132, 1)',
                backgroundColor: 'rgba(255, 99, 132, 0.2)',
                borderWidth: 2,
                tension: 0.1,
                fill: true
            }, {
                label: 'Total Bookings',
                data: {!! json_encode($bookingCounts) !!},
                borderColor: 'rgba(153, 102, 255, 1)',
                backgroundColor: 'rgba(153, 102, 255, 0.2)',
                borderWidth: 2,
                tension: 0.1,
                fill: true
            }, {
                label: 'Average Price',
                data: {!! json_encode($averagePrices) !!},
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
                        text: 'Average Price ($)'
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
    .chart-container {
        margin-bottom: 20px;
    }
    .table th {
        background-color: #f8f9fa;
    }
</style>
@endpush 