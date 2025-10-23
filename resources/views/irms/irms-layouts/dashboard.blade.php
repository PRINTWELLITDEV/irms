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
                                                <div class="info-box-text">
                                                    Bookmarks
                                                </div>
                                                <div class="info-box-number">
                                                    10,000
                                                </div>
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
                                                <div class="info-box-text">
                                                    Bookmarks
                                                </div>
                                                <div class="info-box-number">
                                                    10,000
                                                </div>
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
                                                <div class="info-box-text">
                                                    Bookmarks
                                                </div>
                                                <div class="info-box-number">
                                                    10,000
                                                </div>
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
                                                <div class="info-box-text">
                                                    Users
                                                </div>
                                                <div class="info-box-number">
                                                    10,000
                                                </div>
                                                <div class="progress">

                                                    <div class="progress-bar bg-success" style="width: 10%;"></div>
                                                    <div class="progress-bar bg-warning" style="width: 40%;"></div>
                                                    <div class="progress-bar bg-primary" style="width: 50%;"></div>
                                                    <span class="progress-description">Hellooooo</span>
                                                </div>
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
                                                    <div class="card-header bg-dark bg-gradient text-white">Monthly Cap
                                                        Report</div>
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
                                                <i class="bi bi-bookmark-fill"></i>
                                            </div>
                                            <div class="info-box-content">
                                                <div class="info-box-text">
                                                    Bookmarks
                                                </div>
                                                <div class="info-box-number">
                                                    10,000
                                                </div>
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
                                                <div class="info-box-text">
                                                    Bookmarks
                                                </div>
                                                <div class="info-box-number">
                                                    10,000
                                                </div>
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
                                                <div class="info-box-text">
                                                    Bookmarks
                                                </div>
                                                <div class="info-box-number">
                                                    10,000
                                                </div>
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
                                                <div class="info-box-text">
                                                    Users
                                                </div>
                                                <div class="info-box-number">
                                                    10,000
                                                </div>
                                                <div class="progress">

                                                    <div class="progress-bar bg-success" style="width: 10%;"></div>
                                                    <div class="progress-bar bg-warning" style="width: 40%;"></div>
                                                    <div class="progress-bar bg-primary" style="width: 50%;"></div>
                                                    <span class="progress-description">Hellooooo</span>
                                                </div>
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
                                                    <div class="card-header bg-dark bg-gradient text-white">Monthly Cap
                                                        Report</div>
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

                    @elseif(isset($level) && $level == 3)
                        <div class="card card-primary">
                            <div class="card-body">

                                <div class="row mb-3 wrap">

                                    <div class="col-sm-12 col-md-3 text-center text-white align-items-center">
                                        <div class="info-box text-bg-success bg-gradient">
                                            <div class="info-box-icon">
                                                <i class="bi bi-bookmark-fill"></i>
                                            </div>
                                            <div class="info-box-content">
                                                <div class="info-box-text">
                                                    Bookmarks
                                                </div>
                                                <div class="info-box-number">
                                                    10,000
                                                </div>
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
                                                <div class="info-box-text">
                                                    Bookmarks
                                                </div>
                                                <div class="info-box-number">
                                                    10,000
                                                </div>
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
                                                <div class="info-box-text">
                                                    Bookmarks
                                                </div>
                                                <div class="info-box-number">
                                                    10,000
                                                </div>
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
                                                <div class="info-box-text">
                                                    Users
                                                </div>
                                                <div class="info-box-number">
                                                    10,000
                                                </div>
                                                <div class="progress">

                                                    <div class="progress-bar bg-success" style="width: 10%;"></div>
                                                    <div class="progress-bar bg-warning" style="width: 40%;"></div>
                                                    <div class="progress-bar bg-primary" style="width: 50%;"></div>
                                                    <span class="progress-description">Hellooooo</span>
                                                </div>
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
                                                    <div class="card-header bg-dark bg-gradient text-white">Monthly Cap
                                                        Report</div>
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
                                                <div class="info-box-text">
                                                    Bookmarks
                                                </div>
                                                <div class="info-box-number">
                                                    10,000
                                                </div>
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
                                                <div class="info-box-text">
                                                    Bookmarks
                                                </div>
                                                <div class="info-box-number">
                                                    10,000
                                                </div>
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
                                                <div class="info-box-text">
                                                    Bookmarks
                                                </div>
                                                <div class="info-box-number">
                                                    10,000
                                                </div>
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
                                                <div class="info-box-text">
                                                    Users
                                                </div>
                                                <div class="info-box-number">
                                                    10,000
                                                </div>
                                                <div class="progress">

                                                    <div class="progress-bar bg-success" style="width: 10%;"></div>
                                                    <div class="progress-bar bg-warning" style="width: 40%;"></div>
                                                    <div class="progress-bar bg-primary" style="width: 50%;"></div>
                                                    <span class="progress-description">Hellooooo</span>
                                                </div>
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
                                                    <div class="card-header bg-dark bg-gradient text-white">Monthly Cap
                                                        Report</div>
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
                    </div>

                </div>
            </div>
        </div>
    </div>
</main>

@endsection
