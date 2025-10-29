@extends('irms.irms-partials.app')

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
                <div class="card card-primary">
                    @if(config('app.env') !== 'production')
                    <div class="card-body">
                        <div class="row mb-3 wrap">
                            <div class="col-3 text-center text-white align-items-center">
                                <div class="small-box bg-success">
                                    <div class="inner">
                                        <h3>18%</h3>
                                        <p>Available Vacancy on PWPC</p>
                                    </div>
                                    <div class="icon">
                                        <i class="ion ion-bag"></i>
                                    </div>
                                    <a href="#" class="small-box-footer">
                                        <i class="fas fa-arrow-circle-right"></i>
                                    </a>
                                </div>
                            </div>
                            <div class="col-3 text-center text-white align-items-center">
                                <div class="small-box bg-info">
                                    <div class="inner">
                                        <h3>27</h3>
                                        <p>Dispatched Goods</p>
                                    </div>
                                    <div class="icon">
                                        <i class="ion ion-bag"></i>
                                    </div>
                                    <a href="#" class="small-box-footer">
                                        <i class="fas fa-arrow-circle-right"></i>
                                    </a>
                                </div>
                            </div>
                            <div class="col-3 text-center text-white align-items-center">
                                <div class="small-box bg-danger">
                                    <div class="inner">
                                        <h3>150</h3>
                                        <p>Received Goods</p>
                                    </div>
                                    <div class="icon">
                                        <i class="ion ion-bag"></i>
                                    </div>
                                    <a href="#" class="small-box-footer">
                                        <i class="fas fa-arrow-circle-right"></i>
                                    </a>
                                </div>
                            </div>
                            <div class="col-3 text-center">
                                <div class="small-box bg-warning">
                                    <div class="inner">
                                        <h3>227</h3>
                                        <p>Users</p>
                                    </div>
                                    <div class="icon">
                                        <i class="ion ion-bag"></i>
                                    </div>
                                    <a href="#" class="small-box-footer">
                                        <i class="fas fa-arrow-circle-right"></i>
                                    </a>
                                </div>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-8">
                                <div class="card">
                                    <div class="card-header bg-primary text-white">Monthly Cap Report</div>
                                    <div class="card-body">
                                        <div class="d-flex">
                                            <div class="d-flex flex-column">
                                                <span class="fw-bold text-lg">100</span>
                                                <span>Meet Goods</span>
                                            </div>
                                            <div class="ms-auto d-flex flex-column text-end">
                                                <span class="text-success">
                                                    <i class="fas fa-arrow-up"></i>
                                                    5%
                                                </span>
                                                <span class="text-muted">
                                                    Since Last Week
                                                </span>
                                            </div>
                                        </div>
                                        <div class="position-relative mb-4">
                                            <div id="container-chartjs">
                                                <canvas id="chart_line2" style="height:300px; width:100%;"></canvas>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="card">
                                    <div class="card-header bg-secondary text-white">
                                        <h3 class="card-title">Summary</h3>
                                    </div>
                                    <div class="card-body">
                                        <ul class="list-unstyled mb-0">
                                            <li><strong>Users:</strong> 42</li>
                                            <li><strong>Warehouses:</strong> 12</li>
                                            <li><strong>Rack Locations:</strong> 128</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-sm-12 col-md-3">
                                <div class="card card-dark">
                                    <div class="card-header">
                                        <h3 class="card-title">Available Occupancy per Sites</h3>
                                    </div>
                                    <div class="card-body">
                                        <canvas id="chart_bar" style="height: 250px;"></canvas>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-12 col-md-3">
                                <div class="card card-danger">
                                    <div class="card-header">
                                        <h3 class="card-title">User per Site</h3>
                                    </div>
                                    <div class="card-body">
                                        <canvas id="chart_pie" style="height: 250px;"></canvas>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 col-md-3">
                                <div class="card shadow-md">
                                    <div class="card-header bg-info text-white">
                                        <h3 class="card-title">Incoming & Outgoing Shipments per Week</h3>
                                    </div>
                                    <div class="card-body">
                                        <canvas id="chart_line" style="height: 250px;"></canvas>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 col-md-3">
                                <div class="card">
                                    <div class="card-header bg-warning text-white">
                                        <h3 class="card-title">Transaction per Month</h3>
                                    </div>
                                    <div class="card-body">
                                        <canvas id="chart2" style="height: 250px;"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @else
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
                    @endif

                </div>
            </div>
        </div>
    </div>
</main>


@endsection
