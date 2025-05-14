<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Metro Essence - Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">
    <style>
        body {
            font-family: 'Times New Roman', serif;
            background-color: #fff;
            color: #333;
        }
        .navbar {
            background-color: #fff;
            border-bottom: 1px solid #eee;
            padding: 15px 0;
        }
        .navbar-brand {
            font-family: 'Times New Roman', serif;
            font-weight: 300;
            letter-spacing: 2px;
            color: #5d1d48 !important;
        }
        .nav-link {
            color: #5d1d48 !important;
            font-size: 0.9rem;
            letter-spacing: 1px;
        }
        .card {
            border: none;
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0,0,0,0.1);
            transition: transform 0.3s ease;
        }
        .card:hover {
            transform: translateY(-5px);
        }
        .border-left-primary {
            border-left: 4px solid #5d1d48 !important;
        }
        .border-left-success {
            border-left: 4px solid #1cc88a !important;
        }
        .border-left-warning {
            border-left: 4px solid #f6c23e !important;
        }
        .border-left-info {
            border-left: 4px solid #36b9cc !important;
        }
        .text-primary {
            color: #5d1d48 !important;
        }
        .text-success {
            color: #1cc88a !important;
        }
        .text-warning {
            color: #f6c23e !important;
        }
        .text-info {
            color: #36b9cc !important;
        }
        .card-header {
            background-color: #fff;
            border-bottom: 1px solid #eee;
            padding: 1rem;
        }
        .chart-area {
            position: relative;
            height: 300px;
            width: 100%;
        }
        .chart-pie {
            position: relative;
            height: 300px;
            width: 100%;
        }
        .chart-bar {
            position: relative;
            height: 300px;
            width: 100%;
        }
        footer {
            background-color: #f8f8f8;
            padding: 30px 0;
            margin-top: 50px;
            border-top: 1px solid #eee;
        }
        .footer-links {
            list-style: none;
            padding: 0;
        }
        .footer-links li {
            margin-bottom: 10px;
        }
        .footer-links a {
            color: #5d1d48;
            text-decoration: none;
            font-size: 0.9rem;
        }
        .social-icons {
            list-style: none;
            padding: 0;
            display: flex;
            justify-content: center;
            margin-top: 20px;
        }
        .social-icons li {
            margin: 0 10px;
        }
        .social-icons a {
            color: #5d1d48;
            font-size: 1.2rem;
        }
        /* Stock Alert Styles */
        .stock-alert {
            border-radius: 10px;
            border: none;
            box-shadow: 0 2px 15px rgba(0,0,0,0.1);
        }
        .stock-alert .alert-icon {
            font-size: 2rem;
            margin-right: 1rem;
        }
        .stock-alert .alert-content {
            flex: 1;
        }
        .stock-alert .alert-title {
            font-size: 1.1rem;
            margin-bottom: 0.5rem;
            color: #5d1d48;
        }
        .stock-alert .stock-list {
            list-style: none;
            padding-left: 0;
            margin-bottom: 0;
        }
        .stock-alert .stock-list li {
            margin-bottom: 0.5rem;
            padding-left: 1.5rem;
            position: relative;
        }
        .stock-alert .stock-list li:before {
            content: "•";
            position: absolute;
            left: 0;
            color: #5d1d48;
        }
        .stock-alert .stock-list .stock-count {
            font-weight: 600;
            color: #5d1d48;
        }
        .stock-alert .stock-list .stock-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0.5rem;
            background: rgba(93, 29, 72, 0.05);
            border-radius: 5px;
            margin-bottom: 0.5rem;
        }
        .stock-alert .stock-list .stock-item:last-child {
            margin-bottom: 0;
        }
        .stock-alert .view-all-btn {
            margin-top: 1rem;
            padding: 0.5rem 1.5rem;
            border-radius: 5px;
            transition: all 0.3s ease;
        }
        .stock-alert .view-all-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 2px 8px rgba(93, 29, 72, 0.2);
        }
        .timeline {
            position: relative;
            padding: 20px 0;
        }
        .timeline-item {
            position: relative;
            padding-left: 40px;
            margin-bottom: 20px;
        }
        .timeline-marker {
            position: absolute;
            left: 0;
            top: 0;
            width: 12px;
            height: 12px;
            border-radius: 50%;
            background: #5d1d48;
            border: 2px solid #fff;
            box-shadow: 0 0 0 2px #5d1d48;
        }
        .timeline-item:before {
            content: '';
            position: absolute;
            left: 5px;
            top: 12px;
            height: calc(100% + 8px);
            width: 2px;
            background: #e9ecef;
        }
        .timeline-item:last-child:before {
            display: none;
        }
        /* Chart Styles */
        .chart-container {
            position: relative;
            margin: auto;
            height: 300px;
            width: 100%;
        }
        .chart-card {
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0,0,0,0.1);
            margin-bottom: 1.5rem;
            overflow: hidden;
        }
        .chart-header {
            padding: 1rem;
            border-bottom: 1px solid #eee;
            background: #fff;
        }
        .chart-title {
            font-size: 1rem;
            font-weight: 600;
            color: #5d1d48;
            margin: 0;
        }
        .chart-body {
            padding: 1rem;
        }
        .chart-legend {
            display: flex;
            justify-content: center;
            gap: 1rem;
            margin-top: 1rem;
            flex-wrap: wrap;
        }
        .legend-item {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.875rem;
            color: #666;
        }
        .legend-color {
            width: 12px;
            height: 12px;
            border-radius: 3px;
        }
    </style>
