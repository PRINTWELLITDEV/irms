@extends('irms.irms-partials.app')

@section('title', 'IRMS Dashboard')

@section('content')
    <main class="app-main">
        <div class="app-content-wrapper">
            @if(config('app.env') == 'production')
            <div class="col-12">
                <div class="alert alert-info m-4">
                    <h5 class="alert-heading">IRMS Dashboard</h5>
                    <p>Welcome to the IRMS (Inventory and Rack Management System) Dashboard. This platform is designed to help you efficiently manage inventory and rack locations across various sites.</p>
                    <hr>
                    <div class="alert alert-warning mt-3">
                        <strong>Note:</strong> This dashboard is currently under development. Some features and data may not be final.<br>
                        <span class="text-success">You can still use the system for your inventory and rack management needs.</span>
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
                                @if($userLevel != 1)
                                    Managing {{ $userSiteDesc }}
                                @endif
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
                                    <div class="stats-number">{{ number_format($dashboardData['stats']['total_sites']) }}</div>
                                    <i class="fas fa-building stats-icon text-primary"></i>
                                </div>
                            </div>
                            <div class="col-lg-2 col-md-4 col-sm-6 mb-3">
                                <div class="stats-card success">
                                    <div class="stats-label">Total Users</div>
                                    <div class="stats-number">{{ number_format($dashboardData['stats']['total_users']) }}</div>
                                    <i class="fas fa-users stats-icon text-success"></i>
                                </div>
                            </div>
                            <div class="col-lg-2 col-md-4 col-sm-6 mb-3">
                                <div class="stats-card warning">
                                    <div class="stats-label">Warehouses</div>
                                    <div class="stats-number">{{ number_format($dashboardData['stats']['total_warehouses']) }}
                                    </div>
                                    <i class="fas fa-warehouse stats-icon text-warning"></i>
                                </div>
                            </div>
                            <div class="col-lg-2 col-md-4 col-sm-6 mb-3">
                                <div class="stats-card info">
                                    <div class="stats-label">Rack Locations</div>
                                    <div class="stats-number">{{ number_format($dashboardData['stats']['total_locations']) }}
                                    </div>
                                    <i class="fas fa-map-marker-alt stats-icon text-info"></i>
                                </div>
                            </div>
                            <div class="col-lg-2 col-md-4 col-sm-6 mb-3">
                                <div class="stats-card danger">
                                    <div class="stats-label">Transactions</div>
                                    <div class="stats-number">{{ number_format($dashboardData['stats']['total_transactions']) }}
                                    </div>
                                    <i class="fas fa-exchange-alt stats-icon text-danger"></i>
                                </div>
                            </div>
                            <div class="col-lg-2 col-md-4 col-sm-6 mb-3">
                                <div class="stats-card secondary">
                                    <div class="stats-label">Active Sessions</div>
                                    <div class="stats-number">{{ number_format($dashboardData['stats']['active_sessions']) }}
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
                                    <div class="stats-number">{{ number_format($dashboardData['stats']['site_users']) }}</div>
                                    <i class="fas fa-users stats-icon text-primary"></i>
                                </div>
                            </div>
                            <div class="col-lg-2 col-md-4 col-sm-6 mb-3">
                                <div class="stats-card success">
                                    <div class="stats-label">Warehouses</div>
                                    <div class="stats-number">{{ number_format($dashboardData['stats']['site_warehouses']) }}
                                    </div>
                                    <i class="fas fa-warehouse stats-icon text-success"></i>
                                </div>
                            </div>
                            <div class="col-lg-2 col-md-4 col-sm-6 mb-3">
                                <div class="stats-card warning">
                                    <div class="stats-label">Rack Locations</div>
                                    <div class="stats-number">{{ number_format($dashboardData['stats']['site_locations']) }}
                                    </div>
                                    <i class="fas fa-map-marker-alt stats-icon text-warning"></i>
                                </div>
                            </div>
                            <div class="col-lg-2 col-md-4 col-sm-6 mb-3">
                                <div class="stats-card info">
                                    <div class="stats-label">Monthly Receiving</div>
                                    <div class="stats-number">{{ number_format($dashboardData['stats']['monthly_receiving']) }}
                                    </div>
                                    <i class="fas fa-download stats-icon text-info"></i>
                                </div>
                            </div>
                            <div class="col-lg-2 col-md-4 col-sm-6 mb-3">
                                <div class="stats-card danger">
                                    <div class="stats-label">Monthly Dispatching</div>
                                    <div class="stats-number">
                                        {{ number_format($dashboardData['stats']['monthly_dispatching']) }}</div>
                                    <i class="fas fa-upload stats-icon text-danger"></i>
                                </div>
                            </div>
                            <div class="col-lg-2 col-md-4 col-sm-6 mb-3">
                                <div class="stats-card secondary">
                                    <div class="stats-label">Occupied Locations</div>
                                    <div class="stats-number">{{ number_format($dashboardData['stats']['occupied_locations']) }}
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
                                    <div class="stats-number">
                                        {{ number_format($dashboardData['stats']['available_locations']) }}</div>
                                    <i class="fas fa-check-circle stats-icon text-success"></i>
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-6 mb-3">
                                <div class="stats-card warning">
                                    <div class="stats-label">Occupied Locations</div>
                                    <div class="stats-number">{{ number_format($dashboardData['stats']['occupied_locations']) }}
                                    </div>
                                    <i class="fas fa-boxes stats-icon text-warning"></i>
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-6 mb-3">
                                <div class="stats-card primary">
                                    <div class="stats-label">Today's Receiving</div>
                                    <div class="stats-number">{{ number_format($dashboardData['stats']['today_receiving']) }}
                                    </div>
                                    <i class="fas fa-download stats-icon text-primary"></i>
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-6 mb-3">
                                <div class="stats-card danger">
                                    <div class="stats-label">Today's Dispatching</div>
                                    <div class="stats-number">{{ number_format($dashboardData['stats']['today_dispatching']) }}
                                    </div>
                                    <i class="fas fa-upload stats-icon text-danger"></i>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Main Content Row -->
                    <div class="row">
                        <!-- Charts Column -->
                        @if($userLevel >= 2)
                            <div class="col-lg-8 mb-4">
                                @if($userLevel == 1)
                                    <!-- Super Admin Chart -->
                                    <div class="chart-card">
                                        <div class="chart-card-header">
                                            <h5><i class="fas fa-chart-line me-2"></i>Monthly Operations Overview</h5>
                                        </div>
                                        <div class="chart-card-body">
                                            <canvas id="monthlyChart" height="100"></canvas>
                                        </div>
                                    </div>
                                @else
                                    <!-- Admin Chart -->
                                    <div class="chart-card">
                                        <div class="chart-card-header">
                                            <h5><i class="fas fa-chart-bar me-2"></i>Daily Operations (Last 7 Days)</h5>
                                        </div>
                                        <div class="chart-card-body">
                                            <canvas id="dailyChart" height="100"></canvas>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        @endif

                        <!-- Quick Actions Column -->
                        <div class="col-lg-{{ $userLevel >= 2 ? '4' : '6' }} mb-4">
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

                        @if($userLevel < 2)
                            <!-- User Chart -->
                            <div class="col-lg-6 mb-4">
                                <div class="chart-card">
                                    <div class="chart-card-header">
                                        <h5><i class="fas fa-chart-pie me-2"></i>Weekly Operations</h5>
                                    </div>
                                    <div class="chart-card-body">
                                        <canvas id="weeklyChart" height="100"></canvas>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>

                    <!-- Bottom Row -->
                    <div class="row">
                        <!-- Recent Activities -->
                        <div class="col-lg-8 mb-4">
                            <div class="chart-card">
                                <div class="chart-card-header">
                                    <h5><i class="fas fa-history me-2"></i>Recent Activities</h5>
                                </div>
                                <div class="chart-card-body">
                                    @if(count($dashboardData['recent_activities']) > 0)
                                        <ul class="activity-list">
                                            @foreach($dashboardData['recent_activities'] as $activity)
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
                                            <p class="text-muted">No recent activities found.</p>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- System Status -->
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
                    </div>
                </div>
            </div>
            @endif
        </div>
    </main>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Initialize charts based on user level
            @if($userLevel == 1 && isset($dashboardData['charts']['monthly_transactions']))
                initMonthlyChart({!! json_encode($dashboardData['charts']['monthly_transactions']) !!});
            @elseif($userLevel == 2 && isset($dashboardData['charts']['daily_operations']))
                initDailyChart({!! json_encode($dashboardData['charts']['daily_operations']) !!});
            @elseif($userLevel == 3 && isset($dashboardData['charts']['weekly_operations']))
                initWeeklyChart({!! json_encode($dashboardData['charts']['weekly_operations']) !!});
            @endif
    });

        function initMonthlyChart(data) {
            const ctx = document.getElementById('monthlyChart');
            if (!ctx) return;

            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                    datasets: [{
                        label: 'Received',
                        data: data.map(item => item.received || 0),
                        borderColor: '#28a745',
                        backgroundColor: 'rgba(40, 167, 69, 0.1)',
                        tension: 0.4
                    }, {
                        label: 'Dispatched',
                        data: data.map(item => item.dispatched || 0),
                        borderColor: '#dc3545',
                        backgroundColor: 'rgba(220, 53, 69, 0.1)',
                        tension: 0.4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: true
                        }
                    }
                }
            });
        }

        function initDailyChart(data) {
            const ctx = document.getElementById('dailyChart');
            if (!ctx) return;

            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: data.map(item => new Date(item.date).toLocaleDateString()),
                    datasets: [{
                        label: 'Received',
                        data: data.map(item => item.received || 0),
                        backgroundColor: '#28a745'
                    }, {
                        label: 'Dispatched',
                        data: data.map(item => item.dispatched || 0),
                        backgroundColor: '#dc3545'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false
                }
            });
        }

        function initWeeklyChart(data) {
            const ctx = document.getElementById('weeklyChart');
            if (!ctx) return;

            new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: ['Received', 'Dispatched'],
                    datasets: [{
                        data: [
                            data.reduce((sum, item) => sum + (item.received_count || 0), 0),
                            data.reduce((sum, item) => sum + (item.dispatched_count || 0), 0)
                        ],
                        backgroundColor: ['#28a745', '#dc3545']
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false
                }
            });
        }
    </script>
@endsection