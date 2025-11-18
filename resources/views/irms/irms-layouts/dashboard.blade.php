@extends('irms.irms-partials.app')

@section('title', 'IRMS Dashboard')

@section('content')
    <main class="app-main">
        <div class="app-content-wrapper">
            @if(config('app.env') == 'production')
                <div class="col-12">
                    <div class="alert alert-info m-4">
                        <h5 class="alert-heading">IRMS Dashboard</h5>
                        <p>Welcome to the IRMS (Inventory and Rack Management System) Dashboard. This platform is designed to
                            help you efficiently manage inventory and rack locations across various sites.</p>
                        <hr>
                        <div class="alert alert-warning mt-3">
                            <strong>Note:</strong> This dashboard is currently under development. Some features and data may not
                            be final.<br>
                            <span class="text-success">You can still use the system for your inventory and rack management
                                needs.</span>
                        </div>
                        <p class="mb-0">For assistance or more information, please contact the system administrator.</p>
                    </div>
                </div>
            @else

                <div class="dashboard-header">
                    <div class="container-fluid">
                        <div class="row align-items-center">
                            <div class="col-md-8">
                                <h1>
                                    @if($userLevel == 1)
                                        Super Admin Dashboard
                                    @elseif($userLevel == 2)
                                        Admin Dashboard
                                    @else
                                        User Dashboard
                                    @endif
                                </h1>
                                <p class="dashboard-subtitle">
                                    Welcome back, {{ auth()->user()->name }}!
                                </p>
                            </div>
                            <div class="col-md-4 text-md-end">
                                <span
                                    class="access-level-badge {{ $userLevel == 1 ? 'sa' : ($userLevel == 2 ? 'admin' : 'user') }}">
                                    <i class="fas fa-shield-alt me-2"></i>
                                    @if($userLevel == 1)
                                        Super Admin
                                    @elseif($userLevel == 2)
                                        Site Admin
                                    @else
                                        User
                                    @endif
                                </span>
                                @if($userLevel != 1)
                                    <div class="site-info mt-2">
                                        <i class="fas fa-building me-2"></i>: <strong>{{ $userSiteDesc }}</strong>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <div class="app-content">
                    <div class="container-fluid">
                        <!-- Stats Cards Row -->
                        @if($userLevel == 1)
                            <!-- Super Admin Stats -->
                            <div class="row mb-4">
                                <div class="col-lg-2 col-md-4 col-sm-6 mb-3">
                                    <div class="stats-card primary">
                                        <div class="stats-label">Total Sites</div>
                                        <div class="stats-number" id="total-sites">
                                            {{ number_format($dashboardData['stats']['total_sites']) }}</div>
                                        <i class="fas fa-building stats-icon text-primary"></i>
                                    </div>
                                </div>
                                <div class="col-lg-2 col-md-4 col-sm-6 mb-3">
                                    <div class="stats-card success">
                                        <div class="stats-label">Total Users</div>
                                        <div class="stats-number" id="total-users">
                                            {{ number_format($dashboardData['stats']['total_users']) }}</div>
                                        <i class="fas fa-users stats-icon text-success"></i>
                                    </div>
                                </div>
                                <div class="col-lg-2 col-md-4 col-sm-6 mb-3">
                                    <div class="stats-card warning">
                                        <div class="stats-label">Warehouses</div>
                                        <div class="stats-number" id="total-warehouses">
                                            {{ number_format($dashboardData['stats']['total_warehouses']) }}
                                        </div>
                                        <i class="fas fa-warehouse stats-icon text-warning"></i>
                                    </div>
                                </div>
                                <div class="col-lg-2 col-md-4 col-sm-6 mb-3">
                                    <div class="stats-card info">
                                        <div class="stats-label">Rack Locations</div>
                                        <div class="stats-number" id="total-locations">
                                            {{ number_format($dashboardData['stats']['total_locations']) }}
                                        </div>
                                        <i class="fas fa-map-marker-alt stats-icon text-info"></i>
                                    </div>
                                </div>
                                <div class="col-lg-2 col-md-4 col-sm-6 mb-3">
                                    <div class="stats-card danger">
                                        <div class="stats-label">Transactions</div>
                                        <div class="stats-number" id="total-transactions">
                                            {{ number_format($dashboardData['stats']['total_transactions']) }}
                                        </div>
                                        <i class="fas fa-exchange-alt stats-icon text-danger"></i>
                                    </div>
                                </div>
                                <div class="col-lg-2 col-md-4 col-sm-6 mb-3">
                                    <div class="stats-card secondary">
                                        <div class="stats-label">Active Sessions</div>
                                        <div class="stats-number" id="active-sessions">
                                            {{ number_format($dashboardData['stats']['active_sessions']) }}
                                        </div>
                                        <i class="fas fa-users-cog stats-icon text-secondary"></i>
                                    </div>
                                </div>
                            </div>
                        @elseif($userLevel == 2)
                            <!-- Admin Stats -->
                            <div class="row mb-4">
                                <div class="col-lg-2 col-md-4 col-sm-6 mb-3">
                                    <div class="stats-card primary">
                                        <div class="stats-label">Site Users</div>
                                        <div class="stats-number" id="total-users">
                                            {{ number_format($dashboardData['stats']['site_users']) }}</div>
                                        <i class="fas fa-users stats-icon text-primary"></i>
                                    </div>
                                </div>
                                <div class="col-lg-2 col-md-4 col-sm-6 mb-3">
                                    <div class="stats-card success">
                                        <div class="stats-label">Warehouses</div>
                                        <div class="stats-number" id="total-warehouses">
                                            {{ number_format($dashboardData['stats']['site_warehouses']) }}
                                        </div>
                                        <i class="fas fa-warehouse stats-icon text-success"></i>
                                    </div>
                                </div>
                                <div class="col-lg-2 col-md-4 col-sm-6 mb-3">
                                    <div class="stats-card warning">
                                        <div class="stats-label">Rack Locations</div>
                                        <div class="stats-number" id="total-locations">
                                            {{ number_format($dashboardData['stats']['site_locations']) }}
                                        </div>
                                        <i class="fas fa-map-marker-alt stats-icon text-warning"></i>
                                    </div>
                                </div>
                                <div class="col-lg-2 col-md-4 col-sm-6 mb-3">
                                    <div class="stats-card info">
                                        <div class="stats-label">Monthly Receiving</div>
                                        <div class="stats-number" id="total-monthly-receiving">
                                            {{ number_format($dashboardData['stats']['monthly_receiving']) }}
                                        </div>
                                        <i class="fas fa-download stats-icon text-info"></i>
                                    </div>
                                </div>
                                <div class="col-lg-2 col-md-4 col-sm-6 mb-3">
                                    <div class="stats-card danger">
                                        <div class="stats-label">Monthly Dispatching</div>
                                        <div class="stats-number" id="total-monthly-dispatching">
                                            {{ number_format($dashboardData['stats']['monthly_dispatching']) }}
                                        </div>
                                        <i class="fas fa-upload stats-icon text-danger"></i>
                                    </div>
                                </div>
                                <div class="col-lg-2 col-md-4 col-sm-6 mb-3">
                                    <div class="stats-card secondary">
                                        <div class="stats-label">Occupied Locations</div>
                                        <div class="stats-number" id="total-occupied-locations">
                                            {{ number_format($dashboardData['stats']['occupied_locations']) }}
                                        </div>
                                        <i class="fas fa-boxes stats-icon text-secondary"></i>
                                    </div>
                                </div>
                            </div>
                        @else
                            <!-- User Stats -->
                            <div class="row mb-4">
                                <div class="col-lg-3 col-md-6 mb-3">
                                    <div class="stats-card success">
                                        <div class="stats-label">Available Locations</div>
                                        <div class="stats-number" id="total-available-locations">
                                            {{ number_format($dashboardData['stats']['available_locations']) }}
                                        </div>
                                        <i class="fas fa-check-circle stats-icon text-success"></i>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-6 mb-3">
                                    <div class="stats-card warning">
                                        <div class="stats-label">Occupied Locations</div>
                                        <div class="stats-number" id="total-occupied-locations">
                                            {{ number_format($dashboardData['stats']['occupied_locations']) }}
                                        </div>
                                        <i class="fas fa-boxes stats-icon text-warning"></i>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-6 mb-3">
                                    <div class="stats-card primary">
                                        <div class="stats-label">Today's Receiving</div>
                                        <div class="stats-number" id="total-today-receiving">
                                            {{ number_format($dashboardData['stats']['today_receiving']) }}
                                        </div>
                                        <i class="fas fa-download stats-icon text-primary"></i>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-6 mb-3">
                                    <div class="stats-card danger">
                                        <div class="stats-label">Today's Dispatching</div>
                                        <div class="stats-number" id="total-today-dispatching">
                                            {{ number_format($dashboardData['stats']['today_dispatching']) }}
                                        </div>
                                        <i class="fas fa-upload stats-icon text-danger"></i>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <!-- Main Content Row -->
                        <div class="row">
                            <!-- Quick Actions Column -->
                            <div class="col-lg-4 mb-4">
                                <div class="quick-actions">
                                    <h5 class="mb-3"><i class="fas fa-bolt me-2"></i>Quick Actions</h5>
                                    @foreach($dashboardData['quick_actions'] as $action)
                                        <a href="{{ $action['url'] }}" class="quick-action-btn">
                                            <i class="{{ $action['icon'] }}"></i>
                                            {{ $action['label'] }}
                                        </a>
                                    @endforeach
                                </div>
                            </div>

                            <!-- Charts Column -->
                            @if($userLevel >= 2)
                                <div class="col-lg-8 mb-4">
                                    <!-- Super Admin Chart -->
                                    <div class="chart-card">
                                        <div class="chart-card-header">
                                            <h5><i class="fas fa-chart-line me-2"></i>Monthly Operations Overview</h5>
                                        </div>
                                        <div class="chart-card-body">
                                            <canvas id="monthlyChart" height="{{ $userLevel != 3 ? 400 : 200 }}"></canvas>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-8 mb-4">
                                    <!-- Admin Chart -->
                                    <div class="chart-card">
                                        <div class="chart-card-header">
                                            <h5><i class="fas fa-chart-bar me-2"></i>Weekly Operations</h5>
                                        </div>
                                        <div class="chart-card-body">
                                            <canvas id="weeklyChart" height="200"></canvas>
                                        </div>
                                    </div>
                                </div>

                                <!-- User Chart -->
                                <div class="col-lg-4 mb-4">
                                    <div class="chart-card">
                                        <div class="chart-card-header">
                                            <h5><i class="fas fa-chart-pie me-2"></i>Today's Operations</h5>
                                        </div>
                                        <div class="chart-card-body">
                                            <canvas id="todayChart" height="200"></canvas>
                                        </div>
                                    </div>
                                </div>
                            @endif
                            <div class="col-lg-{{ $userLevel >= 2 ? 12 : 8 }} mb-4">
                                <div class="chart-card">
                                    <div class="chart-card-header">
                                        <h5><i class="fas fa-history me-2"></i>{{ $userLevel <= 2 ? 'My Recent Transactions' : 'Recent Transactions' }}</h5>
                                    </div>
                                    <div class="chart-card-body">
                                        @if(count($dashboardData['recent_transactions']) > 0)
                                            <ul class="activity-list">
                                                @foreach($dashboardData['recent_transactions'] as $activity)
                                                    <li class="activity-item">
                                                        <div class="activity-icon {{ $activity['type'] }}">
                                                            <i class="{{ $activity['icon'] }}"></i>
                                                        </div>
                                                        <div class="activity-content">
                                                            <h6>{{ $activity['title'] }}</h6>
                                                            <small>{{ $activity['description'] }} • {{ $activity['time'] }}</small>
                                                        </div>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        @else
                                            <div class="text-center py-4">
                                                <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                                                <p class="text-muted">No recent transactions found.</p>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Bottom Row -->
                        <div class="row">
                            <!-- Recent Activities -->
                            
                            <!-- System Status -->
                            @if ($userLevel == 1)
                                <div class="col-lg-4 mb-4">
                                    <div class="system-status">
                                        <h5 class="mb-3"><i class="fas fa-server me-2"></i>System Status</h5>
                                        @foreach($dashboardData['system_status'] as $status)
                                            <div class="status-item">
                                                <span>{{ $status['label'] }}</span>
                                                <span class="status-indicator {{ $status['status'] }}"></span>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                        </div>
                    </div>
                </div>
            @endif
        </div>
    </main>
    @if(config('app.env') != 'production')
    <script>
        document.addEventListener('DOMContentLoaded', function () {

            // Initialize charts based on user level
            @if($userLevel >= 2)
                const monthlyData = @json($dashboardData['charts']['monthly_transactions']);
                if (typeof initMonthlyChart === 'function') {
                    initMonthlyChart(monthlyData);
                }

                const weeklyData = @json($dashboardData['charts']['weekly_operations']);
                if (typeof initWeeklyChart === 'function') {
                    initWeeklyChart(weeklyData);
                }

                const todayData = @json($dashboardData['charts']['today_operations']);
                if (typeof initTodayChart === 'function') {
                    initTodayChart(todayData);
                }
            @endif

            setInterval(function () {
                fetch(window.appUrl + '/irms/dashboard/refresh')
                    .then(res => res.json())
                    .then(response => {
                        if (response.success && response.data) {
                            const data = response.data;

                            // Update stats
                            @if($userLevel == 1)
                                document.getElementById('total-sites').textContent = Number(data.stats.total_sites).toLocaleString();
                                document.getElementById('total-users').textContent = Number(data.stats.total_users).toLocaleString();
                                document.getElementById('total-warehouses').textContent = Number(data.stats.total_warehouses).toLocaleString();
                                document.getElementById('total-locations').textContent = Number(data.stats.total_locations).toLocaleString();
                                document.getElementById('total-transactions').textContent = Number(data.stats.total_transactions).toLocaleString();
                                document.getElementById('active-sessions').textContent = Number(data.stats.active_sessions).toLocaleString();
                            @elseif($userLevel == 2)
                                document.getElementById('total-users').textContent = Number(data.stats.site_users).toLocaleString();
                                document.getElementById('total-warehouses').textContent = Number(data.stats.site_warehouses).toLocaleString();
                                document.getElementById('total-locations').textContent = Number(data.stats.site_locations).toLocaleString();
                                document.getElementById('total-monthly-receiving').textContent = Number(data.stats.monthly_receiving).toLocaleString();
                                document.getElementById('total-monthly-dispatching').textContent = Number(data.stats.monthly_dispatching).toLocaleString();
                                document.getElementById('total-occupied-locations').textContent = Number(data.stats.occupied_locations).toLocaleString();
                            @else
                                document.getElementById('total-available-locations').textContent = Number(data.stats.available_locations).toLocaleString();
                                document.getElementById('total-occupied-locations').textContent = Number(data.stats.occupied_locations).toLocaleString();
                                document.getElementById('total-today-receiving').textContent = Number(data.stats.today_receiving).toLocaleString();
                                document.getElementById('total-today-dispatching').textContent = Number(data.stats.today_dispatching).toLocaleString();
                            @endif

                            // Update charts
                            if (typeof initMonthlyChart === 'function') {
                                initMonthlyChart(data.charts.monthly_transactions);
                            }
                            if (typeof initWeeklyChart === 'function') {
                                initWeeklyChart(data.charts.weekly_operations);
                            }
                            if (typeof initTodayChart === 'function' && data.charts.today_operations) {
                                initTodayChart(data.charts.today_operations);
                            }

                            // Optionally update recent activities, system status, etc.
                            // You may need to write JS functions to update those sections.
                        }
                    });
            }, 5000);
        });
    </script>
    @endif
    
@endsection