</head>
<body>
    
  @include('components.admin_navbar')

    <!-- Login/Signup Modal -->
    <div class="modal fade" id="authModal" tabindex="-1" aria-labelledby="authModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="authModalLabel">Welcome</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center">
                    <p>Please choose an option:</p>
                    <a href="{{ url('/login') }}" class="btn tag-btn m-2">Login</a>
                    <a href="{{ url('/signup') }}" class="btn tag-btn m-2">Signup</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Content Section -->
    <div class="container-fluid mt-4">
        {{-- Stock Inventory Alerts --}}
        @if($lowStockProducts->count() > 0 || $outOfStockProducts->count() > 0)
            <div class="alert alert-warning d-flex align-items-center mb-4" role="alert" style="border-radius: 10px; border: none; box-shadow: 0 2px 15px rgba(0,0,0,0.1); padding: 0.75rem;">
                <div style="font-size: 1.5rem; margin-right: 1rem; color: #5d1d48;">
                    <i class="fas fa-exclamation-triangle"></i>
                </div>
                <div style="flex: 1; display: flex; align-items: center; gap: 1rem;">
                    @if($lowStockProducts->count() > 0)
                        <div style="flex: 1; display: flex; align-items: center; gap: 0.5rem;">
                            <span class="badge bg-warning text-dark" style="font-size: 0.9rem;">{{ $lowStockProducts->count() }}</span>
                            <div style="font-size: 0.9rem;">
                                <span class="text-warning fw-bold">Low Stock:</span>
                                {{ $lowStockProducts->take(2)->pluck('name')->join(', ') }}
                                @if($lowStockProducts->count() > 2)
                                    <span class="text-muted">+{{ $lowStockProducts->count() - 2 }} more</span>
                                @endif
                            </div>
                        </div>
                    @endif
                    @if($outOfStockProducts->count() > 0)
                        <div style="flex: 1; display: flex; align-items: center; gap: 0.5rem;">
                            <span class="badge bg-danger" style="font-size: 0.9rem;">{{ $outOfStockProducts->count() }}</span>
                            <div style="font-size: 0.9rem;">
                                <span class="text-danger fw-bold">Out of Stock:</span>
                                {{ $outOfStockProducts->take(2)->pluck('name')->join(', ') }}
                                @if($outOfStockProducts->count() > 2)
                                    <span class="text-muted">+{{ $outOfStockProducts->count() - 2 }} more</span>
                                @endif
                            </div>
                        </div>
                    @endif
                    <a href="{{ route('admin.products') }}" class="btn btn-sm btn-outline-primary" style="white-space: nowrap;">
                        <i class="fas fa-box me-1"></i>Manage
                    </a>
                </div>
            </div>
        @endif
        <div class="row">
            <!-- Summary Cards -->
            <div class="col-xl-3 col-md-6 mb-4">
                <a href="{{ route('admin.users') }}" class="text-decoration-none">
                    <div class="card border-left-primary shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total Users</div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $usersCount }}</div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-users fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>

            <div class="col-xl-3 col-md-6 mb-4">
                <a href="{{ route('admin.products') }}" class="text-decoration-none">
                    <div class="card border-left-success shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Total Products</div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $productsCount }}</div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-box fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>

            <div class="col-xl-3 col-md-6 mb-4">
                <a href="{{ route('admin.orders') }}" class="text-decoration-none">
                    <div class="card border-left-warning shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Pending Orders</div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $ordersPending }}</div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-clock fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>

            <div class="col-xl-3 col-md-6 mb-4">
                <a href="{{ route('admin.orders') }}" class="text-decoration-none">
                    <div class="card border-left-info shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Total Sales</div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">PHP{{ number_format($totalSales, 2) }}</div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
        </div>

        <!-- Charts Row -->
        <div class="row">
            <!-- Sales Overview Chart -->
            <div class="col-xl-8 col-lg-7">
                <div class="chart-card">
                    <div class="chart-header">
                        <h6 class="chart-title">Sales & Revenue Overview</h6>
                    </div>
                    <div class="chart-body">
                        <div class="chart-container">
                            <canvas id="salesChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
            <!-- User Distribution Chart -->
            <div class="col-xl-4 col-lg-5">
                <div class="chart-card">
                    <div class="chart-header">
                        <h6 class="chart-title">User Distribution</h6>
                    </div>
                    <div class="chart-body">
                        <div class="chart-container">
                            <canvas id="userDistributionChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Orders Timeline -->
        <div class="row">
            <div class="col-xl-12">
                <div class="chart-card">
                    <div class="chart-header">
                        <h6 class="chart-title">Recent Orders</h6>
                    </div>
                    <div class="chart-body">
                        <div class="timeline">
                            @foreach($recentOrders as $order)
                            <div class="timeline-item">
                                <div class="timeline-marker"></div>
                                <div class="timeline-content">
                                    <h6 class="mb-0">{{ $order->user->name }}</h6>
                                    <p class="text-muted small mb-0">
                                        Order #{{ $order->id }} - {{ $order->created_at->diffForHumans() }}
                                    </p>
                                    <p class="mb-0">
                                        <span class="badge bg-{{ $order->status === 'completed' ? 'success' : 'warning' }}">
                                            {{ ucfirst($order->status) }}
                                        </span>
                                        <span class="ms-2">PHP {{ number_format($order->total, 2) }}</span>
                                    </p>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Top Products Chart -->
        <div class="row">
            <div class="col-xl-12">
                <div class="chart-card">
                    <div class="chart-header">
                        <h6 class="chart-title">Top Selling Products</h6>
                    </div>
                    <div class="chart-body">
                        <div class="chart-container">
                            <canvas id="productPerformanceChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    @include('components.footer')

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
    // Sales Chart (Combined Sales and Revenue)
    const salesCtx = document.getElementById('salesChart').getContext('2d');
    new Chart(salesCtx, {
        type: 'line',
        data: {
            labels: {!! json_encode($salesData->pluck('date')) !!},
            datasets: [{
                label: 'Daily Sales',
                data: {!! json_encode($salesData->pluck('amount')) !!},
                borderColor: '#5d1d48',
                backgroundColor: 'rgba(93, 29, 72, 0.1)',
                tension: 0.4,
                fill: true,
                pointRadius: 4,
                pointHoverRadius: 6,
                yAxisID: 'y'
            },
            {
                label: 'Monthly Revenue',
                data: {!! json_encode($monthlyRevenue->pluck('amount')) !!},
                borderColor: '#1cc88a',
                backgroundColor: 'rgba(28, 200, 138, 0.1)',
                tension: 0.4,
                fill: true,
                pointRadius: 4,
                pointHoverRadius: 6,
                yAxisID: 'y1'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            interaction: {
                mode: 'index',
                intersect: false,
            },
            plugins: {
                legend: {
                    position: 'top',
                    labels: {
                        padding: 20,
                        font: {
                            size: 12
                        }
                    }
                },
                tooltip: {
                    backgroundColor: 'rgba(0, 0, 0, 0.8)',
                    padding: 12,
                    titleFont: {
                        size: 14
                    },
                    bodyFont: {
                        size: 13
                    }
                }
            },
            scales: {
                y: {
                    type: 'linear',
                    display: true,
                    position: 'left',
                    beginAtZero: true,
                    grid: {
                        color: 'rgba(0, 0, 0, 0.05)'
                    },
                    ticks: {
                        callback: function(value) {
                            return 'PHP ' + value.toLocaleString();
                        }
                    }
                },
                y1: {
                    type: 'linear',
                    display: true,
                    position: 'right',
                    beginAtZero: true,
                    grid: {
                        display: false
                    },
                    ticks: {
                        callback: function(value) {
                            return 'PHP ' + value.toLocaleString();
                        }
                    }
                },
                x: {
                    grid: {
                        display: false
                    }
                }
            }
        }
    });

    // User Distribution Chart
    const userCtx = document.getElementById('userDistributionChart').getContext('2d');
    new Chart(userCtx, {
        type: 'doughnut',
        data: {
            labels: ['Admin Users', 'Regular Users'],
            datasets: [{
                data: [{{ $adminUsersCount }}, {{ $regularUsersCount }}],
                backgroundColor: ['#5d1d48', '#1cc88a'],
                borderWidth: 0
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            cutout: '70%',
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        padding: 20,
                        font: {
                            size: 12
                        }
                    }
                }
            }
        }
    });

    // Product Performance Chart
    const productCtx = document.getElementById('productPerformanceChart').getContext('2d');
    new Chart(productCtx, {
        type: 'bar',
        data: {
            labels: {!! json_encode($topProducts->pluck('name')) !!},
            datasets: [{
                label: 'Units Sold',
                data: {!! json_encode($topProducts->pluck('sales')) !!},
                backgroundColor: '#5d1d48',
                borderRadius: 4,
                maxBarThickness: 40
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    backgroundColor: 'rgba(0, 0, 0, 0.8)',
                    padding: 12,
                    titleFont: {
                        size: 14
                    },
                    bodyFont: {
                        size: 13
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: {
                        color: 'rgba(0, 0, 0, 0.05)'
                    },
                    ticks: {
                        stepSize: 1
                    }
                },
                x: {
                    grid: {
                        display: false
                    }
                }
            }
        }
    });
    </script>
</body>
</html>
