@extends('irms/irms-partials.app')

@section('title', 'IRMS Dashboard')

@section('content')
    <main class="app-main">
        <div class="app-content-wrapper">
            <div class="app-content-header">
                <div class="container-fluid">
                    <h1>Welcome to IRMS Dashboard</h1>
                </div>
            </div>

            <div class="app-content">
                <div class="container-fluid">
                    @if(config('app.env') !== 'production')

                        @if (isset($level) && $level == 1)
                            <div class="card card-primary">
                                <div class="card-body">
                                    <div class="row mb-3 wrap">

                                        <div class="col-sm-12 col-md-3 text-center text-white align-items-center">
                                            <div class="info-box text-bg-success bg-gradient">
                                                <div class="info-box-icon">
                                                    <i class="bi bi-bookmark-fill"></i>
                                                </div>
                                                <div class="info-box-content">
                                                    <div class="info-box-text">sa</div>
                                                    <div class="info-box-number">10,000</div>
                                                    <div class="progress">
                                                        <div class="progress-bar progress-bar-striped progress-bar-animated"
                                                            style="width:70%"></div>
                                                    </div>
                                                    <span class="progress-description">Hellooooo</span>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-sm-12 col-md-3 text-center align-items-center">
                                            <div class="info-box text-bg-warning bg-gradient">
                                                <div class="info-box-icon">
                                                    <i class="bi bi-bookmark-fill"></i>
                                                </div>
                                                <div class="info-box-content">
                                                    <div class="info-box-text">Bookmarks</div>
                                                    <div class="info-box-number">10,000</div>
                                                    <div class="progress">
                                                        <div class="progress-bar" style="width:70%"></div>
                                                    </div>
                                                    <span class="progress-description">Hellooooo</span>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-sm-12 col-md-3 text-center text-white align-items-center">
                                            <div class="info-box text-bg-primary bg-gradient">
                                                <div class="info-box-icon">
                                                    <i class="bi bi-bookmark-fill"></i>
                                                </div>
                                                <div class="info-box-content">
                                                    <div class="info-box-text">Bookmarks</div>
                                                    <div class="info-box-number">10,000</div>
                                                    <div class="progress">
                                                        <div class="progress-bar" style="width:70%"></div>
                                                    </div>
                                                    <span class="progress-description">Hellooooo</span>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-sm-12 col-md-3 text-center">
                                            <div class="info-box text-bg-danger bg-gradient">
                                                <div class="info-box-icon">
                                                    <i class="bi bi-person-fill"></i>
                                                </div>
                                                <div class="info-box-content">
                                                    <div class="info-box-text">Users</div>
                                                    <div class="info-box-number">10,000</div>
                                                    <div class="progress">
                                                        <div class="progress-bar bg-success" style="width: 10%;"></div>
                                                        <div class="progress-bar bg-warning" style="width: 40%;"></div>
                                                        <div class="progress-bar bg-primary" style="width: 50%;"></div>
                                                    </div>
                                                    <span class="progress-description">Hellooooo</span>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row mb-3">
                                            <div class="row m-0">
                                                <div class="col-sm-12 col-md-7 mb-1">
                                                    <div class="card">
                                                        <div class="card-header bg-success bg-gradient text-white">
                                                            <h3 class="card-title">Available Occupancy per Sites</h3>
                                                        </div>
                                                        <div class="card-body">
                                                            <canvas id="chart_bar" style="height: 190px;"></canvas>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-sm-12 col-md-5 mb-4">
                                                    <div class="card">
                                                        <div class="card-header bg-danger bg-gradient text-white">
                                                            <h3 class="card-title">Active User per Site</h3>
                                                        </div>
                                                        <div class="card-body">
                                                            <canvas id="chart_pie" style="height: 190px;"></canvas>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row mb-3">
                                            <div class="col-12 col-md-5">
                                                <div class="card shadow-md">
                                                    <div class="card-header bg-primary bg-gradient text-white">
                                                        <h3 class="card-title">Total Receiving & Dispatching per Week</h3>
                                                    </div>
                                                    <div class="card-body">
                                                        <canvas id="chart_line" style="height: 190px;"></canvas>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-12 col-md-7">
                                                <div class="card">
                                                    <div class="card">
                                                        <div class="card-header bg-dark bg-gradient text-white">Monthly Cap Report
                                                        </div>
                                                        <div class="card-body">
                                                            <table class="table"></table>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>

                        @elseif(isset($level) && $level == 2)

                            <div class="card card-primary">
                                <div class="card-body">
                                    <div class="row mb-3 wrap">

                                        <div class="col-sm-12 col-md-3 text-center text-white align-items-center">
                                            <div class="info-box text-bg-success bg-gradient">
                                                <div class="info-box-icon">
                                                    <i class="bi bi-grid-3x3"></i>
                                                </div>
                                                <div class="info-box-content">
                                                    <div class="info-box-text">Overall Rack Utilization %</div>
                                                    <div class="info-box-number">58%</div>
                                                    <div class="progress">
                                                        <div class="progress-bar progress-bar-striped progress-bar-animated"
                                                            style="width:70%"></div>
                                                    </div>
                                                    <span class="progress-description">234/512</span>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-sm-12 col-md-3 text-center align-items-center">
                                            <div class="info-box text-bg-warning bg-gradient">
                                                <div class="info-box-icon">
                                                    <i class="bi bi-box-seam"></i>
                                                </div>
                                                <div class="info-box-content">
                                                    <div class="info-box-text">Total Goods (Active Inventory)</div>
                                                    <div class="info-box-number">213 Total Stocks</div>
                                                    <div class="progress">
                                                        <div class="progress-bar" style="width:70%"></div>
                                                    </div>
                                                    <span class="progress-description">Hellooooo</span>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-sm-12 col-md-3 text-center text-white align-items-center">
                                            <div class="info-box text-bg-primary bg-gradient">
                                                <div class="info-box-icon">
                                                    <i class="bi bi-truck"></i>
                                                </div>
                                                <div class="info-box-content">
                                                    <div class="info-box-text">Goods Movement (Today)</div>
                                                    <div class="info-box-number">10,000</div>
                                                    <div class="progress">
                                                        <div class="progress-bar" style="width:70%"></div>
                                                    </div>
                                                    <span class="progress-description">Hellooooo</span>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-sm-12 col-md-3 text-center">
                                            <div class="info-box text-bg-danger bg-gradient">
                                                <div class="info-box-icon">
                                                    <i class="bi bi-person-fill"></i>
                                                </div>
                                                <div class="info-box-content">
                                                    <div class="info-box-text">Users</div>
                                                    <div class="info-box-number">10,000</div>
                                                    <div class="progress">
                                                        <div class="progress-bar bg-success" style="width: 10%;"></div>
                                                        <div class="progress-bar bg-warning" style="width: 40%;"></div>
                                                        <div class="progress-bar bg-primary" style="width: 50%;"></div>
                                                    </div>
                                                    <span class="progress-description">Hellooooo</span>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row my-2">

                                            <div class="col-sm-12 col-md-6 mb-1">
                                                <div class="card">
                                                    <div class="card-content">
                                                        <div class="card-header bg-success bg-gradient text-white">
                                                            <h3 class="card-title">Goods Movement Trend</h3>
                                                        </div>
                                                        <div class="card-body">
                                                            <canvas id="chart_line" style="height: 190px;"></canvas>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-sm-12 col-md-6 mb-1">
                                                <div class="card">
                                                    <div class="card-content">
                                                        <div class="card-header bg-danger bg-gradient text-white">
                                                            <h3 class="card-title">Rack Utilization by Warehouse</h3>
                                                        </div>
                                                        <div class="card-body">
                                                            <canvas id="stacked_bar" style="height: 190px;"></canvas>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>

                                        <div class="row mb-1">
                                            <div class="col-12 col-md-6 mb-2">
                                                <div class="card shadow-md">
                                                    <div class="card-header bg-primary bg-gradient text-white">
                                                        <h3 class="card-title">Rack Map</h3>
                                                    </div>
                                                    <div class="card-body">
                                                        <div id="chart-heatmap"></div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-12 col-md-6 mb-2">
                                                <div class="card">
                                                    <div class="card-header bg-warning bg-gradient">
                                                        <h3 class="card-title">Team Activity</h3>
                                                    </div>
                                                    <div class="card-body" style="max-height: 220px;">
                                                        <div class="table-responsive table-view">
                                                            <table
                                                                class="table-striped table-bordered table-hover align-middle display"
                                                                id="rackTable">
                                                                <thead>
                                                                    <tr>
                                                                        <th>Users</th>
                                                                        <th>Activity</th>
                                                                        <th>Time</th>
                                                                        <th>Date</th>
                                                                    </tr>
                                                                </thead>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>

                        @elseif(isset($level) && $level == 3)

                            <div class="card card-primary">
                                <div class="card-body">
                                    <div class="row mb-3 wrap">

                                        <div class="col-sm-12 col-md-3 text-center text-white align-items-center">
                                            <div class="info-box text-bg-success bg-gradient">
                                                <div class="info-box-icon">
                                                    <i class="bi bi-building"></i>
                                                </div>
                                                <div class="info-box-content">
                                                    <div class="info-box-text">Warehouse Occupancy</div>
                                                    <div class="info-box-number">Still Development</div>
                                                    <div class="progress">
                                                        <div class="progress-bar progress-bar-striped progress-bar-animated"
                                                            style="width:70%"></div>
                                                    </div>
                                                    <span class="progress-description">Hellooooo</span>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-sm-12 col-md-3 text-center align-items-center">
                                            <div class="info-box text-bg-warning bg-gradient">
                                                <div class="info-box-icon">
                                                    <i class="bi bi-bookmark-fill"></i>
                                                </div>
                                                <div class="info-box-content">
                                                    <div class="info-box-text">Goods Received Today</div>
                                                    <div class="info-box-number">10,000</div>
                                                    <div class="progress">
                                                        <div class="progress-bar" style="width:70%"></div>
                                                    </div>
                                                    <span class="progress-description">Hellooooo</span>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-sm-12 col-md-3 text-center text-white align-items-center">
                                            <div class="info-box text-bg-primary bg-gradient">
                                                <div class="info-box-icon">
                                                    <i class="bi bi-bookmark-fill"></i>
                                                </div>
                                                <div class="info-box-content">
                                                    <div class="info-box-text">Goods Dispatched Today</div>
                                                    <div class="info-box-number">10,000</div>
                                                    <div class="progress">
                                                        <div class="progress-bar" style="width:70%"></div>
                                                    </div>
                                                    <span class="progress-description">Hellooooo</span>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-sm-12 col-md-3 text-center">
                                            <div class="info-box text-bg-danger bg-gradient">
                                                <div class="info-box-icon">
                                                    <i class="bi bi-person-fill"></i>
                                                </div>
                                                <div class="info-box-content">
                                                    <div class="info-box-text">Pending Transactions</div>
                                                    <div class="info-box-number">10,000</div>
                                                    <div class="progress">
                                                        <div class="progress-bar bg-success" style="width: 10%;"></div>
                                                        <div class="progress-bar bg-warning" style="width: 40%;"></div>
                                                        <div class="progress-bar bg-primary" style="width: 50%;"></div>
                                                    </div>
                                                    <span class="progress-description">Hellooooo</span>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row mb-3">
                                            <div class="col-sm-12 col-md-7 mb-1">
                                                <div class="card">
                                                    <div class="card-header bg-success bg-gradient text-white">
                                                        <h3 class="card-title">Daily Goods Movement Today</h3>
                                                    </div>
                                                    <div class="card-body">
                                                        <canvas id="chart_bar" style="height: 250px;"></canvas>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-sm-12 col-md-5 mb-4">
                                                <div class="card">
                                                    <div class="card-header bg-danger bg-gradient text-white">
                                                        <h3 class="card-title">Rack Utilization</h3>
                                                    </div>
                                                    <div class="card-body">
                                                        <canvas id="chart_pie" style="height: 150px;"></canvas>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row mb-3">
                                            <div class="col-12 col-md-6">
                                                <div class="card shadow-md">
                                                    <div class="card-header bg-primary bg-gradient text-white">
                                                        <h3 class="card-title">Total Receiving & Dispatching per Week</h3>
                                                    </div>
                                                    <div class="card-body">
                                                        <canvas id="chart_line" style="height: 190px;"></canvas>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-12 col-md-6">
                                                <div class="card">
                                                    <div class="card-header bg-warning bg-gradient">
                                                        <h3 class="card-title">Monthly Cap Report</h3>
                                                    </div>
                                                    <div class="card-body">
                                                        <div class="table-responsive table-view">
                                                            <table
                                                                class="table-striped table-bordered table-hover align-middle display"
                                                                id="activityUsers">
                                                                <thead>
                                                                    <tr>
                                                                        <th>Users</th>
                                                                        <th>Activity</th>
                                                                        <th>Time</th>
                                                                        <th>Date</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    <tr>
                                                                        <td id="view-rack-warehouse"></td>
                                                                        <td id="view-rack-baynum"></td>
                                                                        <td id="view-rack-location"></td>
                                                                        <td id="view-rack-description"></td>
                                                                    </tr>
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

                        @else

                            <div class="card card-primary">
                                <div class="card-body">

                                    <div class="row mb-3 wrap">

                                        <div class="col-sm-12 col-md-3 text-center text-white align-items-center">
                                            <div class="info-box text-bg-success bg-gradient">
                                                <div class="info-box-icon">
                                                    <i class="bi bi-bookmark-fill"></i>
                                                </div>
                                                <div class="info-box-content">
                                                    <div class="info-box-text">Bookmarks</div>
                                                    <div class="info-box-number">10,000</div>
                                                    <div class="progress">
                                                        <div class="progress-bar progress-bar-striped progress-bar-animated"
                                                            style="width:70%"></div>
                                                    </div>
                                                    <span class="progress-description">Hellooooo</span>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-sm-12 col-md-3 text-center align-items-center">
                                            <div class="info-box text-bg-warning bg-gradient">
                                                <div class="info-box-icon">
                                                    <i class="bi bi-bookmark-fill"></i>
                                                </div>
                                                <div class="info-box-content">
                                                    <div class="info-box-text">Bookmarks</div>
                                                    <div class="info-box-number">10,000</div>
                                                    <div class="progress">
                                                        <div class="progress-bar" style="width:70%"></div>
                                                    </div>
                                                    <span class="progress-description">Hellooooo</span>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-sm-12 col-md-3 text-center text-white align-items-center">
                                            <div class="info-box text-bg-primary bg-gradient">
                                                <div class="info-box-icon">
                                                    <i class="bi bi-bookmark-fill"></i>
                                                </div>
                                                <div class="info-box-content">
                                                    <div class="info-box-text">Bookmarks</div>
                                                    <div class="info-box-number">10,000</div>
                                                    <div class="progress">
                                                        <div class="progress-bar" style="width:70%"></div>
                                                    </div>
                                                    <span class="progress-description">Hellooooo</span>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-sm-12 col-md-3 text-center">
                                            <div class="info-box text-bg-danger bg-gradient">
                                                <div class="info-box-icon">
                                                    <i class="bi bi-person-fill"></i>
                                                </div>
                                                <div class="info-box-content">
                                                    <div class="info-box-text">Users</div>
                                                    <div class="info-box-number">10,000</div>
                                                    <div class="progress">
                                                        <div class="progress-bar bg-success" style="width: 10%;"></div>
                                                        <div class="progress-bar bg-warning" style="width: 40%;"></div>
                                                        <div class="progress-bar bg-primary" style="width: 50%;"></div>
                                                    </div>
                                                    <span class="progress-description">Hellooooo</span>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row mb-3">
                                            <div class="row m-0">
                                                <div class="col-sm-12 col-md-7 mb-1">
                                                    <div class="card">
                                                        <div class="card-header bg-success bg-gradient text-white">
                                                            <h3 class="card-title">Available Occupancy per Sites</h3>
                                                        </div>
                                                        <div class="card-body">
                                                            <canvas id="chart_bar" style="height: 190px;"></canvas>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-sm-12 col-md-5 mb-4">
                                                    <div class="card">
                                                        <div class="card-header bg-danger bg-gradient text-white">
                                                            <h3 class="card-title">Active User per Site</h3>
                                                        </div>
                                                        <div class="card-body">
                                                            <canvas id="chart_pie" style="height: 190px;"></canvas>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row mb-3">
                                            <div class="col-12 col-md-5">
                                                <div class="card shadow-md">
                                                    <div class="card-header bg-primary bg-gradient text-white">
                                                        <h3 class="card-title">Total Receiving & Dispatching per Week</h3>
                                                    </div>
                                                    <div class="card-body">
                                                        <canvas id="chart_line" style="height: 190px;"></canvas>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-12 col-md-7">
                                                <div class="card">
                                                    <div class="card">
                                                        <div class="card-header bg-dark bg-gradient text-white">Monthly Cap Report
                                                        </div>
                                                        <div class="card-body">
                                                            <table class="table"></table>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    </div>

                                </div>
                            </div>

                        @endif
                    @else
                        <div class="col-12">
                            <div class="alert alert-info m-4">
                                <h5 class="alert-heading">IRMS Dashboard</h5>
                                <p>Welcome to the IRMS (Inventory and Rack Management System) Dashboard. This platform is
                                    designed to help you efficiently manage inventory and rack locations across various sites.
                                </p>
                                <hr>
                                <div class="alert alert-warning mt-3">
                                    <strong>Note:</strong> This dashboard is currently under development. Some features and data
                                    may not be final.<br>
                                    <span class="text-success">You can still use the system for your inventory and rack
                                        management needs.</span>
                                </div>
                                <p class="mb-0">For assistance or more information, please contact the system administrator.</p>
                            </div>
                        </div>
                    @endif

                </div>
            </div>
        </div>
    </main>
@endsection
